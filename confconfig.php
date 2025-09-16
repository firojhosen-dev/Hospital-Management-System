<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hospital_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ✅ Generate unique 6-digit Admission ID
function generateAdmissionID($conn) {
    do {
        $admission_id = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $check = $conn->query("SELECT id FROM patient_admissions WHERE admission_id='$admission_id'");
    } while ($check->num_rows > 0);
    return $admission_id;
}
?>
