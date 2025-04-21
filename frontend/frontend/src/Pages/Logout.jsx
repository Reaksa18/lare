const handleLogout = async () => {
    const token = localStorage.getItem('token');
  
    try {
      const res = await fetch('http://localhost:8000/api/auth/logout', {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${token}`
        }
      });
  
      if (res.ok) {
        localStorage.removeItem('token');
        alert('Logged out!');
      } else {
        const data = await res.json();
        console.error('Logout error:', data);
      }
    } catch (err) {
      console.error('Logout failed:', err);
    }
  };
  