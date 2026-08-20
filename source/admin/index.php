<?php
require_once "../config.php";
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "ADMIN") {
    header("Location: ../login.php");
    exit;
}
$full_name = $_SESSION["full_name"] ?? "Admin";
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <style>
    body { background:#f1f5f9; font-family: system-ui, sans-serif; }
    .card-stat { border-radius:16px; border:none; }
    .card-table { border-radius:16px; border:1px solid #cbd5e1; }
  </style>
</head>
<body class="p-3 p-md-4">
<div class="container-fluid">
  <div class="bg-dark text-white p-4 rounded-4 shadow-sm mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
      <h3 class="fw-bold mb-1">Bảng điều khiển Admin</h3>
      <p class="text-secondary small mb-0">Xin chào: <?php echo htmlspecialchars($full_name); ?></p>
    </div>
    <a href="../logout.php" class="btn btn-outline-light btn-sm">Đăng xuất</a>
  </div>

  <!-- Thống kê -->
  <div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
      <div class="card p-3 card-stat bg-white shadow-sm border-start border-4 border-success">
        <span class="text-muted small fw-bold">Tổng HLV</span>
        <h2 class="fw-bold text-success mt-1">2</h2>
        <small class="text-muted">Đã có trong hệ thống</small>
      </div>
    </div>
    <div class="col-12 col-md-4">
      <div class="card p-3 card-stat bg-white shadow-sm border-start border-4 border-primary">
        <span class="text-muted small fw-bold">Tổng Học viên</span>
        <h2 class="fw-bold text-primary mt-1">2</h2>
        <small class="text-muted">Tài khoản PLAYER</small>
      </div>
    </div>
    <div class="col-12 col-md-4">
      <div class="card p-3 card-stat bg-white shadow-sm border-start border-4 border-warning">
        <span class="text-muted small fw-bold">HLV chờ duyệt</span>
        <h2 class="fw-bold text-warning mt-1">1</h2>
        <small class="text-muted">Cần xử lý</small>
      </div>
    </div>
  </div>

  <!-- Duyệt HLV -->
  <div class="card card-table bg-white shadow-sm mb-4 p-4">
    <h5 class="fw-bold mb-3">1. Duyệt hồ sơ HLV</h5>
    <div class="table-responsive">
      <table class="table table-hover align-middle small mb-0">
        <thead class="table-light">
          <tr>
            <th>Họ tên HLV</th>
            <th>Khu vực</th>
            <th>Chứng chỉ</th>
            <th>Học phí / giờ</th>
            <th>Trạng thái</th>
            <th class="text-end">Hành động</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><strong>Trần Hải Nam</strong></td>
            <td>Đà Nẵng</td>
            <td>PPR Pro Certified</td>
            <td>350.000 đ</td>
            <td><span class="badge bg-warning text-dark">Chờ duyệt</span></td>
            <td class="text-end">
              <button class="btn btn-sm btn-success" type="button" onclick="alert('Đã duyệt (giao diện)')">Duyệt</button>
              <button class="btn btn-sm btn-outline-danger" type="button" onclick="alert('Đã từ chối (giao diện)')">Từ chối</button>
            </td>
          </tr>
          <tr>
            <td><strong>Nguyễn Văn Huấn</strong></td>
            <td>Hà Nội</td>
            <td>IPTPA Level 2</td>
            <td>400.000 đ</td>
            <td><span class="badge bg-success">Đã duyệt</span></td>
            <td class="text-end">
              <button class="btn btn-sm btn-secondary" disabled>Hoàn tất</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Quản lý user -->
  <div class="card card-table bg-white shadow-sm p-4">
    <h5 class="fw-bold mb-3">2. Quản lý tài khoản người dùng</h5>
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle small mb-0">
        <thead class="table-light">
          <tr>
            <th>ID</th>
            <th>Tên</th>
            <th>Email</th>
            <th>Vai trò</th>
            <th>Trạng thái</th>
            <th class="text-end">Thao tác</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td><strong>Quản Trị Viên</strong></td>
            <td>admin@pickleball.vn</td>
            <td><span class="badge bg-dark">ADMIN</span></td>
            <td><span class="text-success fw-bold">Đang hoạt động</span></td>
            <td class="text-end">—</td>
          </tr>
          <tr>
            <td>2</td>
            <td><strong>Nguyễn Văn Huấn</strong></td>
            <td>huan.coach@gmail.com</td>
            <td><span class="badge bg-success">COACH</span></td>
            <td><span class="text-success fw-bold">Đang hoạt động</span></td>
            <td class="text-end"><button class="btn btn-sm btn-outline-warning" type="button">Khóa</button></td>
          </tr>
          <tr>
            <td>4</td>
            <td><strong>Lê Hoàng Long</strong></td>
            <td>long.player@gmail.com</td>
            <td><span class="badge bg-info text-dark">PLAYER</span></td>
            <td><span class="text-success fw-bold">Đang hoạt động</span></td>
            <td class="text-end"><button class="btn btn-sm btn-outline-warning" type="button">Khóa</button></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
</body>
</html>
