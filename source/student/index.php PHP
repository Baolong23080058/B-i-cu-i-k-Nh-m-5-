<?php
require_once "../config.php";
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "PLAYER") {
    header("Location: ../login.php");
    exit;
}
?>
<h2>Xin chào Học viên: <?php echo htmlspecialchars($_SESSION["full_name"]); ?></h2>
<p><a href="../logout.php">Đăng xuất</a></p>
