<?php
session_start();
require_once '../../../../config/config.php';

$order_id = (int)($_GET['order_id'] ?? 0);
$method = $_GET['method'] ?? 'Bkash';

if($order_id <= 0) { die("Invalid order."); }

/* fetch order */
$ord = $conn->query("SELECT * FROM orders WHERE id=".$order_id)->fetch_assoc();
if(!$ord) die("Order not found.");

$amount = $ord['total'];

/* NOTE:
 * এখানে sandbox/live credentials বসাতে হবে (bkash/nagad/card).
 * Demo হিসেবে আমরা simply success এ রিডাইরেক্ট করাচ্ছি।
 * তুমি চাইলে নিচের cURL অংশ uncomment করে sandbox API call করতে পারো।
 */

if($method === 'Bkash'){
  // TODO: Put bKash sandbox init here
  // Placeholder: directly simulate success
  header("Location: payment_callback.php?status=success&order_id=$order_id&trx_id=BK".time()."&method=bkash");
  exit;
}
elseif($method === 'Nagad'){
  header("Location: payment_callback.php?status=success&order_id=$order_id&trx_id=NG".time()."&method=nagad");
  exit;
}
elseif($method === 'Card'){
  header("Location: payment_callback.php?status=success&order_id=$order_id&trx_id=CD".time()."&method=card");
  exit;
}
else{
  header("Location: payment_callback.php?status=failed&order_id=$order_id&method=$method");
  exit;
}