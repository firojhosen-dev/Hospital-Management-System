<?php
if(isset($_GET['mark_read']) && isset($_GET['id'])){
    $admission_id = $_GET['id'];
    $conn->query("UPDATE patient_admissions SET is_read = 1 WHERE admission_id = '$admission_id'");
}

require_once '../../../config/config.php';

// ✅ Unread Notifications Fetch
$notifications = $conn->query("SELECT admission_id, patient_name, phone FROM patient_admissions WHERE is_read = 0 ORDER BY created_at DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Admission|HMS</title>
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
/* Custom Scrollbar */
::-webkit-scrollbar {width: 10px;}
::-webkit-scrollbar-track {background: #f1f1f1;}
::-webkit-scrollbar-thumb {background: #0077cc;border-radius: 5px;  }
::-webkit-scrollbar-thumb:hover {background: #005fa3; }
* {scrollbar-width: thin;scrollbar-color: #0077cc #f1f1f1;}
</style>
</head>
<body>
    

<div style="background:#f8f8f8; padding:10px; border:1px solid #ccc; margin-bottom:20px;">
    <h3>🔔 New Admission Notifications</h3>
    <?php if($notifications->num_rows > 0): ?>
        <ul>
            <?php while($n = $notifications->fetch_assoc()): ?>
                <li>
                    <b>Name:</b> <?php echo $n['patient_name']; ?>
                    <button onclick="copyToClipboard('<?php echo $n['patient_name']; ?>')">📋 Copy</button>
                    <br>

                    <b>Phone:</b> <?php echo $n['phone']; ?>
                    <button onclick="copyToClipboard('<?php echo $n['phone']; ?>')">📋 Copy</button>
                    <br>

                    <b>ID:</b> <?php echo $n['admission_id']; ?>
                    <button onclick="copyToClipboard('<?php echo $n['admission_id']; ?>')">📋 Copy</button>
                    <br>
                    <hr>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>No new notifications</p>
    <?php endif; ?>
</div>
<script>
// ✅ Copy Function
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert("✅ Copied: " + text);
    }).catch(err => {
        alert("❌ Failed to copy!");
    });
}
</script>
</body>
</html>