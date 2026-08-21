<?php
require_once "../config.php";
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "PLAYER") {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Trang Học viên</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container py-5">
  <div class="card shadow-sm p-4">
    <h3 class="fw-bold">Xin chào Học viên: <?php echo htmlspecialchars($_SESSION["full_name"]); ?></h3>
    <p class="text-muted">Chọn chức năng bên dưới</p>
<div class="d-flex flex-wrap gap-2 mt-3">
  <a href="find-coach.php" class="btn btn-outline-primary">Tìm HLV</a>
  <a href="book.php" class="btn btn-success">Đặt lịch học</a>
  <a href="my-bookings.php" class="btn btn-primary">Lịch của tôi</a>
  <a href="../logout.php" class="btn btn-outline-danger">Đăng xuất</a>
</div>
  </div>
</div>
</body>
</html>
