<link rel="stylesheet" href="style.css">
<?php
include 'config.php';
$id = $_GET['id'];
$row = $conn->query("SELECT * FROM products WHERE id = $id")->fetch_assoc();
?>
<h2>Sửa sản phẩm</h2>
<form method="POST" action="save.php" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $row['id'] ?>">
    <input name="name" value="<?= htmlspecialchars($row['name']) ?>"><br>
    <input name="price" type="number" value="<?= $row['price'] ?>"><br>
    <textarea name="description" rows="4" placeholder="Mô tả sản phẩm"><?= htmlspecialchars($row['description']) ?></textarea><br>
    <img src="<?= $row['image'] ?>" width="60"><br>
    <input name="image" type="file"><br>
    <button type="submit">Cập nhật</button>
</form>
<a href="index.php">Quay lại</a>
