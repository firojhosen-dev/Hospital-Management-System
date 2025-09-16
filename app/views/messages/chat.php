<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}
require_once "../../../config/config.php";

$me = (int)$_SESSION['user_id'];
$role = $_SESSION['user_role'];

// 🔹 Sidebar Users List: show different users based on role
if ($role === 'admin') {
    $users = $conn->query("SELECT id, name, role, profile_picture FROM users WHERE id != {$me} ORDER BY role, name");
} elseif ($role === 'doctor') {
    $users = $conn->query("SELECT id, name, role, profile_picture FROM users WHERE id != {$me} AND role IN ('patient','admin') ORDER BY role, name");
} else { // patient
    $users = $conn->query("SELECT id, name, role, profile_picture FROM users WHERE id != {$me} AND role IN ('doctor','admin') ORDER BY role, name");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Chat</title>
    <link rel="shortcut icon" href="../../../public/assets/images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.css">
<style>
/*? Google Font Link */
@import url(https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;600;700&display=swap);
/*? All Color */
:root{
    --ButtonColor: #0704a8ff;
    --BackgroundColor: #b7e8faff;
    --TextColor: #00010aff;
    --AllBorderColor: #dc7d09ff;
    --Accent: #01a809ff;
}
:root{
  /* --SidebarColor: #0a4174; */
  --CardColor: #2403b5ff;
  /* --ScrollbarBackgroundColor: #6ea2b3; */
  --ScrollbarHoverColor: rgba(1, 188, 245, 1);
}
*{
  box-sizing:border-box;
}
body{
  margin:0;
  font-family: 'Rajdhani', sans-serif;
  background: var(--BackgroundColor);
  color: var(--TextColor);
}
.msg-wrap{
  display: flex;
  height: 100vh;
  overflow: hidden;
}

.sidebar {
  width: 340px;
  max-width: 100%;
  border-right: 1px solid var(--AllBorderColor);
  background: var(--SidebarColor);
  display: flex;
  flex-direction: column;
  overflow-y: auto;
}

/* Mini Left Sidebar */
.mini-sidebar {
  position: relative;
  width: 60px; 
  background: #1500b3ff;
  color: #fff;
  display: flex;
  flex-direction: column;
  padding-top: 20px;
  transition: width 0.3s ease;
  overflow: hidden;
}

.mini-sidebar ul {
  list-style: none;
  margin: 0;
  padding: 0;
}

.mini-sidebar li {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 14px;
  cursor: pointer;
  transition: background 0.3s;
}

.mini-sidebar li:hover {
  background: #08007bff;
}

.mini-sidebar i {
  font-size: 22px;
}

.mini-sidebar span {
  opacity: 0;
  white-space: nowrap;
  transition: opacity 0.3s, transform 0.3s;
  transform: translateX(-10px);
}

.mini-sidebar:hover {
  width: 200px;
}

.mini-sidebar:hover span {
  opacity: 1;
  transform: translateX(0);
}


.topbar{
  padding:12px;
  border-bottom:1px solid var(--AllBorderColor);
}
#userSearch{
  width:100%;
  padding:10px 12px;
  border:2px solid var(--AllBorderColor);
  border-radius:30px;
  background: var(--BackgroundColor);
  color: var(--TextColor);
}
.tabs{
  margin:10px 0;
  display:flex;
  gap:8px;
}
.tab{
  flex:1;
  padding:8px;
  border:1px solid var(--AllBorderColor);
  background:var(--CardColor);
  border-radius:30px;
  cursor:pointer;
}
.tab.active{
  background:#111827;
  color:var(--TextColor);
  border-color: var(--AllBorderColor);
}
.unread-bell{
  font-size:14px;
  color:var(--TextColor);
  margin-top:6px;
}

#partnerList{
  list-style:none;
  margin:0;
  padding:0;
  overflow:auto;
  height:calc(100vh - 140px);
}
.partner{
  display:flex;
  align-items:center;
  gap:10px;
  padding:10px 12px;
  border-bottom:1px solid var(--AllBorderColor);
  cursor:pointer;
  transition: 0.4s ease-in-out;
}
.partner:hover{
  background:var(--ScrollbarHoverColor);
}
.background:focus{
  background:var(--ScrollbarHoverColor);
}
.partner img{
  width:40px;
  height:40px;
  border-radius:50%;
  object-fit:cover;
}
.partner .name{
  font-weight:600;
}
.partner .last{
  font-size:12px;
  color:#6b7280;
}
.partner .badge{
  margin-left:auto;
  background:#ef4444;
  color:#fff;
  font-size:12px;
  padding:2px 8px;
  border-radius:999px;
}

