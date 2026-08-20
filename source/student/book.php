<?php
require_once "../config.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "PLAYER") {
    header("Location: ../login.php");
    exit;
}

$player_id = (int)$_SESSION["user_id"];
$message = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["schedule_id"])) {
    $schedule_id = (int)$_POST["schedule_id"];

    $stmt = mysqli_prepare($conn, "SELECT id FROM schedules WHERE id = ? AND status = 'AVAILABLE' LIMIT 1");
    mysqli_stmt_bind_param($stmt, "i", $schedule_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $schedule = mysqli_fetch_assoc($result);

    if (!$schedule) {
        $error = "Ca dạy không còn trống hoặc không tồn tại.";
    } else {
        mysqli_begin_transaction($conn);
        try {
            $stmt1 = mysqli_prepare($conn, "INSERT INTO bookings (schedule_id, player_id, booking_status, payment_status) VALUES (?, ?, 'CONFIRMED', 'UNPAID')");
            mysqli_stmt_bind_param($stmt1, "ii", $schedule_id, $player_id);
            mysqli_stmt_execute($stmt1);

            $stmt2 = mysqli_prepare($conn, "UPDATE schedules SET status = 'BOOKED' WHERE id = ? AND status = 'AVAILABLE'");
            mysqli_stmt_bind_param($stmt2, "i", $schedule_id);
            mysqli_stmt_execute($stmt2);

            if (mysqli_stmt_affected_rows($stmt2) === 0) {
                throw new Exception("Ca dạy vừa được người khác đặt.");
            }

            mysqli_commit($conn);
            $message = "Đặt lịch thành công!";
        } catch (Exception $e) {
            mysqli_rollback($conn);
            $error = $e->getMessage();
        }
    }
}

$sql = "
SELECT s.id, s.start_time, s.end_time, s.price,
       u.full_name AS coach_name,
       c.name AS court_name
FROM schedules s
JOIN coach_profiles cp ON s.coach_id = cp.user_id
JOIN users u ON cp.user_id = u.id
JOIN courts c ON s.court_id = c.id
WHERE s.status = 'AVAILABLE'
ORDER BY s.start_time ASC
";
$schedules = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đặt lịch học</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
      <h3 class="fw-bold mb-0">Đặt lịch học Pickleball</h3>
      <p class="text-muted small mb-0">Xin chào: <?php echo htmlspecialchars($_SESSION["full_name"]); ?></p>
    </div>
    <div class="d-flex gap-2">
      <a href="my-bookings.php" class="btn btn-outline-primary btn-sm">Lịch của tôi</a>
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
      <h5 class="fw-bold mb-3">Các ca dạy đang trống</h5>
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>HLV</th>
              <th>Sân</th>
              <th>Thời gian</th>
              <th>Học phí</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
          <?php if ($schedules && mysqli_num_rows($schedules) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($schedules)): ?>
              <tr>
                <td><?php echo htmlspecialchars($row["coach_name"]); ?></td>
                <td><?php echo htmlspecialchars($row["court_name"]); ?></td>
                <td>
                  <?php echo date("d/m/Y H:i", strtotime($row["start_time"])) . " - " . date("H:i", strtotime($row["end_time"])); ?>
                </td>
                <td><?php echo number_format($row["price"], 0, ",", "."); ?> đ</td>
                <td>
                  <form method="POST" class="d-inline">
                    <input type="hidden" name="schedule_id" value="<?php echo (int)$row["id"]; ?>">
                    <button type="submit" class="btn btn-success btn-sm"
                      onclick="return confirm('Xác nhận đặt ca này?')">Đặt lịch</button>
                  </form>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="5" class="text-center text-muted">Hiện không còn ca trống.</td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
</body>
</html>
