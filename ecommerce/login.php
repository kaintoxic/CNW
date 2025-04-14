<link rel="stylesheet" href="style.css">

<?php
session_start();
include 'config.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password_input = $_POST['password'];  // Nhập mật khẩu

    // Truy vấn lấy user theo username
    $stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // So sánh password bằng password_verify
        if (password_verify($password_input, $user['password'])) {
            // Lưu thông tin người dùng vào session
            $_SESSION['user'] = $user;

            // Đặt thời gian hết hạn cho phiên đăng nhập (3 phút)
            $_SESSION['login_time'] = time();

            // Nếu người dùng chọn "Ghi nhớ tôi"
            if (isset($_POST['remember'])) {
                // Đặt cookie cho việc "ghi nhớ tên đăng nhập" trong 3 phút (180 giây)
                setcookie('username', $username, time() + 180, "/"); // Cookie hết hạn sau 3 phút
            } else {
                // Nếu không chọn "Ghi nhớ tôi", xóa cookie nếu có
                setcookie('username', '', time() - 3600, "/");
            }

            // Chuyển hướng về trang chính
            header("Location: index.php");
            exit;
        } else {
            echo "<p style='color:red'>Sai mật khẩu!</p>";
        }
    } else {
        echo "<p style='color:red'>Sai tên đăng nhập!</p>";
    }
}
?>

<h2>Đăng nhập (TK: admin, MK: 111)</h2>
<form method="post">
    Username: <input name="username" value="<?= isset($_COOKIE['username']) ? $_COOKIE['username'] : ''; ?>"><br>
    Password: <input type="password" name="password"><br>
    
    <!-- Thêm checkbox "Ghi nhớ tôi" -->
    <label>
        <input type="checkbox" name="remember" <?= isset($_COOKIE['username']) ? 'checked' : ''; ?>>
        Ghi nhớ tôi
    </label><br>

    <button name="login">Đăng nhập</button>
</form>

<a href="register.php">Đăng ký tài khoản mới</a>