.chat {
  flex: 1;
  display: flex;
  flex-direction: column;
  overflow-y: auto;
}
.chat-header{
  display:flex;
  justify-content:space-between;
  align-items:center;
  padding:12px 16px;
  border-bottom:1px solid ;
  background:var(--SidebarColor);
}
.peer{
  display:flex;
  gap:10px;
  align-items:center;
}
.peer img{
  width:42px;
  height:42px;
  border-radius:50%;
  object-fit:cover;
}
#peerName{
  font-weight:700;
}
#peerMeta{
  font-size:12px;
  color:#6b7280;
}
.messages{
  flex:1;
  padding:5px;
  overflow:auto;
  background:var(--BackgroundColor);
}
textarea, input{
  font-family: 'Rajdhani', sans-serif;
  outline: none;
  transition: 0.5s ease-in-out;
}
textarea:focus, input:focus{
  box-shadow: 0px 0px 5px var(--ScrollbarHoverColor);
}
.meta{
  font-size:11px;
  color:var(--TextColor);
  margin-top:4px;
  text-align:right;
}
.msg.high{
  border:2px solid var(--AllBorderColor);
}

.composer{
  display:flex;
  gap:10px;
  padding:12px;
  border-top:1px solid var(--AllBorderColor);
  background:var(--SidebarColor);
}
#msgBody{
  flex:1;
  padding:10px;
  border:1px solid var(--AllBorderColor);
  border-radius:30px;
  min-height:44px;
  resize:none;
  background: var(--BackgroundColor);
  color: var(--TextColor);
}
#sendBtn{
  padding:10px 10px;
  border:none;
  background:var(--ButtonColor);
  color:var(--TextColor);
  border-radius:10px;
  cursor:pointer;
}

#sendBtn i{
  font-size: 25px;
}
#sendBtn:disabled{
  opacity:.5;
  cursor:not-allowed;
}

/* Responsive */
@media (max-width:900px){
  .sidebar{
    width:100%;
    position:fixed;
    z-index:10;
    height:60vh;
    transform:translateY(0);
  }
  .chat{
    margin-top:60vh;
  }
}



#messageList {
  flex: 1;
  padding: 15px;
  overflow-y: auto;
  display: flex;
  flex-direction: column;
  gap: 10px;
  background: var(--BackgroundColor);
}
#messageList .msg {
  display: inline-block;
  padding: 10px 14px;
  border-radius: 16px;
  max-width: 70%;
  word-wrap: break-word;
  font-size: 14px;
  line-height: 1.4;
  position: relative;
}
#messageList .msg.me {
  align-self: flex-end;
  background: var(--ButtonColor);
  border-bottom-right-radius: 0;
}
#messageList .msg.them {
  align-self: flex-start;
  background: var(--CardColor);
  border: 1px solid var(--AllBorderColor);
  border-bottom-left-radius: 0;
}
#messageList .msg.high {
  border: 2px solid red; /* High priority message highlight */
}
#messageList .msg .meta {
  font-size: 11px;
  color: var(--TextColor);
  margin-top: 4px;
  text-align: right;
}
#messageList .msg .del {
  position: absolute;
  top: 4px;
  right: 6px;
  cursor: pointer;
  font-size: 12px;
  color: var(--BackgroundColor);
  display: none;
}
#messageList .msg:hover .del {
  display: inline;
}

/* Scrollbar*/
::-webkit-scrollbar {
  width: 5px;      /* Vertical scrollbar width */
  height: 12px;     /* Horizontal scrollbar height */
}

/* Scrollbar track (background) */
::-webkit-scrollbar-track {
  background: var(--BackgroundColor); 
  border-radius: 10px;
}

/* Scrollbar thumb (the draggable part) */
::-webkit-scrollbar-thumb {
  background: var(--ScrollbarBackgroundColor); 
  border-radius: 10px;
}

/* Hover effect on thumb */
::-webkit-scrollbar-thumb:hover {
  background: var(--ScrollbarHoverColor); 
}



