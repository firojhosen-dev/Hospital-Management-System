<?php
session_start();
// যদি ইউজার লগইন করা থাকে তবে সরাসরি dashboard এ পাঠাও
if (isset($_SESSION['user_id'])) {
    header("Location: app/views/dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hospital Management System</title>
      <link rel="shortcut icon" href="assets/images/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.css">
  <style>
    /* Reset */
    *{margin:0;padding:0;box-sizing:border-box;font-family:Arial, sans-serif;}
    body{background:#f5f7fb;color:#0f172a;line-height:1.6;}

    /* Navbar */
    nav{
      width:100%;
      background:#ffffff;
      box-shadow:0 2px 5px rgba(0,0,0,0.1);
      position:fixed;
      top:0;left:0;
      z-index:1000;
    }
    .nav-container{
      max-width:1200px;
      margin:auto;
      display:flex;
      justify-content:space-between;
      align-items:center;
      padding:15px 20px;
    }
    .logo{font-size:22px;font-weight:bold;color:#0077cc;}
    .menu{
      display:flex;
      gap:20px;
    }
    .menu a{
      text-decoration:none;
      color:#0f172a;
      font-weight:500;
      transition:0.3s;
    }
    .menu a:hover{color:#0077cc;}
    .btn{
      background:#0077cc;
      color:#fff !important;
      padding:8px 16px;
      border-radius:6px;
      font-weight:600;
      transition:0.3s;
    }
    .btn:hover{background:#005fa3;}

    /* Hamburger for mobile */
    .hamburger{
      display:none;
      flex-direction:column;
      cursor:pointer;
      gap:5px;
    }
    .hamburger span{
      width:25px;height:3px;
      background:#333;border-radius:5px;
    }

    /* Hero Section */
    .hero{
      height:100vh;
      display:flex;
      flex-direction:column;
      justify-content:center;
      align-items:center;
      text-align:center;
      padding:0 20px;
      background:linear-gradient(to right,#e0f2ff,#f5f7fb);
    }
    .hero h1{font-size:40px;margin-bottom:20px;color:#0f172a;}
    .hero p{max-width:600px;font-size:18px;color:#444;margin-bottom:30px;}
    .hero .buttons a{margin:0 10px;}
#features ul{
  margin-left:40px;
  list-style:circle;
}
#features ol{
  list-style: none;
  margin-left:40px;
}
    /* Sections */
    section{max-width:1000px;margin:auto;padding:60px 20px;}
    section h2{font-size:28px;margin-bottom:15px;color:#0077cc;}
/* Custom Scrollbar */
::-webkit-scrollbar {
  width: 10px;            /* Scrollbar এর width */
}
::-webkit-scrollbar-track {
  background: #f1f1f1;     /* Track এর background */
}
::-webkit-scrollbar-thumb {
  background: #0077cc;     /* Scrollbar এর রঙ */
  border-radius: 5px;      /* Round effect */
}
::-webkit-scrollbar-thumb:hover {
  background: #005fa3;     /* Hover করলে গাঢ় রঙ হবে */
}

/* Firefox Support */
* {
  scrollbar-width: thin;
  scrollbar-color: #0077cc #f1f1f1;
}
    /* Footer */
    footer{
      background:#fff;
      text-align:center;
      padding:15px;
      border-top:1px solid #e5e7eb;
      font-size:14px;
      color:#6b7280;
    }

    /* Responsive */
    @media(max-width:768px){
      .menu{
        display:none;
        flex-direction:column;
        background:#fff;
        position:absolute;
        top:60px;right:20px;
        width:200px;
        padding:10px;
        box-shadow:0 5px 15px rgba(0,0,0,0.1);
      }
      .menu.active{display:flex;}
      .hamburger{display:flex;}
      .hero h1{font-size:28px;}
      .hero p{font-size:16px;}
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <nav>
    <div class="nav-container">
      <div class="logo">🏥 HMS Software</div>
      <div class="menu" id="menu">
        <a href="#about">About</a>
        <a href="#features">Features</a>
        <a href="../app/views/auth/login.php" class="btn">Login</a>
        <a href="../app/views/auth/register.php" class="btn">Register</a>
      </div>
      <div class="hamburger" id="hamburger">
        <span></span><span></span><span></span>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="hero">
    <h1>Welcome to Hospital Management System</h1>
    <p>
      Our Hospital Management System helps you manage patients, doctors, staff, 
      appointments and medical records in a smart and efficient way.
    </p>
    <div class="buttons">
      <a href="../app/views/auth/login.php" class="btn">Login</a>
      <a href="../app/views/auth/register.php" class="btn">Register</a>
    </div>
  </section>

  <!-- About -->
  <section id="about">
    <h2>About Our Software</h2>
    <p>
      This Hospital Management System is developed to make hospital operations digital. 
      It helps in patient registration, doctor scheduling, billing, pharmacy management, 
      and report generation. Our software is secure, user-friendly, and customizable.
    </p>
  </section>

  <!-- Features -->
  <section id="features">
    <h2><i class="ri-checkbox-circle-line"></i> Features</h2>
    <ol>
      <li><i class="ri-checkbox-circle-line"></i> User Management</li>
      <li><i class="ri-checkbox-circle-line"></i> Doctor Management</li>
      <li><i class="ri-checkbox-circle-line"></i> Patient Management</li>
      <li><i class="ri-checkbox-circle-line"></i> Appointment Management</li>
      <li><i class="ri-checkbox-circle-line"></i> Pharmacy & Inventory Management</li>
      <li><i class="ri-checkbox-circle-line"></i> Laboratory Management</li>
      <li><i class="ri-checkbox-circle-line"></i> Billing & Accounting</li>
      <li><i class="ri-checkbox-circle-line"></i> Inpatient (IPD) Management</li>
      <li><i class="ri-checkbox-circle-line"></i> Reports & Analytics</li>
      <li><i class="ri-checkbox-circle-line"></i> Communication System</li>
      <li><i class="ri-checkbox-circle-line"></i> Security & Compliance</li>
      <li><i class="ri-checkbox-circle-line"></i> Modern Features</li>
    </ol>
    <h2><i class="ri-checkbox-circle-line"></i> User Management</h2>
    <ul>
      <li>User Roles & Permissions (Admin, Doctor, Nurse, Receptionist, Accountant, Patient, Pharmacist, Lab Technician etc)</li>
      <li>Profile Management (name, email, mobile, profile picture, etc.)</li>
      <li>Secure Authentication (Login, Signup, JWT/Session, Password Reset, 2FA)</li>
      <li>Multi-tab Session Management (Restricting a user from logging in on multiple devices/tabs simultaneously.)</li>
    </ul>
    <h2><i class="ri-checkbox-circle-line"></i> Doctor Management</h2>
    <ul>
      <li>Add / Edit / Delete Doctors</li>
      <li>Doctor Profile (Specialization, Experience, Schedule, Availability)</li>
      <li>Doctor Dashboard (Appointments, Patients, Messages, Prescriptions)</li>
      <li>Messaging with Patients/Admin</li>
      <li>Prescription Writing & History</li>
    </ul>
    <h2><i class="ri-checkbox-circle-line"></i> Patient Management</h2>
    <ul>
      <li>Patient Registration (Personal info, Medical History)</li>
      <li>Patient Dashboard (Appointments, Prescriptions, Bills, Reports)</li>
      <li>Profile Update (with picture, contact info)</li>
      <li>Online Medical Records (EHR - Electronic Health Records)</li>
      <li>Patient Communication (Chat, Notifications, Email/SMS Alerts)</li>
    </ul>
    <h2><i class="ri-checkbox-circle-line"></i> Appointment Management</h2>
    <ul>
      <li>Book / Reschedule / Cancel Appointments</li>
      <li>Doctor-wise Schedule & Availability</li>
      <li>Calendar View (Day/Week/Month)</li>
      <li>Appointment Reminders (SMS/Email/Push)</li>
      <li>Priority / Emergency Appointment</li>
    </ul>
    <h2><i class="ri-checkbox-circle-line"></i> Pharmacy & Inventory Management</h2>
    <ul>
      <li>Medicine Catalog (Name, Brand, Price, Expiry Date)</li>
      <li>Stock Management (In/Out, Expired Drugs Alert)</li>
      <li>Pharmacy Billing & Invoice</li>
      <li>Supplier Management</li>
      <li>Auto Low Stock Notification</li>
    </ul>
    <h2><i class="ri-checkbox-circle-line"></i> Laboratory Management</h2>
    <ul>
      <li>Test Requests from Doctors</li>
      <li>Lab Test Reports Upload & Sharing</li>
      <li>Report Delivery to Patient Portal</li>
      <li>Lab Inventory (Reagents, Test Kits, Consumables)</li>
      <li>Integration with Billing</li>
    </ul>
    <h2><i class="ri-checkbox-circle-line"></i> Billing & Accounting</h2>
    <ul>
      <li>OPD/IPD Billing (Outpatient/Inpatient)</li>
      <li>Automated Invoice Generation (PDF)</li>
      <li>Payment Integration (SSLCommerz, Stripe, PayPal, etc.)</li>
      <li>Insurance Management</li>
      <li>Expense Tracking (salaries, hospital costs)</li>
      <li>Financial Reports (Income, Expenses, Profit/Loss)</li>
    </ul>
    <h2><i class="ri-checkbox-circle-line"></i> Inpatient (IPD) Management</h2>
    <ul>
      <li>Bed & Ward Allocation</li>
      <li>Admission & Discharge Management</li>
      <li>Nursing Notes</li>
      <li>Patient Daily Progress & Treatment History</li>
      <li>Room Charges Auto Calculation</li>
    </ul>
    <h2><i class="ri-checkbox-circle-line"></i> Reports & Analytics</h2>
    <ul>
      <li>Appointment Reports</li>
      <li>Patient Reports</li>
      <li>Revenue Reports (daily, monthly, yearly)</li>
      <li>Medicine Stock Reports</li>
      <li>Doctor Performance Report</li>
      <li>Custom Export (Excel, PDF, CSV)</li>
    </ul>
    <h2><i class="ri-checkbox-circle-line"></i> Communication System</h2>
    <ul>
      <li>Real-time Chat (Admin ↔ Doctor ↔ Patient)</li>
      <li>WhatsApp-like Messaging UI (typing indicator, read/unread, file sharing)</li>
      <li>Email & SMS Notifications</li>
      <li>Push Notifications (Firebase)</li>
    </ul>
    <h2><i class="ri-checkbox-circle-line"></i> Security & Compliance</h2>
    <ul>
      <li>Role-Based Access Control (RBAC)</li>
      <li>Data Encryption (SSL/HTTPS)</li>
      <li>Audit Logs (Who did what, when)</li>
      <li>Backup & Restore</li>
      <li>HIPAA/GDPR Compliance</li>
    </ul>
    <h2><i class="ri-checkbox-circle-line"></i> Modern Features</h2>
    <ul>
      <li>Dark Mode</li>
      <li>Responsive Design (Mobile, Tablet, Desktop)</li>
      <li>Multi-language Support</li>
      <li>Online Payment for Bills & Appointments</li>
      <li>AI-powered Chatbot for FAQs / Initial Triage</li>
      <li>Voice-to-Text for Doctors (Prescriptions / Notes)</li>
      <li>Telemedicine (Video Call via WebRTC)</li>
    </ul>
  </section>

  <!-- Footer -->
  <footer>
    &copy; <?php echo date("Y"); ?> HMS Software. All Rights Reserved.
  </footer>

  <!-- Script for Hamburger -->
  <script>
    const hamburger = document.getElementById('hamburger');
    const menu = document.getElementById('menu');

    hamburger.addEventListener('click', ()=>{
      menu.classList.toggle('active');
    });
  </script>

</body>
</html>