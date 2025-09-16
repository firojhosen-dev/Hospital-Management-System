<?php
session_start();
require_once '../../../../config/config.php';

// Just Admin Login/ Register
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// ✅ Order Status Update (Confirm / Cancel)
if (isset($_GET['action']) && isset($_GET['id'])) {
    $order_id = (int)$_GET['id'];
    $action = $_GET['action'];

    if ($action == 'confirm') {
        $conn->query("UPDATE orders SET status='Confirmed' WHERE id=$order_id");
    } elseif ($action == 'cancel') {
        $conn->query("UPDATE orders SET status='Cancelled' WHERE id=$order_id");
    }
    header("Location: manage_order.php");
    exit();
}

// ✅ Order
$sql = "SELECT o.*, p.name AS product_name, p.price, u.username 
        FROM orders o
        JOIN products p ON o.product_id = p.id
        JOIN users u ON o.user_id = u.id
        ORDER BY o.id DESC";

$result = $conn->query($sql);

// Error Check
if (!$result) {
    die("❌ Query Failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
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
        body{font-family:'Rajdhani', sans-serif;background:#f9f9f9;margin:0;padding:20px;}
        h2{text-align:center;margin-bottom:20px;}
        table{width:100%;border-collapse:collapse;background:#fff;box-shadow:0 4px 10px rgba(0,0,0,0.1);}
        th,td{padding:12px;border:1px solid #ddd;text-align:center;}
        th{background:#007bff;color:#fff;}
        tr:nth-child(even){background:#f2f2f2;}
        .btn{padding:6px 12px;border:none;border-radius:5px;cursor:pointer;color:#fff;}
        .btn-confirm{background:#28a745;}
        .btn-cancel{background:#dc3545;}
        .btn-confirm:hover{background:#218838;}
        .btn-cancel:hover{background:#c82333;}
    </style>
</head>
<body>

<h2>📦 Manage Orders</h2>

<table>
    <thead>
        <tr>
            <th>#ID</th>
            <th>User</th>
            <th>Product</th>
            <th>Price</th>
            <th>Qty</th>
            <th>Total</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['username']) ?></td>
            <td><?= htmlspecialchars($row['product_name']) ?></td>
            <td>$<?= $row['price'] ?></td>
            <td><?= $row['quantity'] ?></td>
            <td>$<?= $row['price'] * $row['quantity'] ?></td>
            <td><?= $row['status'] ?></td>
            <td>
                <?php if ($row['status'] == 'Pending'): ?>
                    <a href="manage_order.php?action=confirm&id=<?= $row['id'] ?>" class="btn btn-confirm">Confirm</a>
                    <a href="manage_order.php?action=cancel&id=<?= $row['id'] ?>" class="btn btn-cancel">Cancel</a>
                <?php else: ?>
                    <?= $row['status'] ?>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>