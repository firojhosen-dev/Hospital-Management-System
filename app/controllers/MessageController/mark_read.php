<?php
session_start();
require_once '../../../config/config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) { http_response_code(401); echo json_encode(['ok'=>false]); exit; }
$user_id = (int)$_SESSION['user_id'];
$from_id = (int)($_POST['from_id'] ?? 0);

$stmt = $conn->prepare("UPDATE messages SET read_at=NOW() WHERE receiver_id=? AND sender_id=? AND read_at IS NULL");
$stmt->bind_param('ii', $user_id, $from_id);
echo json_encode(['ok'=>$stmt->execute()]);
