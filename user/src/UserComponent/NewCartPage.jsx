import React, { useState, useEffect } from 'react';
import { Link, useNavigate } from 'react-router-dom'; // Import useNavigate for routing
import { Trash2 } from 'lucide-react';

const NewCartPage = () => {
  const [cartItems, setCartItems] = useState([]);
  const navigate = useNavigate(); // useNavigate for routing to shipping page

  useEffect(() => {
    const storedCart = JSON.parse(localStorage.getItem('cart')) || [];
    setCartItems(storedCart);
  }, []);

  const removeItem = (index) => {
    const updatedCart = cartItems.filter((_, i) => i !== index);
    setCartItems(updatedCart);
    localStorage.setItem('cart', JSON.stringify(updatedCart));
  };

  const calculateTotal = () => {
    return cartItems.reduce((total, item) => total + item.price * item.quantity, 0);
  };

  const handleProceedToCheckout = () => {
    const productIds = cartItems.map((item) => item.id); // Extract product IDs
    localStorage.setItem('productIds', JSON.stringify(productIds)); // Store product IDs in localStorage
    navigate('/shipping'); // Navigate to the shipping page
  };

  return (
    
    <div className="container mt-5">
  
      <h3 className="mb-4 p-xl-4">My Cart</h3>
      {cartItems.length === 0 ? (
        <p>Your cart is empty. <Link to="/">Continue shopping</Link></p>
      ) : (
        <>
          <div className="table-responsive">
            <table className="table">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Product</th>
                  <th>Price</th>
                
                  <th>Subtotal</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                {cartItems.map((item, index) => (
                  <tr key={index}>
                    <td>{item.id}</td>
                    <td>{item.name}</td>
                    <td>${item.price}</td>
                    <td>${item.price * item.quantity}</td>
                    <td>
                      <button className="btn btn-danger btn-sm" onClick={() => removeItem(index)}>
                        <Trash2 size={16} />
                      </button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
          <div className="text-end">
            <h4>Total: ${calculateTotal()}</h4>
            <button className="btn btn-primary mt-3" onClick={handleProceedToCheckout}>
              Proceed to Checkout
            </button>
          </div>
        </>
      )}
    
    </div>
  );
};

export default NewCartPage;
