<?php
session_start();
require_once '../../../../config/config.php';

if(!isset($_SESSION['user_id'])){
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];


$sql = "SELECT o.id AS order_id, p.name AS product_name, p.price, o.quantity, o.total_price, o.status, o.created_at
        FROM orders o
        JOIN products p ON o.product_id = p.id
        WHERE o.user_id = ?
        ORDER BY o.id DESC";

$stmt = $conn->prepare($sql);
if(!$stmt){
    die("❌ Prepare failed: " . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
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
.container{max-width:1000px;margin:50px auto;padding:0 15px;}
h2{text-align:center;margin-bottom:30px;color:#333;}
.order-cards{display:flex;flex-wrap:wrap;gap:20px;}
.card-order{background:#fff;padding:15px;border-radius:10px;box-shadow:0 4px 12px rgba(0,0,0,0.08);flex:1 1 calc(50% - 20px);box-sizing:border-box;}
.card-order h4{margin:5px 0;color:#17a2b8;}
.card-order p{margin:5px 0;color:#333;}
.status-pending{color:#ffc107;font-weight:bold;}
.status-completed{color:#28a745;font-weight:bold;}
.status-cancelled{color:#dc3545;font-weight:bold;}
@media(max-width:768px){.card-order{flex:1 1 100%;}}
</style>
</head>
<body>
<div class="container">
<h2>My Orders</h2>
<div class="order-cards">
<?php
if($res->num_rows==0){
    echo "<p style='text-align:center;'>You have no orders yet.</p>";
} else {
    while($row = $res->fetch_assoc()):
?>
<div class="card-order">
    <h4>Order #<?= $row['order_id'] ?></h4>
    <p><strong>Product:</strong> <?= htmlspecialchars($row['product_name']) ?></p>
    <p><strong>Price:</strong> $<?= $row['price'] ?> x <?= $row['quantity'] ?> = <strong>$<?= $row['total_price'] ?></strong></p>
    <p><strong>Status:</strong> 
        <?php 
        if($row['status']=='pending') echo '<span class="status-pending">Pending</span>';
        elseif($row['status']=='completed') echo '<span class="status-completed">Completed</span>';
        elseif($row['status']=='cancelled') echo '<span class="status-cancelled">Cancelled</span>';
        ?>
    </p>
    <p><strong>Ordered on:</strong> <?= date("d M, Y H:i", strtotime($row['created_at'])) ?></p>
</div>
<?php endwhile; } ?>
</div>
</div>
</body>
</html>