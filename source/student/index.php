<?php
require_once "../config.php";
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "PLAYER") {
    header("Location: ../login.php");
    exit;
}
$name = $_SESSION["full_name"] ?? "Học viên";
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Học viên - PickleConnect</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<nav class="navbar navbar-pickle">
  <div class="container py-2">
    <span class="navbar-brand brand-pickle mb-0">PickleConnect</span>
    <div class="d-flex align-items-center gap-3">
      <span class="text-muted small">Xin chào, <strong><?php echo htmlspecialchars($name); ?></strong></span>
      <a href="../logout.php" class="btn btn-sm btn-outline-secondary">Đăng xuất</a>
    </div>
  </div>
</nav>

<div class="container py-4">
  <h3 class="page-title mb-1">Trang Học viên</h3>
  <p class="text-muted mb-4">Quản lý tìm HLV và lịch học của bạn</p>

  <div class="row g-3">
    <div class="col-md-4">
      <a href="find-coach.php" class="text-decoration-none">
        <div class="card card-soft p-4 h-100 bg-white">
          <div class="text-primary fw-bold mb-1">Tìm HLV</div>
          <div class="text-muted small">Tìm kiếm, lọc và sắp xếp huấn luyện viên</div>
        </div>
      </a>
    </div>
    <div class="col-md-4">
      <a href="book.php" class="text-decoration-none">
        <div class="card card-soft p-4 h-100 bg-white">
          <div class="text-success fw-bold mb-1">Đặt lịch học</div>
          <div class="text-muted small">Chọn ca trống và đăng ký buổi tập</div>
        </div>
      </a>
    </div>
    <div class="col-md-4">
      <a href="my-bookings.php" class="text-decoration-none">
        <div class="card card-soft p-4 h-100 bg-white">
          <div class="text-dark fw-bold mb-1">Lịch của tôi</div>
          <div class="text-muted small">Xem và hủy các buổi đã đặt</div>
        </div>
      </a>
    </div>
  </div>
</div>
</body>
</html>
