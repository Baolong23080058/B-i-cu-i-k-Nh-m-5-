<?php
require_once "../config.php";
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "ADMIN") {
    header("Location: ../login.php");
    exit;
}
?>
<h2>Xin chào Admin: <?php echo htmlspecialchars($_SESSION["full_name"]); ?></h2>
<p><a href="../logout.php">Đăng xuất</a></p>
