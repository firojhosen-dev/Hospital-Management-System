<?php
require_once "../../../config/config.php";


$id = $_GET['id'] ?? 0;

// Fetch department data
$stmt = $conn->prepare("SELECT * FROM departments WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$dept = $res->fetch_assoc();

if (!$dept) {
    echo "Department not found!";
    exit;
}

// Handle Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("UPDATE departments SET name=?, description=? WHERE id=?");
    $stmt->bind_param("ssi", $name, $description, $id);
    $stmt->execute();

    echo "<script>alert('Department Updated Successfully'); window.location='department.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Department</title>
    <!-- <link rel="stylesheet" href="../../../public/assets/css/admin.css"> -->
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

.edit_department_main_container {
  width: 100%;
  max-width: 1000px;
  padding: 20px;
}

.edit_department_container {
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

input, select, textarea {
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
textarea{
    height: 100px;
  border-radius: 10px;
}
input:focus, select:focus, textarea:focus {
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

.edit_department_container p {
  text-align: center;
  margin-bottom: 15px;
}

.edit_department_container a {
  display: inline-block;
  margin-top: 20px;
  text-decoration: none;
  font-weight: bold;
  color: var(--Edit);
  transition: color 0.3s;
}

.edit_department_container a:hover {
  color: var(--EditHover);
}

/* Responsive */
@media (max-width: 768px) {
  .edit_department_container {
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

<div class="edit_department_main_container">
<div class="edit_department_container">
  <h2>Edit Department</h2>
  <form method="post">
      <label>Department Name:</label>
      <input type="text" name="name" value="<?= htmlspecialchars($dept['name']) ?>" required>

      <label>Description:</label>
      <textarea name="description" required><?= htmlspecialchars($dept['description']) ?></textarea>

      <label>Phone:</label>
        <input type="text" name="contact_number" value="<?= htmlspecialchars($dept['contact_number']) ?>" >

        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($dept['email']) ?>" >

        <label>Location:</label>
        <input type="text" name="location" value="<?= htmlspecialchars($dept['location']) ?>">

      <button type="submit">Update Department</button>
  </form>
  <a href="department.php" class="back-link">← Back to Department List</a>
</div>

</body>
</html>