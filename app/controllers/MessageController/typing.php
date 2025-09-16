<?php
session_start();
require_once '../../../config/config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) { http_response_code(401); echo json_encode(['ok'=>false]); exit; }
$user_id = (int)$_SESSION['user_id'];
$to_id   = (int)($_POST['to_id'] ?? 0);
$is_typing = (int)($_POST['is_typing'] ?? 0);

if ($is_typing) {
  $stmt = $conn->prepare("INSERT INTO user_status (user_id, is_typing_to, updated_at) VALUES (?,?,NOW())
                          ON DUPLICATE KEY UPDATE is_typing_to=VALUES(is_typing_to), updated_at=NOW()");
  $stmt->bind_param('ii',$user_id,$to_id);
} else {
  $stmt = $conn->prepare("UPDATE user_status SET is_typing_to=NULL, updated_at=NOW() WHERE user_id=?");
  $stmt->bind_param('i',$user_id);
}
echo json_encode(['ok'=>$stmt->execute()]);
