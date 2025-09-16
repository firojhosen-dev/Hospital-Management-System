<?php
session_start();
require_once '../../../../config/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id   = $_POST['id'] ?? 0;
    $name = $_POST['name'] ?? '';
    $desc = $_POST['description'] ?? '';
    $qty  = $_POST['quantity'] ?? 0;
    $price = $_POST['price'] ?? 0;
    $manu = $_POST['manufacturer'] ?? '';
    $expiry = $_POST['expiry_date'] ?? null;

    $uploaded_images = [];

    // Handle image uploads (max 5)
    if (isset($_FILES['images'])) {
        $files = $_FILES['images'];
        $count = count($files['name']);
        if ($count > 5) $count = 5;

        for ($i = 0; $i < $count; $i++) {
            if ($files['error'][$i] === 0) {
                $ext = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
                $imageName = uniqid('prod_') . '.' . $ext;
                $uploadDir = "uploads/";
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                $imagePath = $uploadDir . $imageName;

                if (move_uploaded_file($files['tmp_name'][$i], $imagePath)) {
                    $uploaded_images[] = $imageName;
                }
            }
        }
    }

    $images_str = implode(',', $uploaded_images);

    // If ID exists → Update
    if ($id) {
        if ($images_str) { 
            // Delete old images if new uploaded
            $stmt_old = $conn->prepare("SELECT images FROM products WHERE id=?");
            $stmt_old->bind_param("i", $id);
            $stmt_old->execute();
            $res = $stmt_old->get_result();
            $prod = $res->fetch_assoc();
            if ($prod) {
                $old_images = explode(',', $prod['images']);
                foreach ($old_images as $img) {
                    if ($img && file_exists("uploads/" . $img)) {
                        unlink("uploads/" . $img);
                    }
                }
            }

            $stmt = $conn->prepare("UPDATE products SET name=?, description=?, quantity=?, price=?, manufacturer=?, expiry_date=?, images=? WHERE id=?");
            $stmt->bind_param("ssidsssi", $name, $desc, $qty, $price, $manu, $expiry, $images_str, $id);
        } else { 
            // keep old images
            $stmt = $conn->prepare("UPDATE products SET name=?, description=?, quantity=?, price=?, manufacturer=?, expiry_date=? WHERE id=?");
            $stmt->bind_param("ssids si", $name, $desc, $qty, $price, $manu, $expiry, $id);
        }

        if ($stmt->execute()) {
            echo json_encode(["status" => 1, "msg" => "✅ Product updated successfully"]);
        } else {
            echo json_encode(["status" => 0, "msg" => "❌ Failed to update: " . $stmt->error]);
        }

    } else { 
        // Insert new product
        $stmt = $conn->prepare("INSERT INTO products (name, description, quantity, price, manufacturer, expiry_date, images) VALUES (?,?,?,?,?,?,?)");
        $stmt->bind_param("ssidsss", $name, $desc, $qty, $price, $manu, $expiry, $images_str);

        if ($stmt->execute()) {
            echo json_encode(["status" => 1, "msg" => "✅ Product added successfully"]);
        } else {
            echo json_encode(["status" => 0, "msg" => "❌ Failed to add: " . $stmt->error]);
        }
    }
}