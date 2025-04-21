<?php
ob_start();  // Start output buffering
?>
<!-- Sidebar -->
<div class="sidebar" data-background-color="gray">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="#68e0ed">
            <a href="index.php" class="logo">
                <img src="images/logo.png" alt="navbar brand" class="navbar-brand" height="20" />
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
        <!-- End Logo Header -->
    </div>

    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                <!-- Dashboard -->
                <li class="nav-item active">
                    <a data-bs-toggle="collapse" href="#dashboard" class="collapsed" aria-expanded="false">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="dashboard">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="index.php">
                                    <span class="sub-item">Dashboard 1</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Slideshow -->
                <li class="nav-item active">
                    <a data-bs-toggle="collapse" href="#slideshow" class="collapsed" aria-expanded="false">
                        <i class="fas fa-sliders-h"></i>
                        <p>Slideshow</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="slideshow">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="index.php?p=slideshow">
                                    <span class="sub-item">Slideshow</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Product -->
                <li class="nav-item active">
                    <a data-bs-toggle="collapse" href="#product" class="collapsed" aria-expanded="false">
                        <i class="fa fa-shopping-bag"></i>
                        <p>Product</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="product">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="index.php?p=product">
                                    <span class="sub-item">Product</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Categories -->
                <li class="nav-item active">
                    <a data-bs-toggle="collapse" href="#categories" class="collapsed" aria-expanded="false">
                        <i class="fas fa-toolbox"></i>
                        <p>Categories</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="categories">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="index.php?p=categories">
                                    <span class="sub-item">Categories</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Brand -->
                <li class="nav-item active">
                    <a data-bs-toggle="collapse" href="#brand" class="collapsed" aria-expanded="false">
                        <i class="fab fa-sellcast"></i>
                        <p>Brand</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="brand">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="index.php?p=brand">
                                    <span class="sub-item">Brand</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Page -->
                <li class="nav-item active">
                    <a data-bs-toggle="collapse" href="#page" class="collapsed" aria-expanded="false">
                        <i class="fas fa-box"></i>
                        <p>Page</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="page">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="index.php?p=page">
                                    <span class="sub-item">Page</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- User -->
                <li class="nav-item active">
                    <a data-bs-toggle="collapse" href="#user" class="collapsed" aria-expanded="false">
                        <i class="fas fa-user-alt"></i>
                        <p>User</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="user">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="index.php?p=user">
                                    <span class="sub-item">User</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Settings -->
                <li class="nav-item active">
                    <a data-bs-toggle="collapse" href="#settings" class="collapsed" aria-expanded="false">
                        <i class="fas fa-bars"></i>
                        <p>Settings</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="settings">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="index.php?p=setting">
                                    <span class="sub-item">Settings</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- End Sidebar -->
