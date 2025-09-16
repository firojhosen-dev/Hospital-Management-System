<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

require_once '../../../config/config.php';

$search_data = null;
$error = "";

// ✅ Search by Admission ID or Doctor ID
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search_type = $_POST['search_type']; // patient / doctor
    $search_id = trim($_POST['search_id']);

    if ($search_type === "patient") {
        $stmt = $conn->prepare("SELECT * FROM patient_admissions WHERE admission_id = ?");
    } else {
        $stmt = $conn->prepare("SELECT * FROM doctors WHERE doctor_id = ?");
    }

    $stmt->bind_param("s", $search_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $search_data = $result->fetch_assoc();
    } else {
        $error = "❌ No " . ucfirst($search_type) . " found with ID: $search_id";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Patient / Doctor</title>
    <link rel="shortcut icon" href="../../../public/assets/images/logo.png" type="image/x-icon">
    <!-- <link rel="stylesheet" href="../../../public/assets/css/style.css"> -->
<style>
@import url(https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;600;700&display=swap);
/* light mood */
:root{
    --Primary: #0704a8ff;
    --NeutralBackground: #b7e8faff;
    --Text: #00010aff;
    --Highlight: #dc7d09ff;
    --Accent: #01a809ff;
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
body {
        font-family:'Rajdhani', sans-serif;
    margin: 50px;
    background: #f5f5f5;
}
    form {
    background: white;
    padding: 20px;
    width: 300px;
    border-radius: 8px;
    box-shadow: 0px 0px 5px rgba(0,0,0,0.1);
}

input, select, button {
    width: 100%;
    padding: 8px;
    margin-bottom: 30px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

button {
    background: #007BFF;
    color: white;
    border: none;
    cursor: pointer;
}

button:hover {
    background: #0056b3;
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
    <h2>🔍 Search Patient / Doctor</h2>

    <form method="POST">
        <label>Search For:</label><br>
        <select name="search_type" required>
            <option value="patient" <?php if(isset($_POST['search_type']) && $_POST['search_type']=="patient") echo "selected"; ?>>Patient</option>
            <option value="doctor" <?php if(isset($_POST['search_type']) && $_POST['search_type']=="doctor") echo "selected"; ?>>Doctor</option>
        </select><br><br>

        <label>Enter ID:</label><br>
        <input type="text" name="search_id" maxlength="10" required>
        <button type="submit">Search</button>
    </form>

    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <?php if ($search_data): ?>
        <hr>
        <h3>✅ <?php echo ucfirst($_POST['search_type']); ?> Details</h3>
        <table border="1" cellpadding="6">
            <?php if ($_POST['search_type'] === "patient"): ?>
                <tr><th>Admission ID</th><td><?php echo $search_data['admission_id']; ?></td></tr>
                <tr><th>Name</th><td><?php echo $search_data['patient_name']; ?></td></tr>
                <tr><th>Email</th><td><?php echo $search_data['email']; ?></td></tr>
                <tr><th>Phone</th><td><?php echo $search_data['phone']; ?></td></tr>
                <tr><th>Gender</th><td><?php echo ucfirst($search_data['gender']); ?></td></tr>
                <tr><th>Age</th><td><?php echo $search_data['age']; ?></td></tr>
                <tr><th>Disease</th><td><?php echo $search_data['disease']; ?></td></tr>
                <tr><th>Message</th><td><?php echo nl2br($search_data['message']); ?></td></tr>
                <tr><th>Status</th><td><b style="color:blue;"><?php echo ucfirst($search_data['status']); ?></b></td></tr>
                <tr><th>Submitted At</th><td><?php echo $search_data['created_at']; ?></td></tr>
            <?php else: ?>
                <tr><th>Doctor ID</th><td><?php echo $search_data['doctor_id']; ?></td></tr>
                <tr><th>Name</th><td><?php echo $search_data['name']; ?></td></tr>
                <tr><th>Email</th><td><?php echo $search_data['email']; ?></td></tr>
                <tr><th>Phone</th><td><?php echo $search_data['phone']; ?></td></tr>
                <tr><th>Specialization</th><td><?php echo $search_data['specialization']; ?></td></tr>
                <tr><th>Gender</th><td><?php echo ucfirst($search_data['gender']); ?></td></tr>
                <tr><th>Joined At</th><td><?php echo $search_data['created_at']; ?></td></tr>
            <?php endif; ?>
        </table>
    <?php endif; ?>

    <br>
    <a href="dashboard.php">⬅ Back to Dashboard</a>
</body>
</html>
