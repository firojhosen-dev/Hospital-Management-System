<?php
session_start();
require_once '../../../../config/config.php';


$id = $_GET['id'] ?? 0;
$stmt = $conn->prepare("SELECT * FROM products WHERE id=?");
$stmt->bind_param("i",$id);
$stmt->execute();
$res = $stmt->get_result();
$prod = $res->fetch_assoc();
if(!$prod){ echo "Product not found"; exit;}
$images = explode(',', $prod['images']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($prod['name']) ?> | Product</title>
    <link rel="shortcut icon" href="../../../../public/assets/images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.css">
<style>
@import url(https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;600;700&display=swap);
/* light mood */
:root{
    --Primary: #0704a8ff;
    --NeutralBackground: #b7e8faff;
    --Text: #00010aff;
    --Highlight: #dc7d09ff;
    --Accent: #01a809ff;
}
/* Dark Mood */
:root{
    --PrimaryDark: #f35b04;
    --NeutralBackgroundDark: #03045e;
    --TextDark: #b7e8faff;
    --HighlightDark: #4331e0ff;
    --AccentDark: #f7bd04;
}
*{
    font-family: 'Rajdhani', sans-serif;
}
body{font-family:'Rajdhani', sans-serif;background:#f5f5f5;margin:0;padding:0;}
.view_product_container{max-width:800px;margin:50px auto;padding:0 15px;background:#fff;border-radius:10px;box-shadow:0 5px 15px rgba(0,0,0,0.1);}
.view_product_image_gallery{display:flex;gap:10px;overflow-x:auto;margin-top:15px;}
.view_product_image_gallery img{width:150px;height:150px;object-fit:cover;border-radius:5px;cursor:pointer;}
.view_product_main_image img{width:100%;height:400px;object-fit:cover;border-radius:10px 10px 0 0;}
.view_product_details{padding:20px;}
.view_product_details h2{margin:0 0 15px 0;color:#333;}
.view_product_details p{margin:5px 0;font-size:16px;}
.view_product_details .view_product_price{color:#28a745;font-weight:bold;font-size:18px;margin-top:10px;}
.view_product_button_cart{padding:12px 20px;background:#28a745;color:#fff;border:none;border-radius:5px;cursor:pointer;margin-top:15px; display:inline-block;}
.view_product_button_cart:hover{background:#218838;}
.view_product_back_btn{display:inline-block;margin-top:15px;padding:10px 15px;background:#007bff;color:#fff;border-radius:5px;text-decoration:none;}
.view_product_back_btn:hover{background:#0056b3;}


</style>
<script>
function showMainImage(src){
    document.getElementById('mainImg').src=src;
}
</script>
</head>
<body>
<div class="view_product_container">
<div class="view_product_main_image">
  <?php if(!empty($images[0]) && file_exists("uploads/".$images[0])): ?>
  <img id="mainImg" src="uploads/<?= $images[0] ?>" alt="<?= htmlspecialchars($prod['name']) ?>">
  <?php endif; ?>
</div>

<div class="view_product_image_gallery">
<?php foreach($images as $img): 
    if(!empty($img) && file_exists("uploads/".$img)):
?>
<img src="uploads/<?= $img ?>" onclick="showMainImage(this.src)" alt="<?= htmlspecialchars($prod['name']) ?>">
<?php endif; endforeach; ?>
</div>

<div class="view_product_details">
<h2><?= htmlspecialchars($prod['name']) ?></h2>
<p><strong>Description:</strong> <?= htmlspecialchars($prod['description']) ?></p>
<p><strong>Quantity:</strong> <?= $prod['quantity'] ?></p>
<p class="view_product_price"><strong>Price:</strong> $<?= $prod['price'] ?></p>
<p><strong>Manufacturer:</strong> <?= $prod['manufacturer'] ?></p>
<p><strong>Expiry Date:</strong> <?= $prod['expiry_date'] ?></p>

<!-- Add to Cart & Buy Now -->
      <form action="cart.php" method="post">
          <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
          <button type="submit" name="add_to_cart" class="view_product_button_cart">Add to Cart</button>
      </form>
      <form action="checkout.php" method="post">
          <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
          <button type="submit" name="buy_now" class="view_product_button_cart">Buy Now</button>
      </form>
<a class="view_product_back_btn" href="pharmacy.php">⬅ Back</a>
</div>
</div>
</body>
</html>