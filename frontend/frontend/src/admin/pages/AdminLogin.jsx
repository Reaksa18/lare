import React, { useState } from 'react';
import axios from 'axios';

const AdminLogin = () => {
  const [formData, setFormData] = useState({ email: '', password: '' });
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    setLoading(true);

    try {
      const res = await axios.post('http://localhost:8000/api/admin/login', formData);
      localStorage.setItem('adminToken', res.data.access_token);
      alert('Admin logged in!');
      window.location.href = '/admin/dashboard';
    } catch (err) {
      setError(err.response?.data?.error || 'Login failed');
    } finally {
      setLoading(false);
    }
  };

  return (
    <>
      <style>{`
        .admin-login-wrapper {
          display: flex;
          justify-content: center;
          align-items: center;
          min-height: 100vh;
          background: linear-gradient(135deg, #c3dafe, #fcd1ff);
          font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .admin-login-box {
          background-color: #ffffff;
          padding: 40px 30px;
          border-radius: 12px;
          box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
          width: 100%;
          max-width: 400px;
          text-align: center;
        }

        .admin-login-box h2 {
          margin-bottom: 25px;
          font-size: 26px;
          color: #333;
        }

        .input-group {
          margin-bottom: 20px;
          text-align: left;
        }

        .input-group label {
          display: block;
          margin-bottom: 8px;
          font-size: 14px;
          color: #555;
        }

        .input-group input {
          width: 100%;
          padding: 12px 14px;
          border: 1px solid #ccc;
          border-radius: 6px;
          font-size: 15px;
          transition: 0.3s ease;
          background-color: #f9f9f9;
        }

        .input-group input:focus {
          border-color: #6a5acd;
          outline: none;
          background-color: #fff;
        }

        button {
          width: 100%;
          padding: 12px;
          background-color: #6a5acd;
          color: white;
          border: none;
          border-radius: 6px;
          font-size: 16px;
          font-weight: bold;
          cursor: pointer;
          transition: background-color 0.3s ease;
        }

        button:hover {
          background-color: #4b3fa3;
        }

        button:disabled {
          background-color: #ccc;
          cursor: not-allowed;
        }

        .error-message {
          margin-top: 16px;
          color: #e74c3c;
          font-size: 14px;
        }
      `}</style>

      <div className="admin-login-wrapper">
        <div className="admin-login-box">
          <h2>Admin Login</h2>
          <form onSubmit={handleSubmit}>
            <div className="input-group">
              <label>Email</label>
              <input
                type="email"
                name="email"
                value={formData.email}
                onChange={handleChange}
                required
                placeholder="admin@example.com"
              />
            </div>
            <div className="input-group">
              <label>Password</label>
              <input
                type="password"
                name="password"
                value={formData.password}
                onChange={handleChange}
                required
                placeholder="••••••••"
              />
            </div>
            <button type="submit" disabled={loading}>
              {loading ? 'Logging in...' : 'Login'}
            </button>
            {error && <p className="error-message">{error}</p>}
          </form>
        </div>
      </div>
    </>
  );
};

export default AdminLogin;
