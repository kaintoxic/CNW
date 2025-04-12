<link rel="stylesheet" href="style.css">
<?php
include 'config.php';

$name = $_POST['name'];
$price = $_POST['price'];
$description = $_POST['description'] ?? ''; // Lấy mô tả nếu có
$image = '';

if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    $target = "uploads/" . basename($_FILES['image']['name']);
    move_uploaded_file($_FILES['image']['tmp_name'], $target);
    $image = $target;
}

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    if ($image != '') {
        $conn->query("UPDATE products SET name='$name', price=$price, description='$description', image='$image' WHERE id=$id");
    } else {
        $conn->query("UPDATE products SET name='$name', price=$price, description='$description' WHERE id=$id");
    }
} else {
    $conn->query("INSERT INTO products(name, price, description, image) VALUES('$name', $price, '$description', '$image')");
}

header("Location: index.php");
