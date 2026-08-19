<?php
require_once "config.php";

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $full_name = trim($_POST["full_name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $password = $_POST["password"] ?? "";
    $role = $_POST["role"] ?? "PLAYER";

    if ($full_name === "" || $email === "" || $phone === "" || $password === "") {
        $error = "Vui lòng nhập đầy đủ thông tin.";
    } elseif (!in_array($role, ["PLAYER", "COACH"])) {
        $error = "Vai trò không hợp lệ.";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($conn, "INSERT INTO users (full_name, email, phone, password_hash, role) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sssss", $full_name, $email, $phone, $hash, $role);

        if (mysqli_stmt_execute($stmt)) {
            $user_id = mysqli_insert_id($conn);

            if ($role === "COACH") {
                mysqli_query($conn, "INSERT INTO coach_profiles (user_id) VALUES ($user_id)");
            } else {
                mysqli_query($conn, "INSERT INTO player_profiles (user_id) VALUES ($user_id)");
            }

            $success = "Đăng ký thành công. Hãy đăng nhập.";
        } else {
            $error = "Email hoặc số điện thoại đã tồn tại.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký</title>
</head>
<body>
    <h2>Đăng ký tài khoản</h2>
    <?php if ($error) echo "<p style='color:red'>$error</p>"; ?>
    <?php if ($success) echo "<p style='color:green'>$success</p>"; ?>
    <form method="POST">
        <p>Họ tên: <input type="text" name="full_name" required></p>
        <p>Email: <input type="email" name="email" required></p>
        <p>Số điện thoại: <input type="text" name="phone" required></p>
        <p>Mật khẩu: <input type="password" name="password" required></p>
        <p>Vai trò:
            <select name="role">
                <option value="PLAYER">Học viên</option>
                <option value="COACH">Huấn luyện viên</option>
            </select>
        </p>
        <button type="submit">Đăng ký</button>
    </form>
    <p><a href="login.php">Đã có tài khoản? Đăng nhập</a></p>
</body>
</html>
