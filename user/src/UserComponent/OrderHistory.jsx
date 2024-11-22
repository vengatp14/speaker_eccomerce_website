import React, { useState, useEffect } from 'react';
import { Calendar, Package, MapPin, CreditCard, Clock, Download } from 'lucide-react';

const OrderHistory = () => {
  const [orders, setOrders] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [expandedOrders, setExpandedOrders] = useState({});

  useEffect(() => {
    const fetchOrders = async () => {
      try {
        const userAuthStr = sessionStorage.getItem('userAuth');
        if (!userAuthStr) {
          throw new Error('User authentication data not found');
        }

        const userAuth = JSON.parse(userAuthStr);
        const customerId = userAuth?.user?.user?.id;

        if (!customerId) {
          throw new Error('Customer ID not found in session data');
        }

        const response = await fetch(`http://127.0.0.1:8000/api/orders/customer/${customerId}`, {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
          },
        });

        if (!response.ok) throw new Error('Failed to fetch orders');
        
        const data = await response.json();
        setOrders(data.orders);
      } catch (err) {
        setError(err.message);
      } finally {
        setLoading(false);
      }
    };

    fetchOrders();
  }, []);

  const toggleOrderExpansion = (orderId) => {
    setExpandedOrders(prev => ({
      ...prev,
      [orderId]: !prev[orderId]
    }));
  };

  const getStatusBadgeClass = (status) => {
    switch (status.toLowerCase()) {
      case 'completed':
        return 'bg-success';
      case 'pending':
        return 'bg-warning';
      case 'processing':
        return 'bg-info';
      default:
        return 'bg-danger';
    }
  };

  const handleDownload = (sourceFile, productName) => {
    const downloadUrl = `http://127.0.0.1:8000/source_files/${sourceFile}`;

    alert(downloadUrl)
    // Create a temporary anchor element
    const link = document.createElement('a');
    link.href = downloadUrl;
    link.download = sourceFile; // You can customize the download filename here
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  };

  if (loading) {
    return (
      <div className="d-flex justify-content-center align-items-center min-vh-50">
        <div className="text-center">
          <div className="spinner-border text-primary" role="status">
            <span className="visually-hidden">Loading...</span>
          </div>
          <p className="text-muted mt-3">Loading your orders...</p>
        </div>
      </div>
    );
  }

  if (error) {
    return (
      <div className="alert alert-danger d-flex align-items-center" role="alert">
        <Package className="me-2" size={20} />
        <div>{error}</div>
      </div>
    );
  }

  if (!orders.length) {
    return (
      <div className="card bg-light">
        <div className="card-body text-center py-5">
          <Package className="mb-3" size={48} />
          <h3 className="card-title">No orders yet</h3>
          <p className="card-text text-muted">When you make your first order, it will appear here.</p>
          <button className="btn btn-outline-primary mt-3">Start Shopping</button>
        </div>
      </div>
    );
  }

  return (
    <div className="container-fluid p-0">
      <div className="d-flex justify-content-between align-items-center mb-4">
        <h2 className="mb-0">Order History</h2>
        <span className="badge bg-secondary">
          {orders.length} {orders.length === 1 ? 'Order' : 'Orders'}
        </span>
      </div>
      
      <div className="row g-4">
        {orders.map((order) => (
          <div key={order.id} className="col-12">
            <div className="card">
              {/* Order Header */}
              <div className="card-header bg-light">
                <div className="d-flex justify-content-between align-items-center">
                  <div>
                    <h5 className="mb-1">Order #{order.id}</h5>
                    <div className="text-muted small">
                      <Calendar className="me-2" size={16} />
                      {new Date(order.created_at).toLocaleDateString()}
                    </div>
                  </div>
                  <div className="d-flex align-items-center gap-3">
                    <span className={`badge ${getStatusBadgeClass(order.payment_status)}`}>
                      {order.payment_status.charAt(0).toUpperCase() + order.payment_status.slice(1)}
                    </span>
                    <button
                      className="btn btn-outline-secondary btn-sm"
                      onClick={() => toggleOrderExpansion(order.id)}
                    >
                      {expandedOrders[order.id] ? 'Less Details' : 'More Details'}
                    </button>
                  </div>
                </div>
              </div>

              {/* Expandable Content */}
              {expandedOrders[order.id] && (
                <div className="card-body">
                  {/* Payment Method */}
                  <div className="d-flex align-items-center mb-4">
                    <CreditCard className="me-2" size={16} />
                    <span className="text-muted">
                      {order.payment_method.replace('_', ' ').toUpperCase()}
                    </span>
                  </div>

                  {/* Order Items */}
                  <div className="mb-4">
                    <h6>Items</h6>
                    <div className="list-group">
                      {order.items.map((item) => (
                        <div key={item.id} className="list-group-item bg-light">
                          <div className="row align-items-center">
                            <div className="col-auto">
                              <img
                                src={`http://127.0.0.1:8000/images/${item.product.image}`}
                                alt={item.product.productname}
                                className="img-thumbnail"
                                style={{ width: '64px', height: '64px', objectFit: 'cover' }}
                              />
                            </div>
                            <div className="col">
                              <h6 className="mb-1">{item.product.productname}</h6>
                              <small className="text-muted">Quantity: {item.quantity}</small>
                              {item.product.source_file && (
                                <div className="mt-2">
                                  <button 
                                    className="btn btn-primary btn-sm"
                                    onClick={() => handleDownload(item.product.source_file, item.product.productname)}
                                  >
                                    <Download size={16} className="me-2" />
                                    Download Source File
                                  </button>
                                </div>
                              )}
                            </div>
                          </div>
                        </div>
                      ))}
                    </div>
                  </div>

                  {/* Shipping Address */}
                  <div className="mb-4">
                    <h6>Shipping Address</h6>
                    <div className="bg-light p-3 rounded">
                      <div className="d-flex">
                        <MapPin className="me-2" size={16} />
                        <div>
                          <p className="mb-1">{order.address.street}</p>
                          <p className="mb-1">{order.address.city}, {order.address.state} {order.address.postal_code}</p>
                          <p className="mb-0">{order.address.country}</p>
                        </div>
                      </div>
                    </div>
                  </div>

                  {/* Order Total */}
                  <div className="border-top pt-3">
                    <div className="d-flex justify-content-between">
                      <span className="fw-bold">Total</span>
                      <span className="fw-bold text-primary">
                        ${parseFloat(order.total_price).toFixed(2)}
                      </span>
                    </div>
                  </div>
                </div>
              )}
            </div>
          </div>
        ))}
      </div>
    </div>
  );
};

export default OrderHistory;