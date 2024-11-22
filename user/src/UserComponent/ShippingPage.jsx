import React, { useState, useEffect } from 'react';
import axios from 'axios';
import './ShippingPage.css';

const SuccessAlert = ({ message }) => (
  <div style={{
    padding: '1rem',
    backgroundColor: '#4CAF50',
    color: 'white',
    borderRadius: '4px',
    marginBottom: '1rem'
  }}>
    <h4 style={{ margin: '0 0 0.5rem 0' }}>Success!</h4>
    <p style={{ margin: 0 }}>{message}</p>
  </div>
);

const ErrorAlert = ({ message }) => (
  <div style={{
    padding: '1rem',
    backgroundColor: '#f44336',
    color: 'white',
    borderRadius: '4px',
    marginBottom: '1rem'
  }}>
    <h4 style={{ margin: '0 0 0.5rem 0' }}>Error</h4>
    <p style={{ margin: 0 }}>{message}</p>
  </div>
);

const OrderSuccessModal = ({ onClose, onContinue }) => (
  <div className="modal">
    <div className="modal-content">
      <h2>Order Placed Successfully!</h2>
      <p>Thank you for your purchase. Your order has been placed.</p>
      <div className="modal-actions">
        <button className="btn btn-primary" onClick={onContinue}>
          Continue Shopping
        </button>
        <button className="btn btn-secondary" onClick={onClose}>
          Close
        </button>
      </div>
    </div>
  </div>
);

