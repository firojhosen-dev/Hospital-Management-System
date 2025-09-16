<?php
session_start();
require_once '../../../../config/config.php';

if(!isset($_SESSION['user_id'])){
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$product_id = (int)$_POST['product_id'];

// সরাসরি checkout page এ redirect
header("Location: checkout.php?product_id=".$product_id);
exit;