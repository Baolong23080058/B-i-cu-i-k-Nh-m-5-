<?php
require_once "config.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    $stmt = mysqli_prepare($conn, "SELECT id, full_name, email, password_hash, role, is_active FROM users WHERE email = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user && (int)$user["is_active"] === 1 && password_verify($password, $user["password_hash"])) {
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["full_name"] = $user["full_name"];
        $_SESSION["role"] = $user["role"];

        if ($user["role"] === "ADMIN") {
            header("Location: admin/index.php");
        } elseif ($user["role"] === "COACH") {
            header("Location: coach/index.php");
        } else {
            header("Location: student/index.php");
        }
        exit;
    } else {
        $error = "Email hoặc mật khẩu không đúng.";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
</head>
<body>
    <h2>Đăng nhập</h2>
    <?php if ($error) echo "<p style='color:red'>$error</p>"; ?>
    <form method="POST">
        <p>Email: <input type="email" name="email" required></p>
        <p>Mật khẩu: <input type="password" name="password" required></p>
        <button type="submit">Đăng nhập</button>
    </form>
    <p><a href="register.php">Chưa có tài khoản? Đăng ký</a></p>
</body>
</html>
