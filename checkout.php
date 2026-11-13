<?php
session_start();
include 'db.php';

if (!isset($_POST['selected_items'])) {
    echo "<script>alert('Please select at least one item to checkout.'); window.location='cart.php';</script>";
    exit;
}

$selected_ids = $_POST['selected_items'];
if (!is_array($selected_ids)) $selected_ids = [$selected_ids];
$selected_ids = array_map('intval', $selected_ids);

$valid_ids = array_filter($selected_ids, fn($id) => isset($_SESSION['cart'][$id]));
if (empty($valid_ids)) {
    echo "<script>alert('Your selected items are not in the cart anymore.'); window.location='cart.php';</script>";
    exit;
}

$id_list = implode(',', $valid_ids);
$query = "SELECT * FROM products WHERE id IN ($id_list)";
$result = mysqli_query($conn, $query);
$total = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
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
                <img src="<?= htmlspecialchars($logo['logo_image']) ?>" alt="Fuggler Shop Logo" style="height: 60px;">
            </a>
        </div>
        <nav>
            <a href="index.php">Home</a>
            <a href="cart.php">Cart</a>
        </nav>
    </header>

    <!-- ===== CHECKOUT SECTION ===== -->
    <main class="product-section">
        <h2>Checkout</h2>
        <div class="checkout-summary">
            <h3>Your Order Summary</h3>
            <table class="checkout-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Details</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): 
                        $id = $row['id'];
                        $qty = $_SESSION['cart'][$id];
                        $subtotal = $row['price'] * $qty;
                        $total += $subtotal;
                    ?>
                    <tr>
                        <td><img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>"></td>
                        <td>
                            <strong><?= htmlspecialchars($row['name']) ?></strong><br>
                            <small><?= htmlspecialchars($row['description']) ?></small>
                        </td>
                        <td><?= $qty ?></td>
                        <td>₱<?= number_format($row['price'], 2) ?></td>
                        <td>₱<?= number_format($subtotal, 2) ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <?php
            $shipping_fee = 60;
            $grand_total = $total + $shipping_fee;
            ?>

            <div class="checkout-total">
                <p>Subtotal: ₱<?= number_format($total, 2) ?></p>
                <p>Shipping Fee: ₱<?= number_format($shipping_fee, 2) ?></p>
                <h3>Grand Total: ₱<?= number_format($grand_total, 2) ?></h3>
            </div>
        </div>

        <!-- ===== CUSTOMER DETAILS FORM ===== -->
        <form method="POST" action="confirmation.php" class="checkout-form">
            <?php foreach ($valid_ids as $id): ?>
                <input type="hidden" name="selected_items[]" value="<?= $id ?>">
            <?php endforeach; ?>

            <input type="hidden" name="total_amount" value="<?= $grand_total ?>">

            <label>Full Name:</label>
            <input type="text" name="name" required>

            <label>Email:</label>
            <input type="email" name="email" required>

            <label>Address:</label>
            <textarea name="address" rows="3" required></textarea>

            <button type="submit" name="checkout">Place Order</button>
        </form>
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
