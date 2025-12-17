<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!-- NAVBAR -->
<nav id="mainNavbar" class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
    <div class="container">

        <a class="navbar-brand fw-bold" href="index.php" style="color:#c1121f;">
            Bake N' Mix
        </a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="about.php">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.php">Contact</a>
                </li>
            </ul>

            <form class="d-flex me-3" method="GET" action="index.php">
                <input
                    class="form-control me-2"
                    type="search"
                    name="search"
                    placeholder="Search products"
                >
                <button class="btn btn-outline-danger">Search</button>
            </form>

            <?php if (!isset($_SESSION['user'])): ?>
                <a class="btn btn-outline-primary me-2" href="login.php">Login</a>
                <a class="btn btn-outline-secondary me-3" href="register.php">Register</a>
            <?php else: ?>
                <a class="btn btn-outline-success me-2" href="dashboard.php">Dashboard</a>
                <a class="btn btn-outline-warning me-3" href="logout.php">Logout</a>
            <?php endif; ?>

            <a class="btn btn-outline-danger" href="cart.php">
                🛒 Cart (
                <span class="cart-count">
                    <?= isset($_SESSION['cart'])
                        ? array_sum(array_column($_SESSION['cart'], 'qty'))
                        : 0
                    ?>
                </span>
                )
            </a>

        </div>
    </div>
</nav>

<!-- REQUIRED SPACING FOR FIXED NAVBAR -->
<style>
body {
    padding-top: 75px;
}

/* smooth animation */
#mainNavbar {
    transition: top 0.3s ease-in-out;
}
</style>

<!-- AUTO-HIDE ON SCROLL SCRIPT -->
<script>
let lastScrollTop = 0;
const navbar = document.getElementById('mainNavbar');

window.addEventListener('scroll', function () {

    let scrollTop = window.pageYOffset || document.documentElement.scrollTop;

    if (scrollTop > lastScrollTop) {
        // scrolling down → hide navbar
        navbar.style.top = "-90px";
    } else {
        // scrolling up → show navbar
        navbar.style.top = "0";
    }

    lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
});
</script>
