<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'patient') {
    header("Location: ../auth/login.php");
    exit();
}

$user_name = $_SESSION['user_name'];
$user_email = "patient@example.com"; 
$user_image = "../../../public/assets/uploads/users/" . ($_SESSION['user_image'] ?? "default.jpg");
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Patient Dashboard</title>
    <link rel="shortcut icon" href="../../../public/assets/images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.css">
<style>
/** <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<Google Font Link>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> */
@import url(https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;600;700&display=swap);
:root{
    --bg:#f5f7fb; 
    --surface:#ffffff; 
    --text:#0f172a; 
    --muted:#6b7280; 
    --line:#e5e7eb; 
    --primary:#3b82f6; 
    --accent:#0ea5e9;
    --c-1:#22c55e; 
    --c-2:#f59e0b; 
    --c-3:#ef4444; 
    --c-4:#38bdf8;
    --shadow:0 10px 25px rgba(2,8,23,.06);
}
:root[data-theme="dark"]{
    --bg:#0b1220; 
    --surface:#0f172a; 
    --text:#e5e7eb; 
    --muted:#9aa4b2; 
    --line:#1f2937; 
    --primary:#60a5fa; 
    --accent:#22d3ee;
    --shadow:0 8px 20px rgba(0,0,0,.35);
}
/*? All Dark Color */
:root{
    --BackgroundColor: #020d37;
    --SidebarColor: #0a4174;
    --CardColor: #49769f;
    --ButtonColor: #2bbde8;
    --ScrollbarBackgroundColor: #6ea2b3;
    --ScrollbarHoverColor: #145b70;
    --TextColor: #bdd8e9;
    --AllBorderColor: #021358;
}
:root{
    --my:#E38404;
    --color:#29121A;
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
/* Layout */
/* .AD_sidebar{
    position:fixed;
    inset:0 auto 0 0;
    width:260px;
    background:var(--surface);
    border-right:1px solid var(--line);
    padding:14px;
    display:flex;
    flex-direction:column;
    overflow-y: auto;
    gap:10px;
    z-index:30;
} */
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
.bg-teal{
    background:#14b8a6;
}
.bg-amber{
    background:#14b8a6;
}
.bg-rose{
    background:#14b8a6;
}
.bg-sky{
    background:#38bdf8;
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

/* Icons (using masks) */
/* .i{--svg:none;display:inline-block;width:18px;height:18px;background:currentColor;-webkit-mask:var(--svg) no-repeat center/contain;mask:var(--svg) no-repeat center/contain}
.i-overview{--svg:url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="%23000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>')}
.i-user{--svg:url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="%23000" stroke-width="2"><path d="M20 21a8 8 0 1 0-16 0"/><circle cx="12" cy="7" r="4"/></svg>')}
.i-patient{--svg:url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="%23000" stroke-width="2"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>')}
.i-dept{--svg:url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="%23000" stroke-width="2"><path d="M3 3h7v7H3zM14 3h7v7h-7zM14 14h7v7h-7zM3 14h7v7H3z"/></svg>')}
.i-appoint{--svg:url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="%23000" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>')}
.i-pharma{--svg:url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="%23000" stroke-width="2"><path d="M7 10h10a4 4 0 0 1 0 8H7a4 4 0 1 1 0-8z"/><path d="M7 10V6a4 4 0 1 1 8 0v4"/></svg>')}
.i-payment{--svg:url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="%23000" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>')}
.i-report{--svg:url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="%23000" stroke-width="2"><path d="M9 3v18M9 3h9a3 3 0 0 1 3 3v12a3 3 0 0 1-3 3H9"/><path d="M9 7h12M9 12h12M9 17h12"/></svg>')}
.i-notice{--svg:url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="%23000" stroke-width="2"><path d="M18 8a6 6 0 1 0-12 0v5l-2 2h16l-2-2z"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>')}
.i-settings{--svg:url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="%23000" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.6 1.65 1.65 0 0 0 10.51 4H11a2 2 0 1 1 4 0v.09c0 .64.38 1.22.97 1.49.59.27 1.28.15 1.76-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06c-.48.48-.6 1.17-.33 1.76.27.59.85.97 1.49.97H21a2 2 0 1 1 0 4h-.09c-.64 0-1.22.38-1.49.97z"/></svg>')}
.i-search{--svg:url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="%23000" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>')}
.i-bell{--svg:url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="%23000" stroke-width="2"><path d="M18 8a6 6 0 1 0-12 0v5l-2 2h16l-2-2z"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>')}
.i-sun{--svg:url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="%23000" stroke-width="2"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/></svg>')}
.i-doctor{--svg:url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="%23000" stroke-width="2"><circle cx="12" cy="7" r="4"/><path d="M6 21v-4a6 6 0 0 1 12 0v4"/></svg>')}
.i-staff{--svg:url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="%23000" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-4-4h-1"/><circle cx="16" cy="7" r="4"/></svg>')}
.i-patients{--svg:url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="%23000" stroke-width="2"><path d="M7 21v-2a4 4 0 0 1 4-4h2a4 4 0 0 1 4 4v2"/><circle cx="12" cy="7" r="4"/><path d="M5.5 8.5a3.5 3.5 0 1 1 5-4.9"/></svg>')}
.i-pharm{--svg:url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="%23000" stroke-width="2"><path d="M7 10h10a4 4 0 0 1 0 8H7a4 4 0 1 1 0-8z"/><path d="M7 10V6a4 4 0 1 1 8 0v4"/></svg>')} */

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
/* @media (max-width: 760px){
    .app{
        margin-left:0;
    }
    .only-mobile{
        display:inline-flex;
    }
    .AD_sidebar{
        transform:translateX(-100%);
        transition:transform .3s ease;
    }
    .AD_sidebar.open{
        transform:translateX(0);
    }
} */

/* Toggle Button */
/* .switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 25px;
} */

/* .switch input {
    opacity: 0;
    width: 0;
    height: 0;
} */

/* .AD_sidebar {
    position: absolute;
    cursor: pointer;
    background-color: #ccc;
    transition: .4s;
    border-radius: 34px;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
} */

/* .AD_sidebar:before {
    position: absolute;
    content: "";
    height: 19px;
    width: 19px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}*/

/* input:checked + .slider {
    background-color: #4CAF50;
}

input:checked + .slider:before {
    transform: translateX(24px);
}  */

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

/* Default Light Theme
body {
    background-color: #f5f6fa;
    color: #333;
    transition: background 0.3s ease, color 0.3s ease;
} */

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
    width: 65px;                 /* icon-only */
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
/* .AD_menu_divider{
    width: 65px;
} */
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
    background: rgba(2, 8, 23, 0.18);     /* light dim */
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
            <a class="AD_menu_item" href="admission_form.php"><i class="icon_size ri-user-3-line"></i><span>Admission Now</span></a>
            <div class="AD_menu_divider"></div>
            <a class="AD_menu_item" href="appointments.php"><i class="icon_size ri-calendar-line"></i><span>Appointment Now</span></a>
            <div class="AD_menu_divider"></div>
            <a class="AD_menu_item" href="../admin/pharmacy/pharmacy.php"><i class="icon_size ri-store-line"></i><span>Pharmacy</span></a>
            <div class="AD_menu_divider"></div>
            <a class="AD_menu_item" href="billing.php"><i class="icon_size ri-bill-line"></i><span>Payment</span></a>
            <div class="AD_menu_divider"></div>
            <a class="AD_menu_item" href="../messages/chat.php"><i class="icon_size ri-message-3-line"></i><span>Message</span></a>
            <div class="AD_menu_divider"></div>
            <a class="AD_menu_item" href="reports.php"><i class="icon_size ri-article-line"></i><span>My Report</span></a>
            <div class="AD_menu_divider"></div>
            <a class="AD_menu_item" href="#"><i class="icon_size ri-notification-3-line"></i><span>Notice</span></a>
            <div class="AD_menu_divider"></div>
            <a class="AD_menu_item" href="dashboard_settings.php"><i class="icon_size ri-settings-5-line"></i><span>Settings</span></a>
            <div class="AD_menu_divider"></div>
            <!-- Dark Mode Switch -->
                <label class="switch">
                    <input type="checkbox" id="darkModeToggle">
                    <span class="slider round"></span>
                    <span class="mode_title">Dark Mode Setting</span>
                </label>
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
                <div class="profile">
                    <!-- <img src="../../../public/assets/images/logo.png" alt="Dr." /> -->
                    <img src="<?php echo '../../../public/assets/uploads/'.($u['profile_picture'] ?: 'default.jpg'); ?>" alt="">
                    <div class="meta">
                        <strong>Dr.<?php echo $_SESSION['user_name']; ?></strong>
                        <small>Patient</small>
                    </div>
                </div>
            </div>
        </header>
        <main class="content"> 
            <!-- KPI Cards -->
            <section class="kpis">
                <article class="card kpi" id="Cards">
                    <div class="kpi-icon bg-teal">
                        <i class="i i-doctor"></i>
                    </div>
                    <div class="kpi-main">
                        <p>Dashboard</p>
                    </div>
                </article>
                <article class="card kpi" id="Cards">
                    <div class="kpi-icon bg-amber">
                        <i class="icon_size ri-nurse-line"></i>
                    </div>
                    <div class="kpi-main">
                        <p>Doctor</p>
                    </div>
                </article>
                <article class="card kpi" id="Cards">
                    <div class="kpi-icon bg-rose">
                        <i class="icon_size ri-user-3-line"></i>
                    </div>
                    <div class="kpi-main">
                        <p>Patients</p>
                    </div>
                </article>
                <article class="card kpi" id="Cards">
                    <div class="kpi-icon bg-sky">
                        <i class="icon_size ri-product-hunt-line"></i>
                    </div>
                    <div class="kpi-main">
                        <p>Total Product</p>
                    </div>
                </article>
                <article class="card kpi" id="Cards">
                    <div class="kpi-icon bg-teal">
                        <i class="i i-doctor"></i>
                    </div>
                    <div class="kpi-main">
                        <p>Dashboard</p>
                    </div>
                </article>
                <article class="card kpi" id="Cards">
                    <div class="kpi-icon bg-amber">
                        <i class="i i-staff"></i>
                    </div>
                    <div class="kpi-main">
                        <p>Doctor</p>
                    </div>
                </article>
                <article class="card kpi" id="Cards">
                    <div class="kpi-icon bg-rose">
                        <i class="i i-patients"></i>
                    </div>
                    <div class="kpi-main">
                        <p>Patients</p>
                    </div>
                </article>
                <article class="card kpi" id="Cards">
                    <div class="kpi-icon bg-sky">
                        <i class="i i-pharm"></i>
                    </div>
                    <div class="kpi-main">
                        <p>Total Product</p>
                    </div>
                </article>
            </section>
            <section class="grid">
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
    <!-- <script src="../../../public/assets/js/dashboard.js"></script> -->
<!-- <script src="../../../public/assets/js/main.js"></script> -->
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

  /* ===========================
     Responsive Adjustments
  ============================ */
  // window.addEventListener("resize", () => {
  //   if (window.innerWidth > 768) {
  //     sidebar.classList.remove("open");
  //   }
  // });

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
