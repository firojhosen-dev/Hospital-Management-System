<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}
require_once '../../../config/config.php';

// ✅ Delete Doctor
if (isset($_GET['delete'])) {
    $doctor_id = intval($_GET['delete']);
    $conn->query("DELETE FROM users WHERE id = $doctor_id AND role='doctor'");
    $_SESSION['msg'] = "✅ Doctor deleted successfully!";
    header("Location: manage_doctors.php");
    exit();
}

// ✅ Fetch Doctors
$result = $conn->query("SELECT * FROM users WHERE role='doctor'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Doctors</title>
  <link rel="shortcut icon" href="../../../public/assets/images/logo.png" type="image/x-icon">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.css">
  <!-- <link rel="stylesheet" href="../../../public/assets/css/admin.css"> -->
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
  color: #010567ff;
  font-size: 28px;
  font-weight:bold;
}

/* ✅ Message */
p {
  text-align: center;
  font-weight: 500;
  color: green;
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
  background: #010567ff;
  color: white;
  font-weight: 600;
  text-transform: uppercase;
  font-size: 13px;
  letter-spacing: 0.5px;
}
table tr:nth-child(even) {
  background: var(--NeutralCardAndTableBackgroundHover);
}

/* ✅ Action Links */
 td a {
  display: inline-block;
  padding: 6px 10px;
  border-radius: 6px;
  font-size: 13px;
  margin: 2px;
  text-decoration: none;
  transition: background 0.3s;
}
td a.edit {
  background: var(--Edit);
  color: #fff;
}
td a.edit:hover {
  background: var(--EditHover);
}
td a.delete {
  background: var(--Delete);
  color: #fff;
}
td a.delete:hover {
  background: var(--DeleteHover);
} 

/* ✅ Responsive: Mobile/Tablet */
@media (max-width: 992px) {
  table, thead, tbody, th, td, tr {
    display: block;
  }
  table thead {
    display: none; /* ❌ হেডার লুকানো */
  }
  table tr {
    margin: 15px auto;
    max-width: 500px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.1);
    border-radius: 30px;
    background: #fff;
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
  <h2>Manage Doctors</h2>

  <?php if (isset($_SESSION['msg'])): ?>
      <p><?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?></p>
  <?php endif; ?>

  <table>
    <thead>
        <tr>
      <th>Name</th>
      <th>Email</th>
      <th>Phone</th>
      <th>Specialization</th>
      <th>Action</th>
    </tr>
    </thead>

    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
      <td data-label="Name"><?php echo $row['name']; ?></td>
      <td data-label="Email"><?php echo $row['email']; ?></td>
      <td data-label="Phone"><?php echo $row['phone']; ?></td>
      <td data-label="Specialization"><?php echo $row['specialization']; ?></td>
      <td data-label="Action">
        <a href="edit_doctor.php?id=<?php echo $row['id']; ?>" class="edit"><i class="ri-file-edit-line"></i> Edit</a>
        <a href="manage_doctors.php?delete=<?php echo $row['id']; ?>" class="delete" onclick="return confirm('Are you sure?');"><i class="ri-close-large-line"></i> Delete</a>
      </td>
    </tr>
    <?php endwhile; ?>
  </table>

  <br>
  <a href="dashboard.php">⬅ Back to Dashboard</a>
</body>
</html>