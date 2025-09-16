<?php
session_start();
require_once "../../../config/config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? 0;

    if ($id) {
        $stmt = $conn->prepare("DELETE FROM departments WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(["status"=>1, "msg"=>"🗑 Department Deleted Successfully"]);
        } else {
            echo json_encode(["status"=>0, "msg"=>"❌ Department Not Found"]);
        }
    } else {
        echo json_encode(["status"=>0, "msg"=>"❌ Invalid ID"]);
    }
} else {
    echo json_encode(["status"=>0, "msg"=>"❌ Invalid Request"]);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete</title>
<style>
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
</style>
</head>
<body>
<script>
    // Delete buttons
document.querySelectorAll('.delete-btn').forEach(btn=>{
    btn.addEventListener('click', function(){
        if(!confirm('Are you sure you want to delete this department?')) return;
        const id = this.dataset.id;
        const formData = new FormData();
        formData.append('id', id);
        fetch('delete_department.php', {
            method:'POST',
            body: formData
        }).then(res => res.json())
          .then(data => {
              alert(data.msg);
              if(data.status == 1) location.reload();
          });
    });
});
</script>
</body>
</html>