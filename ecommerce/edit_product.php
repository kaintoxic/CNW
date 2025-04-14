<link rel="stylesheet" href="style.css">

<?php
session_start();
include 'config.php';

// Kiểm tra nếu người dùng đã đăng nhập
if (!isset($_SESSION['user'])) {
    die("Bạn chưa đăng nhập!");
}

// Kiểm tra quyền admin
$is_admin = $_SESSION['user']['is_admin'] == 1;

// Lấy thông tin sản phẩm cần chỉnh sửa
$product_id = $_GET['id'];
$result = $conn->query("SELECT * FROM product WHERE id = $product_id");
$product = $result->fetch_assoc();

$error = ''; // Khai báo biến lỗi

if ($_POST) {
    // Lấy thông tin từ form
    $name = trim($_POST['name']);
    $desc = $_POST['desc'];
    $price = $_POST['price'];

    // Kiểm tra nếu các trường không được bỏ trống
    if (empty($name) || empty($desc) || empty($price)) {
        $error = "Vui lòng điền đầy đủ thông tin!";
    } elseif (!is_numeric($price) || $price < 0) {
        // Kiểm tra giá tiền phải là số và không âm
        $error = "Giá tiền phải là số và không được âm!";
    }

    if (empty($error)) {
        // Nếu có ảnh mới, xử lý upload ảnh
        if ($_FILES['image']['name']) {
            $image = "uploads/" . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], $image);
            $image_update = ", image='$image'";
        } else {
            $image_update = "";
        }

        // Cập nhật dữ liệu vào cơ sở dữ liệu
        $stmt = $conn->prepare("UPDATE product SET name = ?, description = ?, price = ? $image_update WHERE id = ?");
        $stmt->bind_param("ssds", $name, $desc, $price, $product_id);
        $stmt->execute();
        echo "<p style='color:green'>Cập nhật sản phẩm thành công!</p>";
    }
}
?>

<h2>Cập nhật sản phẩm</h2>

<!-- Hiển thị thông báo lỗi nếu có -->
<?php if ($error): ?>
    <div style="color: red;"><?= $error; ?></div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
    Tên sản phẩm: <input name="name" value="<?= htmlspecialchars($product['name']) ?>" required><br>
    Mô tả: <textarea name="desc" required><?= htmlspecialchars($product['description']) ?></textarea><br>
    Giá: <input name="price" value="<?= $product['price'] ?>" required><br>
    Ảnh: <input type="file" name="image"><br>
    <button name="edit">Cập nhật sản phẩm</button>
</form>

<a href="index.php">Quay về trang chủ</a>
