<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}
require_once '../../../config/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $gender = $_POST['gender'];
    $specialization = $_POST['specialization'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = "doctor";

    $stmt = $conn->prepare("INSERT INTO users (name, email, password, phone, gender, role, specialization) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $name, $email, $password, $phone, $gender, $role, $specialization);

    if ($stmt->execute()) {
        $_SESSION['msg'] = "✅ Doctor added successfully!";
        header("Location: manage_doctors.php");
        exit();
    } else {
        $error = "❌ Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Doctor</title>
    <link rel="shortcut icon" href="../../../public/assets/images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.css">
    <style>
@import url(https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;600;700&display=swap);
/* light mood */
:root{
    --Primary: #0704a8ff;
    --NeutralBackground: #b7e8faff;
    --NeutralCardAndTableBackground: #aed4e1ff;
    --NeutralCardAndTableBackgroundHover: #98c7d7ff;
    --Text: #00010aff;
    --Highlight: #dc7d09ff;
    --Accent: #01a809ff;
    --Edit: #010567ff;
    --EditHover: #02066fff;
    --Delete: #850e01ff;
    --DeleteHover: #641007ff;
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
  background: var(--NeutralBackground);
  margin: 0;
  padding: 0;
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
}

.add_doctor_main_container {
  width: 100%;
  max-width: 1000px;
  padding: 20px;
}

.add_doctor_container {
  background: var(--NeutralCardAndTableBackground);
  padding: 30px;
  border-radius: 12px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

h1 {
  text-align: center;
  margin-bottom: 20px;
  color: var(--Edit);
}

label {
  font-weight: bold;
  display: block;
  color: var(--Edit);
}

input, select {
    font-family:'Rajdhani', sans-serif;
  width: 100%;
  padding: 12px;
  border-radius: 30px;
  border: 1px solid var(--Accent);
  font-size: 15px;
  margin-bottom: 18px;
  transition: border-color 0.3s;
  box-sizing: border-box;
  background-color: var(--NeutralCardAndTableBackgroundHover);
}

input:focus, select:focus {
  border-color: var(--Highlight);
  outline: none;
}

button {
  width: 100%;
  padding: 7px;
  background: var(--Edit);
  color: #fff;
  font-size: 25px;
  font-weight: bold;
  border: none;
  border-radius: 30px;
  cursor: pointer;
  transition: background 0.3s ease;
}

button:hover {
  background: var(--EditHover);
}

.add_doctor_container p {
  text-align: center;
  margin-bottom: 15px;
}

.add_doctor_container a {
  display: inline-block;
  margin-top: 20px;
  text-decoration: none;
  font-weight: bold;
  color: var(--Edit);
  transition: color 0.3s;
}

.add_doctor_container a:hover {
  color: var(--EditHover);
}

/* Responsive */
@media (max-width: 768px) {
  .add_doctor_container {
    padding: 20px;
  }

  input, select {
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
::-webkit-scrollbar-track {background: var(--Edit);}
::-webkit-scrollbar-thumb {background: var(--Edit);border-radius: 5px;  }
::-webkit-scrollbar-thumb:hover {background: var(--EditHover); }
* {scrollbar-width: thin;scrollbar-color: var(--Edit) #f1f1f1;}
    </style>
</head>
<body>
<main class="add_doctor_main_container">
<div class="add_doctor_container">
    <h1>Add New Doctor</h1>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST">
        <label>Name:</label><br>
        <input type="text" name="name" required><br><br>
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
        <label>Specialization:</label><br>
        <select name="specialization" required>
        <?php
            $specializations = [
                "Internal Medicine Specialist",
                "General Physician",
                "Infectious Disease Specialist",
                "Endocrinologist",
                "Rheumatologist",
                "Cardiologist",
                "Cardiothoracic Surgeon",
                "Pulmonologist",
                "Allergist / Immunologist",
                "Neurologist",
                "Neurosurgeon",
                "Psychiatrist",
                "Pediatrician",
                "Neonatologist",
                "Gynecologist & Obstetrician",
                "Fertility Specialist",
                "Dermatologist",
                "Venereologist",
                "Sexologist",
                "Ophthalmologist",
                "ENT Specialist (Otolaryngologist)",
                "Dentist",
                "Oral & Maxillofacial Surgeon",
                "Orthopedic Surgeon",
                "Physiotherapist",
                "Spine Surgeon",
                "Gastroenterologist",
                "Hepatologist",
                "Biliary Specialist",
                "Nephrologist",
                "Urologist",
                "Oncologist",
                "Surgical Oncologist",
                "Radiation Oncologist",
                "General Surgeon",
                "Plastic Surgeon",
                "Cosmetic Surgeon",
                "Laparoscopic Surgeon",
                "Pediatric Surgeon",
                "Pain Management Specialist",
                "Emergency Medicine Specialist",
                "Sleep Medicine Specialist",
                "Family Medicine Specialist",
                "Pathologist",
                "Radiologist",
                "Anesthesiologist",
                "Geneticist",
                "Laboratory Medicine Specialist"
            ];
            foreach ($specializations as $spec) {
                $selected = ($doctor['specialization'] == $spec) ? 'selected' : '';
                echo "<option value=\"$spec\" $selected>$spec</option>";
            }
            ?>
        </select><br><br>
        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>
        <button type="submit">Save</button>
    </form>
    <a href="manage_doctors.php">⬅ Back to Dashboard</a>
</div>
</main>
<script src="../../../public/assets/js.main.js"></script>
</body>
</html>