.actions button {
  background: none;
  border: none;
  margin-left: 5px;
  font-size: 20px;
  cursor: pointer;
  color: var(--ButtonColor); /* WhatsApp style green */
  transition: transform 0.2s, color 0.2s;
}

.actions button:hover {
  transform: scale(1.2);
  color: var(--ScrollbarHoverColor); /* Darker green on hover */
}

/* Optional: add tooltip style */
.actions button[title] {
  position: relative;
}

.actions button[title]:hover::after {
  content: attr(title);
  position: absolute;
  top: -18px;
  left: 50%;
  transform: translateX(-50%);
  background: var(--BackgroundColor);
  color: var(--TextColor);
  padding: 3px 7px;
  border-radius: 5px;
  font-size: 12px;
  white-space: nowrap;
}
#priority{
  outline: none;
  background: var(--ButtonColor);
  padding: 3px;
  border-radius: 12px;
  border: 2px solid var(--AllBorderColor);
  transition: 0.5s ease-in-out;
}
#priority:focus{
  background: var(--ScrollbarBackgroundColor);
}

</style>
</head>
<body>
<div class="message_main_container">
<div class="msg-wrap">
    <!-- Left Icon Sidebar -->
<aside class="mini-sidebar">
  <ul>
    <li title="Profile" onclick="window.location='profile.php'">
      <i class="ri-user-3-line"></i><span>Profile</span>
    </li>
    <li title="Messages">
      <i class="ri-chat-3-line"></i><span>Messages</span>
    </li>
    <li title="Calls">
      <i class="ri-phone-line"></i><span>Calls</span>
    </li>
    <li title="Status">
      <i class="ri-checkbox-circle-line"></i><span>Status</span>
    </li>
    <li title="Settings" onclick="window.location='settings.php'">
      <i class="ri-settings-3-line"></i><span>Settings</span>
    </li>
    <li title="Notification">
        <i class="ri-notification-2-line"></i> <span id="unreadCount">Notification</span>
    </li>
  </ul>
</aside>
  <!-- Sidebar (User List) -->
  <aside class="sidebar">
    <div class="topbar">
      <input type="text" id="userSearch" placeholder="Search user...">
      <!-- <div class="unread-bell">
      </div> -->
    </div>
    <ul id="partnerList">
      <?php while($u = $users->fetch_assoc()): ?>
        <li class="partner" data-id="<?php echo $u['id']; ?>">
          <img src="<?php echo '../../../public/assets/uploads/'.($u['profile_picture'] ?: 'default.jpg'); ?>" alt="">
          <div>
            <div class="name"><?php echo htmlspecialchars($u['name']); ?> <small>(<?php echo $u['role']; ?>)</small></div>
            <div class="last">Tap to chat</div>
          </div>
          <span class="badge" style="display:none;">0</span>
        </li>
      <?php endwhile; ?>
    </ul>
  </aside>


  <!-- Main Chat Window -->
  <main class="chat">
    <div class="chat-header">
      <div class="peer">
        <img id="peerAvatar" src="../../../public/assets/uploads/default.jpg" alt="">
        <div>
          <div id="peerName">Select a user</div>
          <div id="peerMeta"><span id="typingHint" style="display:none;">typing…</span></div>
        </div>
      </div>
<div class="actions">
  <!-- Audio & Video Call Buttons -->
  <button id="audioCall" title="Audio Call">
    <i class="ri-phone-line"></i>
  </button>
  <button id="videoCall" title="Video Call">
    <i class="ri-vidicon-line"></i>
  </button>

  <select id="priority">
    <option value="normal">Normal</option>
    <option value="high">High</option>
  </select>
</div>
    </div>

    <div id="messageList" class="messages"></div>

    <div class="composer">
      <textarea id="msgBody" placeholder="Write a message..." disabled></textarea>
      <button id="sendBtn" disabled><i class="ri-send-plane-fill"></i></button>
      <button id="SendOthers" disabled><i class="ri-send-plane-fill"></i></button>
    </div>
  </main>
</div>
</div>
<!-- Global User ID -->
<script>
const ME = <?php echo (int)$_SESSION['user_id']; ?>;
</script>
<script>
// * Messages Script
let activePartner = null;
let lastMsgId = 0;
let pollTimer = null;
let typingTimer = null;

const $ = s => document.querySelector(s);
const $$ = s => document.querySelectorAll(s);

