<?php
session_start();
require_once '../../../../config/config.php';

/** CART STRUCTURE (in session)
 * $_SESSION['cart'] = [
 *    productId => quantity,
 *    ...
 * ]
 */

if(!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

/* Add to cart */
if(isset($_POST['add_to_cart'])) {
  $pid = (int)$_POST['product_id'];
  if(!isset($_SESSION['cart'][$pid])) $_SESSION['cart'][$pid] = 0;
  $_SESSION['cart'][$pid] += 1;
  header("Location: cart.php"); exit;
}

/* Update quantities */
if(isset($_POST['update_cart'])) {
  if(isset($_POST['qty']) && is_array($_POST['qty'])) {
    foreach($_POST['qty'] as $pid => $q) {
      $q = max(0, (int)$q);
      if($q == 0) {
        unset($_SESSION['cart'][$pid]);
      } else {
        $_SESSION['cart'][$pid] = $q;
      }
    }
  }
  header("Location: cart.php"); exit;
}

/* Remove single item */
if(isset($_GET['remove'])) {
  $rid = (int)$_GET['remove'];
  unset($_SESSION['cart'][$rid]);
  header("Location: cart.php"); exit;
}

/* Build items */
$items = [];
$total = 0;
if(!empty($_SESSION['cart'])) {
  $ids = implode(',', array_map('intval', array_keys($_SESSION['cart'])));
  $res = $conn->query("SELECT * FROM products WHERE id IN ($ids)");
  while($row = $res->fetch_assoc()) {
    $row['quantity'] = $_SESSION['cart'][$row['id']];
    $row['line_total'] = $row['price'] * $row['quantity'];
    $items[] = $row;
    $total += $row['line_total'];
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Your Cart</title>
  <link rel="shortcut icon" href="../../../../public/assets/images/logo.png" type="image/x-icon">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.css">

<style>
@import url(https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;600;700&display=swap);

body{font-family:'Rajdhani', sans-serif;background:#f5f5f5;margin:0;}
.cart_container{max-width:1000px;margin:40px auto;background:#fff;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,.08);padding:20px;}
.cart_h2{margin:0 0 15px;}
.cart_table{width:100%;border-collapse:collapse;}
.cart_th,.cart_td{padding:12px;border-bottom:1px solid #eee;text-align:center;}
.cart_th{background:#17a2b8;color:#fff;}
.cart_qty_input{width:60px;text-align:center;}
.cart_actions{display:flex;gap:8px;justify-content:center;}
.cart_btn{padding:8px 12px;border:none;border-radius:6px;cursor:pointer}
.cart_btn_update{background:#17a2b8;color:#fff;}
.cart_btn_remove{background:#dc3545;color:#fff;}
.cart_summary{margin-top:15px;display:flex;justify-content:flex-end;gap:20px;align-items:center;}
.cart_btn_checkout{background:#28a745;color:#fff;}
.cart_top_links{display:flex;justify-content:space-between;margin-bottom:12px;}
.cart_top_links a{color:#17a2b8;text-decoration:none}
.cart_top_links a:hover{text-decoration:underline}

</style>
</head>
<body>
<div class="cart_container">
  <div class="cart_top_links">
    <h2 class="cart_h2">🛒 Your Cart</h2>
    <a href="pharmacy.php">← Continue Shopping</a>
  </div>

  <?php if(empty($items)): ?>
    <p>Your cart is empty.</p>
  <?php else: ?>
  <form method="post">
    <table class="cart_table">
      <tr>
        <th class="cart_th">#</th>
        <th class="cart_th">Product</th>
        <th class="cart_th">Price</th>
        <th class="cart_th">Qty</th>
        <th class="cart_th">Line Total</th>
        <th class="cart_th">Action</th>
      </tr>
      <?php foreach($items as $i=>$it): ?>
      <tr>
        <td class="cart_td"><?= $i+1 ?></td>
        <td class="cart_td"><?= htmlspecialchars($it['name']) ?></td>
        <td class="cart_td">$<?= number_format($it['price'],2) ?></td>
        <td class="cart_td"><input class="cart_qty_input" type="number" name="qty[<?= $it['id'] ?>]" value="<?= (int)$it['quantity'] ?>" min="0"></td>
        <td class="cart_td">$<?= number_format($it['line_total'],2) ?></td>
        <td class="cart_actions cart_td">
          <a class="cart_btn cart_btn_remove" href="cart.php?remove=<?= $it['id'] ?>"><i class="ri-delete-bin-6-line"></i></a>
        </td>
      </tr>
      <?php endforeach; ?>
    </table>

    <div class="cart_summary">
      <strong>Total: $<?= number_format($total,2) ?></strong>
      <button type="submit" name="update_cart" class="cart_btn cart_btn_update">Update Cart</button>
      <form action="checkout.php" method="post" style="display:inline;">
        <button class="cart_btn cart_btn_checkout" type="button" onclick="location.href='checkout.php'">Proceed to Checkout</button>
      </form>
    </div>
  </form>
  <?php endif; ?>
</div>
</body>
</html>