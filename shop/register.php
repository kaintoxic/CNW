<link rel="stylesheet" href="style.css">
<?php
include 'config.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    // Kiểm tra hợp lệ
    if (empty($username) || empty($password) || empty($confirm)) {
        $errors[] = "Vui lòng điền đầy đủ thông tin.";
    } elseif (strlen($password) < 3) {
        $errors[] = "Mật khẩu phải ít nhất 3 ký tự.";
    } elseif ($password !== $confirm) {
        $errors[] = "Mật khẩu nhập lại không khớp.";
    } else {
        // Kiểm tra trùng username
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errors[] = "Tên đăng nhập đã tồn tại.";
        } else {
            // Mã hoá mật khẩu và lưu
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $hash);
            $stmt->execute();
            header("Location: login.php");
            exit;
        }
    }
}
?>

<h2>Đăng ký tài khoản</h2>

<?php if (!empty($errors)): ?>
    <ul style="color:red;">
        <?php foreach ($errors as $e): ?>
            <li><?= htmlspecialchars($e) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="POST">
    <input name="username" placeholder="Tên đăng nhập" value="<?= $_POST['username'] ?? '' ?>"><br>
    <input name="password" type="password" placeholder="Mật khẩu"><br>
    <input name="confirm" type="password" placeholder="Nhập lại mật khẩu"><br>
    <button type="submit">Đăng ký</button>
</form>

<a href="login.php">Đã có tài khoản? Đăng nhập</a>
