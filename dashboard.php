<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

$username = $_SESSION["username"];
$postsPerPage = 5;

// Get current page from URL (default = 1)
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $postsPerPage;

// Handle search
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$totalPosts = 0;

if ($search !== '') {
    // Prepare search query with pagination
    $sql = "SELECT posts.*, users.username 
            FROM posts 
            JOIN users ON posts.user_id = users.id 
            WHERE posts.title LIKE ? OR posts.content LIKE ?
            ORDER BY posts.created_at DESC
            LIMIT ? OFFSET ?";
    
    $stmt = $conn->prepare($sql);
    $searchParam = '%' . $search . '%';
    $stmt->bind_param("ssii", $searchParam, $searchParam, $postsPerPage, $offset);
    $stmt->execute();
    $result = $stmt->get_result();

    // Count total matching posts for pagination
    $countSql = "SELECT COUNT(*) as total FROM posts 
                 WHERE title LIKE ? OR content LIKE ?";
    $countStmt = $conn->prepare($countSql);
    $countStmt->bind_param("ss", $searchParam, $searchParam);
    $countStmt->execute();
    $countResult = $countStmt->get_result();
    $totalRow = $countResult->fetch_assoc();
    $totalPosts = $totalRow['total'];
    $countStmt->close();

} else {
    // No search: normal post list
    $sql = "SELECT posts.*, users.username 
            FROM posts 
            JOIN users ON posts.user_id = users.id 
            ORDER BY posts.created_at DESC
            LIMIT $postsPerPage OFFSET $offset";
    $result = $conn->query($sql);

    // Count total posts
    $countSql = "SELECT COUNT(*) as total FROM posts";
    $countResult = $conn->query($countSql);
    $totalRow = $countResult->fetch_assoc();
    $totalPosts = $totalRow['total'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-4">

    <h1 class="mb-3">Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
<div class="mb-4">
    <a href="post_create.php" class="btn btn-primary btn-sm me-2">➕ Create Post</a>
    <a href="blog.php" class="btn btn-outline-secondary btn-sm me-2">🗂 My Blog</a>
    <a href="logout.php" class="btn btn-danger btn-sm">🚪 Logout</a>
</div>


    <form class="mb-4" method="GET" action="dashboard.php">
  <div class="input-group">
    <input type="text" class="form-control" name="search" placeholder="Search posts..." 
      value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
    <button class="btn btn-secondary" type="submit">Search</button>
  </div>
</form>


    <h2>📋 All Blog Posts</h2>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="card mb-3">
  <div class="card-body">
    <h5 class="card-title"><?php echo htmlspecialchars($row["title"]); ?></h5>
    <p class="card-text"><?php echo nl2br(htmlspecialchars($row["content"])); ?></p>
    <p class="card-text">
      <small class="text-muted">By <?php echo htmlspecialchars($row["username"]); ?> | On <?php echo $row["created_at"]; ?></small>
    </p>
  </div>
</div>

        <?php endwhile; ?>
    <?php else: ?>
        <p>No posts found.</p>
    <?php endif; ?>

    <!-- Pagination links -->
    <div class="d-flex justify-content-center">
    <?php
$totalPages = ceil($totalPosts / $postsPerPage);

if ($totalPages > 1): ?>
  <nav class="mt-4">
    <ul class="pagination">
      <?php
      // Build pagination links
      for ($i = 1; $i <= $totalPages; $i++) {
          // Keep search term (if any) in link
          $link = "?page=$i";
          if ($search !== '') {
              $link .= "&search=" . urlencode($search);
          }

          // Highlight current page
          $active = ($i == $page) ? 'active' : '';
          echo '<li class="page-item ' . $active . '">';
          echo '<a class="page-link" href="' . $link . '">' . $i . '</a>';
          echo '</li>';
      }
      ?>
    </ul>
  </nav>
<?php endif; ?></div>

  </div>
</body>
</html>
