<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../../auth/login.php");
    exit();
}
require_once "../../../../config/config.php";

// Fetch product
if (!isset($_GET['id'])) {
    die("Product ID missing");
}
$id = (int)$_GET['id'];
$product = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();
if (!$product) {
    die("Product not found");
}

// Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $quantity = (int)$_POST['quantity'];
    $price = (float)$_POST['price'];
    $expiry_date = $_POST['expiry_date'];

    $update = $conn->query("UPDATE products SET name='$name', quantity=$quantity, price=$price, expiry_date='$expiry_date' WHERE id=$id");

    if ($update) {
        header("Location: inventory.php?msg=updated");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Product</title>
<link rel="shortcut icon" href="../../../../public/assets/images/logo.png" type="image/x-icon">
<style>
@import url(https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;600;700&display=swap);
:root{
    --Primary: #0704a8ff;
    --NeutralBackground: #b7e8faff;
    --Text: #00010aff;
    --Highlight: #dc7d09ff;
    --Accent: #01a809ff;
}
*{
    font-family: 'Rajdhani', sans-serif;
}
form {max-width:400px;margin:20px auto;padding:20px;border:1px solid #ddd;border-radius:5px;}
label {display:block;margin-top:10px;}
input {width:100%;padding:8px;margin-top:5px;}
button {margin-top:15px;padding:10px;background:#4CAF50;color:#fff;border:none;cursor:pointer;}
</style>
</head>
<body>
<h2>Edit Product</h2>
<form method="post">
    <label>Name</label>
    <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>

    <label>Quantity</label>
    <input type="number" name="quantity" value="<?= $product['quantity'] ?>" required>

    <label>Price</label>
    <input type="text" name="price" value="<?= $product['price'] ?>" required>

    <label>Expiry Date</label>
    <input type="date" name="expiry_date" value="<?= $product['expiry_date'] ?>" required>

    <button type="submit">Update Product</button>
</form>
</body>
</html>
