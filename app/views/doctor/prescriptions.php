<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'doctor') {
    header("Location: ../auth/login.php");
    exit();
}
require_once '../../../config/config.php';

$doctor_id = $_SESSION['user_id'];

// ✅ Approved Appointments List
$result = $conn->query("SELECT a.id AS appointment_id, a.appointment_date, a.time_slot,
                               u.name AS patient_name, u.id AS patient_id
                        FROM appointments a
                        JOIN users u ON a.patient_id = u.id
                        WHERE a.doctor_id = $doctor_id AND a.status = 'approved'
                        ORDER BY a.appointment_date DESC");

// ✅ Insert Prescription
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_prescription'])) {
    $appointment_id = $_POST['appointment_id'];
    $patient_id = $_POST['patient_id'];
    $prescription = $_POST['prescription'];

    $stmt = $conn->prepare("INSERT INTO prescriptions (appointment_id, doctor_id, patient_id, prescription) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $appointment_id, $doctor_id, $patient_id, $prescription);

    if ($stmt->execute()) {
        echo "<p style='color:green;'>✅ Prescription saved successfully!</p>";
    } else {
        echo "<p style='color:red;'>❌ Error: " . $stmt->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Prescriptions</title>
    <!-- <link rel="stylesheet" href="../../../public/assets/css/mains.css"> -->
    <link rel="shortcut icon" href="../../../public/assets/images/logo.png" type="image/x-icon">
<style>
@import url(https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;600;700&display=swap);
body{
      font-family: 'Rajdhani', sans-serif;

}
/* new */
h2 {
  text-align: center;
  margin-bottom: 20px;
  color: #2c3e50;
  font-size: 28px;
  font-weight: 600;
}

/* ✅ Table Styling */
table {
  width: 100%;
  border-collapse: collapse;
  background: #fff;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}
table th, table td {
  padding: 12px 15px;
  text-align: center;
}
table th {
  background: #3498db;
  color: white;
  font-weight: 600;
  text-transform: uppercase;
  font-size: 13px;
  letter-spacing: 0.5px;
}
table tr:nth-child(even) {
  background: #f9f9f9;
}
table tr:hover {
  background: #ecf6fd;
  transition: 0.3s;
}

/* ✅ Status Styling */
td.status {
  font-weight: bold;
}
td.status.paid {
  color: #27ae60;
}
td.status.unpaid {
  color: #e74c3c;
}

/* ✅ Action Buttons */
td a {
  display: inline-block;
  padding: 6px 10px;
  border-radius: 6px;
  font-size: 13px;
  margin: 2px;
  text-decoration: none;
  transition: background 0.3s;
}
td a.paid {
  background: #27ae60;
  color: #fff;
}
td a.paid:hover {
  background: #1e8449;
}
td a.unpaid {
  background: #e74c3c;
  color: #fff;
}
td a.unpaid:hover {
  background: #c0392b;
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

</style>
</head>
<body>
    <h2>💊 Add Prescriptions</h2>

    <table border="1" cellpadding="10">
        <tr>
            <th>Patient</th>
            <th>Date</th>
            <th>Time Slot</th>
            <th>Prescription</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['patient_name']; ?></td>
            <td><?php echo $row['appointment_date']; ?></td>
            <td><?php echo $row['time_slot']; ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="appointment_id" value="<?php echo $row['appointment_id']; ?>">
                    <input type="hidden" name="patient_id" value="<?php echo $row['patient_id']; ?>">
                    <textarea name="prescription" rows="3" cols="30" required></textarea><br>
                    <button type="submit" name="save_prescription">✅ Save</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <a href="dashboard.php">⬅ Back to Dashboard</a>
</body>
</html>



