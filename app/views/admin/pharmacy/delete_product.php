<?php
session_start();
require_once '../../../../config/config.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? 0;

    if ($id) {
        // প্রথমে image path খুঁজে বের করি (delete করার জন্য)
        $stmt = $conn->prepare("SELECT image FROM pharmacy WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $med = $res->fetch_assoc();

        if ($med) {
            // Image delete
            if (!empty($med['image']) && file_exists("../../".$med['image'])) {
                unlink("../../".$med['image']);
            }

            // Database থেকে delete
            $stmt = $conn->prepare("DELETE FROM pharmacy WHERE id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(["status" => 1, "msg" => "🗑 Medicine deleted successfully"]);
            } else {
                echo json_encode(["status" => 0, "msg" => "❌ Medicine not found"]);
            }
        } else {
            echo json_encode(["status" => 0, "msg" => "❌ Medicine not found"]);
        }

    } else {
        echo json_encode(["status" => 0, "msg" => "❌ Invalid ID"]);
    }
}
?>