<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'patient') {
    header("Location: ../auth/login.php");
    exit();
}
require_once '../../../config/config.php';

$patient_id = $_SESSION['user_id'];

// ✅ Fetch Bills
$result = $conn->query("SELECT b.*, u.name AS doctor_name
                        FROM billing b
                        JOIN users u ON b.doctor_id = u.id
                        WHERE b.patient_id = $patient_id
                        ORDER BY b.created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Bills</title>
    <link rel="shortcut icon" href="../../../public/assets/images/logo.png" type="image/x-icon">
<style>
@import url(https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;600;700&display=swap);

/* === Global Reset === */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
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

/* Custom Scrollbar */
::-webkit-scrollbar {width: 10px;}
::-webkit-scrollbar-track {background: #f1f1f1;}
::-webkit-scrollbar-thumb {background: #0077cc;border-radius: 5px;  }
::-webkit-scrollbar-thumb:hover {background: #005fa3; }
* {scrollbar-width: thin;scrollbar-color: #0077cc #f1f1f1;}
</style>
</head>
<body>
<main class="patient_billing_main_container">
    <div class="patient_billing_container">
    <h2>My Billing History</h2>

    <table border="1" cellpadding="10">
        <tr>
            <th>Doctor</th>
            <th>Total</th>
            <th>Status</th>
            <th>Date</th>
            <th>Action</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['doctor_name']; ?></td>
            <td><?php echo $row['total_amount']; ?> TK</td>
            <td><?php echo ucfirst($row['status']); ?></td>
            <td><?php echo $row['created_at']; ?></td>
            <td>
                <a href="invoice_pdf.php?id=<?php echo $row['id']; ?>" target="_blank">📄 Download Invoice</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <a href="dashboard.php">⬅ Back to Dashboard</a>
    </div>
</main>
</body>
</html>
