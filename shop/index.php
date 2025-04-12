<link rel="stylesheet" href="style.css">
<?php
include 'config.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['logged_in'])) header("Location: login.php");

$result = $conn->query("SELECT * FROM products");
?>

<h2>Xin chào, <?= htmlspecialchars($_SESSION['username']) ?>!</h2>
<a href="add.php">Thêm sản phẩm</a> | <a href="logout.php">Đăng xuất</a>
<hr>

<?php if ($result->num_rows == 0): ?>
    <p>Chưa có sản phẩm nào.</p>
<?php else: ?>
    <div class="product-list">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="product">
                <img src="<?= $row['image'] ?>" alt="<?= $row['name'] ?>" class="product-image">
                <h3><?= htmlspecialchars($row['name']) ?></h3>
                <p><?= number_format($row['price']) ?> vnđ</p>
                <p><?= htmlspecialchars($row['description']) ?></p>
                <a href="edit.php?id=<?= $row['id'] ?>" class="edit-btn">Sửa</a>
                <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Xoá?')" class="delete-btn">Xoá</a>
            </div>
        <?php endwhile; ?>
    </div>
<?php endif; ?>