function setActivePartner(id, name, avatar) {
  activePartner = parseInt(id,10);
  $('#peerName').textContent = name;
  $('#peerAvatar').src = avatar || '../../../public/assets/uploads/default.jpg';
  $('#msgBody').disabled = false;
  $('#sendBtn').disabled = false;
  $('#messageList').innerHTML = '';
  lastMsgId = 0;
  fetchThread();
}

async function fetchThread(after = false){
  if(!activePartner) return;
  const url = `../../../app/controllers/MessageController/fetch_thread.php?with_id=${activePartner}` + (after?`&after_id=${lastMsgId}`:'');
  const res = await fetch(url);
  const data = await res.json();
  if(!data.ok) return;

  if (data.typing) {
    $('#typingHint').style.display = 'inline';
  } else {
    $('#typingHint').style.display = 'none';
  }

  const box = $('#messageList');
  data.messages.forEach(m=>{
    const div = document.createElement('div');
    div.className = 'msg ' + (m.sender_id == ME ? 'me' : 'them') + (m.priority === 'high' ? ' high' : '');
    div.dataset.id = m.id;
    div.innerHTML = `
      <span class="del" title="Delete">✖</span>
      ${escapeHtml(m.body)}
      <div class="meta">${stamp(m.created_at)} ${m.sender_id==ME ? (m.read_at ? '✓✓ Seen' : '✓ Delivered') : ''}</div>
    `;
    box.appendChild(div);
    lastMsgId = Math.max(lastMsgId, parseInt(m.id,10));
  });
  box.scrollTop = box.scrollHeight;

  // clear unread badge for this partner
  const item = document.querySelector(`.partner[data-id="${activePartner}"] .badge`);
  if (item) { item.style.display='none'; item.textContent='0'; }

  // mark read ping
  await fetch('../../../app/controllers/MessageController/mark_read.php', {
    method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'},
    body:`from_id=${activePartner}`
  });
}

function escapeHtml(s){ return s.replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m])); }
function stamp(dt){ return new Date(dt.replace(' ','T')).toLocaleString(); }

async function sendMessage(){
  if(!activePartner) return;
  const body = $('#msgBody').value.trim();
  if(!body) return;
  const priority = $('#priority').value;
  const form = new URLSearchParams({receiver_id: activePartner, body, priority});
  const res = await fetch('../../../app/controllers/MessageController/send_message.php',{
    method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'}, body: form.toString()
  });
  const data = await res.json();
  if(data.ok){
    $('#msgBody').value = '';
    fetchThread(true);
  }
}

async function poll(){
  await fetchThread(true);
  // unread counter
  const rc = await fetch('../../../app/controllers/MessageController/unread_count.php');
  const c = await rc.json();
  if (c.ok) $('#unreadCount').textContent = c.count;
}
function startPoll(){
  if (pollTimer) clearInterval(pollTimer);
  pollTimer = setInterval(poll, 3000);
}
startPoll();

$('#sendBtn').addEventListener('click', sendMessage);
$('#msgBody').addEventListener('keydown', e=>{
  if(e.key === 'Enter' && !e.shiftKey){ e.preventDefault(); sendMessage(); }
  typing(true);
});
$('#msgBody').addEventListener('keyup', ()=>{ clearTimeout(typingTimer); typingTimer = setTimeout(()=>typing(false), 800); });

async function typing(state){
  if(!activePartner) return;
  await fetch('../../../app/controllers/MessageController/typing.php', {
    method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'},
    body:`to_id=${activePartner}&is_typing=${state?1:0}`
  });
}

$('#partnerList').addEventListener('click', e=>{
  const li = e.target.closest('.partner');
  if(!li) return;
  const id = li.dataset.id;
  const name = li.querySelector('.name').textContent;
  const avatar = li.querySelector('img').src;
  setActivePartner(id, name, avatar);
});

$('#messageList').addEventListener('click', async e=>{
  if (e.target.classList.contains('del')) {
    const msgEl = e.target.closest('.msg');
    const id = msgEl.dataset.id;
    if (confirm('Delete this message for you?')) {
      const res = await fetch('../../../app/controllers/MessageController/delete_message.php',{
        method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:`message_id=${id}`
      });
      const j = await res.json();
      if (j.ok) msgEl.remove();
    }
  }
});

