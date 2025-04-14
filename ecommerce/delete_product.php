<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user'])) {
    die("Bạn chưa đăng nhập!");
}

$id = $_GET['id'];

// Xoá ảnh nếu có
$result = $conn->query("SELECT image FROM product WHERE id=$id");
$row = $result->fetch_assoc();
// if ($row['image'] && file_exists($row['image'])) {
//     unlink($row['image']);
// }

// Xoá sản phẩm
$conn->query("DELETE FROM product WHERE id=$id");
header('Location: index.php');
?>
