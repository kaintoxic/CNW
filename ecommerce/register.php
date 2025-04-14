<link rel="stylesheet" href="style.css">

<?php
session_start();
include 'config.php';

$error = ''; // Khai báo biến lỗi
$success = ''; // Khai báo biến thành công

if (isset($_POST['register'])) {
    $username = trim($_POST['username']);  // Loại bỏ khoảng trắng thừa
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password']; // Lấy giá trị xác nhận mật khẩu

    // Kiểm tra nếu các trường không được bỏ trống
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error = "Vui lòng điền đầy đủ thông tin!";
    } elseif (strlen($password) < 3) {
        // Kiểm tra mật khẩu tối thiểu 3 ký tự
        $error = "Mật khẩu phải có ít nhất 3 ký tự!";
    } elseif ($password !== $confirm_password) {
        // Kiểm tra mật khẩu và xác nhận mật khẩu có khớp không
        $error = "Mật khẩu và xác nhận mật khẩu không khớp!";
    } else {
        // Kiểm tra tên người dùng đã tồn tại chưa
        $stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = "Tên đăng nhập đã tồn tại!";
        } else {
            // Mã hóa mật khẩu
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Thêm tài khoản vào CSDL
            $stmt = $conn->prepare("INSERT INTO user (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $hashed_password);
            $stmt->execute();
            $success = "Đăng ký thành công! Bạn có thể đăng nhập ngay bây giờ.";
        }
    }
}
?>

<h2>Đăng ký</h2>

<!-- Hiển thị thông báo lỗi nếu có -->
<?php if ($error): ?>
    <div style="color: red;"><?= $error; ?></div>
<?php endif; ?>

<!-- Hiển thị thông báo thành công nếu có -->
<?php if ($success): ?>
    <div style="color: green;"><?= $success; ?></div>
<?php endif; ?>

<form method="post">
    Username: <input name="username" value=""><br>
    Password: <input type="password" name="password"><br>
    Xác nhận mật khẩu: <input type="password" name="confirm_password"><br>
    <button name="register">Đăng ký</button>
</form>

<a href="login.php">Đã có tài khoản? Đăng nhập</a>
