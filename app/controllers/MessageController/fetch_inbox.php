<?php
session_start();
require_once '../../../config/config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) { http_response_code(401); echo json_encode(['ok'=>false]); exit; }
$user_id = (int)$_SESSION['user_id'];
$type = $_GET['type'] ?? 'inbox'; // inbox|sent

if ($type === 'sent') {
  $sql = "SELECT m.*
          FROM messages m
          JOIN (
            SELECT GREATEST(sender_id, receiver_id) g1, LEAST(sender_id, receiver_id) g2, MAX(id) max_id
            FROM messages
            WHERE sender_id=?
              AND sender_deleted=0
            GROUP BY g1, g2
          ) t ON m.id=t.max_id
          ORDER BY m.created_at DESC";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('i', $user_id);
} else {
  $sql = "SELECT m.*
          FROM messages m
          JOIN (
            SELECT GREATEST(sender_id, receiver_id) g1, LEAST(sender_id, receiver_id) g2, MAX(id) max_id
            FROM messages
            WHERE receiver_id=?
              AND receiver_deleted=0
            GROUP BY g1, g2
          ) t ON m.id=t.max_id
          ORDER BY m.created_at DESC";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('i', $user_id);
}

$stmt->execute(); $res = $stmt->get_result(); $rows=[];
while($r=$res->fetch_assoc()){
  $partner_id = ($type==='sent') ? (int)$r['receiver_id'] : (int)$r['sender_id'];
  $partner = $conn->query("SELECT id,name,role,profile_picture FROM users WHERE id={$partner_id}")->fetch_assoc();
  $r['partner'] = $partner;
  // unread count per partner
  $uq = $conn->prepare("SELECT COUNT(*) c FROM messages WHERE receiver_id=? AND sender_id=? AND read_at IS NULL AND receiver_deleted=0");
  $uq->bind_param('ii', $user_id, $partner_id);
  $uq->execute();
  $c = $uq->get_result()->fetch_assoc()['c'];
  $r['unread_count'] = (int)$c;
  $rows[] = $r;
}
echo json_encode(['ok'=>true,'items'=>$rows]);
