<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'doctor') {
    header("Location: ../auth/login.php");
    exit();
}
require_once '../../../config/config.php';

// ✅ Message Show (Approve/Reject এর পর)
if (isset($_SESSION['msg'])) {
    echo "<p style='color:green;'>" . $_SESSION['msg'] . "</p>";
    unset($_SESSION['msg']);
}

$doctor_id = $_SESSION['user_id'];
$result = $conn->query("SELECT a.*, u.name AS patient_name, u.phone AS patient_phone
                        FROM appointments a
                        JOIN users u ON a.patient_id = u.id
                        WHERE a.doctor_id = $doctor_id
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
        
        body {
      font-family: 'Rajdhani', sans-serif;

    background: #f5f7fa;
    padding: 20px;
}

h2 {
    text-align: center;
    margin-bottom: 20px;
}

.appointments-container {
    display: grid;
    gap: 15px;
    max-width: 300px;
    margin: auto;
}

.appointment-card {
    background: #788588ff;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0px 3px 8px rgba(0,0,0,0.1);
}

.appointment-card p {
    margin: 5px 0;
    font-size: 14px;
}

strong {
    color: #333;
}

form {
    margin-top: 8px;
}

.approve-btn {
    background: #28a745;
    color: white;
    border: none;
    padding: 6px 10px;
    border-radius: 5px;
    cursor: pointer;
}

.reject-btn {
    background: #dc3545;
    color: white;
    border: none;
    padding: 6px 10px;
    border-radius: 5px;
    cursor: pointer;
    margin-left: 5px;
}

.approve-btn:hover {
    background: #218838;
}

.reject-btn:hover {
    background: #c82333;
}

.back-btn {
    display: inline-block;
    margin-top: 20px;
    background: #6c757d;
    color: white;
    padding: 8px 12px;
    border-radius: 5px;
    text-decoration: none;
}

.back-btn:hover {
    background: #5a6268;
}

    </style>
    <!-- <link rel="stylesheet" href="../../../public/assets/css/appointments.css"> -->
</head>
<body>
    <h2>My Appointments</h2>

    <div class="appointments-container">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="appointment-card">
                <p><strong>Patient:</strong> <?php echo $row['patient_name']; ?></p>
                <p><strong>Contact:</strong> <?php echo $row['patient_phone']; ?></p>
                <p><strong>Date:</strong> <?php echo $row['appointment_date']; ?></p>
                <p><strong>Time Slot:</strong> <?php echo $row['time_slot']; ?></p>
                <p><strong>Status:</strong> <?php echo ucfirst($row['status']); ?></p>
                <p><strong>Action:</strong></p>
                <?php if ($row['status'] == 'pending'): ?>
                    <form method="POST" action="../../../app/controllers/AppointmentController.php">
                        <input type="hidden" name="appointment_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="action" value="approved" class="approve-btn">✅ Approve</button>
                        <button type="submit" name="action" value="rejected" class="reject-btn">❌ Reject</button>
                    </form>
                <?php else: ?>
                    <strong><?php echo ucfirst($row['status']); ?></strong>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>

    <a href="dashboard.php" class="back-btn">⬅ Back to Dashboard</a>
</body>
</html>
