<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'patient') {
    header("Location: ../auth/login.php");
    exit();
}
require_once '../../../config/config.php';

// Patient এর সব Appointment ফেচ করা
$patient_id = $_SESSION['user_id'];
$result = $conn->query("SELECT a.*, u.name AS doctor_name, u.specialization 
                        FROM appointments a 
                        JOIN users u ON a.doctor_id = u.id
                        WHERE a.patient_id = $patient_id 
                        ORDER BY a.created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Appointments</title>
    <link rel="shortcut icon" href="../../../public/assets/images/logo.png" type="image/x-icon">
    <style>
@import url(https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;600;700&display=swap);
  
        /* Page Layout */
body {
    font-family: 'Rajdhani', sans-serif;

  background: #f4f7fa;
  margin: 0;
  padding: 20px;
  color: #333;
}

h2 {
  text-align: center;
  margin-bottom: 20px;
  color: #2c3e50;
  font-size: 28px;
  font-weight: 600;
}

/* Appointment Table */
table {
  width: 100%;
  border-collapse: collapse;
  background: #fff;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

table th,
table td {
  padding: 12px 15px;
  text-align: center;
}

table th {
  background: #3498db;
  color: white;
  font-weight: 600;
  text-transform: uppercase;
  font-size: 14px;
  letter-spacing: 0.5px;
}

table tr:nth-child(even) {
  background: #f9f9f9;
}

table tr:hover {
  background: #ecf6fd;
  transition: 0.3s;
}

/* Status Badges */
td:nth-child(5) {
  font-weight: bold;
}
td:nth-child(5):contains("Pending") {
  color: #f39c12;
}
td:nth-child(5):contains("Approved") {
  color: #27ae60;
}
td:nth-child(5):contains("Rejected") {
  color: #e74c3c;
}

/* Action Links */
a {
  display: inline-block;
  padding: 6px 12px;
  background: #3498db;
  color: #fff;
  text-decoration: none;
  border-radius: 6px;
  font-size: 14px;
  transition: background 0.3s;
}

a:hover {
  background: #2c82c9;
}

/* Responsive */
@media (max-width: 768px) {
  table, thead, tbody, th, td, tr {
    display: block;
  }

  table tr {
    margin-bottom: 15px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.1);
    border-radius: 8px;
    background: #fff;
    padding: 10px;
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
    color: #555;
  }

  table th {
    display: none;
  }
}

    </style>
</head>
<body>
    <h2>My Appointments</h2>

    <table border="1" cellpadding="10">
        <tr>
            <th>Doctor</th>
            <th>Specialization</th>
            <th>Date</th>
            <th>Time Slot</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td data-label="Doctor"><?php echo htmlspecialchars($row['doctor_name']); ?></td>
<td data-label="Specialization"><?php echo htmlspecialchars($row['specialization']); ?></td>
<td data-label="Date"><?php echo htmlspecialchars($row['appointment_date']); ?></td>
<td data-label="Time Slot"><?php echo htmlspecialchars($row['time_slot']); ?></td>
<td data-label="Status"><?php echo ucfirst(htmlspecialchars($row['status'])); ?></td>
<td data-label="Action">
    <a href="appointment_letter.php?id=<?php echo $row['id']; ?>" target="_blank">View Letter</a>
</td>

            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" style="text-align:center;">No appointments found.</td>
            </tr>
        <?php endif; ?>
    </table>
</body>
</html>
