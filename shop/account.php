<?php
include 'config.php';
if (session_status() == PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];

// Đổi mật khẩu
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $newPassword = trim($_POST['new_password']);
    if ($newPassword !== '') {
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
        $stmt->bind_param("ss", $hash, $username);
        $stmt->execute();
        $message = "<p style='color:green;'>Đổi mật khẩu thành công!</p>";
    } else {
        $message = "<p style='color:red;'>Vui lòng nhập mật khẩu mới!</p>";
    }
}

// Xoá tài khoản
if (isset($_POST['delete_account'])) {
    $stmt = $conn->prepare("DELETE FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    session_destroy();
    header("Location: login.php?deleted=1");
    exit;
}
?>

<h2>Quản lý tài khoản</h2>
<p>Xin chào <strong><?= htmlspecialchars($username) ?></strong></p>

<?= isset($message) ? $message : '' ?>

<form method="POST">
    <h3>Đổi mật khẩu</h3>
    <input type="password" name="new_password" placeholder="Mật khẩu mới" required><br>
    <button type="submit" name="change_password">Đổi mật khẩu</button>
</form>

<form method="POST" onsubmit="return confirm('Bạn chắc chắn muốn xoá tài khoản?')">
    <h3>Xoá tài khoản</h3>
    <button type="submit" name="delete_account" style="color: red;">Xoá tài khoản</button>
</form>

<a href="index.php">Quay lại trang chính</a>
