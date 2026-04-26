<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// Optional: Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

$pageTitle = $pageTitle ?? 'Admin Panel';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($pageTitle); ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">

<style>
    body { background-color: #f8f9fa; }
    .navbar {
        background: linear-gradient(90deg, #dc3545, #b02a37);
    }
    .navbar .nav-link {
        color: #fff !important;
        font-weight: 500;
        margin-right: 15px;
    }
    .navbar .nav-link:hover, .navbar .dropdown-menu a:hover {
        background: rgba(255,255,255,0.2);
        border-radius: 5px;
    }
    .navbar-brand {
        color: #fff !important;
        font-weight: bold;
    }
    .dropdown-menu {
        background: #b02a37;
        border: none;
    }
    .dropdown-menu a {
        color: white !important;
    }
    .content {
        padding: 20px;
    }
    .dropdown-menu a:hover {
        background: rgba(255,255,255,0.3);
    }
</style>
</head>
<body>
    

<!-- Top Navbar -->
<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">
        <i class="bi bi-speedometer2"></i> Admin Panel
    </a>
    <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="adminNavbar">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
            <a class="nav-link" href="index.php">
                <i class="bi bi-house-door"></i> Dashboard
            </a>
        </li>
        
        <!-- Food Dropdown -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
            <i class="bi bi-egg-fried"></i> Food
          </a>
          <ul class="dropdown-menu">
            <li>
                <a class="dropdown-item" href="add-food.php">
                    <i class="bi bi-plus-circle"></i> Add Food
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="manage-food.php">
                    <i class="bi bi-pencil-square"></i> Manage Food
                </a>
            </li>
          </ul>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="orders-history.php">
                <i class="bi bi-receipt"></i> Orders
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="customers.php">
                <i class="bi bi-people"></i> Customers
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="reports.php">
                <i class="bi bi-graph-up"></i> Reports
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-warning" href="logout.php">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Page Content -->
<div class="content">
