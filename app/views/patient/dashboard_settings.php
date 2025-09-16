<?php
require_once "../../../config/config.php";
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// ইউজারের তথ্য আনতে
$stmt = $conn->prepare("SELECT name, email, profile_picture FROM users WHERE id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// আপডেট ফর্ম সাবমিট হলে
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name  = $_POST['name'];
    $email = $_POST['email'];

    // প্রোফাইল ছবি আপলোড
    $profile_picture = $user['profile_picture'];
    if (!empty($_FILES['profile_picture']['name'])) {
        $targetDir = "../../../public/uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $fileName = time() . "_" . basename($_FILES['profile_picture']['name']);
        $targetFilePath = $targetDir . $fileName;

        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFilePath)) {
            $profile_picture = "uploads/" . $fileName;
        }
    }

    // যদি পাসওয়ার্ড দেয়া হয়
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $stmt = $conn->prepare("UPDATE users SET name=?, email=?, profile_picture=?, password=? WHERE id=?");
        $stmt->bind_param("ssssi", $name, $email, $profile_picture, $password, $user_id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET name=?, email=?, profile_picture=? WHERE id=?");
        $stmt->bind_param("sssi", $name, $email, $profile_picture, $user_id);
    }

    if ($stmt->execute()) {
        $_SESSION['user_name'] = $name;
        $message = "✅ Profile updated successfully!";
    } else {
        $message = "❌ Update failed. Try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>Settings | Hospital Management System</title>
    <style>
@import url(https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;600;700&display=swap);
body{
    font-family: 'Rajdhani', sans-serif;

}
        .settings-container {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .settings-container h2 {
            margin-bottom: 20px;
            text-align: center;
        }
        .settings-container label {
            display: block;
            font-weight: bold;
            margin: 10px 0 5px;
        }
        .settings-container input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        .settings-container button {
            background: #3498db;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            cursor: pointer;
        }
        .settings-container img {
            display:block;
            margin: 10px auto;
            border-radius: 50%;
            width: 100px;
            height: 100px;
            object-fit: cover;
        }
        .message {
            text-align:center;
            margin-bottom:15px;
            color: green;
            font-weight:bold;
        }
    </style>
</head>
<body>
    <div class="settings-container">
        <h2>⚙️ Account Settings</h2>
        <?php if($message) echo "<p class='message'>$message</p>"; ?>
        <form method="post" enctype="multipart/form-data">
            <label>Full Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>

            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

            <label>Profile Picture</label>
            <input type="file" name="profile_picture" accept="image/*">
            <?php if($user['profile_picture']): ?>
                <img src="../../../public/<?= $user['profile_picture'] ?>" alt="Profile Picture">
            <?php endif; ?>

            <label>New Password (optional)</label>
            <input type="password" name="password" placeholder="Enter new password">

            <button type="submit">Save Changes</button>
        </form>
    </div>
</body>
</html>
