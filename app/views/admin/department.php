<?php
session_start();
require_once "../../../config/config.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Departments</title>
<link rel="shortcut icon" href="../../../public/assets/images/logo.png" type="image/x-icon">
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
 background: var(--NeutralBackground); margin:0; padding:0; }
.container{ max-width:1000px; margin:50px auto; padding:0 15px; }
h2{text-align:center; margin-bottom:30px; color:var(--Edit);}


/* Department Cards */
.department-cards {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
}
.card-dept {
    background: var(--NeutralCardAndTableBackground);
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    flex: 1 1 calc(50% - 20px); /* Large screens 2 per row */
    box-sizing: border-box;
    transition: transform 0.3s;
}
.card-dept:hover {
    transform: translateY(-5px);
}
.card-dept h4 { margin: 0 0 10px 0; color:#1e293b; }
.card-dept p { margin: 5px 0; color:#334155; }

a {
    background: var(--Edit);
    color: #fff;
    font-weight: 600;
    cursor: pointer;
    width: 100%;
    height:20px;
    padding: 10px 12px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 30px;
    font-size: 1rem;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

a:hover {
    background: var(--EditHover);
    transform: scale(1.02);
}

/* Responsive */
@media (max-width: 992px){
    .card-dept { flex: 1 1 100%; } /* Tablet/Mobile 1 per row */
}
</style>
</head>
<body>
<div class="container">
    <h2>Departments</h2>
    <div class="department-cards">
        <?php
        $res = $conn->query("SELECT * FROM departments ORDER BY id DESC");
        while($row = $res->fetch_assoc()):
        ?>
        <div class="card-dept">
            <h4><strong>Name:</strong> <?= htmlspecialchars($row['name']) ?></h4>
            <p><strong>Description:</strong> <?= htmlspecialchars($row['description']) ?></p>
            <p><strong>Contact:</strong> <?= $row['contact_number'] ?></p>
            <p><strong>Email:</strong> <?= $row['email'] ?></p>
            <p><strong>Location:</strong> <?= $row['location'] ?></p>
            <a href="edit_department.php?id=<?php echo $row['id']; ?>">edit info</a>
            <a href="delete_department.php?id=<?php echo $row['id']; ?>">more info</a>
        </div>
        <?php endwhile; ?>
    </div>
</div>
</body>
</html>