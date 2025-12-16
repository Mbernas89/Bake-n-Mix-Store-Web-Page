<?php
require 'config.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($user = $res->fetch_assoc()) {

        // Verify password  
        if ($password === $user['password']) {

            $_SESSION['user'] = [
                'id'       => $user['id'],
                'username' => $user['username'],
                'email'    => $user['email'],
                'role'     => $user['role']
            ];

            if ($user['role'] === 'admin') {
                header("Location: admin_products.php");
            } else {
                header("Location: index.php");
            }
            exit;

        } else {
            $message = "Incorrect password.";
        }

    } else {
        $message = "Username not found.";
    }
}
?>
<!doctype html>
<html>
<head>
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container col-md-4 mt-5">

<h2 class="mb-4 text-center">Login</h2>

<?php if ($message): ?>
  <div class="alert alert-danger"><?= $message ?></div>
<?php endif; ?>

<form method="POST">
  <div class="mb-3">
    <label>Username</label>
    <input name="username" class="form-control" required>
  </div>

  <div class="mb-3">
    <label>Password</label>
    <input name="password" type="password" class="form-control" required>
  </div>

  <button class="btn btn-danger w-100">Login</button>
</form>

<p class="mt-3 text-center">
  No account? <a href="register.php">Register here</a>
</p>

</div>
</body>
</html>
