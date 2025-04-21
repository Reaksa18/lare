import './App.css';

// Admin Pages
import AdminLayout from "./admin/pages/adminlayout";
import Products from "./admin/pages/products";
import Categories from "./admin/pages/categories";
import Dashboard from "./admin/pages/dashboard";
import Slideshow from "./admin/pages/slideshow";
import Order from "./admin/pages/order";
import Users from "./admin/pages/users";
import AdminLogin from "./admin/pages/AdminLogin";
import AdminRegister from "./admin/pages/AdminRegister";
import AdminLogout from "./admin/pages/AdminLogout"; // Default import


// E-commerce Pages
import About from './Pages/About';
import Aboutus from './Pages/aboutus';
import Contact from './Pages/Contact';
import Blog_List from './Pages/Blog_List';
import Product from './Pages/Product';
import Home from './Pages/Home';
import Testimonial from './Pages/Testimonial';
import SingleProduct from './Pages/SingleProduct';
import Cart from './Pages/Cart';
import Payment from './Pages/Payment'; 
import Login from './Pages/Login';
import Register from './Pages/Register';
import { BrowserRouter, Routes, Route, useLocation, Navigate } from 'react-router-dom';
import Header from './components/Header';
import Footer from './components/Footer';
import { CartProvider } from './components/CartContext';
import Confirmation from './Pages/Confirmation';

function App() {
  return (
    <CartProvider>
      <BrowserRouter>
        <MainRoutes />
      </BrowserRouter>
    </CartProvider>
  );
}

// 🔐 Private route for admin
const PrivateAdminRoute = ({ children }) => {
  const token = localStorage.getItem("adminToken");
  return token ? children : <Navigate to="/admin/login" />;
};

function MainRoutes() {
  const location = useLocation();
  const isAdminRoute = location.pathname.startsWith('/admin');

  return (
    <>
      {/* Header/Footer only for customer routes */}
      {!isAdminRoute && <Header />}

      <Routes>
        {/* Public E-commerce Routes */}
        <Route path="/" element={<Home />} />
        <Route path="/about" element={<About />} />
        <Route path="/aboutus" element={<Aboutus />} />
        <Route path="/cart" element={<Cart />} />
        <Route path="/testimonial" element={<Testimonial />} />
        <Route path="/product" element={<Product />} />
        <Route path="/product/:id" element={<SingleProduct />} />
        <Route path="/blog_list" element={<Blog_List />} />
        <Route path="/contact" element={<Contact />} />
        <Route path="/payment" element={<Payment />} />
        <Route path="/confirmation" element={<Confirmation />} />
        <Route path="/login" element={<Login />} />
        <Route path="/register" element={<Register />} />

        {/* Admin Auth Routes (public) */}
        <Route path="/admin/login" element={<AdminLogin />} />
        <Route path="/admin/register" element={<AdminRegister />} />

        {/* Admin Protected Routes */}
        <Route path="/admin" element={
          <PrivateAdminRoute>
            <AdminLayout />
          </PrivateAdminRoute>
        }>
          <Route index element={<Dashboard />} />
          <Route path="dashboard" element={<Dashboard />} />
          <Route path="products" element={<Products />} />
          <Route path="categories" element={<Categories />} />
          <Route path="slideshow" element={<Slideshow />} />
          <Route path="order" element={<Order />} />
          <Route path="users" element={<Users />} />
          <Route path="logout" element={<AdminLogout />} />

        </Route>
      </Routes>

      {!isAdminRoute && <Footer />}
    </>
  );
}

export default App;
