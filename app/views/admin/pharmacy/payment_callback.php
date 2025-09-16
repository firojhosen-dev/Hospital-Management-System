<?php
session_start();
require_once '../../../../config/config.php';

$status = $_GET['status'] ?? 'failed';
$order_id = (int)($_GET['order_id'] ?? 0);
$trx_id = $_GET['trx_id'] ?? '';
$method = $_GET['method'] ?? 'bkash';

if($order_id <= 0) die("Invalid order");

if($status === 'success'){
  // update orders + payments
  $conn->query("UPDATE orders SET status='paid' WHERE id=".$order_id);

  $stmt = $conn->prepare("UPDATE payments SET status='success', trx_id=? WHERE order_id=? AND method=?");
  $stmt->bind_param("sis", $trx_id, $order_id, $method);
  $stmt->execute();

  // clear cart
  unset($_SESSION['cart']);

  header("Location: order_success.php?order_id=".$order_id);
  exit;
} else {
  $conn->query("UPDATE orders SET status='cancelled' WHERE id=".$order_id);
  $stmt = $conn->prepare("UPDATE payments SET status='failed' WHERE order_id=? AND method=?");
  $stmt->bind_param("is", $order_id, $method);
  $stmt->execute();

  echo "Payment failed/cancelled. <a href='checkout.php'>Back to checkout</a>";
}