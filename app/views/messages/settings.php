<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}
require_once "../../../config/config.php";

$user_id = $_SESSION['user_id'];

// ✅ বর্তমান ইউজার ডাটা আনুন
$stmt = $conn->prepare("SELECT name, profile_picture FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$message = "";

// ✅ ফর্ম সাবমিট হলে আপডেট
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $profile_picture = $user['profile_picture'];

    // ফাইল আপলোড চেক
    if (!empty($_FILES['profile_picture']['name'])) {
        $targetDir = "../../../public/assets/uploads/";
        $fileName = time() . "_" . basename($_FILES['profile_picture']['name']);
        $targetFile = $targetDir . $fileName;

        // ইমেজ চেক
        $check = getimagesize($_FILES['profile_picture']['tmp_name']);
        if ($check !== false) {
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFile)) {
                $profile_picture = $fileName;
            } else {
                $message = "❌ File upload failed.";
            }
        } else {
            $message = "❌ Invalid image file.";
        }
    }

    // ✅ Update DB
    $stmt = $conn->prepare("UPDATE users SET name = ?, profile_picture = ? WHERE id = ?");
    $stmt->bind_param("ssi", $name, $profile_picture, $user_id);
    if ($stmt->execute()) {
        $_SESSION['profile_picture'] = $profile_picture;
        $_SESSION['user_name'] = $name;
        $message = "✅ Profile updated successfully!";
    } else {
        $message = "❌ Update failed.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Settings</title>
  <link rel="shortcut icon" href="../../../public/assets/images/logo.png" type="image/x-icon">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.css">
  <style>
@import url(https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;600;700&display=swap);

    body {
      font-family: 'Rajdhani', sans-serif;
      background: #f9fafb;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .settings-box {
      width: 400px;
      background: #fff;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    h2 {margin-bottom: 20px;}
    .profile-pic {
      text-align: center;
      margin-bottom: 20px;
    }
    .profile-pic img {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      border: 3px solid #4f46e5;
      object-fit: cover;
    }
    label {display:block; margin: 10px 0 5px;}
    input[type="text"], input[type="file"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 8px;
    }
    button {
      margin-top: 15px;
      width: 100%;
      padding: 12px;
      background: #4f46e5;
      border: none;
      color: white;
      font-size: 16px;
      border-radius: 8px;
      cursor: pointer;
    }
    .msg {
      margin-bottom: 15px;
      padding: 10px;
      text-align: center;
      border-radius: 8px;
    }
    .success {background: #d1fae5; color: #065f46;}
    .error {background: #fee2e2; color: #991b1b;}
  </style>
</head>
<body>
  <div class="settings-box">
    <h2>Profile Settings</h2>
    <?php if ($message): ?>
      <div class="msg <?php echo (strpos($message, '✅') !== false) ? 'success' : 'error'; ?>">
        <?php echo $message; ?>
      </div>
    <?php endif; ?>
    <div class="profile-pic">
      <img src="<?php echo '../../../public/assets/uploads/'.($user['profile_picture'] ?: 'default.jpg'); ?>" alt="Profile Picture">
    </div>
    <form method="post" enctype="multipart/form-data">
      <label>Name</label>
      <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>

      <label>Profile Picture</label>
      <input type="file" name="profile_picture" accept="image/*">

      <button type="submit">Save Changes</button>
    </form>
  </div>
</body>
</html>
