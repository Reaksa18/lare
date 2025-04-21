import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';

const Register = () => {
  const [form, setForm] = useState({
    name: '',
    email: '',
    password: '',
    password_confirmation: ''  // Added password confirmation
  });
  const [error, setError] = useState(null);
  const navigate = useNavigate();

  const handleChange = e => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleSubmit = async e => {
    e.preventDefault();
    try {
      const res = await fetch('http://localhost:8000/api/auth/register', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(form),
      });

      const data = await res.json();

      if (!res.ok) {
        setError({ message: Object.values(data).join(', ') }); // Display validation error messages
        return;
      }

      alert('✅ Registration successful! You can now log in.');
      navigate('/login'); // Redirect to login after successful registration

    } catch (err) {
      console.error(err);
      setError({ message: 'Registration failed' });
    }
  };

  return (
    <div style={styles.container}>
      <form onSubmit={handleSubmit} style={styles.form}>
        <h2 style={styles.heading}>Register</h2>
        <input 
          type="text" 
          name="name" 
          placeholder="Name" 
          onChange={handleChange} 
          required 
          style={styles.inputField}
        />
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
        <input 
          type="password" 
          name="password_confirmation" // Added confirmation field
          placeholder="Confirm Password" 
          onChange={handleChange} 
          required 
          style={styles.inputField}
        />
        <button type="submit" style={styles.submitBtn}>Register</button>
        {error && <div style={styles.errorMessage}>{error.message}</div>}
        <button 
          type="button" 
          onClick={() => navigate('/login')} 
          style={styles.loginRedirectBtn}
        >
          Already have an account? Please login here
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
    backgroundColor: '#28a745',
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
  loginRedirectBtn: {
    backgroundColor: 'transparent',
    color: '#28a745',
    border: 'none',
    fontSize: '14px',
    cursor: 'pointer',
    textDecoration: 'underline',
    marginTop: '10px',
    textAlign: 'center',
  }
};

export default Register;