<?php
require_once(__DIR__ . '/../../../app/controllers/AuthController.php');

$message = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $auth = new AuthController();
    $message = $auth->login($_POST['email'], $_POST['password']);

    if ($message == "success") {
        session_start();
        if ($_SESSION['user_role'] == "admin") {
            header("Location: ../admin/dashboard.php");
        } elseif ($_SESSION['user_role'] == "doctor") {
            header("Location: ../doctor/dashboard.php");
        } else {
            header("Location: ../patient/dashboard.php");
        }
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
  <link rel="shortcut icon" href="../../../public/assets/images/logo.png" type="image/x-icon">
  <!--* <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<Web Icon Link>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.css">
<style>
@import url(https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;600;700&display=swap);
:root{
    --Primary: #0704a8ff;
    --NeutralBackground: #b7e8faff;
    --Text: #00010aff;
    --Highlight: #dc7d09ff;
    --Accent: #01a809ff;
}
*{
    font-family: 'Rajdhani', sans-serif;
}
body {
    margin: 0;
    font-family: 'Rajdhani', sans-serif;
    background: var(--NeutralBackground);
}

.auth-container {
    display: flex;
    height: 100vh;
    text-align: center;
}

.auth-left, .auth-right {
    flex: 1;
    padding: 60px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.auth-left {
    background: var(--NeutralBackground);
    max-width: 600px;
}

.auth-left h1 {
    font-size: 36px;
    margin-bottom: -10px;
}

.auth-left p {
    color: var(--Text);
}

form input, select {
    display: block;
    width: 100%;
    padding: 14px;
    margin-bottom: 20px;
    border: 1px solid var(--Accent);
    border-radius: 30px;
    font-size: 16px;
    background: var(--NeutralBackground);
    font-family:'Rajdhani', sans-serif;

    
}

form button {
    width: 100%;
    padding: 14px;
    background: var(--Primary);
    color:var(--NeutralBackground);
    font-size: 16px;
    border-radius: 30px;
    border: none;
    cursor: pointer;
    font-family:'Rajdhani', sans-serif;
}

.forgot-password {
    text-align: right;
    font-size: 14px;
    margin-bottom: 5px;
    display: block;
    color: #888;
    text-decoration: none;
}

.divider {
    text-align: center;
    margin-top: 10px;
    color: #666;
    font-size: 14px;
}

.social-buttons {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-top: 10px;
}

.social-buttons button {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    border: none;
    cursor: pointer;
    background-size: 24px;
    background-repeat: no-repeat;
    background-position: center;
}

.social-buttons a {
    text-decoration: none;
}
.social-buttons a i{
    font-size: 30px;
    text-align: center;
    border: 2px solid var(--Highlight);
    padding: 3px;
    border-radius: 50%;
}
.register-text {
    text-align: center;
    font-size: 14px;
}

.register-text a {
    color: var(--Highlight);
    text-decoration: none;
}

.auth-right {
    background: var(--NeutralBackground);
    text-align: center;
    justify-content: center;
    align-items: center;
    display: flex;
}

.auth-right .illustration img {
    max-width: 300px;
}

.auth-right p {
    font-size: 18px;
    color: var(--Text);
}

form select {
    display: block;
    width: 100%;
    padding: 14px;
    margin-bottom: 10px;
    border: 1px solid var(--Accent);
    border-radius: 30px;
    font-size: 16px;
    background: var(--NeutralBackground);
    font-family:'Rajdhani', sans-serif;

}

.input-field {
    transition: all 0.3s ease;
}
.input-field:focus {
    border-color: var(--Accent);
    box-shadow: 0 0 5px var(--Highlight);
    outline: none;
}

.password-wrapper {
    position: relative;
}

.toggle-password {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    font-size: 18px;
}




/* Custom Scrollbar */
::-webkit-scrollbar {
  width: 10px;            /* Scrollbar এর width */
}
::-webkit-scrollbar-track {
  background: #f1f1f1;     /* Track এর background */
}
::-webkit-scrollbar-thumb {
  background: linear-gradient(#0704a8ff, #dc7d09ff, #01a809ff);     /* Scrollbar এর রঙ */
  border-radius: 5px;      /* Round effect */
}
::-webkit-scrollbar-thumb:hover {
  background: #0704a8ff, #dc7d09ff; 
}

/* Firefox Support */
* {
  scrollbar-width: thin;
  scrollbar-color: #0077cc #f1f1f1;
}
</style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-left">
            <h1>Welcome back!</h1>
            <p>Simplify your workflow and boost your productivity<br> with <strong>HMS App</strong>. Get started for free.</p>

            <form method="POST">
                <?php if ($message && $message !== 'success'): ?>
                    <p class="error"><?= $message ?></p>
                <?php endif; ?>

                <input class="input-field" type="email" name="email" placeholder="Email" required>
                <div class="password-wrapper">
                    <input class="input-field" type="password" name="password" id="password" placeholder="Password" class="input-field" required>
                    <span id="togglePassword" class="toggle-password">👁️</span>
                </div>

                <a href="#" class="forgot-password">Forgot Password?</a>
                <button class="input-field" type="submit">Login</button>

                <div class="divider"><span>or continue with</span></div>

                <div class="social-buttons">
                    <a href="#"><i class="ri-google-fill"></i></a>
                    <a href="#"><i class="ri-apple-line"></i></a>
                    <a href="#"><i class="ri-facebook-fill"></i></a>
                </div>
                <p class="register-text">Not a member? <a href="register.php">Register now</a></p>
            </form>
        </div>

        <div class="auth-right">
            <div class="illustration">
                <img src="../../../public/assets/images/login_image.png" alt="Illustration" />
                <p>Make your work easier and organized<br><strong>with HMS App</strong></p>
            </div>
        </div>
    </div>

<script>
// Password toggle
document.addEventListener("DOMContentLoaded", () => {
    const togglePassword = document.getElementById("togglePassword");
    const password = document.getElementById("password");

    if (togglePassword && password) {
        togglePassword.addEventListener("click", () => {
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);
            togglePassword.textContent = type === "password" ? "👁️" : "🙈";
        });
    }

    // Page load animation
    const authLeft = document.querySelector(".auth-left");
    const authRight = document.querySelector(".auth-right");

    if (authLeft) {
        authLeft.style.opacity = 0;
        authLeft.style.transform = "translateX(-50px)";
        setTimeout(() => {
            authLeft.style.transition = "all 0.8s ease";
            authLeft.style.opacity = 1;
            authLeft.style.transform = "translateX(0)";
        }, 200);
    }

    if (authRight) {
        authRight.style.opacity = 0;
        authRight.style.transform = "translateX(50px)";
        setTimeout(() => {
            authRight.style.transition = "all 0.8s ease";
            authRight.style.opacity = 1;
            authRight.style.transform = "translateX(0)";
        }, 300);
    }
});

</script>
    
</body>
</html>
