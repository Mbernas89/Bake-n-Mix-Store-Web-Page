<?php
require 'config.php';

// redirect to home if not logged in or cart is empty
if (!isset($_SESSION['user']) || empty($_SESSION['cart'])) {
    header('Location: index.php');
    exit;
}

$user = $_SESSION['user'];
$phone   = trim($_POST['phone'] ?? '');
$address = trim($_POST['address'] ?? '');
$payment = $_POST['payment_method'] ?? '';


// validate inputs
if ($phone === '' || $address === '') {
    die('Missing phone or address.');
}

if (!in_array($payment, ['COD', 'Pickup', 'GCash'])) {
    die('Invalid payment method.');
}

// calculate total
$total = 0;
foreach ($_SESSION['cart'] as $it) {
    $total += $it['price'] * $it['qty'];
}

// insert order
$stmt = $mysqli->prepare("
    INSERT INTO orders (user_id, customer_name, phone, address, payment_method, total)
    VALUES (?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "issssd",
    $user['id'],
    $user['username'],
    $phone,
    $address,
    $payment,
    $total
);


$stmt->execute();

$order_id = $stmt->insert_id;

// insert order items
$itemStmt = $mysqli->prepare("
    INSERT INTO order_items
    (order_id, product_id, product_name, qty, price, subtotal)
    VALUES (?,?,?,?,?,?)
");

foreach ($_SESSION['cart'] as $it) {
    $subtotal = $it['price'] * $it['qty'];
    $itemStmt->bind_param(
        "iisidd",
        $order_id,
        $it['id'],
        $it['name'],
        $it['qty'],
        $it['price'],
        $subtotal
    );
    $itemStmt->execute();
}

// get items for receipt
$items = $_SESSION['cart']; 
// clear cart
$_SESSION['cart'] = [];

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Order Receipt</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        .receipt {
            max-width: 600px;
            margin: auto;
            border: 1px dashed #ccc;
            padding: 25px;
            background: #fff;
        }
        .receipt-header {
            text-align: center;
            border-bottom: 1px dashed #ccc;
            margin-bottom: 20px;
        }
        .receipt-footer {
            border-top: 1px dashed #ccc;
            margin-top: 20px;
            padding-top: 10px;
            text-align: center;
            font-size: 14px;
        }
    </style>
</head>

<body class="p-4">

<?php include 'navbar.php'; ?>

<div class="receipt">

    <!-- HEADER -->
    <div class="receipt-header">
        <h3 class="fw-bold">Bake N' Mix</h3>
        <p class="mb-1">Official Receipt</p>
        <small id="receiptDate"></small>
    </div>

    <!-- CUSTOMER INFO -->
    <p><strong>Order #:</strong> <?= $order_id ?></p>
    <p><strong>Customer:</strong> <?= htmlspecialchars($user['username']) ?></p>
    <p><strong>Phone:</strong> <?= htmlspecialchars($phone) ?></p>
    <p><strong>Payment:</strong> <?= htmlspecialchars($payment) ?></p>

    <?php if ($payment === 'GCash'): ?>
        <div class="alert alert-info py-2">
            GCash payment (TEST MODE – no real money charged)
        </div>
    <?php endif; ?>

    <!-- ITEMS -->
    <table class="table table-sm mt-3">
        <thead>
            <tr>
                <th>Item</th>
                <th class="text-center">Qty</th>
                <th class="text-end">Price</th>
                <th class="text-end">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $it): ?>
                <tr>
                    <td><?= htmlspecialchars($it['name']) ?></td>
                    <td class="text-center"><?= $it['qty'] ?></td>
                    <td class="text-end">₱<?= number_format($it['price'], 2) ?></td>
                    <td class="text-end">
                        ₱<?= number_format($it['price'] * $it['qty'], 2) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- TOTAL -->
    <h5 class="text-end">
        TOTAL: ₱<?= number_format($total, 2) ?>
    </h5>

    <!-- FOOTER -->
    <div class="receipt-footer">
        <p>Thank you for shopping with Bake N' Mix! 🍰</p>
        <button onclick="window.print()" class="btn btn-outline-secondary btn-sm">
            Print Receipt
        </button>
        <br><br>
        <a href="index.php" class="btn btn-danger btn-sm">
            Continue Shopping
        </a>
    </div>

</div>

<script>
    // Auto date/time
    const now = new Date();
    document.getElementById('receiptDate').innerText =
        now.toLocaleString();
</script>

</body>
</html>

