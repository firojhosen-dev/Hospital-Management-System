<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'doctor') {
    header("Location: ../auth/login.php");
    exit();
}
require_once '../../../config/config.php';

$doctor_id = $_SESSION['user_id'];

// ✅ Add Bill
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_id = $_POST['patient_id'];
    $consultation_fee = $_POST['consultation_fee'];
    $medicine_cost = $_POST['medicine_cost'];
    $bed_charge = $_POST['bed_charge'];
    $total = $consultation_fee + $medicine_cost + $bed_charge;

    $stmt = $conn->prepare("INSERT INTO billing (patient_id, doctor_id, consultation_fee, medicine_cost, bed_charge, total_amount) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iidddd", $patient_id, $doctor_id, $consultation_fee, $medicine_cost, $bed_charge, $total);

    if ($stmt->execute()) {
        $msg = "Bill Generated Successfully!";
    } else {
        $msg = "Error: " . $stmt->error;
    }
}

// ✅ Fetch Patients
$patients = $conn->query("SELECT DISTINCT u.id, u.name 
                          FROM appointments a
                          JOIN users u ON a.patient_id = u.id
                          WHERE a.doctor_id = $doctor_id AND a.status = 'approved'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Generate Bill</title>
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
/* body{
    background-image: url(../images/logo.png);
    background-repeat: no-repeat;
} */

.billing_main_container, 
.add_doctor_main_container {
    background: #f4f6f8;
    color: #333;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 20px;
    min-height: 100vh; 
}
        /* .add_doctor_main_container {
    display: flex;
    justify-content: center;
    align-items: center;  
    background: #f5f5f5;
} */


/* === Container === */
h2 {
    margin-bottom: 15px;
    font-size: 2rem;
    color: #2b3d52;
    text-align: center;
}

h3 {
    margin: 20px 0 10px;
    color: #444;
}

/* === Form Styling === */

form {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    max-width: 1000px;
    width: 100%;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

form:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 16px rgba(0,0,0,0.12);
}

label {
    font-weight: 500;
    margin-bottom: 5px;
    display: block;
}

input, select, button {
    width: 100%;
    padding: 10px 12px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 1rem;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

input:focus, select:focus {
    border-color: #007bff;
    box-shadow: 0 0 6px rgba(0,123,255,0.4);
    outline: none;
}

button {
    background: #007bff;
    color: #fff;
    font-weight: 600;
    cursor: pointer;
    border: none;
    transition: background 0.3s ease, transform 0.2s ease;
}

button:hover {
    background: #0056b3;
    transform: scale(1.02);
}

/* === Table Styling === */
/* table {
    width: 100%;
    max-width: 1000px;
    margin-top: 20px;
    border-collapse: collapse;
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
} */

th, td {
    padding: 12px 15px;
    text-align: center;
}

th {
    background: #007bff;
    color: #fff;
    font-weight: 600;
}

tr {
    transition: background 0.3s ease;
}

tr:nth-child(even) {
    background: #f9f9f9;
}

tr:hover {
    background: #eaf3ff;
}

a {
    text-decoration: none;
    color: #007bff;
    font-weight: 500;
    transition: color 0.3s ease;
}

a:hover {
    color: #0056b3;
}

/* === Message Styling === */
p {
    margin-bottom: 10px;
    font-weight: 500;
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
::-webkit-scrollbar {
  width: 10px;            /* Scrollbar এর width */
}
::-webkit-scrollbar-track {
  background: #f1f1f1;     /* Track এর background */
}
::-webkit-scrollbar-thumb {
  background: #0077cc;     /* Scrollbar এর রঙ */
  border-radius: 5px;      /* Round effect */
}
::-webkit-scrollbar-thumb:hover {
  background: #005fa3;
}

/* Firefox Support */
* {
  scrollbar-width: thin;
  scrollbar-color: #0077cc #f1f1f1;
}

</style>
    <!-- <link rel="stylesheet" href="../../../public/assets/css/mains.css"> -->
</head>
<body>
<main class="billing_main_container">
    <h2>Generate Bill</h2>
    <?php if (!empty($msg)) echo "<p style='color:green;'>$msg</p>"; ?>

    <form method="POST">
        <label>Select Patient:</label>
        <select name="patient_id" required>
            <option value="">--Select Patient--</option>
            <?php while ($p = $patients->fetch_assoc()): ?>
                <option value="<?php echo $p['id']; ?>"><?php echo $p['name']; ?></option>
            <?php endwhile; ?>
        </select><br><br>

        <label>Consultation Fee:</label>
        <input type="number" step="0.01" name="consultation_fee" required><br><br>

        <label>Medicine Cost:</label>
        <input type="number" step="0.01" name="medicine_cost" required><br><br>

        <label>Bed Charge:</label>
        <input type="number" step="0.01" name="bed_charge" required><br><br>

        <button type="submit">Generate Bill</button>
    </form>

    <a href="dashboard.php">⬅ Back to Dashboard</a>
</main>
</body>
</html>
