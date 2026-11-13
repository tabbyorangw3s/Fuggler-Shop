<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Fuggler Shop</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>

  <!-- ===== HEADER / NAVBAR ===== -->
<header>
  <?php
  $logoQuery = "SELECT logo_image FROM site_header ORDER BY id DESC LIMIT 1";
  $logoResult = mysqli_query($conn, $logoQuery);
  $logo = mysqli_fetch_assoc($logoResult);
  ?>
  
  <div class="logo">
    <a href="index.php">
      <img 
        src="<?= htmlspecialchars($logo['logo_image']) ?>" 
        alt="Fuggler Shop Logo" 
        style="height: 60px; width: auto;">
    </a>
  </div>
  <nav>
    <a href="products.php?category=Plushie Fugglers">Plushie Fugglers</a>
    <a href="products.php?category=Classic Keychain Fuggler">Classic Keychain Fuggler</a>
    <a href="products.php?category=Rainbow Keychain Fuggler">Rainbow Keychain Fuggler</a>
    <a href="products.php?category=Mini-baby Fugglers">Mini-baby Fugglers</a>
    <a href="cart.php" class="cart-link">View Cart</a>
  </nav>
</header>

  <!-- ===== HERO / BANNER SECTION ===== -->
  <section class="hero">
    <div class="hero-text">
      <h1>Welcome to the Fuggler Shop!</h1>
      <p>Adopt your favorite mischievous monster before someone else does!</p>
    </div>
    <div class="hero-img">
  <?php
  $bannerQuery = "SELECT image FROM banner ORDER BY id DESC LIMIT 1";
  $bannerResult = mysqli_query($conn, $bannerQuery);
  $banner = mysqli_fetch_assoc($bannerResult);
  ?>
  <img src="<?= htmlspecialchars($banner['image']) ?>" alt="Fuggler Banner">
</div>
  </section>

  <!-- ===== PRODUCTS GRID (sample dynamic section) ===== -->
  <main class="product-section">
    <h2>Featured Fugglers</h2>
    <div class="product-container">
      <?php
      $query = "SELECT * FROM products LIMIT 4";
      $result = mysqli_query($conn, $query);
      while ($row = mysqli_fetch_assoc($result)): ?>
        <div class="product-card">
          <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
          <h4><?= htmlspecialchars($row['name']) ?></h4>
          <p class="price">₱<?= number_format($row['price'], 2) ?></p>
          <form method="POST" action="cart.php">
            <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
            <button type="submit" name="add_to_cart">Add to Cart</button>
          </form>
        </div>
      <?php endwhile; ?>
    </div>
  </main>

  <!-- ===== FOOTER ===== -->
<footer>
  <h2 class="footer-title">Cute cuddly monsters for you.</h2>

  <div class="footer-columns">

    <div class="footer-col">
      <h4>Quicklinks</h4>
      <a href="#">Home</a>
      <a href="#">News</a>
      <a href="#">Where to Buy</a>
      <a href="#">Recalls & Safety</a>
    </div>

    <div class="footer-col">
      <h4>Our Company</h4>
      <a href="#">About Us</a>
      <a href="#">Careers</a>
      <a href="#">Contact</a>
    </div>

    <div class="footer-col">
      <h4>Follow Us</h4>
      <a href="#">Facebook</a>
      <a href="#">Instagram</a>
      <a href="#">TikTok</a>
    </div>

    <div class="footer-col">
      <h4>Policies</h4>
      <a href="#">Terms of Use</a>
      <a href="#">Privacy Policy</a>
      <a href="#">Cookie Policy</a>
    </div>

  </div>
</footer>
