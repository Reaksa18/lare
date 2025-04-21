import React, { useEffect, useState } from 'react';
import axios from '../../api';

const SlideManager = () => {
  const [slides, setSlides] = useState([]);
  const [form, setForm] = useState({
    title: '',
    description: '',
    image: null,
    enabled: true,
  });
  const [editingSlideId, setEditingSlideId] = useState(null); // Track the slide being edited

  const fetchSlides = async () => {
    try {
      const res = await axios.get('/slides');
      setSlides(res.data);
    } catch (error) {
      console.error('Error fetching slides:', error);
    }
  };

  useEffect(() => {
    fetchSlides();
  }, []);

  // Pre-fill form data for editing
  const handleEdit = (id) => {
    console.log('test');
    const slideToEdit = slides.find((slide) => slide.id === id);
    setForm({
      title: slideToEdit.title,
      description: slideToEdit.description,
      image: null, // Reset image for edit
      enabled: slideToEdit.enabled,
    });
    setEditingSlideId(id); // Set the slide being edited
  };

  // Handle form field changes
  const handleChange = (e) => {
    const { name, value, type, checked, files } = e.target;
    if (type === 'file') {
      setForm({ ...form, image: files[0] });
    } else if (type === 'checkbox') {
      setForm({ ...form, [name]: checked });
    } else {
      setForm({ ...form, [name]: value });
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
  
    const formData = new FormData();
    formData.append('title', form.title);
    formData.append('description', form.description || '');
    formData.append('enabled', form.enabled ? '1' : '0');

  
    if (form.image) {
      formData.append('image', form.image);
    }
  
    try {
      if (editingSlideId) {
        // Fix: Spoof PUT request via POST for Laravel
        formData.append('_method', 'PUT');
  
        await axios.post(`/slides/${editingSlideId}`, formData, {
          headers: { 'Content-Type': 'multipart/form-data' },
        });
      } else {
        await axios.post('/slides', formData, {
          headers: { 'Content-Type': 'multipart/form-data' },
        });
      }
  
      fetchSlides();
      setEditingSlideId(null);
      setForm({ title: '', description: '', image: null, enabled: true });
    } catch (error) {
      console.error('Slide submission failed:', error.response?.data || error);
      alert(error.response?.data?.message || 'Failed to save slide');
    }
  };
  

  // Delete slide
  const handleDelete = async (id) => {
    try {
      await axios.delete(`/slides/${id}`);
      fetchSlides(); // Reload slides after deletion
    } catch (error) {
      console.error('Failed to delete slide:', error);
    }
  };

  return (
    <div className="p-6 space-y-6">
      <form onSubmit={handleSubmit} className="space-y-4 bg-white p-4 rounded shadow">
        <input
          name="title"
          type="text"
          placeholder="Title"
          value={form.title}
          onChange={handleChange}
          className="border p-2 w-full"
          required
        />
        <textarea
          name="description"
          placeholder="Description"
          value={form.description}
          onChange={handleChange}
          className="border p-2 w-full"
        />
        <input
          type="file"
          name="image"
          onChange={handleChange}
          className="w-full"
        />
        <label className="flex items-center space-x-2">
          <input
            type="checkbox"
            name="enabled"
            checked={form.enabled}
            onChange={handleChange}
          />
          <span>Enabled</span>
        </label>
        <button type="submit" className="bg-blue-500 text-white px-4 py-2 rounded">
          {editingSlideId ? 'Update Slide' : 'Add Slide'}
        </button>
      </form>

      <div className="grid grid-cols-2 gap-4 mt-6">
        {slides.map((slide) => (
          <div key={slide.id} className="p-4 border rounded shadow">
            <h3 className="text-xl font-bold">{slide.title}</h3>
            <p>{slide.description}</p>
            <img
              src={`http://localhost:8000/storage/${slide.image_path}`}
              alt="Slide"
              className="mt-2 h-40 object-cover"
            />
            <p className="text-sm mt-1">Enabled: {slide.enabled ? 'Yes' : 'No'}</p>
            <button
              onClick={() => handleEdit(slide.id)}
              className="mt-2 bg-yellow-500 text-white px-3 py-1 rounded"
            >
              Edit
            </button>
            <button
              onClick={() => handleDelete(slide.id)}
              className="mt-2 bg-red-500 text-white px-3 py-1 rounded"
            >
              Delete
            </button>
          </div>
        ))}
      </div>
    </div>
  );
};

export default SlideManager;