<link rel="stylesheet" href="style.css">
<?php
session_start();
include 'config.php';

// Kiểm tra quyền admin
if (!isset($_SESSION['user']) || $_SESSION['user']['is_admin'] != 1) {
    die("Bạn không có quyền truy cập!");
}

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM user WHERE id=$id");
$user = $result->fetch_assoc();

// Kiểm tra khi người dùng gửi form cập nhật tài khoản
if (isset($_POST['update_user'])) {
    $username = $_POST['username'];

    // Kiểm tra xem username có trùng với username của người dùng khác không (trừ chính tài khoản hiện tại)
    $check_username = $conn->prepare("SELECT id FROM user WHERE username=? AND id != ?");
    $check_username->bind_param("si", $username, $id);
    $check_username->execute();
    $check_result = $check_username->get_result();

    if ($check_result->num_rows > 0) {
        // Nếu username đã tồn tại, hiển thị thông báo lỗi và không thực hiện cập nhật
        echo "<p style='color:red'>Tên đăng nhập này đã tồn tại! Vui lòng chọn tên khác.</p>";
    } else {
        // Nếu mật khẩu mới không rỗng, thực hiện cập nhật mật khẩu mới
        if (!empty($_POST['password'])) {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);  // Mã hóa mật khẩu mới
        } else {
            $password = $user['password'];  // Giữ nguyên mật khẩu cũ nếu không thay đổi
        }

        $is_admin = isset($_POST['is_admin']) ? 1 : 0;

        // Cập nhật tài khoản người dùng
        $stmt = $conn->prepare("UPDATE user SET username=?, password=?, is_admin=? WHERE id=?");
        $stmt->bind_param("ssii", $username, $password, $is_admin, $id);
        $stmt->execute();
        echo "<p style='color:green'>Tài khoản đã được cập nhật!</p>";

        // Chuyển hướng về trang quản lý người dùng sau khi cập nhật
        header("Location: manage_users.php");
        exit();  // Dừng việc thực thi mã thêm sau khi chuyển hướng
    }
}
?>

<form method="post">
    Username: <input name="username" value="<?= $user['username'] ?>" required><br>

    <!-- Mật khẩu mới -->
    Password mới: <input type="password" name="password"><br>

    <!-- Quyền Admin -->
    Quyền Admin: <input type="checkbox" name="is_admin" <?= $user['is_admin'] == 1 ? 'checked' : '' ?>><br>

    <button name="update_user">Cập nhật tài khoản</button>
</form>
