import React, { useState, useEffect } from 'react';
import { useNavigate, useParams } from 'react-router-dom';
import axios from 'axios';

const ProductListing = () => {
  const [products, setProducts] = useState([]);
  const [category, setCategory] = useState(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);
  const [sortOption, setSortOption] = useState('name'); // State for sorting
  const navigate = useNavigate();
  const { categoryId } = useParams();

  const api = axios.create({
    baseURL: 'http://127.0.0.1:8000/api',
    headers: {
      'Content-Type': 'application/json',
    },
  });

  useEffect(() => {
    let isMounted = true;
    const controller = new AbortController();

    const fetchProducts = async () => {
      const loadingTimeout = setTimeout(() => {
        if (isMounted) setLoading(true);
      }, 200);

      try {
        const response = await api.get('/products', {
          params: { category_id: categoryId },
          signal: controller.signal,
        });

        if (!isMounted) return;

        if (response.data?.status === 'success') {
          setCategory(response.data.data.category);
          const productData = response.data.data.products.data || [];
          setProducts(productData);
          setError(null);
        } else {
          throw new Error('Invalid data structure received from API');
        }
      } catch (err) {
        if (!isMounted) return;
        
        if (axios.isCancel(err)) return;
        
        console.error('Error fetching products:', err);
        setError(err.message || 'Failed to fetch products');
      } finally {
        if (isMounted) {
          clearTimeout(loadingTimeout);
          setLoading(false);
        }
      }
    };

    fetchProducts();

    return () => {
      isMounted = false;
      controller.abort();
    };
  }, [categoryId]);

  // Handle sort change
  const handleSortChange = (event) => {
    setSortOption(event.target.value);
  };

  // Apply sorting to products based on sortOption
  const sortedProducts = [...products].sort((a, b) => {
    if (sortOption === 'name') {
      return a.productname.localeCompare(b.productname);
    } else if (sortOption === 'price') {
      return a.price - b.price;
    } else if (sortOption === 'date') {
      return new Date(b.created_at) - new Date(a.created_at);
    }
    return 0;
  });

  const handleAddToCart = (productId) => {
    if (productId) navigate(`/Product/${productId}`);
  };

  return (
    <div className="container py-4">
      {loading && (
        <div className="position-fixed top-0 start-0 w-100 h-100 bg-white bg-opacity-75" style={{ zIndex: 1050 }}>
          <div className="position-absolute top-50 start-50 translate-middle">
            <div className="spinner-border text-primary" role="status">
              <span className="visually-hidden">Loading...</span>
            </div>
          </div>
        </div>
      )}

      {category && (
        <div className="mb-4">
          <h2 className="mb-3">{category.name}</h2>
          <hr className="my-4" />
        </div>
      )}

      {/* Sort Filter */}
      <div className="mb-4">
        <label htmlFor="sort" className="form-label me-2">Sort By:</label>
        <select id="sort" className="form-select w-auto d-inline" value={sortOption} onChange={handleSortChange}>
          <option value="name">Name</option>
          <option value="price">Price</option>
          <option value="date">Newest</option>
        </select>
      </div>

      {/* Products Grid */}
      <div className="row g-4">
        {sortedProducts.map((product) => (
          <div key={product.id} className="col-12 col-sm-6 col-md-3">
            <div className="card h-100 border-0 shadow-sm">
              <img
                src={`http://127.0.0.1:8000/images/${product.image}`}
                alt={product.productname}
                className="card-img-top"
                style={{ height: '200px', objectFit: 'cover' }}
                loading="lazy"
              />
              <div className="card-body d-flex flex-column">
                <h5 className="card-title">{product.productname}</h5>
                <p className="card-text flex-grow-1">{product.description}</p>
                <button
                  className="btn btn-primary mt-auto"
                  onClick={() => handleAddToCart(product.id)}
                  disabled={loading}
                >
                  <i className="fas fa-shopping-cart me-2"></i> Purchase Now
                </button>
              </div>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
};

export default ProductListing;
