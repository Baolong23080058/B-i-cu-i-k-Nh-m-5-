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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đăng nhập - PickleConnect</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/style.css">
  <style>
    body {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #f1f5f9;
      font-family: system-ui, sans-serif;
    }
    .login-card {
      width: 100%;
      max-width: 420px;
      border-radius: 16px;
      border: 1px solid #e2e8f0;
    }
    .brand {
      color: #047857;
      font-weight: 700;
    }
  </style>
</head>
<body>
  <div class="card login-card shadow-sm bg-white p-4 p-md-5">
    <div class="text-center mb-4">
      <h3 class="brand mb-1">PickleConnect</h3>
      <p class="text-muted small mb-0">Hệ thống kết nối HLV Pickleball</p>
    </div>

    <h5 class="fw-bold mb-3 text-center">Đăng nhập</h5>

    <?php if ($error): ?>
      <div class="alert alert-danger py-2"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label class="form-label fw-semibold">Email</label>
        <input type="email" name="email" class="form-control" placeholder="Nhập email" required>
      </div>
      <div class="mb-3">
        <label class="form-label fw-semibold">Mật khẩu</label>
        <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu" required>
      </div>
      <button type="submit" class="btn btn-success w-100 fw-bold">Đăng nhập</button>
    </form>

    <p class="text-center small mt-3 mb-0">
      Chưa có tài khoản?
      <a href="register.php" class="text-success fw-semibold">Đăng ký</a>
    </p>
  </div>
</body>
</html>
