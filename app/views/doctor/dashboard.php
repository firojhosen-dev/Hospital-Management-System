<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'doctor') {
    header("Location: ../auth/login.php");
    exit();
}
?>


<!DOCTYPE html><html lang="bn">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Doctor Dashboard</title>
    <link rel="shortcut icon" href="../../../public/assets/images/logo.png" type="image/x-icon">
  <!--* <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<Web Icon Link>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.css">
<style>
@import url(https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;600;700&display=swap);
body{
      font-family: 'Rajdhani', sans-serif;

}
    /* -------------------------------
       RESET & BASE
    --------------------------------*/
    *, *::before, *::after { box-sizing: border-box; }
    html, body { height: 100%; }
    body {
      margin: 0;
      font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, "Apple Color Emoji", "Segoe UI Emoji";
      line-height: 1.5;
      background: var(--bg);
      color: var(--text);
      transition: background .25s ease, color .25s ease;
    }
    :root{
      /* LIGHT */
      --bg: #f3f5f9;
      --card: #ffffff;
      --muted: #6b7280;
      --text: #0f172a;
      --primary: #5b7cfa;
      --primary-ink:#ffffff;
      --ring: 0 0 0 3px rgba(91,124,250,.25);
      --stroke: #e5e7eb;
      --shadow: 0 10px 35px rgba(15,23,42,.08);
      --sidebar:#0f172a;
      --sidebar-ink:#cbd5e1;
      --sidebar-active:#ffffff;
      --chip1:#e8f2ff; --chip1-ink:#3b82f6;
      --chip2:#e9ffef; --chip2-ink:#16a34a;
      --chip3:#fff5e6; --chip3-ink:#f59e0b;
    }
    body.dark{
      /* DARK */
      --bg: #0b1220;
      --card: #0f172a;
      --muted: #93a3b8;
      --text: #e5e9f2;
      --primary: #6d8cff;
      --primary-ink:#0b1220;
      --ring: 0 0 0 3px rgba(109,140,255,.22);
      --stroke: #1e293b;
      --shadow: 0 10px 35px rgba(2,6,23,.55);
      --sidebar:#0b1220;
      --sidebar-ink:#8ea3c2;
      --sidebar-active:#ffffff;
      --chip1:#0b244a; --chip1-ink:#8bb4ff;
      --chip2:#072a18; --chip2-ink:#7ff0a1;
      --chip3:#2a1f07; --chip3-ink:#ffd68a;
    }
    img { max-width: 100%; display: block; }
    button { cursor: pointer; border: 0; background: transparent; color: inherit; }
    .sr-only{ position:absolute; width:1px; height:1px; padding:0; margin:-1px; overflow:hidden; clip:rect(0,0,0,0); border:0; }/* -------------------------------
   LAYOUT
--------------------------------*/
.app{
  display:grid;
  grid-template-columns: 88px 1fr;
  min-height: 100dvh;
}
/* Sidebar */
.sidebar{
  background: var(--sidebar);
  color: var(--sidebar-ink);
  padding: 20px 16px;
  display:flex; flex-direction:column; gap:14px; align-items:center;
}
.logo{
  width: 44px; height:44px; border-radius: 14px; display:grid; place-items:center;
  background: linear-gradient(135deg, #5b7cfa, #22c1c3);
  color:#fff; font-weight:700; box-shadow: var(--shadow);
}
.nav{ margin-top: 8px; display:flex; flex-direction:column; gap: 10px; width:100%; }
.nav a{
  text-decoration: none;
}
.nav a button i{
  font-size: 30px;
}
.nav button{
  width:100%; 
  height:44px; 
  border-radius:14px; 
  display:grid; 
  place-items:center; 
  position:relative;
  color: var(--sidebar-ink);
}
.nav button.active, .nav button:hover{
  background: rgba(255,255,255,.1);
  color: var(--sidebar-active);
}
.sidebar .spacer{ flex:1; }
.sidebar .mini{
  width: 40px; height:40px; border-radius: 12px; display:grid; place-items:center; background: #1f2937; color:#fff;
}

/* Main */
.main{
  padding: 22px 26px;
  display:grid;
  grid-template-rows: auto 1fr;
  gap: 18px;
}

/* Topbar */
.topbar{
  display:flex; align-items:center; gap: 12px; 
}
.search{ flex:1; position:relative; }
.search input{
  width:100%; height:46px; border-radius:16px; border:1px solid var(--stroke); background: var(--card);
  color:var(--text); padding: 0 46px 0 46px; outline: none; box-shadow: var(--shadow);
}
.search svg{ position:absolute; left:14px; top:50%; transform:translateY(-50%); opacity:.6 }

.cluster{ display:flex; align-items:center; gap:10px; }
.pill{
  height: 40px; display:inline-flex; align-items:center; gap:10px; padding:0 12px; border-radius:999px;
  background: var(--card); border: 1px solid var(--stroke); box-shadow: var(--shadow);
}
.avatar{ width:36px; height:36px; border-radius:50%; background: url('https://i.imgur.com/3y0R3.jpg') center/cover no-repeat; }

/* Grid area */
.grid{
  display:grid; gap:18px;
  grid-template-columns: 1.4fr 1fr; grid-auto-rows:minmax(120px, auto);
  grid-template-areas:
    "profile calendar"
    "stats   schedule"
    "table   schedule";
}

/* Card */
.card{ 
    text-align: center;
    background: var(--card); 
    border:1px solid var(--stroke); 
    border-radius:22px; 
    box-shadow: var(--shadow); 
    padding:18px; }
.card h3{ 
    margin:0 0 10px; 
    font-size: 18px; 
}

/* Profile card */
.profile{ 
    text-align: center;
    grid-area: profile; 
    display:grid; 
    /* grid-template-columns: 130px 1fr;  */
    gap:18px; 
    align-items:center; 
}
.profile .doc{
    text-align: center;
    width:200px; 
    height:200px; 
    border-radius:50%; 
    background:#eef2ff; 
    /* display:grid;  */
    display: block;
    place-items:center; 
    overflow:hidden; 
}
.chips{ display:flex; flex-wrap:wrap; gap:8px; }
.chip{ padding:6px 10px; border-radius:999px; font-size:12px; font-weight:600; }
.chip.c1{ background: var(--chip1); color: var(--chip1-ink); }
.chip.c2{ background: var(--chip2); color: var(--chip2-ink); }
.chip.c3{ background: var(--chip3); color: var(--chip3-ink); }
.muted{ color:var(--muted); font-size: 13px; }

/* Stats */
.stats{ 
  grid-area: stats; 
  display:grid; 
  grid-template-columns: 
  repeat(3, 1fr);
  gap:14px; 
  text-align: center;
}
.stat{ 
  text-align: center;
  display:flex; 
  align-items:center; 
  gap:12px; 
  padding:14px; 
  border-radius:18px; 
  border:1px solid var(--stroke); 
  background:var(--card); 
  box-shadow: var(--shadow); 
  transition: 0.5s ease-in-out;
}
.stat .ic{ 
  /* width:40px; 
  height:40px;  */
  display:grid; 
  place-items:center; 
  border-radius:12px; 
  background:#eef2ff; 
}
.stat .ic i{
  font-size: 30px;
}
body.dark .stat .ic{ 
  background:#111827; 
}
.stat .value{ 
  text-align: center;
  /* font-weight:800;  */
  font-size: 18px; 
}
.stat .label{ 
  font-size:18px; 
  color: var(--muted); 
}
.stats a{
  text-decoration: none;
  color: #22c1c3;
}
.stats .stat:hover{
  box-shadow: 0 0 10px blue;
}
/* Calendar */
.calendar{ grid-area: calendar; }
.cal-head{ display:flex; align-items:center; justify-content:space-between; }
.cal-grid{ display:grid; grid-template-columns: repeat(7, 1fr); gap:10px; margin-top:14px; }
.cal-grid .cell{ aspect-ratio:1/1; border-radius:14px; display:grid; place-items:center; border:1px solid var(--stroke); background:var(--card); font-weight:600; }
.cal-grid .muted-day{ opacity:.45; }
.cal-grid .today{ background: var(--primary); color: var(--primary-ink); box-shadow: var(--ring); border-color: transparent; }

/* Schedule */
.schedule{ grid-area: schedule; display:flex; flex-direction:column; gap:12px; }
.slot{ display:flex; align-items:center; justify-content:space-between; gap:12px; padding:14px; border-radius:18px; border:1px solid var(--stroke); background:var(--card); box-shadow: var(--shadow); }
.slot.primary{ background: #0f172a; color:#fff; border-color:transparent; }
body.dark .slot.primary{ background:#111827; }
.slot .time{ font-size:12px; color:var(--muted); }

/* Table */
.table{ grid-area: table; overflow:auto; }
table{ width:100%; border-collapse: collapse; }
th, td{ padding:12px 10px; border-bottom:1px solid var(--stroke); text-align:left; white-space:nowrap; }
thead th{ font-size:12px; color:var(--muted); font-weight:700; position:sticky; top:0; background:var(--card); }

/* Buttons */
.btn{ display:inline-flex; align-items:center; gap:10px; height:40px; padding:0 14px; border-radius:12px; background:var(--primary); color: var(--primary-ink); font-weight:700; box-shadow: var(--shadow); }
.icon-btn{ width:36px; height:36px; display:grid; place-items:center; border-radius:10px; border:1px solid var(--stroke); background:var(--card); }

/* Responsive */
@media (max-width: 1200px){
  .grid{ grid-template-columns: 1fr; grid-template-areas:
    "profile" "calendar" "stats" "schedule" "table"; }
  .stats{ grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 720px){
  .app{ grid-template-columns: 72px 1fr; }
  .profile{ grid-template-columns: 1fr; text-align:center; }
  .profile .doc{ margin:0 auto; }
  .topbar{ flex-wrap: wrap; }
  .stats{ grid-template-columns: 1fr; }
}

.doctor_profile_info img{
  display: inline-block;
  justify-content: center;
  align-items: center;
  text-align: center;
  width: 200px;
  height: 200px;
}

</style>
</head>
<body>
  <div class="app">
    <!-- SIDEBAR -->
    <aside class="sidebar" aria-label="Sidebar">
      <a href="#">
      <div class="logo" title="Hospital Management System">
        <i class="ri-hospital-line"></i>
      </div>
      </a>
      <nav class="nav" aria-label="Main Navigation">
        <a href="#"><button class="active" title="Dashboard" aria-current="page"><i class="ri-dashboard-horizontal-line"></i></button></a>
        <a href="prescriptions.php"><button title="Prescriptions"><i class="ri-article-line"></i></button></a>
        <a href="reports.php"><button title="Upload Patient Reports"><i class="ri-bar-chart-box-line"></i></button></a>
        <a href="appointments.php"><button title="Appointments"><i class="ri-calendar-2-line"></i></button></a>
        <a href="billing.php"><button title="Billing"><i class="ri-bill-line"></i></button></a>
        <a href="#"><button title="Analytics"><i class="ri-bar-chart-fill"></i></button></a>
        <a href="../messages/chat.php"><button title="Messages"><i class="ri-message-3-line"></i></button></a>
        <a href="../admin/dashboard_settings.php"><button title="Settings"><i class="ri-settings-3-line"></i></button></a>
        <a href="../../controllers/logout.php"><button title="Logout"><i class="ri-logout-box-line"></i></button></a>
      </nav>
      <div class="spacer"></div>
      <div class="mini" id="collapseBtn" title="Collapse">≡</div>
    </aside><!-- MAIN -->
<main class="main">
  <!-- TOPBAR -->
  <div class="topbar">
    <div class="search" role="search">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M21 21l-4.3-4.3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2"/></svg>
      <input type="text" placeholder="Search something…" id="searchInput" aria-label="Search"/>
    </div>
    <div class="cluster">
      <button class="icon-btn" id="notifBtn" aria-label="Notifications">🔔</button>
      <label class="pill" title="Toggle Dark Mode">
        <input id="darkToggle" type="checkbox" class="sr-only" />
        <span>🌙</span>
        <strong>Dark</strong>
      </label>
      <div class="pill">
        <!-- aria-hidden="true" -->
        <div class="avatar" >
          <img src="../../../public/assets/images/logo.png" alt="">
        </div>
        <div>
          <div style="font-weight:700; font-size:14px;">Dr.<?php echo $_SESSION['user_name']; ?></div>
          <!-- <div class="muted" style="font-size:12px;">Medical Practitioner</div> -->
        </div>
      </div>
    </div>
  </div>

  <!-- GRID -->
  <section class="grid">
    <!-- PROFILE CARD -->
    <article class="card profile">
      
      <div class="doctor_profile_info">
        <!-- <div class="doc">
    </div> -->
        <img src="../../../public/assets/images/logo.png" alt="Doctor photo">

        <h2>Prof. Dr. <?php echo $_SESSION['user_name']; ?></h2>
        <div class="muted">Specialist Anesthesiologist</div>
        <!-- <div class="chips" style="margin-bottom:10px;">
          <span class="chip c1">Co-founder</span>
          <span class="chip c2">Surgeon</span>
          <span class="chip c3">Veteran</span>
        </div> -->
        <p class="muted">Learn ipsum is simply dummy text of the printing and typesetting industry. It has been the industry's standard.</p>
      </div>
    </article>

    <!-- CALENDAR -->
    <aside class="card calendar">
      <div class="cal-head">
        <h3 id="calTitle">December 2021</h3>
        <div>
          <button class="icon-btn" id="prevMonth" aria-label="Previous month">◀</button>
          <button class="icon-btn" id="nextMonth" aria-label="Next month">▶</button>
        </div>
      </div>
      <div class="cal-grid" id="calWeekdays"></div>
      <div class="cal-grid" id="calDays" style="margin-top:8px"></div>
    </aside>

    <!-- STATS -->
    <section class="stats">
      <a href="appointments.php">
      <div class="stat">
        <div class="ic"><i class="ri-calendar-2-line"></i></div>
        <div>
          <!-- <div class="value" data-count="1250">0</div> -->
          <!-- <div class="icon"></div> -->
          <div class="label">Appointments</div>
        </div>
      </div>
      </a>
      <a href="prescriptions.php">
      <div class="stat">
        <div class="ic"><i class="ri-article-line"></i></div>
        <div>
          <div class="label">Prescriptions</div>
        </div>
      </div>
      </a>
      <a href="reports.php">
      <div class="stat">
        <div class="ic"><i class="ri-bar-chart-box-line"></i></div>
        <div>
          <div class="label">Patient Reports</div>
        </div>
      </div>
      </a>
      <a href="billing.php">
      <div class="stat">
        <div class="ic"><i class="ri-bill-line"></i></div>
        <div>
          <div class="label">Generate Bill</div>
        </div>
      </div>
      </a>
    </section>

    <!-- TABLE -->
    <section class="card table">
      <h3 style="margin-bottom:10px;">Latest Patients Data</h3>
      <div style="overflow:auto;">
        <table aria-label="Patients table">
          <thead>
            <tr>
              <th>#</th>
              <th>Date In</th>
              <th>Name</th>
              <th>Age</th>
              <th>Country</th>
              <th>Gender</th>
              <th>Setting</th>
            </tr>
          </thead>
          <tbody id="patientsBody"></tbody>
        </table>
      </div>
    </section>
    <!-- SCHEDULE -->
    <section class="schedule">
      <div class="card slot">
        <div>
          <div style="font-weight:800">Heart Surgeon</div>
          <div class="time">10:00 - 11:30 • Dr. Alexandre Melin Brahamiin</div>
        </div>
        <button class="icon-btn">›</button>
      </div>
      <div class="slot primary">
        <div>
          <div style="font-weight:800">Medicine Benefit</div>
          <div class="time">12:00 - 13:00 • Dr. Henry Alhermayn</div>
        </div>
        <button class="icon-btn" style="background:#fff;">›</button>
      </div>
      <div class="card slot">
        <div>
          <div style="font-weight:800">Brain Surgeon</div>
          <div class="time">16:00 - 17:30 • Dr. Mehmet Oz</div>
        </div>
        <button class="icon-btn">›</button>
      </div>
      <div style="display:flex; gap:10px;">
        <button class="btn">Add new</button>
        <button class="icon-btn" title="See all">⋯</button>
      </div>
    </section>

  </section>
</main>

  </div>

<script>
    // -----------------------------------------
    // Dark mode toggle (with localStorage)
    // -----------------------------------------
    const toggle = document.getElementById('darkToggle');
    const saved = localStorage.getItem('dash-theme');
    if(saved === 'dark'){ document.body.classList.add('dark'); toggle.checked = true; }
    toggle.addEventListener('change', () => {
      document.body.classList.toggle('dark', toggle.checked);
      localStorage.setItem('dash-theme', toggle.checked ? 'dark' : 'light');
    });

    // -----------------------------------------
    // Sidebar collapse (mini interaction)
    // -----------------------------------------
    const collapseBtn = document.getElementById('collapseBtn');
    const app = document.querySelector('.app');
    let collapsed = false;
    collapseBtn.addEventListener('click', ()=>{
      collapsed = !collapsed;
      app.style.gridTemplateColumns = collapsed ? '64px 1fr' : '88px 1fr';
    });

    // -----------------------------------------
    // Counter animation for stats
    // -----------------------------------------
    const counters = document.querySelectorAll('[data-count]');
    const ease = (t)=>1- Math.pow(1-t, 3);
    const animateCounter = (el, to) => {
      const start = performance.now();
      const dur = 1000 + Math.random()*600;
      const from = 0;
      const step = (now)=>{
        const p = Math.min(1, (now - start)/dur);
        const val = Math.round(from + (to-from)*ease(p));
        el.textContent = val.toLocaleString();
        if(p<1) requestAnimationFrame(step);
      };
      requestAnimationFrame(step);
    };
    counters.forEach(el => animateCounter(el, +el.dataset.count));

    // -----------------------------------------
    // Calendar (dynamic render)
    // -----------------------------------------
    const calTitle = document.getElementById('calTitle');
    const calDays = document.getElementById('calDays');
    const calWeekdays = document.getElementById('calWeekdays');
    const prevBtn = document.getElementById('prevMonth');
    const nextBtn = document.getElementById('nextMonth');

    const WEEKDAYS = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
    WEEKDAYS.forEach(w => {
      const d = document.createElement('div');
      d.textContent = w; d.className='cell'; d.style.fontWeight='700'; d.style.opacity='.75';
      calWeekdays.appendChild(d);
    });

    let anchor = new Date();
    function renderCalendar(date){
      const y = date.getFullYear();
      const m = date.getMonth();
      const first = new Date(y, m, 1);
      const last = new Date(y, m+1, 0);
      const today = new Date();

      calTitle.textContent = date.toLocaleString('en-US', { month:'long', year:'numeric' });
      calDays.innerHTML = '';

      // Determine index of Monday-based calendar
      const startIndex = (first.getDay()+6)%7; // Mon=0

      // Previous month days to fill
      for(let i=startIndex; i>0; i--){
        const d = new Date(y, m, 1 - i);
        const cell = document.createElement('div');
        cell.className = 'cell muted-day';
        cell.textContent = d.getDate();
        calDays.appendChild(cell);
      }

      // Current month
      for(let day=1; day<=last.getDate(); day++){
        const cell = document.createElement('div');
        cell.className = 'cell';
        cell.textContent = day;
        if(today.getFullYear()===y && today.getMonth()===m && today.getDate()===day){
          cell.classList.add('today');
        }
        calDays.appendChild(cell);
      }

      // Next month padding
      const totalCells = calDays.children.length;
      const pad = (Math.ceil(totalCells/7)*7) - totalCells;
      for(let i=1; i<=pad; i++){
        const cell = document.createElement('div');
        cell.className='cell muted-day';
        cell.textContent = i;
        calDays.appendChild(cell);
      }
    }
    prevBtn.addEventListener('click', ()=>{ anchor = new Date(anchor.getFullYear(), anchor.getMonth()-1, 1); renderCalendar(anchor); });
    nextBtn.addEventListener('click', ()=>{ anchor = new Date(anchor.getFullYear(), anchor.getMonth()+1, 1); renderCalendar(anchor); });
    renderCalendar(anchor);

    // -----------------------------------------
    // Patients data (dynamic mock)
    // -----------------------------------------
    const patients = [
      // { date:'05.07.2021', name:'Angelica Monica', age:29, country:'USA', gender:'Female' },
      // { date:'06.07.2021', name:'Stavelin Genicity', age:38, country:'England', gender:'Male' },
      // { date:'12.07.2021', name:'Allen Humanityle', age:52, country:'USA', gender:'Female' },
      // { date:'20.07.2021', name:'Marcus Fioscanelli', age:23, country:'Germany', gender:'Male' },
      // { date:'23.07.2021', name:'Muhammed Fatih', age:28, country:'India', gender:'Male' }
    ];
    const tbody = document.getElementById('patientsBody');
    patients.forEach((p, i)=>{
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td>${String(i+1).padStart(2,'0')}</td>
        <td>${p.date}</td>
        <td>${p.name}</td>
        <td>${p.age}</td>
        <td>${p.country}</td>
        <td>${p.gender}</td>
        <td>
          <button class="icon-btn" title="Edit">✏</button>
          <button class="icon-btn" title="Delete">🗑</button>
        </td>`;
      tbody.appendChild(tr);
    });

    // -----------------------------------------
    // Tiny interactions
    // -----------------------------------------
    document.getElementById('notifBtn').addEventListener('click', ()=>{
      alert('No new notifications.');
    });
    document.getElementById('searchInput').addEventListener('input', (e)=>{
      const q = e.target.value.toLowerCase();
      [...tbody.children].forEach(tr=>{
        const hit = tr.textContent.toLowerCase().includes(q);
        tr.style.display = hit ? '' : 'none';
      });
    });
</script>
</body>
</html>