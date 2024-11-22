import React from 'react';
import { UserCircle, Package, Heart, MapPin, User, Lock, LogOut } from 'lucide-react';
import { Link } from 'react-router-dom'; // Import Link component
import './AccountPage.css'; // We'll create this CSS file next

const AccountPage = () => {
  const [activeTab, setActiveTab] = React.useState('dashboard');
  const userData = {
    name: 'Plugcrux'
  };

  const menuItems = [
    { id: 'dashboard', icon: UserCircle, label: 'Dashboard' },
    { id: 'orders', icon: Package, label: 'Orders', link: '/orderhistory' }, // Add link for Orders
   
  ];

  const handleMenuClick = (id) => {
    setActiveTab(id);
  };

  return (
    <div className="dashboard-container">
      {/* Sidebar */}
      <div className="sidebar">
        <div className="sidebar-header">
          <h3 className="mb-0">My Account</h3>
        </div>
        <nav className="sidebar-nav">
          {menuItems.map((item) => (
            <button
              key={item.id}
              onClick={() => handleMenuClick(item.id)}
              className={`nav-item ${activeTab === item.id ? 'active' : ''}`}
            >
              <item.icon className="nav-icon" />
              <span>{item.label}</span>
            </button>
          ))}
        </nav>
      </div>

      {/* Main Content */}
      <div className="main-content">
        <div className="welcome-section">
          <h1 className="welcome-text">Welcome back, {userData.name}</h1>
        </div>

        <div className="dashboard-cards">
          <div className="row g-4">
            <div className="col-md-6">
              <Link to="/order"> {/* Wrap in Link to navigate */}
                <div className="dashboard-card">
                  <div className="card-icon orders">
                    <Package />
                  </div>
                  <h3>Orders</h3>
                  <p>View your order history</p>
                </div>
              </Link>
            </div>

            <div className="col-md-6">
              <div className="dashboard-card">
                <div className="card-icon wishlist">
                  <Heart />
                </div>
                <h3>Wishlist</h3>
                <p>View saved items</p>
              </div>
            </div>

            <div className="col-md-6">
              <div className="dashboard-card">
                <div className="card-icon billing">
                  <MapPin />
                </div>
                <h3>Billing Address</h3>
                <p>Manage billing information</p>
              </div>
            </div>

            <div className="col-md-6">
              <div className="dashboard-card">
                <div className="card-icon shipping">
                  <MapPin />
                </div>
                <h3>Shipping Address</h3>
                <p>Manage shipping details</p>
              </div>
            </div>

            <div className="col-md-6">
              <div className="dashboard-card">
                <div className="card-icon password">
                  <Lock />
                </div>
                <h3>Change Password</h3>
                <p>Update your password</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default AccountPage;
