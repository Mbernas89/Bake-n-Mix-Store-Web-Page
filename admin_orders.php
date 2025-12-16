<?php
require 'config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
?>


<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin - Orders</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
  <?php include 'navbar.php'; ?>

  <div class="container">
    <h1>Orders</h1>
    <?php if (empty($orders)): ?>
      <div class="alert alert-info">No orders yet.</div>
    <?php else: ?>
      <?php foreach ($orders as $o): ?>
        <div class="card mb-3">
          <div class="card-body">
            <h5>Order #<?= $o['id'] ?> — <?= htmlspecialchars($o['customer_name']) ?> <small class="text-muted"><?= $o['created_at'] ?></small></h5>
            <p>Phone: <?= htmlspecialchars($o['phone']) ?> | Payment: <?= htmlspecialchars($o['payment_method']) ?></p>
            <p>Address: <?= nl2br(htmlspecialchars($o['address'])) ?></p>
            <p><strong>Total: ₱<?= number_format($o['total'],2) ?></strong></p>
            <h6>Items:</h6>
            <ul>
              <?php
                $stmt = $mysqli->prepare("SELECT * FROM order_items WHERE order_id = ?");
                $stmt->bind_param("i",$o['id']);
                $stmt->execute();
                $items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                foreach ($items as $it) {
                  echo '<li>'.htmlspecialchars($it['product_name']).' × '.(int)$it['qty'].' — ₱'.number_format($it['subtotal'],2).'</li>';
                }
              ?>
            </ul>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</body>
</html>
