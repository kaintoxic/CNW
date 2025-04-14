<link rel="stylesheet" href="style.css">

<?php
session_start();
include 'config.php';

// Kiểm tra nếu người dùng đã đăng nhập
if (!isset($_SESSION['user'])) {
    die("Bạn chưa đăng nhập!");
}

// Kiểm tra nếu là admin hoặc người dùng bình thường
$is_admin = $_SESSION['user']['is_admin'] == 1;

if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $desc = $_POST['desc'];
    $price = $_POST['price'];

    // Kiểm tra xem giá có phải là một số và không âm
    if (!is_numeric($price) || $price < 0) {
        echo "<p style='color:red'>Giá tiền phải là số và không được âm!</p>";
    } else {
        // Xử lý file ảnh
        $image = "uploads/" . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image);

        // Thêm sản phẩm vào cơ sở dữ liệu
        $stmt = $conn->prepare("INSERT INTO product (name, description, price, image) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssds", $name, $desc, $price, $image);
        $stmt->execute();
        // Thông báo và chuyển hướng bằng JavaScript
        echo "<script>
                alert('Thêm sản phẩm thành công!');
                window.location.href = 'index.php';
              </script>";
        exit;
    }
}
?>

<form method="post" enctype="multipart/form-data">
    Tên sản phẩm: <input name="name" required><br>
    Mô tả: <textarea name="desc"></textarea><br>
    Giá: <input name="price" required><br>
    Ảnh: <input type="file" name="image" required><br>
    <button name="add">Thêm sản phẩm</button>
</form>

<a href="index.php">Quay về trang chủ</a>
