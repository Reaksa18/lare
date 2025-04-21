import axios from 'axios';

const AdminLogout = async () => {
  const token = localStorage.getItem('adminToken');

  try {
    await axios.post('http://localhost:8000/api/admin/logout', {}, {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    });

    localStorage.removeItem('adminToken');
    alert("Admin logged out!");
    window.location.href = '/admin/login';
  } catch (err) {
    console.error('Logout failed:', err);
    alert('Logout failed. Please try again.');
  }
};

export default AdminLogout;