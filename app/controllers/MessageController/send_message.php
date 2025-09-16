<?php
session_start();
require_once '../../../config/config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
  http_response_code(401);
  echo json_encode(['ok'=>false,'error'=>'Unauthorized']);
  exit;
}

$sender_id   = (int)$_SESSION['user_id'];
$receiver_id = (int)($_POST['receiver_id'] ?? 0);
$body        = trim($_POST['body'] ?? '');
$priority    = ($_POST['priority'] ?? 'normal') === 'high' ? 'high' : 'normal';

if ($receiver_id <= 0 || $body === '') {
  echo json_encode(['ok'=>false,'error'=>'Missing fields']);
  exit;
}

$stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, body, priority, delivered_at) VALUES (?,?,?,?,NOW())");
$stmt->bind_param('iiss', $sender_id, $receiver_id, $body, $priority);
$ok = $stmt->execute();

if ($ok) {
  // update sender last_seen (activity ping)
  $conn->query("UPDATE users SET last_seen=NOW() WHERE id={$sender_id}");
  echo json_encode(['ok'=>true, 'message_id'=>$conn->insert_id]);
} else {
  echo json_encode(['ok'=>false,'error'=>$stmt->error]);
}
