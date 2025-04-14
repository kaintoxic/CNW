<link rel="stylesheet" href="style.css">
<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user'])) {
    echo "Bạn chưa đăng nhập!";
    exit;
}

// Kiểm tra quyền admin
$is_admin = $_SESSION['user']['is_admin'] == 1;

// Lấy danh sách sản phẩm từ cơ sở dữ liệu
$result = $conn->query("SELECT * FROM product");
?>

<h2>Danh sách sản phẩm</h2>

<?php if ($is_admin): ?>
    <!-- Admin có thể quản lý tài khoản người dùng và các chức năng khác -->
    <a href="manage_users.php">Quản lý tài khoản người dùng</a><br><br>
<?php endif; ?>

<!-- Tất cả người dùng đều có thể thêm sản phẩm -->
<a href="add_product.php">Thêm sản phẩm</a><br><br>
<a href="logout.php">Đăng xuất</a><br><br>

<table border="1">
    <tr>
        <th>Tên sản phẩm</th>
        <th>Mô tả</th>
        <th>Giá</th>
        <th>Ảnh</th>
        <th>Hành động</th>
    </tr>

<?php
while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>{$row['name']}</td>";
    echo "<td>{$row['description']}</td>";
    echo "<td>{$row['price']} vnd</td>";
    echo "<td><img src='{$row['image']}' width='100'></td>";
    
    // Nếu là admin, có thể sửa hoặc xóa tất cả sản phẩm
    if ($is_admin) {
        echo "<td>
    <a href='edit_product.php?id={$row['id']}'>Sửa</a> | 
    <a href='delete_product.php?id={$row['id']}' onclick=\"return confirm('Bạn có chắc chắn muốn xoá sản phẩm này không?');\">Xoá</a>
</td>";

    } else {
        // Nếu là người dùng thường, chỉ có thể sửa và xóa sản phẩm của chính mình
        echo "<td>
    <a href='edit_product.php?id={$row['id']}'>Sửa</a> | 
    <a href='delete_product.php?id={$row['id']}' onclick=\"return confirm('Bạn có chắc chắn muốn xoá sản phẩm này không?');\">Xoá</a>
</td>";

    }
    echo "</tr>";
}
?>

</table>
