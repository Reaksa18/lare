import React, { createContext, useContext, useState, useEffect, useCallback } from 'react';
import axios from 'axios';

const CartContext = createContext();

export const useCart = () => useContext(CartContext);

export const CartProvider = ({ children }) => {
  const [cartItems, setCartItems] = useState([]);
  const [cartCount, setCartCount] = useState(0);
  const [token, setToken] = useState(localStorage.getItem('token'));

  const fetchCartCount = useCallback(async () => {
    if (!token) return;
    try {
      const res = await axios.get('http://127.0.0.1:8000/api/cart/count', {
        headers: { Authorization: `Bearer ${token}` },
      });
      setCartCount(res.data.count);
    } catch (err) {
      console.error('Cart count fetch error:', err);
    }
  }, [token]);

  const fetchCartItems = useCallback(async () => {
    if (!token) return;
    try {
      const res = await axios.get('http://127.0.0.1:8000/api/cart', {
        headers: { Authorization: `Bearer ${token}` },
      });
      setCartItems(res.data.cart_items); // ✅ Only set the array
      // ✅ Update cart items
    } catch (err) {
      console.error('Error fetching cart items:', err);
    }
  }, [token]);

  const updateCartCount = (change) => {
    setCartCount((prev) => Math.max(0, prev + change));
  };

  useEffect(() => {
    fetchCartCount();
    fetchCartItems();
  }, [fetchCartCount, fetchCartItems]);

  useEffect(() => {
    const storedToken = localStorage.getItem('token');
    if (storedToken !== token) {
      setToken(storedToken);
    }
  }, [token]);

  return (
    <CartContext.Provider
      value={{
        cart: cartItems, // ✅ Provided here
        cartCount,
        updateCartCount,
        fetchCartCount,
        fetchCartItems,
      }}
    >
      {children}
    </CartContext.Provider>
  );
};
