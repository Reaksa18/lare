import React, { useEffect, useState } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import axios from 'axios';

const SingleProduct = () => {
  const { id } = useParams();
  const [product, setProduct] = useState(null);
  const navigate = useNavigate();

  useEffect(() => {
    axios.get(`http://127.0.0.1:8000/api/products/${id}`)
      .then(res => setProduct(res.data))
      .catch(err => console.error("Failed to load product", err));
  }, [id]);

  if (!product) return <div>Loading product...</div>;

  const goBackToShop = () => {
    navigate('/product'); 
  };

  const handleAddToCart = async () => {
    try {
      await axios.post(
        'http://127.0.0.1:8000/api/cart/add',
        {
          product_id: product.id,
          quantity: 1,
        },
        { withCredentials: true }
      );
      alert('Product added to cart!');
    } catch (error) {
      console.error('Add to cart error:', error);
      alert('Failed to add product to cart.');
    }
  };

  return (
    <div className="single-product-container">
      <style>{`
        .single-product-container {
          display: flex;
          flex-direction: column;
          justify-content: center;
          align-items: center;
          background-color: #f8f9fa;
          font-family: 'Segoe UI', sans-serif;
        }

        .inner_page_head {
          background-color:rgb(228, 67, 67);
          padding: 2rem 1rem;
          width: 100%;
          text-align: center;
          margin-bottom: 2rem;
        }

        .inner_page_head h3 {
          font-size: 2.5rem;
          font-weight: 600;
          color: #FFFFFF;
        }

        .container_fuild {
          width: 100%;
          margin: 0 auto;
          padding: 0 1rem;
        }

        .row {
          display: flex;
          justify-content: center;
        }

        .col-md-12 {
          flex: 1;
          max-width: 100%;
        }

        .full {
          padding: 1rem;
        }

        .product-container {
          display: flex;
          justify-content: center;
          align-items: flex-start;
          gap: 2rem;
          width: 80%;
          max-width: 1200px;
          margin-bottom: 3rem;
        }

        .product-image-container, .product-info {
          background-color: white;
          padding: 2rem;
          border-radius: 8px;
          box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
          flex: 1;
        }

        .product-image-container {
          display: flex;
          justify-content: center;
          align-items: center;
        }

        .product-image {
          max-width: 300px;
          height: auto;
          border-radius: 8px;
          box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .product-info {
          padding: 1.5rem;
        }

        .product-name {
          font-size: 2.5rem;
          font-weight: bold;
          color: #333;
          margin-bottom: 1rem;
        }

        .product-price {
          font-size: 2rem;
          color: #f85c00;
          margin-bottom: 1rem;
        }

        .product-description, .product-category {
          font-size: 1rem;
          color: #555;
          margin-bottom: 1rem;
        }

        .product-description strong, .product-category strong {
          color: #333;
        }

        .product-actions {
          display: flex;
          gap: 1rem;
          margin-top: 2rem;
        }

        .btn {
          padding: 0.8rem 1.5rem;
          font-size: 1.2rem;
          cursor: pointer;
          border: none;
          border-radius: 5px;
          color: white;
          transition: background-color 0.3s ease;
        }

        .btn-add-to-cart {
          background-color: rgb(255, 72, 5);
        }

        .btn-add-to-cart:hover {
          background-color: rgb(248, 72, 7);
        }

        .btn-back {
          background-color: rgb(9, 154, 252);
        }

        .btn-back:hover {
          background-color: rgb(5, 148, 243);
        }
      `}</style>

      {/* inner page section */}
      <section className="inner_page_head">
        <div className="container_fuild">
          <div className="row">
            <div className="col-md-12">
              <div className="full">
                <h3>Product Detail</h3>
              </div>
            </div>
          </div>
        </div>
      </section>
      {/* end inner page section */}

      {/* Product container with image and info */}
      <div className="product-container">
        <div className="product-image-container">
          <img 
            src={product.image || 'https://via.placeholder.com/300'} 
            alt={product.name} 
            className="product-image"
          />
        </div>
        <div className="product-info">
          <h2 className="product-name">{product.name}</h2>
          <h4 className="product-price">${product.price}</h4>
          <p className="product-description"><strong>Description:</strong> {product.description}</p>
          <p className="product-category"><strong>Category:</strong> {product.category?.name || 'Uncategorized'}</p>
          
          <div className="product-actions">
            <button className="btn btn-add-to-cart" onClick={handleAddToCart}>
              Add to Cart
            </button>
            <button className="btn btn-back" onClick={goBackToShop}>
              Back to Shop
            </button>
          </div>
        </div>
      </div>
    </div>
  );
};

export default SingleProduct;
