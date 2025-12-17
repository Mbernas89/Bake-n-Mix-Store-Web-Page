<?php
require 'config.php';

// ensure admin access
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

// fetch orders
$ordersStmt = $mysqli->prepare("
    SELECT o.*, u.username
    FROM orders o
    JOIN users u ON o.user_id = u.id
    ORDER BY o.created_at DESC
");
$ordersStmt->execute();
$orders = $ordersStmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin Orders</title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

<?php include 'navbar.php'; ?>

<div class="container my-4">

    <h1 class="mb-4">📦 Orders Management</h1>

    <?php if (empty($orders)): ?>
        <div class="alert alert-info">
            No orders found.
        </div>
    <?php else: ?>

        <?php foreach ($orders as $o): ?>

            <div class="card mb-4 shadow-sm">
                <div class="card-body">

                    <!-- ORDER HEADER -->
                    <h5 class="fw-bold mb-2">
                        Order #<?= $o['id'] ?>
                        <span class="text-muted">
                            — <?= htmlspecialchars($o['username']) ?>
                        </span>
                    </h5>

                    <!-- ORDER DETAILS -->
                    <p class="mb-1">
                        <strong>Date:</strong>
                        <?= $o['created_at'] ?>
                    </p>

                    <p class="mb-1">
                        <strong>Payment:</strong>
                        <?= htmlspecialchars($o['payment_method']) ?>
                    </p>

                    <?php if (!empty($o['phone'])): ?>
                        <p class="mb-1">
                            <strong>Phone:</strong>
                            <?= htmlspecialchars($o['phone']) ?>
                        </p>
                    <?php endif; ?>

                    <?php if (!empty($o['address'])): ?>
                        <p class="mb-1">
                            <strong>Address:</strong>
                            <?= htmlspecialchars($o['address']) ?>
                        </p>
                    <?php endif; ?>

                    <p class="mb-2">
                        <strong>Total:</strong>
                        ₱<?= number_format($o['total'], 2) ?>
                    </p>

                    <!-- ORDER ITEMS -->
                    <details>
                        <summary class="text-danger fw-bold">
                            View Items
                        </summary>

                        <ul class="mt-2">
                            <?php
                            $itemsStmt = $mysqli->prepare("
                                SELECT * FROM order_items
                                WHERE order_id = ?
                            ");
                            $itemsStmt->bind_param("i", $o['id']);
                            $itemsStmt->execute();
                            $items = $itemsStmt->get_result()->fetch_all(MYSQLI_ASSOC);

                            foreach ($items as $it):
                            ?>
                                <li>
                                    <?= htmlspecialchars($it['product_name']) ?>
                                    × <?= $it['qty'] ?>
                                    — ₱<?= number_format($it['subtotal'], 2) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </details>

                </div>
            </div>

        <?php endforeach; ?>

    <?php endif; ?>

</div>

<!-- FOOTER -->
<footer class="bg-dark text-white py-4 mt-5">
    <div class="container text-center">
        <h5 class="mb-1">Bake N' Mix</h5>
        <p class="mb-2">Admin Order Management</p>

        <div class="mb-3">
            <a href="https://www.facebook.com/BakeAndMixBakingSupplies"
               class="text-white fs-4"
               target="_blank">
                <i class="fa-brands fa-facebook"></i>
            </a>
        </div>

        <small>
            © <?= date('Y') ?> Bake N' Mix. All rights reserved.
        </small>
    </div>
</footer>

<!-- BOOTSTRAP JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
