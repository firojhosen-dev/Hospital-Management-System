<?php
require_once "../../../config/config.php";


$action = $_POST['action'] ?? '';

if ($action == 'add') {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $contact = $_POST['contact_number'];
    $email = $_POST['email'];
    $location = $_POST['location'];

    $stmt = $conn->prepare("INSERT INTO departments (name, description, contact_number, email, location) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $desc, $contact, $email, $location);

    if($stmt->execute()){
        echo json_encode(["status"=>1, "msg"=>"✅ Department added successfully"]);
    } else {
        echo json_encode(["status"=>0, "msg"=>"❌ Failed to add department"]);
    }
    exit;
}

if ($action == 'delete') {
    $id = $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM departments WHERE id=?");
    $stmt->bind_param("i", $id);

    if($stmt->execute()){
        echo json_encode(["status"=>1, "msg"=>"🗑 Department deleted successfully"]);
    } else {
        echo json_encode(["status"=>0, "msg"=>"❌ Failed to delete department"]);
    }
    exit;
}

echo json_encode(["status"=>0, "msg"=>"❌ Invalid action"]);

//   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">