<?php
session_start();
require_once '../../../config/config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) { http_response_code(401); echo json_encode(['ok'=>false]); exit; }

$user_id = (int)$_SESSION['user_id'];
$message_id = (int)($_POST['message_id'] ?? 0);

$ownerCol = null;
$chk = $conn->prepare("SELECT sender_id, receiver_id FROM messages WHERE id=?");
$chk->bind_param('i',$message_id);
$chk->execute();
$row = $chk->get_result()->fetch_assoc();
if (!$row) { echo json_encode(['ok'=>false,'error'=>'Not found']); exit; }

if ((int)$row['sender_id'] === $user_id) $ownerCol='sender_deleted';
if ((int)$row['receiver_id'] === $user_id) $ownerCol='receiver_deleted';
if (!$ownerCol) { echo json_encode(['ok'=>false,'error'=>'No permission']); exit; }

$q = $conn->prepare("UPDATE messages SET $ownerCol=1 WHERE id=?");
$q->bind_param('i',$message_id);
echo json_encode(['ok'=>$q->execute()]);
