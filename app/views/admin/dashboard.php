<?php
require_once "../../../config/config.php";
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}
$sql_doctor = "SELECT COUNT(*) AS total_doctors FROM users WHERE role='doctor'";
$sql_patient = "SELECT COUNT(*) AS total_patients FROM users WHERE role='patient'";
$sql_admin   = "SELECT COUNT(*) AS total_admins FROM users WHERE role='admin'";

$doctors  = $conn->query($sql_doctor)->fetch_assoc()['total_doctors'];
$patients = $conn->query($sql_patient)->fetch_assoc()['total_patients'];
$admins   = $conn->query($sql_admin)->fetch_assoc()['total_admins'];
//? 🔹 Product Count Query
$sql = "SELECT COUNT(*) AS total_products FROM products";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$products = $row['total_products'];
//? Count Birth, Accident, Death
$birth = $conn->query("SELECT COUNT(*) as total FROM cases WHERE type='birth'")->fetch_assoc()['total'];
$accident = $conn->query("SELECT COUNT(*) as total FROM cases WHERE type='accident'")->fetch_assoc()['total'];
$death = $conn->query("SELECT COUNT(*) as total FROM cases WHERE type='death'")->fetch_assoc()['total'];
//? Total cases
$total = $birth + $accident + $death;
//? Calculate %
$p1 = $total > 0 ? round(($birth / $total) * 100) : 0;
$p2 = $total > 0 ? round(($accident / $total) * 100) : 0;
$p3 = $total > 0 ? round(($death / $total) * 100) : 0;

$birth = $conn->query("SELECT COUNT(*) AS total FROM cases WHERE type='birth'")->fetch_assoc()['total'];
$accident = $conn->query("SELECT COUNT(*) AS total FROM cases WHERE type='accident'")->fetch_assoc()['total'];
$death = $conn->query("SELECT COUNT(*) AS total FROM cases WHERE type='death'")->fetch_assoc()['total'];


// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query for all counts
$sql = "SELECT 
            (SELECT COUNT(*) FROM users) AS total_users,
            (SELECT COUNT(*) FROM patient_admissions) AS total_patient_admissions,
            (SELECT COUNT(*) FROM departments) AS total_departments,
            (SELECT COUNT(*) FROM appointments) AS total_appointments";
            
$result = $conn->query($sql);
$data = $result->fetch_assoc();

$total_users = $data['total_users'];
$total_patient_admissions = $data['total_patient_admissions'];
$total_departments = $data['total_departments'];
$total_appointments = $data['total_appointments'];

$conn->close();


?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Hospital Management System|Dashboard</title>
    <link rel="shortcut icon" href="../../../public/assets/images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.css">
<style>
@import url(https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;600;700&display=swap);
:root{
    --bg: #f5f7fb; 
    --surface: #ffffff; 
    --text: #0f172a; 
    --muted: #6b7280; 
    --line: #e5e7eb; 
    --primary: #3b82f6; 
    --accent: #0ea5e9;
    --c-1: #22c55e; 
    --c-2: #f59e0b; 
    --c-3: #ef4444; 
    --c-4: #38bdf8;
    --shadow: 0 10px 25px rgba(2,8,23,.06);
}
:root[data-theme="dark"]{
    --bg: #0b1220; 
    --surface: #0f172a; 
    --text: #e5e7eb; 
    --muted: #9aa4b2; 
    --line: #1f2937; 
    --primary: #60a5fa; 
    --accent: #22d3ee;
    --shadow: 0 8px 20px rgba(0,0,0,.35);
}
/*? All Dark Color */
:root{
    --BackgroundColor: #020d37;
    --SidebarColor: #0e0a74ff;
    --CardColor: #0044f1ff;
    --ButtonColor: #0b0070ff;
    --ScrollbarBackgroundColor: #2417d7ff;
    --ScrollbarHoverColor: #010567ff;
    --AllIconColor: #000484ff;
    --TextColor: #bdd8e9ff;
    --AllBorderColor: #021358;
}
:root{
    --my: #E38404;
    --color: #29121A;
}
*{
    box-sizing:border-box;
}
html,body{
    height:100%;
}
body{
    margin:0;
    font-family:'Rajdhani', sans-serif;
    background:var(--bg);
    color:var(--text);
}

