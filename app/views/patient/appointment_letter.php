<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'patient') {
    header("Location: ../auth/login.php");
    exit();
}
require_once '../../../config/config.php';

if (!isset($_GET['id'])) {
    die("Invalid Request!");
}

$appointment_id = intval($_GET['id']);
$patient_id = $_SESSION['user_id'];

// Appointment ফেচ করা
$stmt = $conn->prepare("SELECT a.*, 
                               d.name AS doctor_name, d.specialization, 
                               p.name AS patient_name, p.phone AS patient_phone 
                        FROM appointments a
                        JOIN users d ON a.doctor_id = d.id
                        JOIN users p ON a.patient_id = p.id
                        WHERE a.id = ? AND a.patient_id = ?");
$stmt->bind_param("ii", $appointment_id, $patient_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("No appointment found!");
}

$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Appointment Letter</title>
    <link rel="shortcut icon" href="../../../public/assets/images/logo.png" type="image/x-icon">
    <style>
       <style>
@import url(https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;600;700&display=swap);
    
    /* Body */
    main {
            font-family: 'Rajdhani', sans-serif;

        background: #f4f7fa;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        padding: 40px 20px;
    }

    /* Letter container */
    .letter {
        background: #fff;
        padding: 30px 40px;
        width: 420px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        border-top: 5px solid #3498db;
    }

    /* Heading */
    h2 {
        text-align: center;
        color: #3498db;
        margin-bottom: 25px;
    }

    /* Paragraphs */
    p {
        font-size: 15px;
        line-height: 1.6;
        margin: 8px 0;
    }

    strong {
        color: #2c3e50;
    }

    /* Print Button */
    .print-btn {
        display: block;
        width: 100%;
        margin-top: 25px;
        padding: 10px;
        background: #3498db;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        transition: background 0.3s;
    }

    .print-btn:hover {
        background: #2c82c9;
    }

    /* Responsive */
    @media (max-width: 500px) {
        .letter {
            width: 100%;
            padding: 20px;
        }
    }
</style>

    </style>
</head>
<body>
<main>
    <div class="letter">
        <h2>Appointment Confirmation</h2>
        <p><strong>Patient Name:</strong> <?php echo $row['patient_name']; ?></p>
        <p><strong>Contact:</strong> <?php echo $row['patient_phone']; ?></p>
        <p><strong>Doctor:</strong> <?php echo $row['doctor_name']; ?> (<?php echo $row['specialization']; ?>)</p>
        <p><strong>Date:</strong> <?php echo $row['appointment_date']; ?></p>
        <p><strong>Time Slot:</strong> <?php echo $row['time_slot']; ?></p>
        <p><strong>Status:</strong> <?php echo ucfirst($row['status']); ?></p>
        <p><strong>Issued On:</strong> <?php echo date("d M Y h:i A", strtotime($row['created_at'])); ?></p>
    </div>

    <button class="print-btn" onclick="window.print()">🖨 Print Letter</button>
</main>
</body>
</html>
