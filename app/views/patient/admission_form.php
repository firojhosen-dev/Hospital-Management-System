<?php
session_start();
require_once '../../../config/config.php';

// ✅ Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admission_id = generateAdmissionID($conn);
    $patient_name = $_POST['patient_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $disease = $_POST['disease'];
    $message = $_POST['message'];

    $stmt = $conn->prepare("INSERT INTO patient_admissions (admission_id, patient_name, email, phone, gender, age, disease, message) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        $error = "Prepare failed: " . $conn->error;
    } else {
        $stmt->bind_param("sssssiss", $admission_id, $patient_name, $email, $phone, $gender, $age, $disease, $message);
        if ($stmt->execute()) {
            $success = "Admission submitted successfully! Your Admission ID: $admission_id";
        } else {
            $error = "Execute failed: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Patient Admission Form</title>
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

.admission_form_main_container {
  width: 100%;
  max-width: 1000px;
  padding: 20px;
}

.admission_form_container {
  background: #fff;
  padding: 30px;
  border-radius: 12px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

h2 {
  text-align: center;
  margin-bottom: 20px;
  color: #333;
}

label {
  font-weight: bold;
  display: block;
  margin-bottom: 6px;
  color: #444;
}

input, select, textarea {
  width: 100%;
  padding: 12px;
  border-radius: 8px;
  border: 1px solid #ccc;
  font-size: 15px;
  margin-bottom: 18px;
  transition: border-color 0.3s;
  box-sizing: border-box;
  background-color: #fff;
}

input:focus, select:focus, textarea:focus {
  border-color: #007BFF;
  outline: none;
}

button {
  width: 100%;
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

.admission_form_container p {
  text-align: center;
  margin-bottom: 15px;
}

.admission_form_container a {
  display: inline-block;
  margin-top: 20px;
  text-decoration: none;
  font-weight: bold;
  color: #007BFF;
  transition: color 0.3s;
}

.admission_form_container a:hover {
  color: #0056b3;
}

/* Responsive */
@media (max-width: 768px) {
  .admission_form_container {
    padding: 20px;
  }

  input, select, textarea {
    font-size: 14px;
    padding: 10px;
  }

  button {
    font-size: 14px;
    padding: 10px;
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
<div class="admission_form_main_container">
    <div class="admission_form_container">
<h2>Hospital Admission Form</h2>

<?php if(isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
<?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

<form method="POST">
    <label>Name:</label><br>
    <input type="text" name="patient_name" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Phone:</label><br>
    <input type="text" name="phone" required><br><br>

    <label>Gender:</label><br>
    <select name="gender" required>
        <option value="male">Male</option>
        <option value="female">Female</option>
        <option value="other">Other</option>
    </select><br><br>

    <label>Age:</label><br>
    <input type="number" name="age" required><br><br>

    <label>Disease / Reason for Admission:</label><br>
    <input type="text" name="disease" required><br><br>

    <label>Message:</label><br>
    <textarea name="message"></textarea><br><br>

    <button type="submit">Submit Admission</button>
</form>
</div>
</div>
</body>
</html> 
