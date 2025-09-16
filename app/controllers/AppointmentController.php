<?php
session_start();
require_once '../../config/config.php';
require_once '../../config/email_config.php'; // ✅ For Email Notification

class AppointmentController {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // ✅ Book Appointment (Patient)
    public function bookAppointment($patient_id, $doctor_id, $date, $time_slot) {
        $status = "pending";
        $stmt = $this->conn->prepare("INSERT INTO appointments (patient_id, doctor_id, appointment_date, time_slot, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iisss", $patient_id, $doctor_id, $date, $time_slot, $status);

        if ($stmt->execute()) {
            return "✅ Appointment booked successfully!";
        } else {
            return "❌ Error: " . $stmt->error;
        }
    }

    // ✅ Approve or Reject Appointment (Doctor)
    public function updateAppointmentStatus($appointment_id, $action) {
        if (!in_array($action, ['approved', 'rejected'])) {
            return "❌ Invalid action!";
        }

        $stmt = $this->conn->prepare("UPDATE appointments SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $action, $appointment_id);

        if ($stmt->execute()) {
            // ✅ Send Email Notification to Patient
            $stmt2 = $this->conn->prepare("SELECT u.email, u.name 
                                           FROM appointments a
                                           JOIN users u ON a.patient_id = u.id
                                           WHERE a.id = ?");
            $stmt2->bind_param("i", $appointment_id);
            $stmt2->execute();
            $res = $stmt2->get_result();
            $patient = $res->fetch_assoc();

            if ($patient) {
                $subject = "Your Appointment has been " . ucfirst($action);
                $message = "Hello " . $patient['name'] . ",<br><br>
                            Your appointment has been <b>" . ucfirst($action) . "</b>.<br>
                            Please login to check details.<br><br>
                            Regards,<br>Hospital Management System";

                sendEmail($patient['email'], $subject, $message);
            }

            return "✅ Appointment has been $action successfully!";
        } else {
            return "❌ Error: " . $stmt->error;
        }
    }
}

$appointment = new AppointmentController($conn);

// ✅ Handle Book Appointment Request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['book_appointment'])) {
    $patient_id = $_SESSION['user_id'];
    $doctor_id = $_POST['doctor_id'];
    $date = $_POST['appointment_date'];
    $time_slot = $_POST['time_slot'];

    $_SESSION['msg'] = $appointment->bookAppointment($patient_id, $doctor_id, $date, $time_slot);
    header("Location: ../views/patient/appointments.php");
    exit();
}

// ✅ Handle Approve/Reject Appointment Request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && isset($_POST['appointment_id'])) {
    $appointment_id = intval($_POST['appointment_id']);
    $action = $_POST['action'];

    $_SESSION['msg'] = $appointment->updateAppointmentStatus($appointment_id, $action);
    header("Location: ../views/doctor/appointments.php");
    exit();
}
?>
