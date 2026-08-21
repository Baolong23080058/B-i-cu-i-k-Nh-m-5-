<?php
require_once "../config.php";
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "PLAYER") {
    header("Location: ../login.php");
    exit;
}

$keyword = trim($_GET["q"] ?? "");
$min_rate = trim($_GET["min_rate"] ?? "");
$max_rate = trim($_GET["max_rate"] ?? "");
$sort = $_GET["sort"] ?? "rating_desc";

$where = ["u.role = 'COACH'", "u.is_active = 1"];
$params = [];
$types = "";

if ($keyword !== "") {
    $where[] = "(u.full_name LIKE ? OR cp.bio LIKE ?)";
    $kw = "%" . $keyword . "%";
    $params[] = $kw;
    $params[] = $kw;
    $types .= "ss";
}
if ($min_rate !== "" && is_numeric($min_rate)) {
    $where[] = "cp.hourly_rate >= ?";
    $params[] = (float)$min_rate;
    $types .= "d";
}
if ($max_rate !== "" && is_numeric($max_rate)) {
    $where[] = "cp.hourly_rate <= ?";
    $params[] = (float)$max_rate;
    $types .= "d";
}

$order = "cp.rating_avg DESC";
if ($sort === "rating_asc") $order = "cp.rating_avg ASC";
if ($sort === "price_asc") $order = "cp.hourly_rate ASC";
if ($sort === "price_desc") $order = "cp.hourly_rate DESC";
if ($sort === "name_asc") $order = "u.full_name ASC";

$sql = "
SELECT u.id, u.full_name, cp.bio, cp.years_of_experience, cp.hourly_rate,
       cp.rating_avg, cp.rating_count, cp.is_verified
FROM users u
JOIN coach_profiles cp ON u.id = cp.user_id
WHERE " . implode(" AND ", $where) . "
ORDER BY $order
";

$stmt = mysqli_prepare($conn, $sql);
if ($types !== "") {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tìm HLV</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="bg-light">
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h3 class="fw-bold mb-0">Tìm kiếm HLV</h3>
    <div class="d-flex gap-2">
      <a href="index.php" class="btn btn-outline-secondary btn-sm">Trang chủ</a>
      <a href="book.php" class="btn btn-success btn-sm">Đặt lịch</a>
      <a href="../logout.php" class="btn btn-outline-danger btn-sm">Đăng xuất</a>
    </div>
  </div>

  <form class="card card-body shadow-sm mb-4" method="GET">
    <div class="row g-2 align-items-end">
      <div class="col-md-4">
        <label class="form-label small fw-bold">Từ khóa (tên / mô tả)</label>
        <input type="text" name="q" class="form-control" value="<?php echo htmlspecialchars($keyword); ?>" placeholder="VD: Huấn">
      </div>
      <div class="col-md-2">
        <label class="form-label small fw-bold">Giá từ</label>
        <input type="number" name="min_rate" class="form-control" value="<?php echo htmlspecialchars($min_rate); ?>">
      </div>
      <div class="col-md-2">
        <label class="form-label small fw-bold">Giá đến</label>
        <input type="number" name="max_rate" class="form-control" value="<?php echo htmlspecialchars($max_rate); ?>">
      </div>
      <div class="col-md-2">
        <label class="form-label small fw-bold">Sắp xếp</label>
        <select name="sort" class="form-select">
          <option value="rating_desc" <?php if ($sort==="rating_desc") echo "selected"; ?>>Rating cao → thấp</option>
          <option value="rating_asc" <?php if ($sort==="rating_asc") echo "selected"; ?>>Rating thấp → cao</option>
          <option value="price_asc" <?php if ($sort==="price_asc") echo "selected"; ?>>Giá thấp → cao</option>
          <option value="price_desc" <?php if ($sort==="price_desc") echo "selected"; ?>>Giá cao → thấp</option>
          <option value="name_asc" <?php if ($sort==="name_asc") echo "selected"; ?>>Tên A → Z</option>
        </select>
      </div>
      <div class="col-md-2">
        <button class="btn btn-primary w-100" type="submit">Lọc</button>
      </div>
    </div>
  </form>

  <div class="row g-3">
    <?php if ($result && mysqli_num_rows($result) > 0): ?>
      <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <div class="col-md-6 col-lg-4">
          <div class="card h-100 shadow-sm">
            <div class="card-body">
              <h5 class="fw-bold"><?php echo htmlspecialchars($row["full_name"]); ?></h5>
              <?php if ((int)$row["is_verified"] === 1): ?>
                <span class="badge bg-success mb-2">Đã xác thực</span>
              <?php else: ?>
                <span class="badge bg-secondary mb-2">Chưa duyệt</span>
              <?php endif; ?>
              <p class="small text-muted"><?php echo htmlspecialchars($row["bio"] ?: "Chưa có giới thiệu"); ?></p>
              <ul class="small mb-3">
                <li>Kinh nghiệm: <?php echo (int)$row["years_of_experience"]; ?> năm</li>
                <li>Học phí: <?php echo number_format($row["hourly_rate"], 0, ",", "."); ?> đ/giờ</li>
                <li>Đánh giá: <?php echo number_format($row["rating_avg"], 1); ?> (<?php echo (int)$row["rating_count"]; ?>)</li>
              </ul>
              <a href="book.php" class="btn btn-success btn-sm w-100">Đặt lịch</a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="col-12"><div class="alert alert-info mb-0">Không tìm thấy HLV phù hợp.</div></div>
    <?php endif; ?>
  </div>
</div>
</body>
</html>
