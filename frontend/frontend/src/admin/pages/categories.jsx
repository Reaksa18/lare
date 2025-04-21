import axios from 'axios';
import React, { useState, useEffect } from 'react';

const Categories = () => {
  const [categories, setCategories] = useState([]);
  const [name, setName] = useState('');
  const [editId, setEditId] = useState(null);

  const fetchCategories = async () => {
    const res = await axios.get('http://127.0.0.1:8000/api/categories');
    setCategories(res.data);
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (editId) {
      await axios.put(`http://127.0.0.1:8000/api/categories/${editId}`, { name });
      alert('Category updated successfully!');
      setEditId(null);
    } else {
      await axios.post('http://127.0.0.1:8000/api/categories', { name });
      alert('Category added successfully!');
    }
    setName('');
    fetchCategories();
  };

  const handleEdit = (category) => {
    setName(category.name);
    setEditId(category.id);
    alert('Editing category: ' + category.name);
  };

  const handleDelete = async (id) => {
    await axios.delete(`http://127.0.0.1:8000/api/categories/${id}`);
    alert('Category deleted successfully!');
    fetchCategories();
  };

  useEffect(() => {
    fetchCategories();
  }, []);

  const styles = {
    container: {
      padding: '1rem',
      maxWidth: '600px',
      margin: '0 auto',
      fontFamily: 'Arial, sans-serif'
    },
    title: {
      fontSize: '2rem',
      fontWeight: '700',
      marginBottom: '1rem',
      color: '#DE1416',
      textAlign: 'center',
      textTransform: 'uppercase',
      letterSpacing: '1px'
    },
    form: {
      display: 'flex',
      gap: '0.5rem',
      marginBottom: '1.5rem'
    },
    input: {
      flex: 1,
      padding: '0.5rem',
      border: '1px solid #ccc',
      borderRadius: '4px'
    },
    button: {
      backgroundColor: '#3b82f6',
      color: '#fff',
      padding: '0.5rem 1rem',
      border: 'none',
      borderRadius: '4px',
      cursor: 'pointer'
    },
    list: {
      listStyle: 'none',
      padding: 0
    },
    item: {
      display: 'flex',
      justifyContent: 'space-between',
      alignItems: 'center',
      border: '1px solid #e5e7eb',
      padding: '0.5rem',
      borderRadius: '4px',
      marginBottom: '0.5rem'
    },
    actions: {
      display: 'flex',
      gap: '0.5rem'
    },
    editBtn: {
      color: '#d97706',
      background: 'none',
      border: 'none',
      cursor: 'pointer'
    },
    deleteBtn: {
      color: '#dc2626',
      background: 'none',
      border: 'none',
      cursor: 'pointer'
    }
  };

  return (
    <div style={styles.container}>
      <h1 style={styles.title}>Category Management</h1>
      <form onSubmit={handleSubmit} style={styles.form}>
        <input
          type="text"
          value={name}
          onChange={(e) => setName(e.target.value)}
          placeholder="Category name"
          required
          style={styles.input}
        />
        <button type="submit" style={styles.button}>
          {editId ? 'Update' : 'Add'}
        </button>
      </form>

      <ul style={styles.list}>
        {categories.map((cat) => (
          <li key={cat.id} style={styles.item}>
            <span>{cat.name}</span>
            <div style={styles.actions}>
              <button onClick={() => handleEdit(cat)} style={styles.editBtn}>
                Edit
              </button>
              <button onClick={() => handleDelete(cat.id)} style={styles.deleteBtn}>
                Delete
              </button>
            </div>
          </li>
        ))}
      </ul>
    </div>
  );
};

export default Categories;
