<?php
session_start();
include 'db.php';

if (!isset($_POST['checkout'])) {
    echo "<script>alert('Invalid access. Please checkout properly.'); window.location='cart.php';</script>";
    exit;
}

$name = htmlspecialchars($_POST['name']);
$email = htmlspecialchars($_POST['email']);
$address = htmlspecialchars($_POST['address']);
$total_amount = number_format($_POST['total_amount'], 2);
$selected_items = $_POST['selected_items'] ?? [];

if (empty($selected_items)) {
    echo "<script>alert('No items found in your order.'); window.location='cart.php';</script>";
    exit;
}

$id_list = implode(',', array_map('intval', $selected_items));
$query = "SELECT * FROM products WHERE id IN ($id_list)";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order Confirmation</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>

  <!-- ===== HEADER ===== -->
  <header>
    <?php
    $logoQuery = "SELECT logo_image FROM site_header ORDER BY id DESC LIMIT 1";
    $logoResult = mysqli_query($conn, $logoQuery);
    $logo = mysqli_fetch_assoc($logoResult);
    ?>
    <div class="logo">
      <a href="index.php">
        <img src="<?= htmlspecialchars($logo['logo_image']) ?>" alt="Fuggler Logo" style="height: 60px;">
      </a>
    </div>
    <nav>
      <a href="index.php">Home</a>
      <a href="cart.php">Cart</a>
    </nav>
  </header>

  <!-- ===== CONFIRMATION SECTION ===== -->
  <main class="confirmation-container">
    <h1>Thank you for your Furrychase, <?= ucfirst($name) ?>!</h1>
    <p>Your order has been placed successfully. Below are your order details:</p>

    <div class="order-details">
      <h3>Order Summary</h3>
      <table>
        <thead>
          <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Subtotal</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $total = 0;
          while ($row = mysqli_fetch_assoc($result)):
              $qty = $_SESSION['cart'][$row['id']];
              $subtotal = $row['price'] * $qty;
              $total += $subtotal;
          ?>
          <tr>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= $qty ?></td>
            <td>₱<?= number_format($row['price'], 2) ?></td>
            <td>₱<?= number_format($subtotal, 2) ?></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>

      <div class="total-box">
        Total Amount Paid: ₱<?= $total_amount ?>
      </div>
    </div>

    <div class="customer-info">
      <strong>Delivery Information</strong><br>
      <p><b>Name:</b> <?= ucfirst($name) ?><br>
      <b>Email:</b> <?= $email ?><br>
      <b>Address:</b> <?= nl2br($address) ?></p>
    </div>

    <a href="index.php" class="home-btn">Back to Home</a>
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

</body>
</html>
