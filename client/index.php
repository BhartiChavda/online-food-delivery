<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Zippy | Food Delivery</title>

  <!-- Font Awesome -->
  <link rel="stylesheet" 
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />

  <!-- Custom CSS -->
  <link rel="stylesheet" href="style.css" />

  <style>
    /* ---------- HERO SECTION ---------- */
    .hero {
      position: relative;
      height: 100vh;
      overflow: hidden;
    }
    .bg-video {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
      z-index: -1;
    }
    .hero-content {
      position: relative;
      color: white;
      text-align: center;
      top: 50%;
      transform: translateY(-50%);
      padding: 0 15px;
    }
    .hero-content h1 {
      font-size: 4rem;
      margin-bottom: 10px;
    }
    .hero-content h2 {
      font-size: 2.5rem;
      margin-bottom: 15px;
    }
    .hero-content p {
      font-size: 1.2rem;
      margin-bottom: 20px;
    }

    /* ---------- SEARCH BAR ---------- */
    .search-bar {
      display: flex;
      gap: 10px;
      justify-content: center;
      flex-wrap: wrap;
    }
    .search-bar input {
      padding: 10px;
      width: 250px;
      border-radius: 5px;
      border: 1px solid #ccc;
      font-size: 16px;
    }
    .search-bar button {
      padding: 10px 15px;
      background: #e74c3c;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
      transition: background 0.3s;
    }
    .search-bar button:hover {
      background: #c0392b;
    }

    /* ---------- HOME SECTION ---------- */
    .home {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      justify-content: space-around;
      padding: 50px 5%;
      background: #fff;
    }
    .home .content {
      flex: 1 1 500px;
      max-width: 600px;
    }
    .home .content h3 {
      font-size: 2.5rem;
      margin-bottom: 15px;
      color: #333;
    }
    .home .content p {
      font-size: 1.2rem;
      line-height: 1.6;
      color: #666;
    }
    .home .image {
      flex: 1 1 400px;
      text-align: center;
    }
    .home .image img {
      width: 100%;
      max-width: 550px; /* ✅ Bigger Burger */
      height: auto;
    }

    /* ---------- STEPS SECTION ---------- */
    .step-container {
      text-align: center;
      padding: 50px 5%;
    }
    .steps {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 20px;
      margin-top: 30px;
    }
    .steps .box {
      flex: 1 1 200px;
      max-width: 220px;
      padding: 15px;
      border: 1px solid #ddd;
      border-radius: 10px;
      background: #fff;
      transition: transform 0.3s ease;
    }
    .steps .box:hover {
      transform: translateY(-5px);
    }
    .steps img {
      width: 100%;
      border-radius: 10px;
    }
  </style>
</head>

<body>

  <!-- HERO SECTION -->
  <section class="hero">
    <video autoplay muted loop playsinline class="bg-video">
      <source src="video/food.mp4" type="video/mp4" />
      Your browser does not support the video tag.
    </video>

    <div class="hero-content">
      <h1>Zippy</h1>
      <h2>India’s #1 <br> Food Delivery</h2>
      <p>Experience fast & easy online delivery on Zippy</p>

      <!-- SEARCH BAR -->
      <form action="search.php" method="GET" class="search-bar">
        <input type="text" name="query" placeholder="Search for Pizza, Burger..." required>
        <button type="submit"><i class="fas fa-search"></i></button>
      </form>
    </div>
  </section>

  <!-- HEADER -->
  <?php include 'header.php'; ?>

  <!-- HOME SECTION -->
  <section class="home" id="home">
    <div class="content">
      <h3>Fresh, Fast & Flavorful</h3>
      <p>Satisfy your cravings with chef-crafted dishes made from the freshest
        ingredients. Delivered fast & with care.</p>
    </div>
    <div class="image">
      <img src="../images/home-img.png" alt="Delicious food" />
    </div>
  </section>

  <!-- STEPS SECTION -->
  <div class="step-container">
    <h1 class="heading">How it <span>Works</span></h1>
    <section class="steps">
      <div class="box">
        <img src="../images/step-1.jpg" alt="">
        <h3>Choose your favorite food</h3>
      </div>
      <div class="box">
        <img src="../images/step-2.jpg" alt="">
        <h3>Free and fast delivery</h3>
      </div>
      <div class="box">
        <img src="../images/step-3.jpg" alt="">
        <h3>Easy payment methods</h3>
      </div>
      <div class="box">
        <img src="../images/step-4.jpg" alt="">
        <h3>Finally, enjoy your food</h3>
      </div>
    </section>
  </div>

  <!-- FOOTER -->
  <?php include 'footer.php'; ?>

  <!-- SCROLL TO TOP -->
  <a href="#home" class="fas fa-angle-up" id="scroll-top"></a>

  <!-- LOADER -->
  <div class="loader-container">
    <img src="../images/loader.gif.gif" alt="Loading...">
  </div>

  <!-- SCRIPT -->
  <script src="script.js"></script>

</body>
</html>
