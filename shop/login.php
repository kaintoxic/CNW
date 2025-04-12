<link rel="stylesheet" href="style.css">
<?php
include 'config.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = trim($_POST['username']);
    $pass = $_POST['password'];

    // Dùng prepared statement để chống SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($pass, $row['password'])) {
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $row['username'];

            // Nếu người dùng tick vào "Nhớ tài khoản"
            if (isset($_POST['remember'])) {
                setcookie('remember_username', $user, time() + 300, "/"); // lưu cookie 30 ngày
            } else {
                setcookie('remember_username', '', time() - 3600, "/"); // huỷ nếu không chọn
            }

            header("Location: index.php");
            exit;
        } else {
            echo "Sai tài khoản hoặc mật khẩu!";
        }
    } else {
        echo "Sai tài khoản hoặc mật khẩu!";
    }
}
?>

<h2>Đăng nhập</h2>
<form method="POST">
    <input name="username" placeholder="Tài khoản" 
           value="<?= isset($_COOKIE['remember_username']) ? htmlspecialchars($_COOKIE['remember_username']) : '' ?>"><br>
    <input name="password" type="password" placeholder="Mật khẩu"><br>
    <label><input type="checkbox" name="remember"> Nhớ tài khoản</label><br>
    <button type="submit">Đăng nhập</button>
</form>
<a href="register.php">Chưa có tài khoản? Đăng ký</a>
