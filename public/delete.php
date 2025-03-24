<?php
session_start();
if (empty($_SESSION['is_admin'])) {
    header("Location: login.php");
    exit;
  }

require_once '../config/db.php';

$id = $_GET['id'] ?? null;
if ($id) {
  $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
  $stmt->execute([$id]);

  $_SESSION['toast'] = [
    'type' => 'warning',
    'message' => 'Post deleted successfully!'
  ];
}

header("Location: index.php");
exit;
