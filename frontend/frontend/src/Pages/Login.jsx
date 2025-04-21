import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom'; // ✅ Import useNavigate

const Login = ({ setToken }) => {
  const [form, setForm] = useState({ email: '', password: '' });
  const [error, setError] = useState(null);
  const navigate = useNavigate(); // ✅ Initialize navigate

  const handleChange = e => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleSubmit = async e => {
    e.preventDefault();
    try {
      const res = await fetch('http://localhost:8000/api/auth/login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(form)
      });

      const data = await res.json();

      if (!res.ok) {
        setError(data);
        return;
      }

      localStorage.setItem('token', data.access_token);
      alert('✅ Login successful!');
      if (setToken) setToken(data.access_token);

      navigate('/'); // ✅ Redirect to homepage after successful login

    } catch (err) {
      console.error(err);
      setError({ message: 'Login failed' });
    }
  };

  return (
    <div style={styles.container}>
      <form onSubmit={handleSubmit} style={styles.form}>
        <h2 style={styles.heading}>Login</h2>
        <input 
          type="email" 
          name="email" 
          placeholder="Email" 
          onChange={handleChange} 
          required 
          style={styles.inputField}
        />
        <input 
          type="password" 
          name="password" 
          placeholder="Password" 
          onChange={handleChange} 
          required 
          style={styles.inputField}
        />
        <button type="submit" style={styles.submitBtn}>Login</button>
        {error && <div style={styles.errorMessage}>{error.message}</div>}
        <button 
          type="button" 
          onClick={() => navigate('/register')} // ✅ Navigate to register page
          style={styles.registerBtn}
        >
          Don't have an account? Register here
        </button>
      </form>
    </div>
  );
};

const styles = {
  container: {
    width: '100%',
    maxWidth: '400px',
    backgroundColor: 'white',
    borderRadius: '8px',
    padding: '20px',
    boxShadow: '0px 4px 15px rgba(0, 0, 0, 0.1)',
    margin: '0 auto',
    display: 'flex',
    justifyContent: 'center',
    alignItems: 'center',
    height: '100vh',
  },
  form: {
    display: 'flex',
    flexDirection: 'column',
    gap: '15px',
    width: '100%',
  },
  heading: {
    textAlign: 'center',
    fontSize: '24px',
    marginBottom: '20px',
    color: '#333',
  },
  inputField: {
    width: '100%',
    padding: '12px',
    fontSize: '16px',
    border: '1px solid #ccc',
    borderRadius: '4px',
    transition: 'border 0.3s ease',
  },
  submitBtn: {
    backgroundColor: '#5f63f2',
    color: 'white',
    border: 'none',
    padding: '12px',
    fontSize: '16px',
    borderRadius: '4px',
    cursor: 'pointer',
    transition: 'background-color 0.3s ease',
  },
  errorMessage: {
    color: 'red',
    fontSize: '14px',
    marginTop: '10px',
    textAlign: 'center',
  },
  registerBtn: {
    backgroundColor: 'transparent',
    color: '#5f63f2',
    border: 'none',
    fontSize: '14px',
    cursor: 'pointer',
    textDecoration: 'underline',
    marginTop: '10px',
    textAlign: 'center',
  }
};

export default Login;
