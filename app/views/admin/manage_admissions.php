<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}
require_once '../../../config/config.php';


// ✅ Confirm / Reject Action
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $status = ($_GET['action'] == 'confirm') ? 'confirmed' : 'rejected';
    $conn->query("UPDATE patient_admissions SET status='$status' WHERE id=$id");
    header("Location: manage_admissions.php");
    exit();
}

$result = $conn->query("SELECT * FROM patient_admissions ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Patient Admissions</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.css">
    <link rel="shortcut icon" href="../../../public/assets/images/logo.png" type="image/x-icon">
<style>
@import url(https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;600;700&display=swap);
/* light mood */
:root{
    --Primary: #0704a8ff;
    --NeutralBackground: #b7e8faff;
    --NeutralCardAndTableBackground: #aed4e1ff;
    --NeutralCardAndTableBackgroundHover: #98c7d7ff;
    --Text: #00010aff;
    --Highlight: #dc7d09ff;
    --Accent: #01a809ff;
    --Edit: #010567ff;
    --EditHover: #02066fff;
    --Delete: #850e01ff;
    --DeleteHover: #641007ff;
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
/* ✅ Page Layout */
body {
  background: var(--NeutralBackground);
  margin: 0;
  padding: 20px;
  color: #333;
}

h2 {
  text-align: center;
  margin-bottom: 20px;
  color: var(--Edit);
  font-size: 28px;
  font-weight: bold;
}

/* ✅ Message */
p {
  text-align: center;
  font-weight: 500;
}

/* ✅ Search Form */
form {
  display: flex;
  gap: 10px;
  justify-content: center;
  margin-bottom: 20px;
}
form input[type="text"] {
  padding: 10px;
  border: 1px solid var(--Highlight);
  border-radius: 30px;
  width: 280px;
}
form input:focus{
  border-color: var(--Accent);
  outline: none;
}
form button,
form a {
  padding: 10px 16px;
  border: none;
  border-radius: 30px;
  background: var(--Edit);
  color: #fff;
  cursor: pointer;
  font-size: 14px;
  text-decoration: none;
  transition: background 0.3s;
}
form a {
  background: var(--Edit);
}
form button:hover {
  background: var(--EditHover);
}
form a:hover {
  background: var(--EditHover);
}

/* ✅ Table Styling */
table {
  width: 100%;
  border-collapse: collapse;
  background: var(--NeutralCardAndTableBackground);
  border-radius: 30px 30px 5px 5px;
  overflow: hidden;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}
table th, table td {
  padding: 12px 15px;
  text-align: center;
}
table th {
  background: var(--Edit);
  color: white;
  font-weight: 600;
  text-transform: uppercase;
  font-size: 13px;
  letter-spacing: 0.5px;
}
table tr:nth-child(even) {
  background: var(--NeutralCardAndTableBackground);
}
table tr {
  background: var(--NeutralCardAndTableBackgroundHover);
  transition: 0.3s;
}

/* ✅ Action Links */
td a {
  display: inline-block;
  padding: 6px 10px;
  background: var(--Edit);
  color: #fff;
  text-decoration: none;
  border-radius: 6px;
  font-size: 13px;
  margin: 2px;
  transition: background 0.3s;
}
td a:hover {
  background: var(--EditHover);
}
td a.delete {
  background: var(--Delete);
}
td a.delete:hover {
  background: var(--DeleteHover);
}
button{
  background: var(--Edit);
  border-radius: 30px;
  color: #fff;
  padding: 12px;
  border:none;
}
button a{
  font-size:20px;
  color: #fff;
  text-decoration:none;
}
/* ✅ Responsive: Mobile/Tablet */
@media (max-width: 992px) {
  table, thead, tbody, th, td, tr {
    display: block;
  }
  table thead {
    display: none;
  }
  table tr {
    margin: 15px auto;
    max-width: 500px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.1);
    border-radius: 8px;
    background: var(--Edit);
    padding: 12px;
  }
  table td {
    text-align: right;
    padding-left: 50%;
    position: relative;
    border: none;
    border-bottom: 1px solid #eee;
  }
  table td::before {
    content: attr(data-label);
    position: absolute;
    left: 15px;
    width: 45%;
    text-align: left;
    font-weight: bold;
    color: #555;
  }
  table td:last-child {
    border-bottom: none;
  }
}

/* Custom Scrollbar */
::-webkit-scrollbar {width: 10px;}
::-webkit-scrollbar-track {background: #f1f1f1;}
::-webkit-scrollbar-thumb {background: #0077cc;border-radius: 5px;  }
::-webkit-scrollbar-thumb:hover {background: #005fa3; }
* {scrollbar-width: thin;scrollbar-color: #0077cc #f1f1f1;}
</style>
</head>
<body>
<h2>Patient Admission Requests</h2>
<!-- ✅ Search Form -->
    <!-- <form method="GET">
        <input type="text" name="search" placeholder="Search by Name or Phone" value="<?php echo $search; ?>">
        <button type="submit">Search</button>
        <a href="manage_all_patients.php">Reset</a>
    </form> -->
    <!-- <br><br> -->

<table border="1" cellpadding="5">
    <thead>
        <tr>
        <th>Admission ID</th>
        <th>Name</th>
        <th>Phone</th>
        <th>Disease</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    </thead>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo $row['admission_id']; ?></td>
        <td><?php echo $row['patient_name']; ?></td>
        <td><?php echo $row['phone']; ?></td>
        <td><?php echo $row['disease']; ?></td>
        <td><?php echo ucfirst($row['status']); ?></td>
        <td>
            <?php if($row['status'] == 'pending'): ?>
                <a href="?action=confirm&id=<?php echo $row['id']; ?>"><i class="ri-checkbox-circle-line"></i> Confirm</a> |
                <a href="?action=reject&id=<?php echo $row['id']; ?>"><i class="ri-close-circle-line"></i> Reject</a>
            <?php else: ?>
                -
            <?php endif; ?>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
<br>
<button><a href="dashboard.php">⬅ Back to Dashboard</a></button>
</body>
</html>
