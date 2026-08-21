<?php
require_once "../config.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "PLAYER") {
    header("Location: ../login.php");
    exit;
}

$player_id = (int)$_SESSION["user_id"];
$message = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["cancel_booking_id"])) {
    $booking_id = (int)$_POST["cancel_booking_id"];

    $stmt = mysqli_prepare(
        $conn,
        "SELECT b.id, b.schedule_id FROM bookings b WHERE b.id = ? AND b.player_id = ? AND b.booking_status = 'CONFIRMED' LIMIT 1"
    );
    mysqli_stmt_bind_param($stmt, "ii", $booking_id, $player_id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $booking = mysqli_fetch_assoc($res);

    if (!$booking) {
        $error = "Khong tim thay lich hop le de huy.";
    } else {
        mysqli_begin_transaction($conn);
        try {
            $stmt1 = mysqli_prepare(
                $conn,
                "DELETE FROM bookings WHERE id = ? AND player_id = ?"
            );
            mysqli_stmt_bind_param($stmt1, "ii", $booking_id, $player_id);
            mysqli_stmt_execute($stmt1);

            $schedule_id = (int)$booking["schedule_id"];
            $stmt2 = mysqli_prepare(
                $conn,
                "UPDATE schedules SET status = 'AVAILABLE' WHERE id = ?"
            );
            mysqli_stmt_bind_param($stmt2, "i", $schedule_id);
            mysqli_stmt_execute($stmt2);

            mysqli_commit($conn);
            $message = "Da huy lich thanh cong.";
        } catch (Exception $e) {
            mysqli_rollback($conn);
            $error = "Huy lich that bai.";
        }
    }
}

$sql = "SELECT b.id, b.booking_status, b.payment_status, b.created_at,
               s.start_time, s.end_time, s.price,
               u.full_name AS coach_name,
               c.name AS court_name
        FROM bookings b
        JOIN schedules s ON b.schedule_id = s.id
        JOIN coach_profiles cp ON s.coach_id = cp.user_id
        JOIN users u ON cp.user_id = u.id
        JOIN courts c ON s.court_id = c.id
        WHERE b.player_id = ?
        ORDER BY s.start_time DESC";

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
  <title>Lich da dat</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="bg-light">
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h3 class="page-title mb-0">Lich hoc cua toi</h3>
    <div class="d-flex gap-2">
      <a href="book.php" class="btn btn-success btn-sm">Dat lich moi</a>
      <a href="find-coach.php" class="btn btn-outline-primary btn-sm">Tim HLV</a>
      <a href="index.php" class="btn btn-outline-secondary btn-sm">Trang chu</a>
      <a href="../logout.php" class="btn btn-outline-danger btn-sm">Dang xuat</a>
    </div>
  </div>

  <?php if ($message): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
  <?php endif; ?>
  <?php if ($error): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>

  <div class="card card-soft shadow-sm">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>HLV</th>
              <th>San</th>
              <th>Thoi gian</th>
              <th>Hoc phi</th>
              <th>Trang thai</th>
              <th>Thanh toan</th>
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
                  <?php
                  echo date("d/m/Y H:i", strtotime($row["start_time"]))
                     . " - "
                     . date("H:i", strtotime($row["end_time"]));
                  ?>
                </td>
                <td><?php echo number_format($row["price"], 0, ",", "."); ?> d</td>
                <td><span class="badge bg-primary"><?php echo htmlspecialchars($row["booking_status"]); ?></span></td>
                <td><span class="badge bg-secondary"><?php echo htmlspecialchars($row["payment_status"]); ?></span></td>
                <td>
                  <?php if ($row["booking_status"] === "CONFIRMED"): ?>
                    <form method="POST" class="d-inline" onsubmit="return confirm('Xac nhan huy lich nay?');">
                      <input type="hidden" name="cancel_booking_id" value="<?php echo (int)$row["id"]; ?>">
                      <button type="submit" class="btn btn-outline-danger btn-sm">Huy</button>
                    </form>
                  <?php else: ?>
                    <span class="text-muted small">-</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="7" class="text-center text-muted">Ban chua dat lich nao.</td>
            </tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
</body>
</html>
