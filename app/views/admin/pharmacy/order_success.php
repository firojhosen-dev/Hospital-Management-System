<?php
session_start();
require_once '../../../../config/config.php';
$order_id = (int)($_GET['order_id'] ?? 0);
$order = $conn->query("SELECT * FROM orders WHERE id=".$order_id)->fetch_assoc();
if(!$order) die("Order not found");
$items = $conn->query("SELECT * FROM order_items WHERE order_id=".$order_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order Success</title>
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
.container{max-width:900px;margin:40px auto;background:#fff;padding:20px;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,.08);}
h2{margin:0 0 10px;}
.order-info{margin-bottom:15px;color:#28a745;}
table{width:100%;border-collapse:collapse;}
th,td{padding:10px;border-bottom:1px solid #eee;text-align:center;}
th{background:#17a2b8;color:#fff;}
a{color:#17a2b8;text-decoration:none;}
</style>
</head>
<body>
<div class="container">
  <h2>🎉 Thank you! Your order is confirmed.</h2>
  <p class="order-info">Order #<?= $order['id'] ?> | Status: <strong><?= htmlspecialchars($order['status']) ?></strong> | Total: <strong>$<?= number_format($order['total'],2) ?></strong></p>

  <h3>Order Items</h3>
  <table>
    <tr><th>Product</th><th>Price</th><th>Qty</th><th>Line Total</th></tr>
    <?php while($it = $items->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($it['name']) ?></td>
        <td>$<?= number_format($it['price'],2) ?></td>
        <td><?= (int)$it['quantity'] ?></td>
        <td>$<?= number_format($it['price'] * $it['quantity'],2) ?></td>
      </tr>
    <?php endwhile; ?>
  </table>

  <p style="margin-top:15px;"><a href="pharmacy.php">← Continue Shopping</a></p>
</div>
</body>
</html>