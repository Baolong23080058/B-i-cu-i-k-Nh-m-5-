<?php
require_once "../config.php";
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "COACH") {
    header("Location: ../login.php");
    exit;
}
$full_name = $_SESSION["full_name"] ?? "HLV";
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>HLV - Hồ sơ & Lịch dạy</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <style>
    body { background:#f8fafc; font-family: system-ui, sans-serif; }
    .card { border-radius:16px; border:1px solid #e2e8f0; }
    .btn-emerald { background:#047857; color:#fff; border-radius:10px; font-weight:600; }
    .btn-emerald:hover { background:#065f46; color:#fff; }
  </style>
</head>
<body class="p-3 p-md-4">
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
      <h3 class="fw-bold mb-0">Quản lý Hồ sơ & Lịch dạy</h3>
      <p class="text-muted small mb-0">Xin chào HLV: <?php echo htmlspecialchars($full_name); ?></p>
    </div>
    <div class="d-flex gap-2">
      <span class="badge bg-success bg-opacity-10 text-success border border-success p-2">Vai trò: COACH</span>
      <a href="../logout.php" class="btn btn-outline-secondary btn-sm">Đăng xuất</a>
    </div>
  </div>

  <div class="row g-4">
    <!-- Hồ sơ HLV -->
    <div class="col-12 col-lg-5">
      <div class="card p-4 shadow-sm bg-white">
        <h5 class="fw-bold mb-3 text-success">1. Cập nhật hồ sơ HLV</h5>
        <form method="post" action="#">
          <div class="mb-3">
            <label class="form-label small fw-bold">Họ và tên</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($full_name); ?>" readonly>
          </div>
          <div class="row g-2 mb-3">
            <div class="col-6">
              <label class="form-label small fw-bold">Kinh nghiệm (năm)</label>
              <input type="number" class="form-control" name="years" value="4" min="0">
            </div>
            <div class="col-6">
              <label class="form-label small fw-bold">Học phí / giờ (VNĐ)</label>
              <input type="number" class="form-control" name="rate" value="350000" min="0">
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-bold">Chứng chỉ</label>
            <input type="text" class="form-control" name="cert" value="IPTPA Level 2, PPR Certified">
          </div>
          <div class="mb-3">
            <label class="form-label small fw-bold">Giới thiệu (Bio)</label>
            <textarea class="form-control" name="bio" rows="3">Chuyên huấn luyện kỹ thuật và chiến thuật Pickleball.</textarea>
          </div>
          <button type="button" class="btn btn-emerald w-100" onclick="alert('Đã lưu hồ sơ (giao diện). Sẽ gắn database sau.')">Lưu hồ sơ</button>
        </form>
      </div>
    </div>

    <!-- Lịch dạy -->
    <div class="col-12 col-lg-7">
      <div class="card p-4 shadow-sm bg-white mb-4">
        <h5 class="fw-bold mb-3 text-primary">2. Tạo ca dạy mới</h5>
        <form class="row g-2">
          <div class="col-12 col-md-6">
            <label class="form-label small fw-bold">Sân tập</label>
            <select class="form-select" name="court">
              <option>Sân Pickleball Cầu Giấy</option>
              <option>Pickleball Club Tân Bình</option>
            </select>
          </div>
          <div class="col-12 col-md-6">
            <label class="form-label small fw-bold">Ngày dạy</label>
            <input type="date" class="form-control" name="date">
          </div>
          <div class="col-6">
            <label class="form-label small fw-bold">Giờ bắt đầu</label>
            <input type="time" class="form-control" name="start" value="08:00">
          </div>
          <div class="col-6">
            <label class="form-label small fw-bold">Giờ kết thúc</label>
            <input type="time" class="form-control" name="end" value="09:30">
          </div>
          <div class="col-12 mt-3">
            <button type="button" class="btn btn-primary w-100 fw-bold" onclick="alert('Đã thêm ca dạy (giao diện). Sẽ gắn database sau.')">+ Thêm ca dạy</button>
          </div>
        </form>
      </div>

      <div class="card p-4 shadow-sm bg-white">
        <h5 class="fw-bold mb-3">3. Danh sách lịch dạy</h5>
        <div class="table-responsive">
          <table class="table table-hover align-middle small mb-0">
            <thead class="table-light">
              <tr>
                <th>Ngày & Giờ</th>
                <th>Sân</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><strong>20/08/2026</strong><br><small class="text-muted">08:00 - 09:30</small></td>
                <td>Sân Cầu Giấy</td>
                <td><span class="badge bg-success">Đã có học viên</span></td>
                <td><button class="btn btn-sm btn-outline-danger" type="button">Hủy</button></td>
              </tr>
              <tr>
                <td><strong>20/08/2026</strong><br><small class="text-muted">17:00 - 18:30</small></td>
                <td>Club Tân Bình</td>
                <td><span class="badge bg-warning text-dark">Đang mở</span></td>
                <td><button class="btn btn-sm btn-outline-danger" type="button">Xóa</button></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
