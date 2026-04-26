<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>About Us | Zippy</title>

  <!-- Font Awesome & CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
  <link rel="stylesheet" href="style.css" />

  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 0;
    }

    .heading span {
      color: #e74c3c;
      position: relative;
    }


    @keyframes slide {
      0%, 100% { width: 0; }
      50% { width: 100%; }
    }

    .divider {
      width: 80px;
      height: 4px;
      background: #e74c3c;
      margin: 2rem auto;
      border-radius: 2px;
    }

    .about-section {
      padding-top: 100px; /* Header niche space */
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      justify-content: center;
      background-color: #fdfdfd;
     }


    .about-text {
      flex: 1 1 400px;
      max-width: 600px;
      padding: 2rem;
    }

    .about-text h1 {
      font-size: 4rem;
      margin-bottom: 0.5rem;
    }

    .about-text h3 {
      font-size: 1.8rem;
      color: #e74c3c;
      margin-bottom: 1.5rem;
    }

    .about-text p {
      font-size: 1.2rem;
      color: #555;
      line-height: 1.7;
    }

    .about-img {
      flex: 1 1 300px;
      max-width: 500px;
      padding: 2rem;
      text-align: center;
    }

    .about-img img {
      width: 100%;
      border-radius: 10px;
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .about-img img:hover {
      transform: scale(1.05);
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
      cursor: pointer;
    }

    .features {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-around;
      padding: 3rem 5%;
      background: #fff8f3;
    }

    .feature-box {
      flex: 1 1 250px;
      text-align: center;
      padding: 2rem;
    }

    .feature-box i {
      font-size: 3rem;
      color: #e74c3c;
      margin-bottom: 1rem;
    }

    .feature-box h3 {
      font-size: 1.5rem;
      margin-bottom: 1rem;
    }

    .stats {
      display: flex;
      justify-content: space-around;
      background: #fff;
      padding: 3rem 5%;
      flex-wrap: wrap;
      text-align: center;
    }

    .stats .box {
      flex: 1 1 200px;
      padding: 1.5rem;
    }

    .stats .box h3 {
      font-size: 2.5rem;
      color: #e74c3c;
      margin-bottom: 0.5rem;
    }

    .stats .box p {
      font-size: 1.2rem;
      color: #555;
    }

    .team-section {
      background: #fafafa;
      padding: 4rem 5%;
      text-align: center;
    }

    .team-section h2 {
      font-size: 3rem;
      margin-bottom: 2rem;
    }

    .team-members {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 2rem;
    }

    .member {
      background: #fff;
      padding: 1.5rem;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      max-width: 250px;
      transition: transform 0.3s ease;
    }

    .member:hover {
      transform: translateY(-10px);
    }

    .member img {
      width: 180px;
      height: 180px;
      border-radius: 50%;
      object-fit: cover;
      margin: 0 auto;
      display: block;
    }

    .member h4 {
      font-size: 1.3rem;
      margin: 0.5rem 0;
    }

    .member p {
      font-size: 1rem;
      color: #666;
    }

    @media(max-width: 768px) {
      .about-section, .features, .team-section, .stats {
        padding: 2rem 3%;
      }
    }
  </style>
</head>

<body>

  <!-- Header -->
  <?php include 'header.php'; ?>

  <!-- About Section -->
  <section class="about-section">
    <div class="about-img">
      <img src="../images/FD-UIanimation.gif" alt="About Zippy">
    </div>
    <div class="about-text">
      <h1 class="heading"> About <span> Zippy </span> </h1>
      <h3>Delicious food, Delivered fast.</h3>
      <p>
        Zippy is India’s leading food delivery platform. With a wide selection of restaurants, fast delivery, and reliable service, we’re here to make your food cravings a thing of the past. Whether you’re ordering from your favorite local spot or discovering something new, Zippy delivers happiness in every bite.
      </p>
    </div>
  </section>

  <div class="divider"></div>

  <!-- Features Section -->
  <section class="features">
    <div class="feature-box">
      <i class="fas fa-shipping-fast"></i>
      <h3>Fast Delivery</h3>
      <p>We deliver food in record time, hot and fresh right at your door.</p>
    </div>
    <div class="feature-box">
      <i class="fas fa-utensils"></i>
      <h3>Variety of Cuisines</h3>
      <p>Choose from hundreds of dishes and global cuisines.</p>
    </div>
    <div class="feature-box">
      <i class="fas fa-wallet"></i>
      <h3>Secure Payments</h3>
      <p>Pay safely with multiple payment options, including COD.</p>
    </div>
  </section>

  <div class="divider"></div>

  <!-- Stats Section -->
  <section class="stats">
    <div class="box">
      <h3>500K+</h3>
      <p>Happy Customers</p>
    </div>
    <div class="box">
      <h3>100+</h3>
      <p>Partner Restaurants</p>
    </div>
    <div class="box">
      <h3>30min</h3>
      <p>Avg. Delivery Time</p>
    </div>
  </section>

  <div class="divider"></div>

  <!-- Team Section -->
  <section class="team-section">
    <h2>Meet Our Team</h2>
    <div class="team-members">
      <div class="member">
        <img src="../images/order-img.jpg" alt="Team Member 1">
        <h4>Riya Shah</h4>
        <p>Founder & CEO</p>
      </div>
      <div class="member">
        <img src="../images/p1.jpg" alt="Team Member 2">
        <h4>Lisa Charli</h4>
        <p>Head of Operations</p>
      </div>
      <div class="member">
        <img src="../images/p2.jpg" alt="Team Member 3">
        <h4>Neha Mehta</h4>
        <p>Lead Developer</p>
      </div>
    </div>
  </section>
  <section class="contact-info" style="padding: 50px 0; background: #fff8f3; text-align: center;">
  <h2 style="color:#e74c3c; font-size: 2.5rem; margin-bottom: 30px;">Contact Us</h2>

  <div style="display: flex; justify-content: center; gap: 80px; flex-wrap: wrap;">

    <div style="display: flex; align-items: center; gap: 15px; font-size: 1.3rem; color: #333;">
      <i class="fas fa-map-marker-alt" style="color:#e74c3c; font-size: 1.8rem;"></i>
      <span><b>Zippy HQ</b>, Ahmedabad, India</span>
    </div>

    <div style="display: flex; align-items: center; gap: 15px; font-size: 1.3rem; color: #333;">
      <i class="fas fa-envelope" style="color:#e74c3c; font-size: 1.8rem;"></i>
      <span><b>Email:</b> support@zippy.com</span>
    </div>

    <div style="display: flex; align-items: center; gap: 15px; font-size: 1.3rem; color: #333;">
      <i class="fas fa-globe" style="color:#e74c3c; font-size: 1.8rem;"></i>
      <span><b>Website:</b> www.zippy.com</span>
    </div>

  </div>
</section>



  <!-- Footer -->
  <?php include 'footer.php'; ?>

  <script src="script.js"></script>
</body>
</html>