const ShippingPage = () => {
  const [formData, setFormData] = useState({
    name: '',
    address: '',
    pin: '',
    state: '',
    district: '',
    paymentMethod: ''
  });
  const [cartItems, setCartItems] = useState([]);
  const [userData, setUserData] = useState(null);
  const [showSuccessAlert, setShowSuccessAlert] = useState(false);
  const [errorMessage, setErrorMessage] = useState('');
  const [isLoading, setIsLoading] = useState(false);
  const [showOrderSuccessModal, setShowOrderSuccessModal] = useState(false);

  const paymentMethods = [
    // { value: 'upi', label: 'UPI' },
    // { value: 'debit_card', label: 'Debit Card' },
    { value: 'credit_card', label: 'Credit Card' }, 
    // { value: 'cod', label: 'Cash on Delivery (COD)' }
  ];

  useEffect(() => {
    // Load cart data
    const cartData = localStorage.getItem('cart');
    if (cartData) {
      try {
        const parsedCartData = JSON.parse(cartData);
        setCartItems(parsedCartData);
      } catch (error) {
        console.error('Error parsing cart data:', error);
        setCartItems([]);
      }
    }

    // Load user data and auto-populate name
    const userAuthData = sessionStorage.getItem('userAuth');
    if (userAuthData) {
      try {
        const parsedUserData = JSON.parse(userAuthData);
        setUserData(parsedUserData);
        // Auto-populate name from user data
        if (parsedUserData?.user?.data?.name) {
          setFormData(prevData => ({
            ...prevData,
            name: parsedUserData.user.data.name
          }));
        }
      } catch (error) {
        console.error('Error parsing user data:', error);
      }
    }
  }, []);

  const handleChange = (e) => {
    setFormData({
      ...formData,
      [e.target.name]: e.target.value
    });
    setErrorMessage('');
  };

  const calculateTotalPrice = () => {
    return cartItems.reduce((total, item) => {
      const price = parseFloat(item.price || 0);
      const quantity = parseInt(item.quantity, 10) || 1;
      return total + (price * quantity);
    }, 0);
  };

  const handleCloseModal = () => {
    setShowOrderSuccessModal(false);
  };

  const handleContinueShopping = () => {
    setShowOrderSuccessModal(false);
    window.location.href = '/';
  };

  const createOrder = async (total, paymentId) => {
    try {
      const userAuth = JSON.parse(sessionStorage.getItem('userAuth'));
      const customerId = userAuth?.user?.data?.user_id;

      if (!customerId) {
        throw new Error('Customer ID is required');
      }

      const orderData = {
        customer_id: customerId,
        name: userData?.user?.data?.name || formData.name, // Use name from userData first
        address: {
          street: formData.address,
          city: formData.district,
          state: formData.state,
          country: 'India',
          postal_code: formData.pin
        },
        items: cartItems.map(item => ({
          product_id: item.id,
          quantity: parseInt(item.quantity, 10),
          price: parseFloat(item.price || 0)
        })),
        payment_method: formData.paymentMethod,
        total_price: total,
        payment_id: paymentId
      };

      const orderResponse = await axios.post(
        'http://127.0.0.1:8000/api/orders/',
        orderData,
        {
          headers: {
            'Content-Type': 'application/json'
          }
        }
      );

      if (orderResponse.status === 200 || orderResponse.status === 201) {
        setShowSuccessAlert(true);
        setShowOrderSuccessModal(true);
        localStorage.removeItem('cart');
      } else {
        throw new Error('Failed to create order');
      }
    } catch (error) {
      console.error('Error creating order:', error);
      setErrorMessage('An error occurred while creating your order. Please contact customer support.');
      throw error;
    }
  };

  const paymentHandler = async (e) => {
    e.preventDefault();
    setIsLoading(true);
    setErrorMessage('');

    // For COD, directly create order without Razorpay
    if (formData.paymentMethod === 'cod') {
      try {
        await createOrder(calculateTotalPrice(), 'COD');
        setIsLoading(false);
      } catch (error) {
        setIsLoading(false);
        setErrorMessage('Failed to create order. Please try again.');
      }
      return;
    }

    // For online payments, use Razorpay
    const total = calculateTotalPrice();
    const options = {
      key: 'rzp_test_VOqJ2cwENG1Z0N',
      amount: total * 100,
      name: 'PlugCrux',
      description: 'Purchase Description',
      handler: async function (response) {
        try {
          await createOrder(total, response.razorpay_payment_id);
        } catch (error) {
          console.error('Error processing payment and creating order:', error);
          setErrorMessage('Payment failed. Please try again or contact support.');
        } finally {
          setIsLoading(false);
        }
      },
      prefill: {
        name: userData?.user?.data?.name || '',
        email: userData?.user?.data?.email || '',
      },
      notes: {
        address: `${formData.address}, ${formData.district}, ${formData.state}, India`,
      },
      theme: {
        color: '#9D50BB',
      },
    };

    const rzp1 = new window.Razorpay(options);
    rzp1.on('payment.failed', function (response) {
      console.error('Payment failed:', response.error);
      setErrorMessage('Payment failed. Please try again or contact support.');
      setIsLoading(false);
    });

    rzp1.open();
  };

  return (
    <div className="shipping-container">
      {showSuccessAlert && (
        <SuccessAlert message="Your order has been successfully placed. Thank you for shopping with us!" />
      )}
      {errorMessage && <ErrorAlert message={errorMessage} />}
      
      {showOrderSuccessModal && (
        <OrderSuccessModal 
          onClose={handleCloseModal} 
          onContinue={handleContinueShopping} 
        />
      )}
      
      <div className="card1">
        <h2>Shipping Details</h2>
        <form onSubmit={paymentHandler}>
          <div className="form-group">
            <label>Name:</label>
            <input 
              type="text" 
              name="name" 
              value={formData.name} 
              onChange={handleChange} 
              className="form-control" 
              placeholder="Enter your name" 
              required 
              readOnly={!!userData?.user?.data?.name}
            />
          </div>

          <div className="form-group">
            <label>Address:</label>
            <textarea
              name="address"
              value={formData.address}
              onChange={handleChange}
              className="form-control"
              placeholder="Enter your address"
              required
            ></textarea>
          </div>

          <div className="form-group">
            <label>Pin Code:</label>
            <input
              type="text"
              name="pin"
              value={formData.pin}
              onChange={handleChange}
              className="form-control"
              placeholder="Enter your pin code"
              required
            />
          </div>

          <div className="form-group">
            <label>State:</label>
            <input
              type="text"
              name="state"
              value={formData.state}
              onChange={handleChange}
              className="form-control"
              placeholder="Enter your state"
              required
            />
          </div>

          <div className="form-group">
            <label>District:</label>
            <input
              type="text"
              name="district"
              value={formData.district}
              onChange={handleChange}
              className="form-control"
              placeholder="Enter your district"
              required
            />
          </div>

          <div className="form-group">
            <label>Payment Method:</label>
            <div className="payment-options">
              {paymentMethods.map(method => (
                <label key={method.value}>
                  <input 
                    type="radio" 
                    name="paymentMethod" 
                    value={method.value} 
                    onChange={handleChange} 
                    required 
                  />
                  {method.label}
                </label>
              ))}
            </div>
          </div>

          <div className="order-summary">
            <h3>Order Summary</h3>
            {cartItems.map(item => (
              <div key={item.id} className="order-item">
                <span>{item.name}</span>
                <span>${parseFloat(item.price)} x {parseInt(item.quantity)}</span>
              </div>
            ))}
            <div className="total">
              <strong>Total:</strong> ${calculateTotalPrice()}
            </div>
          </div>

          <button 
            type="submit" 
            className="btn btn-primary"
            disabled={isLoading}
          >
            {isLoading ? 'Placing Order...' : 'Place Order'}
          </button>
        </form>
      </div>
    </div>
  );
};

export default ShippingPage;