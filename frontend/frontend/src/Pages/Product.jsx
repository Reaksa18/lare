import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { Link } from 'react-router-dom';
import { useCart } from '../components/CartContext';

const Product = () => {
  const [categories, setCategories] = useState([]);
  const [selectedCatId, setSelectedCatId] = useState(null);
  const [products, setProducts] = useState([]);
  const { fetchCartItems } = useCart(); // Accessing fetchCartItems from CartContext

  useEffect(() => {
    // Fetch categories on component mount
    axios.get('http://127.0.0.1:8000/api/categories')
      .then(res => {
        setCategories(res.data);
        if (res.data.length > 0) setSelectedCatId(res.data[0].id); // Set the first category as default
      })
      .catch(err => console.error('Error fetching categories:', err));
  }, []);

  useEffect(() => {
    // Fetch products when the selected category changes
    if (selectedCatId) {
      axios.get(`http://127.0.0.1:8000/api/categories/${selectedCatId}/products`)
        .then(res => setProducts(res.data))
        .catch(err => console.error('Error fetching products:', err));
    }
  }, [selectedCatId]);

  const handleAddToCart = async (productId) => {
    const token = localStorage.getItem('token'); // Retrieve token from localStorage

    try {
      await axios.post(
        'http://127.0.0.1:8000/api/cart/add',
        {
          product_id: productId,
          quantity: 1, // Default quantity is 1
        },
        {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        }
      );

      await fetchCartItems(); // Update the cart items (and count) after adding the product
      alert('Product added to cart!');
    } catch (error) {
      console.error('Add to cart error:', error);
      alert('Failed to add product to cart! You need to login first.');
    }
  };

  return (
    <>
      <style>{`
        .product-container {
          display: flex;
          padding: 2rem;
          gap: 1.5rem;
          background-color: #f8f9fa;
          min-height: 100vh;
          font-family: 'Segoe UI', sans-serif;
        }
        .box {
          background-color: #fff;
          padding: 1.25rem;
          border-radius: 12px;
          box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }
        .sidebar {
          width: 250px;
        }
        .sidebar h3 {
          margin-bottom: 1rem;
          color: #E4080A;
        }
        .sidebar ul {
          list-style: none;
          padding: 0;
        }
        .sidebar button {
          width: 100%;
          background: none;
          border: none;
          text-align: left;
          padding: 0.6rem 0.75rem;
          cursor: pointer;
          transition: background-color 0.2s ease;
          border-radius: 6px;
          font-size: 0.95rem;
        }
        .sidebar button:hover {
          background-color: #f1f1f1;
        }
        .sidebar button.active {
          background-color: rgb(255, 72, 0);
          color: white;
        }
        .product-list {
          flex: 1;
        }
        .product-list h3 {
          margin-bottom: 1rem;
          color: rgb(248, 8, 12);
          text-align: center;
          font-size: 1.6rem;
          font-weight: 600;
        }
        .grid {
          display: grid;
          grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
          gap: 1.25rem;
        }
        .card {
          border: 1px solid #eee;
          padding: 0.75rem;
          border-radius: 10px;
          background-color: #fff;
          box-shadow: 0 1px 4px rgba(0,0,0,0.05);
          text-align: center;
          transition: transform 0.2s ease-in-out;
        }
        .card:hover {
          transform: translateY(-5px);
        }
        .product-image {
          width: 100%;
          height: 150px;
          object-fit: cover;
          border-radius: 8px;
          margin-bottom: 0.5rem;
        }
        .card-actions {
          margin-top: 0.5rem;
          display: flex;
          justify-content: space-between;
        }
        .btn {
          padding: 0.4rem 0.6rem;
          border: none;
          border-radius: 5px;
          cursor: pointer;
          font-size: 0.85rem;
        }
        .btn.view {
          background-color: rgb(252, 124, 4);
          color: #fff;
        }
        .btn.cart {
          background-color: rgb(248, 74, 5);
          color: #fff;
        }
        .btn:hover {
          opacity: 0.9;
        }
      `}</style>

      {/* inner page section */}
      <section className="inner_page_head">
        <div className="container_fuild">
          <div className="row">
            <div className="col-md-12">
              <div className="full">
                <h3>Our Product</h3>
              </div>
            </div>
          </div>
        </div>
      </section>
      {/* end inner page section */}

      <div className="product-container">
        <div className="sidebar box">
          <h3>Categories</h3>
          <ul>
            {categories.map(cat => (
              <li key={cat.id}>
                <button
                  className={cat.id === selectedCatId ? 'active' : ''}
                  onClick={() => setSelectedCatId(cat.id)}
                >
                  {cat.name}
                </button>
              </li>
            ))}
          </ul>
        </div>

        <div className="product-list box">
          <h3>Products</h3>
          <div className="grid">
            {products.map(prod => (
              <div className="card" key={prod.id}>
                <img
                  src={prod.image || 'https://via.placeholder.com/200x150'}
                  alt={prod.name}
                  className="product-image"
                />
                <h4>{prod.name}</h4>
                <p>${prod.price}</p>
                <div className="card-actions">
                  <Link to={`/product/${prod.id}`} className="btn view">View Detail</Link>
                  <button
                    className="btn cart"
                    onClick={() => handleAddToCart(prod.id)}
                  >
                    Add to Cart
                  </button>
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>
    </>
  );
};

export default Product;
