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
            // Xóa booking để có thể đặt lại cùng ca (tránh UNIQUE schedule_id)
            $stmt1 = mysqli_prepare($conn, "DELETE FROM bookings WHERE id = ? AND player_id = ?");
            mysqli_stmt_bind_param($stmt1, "ii", $booking_id, $player_id);
            mysqli_stmt_execute($stmt1);

            $stmt2 = mysqli_prepare($conn, "UPDATE schedules SET status = 'AVAILABLE' WHERE id = ?
