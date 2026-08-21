<?php
require_once "../config.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "PLAYER") {
    header("Location: ../login.php");
    exit;
}

$player_id = (int)$_SESSION["user_id"];
$message = "";
$error = "";

// Xử lý hủy lịch
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["cancel_booking_id"])) {
    $booking_id = (int)$_POST["cancel_booking_id"];

    // Lấy booking của đúng học viên + còn CONFIRMED
    $stmt = mysqli_prepare($conn, "
        SELECT b.id, b.schedule_id
        FROM bookings b
        WHERE b.id = ? AND b.player_id = ? AND b.booking_status = 'CONFIRMED'
        LIMIT 1
    ");
    mysqli_stmt_bind_param($stmt, "ii", $booking_id, $player_id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $booking = mysqli_fetch_assoc($res);

    if (!$booking) {
        $error = "Không tìm thấy lịch hợp lệ để hủy.";
    } else {
        mysqli_begin_transaction($conn);
        try {
            $stmt1 = mysqli_prepare($conn, "UPDATE bookings SET booking_status = 'CANCELLED' WHERE id = ?");
            mysqli_stmt_bind_param($stmt1, "i", $booking_id);
            mysqli_stmt_execute($stmt1);

            $stmt2 = mysqli_prepare($conn, "UPDATE schedules SET status = 'AVAILABLE' WHERE id = ?");
            mysqli_stmt_bind_param($stmt2, "i", $booking["schedule_id"]);
            mysqli_stmt_execute($stmt2);

            mysqli_commit($conn);
            $message = "Đã hủy lịch thành công.";
        } catch (Exception $e) {
            mysqli_rollback($conn);
            $error = "Hủy lịch thất bại.";
        }
    }
}

$sql = "
SELECT b.id, b.booking_status, b.payment_status, b.created_at,
       s.start_time, s.end_time, s.price,
       u.full_name AS coach_name,
       c.name AS court_name
FROM bookings b
JOIN schedules s ON b.schedule_id = s.id
JOIN coach_profiles cp ON s.coach_id = cp.user_id
JOIN users u ON cp.user_id = u.id
JOIN courts c ON s.court_id = c.id
WHERE b.player_id = ?
ORDER BY s.start_time DESC
";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $player_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lịch đã đặt</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h3 class="fw-bold mb-0">Lịch học của tôi</h3>
    <div class="d-flex gap-2">
      <a href="book.php" class="btn btn-success btn-sm">Đặt lịch mới</a>
      <a href="find-coach.php" class="btn btn-outline-primary btn-sm">Tìm HLV</a>
      <a href="index.php" class="btn btn-outline-secondary btn-sm">Trang chủ</a>
      <a href="../logout.php" class="btn btn-outline-danger btn-sm">Đăng xuất</a>
    </div>
  </div>

  <?php if ($message): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
  <?php endif; ?>
  <?php if ($error): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>

  <div class="card shadow-sm">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>HLV</th>
              <th>Sân</th>
              <th>Thời gian</th>
              <th>Học phí</th>
              <th>Trạng thái</th>
              <th>Thanh toán</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
          <?php if ($result && mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
              <tr>
                <td><?php echo htmlspecialchars($row["coach_name"]); ?></td>
                <td><?php echo htmlspecialchars($row["court_name"]); ?></td>
                <td>
                  <?php echo date("d/m/Y H:i", strtotime($row["start_time"])) . " - " . date("H:i", strtotime($row["end_time"])); ?>
                </td>
                <td><?php echo number_format($row["price"], 0, ",", "."); ?> đ</td>
                <td><span class="badge bg-primary"><?php echo htmlspecialchars($row["booking_status"]); ?></span></td>
                <td><span class="badge bg-secondary"><?php echo htmlspecialchars($row["payment_status"]); ?></span></td>
                <td>
                  <?php if ($row["booking_status"] === "CONFIRMED"): ?>
                    <form method="POST" class="d-inline" onsubmit="return confirm('Xác nhận hủy lịch này?');">
                      <input type="hidden" name="cancel_booking_id" value="<?php echo (int)$row["id"]; ?>">
                      <button type="submit" class="btn btn-outline-danger btn-sm">Hủy</button>
                    </form>
                  <?php else: ?>
                    <span class="text-muted small">—</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="7" class="text-center text-muted">Bạn chưa đặt lịch nào.</td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
</body>
</html>
