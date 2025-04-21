import React from 'react';
import { Link } from 'react-router-dom';  // Import Link for React Router



const Sidebar = () => {
  return (
    <div>
      {/* Sidebar */}
      <div className="sidebar" style={{ backgroundColor: "#343a40" }}> {/* Use a valid dark color */}
        <div className="sidebar-logo">
          {/* Logo Header */}
          <div className="logo-header" style={{ backgroundColor: "#343a40" }}>
            <a href="/admin/dashboard" className="logo">
              <img
                src="assets2/img/kaiadmin/logo_light.svg"
                alt="navbar brand"
                className="navbar-brand"
                height="20"
              />
            </a>
            <div className="nav-toggle">
              <button className="btn btn-toggle toggle-sidebar">
                <i className="gg-menu-right"></i>
              </button>
              <button className="btn btn-toggle sidenav-toggler">
                <i className="gg-menu-left"></i>
              </button>
            </div>
            <button className="topbar-toggler more">
              <i className="gg-more-vertical-alt"></i>
            </button>
          </div>
          {/* End Logo Header */}
        </div>
        <div className="sidebar-wrapper scrollbar scrollbar-inner">
          <div className="sidebar-content">
            <ul className="nav nav-secondary">
              <li className="nav-item active">
                <Link to="/admin/dashboard"> {/* Use Link for React Router navigation */}
                  <i className="fas fa-home"></i>
                  <p>Dashboard</p>
                </Link>
              </li>

              <li className="nav-section">
                <span className="sidebar-mini-icon">
                  <i className="fa fa-ellipsis-h"></i>
                </span>
                <h4 className="text-section">Components</h4>
              </li>

              {/* Base Items */}
              <li className="nav-item">
                <Link to="/admin/products"> {/* Updated to use Link */}
                  <i className="fas fa-box-open"></i>
                  <p>Products</p>
                </Link>
              </li>
              <li className="nav-item">
                <Link to="/admin/slideshow"> {/* Updated to use Link */}
                  <i className="fas fa-images"></i>
                  <p>Slideshows</p>
                </Link>
              </li>
              <li className="nav-item">
                <Link to="/admin/categories"> {/* Updated to use Link */}
                  <i className="fas fa-th-large"></i>
                  <p>Categories</p>
                </Link>
              </li>
              <li className="nav-item">
                <Link to="/admin/users"> {/* Updated to use Link */}
                  <i className="fas fa-users"></i>
                  <p>Users</p>
                </Link>
              </li>
              <li className="nav-item">
                <Link to="/admin/order"> {/* Updated to use Link */}
                  <i className="fas fa-cart-arrow-down"></i>
                  <p>Orders</p>
                </Link>
              </li>
            </ul>
          </div>
        </div>
      </div>
      {/* End Sidebar */}
    </div>
  );
}

export default Sidebar;
