<?php
session_start();
require_once '../../../../config/config.php';


$id = $_GET['id'] ?? 0;
$stmt = $conn->prepare("SELECT * FROM products WHERE id=?");
$stmt->bind_param("i",$id);
$stmt->execute();
$res = $stmt->get_result();
$prod = $res->fetch_assoc();
if(!$prod){ echo "Product not found"; exit; }

$images = explode(',', $prod['images']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Product</title>
    <link rel="shortcut icon" href="../../../../public/assets/images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.css">

<style>
@import url(https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;600;700&display=swap);

body{font-family:'Rajdhani', sans-serif;background:#f5f5f5;margin:0;padding:0;}
.container{max-width:600px;margin:50px auto;padding:0 15px;}
form{background:#fff;padding:20px;border-radius:10px;box-shadow:0 5px 15px rgba(0,0,0,0.1);}
input,textarea{width:100%;padding:10px;margin-top:5px;margin-bottom:15px;border:1px solid #ccc;border-radius:5px;}
button{width:100%;padding:12px;background:#007bff;color:#fff;border:none;border-radius:5px;cursor:pointer;}
button:hover{background:#0056b3;}
.img-preview{display:flex;gap:10px;margin-bottom:15px;}
.img-preview img{width:80px;height:80px;object-fit:cover;border-radius:5px;}
</style>
</head>
<body>
<div class="container">
<h2>Edit Product</h2>
<form id="editForm" enctype="multipart/form-data">
<input type="hidden" name="id" value="<?= $prod['id'] ?>">
<input type="text" name="name" placeholder="Product Name" value="<?= htmlspecialchars($prod['name']) ?>" required>
<textarea name="description" placeholder="Description"><?= htmlspecialchars($prod['description']) ?></textarea>
<input type="text" name="quantity" placeholder="Quantity" value="<?= $prod['quantity'] ?>" min="0">
<input type="number" step="0.01" name="price" placeholder="Price" value="<?= $prod['price'] ?>">
<input type="text" name="manufacturer" placeholder="Manufacturer" value="<?= $prod['manufacturer'] ?>">
<input type="date" name="expiry_date" value="<?= $prod['expiry_date'] ?>">

<label>Current Images:</label>
<div class="img-preview">
<?php foreach($images as $img): 
if(!empty($img) && file_exists("uploads/".$img)): ?>
<img src="uploads/<?= $img ?>" alt="">
<?php endif; endforeach; ?>
</div>

<label>Upload New Images (max 5)</label>
<input type="file" name="images[]" accept="image/*" multiple>
<button type="submit">Update Product</button>
</form>
</div>
<script src="../../../../public/assets/js/main.js"></script>
</body>
</html>