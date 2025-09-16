<?php
session_start();
require_once '../../../config/config.php';

// Form submission
if(isset($_POST['submit'])){
    $type = $_POST['type'];
    $patient_name = $_POST['patient_name'];
    $created_at = date('Y-m-d');

    // Prepare statement
    $stmt = $conn->prepare("INSERT INTO cases (type, patient_name, created_at) VALUES (?, ?, ?)");
    if(!$stmt){
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("sss", $type, $patient_name, $created_at);

    if($stmt->execute()){
        $msg = "Case added successfully!";
    } else {
        $msg = "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Case</title>
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
body{background: var(--NeutralBackground);margin:0;padding:20px;}
form{max-width:500px;margin:auto;background: var(--NeutralCardAndTableBackground);padding:20px;border-radius:10px;box-shadow:0 4px 12px rgba(0,0,0,0.1);}
input, select, button{width:100%;padding:10px;margin:10px 0;border-radius:30px;background: var(--NeutralCardAndTableBackgroundHover);border:1px solid var(--Accent);}
input:focus, select:focus{
    border-color: var(--Highlight);
  outline: none;
}input{width:95%;}
button{background: var(--Edit);color:#fff;border:none;cursor:pointer;}
button:hover{background: var(--EditHover);}
.msg{color:green;text-align:center;}
</style>
</head>
<body>
<h2 style="text-align:center;">Add New Case</h2>
<?php if(isset($msg)) echo "<p class='msg'>{$msg}</p>"; ?>
<form method="post">
    <label>Case Type</label>
    <select name="type" required>
        <option value="">Select Type</option>
        <option value="Birth">Birth</option>
        <option value="Accident">Accident</option>
        <option value="Death">Death</option>
    </select>
    <label>Patient Name</label>
    <input type="text" name="patient_name" placeholder="Enter patient name" required>
    <button type="submit" name="submit">Add Case</button>
</form>
</body>
</html>