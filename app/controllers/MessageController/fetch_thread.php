<?php
session_start();
require_once '../../../config/config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) { http_response_code(401); echo json_encode(['ok'=>false]); exit; }

$user_id   = (int)$_SESSION['user_id'];
$with_id   = (int)($_GET['with_id'] ?? 0);
$after_id  = (int)($_GET['after_id'] ?? 0);   // for new messages polling
$limit     = (int)($_GET['limit'] ?? 50);

if ($with_id <= 0) { echo json_encode(['ok'=>false,'error'=>'with_id required']); exit; }

if ($after_id > 0) {
  $sql = "SELECT * FROM messages
          WHERE ((sender_id=? AND receiver_id=?) OR (sender_id=? AND receiver_id=?))
            AND id > ?
            AND ( (sender_id=? AND sender_deleted=0) OR (receiver_id=? AND receiver_deleted=0) )
          ORDER BY id ASC LIMIT ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('iiiiiiii', $user_id, $with_id, $with_id, $user_id, $after_id, $user_id, $user_id, $limit);
} else {
  $sql = "SELECT * FROM messages
          WHERE ((sender_id=? AND receiver_id=?) OR (sender_id=? AND receiver_id=?))
            AND ( (sender_id=? AND sender_deleted=0) OR (receiver_id=? AND receiver_deleted=0) )
          ORDER BY id DESC LIMIT ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('iiiiiii', $user_id, $with_id, $with_id, $user_id, $user_id, $user_id, $limit);
}

$stmt->execute();
$res = $stmt->get_result();
$rows = [];
while ($r = $res->fetch_assoc()) { $rows[] = $r; }
if ($after_id === 0) { $rows = array_reverse($rows); } // latest at bottom

// mark partner->me messages as read
$mark = $conn->prepare("UPDATE messages SET read_at=NOW() WHERE receiver_id=? AND sender_id=? AND read_at IS NULL");
$mark->bind_param('ii', $user_id, $with_id);
$mark->execute();

// typing/online status:
$partner = $conn->query("SELECT name, role, profile_picture, last_seen FROM users WHERE id={$with_id}")->fetch_assoc();
$isTyping = false;
$st = $conn->prepare("SELECT is_typing_to, updated_at FROM user_status WHERE user_id=?");
$st->bind_param('i', $with_id);
$st->execute();
$stRes = $st->get_result()->fetch_assoc();
if ($stRes && (int)$stRes['is_typing_to'] === $user_id && strtotime($stRes['updated_at']) >= time()-5) {
  $isTyping = true;
}

echo json_encode([
  'ok'=>true,
  'messages'=>$rows,
  'partner'=>$partner,
  'typing'=>$isTyping
]);
