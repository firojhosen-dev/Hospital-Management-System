<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'patient') {
    header("Location: ../auth/login.php");
    exit();
}
require_once '../../../config/config.php';

$patient_id = $_SESSION['user_id'];

// ✅ Fetch Reports
$result = $conn->query("SELECT r.*, u.name AS doctor_name
                        FROM reports r
                        JOIN users u ON r.doctor_id = u.id
                        WHERE r.patient_id = $patient_id
                        ORDER BY r.created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Reports</title>
    <link rel="shortcut icon" href="../../../public/assets/images/logo.png" type="image/x-icon">
<style>
@import url(https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;600;700&display=swap);
body{
    font-family: 'Rajdhani', sans-serif;

}
h2{
    text-align: center;
}
/* Report Table Styling */
table {
  width: 100%;
  max-width: 1000px;
  margin: 20px auto;
  border-collapse: collapse;
  background: #fff;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  border-radius: 12px;
  overflow: hidden;
}

table th, table td {
  padding: 12px 15px;
  text-align: left;
  font-size: 15px;
}

table th {
  background: #1976d2;
  color: white;
  font-weight: bold;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

table tr:nth-child(even) {
  background: #f9f9f9;
}

table tr:hover {
  background: #eef6ff;
}

table a {
  text-decoration: none;
  color: #1976d2;
  font-weight: 600;
}

table a:hover {
  text-decoration: underline;
}

/* Back button styling */
a[href="dashboard.php"] {
  display: inline-block;
  margin: 20px auto;
  padding: 10px 20px;
  background: #1976d2;
  color: white;
  border-radius: 8px;
  font-weight: bold;
  text-decoration: none;
  transition: 0.3s;
}

a[href="dashboard.php"]:hover {
  background: #125aa0;
}

/* Responsive Design */
@media (max-width: 768px) {
  table, thead, tbody, th, td, tr {
    display: block;
  }

  table tr {
    margin-bottom: 15px;
    border-bottom: 2px solid #ddd;
    padding: 10px;
    border-radius: 10px;
    background: #fff;
  }

  table td {
    text-align: right;
    padding-left: 50%;
    position: relative;
  }

  table td::before {
    content: attr(data-label);
    position: absolute;
    left: 15px;
    width: 45%;
    text-align: left;
    font-weight: bold;
    color: #333;
  }

  table th {
    display: none;
  }
}
</style>
</head>
<body>
    <h2>My Reports</h2>

    <table border="1" cellpadding="10">
        <tr>
            <th>Doctor</th>
            <th>Report File</th>
            <th>Date</th>
            <th>Action</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['doctor_name']; ?></td>
            <td><?php echo $row['report_file']; ?></td>
            <td><?php echo $row['created_at']; ?></td>
            <td>
                <a href="../../../public/assets/uploads/<?php echo $row['report_file']; ?>" target="_blank">📄 View / Download</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <a href="dashboard.php">⬅ Back to Dashboard</a>
</body>
</html>
