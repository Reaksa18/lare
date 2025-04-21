import React, { useEffect, useState } from 'react';
import axios from 'axios';
import { useNavigate } from 'react-router-dom';

const Cart = () => {
  const [cart, setCart] = useState([]);
  const [loading, setLoading] = useState(true);
  const navigate = useNavigate();

  useEffect(() => {
    fetchCart();
  }, []);

  const fetchCart = async () => {
    setLoading(true);
    const token = localStorage.getItem('token');

    try {
      const response = await axios.get('http://127.0.0.1:8000/api/cart', {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });
      setCart(response.data.cart_items);
    } catch (error) {
      console.error('Error fetching cart:', error.response ? error.response.data : error);
    } finally {
      setLoading(false);
    }
  };

  const updateCart = async (productId, action) => {
    const token = localStorage.getItem('token');
    const productIdInt = parseInt(productId, 10);

    try {
      await axios.post(
        'http://127.0.0.1:8000/api/cart/update',
        { product_id: productIdInt, action },
        {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        }
      );

      setCart(prevCart =>
        prevCart.map(item =>
          item.id === productIdInt
            ? {
                ...item,
                quantity:
                  action === 'increase'
                    ? item.quantity + 1
                    : item.quantity > 1
                    ? item.quantity - 1
                    : 1,
              }
            : item
        )
      );
    } catch (error) {
      console.error('Failed to update cart:', error.response ? error.response.data : error);
    }
  };

  const removeFromCart = async (productId) => {
    const token = localStorage.getItem('token');

    try {
      await axios.post(
        'http://127.0.0.1:8000/api/cart/remove',
        { product_id: productId },
        {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        }
      );

      setCart(prevCart => prevCart.filter(item => item.id !== productId));
    } catch (error) {
      console.error('Failed to remove item:', error.response ? error.response.data : error);
    }
  };

  const calculateTotal = () =>
    cart.reduce((total, item) => total + item.price * item.quantity, 0);

  const handleContinueShopping = () => {
    navigate('/product');
  };

  const handleGoToCheckout = () => {
    navigate('/checkout');
  };

  if (loading) {
    return <p>Fetching your products...</p>;
  }

  return (
    <div style={styles.cartContainer}>
      <h2>Your Cart</h2>
      {cart.length === 0 ? (
        <p style={styles.cartEmpty}>Your cart is empty.</p>
      ) : (
        <div>
          {cart.map(item => (
            <div key={item.id} style={styles.cartItem}>
              <img
                src={item.image || 'https://via.placeholder.com/100x80'}
                alt={item.name}
                style={styles.cartItemImage}
              />
              <div style={styles.cartItemInfo}>
                <strong>{item.name}</strong>
                <p>Price: ${item.price}</p>
                <p>Quantity: {item.quantity}</p>
              </div>
              <div>
                <button
                  onClick={() => updateCart(item.id, 'decrease')}
                  style={styles.button}
                >
                  -
                </button>
                <button
                  onClick={() => updateCart(item.id, 'increase')}
                  style={styles.button}
                >
                  +
                </button>
                <button
                  onClick={() => removeFromCart(item.id)}
                  style={styles.removeButton}
                >
                  Remove
                </button>
              </div>
            </div>
          ))}
          <div style={styles.cartTotal}>
            <span>Total: ${calculateTotal().toFixed(2)}</span>
          </div>
        </div>
      )}

      <div style={styles.buttonsContainer}>
        <button onClick={handleContinueShopping} style={styles.continueButton}>
          Continue Shopping
        </button>
        <button onClick={handleGoToCheckout} style={styles.checkoutButton}>
          Go to Checkout
        </button>
      </div>
    </div>
  );
};

const styles = {
  cartContainer: {
    maxWidth: '1200px',
    margin: '0 auto',
    padding: '2rem',
    backgroundColor: '#fff',
    borderRadius: '8px',
    boxShadow: '0 2px 10px rgba(0,0,0,0.1)',
  },
  cartItem: {
    display: 'flex',
    alignItems: 'center',
    marginBottom: '1rem',
    padding: '1rem',
    borderBottom: '1px solid #ddd',
  },
  cartItemImage: {
    width: '100px',
    height: '80px',
    objectFit: 'cover',
    marginRight: '1rem',
  },
  cartItemInfo: {
    flex: 1,
  },
  cartEmpty: {
    fontSize: '1.5rem',
    textAlign: 'center',
    color: '#555',
  },
  cartTotal: {
    marginTop: '20px',
    fontSize: '1.25rem',
    textAlign: 'right',
  },
  buttonsContainer: {
    display: 'flex',
    justifyContent: 'space-between',
    marginTop: '20px',
  },
  continueButton: {
    backgroundColor: '#007BFF',
    color: '#fff',
    border: 'none',
    padding: '10px 20px',
    fontSize: '1rem',
    borderRadius: '5px',
    cursor: 'pointer',
  },
  checkoutButton: {
    backgroundColor: '#28a745',
    color: '#fff',
    border: 'none',
    padding: '10px 20px',
    fontSize: '1rem',
    borderRadius: '5px',
    cursor: 'pointer',
  },
  button: {
    padding: '5px 10px',
    marginRight: '10px',
    backgroundColor: '#f1f1f1',
    border: '1px solid #ccc',
    cursor: 'pointer',
  },
  removeButton: {
    padding: '5px 10px',
    backgroundColor: '#FF4136',
    color: '#fff',
    border: '1px solid #ccc',
    cursor: 'pointer',
  },
};

export default Cart;