.AD_sidebar::-webkit-scrollbar {
    width: 4px;
    transition: 0.3s ease-in-out;
}
.AD_sidebar::-webkit-scrollbar-thumb {
    background: var(--CardColor); 
    border-radius: 4px;
    cursor: pointer;
    transition: 0.3s ease-in-out;
}
body::-webkit-scrollbar-thumb:hover {
    background: var(--c-4);
}
body::-webkit-scrollbar {
    width: 4px;
    transition: 0.3s ease-in-out;

}
body::-webkit-scrollbar-thumb {
    background: var(--CardColor); 
    border-radius: 4px;
    cursor: pointer;
    transition: 0.3s ease-in-out;
}
body::-webkit-scrollbar-thumb:hover {
    background: var(--c-4);
}
.AD_sidebar .AD_brand{
    display:flex;
    align-items:center;
    gap:10px;
    padding:8px 0px;
}
.logo{
    width:45px;
    height:45px;
}
.AD_logo_text{
    font-weight:700;
}
.only-mobile{
    display:none;
}
.menu{
    display:flex;
    flex-direction:column;
    gap:6px;
}
.AD_menu_item{
    display:flex;
    align-items:center;
    gap:12px;
    padding:10px 6px;
    border-radius:10px;
    color:var(--muted);
    text-decoration:none;
}
.AD_menu_item:hover{
    background:rgba(99,102,241,.08);
    color:var(--text);
}
.AD_menu_item.active{
    background:#eaf2ff;
    color:var(--primary);
}
:root[data-theme="dark"] .AD_menu_item.active{
    background:#0b1a33;
}
.AD_menu_divider{
    height:1px;
    background:var(--line);
    margin:8px 0;
}
.AD_create_box{
    margin-top:auto;
    background:linear-gradient(180deg,#eef6ff,transparent);
    border:1px dashed var(--line);
    border-radius:16px;
    padding:14px;
    text-align:center;
}
:root[data-theme="dark"] .AD_create_box{
    background:linear-gradient(180deg,#0b1a33,transparent);
}
.AD_create_box .AD_cloud{
    width:52px;
    height:36px;
    border-radius:12px;
    background:var(--bg);
    margin:8px auto 10px;
    box-shadow:var(--shadow);
}
.btn{
    border:0;
    padding:10px 12px;
    border-radius:10px;
    cursor:pointer;
}
.btn.primary{
    background:var(--primary);
    color:#fff;
}
.app{
    margin-left:60px;
    min-height:100vh;
}
.topbar{
    position:sticky;
    top:0;
    background:var(--surface);
    border-bottom:1px solid var(--line);
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:14px 20px;
    z-index:20;
}
.search{
    display:flex;
    align-items:center;
    gap:10px;
    background:var(--bg);
    border:1px solid var(--line);
    padding:8px 12px;
    border-radius:12px;
    min-width:280px;
    flex:1;
    max-width:580px;
}
.search input{
    background:transparent;
    border:0;
    outline:0;
    color:var(--text);
    width:100%;
}
.top-actions{
    display:flex;
    align-items:center;
    gap:10px;
}
.admin_icon_btn{
    border:1px solid var(--line);
    background:var(--surface);
    border-radius:12px;
    padding:8px;
    cursor:pointer;
}
.profile{
    display:flex;
    align-items:center;
    gap:10px;
    margin-left:6px;
}
.profile img{
    width:36px;
    height:36px;
    border-radius:50%;
    object-fit:cover;
}
.profile .meta{
    display:flex;
    flex-direction:column;
    line-height:1;
}
.profile .meta small{
    color:var(--muted);
}

.content{
    padding:18px;
}
.kpis{
    display:grid;
    gap:16px;
    margin-bottom:16px;
}
.card{
    background:var(--surface);
    border:1px solid var(--line);
    border-radius:16px;
    box-shadow:var(--shadow);
    padding:16px;
}
.kpi{
    display:flex;
    gap:14px;
    align-items:center;
}
.kpi-icon{
    width:46px;
    height:46px;
    border-radius:12px;
    display:grid;
    place-items:center;
    color:#fff;
}
.bg-teal, .bg-amber, .bg-rose, .bg-sky{
    background:var(--AllIconColor);
}

.kpi-main h3{
    margin:0;
    font-size:24px;
    color: var(--TextColor);
}
.kpi-main p{
    margin:2px 0 6px;
    color:var(--c-2);
}
.muted{
    color:var(--muted);
}
.accent{
    color:var(--accent);
    font-weight:600;
}

.grid{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:16px;
}
.span-2{
    grid-column:span 2;
}
.card-head{
    display:flex;
    align-items:center;
    justify-content:space-between;
    margin-bottom:8px;
}
.card-head h4{
    margin:0;
    font-weight:600;
}
.view-all{
    color:var(--primary);
    text-decoration:none;
    font-weight:500;
}
/* Card Styling */
.analytics-card {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    max-width: 400px;
    margin: 20px auto;
    font-family: 'Arial', sans-serif;
}
.analytics-card .card-head h4 {
    margin: 0 0 15px 0;
    font-size: 18px;
    color: #1e293b;
    text-align: center;
}
/* Donut Chart */
.donut-wrap {
    display: flex;
    gap: 25px;
    align-items: center;
    justify-content: center;
}
.donut {
    --p1: 45; /* default values */
    --p2: 18;
    --p3: 29;
    width: 140px;
    height: 140px;
    border-radius: 50%;
    background: conic-gradient(
        #22c55e 0% calc(var(--p1) * 1%),
        #f59e0b calc(var(--p1) * 1%) calc((var(--p1) + var(--p2)) * 1%),
        #ef4444 calc((var(--p1) + var(--p2)) * 1%) calc((var(--p1) + var(--p2) + var(--p3)) * 1%)
    );
    mask: radial-gradient(circle farthest-side, transparent 55%, #000 56%);
}
/* Legend */
.legend {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    gap: 10px;
    font-size: 14px;
    color: #334155;
}
.legend li {
    display: flex;
    align-items: center;
    gap: 6px;
}
.color_text_c1{
    color: #22c55e;
}
.color_text_c2{
    color: #f59e0b;
}
.color_text_c3{
    color: #ef4444;
}
.legend .dot {
    display: inline-block;
    width: 14px;
    height: 14px;
    border-radius: 50%;

}
.legend .c1 { background: #22c55e; }
.legend .c2 { background: #f59e0b; }
.legend .c3 { background: #ef4444; }

/* Responsive */
@media(max-width: 500px){
    .donut-wrap {
        flex-direction: column;
        gap: 20px;
    }
}

/* Report list */
.report-list{
    list-style:none;
    margin:0;
    padding:0;
    display:grid;
    gap:10px;
}
.tag{
    display:block;
    font-weight:600;
    margin-bottom:4px;
}
.tag.warning{
    color:#f59e0b;
}
.tag.info{
    color:#06b6d4;
}
.tag.danger{
    color:#ef4444;
}
.report-list small{
    color:var(--muted);
}

/* Bars */
.bars{
    list-style:none;
    margin:0;
    padding:0;
    display:grid;
    gap:10px;
}
.bars li{
    display:grid;
    grid-template-columns:140px 1fr 50px;
    align-items:center;
    gap:10px;
}
.bar{
    height:8px;
    background:var(--bg);
    border:1px solid var(--line);
    border-radius:999px;
    position:relative;
    overflow:hidden;
}
.bar::after{
    content:"";
    position:absolute;
    inset:0;
    width:0;
    background:linear-gradient(90deg,var(--primary),var(--accent));
    transition:width .8s ease;
    border-radius:inherit;
}

/* Table */
.table-wrap{
    overflow:auto;
}
.table{
    width:100%;
    border-collapse:separate;
    border-spacing:0 10px;
}
.table thead th{
    text-align:left;
    color:var(--muted);
    font-weight:600;
    font-size:14px;
}
.table tbody tr{
    background:var(--bg);
}
:root[data-theme="dark"] .table tbody tr{
    background:#0b1220;
}
.table tbody td{
    padding:12px;
    border-top:1px solid var(--line);
    border-bottom:1px solid var(--line);
}
.table tbody tr td:first-child{
    border-left:1px solid var(--line);
    border-top-left-radius:12px;
    border-bottom-left-radius:12px;
}
.table tbody tr td:last-child{
    border-right:1px solid var(--line);
    border-top-right-radius:12px;
    border-bottom-right-radius:12px;
}

/* Doctors */
.doctors{
    list-style:none;
    margin:0;
    padding:0;
    display:grid;
    gap:12px;
}
.doctors li{
    display:flex;
    align-items:center;
    gap:10px;
}
.doctors img{
    width:36px;
    height:36px;
    border-radius:50%;
    object-fit:cover;
}
.doctors small{
    color:var(--muted);
}


/* Responsive */
@media (max-width: 1100px){
    .grid{
        grid-template-columns:1fr 1fr;
    }
}
@media (max-width: 900px){
    .grid{
        grid-template-columns:1fr;
    }
    .span-2{grid-column:auto;
    }
}

.kpis {
  display: grid;
  gap: 1rem;
  grid-template-columns: repeat(4, 1fr);
}
.icon_size{
    font-size: 30px;
}
@media (max-width: 1023px) {
  .kpis {
    grid-template-columns: repeat(3, 1fr);
  }
}

@media (max-width: 767px) {
  .kpis {
    grid-template-columns: repeat(2, 1fr);
  }
  /* .meta{
    display: none;
  } */
}


/* Switch container */
.switch {
  position: relative;
  display: inline-block;
  width: 50px;
  height: 34px;
}

/* Hide default checkbox */
.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

/* Slider background */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  transition: 0.4s;
  border-radius: 34px;
}

/* Slider circle */
.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  /* left: 4px; */
  bottom: 4px;
  background-color: white;
  transition: 0.4s;
  border-radius: 50%;
}

/* Checked State */
input:checked + .slider {
  background-color: #4CAF50; /* Green background when ON */
}

input:checked + .slider:before {
  transform: translateX(20px);
}

/* Optional: Glow effect */
input:checked + .slider {
  box-shadow: 0 0 10px rgba(76, 175, 80, 0.7);
}
.mode_title{
    margin-left: 50px;
    margin-top: 70px;
}


/* Dark Theme */
body.dark-mode {
    background-color: var(--BackgroundColor);
    color: var(--TextColor);
}

/* Sidebar Dark Mode */
body.dark-mode .AD_sidebar {
    background-color: var(--SidebarColor);
    color: var(--TextColor);
}
body.dark-mode .AD_menu_item{
    color: var(--TextColor);
}
body.dark-mode .Sidebar_menu{
    color: var(--text);
}
body.dark-mode .menu {
    color: var(--TextColor);
}
body.dark-mode .topbar{
    background: var(--BackgroundColor);
    color: var(--TextColor);
}
body.dark-mode .search{
    background: var(--SidebarColor);
    border-color: var(--AllBorderColor);
    color: var(--TextColor);
}
body.dark-mode .search_input{
    color: var(--TextColor);
}
/* Card Dark Mode */
body.dark-mode .card {
    background-color: var(--ScrollbarHoverColor);
    color: var(--TextColor);
    border-color: var(--AllBorderColor);
}

/* Table Dark Mode */
body.dark-mode table {
    background-color: var(--ScrollbarHoverColor);
    color: var(--TextColor);
}

/* === Sidebar (collapsed by default) === */
.AD_sidebar {
    position: fixed;
    top: 0; left: 0;
    height: 100dvh;
    width: 70px;                 /* icon-only */
    background: var(--surface);
    border-right: 1px solid var(--line);
    padding: 14px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    overflow-x: hidden;
    white-space: nowrap;
    transition: width .25s cubic-bezier(.2,.8,.2,1);
    will-change: width;
    z-index: 1000;
    contain: paint;              /* paint isolation for smoother anim */
}

.AD_sidebar.expanded { width: 260px; }

/* Menu text animation */
.AD_sidebar .AD_menu_item span {
    opacity: 0;
    transform: translateX(-6px);
    transition: opacity .2s ease, transform .25s cubic-bezier(.2,.8,.2,1);
}
.AD_sidebar.expanded .AD_menu_item span {
    opacity: 1;
    transform: translateX(0);
}

/* === Overlay that blurs & dims content smoothly === */
.AD_overlay {
    position: fixed;
    inset: 0;
    background: rgba(7, 68, 220, 0.18);     /* light dim */
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    opacity: 0;
    pointer-events: none;
    transition: opacity .25s ease;
    z-index: 900;                         /* under sidebar (1000) */
}

/* When sidebar is open */
body.sidebar-open .AD_overlay {
    opacity: 1;
    pointer-events: auto;        
}

@supports not ((backdrop-filter: blur(6px))) {
  .app, .topbar { transition: filter .25s ease; }
  body.sidebar-open .app,
  body.sidebar-open .topbar { filter: blur(4px); }
}

/* Reduce motion users */
@media (prefers-reduced-motion: reduce) {
  * { transition: none !important; }
}
.icon_size{
    font-size: 25px;
}
</style>
</head>
<body>
<main>
    <aside class="AD_sidebar" id="AD_sidebar">
        <div class="AD_brand"> 
            <button class="AD_icon_btn only-mobile" id="sidebarToggle" aria-label="Toggle Menu">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
            </button>
            <img src="../../../public/assets/images/logo.png" alt="Hospital Management System" class="logo" />
            <span class="AD_logo_text">Hospital Management<br> System</span>
        </div>
        <nav class="AD_menu">
            <a class="AD_menu_item Sidebar_menu active" href="#"><i class="icon_size ri-dashboard-line"></i><span>Overview</span></a>
            <a class="AD_menu_item" href="manage_doctors.php"><i class="icon_size ri-user-line"></i><span>Doctor</span></a>
            <a class="AD_menu_item" href="add_doctor.php"><i class="icon_size ri-user-add-line"></i><span>Add New Doctor</span></a>
            <a class="AD_menu_item" href="add_case.php"><i class="icon_size ri-file-add-line"></i><span>Add Case</span></a>
            <a class="AD_menu_item" href="manage_all_patients.php"><i class="icon_size ri-user-3-line"></i><span>Patient</span></a>
            <a class="AD_menu_item" href="manage_admissions.php"><i class="icon_size ri-user-3-line"></i><span>Patient Admission</span></a>
            <a class="AD_menu_item" href="department.php"><i class="icon_size ri-building-2-line"></i><span>Department</span></a>
            <a class="AD_menu_item" href="add_department.php"><i class="icon_size ri-building-2-line"></i><i class="ri-add-line" style="margin-left:-20px;margin-top:-10px;"></i><span>Add Department</span></a>
            <a class="AD_menu_item" href="appointments.php"><i class="icon_size ri-calendar-line"></i><span>Appointment</span></a>
            <a class="AD_menu_item" href="pharmacy/pharmacy.php"><i class="icon_size ri-store-line"></i><span>Pharmacy</span></a>
            <a class="AD_menu_item" href="pharmacy/add_product.php"><i class="icon_size ri-store-line"></i><i class="ri-add-line" style="margin-left:-20px;margin-top:-17px;"></i><span>Add Medicine</span></a>
            <a class="AD_menu_item" href="pharmacy/inventory.php"><i class="icon_size ri-store-line"></i><i class="ri-add-line" style="margin-left:-20px;margin-top:-17px;"></i><span>Product Management</span></a>
            <a class="AD_menu_item" href="billing.php"><i class="icon_size ri-bill-line"></i><span>Payment</span></a>
            <a class="AD_menu_item" href="search_page.php"><i class="icon_size ri-bill-line"></i><span>Search Now</span></a>
            <a class="AD_menu_item" href="../messages/chat.php"><i class="icon_size ri-message-3-line"></i><span>Message</span></a>
            <div class="AD_menu_divider"></div>
            <a class="AD_menu_item" href="#"><i class="icon_size ri-article-line"></i><span>Report</span></a>
            <a class="AD_menu_item" href="#"><i class="icon_size ri-notification-3-line"></i><span>Notice</span></a>
            <a class="AD_menu_item" href="dashboard_settings.php"><i class="icon_size ri-settings-5-line"></i><span>Settings</span></a>
            <div class="AD_menu_divider"></div>
            <!-- Dark Mode Switch -->
                <label class="switch">
                    <input type="checkbox" id="darkModeToggle">
                    <span class="slider round"></span>
                    <span class="mode_title">Dark Mode Setting</span>
                </label>
            <div class="AD_menu_divider"></div>
            <div class="AD_create_box">
                <div class="AD_cloud"></div>
                <p>Add New Category or Database</p>
                <button class="btn primary" id="createBtn">+ Create New</button>
            </div>
        </nav>
    </aside>
    
    <div class="app">
        <header class="topbar">
            <div class="search">
    <form action="search_results.php" method="get" style="display:flex;align-items:center;">
        <i class="i i-search"></i>
        <input class="search_input" type="search" name="q" placeholder="Search for Files, Patient or Docs" required />
    </form>
</div>

            <div class="top-actions">
                <!-- <button class="admin_icon_btn" aria-label="Notifications">
                    <i class="i i-bell"></i>
                </button> -->
                <div class="profile">
                    <!-- <img src="../../../public/assets/images/logo.png" alt="Dr." /> -->
                    <img src="<?php echo '../../../public/assets/uploads/'.($u['profile_picture'] ?: 'default.jpg'); ?>" alt="">
                    <div class="meta">
                        <strong>Dr.<?php echo $_SESSION['user_name']; ?></strong>
                        <small>Admin</small>
                    </div>
                </div>
            </div>
        </header>
        <main class="content"> 
            <!-- KPI Cards -->
            <section class="kpis">
                <article class="card kpi">
                    <div class="kpi-icon bg-teal">
                        <i class="icon_size ri-admin-line"></i>
                    </div>
                    <div class="kpi-main">
                        <h3><?php echo $admins; ?></h3>
                        <p>Admin</p>
                        <small class="muted"><span class="accent"><?php echo $admins; ?></span> Doctors joined this week</small>
                    </div>
                </article>
                <article class="card kpi">
                    <div class="kpi-icon bg-amber">
                        <i class="icon_size ri-nurse-line"></i>
                    </div>
                    <div class="kpi-main">
                        <h3><?php echo $doctors; ?></h3>
                        <p>Doctors</p>
                        <small class="muted"><span class="accent"><?php echo $doctors; ?></span> Staffs on vacation</small>
                    </div>
                </article>
                <article class="card kpi">
                    <div class="kpi-icon bg-rose">
                        <i class="icon_size ri-user-3-line"></i>
                    </div>
                    <div class="kpi-main">
                        <h3><?php echo $patients; ?></h3>
                        <p>Patients</p>
                        <small class="muted"><span class="accent"><?php echo $patients; ?></span> New patients admitted</small>
                    </div>
                </article>
                <article class="card kpi">
                    <div class="kpi-icon bg-sky">
                        <i class="icon_size ri-capsule-fill"></i>
                    </div>
                    <div class="kpi-main">
                        <h3><?php echo $products; ?></h3>
                        <p>Total Products</p>
                        <small class="muted"><span class="accent"><?php echo $products; ?></span> Medicine on reserve</small>
                    </div>
                </article>
                <article class="card kpi">
                    <div class="kpi-icon bg-sky">
                        <i class="icon_size ri-file-user-line"></i>
                    </div>
                    <div class="kpi-main">
                        <h3><?php echo $total_users; ?></h3>
                        <p>Total Users</p>
                        <small class="muted"><span class="accent"><?php echo $total_users; ?></span> Total Users</small>
                    </div>
                </article>
                <article class="card kpi">
                    <div class="kpi-icon bg-sky">
                        <i class="icon_size ri-calendar-line"></i>
                    </div>
                    <div class="kpi-main">
                        <h3><?php echo $total_appointments; ?></h3>
                        <p>Total Appointments</p>
                        <small class="muted"><span class="accent"><?php echo $total_appointments; ?></span> Total Appointments</small>
                    </div>
                </article>
                <article class="card kpi">
                    <div class="kpi-icon bg-sky">
                        <i class="icon_size ri-building-2-line"></i>
                    </div>
                    <div class="kpi-main">
                        <h3><?php echo $total_departments; ?></h3>
                        <p>Total Departments</p>
                        <small class="muted"><span class="accent"><?php echo $total_departments; ?></span> Total Departments</small>
                    </div>
                </article>
                <article class="card kpi">
                    <div class="kpi-icon bg-sky">
                        <i class="icon_size ri-user-6-line"></i>
                    </div>
                    <div class="kpi-main">
                        <h3><?php echo $total_patient_admissions; ?></h3>
                        <p>Total Patient Admissions</p>
                        <small class="muted"><span class="accent"><?php echo $total_patient_admissions; ?></span> Total Admissions</small>
                    </div>
                </article>
            </section>
            <section class="grid">
        <!-- Birth & Death Analytics (Donut) -->
                <article class="card">
    <header class="card-head">
        <h4>Hospital Birth &amp; Death Analytics</h4>
    </header>
    <div class="donut-wrap">
        <div class="donut" style="--p1:<?= $p1 ?>; --p2:<?= $p2 ?>; --p3:<?= $p3 ?>"></div>
        <ul class="legend">
            <li class="color_text_c1"><span class="dot c1"></span> Birth Case <strong><?= $p1 ?>%</strong></li>
            <li class="color_text_c2"><span class="dot c2"></span> Accident Case <strong><?= $p2 ?>%</strong></li>
            <li class="color_text_c3"><span class="dot c3"></span> Death Case <strong><?= $p3 ?>%</strong></li>
        </ul>
    </div>
</article>
                <!-- Hospital Report (List) -->
                <article class="card">
                    <header class="card-head">
                    <h4>Hospital Report</h4>
                    <a href="#" class="view-all">View All</a>
                </header>
                <ul class="report-list">
                    <li>
                        <span class="tag warning">Room 501 AC is not working</span>
                        <small>Reported by Steve</small>
                    </li>
                    <li>
                        <span class="tag info">Daniel extended his holiday</span>
                        <small>Reported by Andrew</small>
                    </li>
                    <li>
                        <span class="tag danger">101 washroom needed to clean</span>
                        <small>Reported by Steve</small>
                    </li>
                </ul>
            </article>
            <!-- Success Stats (Bars) -->
            <article class="card">
                <header class="card-head">
                    <h4>Success Stats</h4>
                    <div class="select">
                        <select aria-label="Month select">
                            <option>May 2025</option>
                            <option>June 2024</option>
                            <option>July 2021</option>
                        </select>
                    </div>
                </header>
                <ul class="bars">
                    <li><span>Anesthetics</span><div class="bar" data-value="78"></div><b>78%</b></li>
                    <li><span>Gynecology</span><div class="bar" data-value="100"></div><b>100%</b></li>
                    <li><span>Neurology</span><div class="bar" data-value="50"></div><b>50%</b></li>
                    <li><span>Oncology</span><div class="bar" data-value="65"></div><b>65%</b></li>
                    <li><span>Orthopedics</span><div class="bar" data-value="85"></div><b>85%</b></li>
                    <li><span>Physiotherapy</span><div class="bar" data-value="99"></div><b>99%</b></li>
                </ul>
            </article>
            <!-- Online Appointment (Table) -->
            <article class="card span-2">
                <header class="card-head">
                    <h4>Online Appointment</h4>
                    <a href="#" class="view-all">View All</a>
                </header>
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Date &amp; Time</th>
                                <th>Age</th>
                                <th>Gender</th>
                                <th>Appoint for</th>
                                <th>Setting</th>
                            </tr>
                        </thead>
                        <tbody id="apptBody">
                        <!-- JS will populate -->
                        </tbody>
                    </table>
                </div>
            </article>
            <!-- Doctors List -->
            <article class="card">
                <header class="card-head">
                    <h4>Doctors List</h4>
                </header>
                <ul class="doctors" id="docList">
                    <!-- JS will populate -->
                </ul>
            </article>
        </section>
        </main>
    </div>
<!-- Minimal icon system (pure CSS masks) -->
    <svg aria-hidden="true" width="0" height="0" style="position:absolute">
        <defs></defs>
    </svg>
</main>

<script>
// dashboard.js

document.addEventListener("DOMContentLoaded", () => {
  /* ===========================
     Dark Mode Toggle
  ============================ */
  const themeToggle = document.getElementById("themeToggle");
  const prefersDark = window.matchMedia("(prefers-color-scheme: dark)").matches;

  // Initial check from localStorage
  if (localStorage.getItem("theme") === "dark" || (!localStorage.getItem("theme") && prefersDark)) {
    document.body.classList.add("dark");
  }

  themeToggle?.addEventListener("click", () => {
    document.body.classList.toggle("dark");
    if (document.body.classList.contains("dark")) {
      localStorage.setItem("theme", "dark");
    } else {
      localStorage.setItem("theme", "light");
    }
  });

  /* ===========================
     Animate KPI Bars
  ============================ */
  const bars = document.querySelectorAll(".bar");
  bars.forEach((bar) => {
    const value = bar.dataset.value;
    bar.style.width = "0%";
    setTimeout(() => {
      bar.style.width = value + "%";
    }, 300);
  });

  /* ===========================
     Animate Donut Chart
  ============================ */
  const donut = document.querySelector(".donut");
  if (donut) {
    donut.classList.add("animate");
  }

  /* ===========================
     Dummy Data for Tables & Lists
  ============================ */
  const appointments = [
    // { no: 1, name: "John Doe", datetime: "2025-08-15 10:00 AM", age: 34, gender: "Male", for: "Dental", setting: "Edit" },
    // { no: 2, name: "Jane Smith", datetime: "2025-08-15 11:30 AM", age: 28, gender: "Female", for: "Eye Check", setting: "Edit" },
    // { no: 3, name: "Michael Lee", datetime: "2025-08-15 01:00 PM", age: 45, gender: "Male", for: "Orthopedic", setting: "Edit" },
  ];

  const doctors = [
    // { name: "Dr. Emily Carter", specialty: "Cardiologist", img: "assets/avatar-1.jpg" },
    // { name: "Dr. Daniel James", specialty: "Neurologist", img: "assets/avatar-2.jpg" },
    // { name: "Dr. Sarah Johnson", specialty: "Gynecologist", img: "assets/avatar-3.jpg" },
  ];

  const apptBody = document.getElementById("apptBody");
  if (apptBody) {
    appointments.forEach(appt => {
      const row = document.createElement("tr");
      row.innerHTML = `
        <td>${appt.no}</td>
        <td>${appt.name}</td>
        <td>${appt.datetime}</td>
        <td>${appt.age}</td>
        <td>${appt.gender}</td>
        <td>${appt.for}</td>
        <td><button class="btn small">${appt.setting}</button></td>
      `;
      apptBody.appendChild(row);
    });
  }

  const docList = document.getElementById("docList");
  if (docList) {
    doctors.forEach(doc => {
      const li = document.createElement("li");
      li.innerHTML = `
        <img src="${doc.img}" alt="${doc.name}" />
        <div class="meta">
          <strong>${doc.name}</strong>
          <small>${doc.specialty}</small>
        </div>
      `;
      docList.appendChild(li);
    });
  }


});

// Dark mode toggle
const toggleBtn = document.getElementById("darkModeToggle");
const body = document.body;

// Load saved theme
if (localStorage.getItem("theme") === "dark") {
    body.classList.add("dark-mode");
    toggleBtn.checked = true;
}

// Toggle theme
toggleBtn.addEventListener("change", () => {
    if (toggleBtn.checked) {
        body.classList.add("dark-mode");
        localStorage.setItem("theme", "dark");
    } else {
        body.classList.remove("dark-mode");
        localStorage.setItem("theme", "light");
    }
});


(() => {
  const sidebar = document.getElementById('AD_sidebar');
  let overlay = document.getElementById('AD_overlay');

  // safety: না থাকলে overlay তৈরি করে দাও
  if (!overlay) {
    overlay = document.createElement('div');
    overlay.className = 'AD_overlay';
    overlay.id = 'AD_overlay';
    sidebar.insertAdjacentElement('afterend', overlay);
  }

  let closeTimer = null;

  const openSidebar = () => {
    clearTimeout(closeTimer);
    document.body.classList.add('sidebar-open');
    sidebar.classList.add('expanded');
  };

  const closeSidebar = (withDelay = true) => {
    clearTimeout(closeTimer);
    const run = () => {
      document.body.classList.remove('sidebar-open');
      sidebar.classList.remove('expanded');
    };
    if (withDelay) {
      // ছোট delay = edge hover flicker কমে
      closeTimer = setTimeout(run, 120);
    } else {
      run();
    }
  };

  // Hover intent (desktop)
  sidebar.addEventListener('mouseenter', openSidebar);
  sidebar.addEventListener('mouseleave', () => closeSidebar(true));

  // Overlay ক্লিক করলে বন্ধ
  overlay.addEventListener('click', () => closeSidebar(false));

  // ESC চাপলে বন্ধ
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeSidebar(false);
  });

  // Touch devices-এ tap করলে open/close
  const isTouch = matchMedia('(hover: none)').matches;
  if (isTouch) {
    sidebar.addEventListener('click', () => {
      if (sidebar.classList.contains('expanded')) {
        closeSidebar(false);
      } else {
        openSidebar();
      }
    });
  }
})();
</script>
</body>
</html>