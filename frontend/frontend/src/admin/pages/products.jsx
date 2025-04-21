import React, { useState, useEffect } from 'react';
import axios from 'axios';

const Products = () => {
  const [products, setProducts] = useState([]);
  const [categories, setCategories] = useState([]);
  const [editing, setEditing] = useState(null);
  const [form, setForm] = useState({
    name: '',
    category_id: '',
    price: '',
    description: '',
    image: null,
  });

  useEffect(() => {
    loadProducts();
    axios.get('http://127.0.0.1:8000/api/categories').then(res => setCategories(res.data));
  }, []);

  const loadProducts = () => {
    axios.get('http://127.0.0.1:8000/api/products').then(res => setProducts(res.data));
  };

  const handleChange = (e) => {
    const { name, value, files } = e.target;
    setForm({
      ...form,
      [name]: files ? files[0] : value,
    });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    
    const formData = new FormData();

    
    Object.keys(form).forEach(key => {
      if (form[key] !== null && form[key] !== '') {
        formData.append(key, form[key]);
      }
    });

    
    for (let pair of formData.entries()) {
      console.log(`${pair[0]}:`, pair[1]);
    }

    try {
      if (editing) {
        await axios.post(`http://127.0.0.1:8000/api/products/${editing.id}?_method=PUT`, formData); 
        alert('Product updated successfully!');
      } else {
        await axios.post('http://127.0.0.1:8000/api/products', formData);
        alert('Product created successfully!');
      }
      resetForm();
      loadProducts();
    } catch (error) {
      if (error.response && error.response.status === 422) {
        console.log("Validation Errors: ", error.response.data.errors);
        alert("Validation failed. Check the console for details.");
      } else {
        console.error(error);
        alert('Something went wrong. Please try again.');
      }
    }
  };

  const resetForm = () => {
    setForm({
      name: '',
      category_id: '',
      price: '',
      description: '',
      image: null,
    });
    setEditing(null);
  };

  const handleEdit = (product) => {
    setEditing(product);
    setForm({
      name: product.name,
      category_id: product.category_id,
      price: product.price,
      description: product.description || '',
      image: null,
    });
    alert('Editing product: ' + product.name);
  };

  const handleDelete = async (id) => {
    if (window.confirm('Are you sure you want to delete this product?')) {
      try {
        await axios.delete(`http://127.0.0.1:8000/api/products/${id}`);
        alert('Product deleted successfully!');
        loadProducts();
      } catch (error) {
        console.error(error);
        alert('Failed to delete the product.');
      }
    }
  };

  return (
    <div className="container">
      <style>
        {`
          .container {
            background-color: #f4f4f4;
            padding: 20px;
          }

          .product-form {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
          }

          .product-form input,
          .product-form select,
          .product-form textarea {
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
            width: 100%;
            margin-bottom: 10px;
          }

          .product-form button {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
          }

          .product-form button:hover {
            background-color: #218838;
          }

          .product-item {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
          }

          .product-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
          }

          .product-item .actions button {
            padding: 6px 12px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
          }

          .product-item .actions button.edit {
            background-color: #007bff;
            color: white;
          }

          .product-item .actions button.edit:hover {
            background-color: #0056b3;
          }

          .product-item .actions button.delete {
            background-color: #dc3545;
            color: white;
          }

          .product-item .actions button.delete:hover {
            background-color: #c82333;
          }
        `}
      </style>

      <h1 className="text-3xl font-semibold mb-6 text-center text-blue-600">
        {editing ? 'Edit Product' : 'Add Product'}
      </h1>

      <form onSubmit={handleSubmit} className="product-form">
        <input
          name="name"
          value={form.name}
          onChange={handleChange}
          placeholder="Product Name"
          required
        />
        <select
          name="category_id"
          value={form.category_id}
          onChange={handleChange}
          required
        >
          <option value="">Select Category</option>
          {categories.map(cat => (
            <option key={cat.id} value={cat.id}>{cat.name}</option>
          ))}
        </select>
        <input
          name="price"
          type="number"
          value={form.price}
          onChange={handleChange}
          placeholder="Price"
          required
        />
        <textarea
          name="description"
          value={form.description}
          onChange={handleChange}
          placeholder="Description"
        />
        <input
          type="file"
          name="image"
          onChange={handleChange}
        />
        <div className="flex justify-between gap-4">
          <button type="submit">
            {editing ? 'Update' : 'Add Product'}
          </button>
          {editing && (
            <button
              type="button"
              onClick={resetForm}
              className="bg-gray-500 hover:bg-gray-600"
            >
              Cancel
            </button>
          )}
        </div>
      </form>

      <h2 className="text-2xl font-semibold mb-4 text-center text-blue-600">Product List</h2>
      <ul className="space-y-4">
        {products.map(p => (
          <li key={p.id} className="product-item">
            <div className="flex items-center space-x-4">
              <div>
                <div className="font-semibold text-lg">{p.name} - <span className="text-green-600">${p.price}</span></div>
                <div className="text-sm text-gray-500">Category: {p.category?.name}</div>
              </div>
              {p.image && (
                <img
                  src={p.image}
                  alt={p.name}
                  className="w-20 h-20 object-cover rounded-lg border"
                />
              )}
            </div>
            <div className="actions flex gap-4">
              <button
                onClick={() => handleEdit(p)}
                className="edit"
              >
                Edit
              </button>
              <button
                onClick={() => handleDelete(p.id)}
                className="delete"
              >
                Delete
              </button>
            </div>
          </li>
        ))}
      </ul>
    </div>
  );
};

export default Products;
