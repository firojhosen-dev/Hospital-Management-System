<?php
session_start();
require_once '../../../config/config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) { http_response_code(401); echo json_encode(['ok'=>false]); exit; }
$user_id = (int)$_SESSION['user_id'];

$q = $conn->prepare("SELECT COUNT(*) c FROM messages WHERE receiver_id=? AND read_at IS NULL AND receiver_deleted=0");
$q->bind_param('i',$user_id);
$q->execute();
$c = $q->get_result()->fetch_assoc()['c'] ?? 0;
echo json_encode(['ok'=>true,'count'=>(int)$c]);
