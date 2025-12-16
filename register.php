<?php
require "config.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Insert user with default "user" role
    $stmt = $mysqli->prepare("INSERT INTO users (username, email, password, role) VALUES (?,?,?, 'user')");
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        header("Location: login.php?registered=1");
        exit;
    } else {
        $message = "Username already taken.";
    }
}
?>
<!doctype html>
<html>
<head>
  <title>Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<div class="container col-md-4 mt-5">

<h2 class="mb-4 text-center">Register</h2>

<?php if ($message): ?>
  <div class="alert alert-danger"><?= $message ?></div>
<?php endif; ?>

<form method="POST">

  <div class="mb-3">
    <label>Username</label>
    <input name="username" class="form-control" required>
  </div>

  <div class="mb-3">
    <label>Email</label>
    <input name="email" type="email" class="form-control" required>
  </div>

  <div class="mb-3">
    <label>Password</label>
    <input name="password" type="password" class="form-control" required>
  </div>

  <button class="btn btn-primary w-100">Register</button>

</form>

<p class="mt-3 text-center">
  Already have an account? <a href="login.php">Login</a>
</p>

</div>
</body>
</html>
