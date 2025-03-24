<?php
session_start();
if (empty($_SESSION['is_admin'])) {
    header("Location: login.php");
    exit;
  }

require_once '../config/db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
  header("Location: index.php");
  exit;
}

$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$post) {
  echo "Post not found.";
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = $_POST['title'] ?? '';
  $content = $_POST['content'] ?? '';
  if ($title && $content) {
    $update = $pdo->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ?");
    $update->execute([$title, $content, $id]);

    $_SESSION['toast'] = [
      'type' => 'info',
      'message' => 'Post updated successfully!'
    ];

    header("Location: index.php");
    exit;
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Edit Post</title>
  
    <!-- Favicon -->
    <link rel="icon" href="favicon.ico" type="image/x-icon">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body style="font-family: 'Inter', sans-serif; background-color: #f9f9f9; padding: 2rem;">
  <div class="container">
    <h1 class="text-warning text-center mb-4"><i class="fas fa-edit"></i> Edit Post</h1>
    <div class="card p-4 mx-auto" style="max-width: 700px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.05);">
      <form method="post">
        <div class="mb-3">
          <label for="title" class="form-label">Post Title</label>
          <input type="text" class="form-control" id="title" name="title" required value="<?= htmlspecialchars($post['title']) ?>">
        </div>
        <div class="mb-3">
          <label for="content" class="form-label">Content</label>
          <textarea class="form-control" id="content" name="content" rows="6" required><?= htmlspecialchars($post['content']) ?></textarea>
        </div>
        <div class="d-flex justify-content-between">
          <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Cancel</a>
          <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
        </div>
      </form>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
