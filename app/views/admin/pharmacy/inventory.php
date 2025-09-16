<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}
require_once "../../../../config/config.php";

// Search
$search = "";
if (isset($_GET['q'])) {
    $search = $conn->real_escape_string($_GET['q']);
}

$sql = "SELECT * FROM products WHERE name LIKE '%$search%' ORDER BY id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Inventory Management</title>
<link rel="shortcut icon" href="../../../../public/assets/images/logo.png" type="image/x-icon">
<style>
@import url(https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;600;700&display=swap);
:root{
    --Primary: #0704a8ff;
    --NeutralBackground: #b7e8faff;
    --Text: #00010aff;
    --Highlight: #dc7d09ff;
    --Accent: #01a809ff;
}
/* General Reset */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
    font-family: 'Rajdhani', sans-serif;
}

body {
  background: var(--NeutralBackground);
  padding: 20px;
  color: var(--Text);
}

/* Heading */
h2 {
  text-align: center;
  margin-bottom: 20px;
  font-size: 26px;
  color: var(--Primary);
}

/* Search Box */
.search-box {
  display: flex;
  justify-content: center;
  margin-bottom: 20px;
}

.search-box input {
  padding: 10px 15px;
  width: 300px;
  border: 1px solid var(--Primary);
  border-radius: 8px 0 0 8px;
  outline: none;
  transition: all 0.3s ease;
}

.search-box input:focus {
  border-color: var(--Highlight);
  box-shadow: 0 0 6px rgba(220, 125, 9, 0.4);
}

.search-box button {
  padding: 10px 20px;
  border: none;
  background: var(--Primary);
  color: white;
  font-weight: bold;
  border-radius: 0 8px 8px 0;
  cursor: pointer;
  transition: background 0.3s;
}

.search-box button:hover {
  background: var(--Highlight);
}

/* Table Styling */
.table {
  width: 100%;
  border-collapse: collapse;
  background: white;
  box-shadow: 0 2px 6px rgba(0,0,0,0.08);
  border-radius: 12px;
  overflow: hidden;
}

.table th, 
.table td {
  padding: 14px 16px;
  text-align: left;
  border-bottom: 1px solid #e2e8f0;
  font-size: 15px;
}

.table th {
  background: var(--Primary);
  font-weight: 600;
  color: #fff;
}

.table tr:hover {
  background: rgba(7, 4, 168, 0.05);
}

/* Action Buttons */
.action-btn {
  padding: 6px 12px;
  text-decoration: none;
  border-radius: 6px;
  font-size: 14px;
  margin: 0 4px;
  display: inline-block;
  transition: 0.3s;
}

.action-btn.edit {
  background: var(--Accent);
  color: white;
}

.action-btn.edit:hover {
  background: #018c07;
}

.action-btn.delete {
  background: var(--Highlight);
  color: white;
}

.action-btn.delete:hover {
  background: #a95006;
}

/* Responsive Table */
@media (max-width: 768px) {
  .table, 
  .table thead, 
  .table tbody, 
  .table th, 
  .table td, 
  .table tr {
    display: block;
    width: 100%;
  }

  .table tr {
    margin-bottom: 15px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.05);
    border-radius: 10px;
    padding: 10px;
    background: #fff;
  }

  .table td {
    text-align: right;
    padding-left: 50%;
    position: relative;
    border: none;
    border-bottom: 1px solid #f1f5f9;
  }

  .table td::before {
    content: attr(data-label);
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    font-weight: 600;
    color: var(--Primary);
  }

  .table th {
    display: none;
  }
}
</style>
</head>
<body>
<h2>Inventory Management</h2>

<form method="get" class="search-box">
    <input type="text" name="q" placeholder="Search product..." value="<?= htmlspecialchars($search) ?>">
    <button type="submit">Search</button>
</form>

<table class="table">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Expiry Date</th>
        <th>Action</th>
    </tr>
    <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= $row['quantity'] ?></td>
            <td><?= $row['price'] ?> ৳</td>
            <td><?= $row['expiry_date'] ?></td>
            <td>
                <a href="edit_inventory.php?id=<?= $row['id'] ?>" class="action-btn edit">Edit</a>
                <a href="delete_inventory.php?id=<?= $row['id'] ?>" class="action-btn delete" onclick="return confirm('Are you sure?');">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="6">No products found</td></tr>
    <?php endif; ?>
</table>
<script>
// Add responsive labels for mobile
document.querySelectorAll(".table tr").forEach(row => {
  row.querySelectorAll("td").forEach((cell, i) => {
    const header = row.closest("table").querySelectorAll("th")[i];
    if (header) {
      cell.setAttribute("data-label", header.innerText);
    }
  });
});
</script>
</body>
</html>
