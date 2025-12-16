<?php
require 'config.php';

//redirect to login if not logged in
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

//redirect to home if cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: index.php');
    exit;
}

//compute cart total
$cart = $_SESSION['cart'];
$total = 0;
foreach ($cart as $c) {
    $total += $c['price'] * $c['qty'];
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Your Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<?php include 'navbar.php'; ?>

<div class="container">

    <h1 class="mb-4">Your Cart</h1>

    <table class="table table-bordered align-middle">
        <thead>
            <tr>
                <th>Product</th>
                <th width="120">Price</th>
                <th width="80">Qty</th>
                <th width="120">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cart as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['name']) ?></td>
                    <td>₱<?= number_format($p['price'], 2) ?></td>
                    <td><?= (int) $p['qty'] ?></td>
                    <td>₱<?= number_format($p['price'] * $p['qty'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h4 class="text-end mb-4">
        Total: ₱<?= number_format($total, 2) ?>
    </h4>

    <!-- CHECKOUT FORM -->
    <form method="post" action="checkout.php">

        <!-- PHONE -->
        <div class="mb-3">
            <label class="form-label fw-bold">Phone Number</label>
            <input
                type="text"
                name="phone"
                class="form-control"
                placeholder="09XXXXXXXXX"
                required
            >
        </div>

        <!-- ADDRESS -->
        <div class="mb-3">
            <label class="form-label fw-bold">Delivery Address</label>
            <textarea
                name="address"
                class="form-control"
                rows="3"
                placeholder="Complete address"
                required
            ></textarea>
        </div>

        <!-- PAYMENT -->
        <div class="mb-4">
            <label class="form-label fw-bold">Payment Method</label>
            <select name="payment_method" class="form-select" required>
                <option value="">-- Select Payment --</option>
                <option value="COD">Cash on Delivery</option>
                <option value="Pickup">Pickup</option>
                <option value="GCash">GCash (Online Payment)</option>
            </select>
        </div>

        <button class="btn btn-danger w-100 py-2">
            Place Order
        </button>

    </form>

</div>

</body>
</html>
