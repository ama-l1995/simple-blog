<?php
session_start();
require_once '../config/db.php';

// Pagination & Search
$perPage = 5;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$search = $_GET['search'] ?? '';
$start = ($page - 1) * $perPage;

// Count total posts
$searchSql = $search ? "WHERE title LIKE :search OR content LIKE :search" : "";
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM posts $searchSql");
$search ? $countStmt->execute(['search' => "%$search%"]) : $countStmt->execute();
$totalPosts = $countStmt->fetchColumn();
$totalPages = ceil($totalPosts / $perPage);

// Get paginated posts
$sql = "SELECT * FROM posts $searchSql ORDER BY created_at DESC LIMIT :start, :perPage";
$stmt = $pdo->prepare($sql);
if ($search) $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
$stmt->bindValue(':start', $start, PDO::PARAM_INT);
$stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Simple Blog</title>
    <!-- Favicon -->
    <link rel="icon" href="favicon.ico" type="image/x-icon">

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <!-- Toastr -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />


  <!-- Custom Style -->
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f9f9f9;
      padding: 2rem;
    }
    .card {
      border-radius: 16px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    }
    .btn-primary {
      background-color: #4A00E0;
      border-color: #4A00E0;
    }
    .btn-primary:hover {
      background-color: #3700b3;
      border-color: #3700b3;
    }
    .clear-btn {
      position: absolute;
      top: 50%;
      right: 15px;
      transform: translateY(-50%);
      cursor: pointer;
      font-size: 20px;
      color: #aaa;
      z-index: 10;
      display: none;
    }
    .clear-btn:hover {
      color: #000;
    }
  </style>
</head>

<body>
<div class="container">

  <!-- Page Header -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="text-primary"><i class="fas fa-blog"></i> Blog Posts</h1>
    <a href="create.php" class="btn btn-success"><i class="fas fa-plus"></i> New Post</a>
  </div>

  <!-- Search Input -->
  <div class="position-relative mb-4">
    <input type="text" id="searchInput" name="search" class="form-control" placeholder="Search posts..." value="<?= htmlspecialchars($search) ?>" autocomplete="off">
    <span class="clear-btn" onclick="clearSearch()" title="Clear">&times;</span>
  </div>

  <!-- Posts List -->
  <?php if (count($posts)): ?>
    <?php foreach ($posts as $post): ?>
      <div class="card mb-3">
        <div class="card-body">
          <h4 class="card-title"><?= htmlspecialchars($post['title']) ?></h4>
          <p class="card-text"><?= nl2br(htmlspecialchars($post['content'])) ?></p>
          <p class="text-muted"><i class="far fa-calendar"></i> <?= $post['created_at'] ?></p>
            <?php if (!empty($_SESSION['is_admin'])): ?>
                <div class="d-flex gap-2">
                    <a href="edit.php?id=<?= $post['id'] ?>" class="btn btn-sm btn-warning">
                    <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="delete.php?id=<?= $post['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                    <i class="fas fa-trash-alt"></i> Delete
                    </a>
                </div>
            <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>

    <!-- Pagination -->
    <nav>
      <ul class="pagination justify-content-center">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
          <li class="page-item <?= $i == $page ? 'active' : '' ?>">
            <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
          </li>
        <?php endfor; ?>
      </ul>
    </nav>
  <?php else: ?>
    <div class="alert alert-info">No posts found.</div>
  <?php endif; ?>
</div>

<!-- JS Libraries -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Toastr Notification -->
<script>
<?php if (isset($_SESSION['toast'])): ?>
  toastr.<?= $_SESSION['toast']['type'] ?>("<?= $_SESSION['toast']['message'] ?>");
<?php unset($_SESSION['toast']); endif; ?>
</script>

<!-- Search Autocomplete + Clear -->
<script>
const input = document.getElementById('searchInput');
const clearBtn = document.querySelector('.clear-btn');

// Show or hide clear button
function toggleClearButton() {
  clearBtn.style.display = input.value.trim() ? 'block' : 'none';
}

// Clear search field
function clearSearch() {
  input.value = '';
  window.location.href = window.location.pathname;
}

// Trigger search while typing
let delayTimer;
input.addEventListener('input', function () {
  toggleClearButton();
  clearTimeout(delayTimer);
  delayTimer = setTimeout(() => {
    const value = input.value.trim();
    if (value.length > 0) {
      window.location.href = window.location.pathname + '?search=' + encodeURIComponent(value);
    } else {
      window.location.href = window.location.pathname;
    }
  }, 600);
});

// Initial state for clear button
toggleClearButton();
</script>
</body>
</html>
