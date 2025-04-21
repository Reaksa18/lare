import React, { useState, useEffect } from 'react';
import { useLocation, Link, useNavigate } from 'react-router-dom';
import { FaUser, FaShoppingCart } from 'react-icons/fa';
import './Header.css';
import { useCart } from '../components/CartContext';

const Header = () => {
  const { cart } = useCart();
  const [cartCount, setCartCount] = useState(0);
  const [isLoggedIn, setIsLoggedIn] = useState(false);
  const [dropdownOpen, setDropdownOpen] = useState(false);
  const location = useLocation();
  const currentPath = location.pathname;
  const navigate = useNavigate();

  useEffect(() => {
    const count = cart?.reduce((total, item) => total + item.quantity, 0) || 0;
    setCartCount(count);

    const token = localStorage.getItem("token");
    setIsLoggedIn(!!token);

    const handleStorageChange = () => {
      const updatedCart = JSON.parse(localStorage.getItem("cart")) || [];
      const newCount = updatedCart.reduce((total, item) => total + item.quantity, 0);
      setCartCount(newCount);
    };

    window.addEventListener('storage', handleStorageChange);

    return () => {
      window.removeEventListener('storage', handleStorageChange);
    };
  }, [cart]);

  const toggleDropdown = () => {
    setDropdownOpen(!dropdownOpen);
  };

  const handleLogout = () => {
    localStorage.removeItem("token");
    setIsLoggedIn(false);
    setDropdownOpen(false);
    alert("✅ Logged out successfully!");
    navigate('/');
  };

  return (
    <header className="header_section">
      <div className="container">
        <nav className="navbar navbar-expand-lg custom_nav-container">
          <a className="navbar-brand" href="/">
            <img width={250} src="assets/images/logo.png" alt="Logo" />
          </a>
          <button
            className="navbar-toggler"
            type="button"
            data-toggle="collapse"
            data-target="#navbarSupportedContent"
          >
            <span className></span>
          </button>
          <div className="collapse navbar-collapse" id="navbarSupportedContent">
            <ul className="navbar-nav">
              <li className="nav-item">
                <a className={`nav-link ${currentPath === '/' ? 'active' : ''}`} href="/">Home</a>
              </li>
              <li className="nav-item">
                <a className={`nav-link ${currentPath === '/aboutus' ? 'active' : ''}`} href="/aboutus">About Us</a>
              </li>
              <li className="nav-item">
                <a className={`nav-link ${currentPath === '/product' ? 'active' : ''}`} href="/product">Shop</a>
              </li>
              <li className="nav-item">
                <a className={`nav-link ${currentPath === '/contact' ? 'active' : ''}`} href="/contact">Contact Us</a>
              </li>
              <li className="nav-item">
                <a className={`nav-link ${currentPath === '/blog_list' ? 'active' : ''}`} href="/blog_list">Blog</a>
              </li>           
              <li className="nav-item">
                <Link className={`nav-link ${currentPath === '/cart' ? 'active' : ''}`} to="/cart">
                  <div style={{ position: 'relative' }}>
                    <FaShoppingCart
                      size={24}
                      style={{ color: currentPath === '/cart' ? 'red' : '#000' }}
                    />
                    {cartCount > 0 && (
                      <span
                        style={{
                          position: 'absolute',
                          top: '-15px',
                          right: '-5px',
                          backgroundColor: 'red',
                          color: 'white',
                          fontSize: '12px',
                          borderRadius: '50%',
                          padding: '2px 6px',
                        }}
                      >
                        {cartCount}
                      </span>
                    )}
                  </div>
                </Link>
              </li>
              <li className="nav-item" style={{ position: 'relative' }}>
                <FaUser
                  size={17}
                  onClick={toggleDropdown}
                  style={{
                    cursor: 'pointer',
                    marginLeft: '20px',
                    color: '#000',
                  }}
                />
                {dropdownOpen && (
                  <div
                    style={{
                      position: 'absolute',
                      top: '28px',
                      right: '0',
                      backgroundColor: 'white',
                      border: '1px solid #ddd',
                      borderRadius: '5px',
                      boxShadow: '0px 2px 8px rgba(0,0,0,0.1)',
                      padding: '10px',
                      zIndex: 10,
                    }}
                  >
                    {isLoggedIn ? (
                      <button
                        onClick={handleLogout}
                        style={{
                          border: 'none',
                          background: 'none',
                          cursor: 'pointer',
                        }}
                      >
                        Logout
                      </button>
                    ) : (
                      <Link to="/login">Login</Link>
                    )}
                  </div>
                )}
              </li>
            </ul>
          </div>
        </nav>
      </div>
    </header>
  );
};

export default Header;
