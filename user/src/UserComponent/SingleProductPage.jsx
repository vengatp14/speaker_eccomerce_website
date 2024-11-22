import React, { useState, useEffect } from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faHeart, faShoppingCart, faStar, faCartPlus } from '@fortawesome/free-solid-svg-icons';
import { faFacebook, faTwitter, faLinkedin } from '@fortawesome/free-brands-svg-icons';
import { useParams, Link, useNavigate } from 'react-router-dom';
import './SingleProductPage.css';

const SingleProductPage = () => {
  const { productId } = useParams();
  const navigate = useNavigate();
  const [product, setProduct] = useState(null);
  const [selectedPriceTier, setSelectedPriceTier] = useState('');
  const [selectedLicense, setSelectedLicense] = useState('annual');
  const [selectedImage, setSelectedImage] = useState('');
  const [rating, setRating] = useState(0);
  const [relatedProducts, setRelatedProducts] = useState([]);

  useEffect(() => {
    const fetchProductDetails = async () => {
      try {
        const response = await fetch(`http://127.0.0.1:8000/api/products/${productId}`);
        const result = await response.json();
        if (result.status === 'success') {
          setProduct(result.data);
          setSelectedImage(`http://127.0.0.1:8000/images/${result.data.image}`);
          setSelectedPriceTier('single_site');
        }
      } catch (error) {
        console.error('Error fetching product details:', error);
      }
    };

    const fetchRelatedProducts = async () => {
      try {
        const response = await fetch(`http://127.0.0.1:8000/api/products/related/${productId}`);
        const result = await response.json();
        if (result.status === 'success') {
          setRelatedProducts(result.data);
        }
      } catch (error) {
        console.error('Error fetching related products:', error);
      }
    };

    fetchProductDetails();
    fetchRelatedProducts();
  }, [productId]);

  const getSelectedPrice = () => {
    if (product && product.price_tiers && selectedLicense && selectedPriceTier) {
      return product.price_tiers[selectedLicense][selectedPriceTier];
    }
    return '';
  };

  const addToCart = () => {
    const cartItem = {
      id: product.id,
      name: product.productname,
      price: getSelectedPrice(),
      license: selectedLicense,
      tier: selectedPriceTier,
      quantity: 1
    };

    const existingCart = JSON.parse(localStorage.getItem('cart')) || [];
    const existingItemIndex = existingCart.findIndex(item => 
      item.id === cartItem.id && 
      item.license === cartItem.license && 
      item.tier === cartItem.tier
    );

    if (existingItemIndex !== -1) {
      existingCart[existingItemIndex].quantity += 1;
    } else {
      existingCart.push(cartItem);
    }

    localStorage.setItem('cart', JSON.stringify(existingCart));
    navigate('/cart');
  };

  const buyNow = () => {
    addToCart();
    navigate('/cart');
  };

  const addToWishlist = () => {
    const wishlistItem = {
      id: product.id,
      name: product.productname,
      price: getSelectedPrice(),
      license: selectedLicense,
      tier: selectedPriceTier
    };

    const existingWishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
    if (!existingWishlist.find(item => 
      item.id === wishlistItem.id && 
      item.license === wishlistItem.license && 
      item.tier === wishlistItem.tier
    )) {
      existingWishlist.push(wishlistItem);
      localStorage.setItem('wishlist', JSON.stringify(existingWishlist));
    }
  };

  const handleRatingChange = (value) => {
    setRating(value);
  };

  if (!product) {
    return <div>Loading...</div>;
  }

  return (
    <div className="container my-5">
      <div className="card shadow-sm p-4">
        <div className="row g-5">
          {/* Image Section */}
          <div className="col-lg-6">
            <div className="image-gallery">
              <img 
                src={selectedImage} 
                alt={product.productname} 
                className="img-fluid rounded shadow-sm main-image" 
                style={{ width: '100%', height: '400px', objectFit: 'cover' }}
              />
              <div className="thumbnail-gallery mt-3 d-flex gap-2">
                {product.images && product.images.map((image, index) => (
                  <img 
                    key={index}
                    src={`http://127.0.0.1:8000/images/${image}`}
                    alt={`Thumbnail ${index + 1}`}
                    className="img-thumbnail"
                    style={{ width: '80px', height: '80px', cursor: 'pointer', objectFit: 'cover' }}
                    onClick={() => setSelectedImage(`http://127.0.0.1:8000/images/${image}`)}
                  />
                ))}
              </div>
            </div>
          </div>

          {/* Product Info Section */}
          <div className="col-lg-6">
            <h1 className="h2 mb-3">{product.productname}</h1>
            <p className="text-muted">{product.description}</p>

            {/* Price Tier and License Options */}
            <div className="mb-4">
              <select 
                className="form-select mb-3"
                value={selectedLicense}
                onChange={(e) => setSelectedLicense(e.target.value)}
              >
                <option value="annual">Annual Payment</option>
                <option value="lifetime">Lifetime Purchase</option>
              </select>

              <select 
                className="form-select"
                value={selectedPriceTier}
                onChange={(e) => setSelectedPriceTier(e.target.value)}
              >
                <option value="single_site">1 Site</option>
                <option value="up_to_5_sites">Up to 5 Sites</option>
                <option value="up_to_20_sites">Up to 20 Sites</option>
              </select>
            </div>

            {/* Price and Actions */}
            <div className="d-flex justify-content-between align-items-center mb-4">
              <span className="h3 mb-0">Price: ${getSelectedPrice()}</span>
              <div>
                <button className="btn btn-outline-secondary me-2" onClick={addToWishlist}>
                  <FontAwesomeIcon icon={faHeart} size="sm" />
                  Add to Wishlist
                </button>
                <button className="btn btn-primary" onClick={addToCart}>
                  <FontAwesomeIcon icon={faShoppingCart} size="sm" className="me-2" />
                  Add to Cart
                </button>
              </div>
            </div>

            {/* Buy Now and Documentation Links */}
            <div className="mb-4 d-flex gap-2">
              <button className="btn btn-success" onClick={buyNow}>
                <FontAwesomeIcon icon={faCartPlus} size="lg" className="me-2" />
                Buy Now
              </button>
              <Link to="/documentation" className="btn btn-secondary">
                Documentation
              </Link>
            </div>

            {/* Rating Section */}
            <div className="mb-4">
              <h4 className="h5 mb-3">Rate this product:</h4>
              <div className="rating">
                {[1, 2, 3, 4, 5].map((value) => (
                  <span 
                    key={value}
                    className={`star ${rating >= value ? 'filled' : ''}`}
                    onClick={() => handleRatingChange(value)}
                  >
                    <FontAwesomeIcon icon={faStar} />
                  </span>
                ))}
              </div>
              <p className="text-muted mt-2">Your rating: {rating} {rating > 0 ? 'stars' : ''}</p>
            </div>

            {/* Share Section */}
            <div className="mb-4">
              <h4 className="h5 mb-3">Share:</h4>
              <div className="d-flex gap-2">
                <a href="https://www.facebook.com" target="_blank" rel="noopener noreferrer" className="me-2">
                  <FontAwesomeIcon icon={faFacebook} size="2x" />
                </a>
                <a href="https://www.twitter.com" target="_blank" rel="noopener noreferrer" className="me-2">
                  <FontAwesomeIcon icon={faTwitter} size="2x" />
                </a>
                <a href="https://www.linkedin.com" target="_blank" rel="noopener noreferrer">
                  <FontAwesomeIcon icon={faLinkedin} size="2x" />
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default SingleProductPage;
