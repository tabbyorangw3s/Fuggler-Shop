<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['update_qty'])) {
    $val = $_POST['update_qty'];
    if (strpos($val, '_') !== false) {
      list($action, $id) = explode('_', $val);
      $id = intval($id);
      if (isset($_SESSION['cart'][$id])) {
        if ($action === 'plus') {
          $_SESSION['cart'][$id]++;
        } elseif ($action === 'minus') {
          $_SESSION['cart'][$id] = max(1, $_SESSION['cart'][$id] - 1);
        }
      }
    }
    header("Location: cart.php");
    exit;
  }

  if (isset($_POST['remove_item'])) {
    $val = $_POST['remove_item'];
    if (strpos($val, '_') !== false) {
      list($_, $id) = explode('_', $val);
      $id = intval($id);
      unset($_SESSION['cart'][$id]);
    }
    header("Location: cart.php");
    exit;
  }
}

if (isset($_POST['add_to_cart'])) {
  $product_id = intval($_POST['product_id']);
  if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
  if (!isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] = 1;
  } else {
    $_SESSION['cart'][$product_id]++;
  }
  header("Location: cart.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Your Cart</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>

<header>
  <?php
  $logoQuery = "SELECT logo_image FROM site_header ORDER BY id DESC LIMIT 1";
  $logoResult = mysqli_query($conn, $logoQuery);
  $logo = mysqli_fetch_assoc($logoResult);
  ?>
  <div class="logo">
    <a href="index.php">
      <img src="<?= htmlspecialchars($logo['logo_image']) ?>" alt="Fuggler Shop Logo" style="height: 60px; width: auto;">
    </a>
  </div>

  <nav>
    <a href="products.php?category=Plushie Fugglers">Plushie Fugglers</a>
    <a href="products.php?category=Classic Keychain Fuggler">Classic Keychain Fuggler</a>
    <a href="products.php?category=Rainbow Keychain Fuggler">Rainbow Keychain Fuggler</a>
    <a href="products.php?category=Mini-baby Fugglers">Mini-baby Fugglers</a>
  </nav>
</header>

<main class="product-section">
  <h2>Your Shopping Cart</h2>

  <?php
  if (!empty($_SESSION['cart'])):
    $idsArr = array_map('intval', array_keys($_SESSION['cart']));
    $ids = implode(',', $idsArr);
    if ($ids === '') $ids = '0';
    $query = "SELECT * FROM products WHERE id IN ($ids)";
    $result = mysqli_query($conn, $query);
    $grand_total = 0;
  ?>

  <form id="cartActionForm" method="POST" action="cart.php" style="display:none;">
    <input type="hidden" name="update_qty" value="">
    <input type="hidden" name="remove_item" value="">
  </form>

  <form method="POST" action="checkout.php" id="checkoutForm">
    <div class="cart-container">
      <?php while ($row = mysqli_fetch_assoc($result)):
        $id = (int)$row['id'];
        $qty = isset($_SESSION['cart'][$id]) ? $_SESSION['cart'][$id] : 0;
        $subtotal = $row['price'] * $qty;
        $grand_total += $subtotal;
      ?>
      <div class="cart-item">

        <input type="checkbox" name="selected_items[]" value="<?= $id ?>" class="cart-checkbox">

        <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">

        <div class="cart-details">
          <h4><?= htmlspecialchars($row['name']) ?></h4>
          <p class="cart-desc"><?= htmlspecialchars($row['description']) ?></p>
          <p class="price">₱<?= number_format($row['price'], 2) ?></p>

          <div class="qty-control">

            <button
              type="submit"
              form="cartActionForm"
              name="update_qty"
              value="minus_<?= $id ?>"
              class="qty-btn">−</button>

            <input type="number" value="<?= $qty ?>" readonly min="1" class="qty-input">

            <button
              type="submit"
              form="cartActionForm"
              name="update_qty"
              value="plus_<?= $id ?>"
              class="qty-btn">+</button>
          </div>
          <button
            type="submit"
            form="cartActionForm"
            name="remove_item"
            value="remove_<?= $id ?>"
            class="remove-btn"
            style="margin-top:5px;">Remove</button>

          <p>Subtotal: ₱<?= number_format($subtotal, 2) ?></p>
        </div>
      </div>
      <?php endwhile; ?>
    </div>

    <div class="cart-total-container">
  <p class="selected-total">Total (All Items): ₱<span id="totalAmount"><?= number_format($grand_total, 2) ?></span></p>
  <button type="submit" name="proceed_checkout" class="checkout-btn">Proceed to Checkout</button>
</div>

  <?php else: ?>
    <p>Your cart is empty! <a href="index.php">Go back to shop</a></p>
  <?php endif; ?>
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

<script>
document.getElementById('checkoutForm').addEventListener('submit', function(e) {
  const checked = document.querySelectorAll('input[name="selected_items[]"]:checked');
  if (checked.length === 0) {
    e.preventDefault();
    alert('Please select at least one item to proceed to checkout.');
  }
});
</script>

</body>
</html>
