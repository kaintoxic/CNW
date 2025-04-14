<link rel="stylesheet" href="style.css">
<?php
session_start();
include 'config.php';

// Kiểm tra xem người dùng có phải là admin không
if (!isset($_SESSION['user']) || $_SESSION['user']['is_admin'] != 1) {
    die("Bạn không có quyền truy cập!");
}

// Lấy danh sách tất cả người dùng
$result = $conn->query("SELECT * FROM user");

?>

<h2>Quản lý tài khoản người dùng</h2>
<a href="add_user.php">Thêm tài khoản mới</a><br><br>

<table border="1">
    <tr>
        <th>Username</th>
        <th>Quyền Admin</th>
        <th>Hành động</th>
    </tr>

<?php
while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>{$row['username']}</td>";
    echo "<td>" . ($row['is_admin'] == 1 ? 'Admin' : 'User') . "</td>";
    
    // Kiểm tra xem tài khoản là admin hay không
    if ($row['username'] == 'admin') {
        echo "<td><a href='edit_user.php?id={$row['id']}'>Sửa</a> | <span style='color: grey;'>Xoá</span></td>"; // Làm mờ nút Xóa cho admin
    } else {
        echo "<td>
    <a href='edit_user.php?id={$row['id']}'>Sửa</a> | 
    <a href='delete_user.php?id={$row['id']}' onclick=\"return confirm('Bạn có chắc chắn muốn xoá tài khoản này không?');\">Xoá</a>
</td>";
// Nút Xóa bình thường cho người dùng khác
    }

    echo "</tr>";
}
?>

</table>
<a href="index.php">Quay về trang chủ</a>
