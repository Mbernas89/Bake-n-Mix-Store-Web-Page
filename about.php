<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>About Bake N' Mix</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    .hero-section {
      background-color: #c1121f; 
      height: 300px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      text-align: center;
    }

    .about-img {
      width: 100%;
      border-radius: 12px;
      object-fit: cover;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
  </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<!-- RED HEADER -->
<div class="hero-section mb-5">
  <div>
    <h1 class="fw-bold display-4">About Bake N' Mix</h1>
    <p class="lead mt-2">Your trusted baking supply store</p>
  </div>
</div>

<div class="container">

  <!-- Intro Section -->
  <div class="row align-items-center mb-5">
    <div class="col-md-6">
      <h2 class="fw-bold mb-3">Who We Are</h2>
      <p class="lead">
        Bake N' Mix is a trusted baking supplies store offering affordable tools, 
        ingredients, and equipment for home bakers, students, and small businesses.
      </p>
      <p>
        From spatulas to specialty ingredients, we aim to provide everything you 
        need to bring your baking ideas to life — whether you're a beginner or an expert.
      </p>
    </div>
    <div class="col-md-6">
      <img src="images/tools.jpg" class="about-img" alt="Baking tools">
    </div>
  </div>

  <!-- Vision & Mission -->
  <div class="row mb-5">
    <div class="col-md-6 mb-4">
      <div class="p-4 bg-light rounded shadow-sm">
        <h3 class="fw-bold mb-3">Our Mission</h3>
        <p>
          To make baking accessible and enjoyable for everyone by providing 
          quality and budget-friendly baking tools and ingredients.
        </p>
      </div>
    </div>
    <div class="col-md-6 mb-4">
      <div class="p-4 bg-light rounded shadow-sm">
        <h3 class="fw-bold mb-3">Our Vision</h3>
        <p>
          To become the leading community-friendly baking supply store 
          supporting hobbyists, micro-bakers, and young entrepreneurs.
        </p>
      </div>
    </div>
  </div>

  <!-- Gallery -->
  <h3 class="fw-bold text-center mb-4">Our Store & Products</h3>
  <div class="row g-4 mb-5">
    <div class="col-md-4">
      <img src="images/fernagelatine.jpg" class="about-img" alt="Store image 1">
    </div>
    <div class="col-md-4">
      <img src="images/creamcheese.jpg" class="about-img" alt="Store image 2">
    </div>
    <div class="col-md-4">
      <img src="images/yeast.jpg" class="about-img" alt="Store image 3">
    </div>
  </div>

</div>
<footer class="bg-dark text-white py-4 mt-5">
    <div class="container text-center">
        <h5 class="mb-1">Bake N' Mix</h5>
        <p class="mb-2">Your one-stop shop for baking supplies</p>

        <div class="mb-3">
            <a href="https://www.facebook.com/BakeAndMixBakingSupplies" class="text-white fs-4 me-3">
                <i class="fa-brands fa-facebook"></i>
            </a>
        </div>

        <small>
            © <?= date('Y') ?> Bake N' Mix. All rights reserved.
        </small>
    </div>
</footer>

<footer class="bg-dark text-wh
