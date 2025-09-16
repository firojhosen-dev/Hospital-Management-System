<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}
require_once "../../../config/config.php";

$user_id = (int)$_SESSION['user_id'];

// 🔹 ইউজারের তথ্য আনো
$stmt = $conn->prepare("SELECT name, profile_picture FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// 🔹 ফর্ম সাবমিট হলে আপডেট করো
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $profile_picture = $user['profile_picture'];

    // ইমেজ আপলোড হ্যান্ডল
    if (!empty($_FILES['profile_picture']['name'])) {
        $target_dir = "../../../public/assets/uploads/";
        $file_name = time() . "_" . basename($_FILES["profile_picture"]["name"]);
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
            $profile_picture = $file_name;
        }
    }

    $update = $conn->prepare("UPDATE users SET name=?, profile_picture=? WHERE id=?");
    $update->bind_param("ssi", $name, $profile_picture, $user_id);

    if ($update->execute()) {
        $_SESSION['success'] = "Profile updated successfully!";
        header("Location: profile.php");
        exit;
    } else {
        $_SESSION['error'] = "Failed to update profile.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
  <link rel="shortcut icon" href="../../../public/assets/images/logo.png" type="image/x-icon">

<title>My Profile</title>
<style>
@import url(https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;600;700&display=swap);

body {
    font-family: 'Rajdhani', sans-serif;
    background: #f3f4f6;
    margin: 0;
    padding: 0;
}
.profile-container {
    max-width: 450px;
    margin: 50px auto;
    background: #fff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0px 4px 10px rgba(0,0,0,0.1);
    text-align: center;
}
.profile-container h2 {
    margin-bottom: 20px;
}
.profile-container img {
    display: block;
    margin: 0 auto 15px;
    width: 120px;
    height: 120px;
    border-radius: 50%;
    border: 3px solid #4f46e5;
    object-fit: cover;
}
.profile-container label {
    display: block;
    margin-top: 10px;
    font-weight: bold;
    text-align: left;
}
.profile-container input {
    width: 100%;
    padding: 10px;
    margin-top: 6px;
    border: 1px solid #ccc;
    border-radius: 8px;
}
.profile-container button {
    margin-top: 15px;
    width: 100%;
    padding: 12px;
    border: none;
    background: #4f46e5;
    color: #fff;
    font-size: 16px;
    border-radius: 8px;
    cursor: pointer;
    transition: 0.3s;
}
.profile-container button:hover {
    background: #4338ca;
}
.alert {
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 6px;
}
.alert.success {
    background: #d1fae5;
    color: #065f46;
}
.alert.error {
    background: #fee2e2;
    color: #991b1b;
}
</style>
</head>
<body>
<div class="profile-container">
    <h2>My Profile</h2>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <img src="<?php echo '../../../public/assets/uploads/' . ($user['profile_picture'] ?: 'default.jpg'); ?>" alt="Profile">

        <label for="name">Name</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>

        <label for="profile_picture">Profile Picture</label>
        <input type="file" name="profile_picture">

        <button type="submit">Update Profile</button>
    </form>
</div>
</body>
</html>
