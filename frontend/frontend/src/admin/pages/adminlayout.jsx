import React from "react";
import { Outlet } from "react-router-dom";
import Sidebar from "../components/sidebar";
import Header from "../components/header";
import Navbar from "../components/navbar";
import Footer from "../components/footer";

const AdminLayout = () => {
  return (
    <div className="wrapper">
      <Sidebar />
      <div className="main-panel">
        <div className="main-header">
          <div className="main-header-logo">
            <Header />
          </div>
          <Navbar />
        </div>
        <div className="container">
          <Outlet /> {/* ✅ This renders dashboard, categories, etc. */}
        </div>
        <Footer />
      </div>
    </div>
  );
};

export default AdminLayout;
