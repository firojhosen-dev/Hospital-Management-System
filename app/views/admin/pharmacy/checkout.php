<?php
session_start();
require_once '../../../../config/config.php';

if(empty($_SESSION['cart'])) { header("Location: cart.php"); exit; }

/* Build cart items + total */
$items = [];
$total = 0;
$ids = implode(',', array_map('intval', array_keys($_SESSION['cart'])));
$res = $conn->query("SELECT * FROM products WHERE id IN ($ids)");
while($row = $res->fetch_assoc()) {
  $qty = (int)$_SESSION['cart'][$row['id']];
  $row['quantity'] = $qty;
  $row['line_total'] = $row['price'] * $qty;
  $items[] = $row;
  $total += $row['line_total'];
}

/* Place order */
$success = $error = "";
if(isset($_POST['place_order'])) {
  $name = trim($_POST['name'] ?? "");
  $email = trim($_POST['email'] ?? "");
  $phone = trim($_POST['phone'] ?? "");
  $address = trim($_POST['address'] ?? "");
  $payment_method = $_POST['payment_method'] ?? "Cash on Delivery";
  $user_id = $_SESSION['user_id'] ?? 0;

  if($name=="" || $phone=="" || $address=="") {
    $error = "Please fill in name, phone and address.";
  } else {
    $conn->begin_transaction();
    try {
      $stmt = $conn->prepare("INSERT INTO orders (user_id, name, email, phone, address, payment_method, total, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')");
      $stmt->bind_param("isssssd", $user_id, $name, $email, $phone, $address, $payment_method, $total);
      $stmt->execute();
      $order_id = $stmt->insert_id;

      $ins = $conn->prepare("INSERT INTO order_items (order_id, product_id, name, price, quantity) VALUES (?, ?, ?, ?, ?)");
      foreach($items as $it){
        $pid = $it['id'];
        $pname = $it['name'];
        $pprice = $it['price'];
        $pqty = $it['quantity'];
        $ins->bind_param("iisdi", $order_id, $pid, $pname, $pprice, $pqty);
        $ins->execute();
      }

      // record payment row (initiated)
      $pm = $conn->prepare("INSERT INTO payments (order_id, method, amount, status) VALUES (?, ?, ?, 'initiated')");
      $pm->bind_param("isd", $order_id, $payment_method, $total);
      $pm->execute();

      $conn->commit();

      // route by payment method
      if($payment_method === 'Cash on Delivery'){
        // mark as success-like placed (still pending)
        unset($_SESSION['cart']);
        header("Location: order_success.php?order_id=".$order_id);
        exit;
      } else {
        // go to payment init (bkash/nagad/card)
        header("Location: payment_init.php?order_id=$order_id&method=".urlencode($payment_method));
        exit;
      }
    } catch(Exception $e){
      $conn->rollback();
      $error = "Order failed: ".$e->getMessage();
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Checkout</title>
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
body{font-family:'Rajdhani', sans-serif;background:#f5f5f5;margin:0;}
.container{max-width:1100px;margin:30px auto;display:grid;grid-template-columns:2fr 1fr;gap:20px;padding:0 15px;}
.card{background:#fff;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,.08);padding:20px;}
h2{margin:0 0 15px;}
table{width:100%;border-collapse:collapse;}
th,td{padding:10px;border-bottom:1px solid #eee;text-align:center;}
th{background:#17a2b8;color:#fff;}
label{display:block;margin:8px 0 4px;}
input,textarea,select{width:100%;padding:10px;border:1px solid #ccc;border-radius:6px;}
.btn{width:100%;padding:12px;margin-top:12px;border:none;border-radius:8px;background:#28a745;color:#fff;cursor:pointer;font-size:16px;}
.btn:hover{background:#218838;}
.notice{padding:10px;border-radius:6px;margin-bottom:10px;}
.error{background:#f8d7da;color:#721c24;}
</style>
</head>
<body>
<div class="container">
  <div class="card">
    <h2>📦 Delivery Information</h2>
    <?php if($error): ?><div class="notice error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <form method="post">
      <label>Full Name</label>
      <input type="text" name="name" required>

      <label>Email</label>
      <input type="email" name="email">

      <label>Phone</label>
      <input type="text" name="phone" required>

      <label>Address</label>
      <textarea name="address" required></textarea>

      <label>Payment Method</label>
      <select name="payment_method" required>
        <option value="Cash on Delivery">💵 Cash on Delivery</option>
        <option value="Bkash">📱 Bkash</option>
        <option value="Nagad">📱 Nagad</option>
        <option value="Card">💳 Card</option>
      </select>

      <button class="btn" type="submit" name="place_order">Place Order</button>
    </form>
  </div>

  <div class="card">
    <h2>🛒 Order Summary</h2>
    <table>
      <tr><th>Product</th><th>Qty</th><th>Price</th><th>Total</th></tr>
      <?php foreach($items as $it): ?>
      <tr>
        <td><?= htmlspecialchars($it['name']) ?></td>
        <td><?= (int)$it['quantity'] ?></td>
        <td>$<?= number_format($it['price'],2) ?></td>
        <td>$<?= number_format($it['line_total'],2) ?></td>
      </tr>
      <?php endforeach; ?>
      <tr>
        <td colspan="3" style="text-align:right;"><strong>Grand Total</strong></td>
        <td><strong>$<?= number_format($total,2) ?></strong></td>
      </tr>
    </table>
  </div>
</div>
</body>
</html>