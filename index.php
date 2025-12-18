<?php
require 'config.php';

// HANDLE AJAX ADD TO CART SO IT DOESN'T RELOAD PAGE
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['ajax']) &&
    $_POST['ajax'] === 'add_to_cart'
) {
    header('Content-Type: application/json');

    // require login before adding to cart
    if (!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'login_required']);
        exit;
    }

    // Gets product ID from POST request
    $pid = (int) $_POST['product_id'];

    $res = $mysqli->query(
        "SELECT * FROM products WHERE id = $pid LIMIT 1"
    );

    if ($product = $res->fetch_assoc()) {

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$pid])) {
            $_SESSION['cart'][$pid]['qty']++;
        } else {
            $_SESSION['cart'][$pid] = [
                'id'    => $product['id'],
                'name'  => $product['name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'qty'   => 1
            ];
        }

        echo json_encode([
            'status' => 'success',
            'count'  => array_sum(
                array_column($_SESSION['cart'], 'qty')
            )
        ]);
        exit;
    }

    echo json_encode(['status' => 'error']);
    exit;
}

// FETCH PRODUCTS AND CATEGORIES
$products = $mysqli
    ->query("SELECT * FROM products ORDER BY created_at DESC")
    ->fetch_all(MYSQLI_ASSOC);

$categories = $mysqli
    ->query("SELECT DISTINCT category FROM products")
    ->fetch_all(MYSQLI_ASSOC);

// FILTER PRODUCTS
$filtered = $products;

if (!empty($_GET['category'])) {
    $filtered = array_filter(
        $filtered,
        fn($p) => $p['category'] === $_GET['category']
    );
}

if (!empty($_GET['search'])) {
    $search = strtolower($_GET['search']);
    $filtered = array_filter(
        $filtered,
        fn($p) =>
            strpos(strtolower($p['name']), $search) !== false ||
            strpos(strtolower($p['description']), $search) !== false
    );
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Bake N' Mix</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

<?php include 'navbar.php'; ?>

<section class="py-5 text-center text-white" style="background:#c1121f;">
    <div class="container">
        <h1 class="display-5 fw-bold">Welcome to Bake N' Mix</h1>
        <p class="lead">Your one-stop shop for baking supplies</p>
    </div>
</section>

<div class="container mt-3">
    <div id="alertBox"></div>
</div>

<section class="py-5">
    <div class="container">
        <div class="row">

            <!-- SIDEBAR -->
            <div class="col-md-3 mb-4">
                <h5 class="fw-bold">Categories</h5>
                <div class="list-group">
                    <a href="index.php" class="list-group-item">All</a>
                    <?php foreach ($categories as $c): ?>
                        <a
                            href="index.php?category=<?= urlencode($c['category']) ?>"
                            class="list-group-item"
                        >
                            <?= htmlspecialchars($c['category']) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- PRODUCTS -->
            <div class="col-md-9">
                <div class="row g-4">

                    <?php foreach ($filtered as $p): ?>
                        <div class="col-md-4">
                            <div class="card h-100">

                                <img
                                    src="<?= htmlspecialchars($p['image']) ?>"
                                    class="card-img-top"
                                    alt="<?= htmlspecialchars($p['name']) ?>"
                                >

                                <div class="card-body d-flex flex-column">
                                    <h5><?= htmlspecialchars($p['name']) ?></h5>

                                    <!--  FIXED DESCRIPTION -->
                                    <p>
                                        <?= htmlspecialchars(mb_strimwidth($p['description'], 0, 100, '...')) ?>
                                    </p>

                                    <p class="fw-bold mt-auto">
                                        ₱<?= number_format($p['price'], 2) ?>
                                    </p>

                                    <button
                                        class="btn btn-danger w-100 addToCartBtn"
                                        data-id="<?= $p['id'] ?>"
                                    >
                                        <i class="fa fa-cart-plus"></i>
                                        Add to Cart
                                    </button>
                                </div>

                            </div>
                        </div>
                    <?php endforeach; ?>

                </div>
            </div>

        </div>
    </div>
</section>

<!-- JAVASCRIPT -->
<script>
document.querySelectorAll('.addToCartBtn').forEach(button => {

    button.addEventListener('click', () => {

        fetch('index.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body:
                'ajax=add_to_cart&product_id=' +
                button.dataset.id
        })
        .then(res => res.json())
        .then(data => {

            if (data.status === 'login_required') {
                window.location.href = 'login.php';
                return;
            }

            if (data.status === 'success') {

                document.querySelector('.cart-count').innerText = data.count;

                const alertBox = document.getElementById('alertBox');
                alertBox.innerHTML = `
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        ✅ Item added to cart successfully!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;

                setTimeout(() => {
                    alertBox.innerHTML = '';
                }, 2000);
            }
        });

    });

});
</script>
<!-- FOOTER -->
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

</body>
</html>
