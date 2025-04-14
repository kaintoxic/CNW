<?php
session_start();
include 'config.php';

// Kiểm tra nếu người dùng đã đăng nhập và là admin
if (!isset($_SESSION['user']) || $_SESSION['user']['is_admin'] != 1) {
    die("Bạn không có quyền truy cập!");
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Truy vấn để lấy thông tin user
    $stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Kiểm tra nếu là admin thì không cho xóa
    if ($user && $user['username'] == 'admin') {
        echo "Không thể xóa tài khoản admin!";
        exit;
    }

    // Nếu không phải admin thì xóa bình thường
    $stmt = $conn->prepare("DELETE FROM user WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header('Location: manage_users.php');
    exit;
}
?>
