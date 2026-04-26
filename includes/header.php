<?php // admin/includes/header.php ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Zippy Admin</title>
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body{background:#f7f7fb;}
    .app-navbar{background:#111827;}
    .app-navbar .navbar-brand,.app-navbar .nav-link{color:#fff}
    .stat-card{border:0;border-radius:1rem;box-shadow:0 10px 30px rgba(0,0,0,.06);}
    .table thead th{background:#f1f5f9;}
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg app-navbar mb-4">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="./customers.php">Zippy Admin</a>
    <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div id="nav" class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="./customers.php">Customers</a></li>
        <li class="nav-item"><a class="nav-link" href="./reports.php">Reports</a></li>
      </ul>
    </div>
  </div>
</nav>
<div class="container-fluid px-4">