$('#userSearch').addEventListener('input', ()=>{
  const q = $('#userSearch').value.toLowerCase();
  $$('#partnerList .partner').forEach(li=>{
    const name = li.querySelector('.name').textContent.toLowerCase();
    li.style.display = name.includes(q) ? '' : 'none';
  });
});

// tabs (inbox/sent) populate partner previews
$$('.tab').forEach(t=>t.addEventListener('click', async ()=>{
  $$('.tab').forEach(x=>x.classList.remove('active')); t.classList.add('active');
  const type = t.dataset.tab;
  const res = await fetch(`../../../app/controllers/MessageController/fetch_inbox.php?type=${type}`);
  const data = await res.json();
  if(!data.ok) return;
  const ul = $('#partnerList'); ul.innerHTML = '';
  data.items.forEach(it=>{
    const p = it.partner;
    const li = document.createElement('li');
    li.className = 'partner'; li.dataset.id = p.id;
    li.innerHTML = `
      <img src="../../../public/assets/uploads/${p.profile_picture || 'default.jpg'}" alt="">
      <div>
        <div class="name">${p.name} <small>(${p.role})</small></div>
        <div class="last">${escapeHtml(it.body).slice(0,60)}</div>
      </div>
      <span class="badge" ${it.unread_count?'' : 'style="display:none;"'}>${it.unread_count||0}</span>
    `;
    ul.appendChild(li);
  });
}));
// load inbox once
document.querySelector('.tab[data-tab="inbox"]').click();

const audioBtn = document.getElementById('audioCall');
const videoBtn = document.getElementById('videoCall');

audioBtn.addEventListener('click', () => {
  alert("Audio call feature clicked!"); 
});

videoBtn.addEventListener('click', () => {
  alert("Video call feature clicked!"); 
});


let localStream;
let peerConnection;
const config = { iceServers: [{ urls: 'stun:stun.l.google.com:19302' }] };
const ROOM = "myroom"; // room name
const socket = io();  // connect to Socket.io server

// Join room
socket.emit('join', ROOM);

// Call buttons
audioBtn.addEventListener('click', () => startCall(false));
videoBtn.addEventListener('click', () => startCall(true));

async function startCall(isVideo) {
    localStream = await navigator.mediaDevices.getUserMedia({ video: isVideo, audio: true });
    document.getElementById('localVideo').srcObject = localStream;

    createPeer(true); // initiator
}

function createPeer(initiator, remoteId) {
    peerConnection = new RTCPeerConnection(config);

    // add local tracks
    localStream.getTracks().forEach(track => peerConnection.addTrack(track, localStream));

    // receive remote stream
    peerConnection.ontrack = e => {
        document.getElementById('remoteVideo').srcObject = e.streams[0];
    };

    // ICE candidate
    peerConnection.onicecandidate = e => {
        if (e.candidate) {
            socket.emit('signal', { to: remoteId, signal: e.candidate });
        }
    };

    if (initiator) {
        peerConnection.createOffer().then(offer => {
            peerConnection.setLocalDescription(offer);
            socket.emit('signal', { to: remoteId, signal: offer });
        });
    }
}

// handle incoming signal
socket.on('signal', async data => {
    if (!peerConnection) createPeer(false);
    if (data.signal.type) { // offer/answer
        await peerConnection.setRemoteDescription(new RTCSessionDescription(data.signal));
        if (data.signal.type === 'offer') {
            const answer = await peerConnection.createAnswer();
            await peerConnection.setLocalDescription(answer);
            socket.emit('signal', { to: data.from, signal: peerConnection.localDescription });
        }
    } else { // ICE candidate
        await peerConnection.addIceCandidate(data.signal);
    }
});

// * Server Script
const express = require('express');
const http = require('http');
const { Server } = require('socket.io');

const app = express();
const server = http.createServer(app);
const io = new Server(server);

app.use(express.static('public')); // তোমার HTML/CSS/JS

io.on('connection', socket => {
    console.log('User connected', socket.id);

    socket.on('join', roomId => {
        socket.join(roomId);
        socket.to(roomId).emit('user-joined', socket.id);
    });

    socket.on('signal', data => {
        io.to(data.to).emit('signal', { from: socket.id, signal: data.signal });
    });

    socket.on('disconnect', () => {
        console.log('User disconnected', socket.id);
        io.emit('user-disconnected', socket.id);
    });
});

server.listen(3000, () => console.log('Server running on port 3000'));
</script>
</body>
</html>
