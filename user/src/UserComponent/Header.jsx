import React, { useState, useEffect, useRef } from 'react';
import axios from 'axios';
import { Link, useNavigate } from 'react-router-dom';  // Import useNavigate for redirection
import "./Header.css";

const Headers = () => {
  const [activeModal, setActiveModal] = useState(null);
  const [plugins, setPlugins] = useState([]);
  const [isLoggedIn, setIsLoggedIn] = useState(false);
  const [cartCount, setCartCount] = useState(0);
  const [cartItems, setCartItems] = useState({});
  const [successMessage, setSuccessMessage] = useState('');  // Success message state
  const accountref = useRef();
  const navigate = useNavigate();  // Use navigate for redirection

  useEffect(() => {
    const fetchPlugins = async () => {
      try {
        const response = await axios.get('http://127.0.0.1:8000/api/category');
        setPlugins(response.data);
      } catch (error) {
        console.error('Error fetching product data:', error);
      }
    };
    fetchPlugins();

    const loadCartData = () => {
      const cartData = localStorage.getItem('cartValues');
      if (cartData) {
        const parsedCartData = JSON.parse(cartData);
        setCartItems(parsedCartData);
        setCartCount(Object.keys(parsedCartData).length);
      }
    };

    const checkAuthStatus = () => {
      const userAuth = sessionStorage.getItem('userAuth');
      if (userAuth) {
        setIsLoggedIn(true);
      } else {
        setIsLoggedIn(false);
      }
    };

    loadCartData();
    checkAuthStatus();

    window.addEventListener('storage', loadCartData);
    window.addEventListener('storage', checkAuthStatus);

    return () => {
      window.removeEventListener('storage', loadCartData);
      window.removeEventListener('storage', checkAuthStatus);
    };
  }, []);

  const handleOpenModal = (modalType) => {
    setActiveModal(modalType);
  };

  const handleCloseModal = () => {
    setActiveModal(null);
  };

  const handleLogout = () => {
    sessionStorage.removeItem('userAuth');
    setIsLoggedIn(false);
    setSuccessMessage('Logged out successfully!');
  };

  return (
    <>
      <nav className="navbar navbar-expand-lg">
        <div className="container-fluid">
          <a className="navbar-brand" href="#">
            <img src="/src/assets/plugcruxs.png" alt="Logo" className="logo-img" />
          </a>
          <button
            className="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav"
            aria-controls="navbarNav"
            aria-expanded="false"
            aria-label="Toggle navigation"
          >
            <span className="navbar-toggler-icon"></span>
          </button>
          <div className="collapse navbar-collapse" id="navbarNav">
            <ul className="css_costum navbar-nav mx-auto mb-2 mb-lg-0 nab">
              <li className="nav-item">
                <a className="nav-link" href="#">Home</a>
              </li>
              <li className="nav-item dropdown">
                <a
                  className="nav-link dropdown-toggle"
                  href="#"
                  id="navbarDropdown"
                  role="button"
                  aria-expanded="false"
                >
                  Plugin
                </a>
                <ul className="dropdown-menu" aria-labelledby="navbarDropdown">
                  {plugins.length > 0 ? (
                    plugins.map((plugin) => (
                      <li key={plugin.id}>
                        <Link className="dropdown-item" to={`/category/${plugin.id}`}>
                          {plugin.name}
                        </Link>
                      </li>
                    ))
                  ) : (
                    <li><a className="dropdown-item" href="#">Loading...</a></li>
                  )}
                </ul>
              </li>
              <li className="nav-item">
                <a className="nav-link" href="#">Support</a>
              </li>
              <li className="nav-item">
                <a className="nav-link" href="#">Demo</a>
              </li>
              <li className="nav-item">
                <a className="nav-link" href="#">Feature Request</a>
              </li>
            </ul>
            <ul className="boss_cons navbar-nav ms-auto">
              <li className="nav-item">
                <a className="nav-link" href="#"><i className="fas fa-heart"></i></a>
              </li>
              <li className="nav-item cart-icon">
                <Link className="nav-link" to="/cart">
                  <i className="fas fa-shopping-cart"></i>
                  {cartCount > 0 && <span className="cart-count-badge">{cartCount}</span>}
                </Link>
                {/* Cart Dropdown */}
                {cartItems && Object.keys(cartItems).length > 0 && (
                  <div className="cart-dropdown">
                    <ul className="list-group">
                      {Object.values(cartItems).map((item, index) => (
                        <li key={index} className="list-group-item">
                          {item.name} - {item.quantity}
                        </li>
                      ))}
                    </ul>
                  </div>
                )}
              </li>
             <li className="nav-item">
  {isLoggedIn ? (
    <>
      <Link className="nav-link" to="/account"><i className="fas fa-user"></i> My Account</Link>
      <button className="btn btn-link nav-link" onClick={handleLogout}>Log Out</button>
    </>
  ) : (
    <a ref={accountref} className="nav-link" href="#" onClick={() => handleOpenModal('login')}>
      <i className="fas fa-user"></i>
    </a>
  )}
</li>

            </ul>
          </div>
        </div>
      </nav>

      {/* Show success message */}
      {successMessage && (
        <div className="alert alert-success text-center">
          {successMessage}
        </div>
      )}

      {activeModal === 'login' && <LoginModal handleOpenModal={handleOpenModal} handleCloseModal={handleCloseModal} setIsLoggedIn={setIsLoggedIn} setSuccessMessage={setSuccessMessage} />}
      {activeModal === 'signup' && <SignUpModal handleCloseModal={handleCloseModal} setIsLoggedIn={setIsLoggedIn} setSuccessMessage={setSuccessMessage} />}
      {activeModal === 'forgot' && <ForgotPasswordModal handleCloseModal={handleCloseModal} />}
    </>
  );
};

