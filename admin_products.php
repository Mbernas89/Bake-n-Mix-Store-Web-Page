<?php
require 'config.php';

// ensure admin access
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$action = $_GET['action'] ?? 'list';

//delete product
if ($action === 'delete' && isset($_GET['id'])) {

    $id = (int) $_GET['id'];

    // delete image file
    $stmt = $mysqli->prepare("SELECT image FROM products WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $img = $stmt->get_result()->fetch_assoc();

    if ($img && file_exists($img['image'])) {
        unlink($img['image']);
    }

    $stmt = $mysqli->prepare("DELETE FROM products WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: admin_products.php");
    exit;
}

//add product
if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {

    $imagePath = '';

    if (!empty($_FILES['image']['name'])) {
        $targetDir = "images/";
        $fileName = time() . "_" . basename($_FILES['image']['name']);
        $targetFile = $targetDir . $fileName;

        move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
        $imagePath = $targetFile;
    }

    $stmt = $mysqli->prepare("
        INSERT INTO products (name, description, price, category, image)
        VALUES (?,?,?,?,?)
    ");

    $stmt->bind_param(
        "ssdss",
        $_POST['name'],
        $_POST['description'],
        $_POST['price'],
        $_POST['category'],
        $imagePath
    );

    $stmt->execute();

    header("Location: admin_products.php");
    exit;
}

// edit product
if ($action === 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = (int) $_POST['id'];

    // keep old image unless replaced
    $stmt = $mysqli->prepare("SELECT image FROM products WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $oldImage = $stmt->get_result()->fetch_assoc()['image'];

    $imagePath = $oldImage;

    if (!empty($_FILES['image']['name'])) {
        if ($oldImage && file_exists($oldImage)) {
            unlink($oldImage);
        }

        $targetDir = "images/";
        $fileName = time() . "_" . basename($_FILES['image']['name']);
        $targetFile = $targetDir . $fileName;

        move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
        $imagePath = $targetFile;
    }

    $stmt = $mysqli->prepare("
        UPDATE products
        SET name=?, description=?, price=?, category=?, image=?
        WHERE id=?
    ");

    $stmt->bind_param(
        "ssdssi",
        $_POST['name'],
        $_POST['description'],
        $_POST['price'],
        $_POST['category'],
        $imagePath,
        $id
    );

    $stmt->execute();

    header("Location: admin_products.php");
    exit;
}

//fetch product for editing
$editProduct = null;

if ($action === 'edit' && isset($_GET['id'])) {
    $stmt = $mysqli->prepare("SELECT * FROM products WHERE id=?");
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    $editProduct = $stmt->get_result()->fetch_assoc();
}

$products = $mysqli
    ->query("SELECT * FROM products ORDER BY created_at DESC")
    ->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html lang="en">
<head>
    <title>Admin - Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<?php include 'navbar.php'; ?>

<div class="container">

    <h1 class="mb-4">Admin Products</h1>

    <?php if ($action === 'add' || $action === 'edit'): ?>

        <h3><?= $action === 'add' ? 'Add Product' : 'Edit Product' ?></h3>

        <form method="POST" enctype="multipart/form-data">

            <?php if ($action === 'edit'): ?>
                <input type="hidden" name="id" value="<?= $editProduct['id'] ?>">
            <?php endif; ?>

            <div class="mb-3">
                <label>Name</label>
                <input name="name" class="form-control"
                       value="<?= $editProduct['name'] ?? '' ?>" required>
            </div>

            <div class="mb-3">
                <label>Description</label>
                <textarea name="description" class="form-control" required><?= $editProduct['description'] ?? '' ?></textarea>
            </div>

            <div class="mb-3">
                <label>Price</label>
                <input name="price" type="number" step="0.01"
                       class="form-control"
                       value="<?= $editProduct['price'] ?? '' ?>" required>
            </div>

            <div class="mb-3">
                <label>Category</label>
                <input name="category" class="form-control"
                       value="<?= $editProduct['category'] ?? '' ?>" required>
            </div>

            <div class="mb-3">
                <label>Product Image</label>
                <input type="file" name="image" class="form-control" <?= $action === 'add' ? 'required' : '' ?>>
            </div>

            <?php if ($action === 'edit' && $editProduct['image']): ?>
                <div class="mb-3">
                    <img src="<?= $editProduct['image'] ?>" height="80">
                </div>
            <?php endif; ?>

            <button class="btn btn-success">
                <?= $action === 'add' ? 'Save Product' : 'Update Product' ?>
            </button>

            <a href="admin_products.php" class="btn btn-secondary">Cancel</a>
        </form>

        <hr>

    <?php endif; ?>

    <div class="d-flex justify-content-between mb-3">
        <h3>Product List</h3>
        <a href="admin_products.php?action=add" class="btn btn-primary">+ Add Product</a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th width="80">Image</th>
                <th>Name</th>
                <th>Price</th>
                <th>Category</th>
                <th width="150">Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($products as $p): ?>
            <tr>
                <td><img src="<?= $p['image'] ?>" height="60"></td>
                <td><?= htmlspecialchars($p['name']) ?></td>
                <td>₱<?= number_format($p['price'], 2) ?></td>
                <td><?= htmlspecialchars($p['category']) ?></td>
                <td>
                    <a href="admin_products.php?action=edit&id=<?= $p['id'] ?>"
                       class="btn btn-warning btn-sm">Edit</a>

                    <a href="admin_products.php?action=delete&id=<?= $p['id'] ?>"
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Delete product?');">
                       Delete
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

</div>
</body>
</html>
