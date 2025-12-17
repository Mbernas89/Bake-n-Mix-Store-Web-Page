<?php
require 'config.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user = $_SESSION['user'];

// Fetch user's orders
$stmt = $mysqli->prepare("
    SELECT * FROM orders
    WHERE user_id = ?
    ORDER BY created_at DESC
");
$stmt->bind_param("i", $user['id']);
$stmt->execute();
$orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

<?php include 'navbar.php'; ?>

<!-- PAGE CONTENT -->
<div class="container py-4">

    <h1>Hello, <?= htmlspecialchars($user['username']) ?>!</h1>

    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>

    <?php if ($user['role'] === 'admin'): ?>
        <p class="text-danger fw-bold">Admin Account</p>
        <a href="admin_products.php" class="btn btn-danger">Admin Panel</a>
    <?php endif; ?>

    <hr class="my-4">

    <h3 class="mb-3">Purchase History</h3>

    <?php if (empty($orders)): ?>
        <div class="alert alert-info">
            You have not made any purchases yet.
        </div>
    <?php else: ?>
        <?php foreach ($orders as $o): ?>

            <div class="card mb-3">
                <div class="card-body">

                    <h5 class="fw-bold">
                        Order #<?= $o['id'] ?>
                        <small class="text-muted">
                            (<?= $o['created_at'] ?>)
                        </small>
                    </h5>

                    <p class="mb-1">
                        <strong>Payment:</strong>
                        <?= htmlspecialchars($o['payment_method']) ?>
                    </p>

                    <p class="mb-1">
                        <strong>Total:</strong>
                        ₱<?= number_format($o['total'], 2) ?>
                    </p>

                    <details>
                        <summary class="text-danger fw-bold">
                            View Items
                        </summary>

                        <ul class="mt-2">
                            <?php
                            $itemStmt = $mysqli->prepare("
                                SELECT * FROM order_items
                                WHERE order_id = ?
                            ");
                            $itemStmt->bind_param("i", $o['id']);
                            $itemStmt->execute();
                            $items = $itemStmt->get_result()->fetch_all(MYSQLI_ASSOC);

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
        <p class="mb-2">Your one-stop shop for baking supplies</p>

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

</body>
</html>
