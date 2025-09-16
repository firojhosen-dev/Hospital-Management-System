<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'doctor') {
    header("Location: ../auth/login.php");
    exit();
}
require_once '../../../config/config.php';

$doctor_id = $_SESSION['user_id'];

// ✅ Upload Report
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $patient_id = $_POST['patient_id'];
    $file_name = time() . "_" . basename($_FILES['report_file']['name']);
    $target = "../../../public/assets/uploads/" . $file_name;

    if (move_uploaded_file($_FILES['report_file']['tmp_name'], $target)) {
        $stmt = $conn->prepare("INSERT INTO reports (patient_id, doctor_id, report_file) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $patient_id, $doctor_id, $file_name);
        $stmt->execute();
        $msg = "Report uploaded successfully!";
    } else {
        $msg = "Failed to upload report!";
    }
}

// ✅ Fetch Patients (for dropdown)
$patients = $conn->query("SELECT DISTINCT u.id, u.name 
                          FROM appointments a
                          JOIN users u ON a.patient_id = u.id
                          WHERE a.doctor_id = $doctor_id AND a.status = 'approved'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Patient Reports</title>
    <!-- <link rel="stylesheet" href="../../../public/assets/css/mains.css"> -->
    <link rel="shortcut icon" href="../../../public/assets/images/logo.png" type="image/x-icon">
  <style>
@import url(https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;600;700&display=swap);

    body {
      font-family: 'Rajdhani', sans-serif;
      background: #f5f7fa;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }

    .container {
      background: #fff;
      max-width: 900px;
      width: 100%;
      margin: 20px;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    h2 {
      text-align: center;
      color: #333;
      margin-bottom: 20px;
    }

    form {
      display: flex;
      flex-direction: column;
      gap: 20px;
    }

    label {
      font-weight: bold;
      margin-bottom: 5px;
      color: #444;
    }

    select, input[type="file"] {
      padding: 10px;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 16px;
      width: 100%;
      transition: border-color 0.3s;
    }

    select:focus, input[type="file"]:focus {
      border-color: #007BFF;
      outline: none;
    }

    button {
      padding: 12px;
      background: #007BFF;
      color: #fff;
      font-size: 16px;
      font-weight: bold;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    button:hover {
      background: #0056b3;
    }

    .back-link {
      display: inline-block;
      margin-top: 20px;
      text-decoration: none;
      color: #007BFF;
      font-weight: bold;
      transition: color 0.3s;
    }

    .back-link:hover {
      color: #0056b3;
    }

    /* Responsive */
    @media (max-width: 600px) {
      .container {
        padding: 20px;
      }

      button {
        font-size: 14px;
        padding: 10px;
      }
    }
    </style>
</head>
<body>
  <div class="container">
    <h2>Upload Patient Reports</h2>
    <?php if (!empty($msg)) echo "<p style='color:green;'>$msg</p>"; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Select Patient:</label>
        <select name="patient_id" required>
            <option value="">--Select Patient--</option>
            <?php while ($p = $patients->fetch_assoc()): ?>
                <option value="<?php echo $p['id']; ?>"><?php echo $p['name']; ?></option>
            <?php endwhile; ?>
        </select><br><br>

        <label>Upload Report (PDF/Image):</label>
        <input type="file" name="report_file" required><br><br>

        <button type="submit">Upload Report</button>
    </form>

    <a href="dashboard.php">⬅ Back to Dashboard</a>
  </div>
</body>
</html>
