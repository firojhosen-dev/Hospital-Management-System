<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'patient') {
    header("Location: ../auth/login.php");
    exit();
}
require_once '../../../config/config.php';

$patient_id = $_SESSION['user_id'];

// ✅ Fetch Prescriptions
$result = $conn->query("SELECT p.id AS prescription_id, p.prescription, p.created_at,
                               u.name AS doctor_name
                        FROM prescriptions p
                        JOIN users u ON p.doctor_id = u.id
                        WHERE p.patient_id = $patient_id
                        ORDER BY p.created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Prescriptions</title>
    <!-- <link rel="stylesheet" href="../../../public/assets/css/main.css"> -->
    <link rel="shortcut icon" href="../../../public/assets/images/logo.png" type="image/x-icon">
<style>
@import url(https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;600;700&display=swap);
body{
    font-family: 'Rajdhani', sans-serif;

}
/* Prescription Table Styling */
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
  background: #4CAF50;
  color: white;
  font-weight: bold;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

table tr:nth-child(even) {
  background: #f9f9f9;
}

table tr:hover {
  background: #f1f7ff;
}

table a {
  text-decoration: none;
  color: #007BFF;
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
  background: #4CAF50;
  color: white;
  border-radius: 8px;
  font-weight: bold;
  text-decoration: none;
  transition: 0.3s;
}

a[href="dashboard.php"]:hover {
  background: #43a047;
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
    <h2>💊 My Prescriptions</h2>

    <table border="1" cellpadding="10">
        <tr>
            <th>Doctor</th>
            <th>Prescription</th>
            <th>Date</th>
            <th>Action</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['doctor_name']; ?></td>
            <td><?php echo nl2br($row['prescription']); ?></td>
            <td><?php echo $row['created_at']; ?></td>
            <td>
                <a href="../../../app/views/patient/prescription_pdf.php?id=<?php echo $row['prescription_id']; ?>" target="_blank">📄 Download PDF</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <a href="dashboard.php">⬅ Back to Dashboard</a>
</body>
</html>