const LoginModal = ({ handleOpenModal, handleCloseModal, setIsLoggedIn, setSuccessMessage }) => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const navigate = useNavigate();  // For redirecting

  const handleLogin = async (e) => {
    e.preventDefault();
    setError('');

    try {
      const response = await axios.post('http://127.0.0.1:8000/api/signin', { email, password });

      sessionStorage.setItem('userAuth', JSON.stringify({
        user: response.data,
        timestamp: new Date().toISOString(),
        type: 'login'
      }));

      setIsLoggedIn(true);
      setSuccessMessage('Login successful! Redirecting to your account...');
      handleCloseModal();

      // Redirect to account page after login
      navigate('/account');
    } catch (error) {
      setError('Invalid email or password. Please try again.');
      console.error('Login error:', error);
    }
  };

  return (
    <div className="modal fade show" style={{ display: 'block' }} tabIndex="-1">
      <div className="modal-dialog">
        <div className="modal-content">
          <div className="modal-header">
            <h5 className="modal-title">Sign In</h5>
            <button type="button" className="btn-close" onClick={handleCloseModal}></button>
          </div>
          <form onSubmit={handleLogin}>
            <div className="modal-body">
              {error && <div className="alert alert-danger">{error}</div>}
              <input 
                type="email" 
                className="form-control mb-3" 
                placeholder="Email"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                required
              />
              <input 
                type="password" 
                className="form-control mb-3" 
                placeholder="Password"
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                required
              />
              <button type="submit" className="btn btn-primary w-100 mb-2">Sign In</button>
              <button type="button" className="btn btn-link w-100" onClick={() => handleOpenModal('forgot')}>Forgot Password?</button>
            </div>
            <div className="modal-footer">
              <button type="button" className="btn btn-secondary" onClick={() => handleOpenModal('signup')}>Sign Up</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  );
};

const SignUpModal = ({ handleCloseModal, setIsLoggedIn, setSuccessMessage }) => {
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    password: '',
    confirmPassword: ''
  });
  const [error, setError] = useState('');

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');

    if (formData.password !== formData.confirmPassword) {
      setError("Passwords do not match.");
      return;
    }

    try {
      const response = await axios.post('http://127.0.0.1:8000/api/signup', formData);
      sessionStorage.setItem('userAuth', JSON.stringify({
        user: response.data,
        timestamp: new Date().toISOString(),
        type: 'signup'
      }));
      setIsLoggedIn(true);
      setSuccessMessage('Sign up successful! Redirecting to your account...');
      handleCloseModal();
      
      // Redirect to account page after sign-up
      navigate('/account');
    } catch (error) {
      setError("Sign up failed. Please try again.");
    }
  };

  return (
    <div className="modal fade show" style={{ display: 'block' }} tabIndex="-1">
      <div className="modal-dialog">
        <div className="modal-content">
          <div className="modal-header">
            <h5 className="modal-title">Sign Up</h5>
            <button type="button" className="btn-close" onClick={handleCloseModal}></button>
          </div>
          <form onSubmit={handleSubmit}>
            <div className="modal-body">
              {error && <div className="alert alert-danger">{error}</div>}
              <input 
                type="text" 
                name="name"
                className="form-control mb-3" 
                placeholder="Name"
                value={formData.name}
                onChange={handleChange}
                required
              />
              <input 
                type="email" 
                name="email"
                className="form-control mb-3" 
                placeholder="Email"
                value={formData.email}
                onChange={handleChange}
                required
              />
              <input 
                type="password" 
                name="password"
                className="form-control mb-3" 
                placeholder="Password"
                value={formData.password}
                onChange={handleChange}
                required
              />
              <input 
                type="password" 
                name="confirmPassword"
                className="form-control mb-3" 
                placeholder="Confirm Password"
                value={formData.confirmPassword}
                onChange={handleChange}
                required
              />
              <button type="submit" className="btn btn-primary w-100">Sign Up</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  );
};

const ForgotPasswordModal = ({ handleCloseModal }) => {
  const [email, setEmail] = useState('');

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      await axios.post('http://127.0.0.1:8000/api/forgot-password', { email });
      alert("Password reset link sent to your email.");
      handleCloseModal();
    } catch (error) {
      console.error('Error sending password reset link:', error);
    }
  };

  return (
    <div className="modal fade show" style={{ display: 'block' }} tabIndex="-1">
      <div className="modal-dialog">
        <div className="modal-content">
          <div className="modal-header">
            <h5 className="modal-title">Forgot Password</h5>
            <button type="button" className="btn-close" onClick={handleCloseModal}></button>
          </div>
          <form onSubmit={handleSubmit}>
            <div className="modal-body">
              <input 
                type="email" 
                className="form-control mb-3" 
                placeholder="Email"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                required
              />
              <button type="submit" className="btn btn-primary w-100">Reset Password</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  );
};

export default Headers;
