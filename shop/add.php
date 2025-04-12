<link rel="stylesheet" href="style.css">
<?php
include 'config.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $description = trim($_POST['description']);
    $image = $_FILES['image'];

    // Kiểm tra các lỗi nhập liệu
    if (empty($name)) $errors[] = "Tên sản phẩm không được để trống.";
    if (empty($price) || !is_numeric($price) || $price <= 0) $errors[] = "Giá phải là một số dương.";
    if (empty($description)) $errors[] = "Mô tả sản phẩm không được để trống.";
    if ($image['error'] !== 0) $errors[] = "Vui lòng chọn hình ảnh.";

    // Nếu không có lỗi, thực hiện lưu sản phẩm vào cơ sở dữ liệu
    if (empty($errors)) {
        // Lưu hình ảnh vào thư mục uploads
        $imgPath = 'uploads/' . basename($image['name']);
        move_uploaded_file($image['tmp_name'], $imgPath);

        // Thêm sản phẩm vào cơ sở dữ liệu
        $stmt = $conn->prepare("INSERT INTO products (name, price, description, image) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sdss", $name, $price, $description, $imgPath);
        $stmt->execute();

        // Chuyển hướng về trang danh sách sản phẩm
        header("Location: index.php");
        exit;
    }
}
?>

<h2>Thêm sản phẩm</h2>

<?php if (!empty($errors)): ?>
    <ul style="color:red;">
        <?php foreach ($errors as $e): ?>
            <li><?= htmlspecialchars($e) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <input name="name" placeholder="Tên sản phẩm" value="<?= $_POST['name'] ?? '' ?>"><br>
    <input name="price" type="number" placeholder="Giá" value="<?= $_POST['price'] ?? '' ?>"><br>
    <textarea name="description" placeholder="Mô tả sản phẩm"><?= $_POST['description'] ?? '' ?></textarea><br>
    <input name="image" type="file"><br>
    <button type="submit">Lưu</button>
</form>

<a href="index.php">Quay lại</a>
