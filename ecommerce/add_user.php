<link rel="stylesheet" href="style.css">
<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['is_admin'] != 1) {
    die("Bạn không có quyền truy cập!");
}

if (isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $password = sha1($_POST['password']);  // Mã hóa mật khẩu
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;

    // Thêm tài khoản người dùng
    $stmt = $conn->prepare("INSERT INTO user (username, password, is_admin) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $username, $password, $is_admin);
    $stmt->execute();
    echo "<script>
                alert('Thêm người dùng thành công!');
                window.location.href = 'manage_users.php';
              </script>";
        exit;
}
?>

<form method="post">
    Username: <input name="username"><br>
    Password: <input type="password" name="password"><br>
    Quyền Admin: <input type="checkbox" name="is_admin"><br>
    <button name="add_user">Thêm tài khoản</button>
</form>
<a href="manage_users.php">Quay về</a>
