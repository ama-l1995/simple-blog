<?php
session_start();
require_once '../config/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $password = $_POST['password'] ?? '';

  if ($username && $password) {
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($password, $admin['password'])) {
      $_SESSION['is_admin'] = true;
      $_SESSION['admin_username'] = $admin['username'];
      $_SESSION['toast'] = ['type' => 'success', 'message' => 'Welcome, ' . $admin['username'] . '!'];
      header("Location: index.php");
      exit;
    } else {
      $error = "Invalid username or password.";
    }
  } else {
    $error = "Please enter both username and password.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
    <!-- Favicon -->
    <link rel="icon" href="favicon.ico" type="image/x-icon">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light" style="font-family: Inter, sans-serif;">
  <div class="container mt-5">
    <div class="card p-4 mx-auto" style="max-width: 400px;">
      <h3 class="mb-4 text-center text-primary">Admin Login</h3>

      <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
      <?php endif; ?>

      <form method="post">
        <div class="mb-3">
          <label>Username</label>
          <input type="text" name="username" class="form-control" required>
        </div>

        <div class="mb-3">
          <label>Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>

        <button class="btn btn-primary w-100">Login</button>
      </form>

      <div class="mt-3 text-center">
        <a href="register.php">Create new admin</a>
      </div>
    </div>
  </div>
</body>
</html>
