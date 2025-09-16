<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../public/vendor/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../public/vendor/PHPMailer/src/SMTP.php';
require_once __DIR__ . '/../public/vendor/PHPMailer/src/Exception.php';

function sendEmail($to, $subject, $body) {
    $mail = new PHPMailer(true);

    try {
        // ✅ SMTP Settings (Gmail Example)
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'firojactivenow@gmail.com';  // ✅ আপনার Gmail
        $mail->Password = 'mlkkcoygbmwnmdqw';          // ✅ App Password (16 digit)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // ✅ আপনার ইমেইল থেকে পাঠানো হবে
        $mail->setFrom('firojactivenow@gmail.com', 'Hospital Management');
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
