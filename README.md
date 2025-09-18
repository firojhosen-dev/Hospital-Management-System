# 🏥 Hospital Management System (HMS)

The Hospital Management System (HMS) is a complete and professional web-based solution designed to simplify the day-to-day management of hospitals, clinics, and healthcare organizations.
This project automates and digitizes all hospital processes including Patient Registration, Doctor Management, Appointments, Pharmacy, Billing, Reporting, and Messaging.

It is a multi-role system that supports:<br>
👨‍💼 Admin (full control & management) <br>
👨‍⚕️ Doctors (patient care & communication)<br>
🧑 Patients (appointments & health records)<br>

## 📖 Why HMS is Needed?
Managing a hospital involves multiple complex operations – from patient admission to billing and pharmacy management. Traditionally, hospitals rely on manual systems which are slow, error-prone, and inefficient.<br>

A digital Hospital Management System solves these problems by:<br>
📊 Centralizing patient data in a secure database.<br>
🔐 Providing role-based access to admins, doctors, and patients.<br>
💻 Offering a paperless solution for appointments, prescriptions, and reports.<br>
⏳ Saving time and increasing efficiency of hospital staff.<br>
💡 Helping hospitals make data-driven decisions through analytics.<br>
🌟 Core Features (Detailed)<br>

## 👨‍💼 Admin Panel
Manage Doctors (Add, Edit, Delete, Assign Departments).<br>
Manage Patients (Registration, History, Records).<br>
Control Appointments (Approve, Cancel, Reschedule).<br>
Manage Departments & Wards.<br>
Handle Pharmacy & Inventory.<br>
Generate Bills & Manage Payments.<br>
Add Birth, Death, and Accident Cases.<br>
Dashboard with Analytics (Charts & Reports).<br>
Role-based User Access Control (Admin/Doctor/Patient).<br>
Generate & Export Reports (PDF, Excel).<br>

## 👨‍⚕️ Doctor Panel
Secure Login & Profile Management.<br>
View, Accept, or Reject Appointments.<br>
Access & Update Patient Records.<br>
Write & Update Prescriptions.<br>
Communicate with Patients & Admin via Chat Module.<br>
Track Hospital Cases (Birth/Death/Accidents).<br>
Dashboard with Today’s Appointments & Patient List.<br>

## 🧑 Patient Portal
Easy Registration & Login.<br>
Book Appointments with Doctors.<br>
View Admission & Treatment History.<br>
Pharmacy Purchase & Bill Payment.<br>
Download Prescriptions & Medical Reports.<br>
Chat with Doctors or Admin.<br>
Update Profile (Name, Photo).<br>

## 💊 Pharmacy Management
Add, Edit, Delete Medicines.<br>
Maintain Medicine Stock.<br>
Auto alert for low stock.<br>
Generate Invoices for Patient Purchase.<br>

## 💳 Billing & Payments
Generate automated bills for each patient.<br>
Track pending and completed payments.<br>
Integration-ready for Online Payment Gateway (SSLCommerz, Stripe, PayPal).<br>
Export billing data to PDF/Excel.<br>

## 📈 Reports & Analytics
Number of Patients (Daily, Monthly, Yearly).<br>
Number of Doctors & Appointments.<br>
Birth/Death/Accident Case Reports.<br>
Income & Expense Statistics.<br>
Department Performance Report.<br>
Graphical Dashboard (Chart.js).<br>

## 🛠️ Technology Stack
Frontend:<br>
HTML5, CSS3, JavaScript (AJAX, Bootstrap 5)<br>
Backend:<br>
PHP (Core PHP + OOP Concepts)<br>
Database:<br>
MySQL (Relational Database with Foreign Keys)<br>
Authentication:<br>
PHP Sessions, Password Hashing<br>
Reports:<br>
FPDF (for PDF generation)<br>
Charts & Analytics:<br>
Chart.js<br>
Real-time Messaging:<br>
AJAX / WebSocket (optional upgrade)<br>


---

## 📂 Folder Structure
```bash
Hospital_Management_system/
│── app/
│   │── controllers/
│   │   │── AdminController.php
│   │   │── AppointmentController.php
│   │   │── AuthController.php
│   │   │── BillingController.php
│   │   │── DoctorController.php
│   │   │── InventoryController.php
│   │   │── logout.php
│   │   └── PatientController.php
│   │   │
│   │   │── MassageController/
│   │   │   │── delete_message.php
│   │   │   │── fetch_inbox.php
│   │   │   │── fetch_thread.php
│   │   │   │── mark_message.php
│   │   │   │── send_message.php
│   │   │   │── typing.php
│   │   │   └── unread_count.php
│   │   │  
│   │── helpers/
│   │   └── functions.php
│   │    
│   │── uploads/
│   │ 
│   │── views/
│   │   │── admin/
│   │   │   │── add.case.php
│   │   │   │── add_department.php
│   │   │   │── add_doctor.php
│   │   │   │── admission_requests.php
│   │   │   │── appointments.php
│   │   │   │── billing.php
│   │   │   │── dashboard_settings.php
│   │   │   │── dashboard.php
│   │   │   │── delete_department.php
│   │   │   │── delete_product.php
│   │   │   │── department_ajax.php
│   │   │   │── department.php
│   │   │   │── edit_department.php
│   │   │   │── edit_doctor.php
│   │   │   │── inventory.php
│   │   │   │── manage_admissions.php
│   │   │   │── manage_all_patients.php
│   │   │   │── manage_doctors.php
│   │   │   │── manage_order.php
│   │   │   │── patients_pdf.php
│   │   │   │── search_admission.php
│   │   │   └── view_admission.php
│   │   │   │   
│   │   │   │── uploads/
│   │   │   │   └── your profile photo.....
│   │   │   │   
│   │   │   │── pharmacy/
│   │   │   │   │── add_product.php
│   │   │   │   │── buy_now.php
│   │   │   │   │── cart.php
│   │   │   │   │── checkout.php
│   │   │   │   │── edit_product.php
│   │   │   │   │── my_order.php
│   │   │   │   │── order_success.php
│   │   │   │   │── payment_callback.php
│   │   │   │   │── payment_init.php
│   │   │   │   │── pharmacy.php
│   │   │   │   │── product_ajax.php
│   │   │   │   └── view_product.php
│   │   │   │   │
│   │   │   │   │── uploads/
│   │   │   └   └── our product......
│   │   │
│   │   │── auth/
│   │   │   │── login.php
│   │   │   └── register.php
│   │   │   
│   │   │── doctor/
│   │   │   │── appointments.php
│   │   │   │── billing.php
│   │   │   │── dashboard.php
│   │   │   │── prescriptions.php
│   │   │   └── reports.php
│   │   │   
│   │   │── messages/
│   │   │   │── chat.php
│   │   │   │── fetch_messages.php
│   │   │   │── profile.php
│   │   │   │── send_message.php
│   │   │   └── settings.php
│   │   │   
│   │   │── patient/
│   │   │   │── admission_from.php
│   │   │   │── appointment_letter.php
│   │   │   │── appointments.php
│   │   │   │── billing.php
│   │   │   │── book_appointment.php
│   │   │   │── dashboard.php
│   │   │   │── invoice_pdf.php
│   │   │   │── prescriptions.php
│   │   │   │── reports.php
│   │   │   └── settings.php  
│   
│── config/
│   │── config.php
│   └── email_config.php
│ 
│── routes/
│   └── web.php
│
│── vendor/
│   │── fpdf/
│   │   └── This folder should be placed in this location by installing it
│   │── PHPMailer/
│   │   └── This folder should be placed in this location by installing it.
│
│── public/
│   │── index.php
│   ├── assets/
│   │   ├── images/
│   │   │   ├── login_image.png
│   │   │   ├── logo.png
│   │   │   └── register_image.png
│   │   │
│   │   ├── uploads/
│   │   │   └── uploads your photo
│   │   │   │
│   │   │   ├── messages/
│   │   │   │   └── message photo
│   │   │   └──
├── .htaccess
│
└── README.md

```


## ⚙️ Installation & Setup

Clone this repository:
```
git clone https://github.com/firojhosen-dev/Hospital-Management-System.git
```

Import Database:<br>
Open phpMyAdmin.<br>
Create a new database hospital_db.<br>
Import the file database.sql from /config/.<br>
Configure Database Connection:<br>
Edit config/config.php:<br>
```
$host = "localhost";
$user = "root";
$pass = "";
$db   = "hospital_db";
```

Run the project on XAMPP/WAMP server:
```
http://localhost/hospital-management-system/
```

## Default User Credentials:<br>
Admin: hospitalmanagementsystemadmin@gmail.com<br>
 | Password (154321)<br>
Doctor: hospitalmanagementsystemdoctor@gmail.com<br>
 | Password (123453)<br>
Patient: hospitalmanagementsystempatient@gmail.com<br>
 | Password (123452)<br>

## 🔐 User Roles & Permissions
Role	Features<br>
Admin	Manage doctors, patients, pharmacy, billing, reports, analytics<br>
Doctor	Appointments, patient records, prescriptions, chat<br>
Patient	Book appointments, view records, pharmacy, billing, chat<br>

## 📈 Future Scope
AI-based Appointment Recommendation.<br>
Telemedicine (Video/Audio Consultation using WebRTC).<br>
Mobile App version (Flutter/React Native).<br>
Online Payment Gateway (SSLCommerz, Stripe, PayPal).<br>
Push Notifications (Firebase).<br>
Cloud Deployment (AWS, Azure, Vercel).<br>
Multi-language Support (English, Bangla).<br>

## 📸 Demo Screenshots
(Add your screenshots here for better presentation)<br>
Dashboard Overview<br>
Admin Panel<br>
Doctor’s Appointment List<br>
Patient Booking Page<br>
Pharmacy Management<br>
Billing & Reports<br>

## 🤝 Contribution Guidelines
We welcome contributions!<br>
Fork this repository.<br>
Create a new branch.<br>
Make your changes.<br>
Submit a Pull Request.<br>

## 📜 License
This project is licensed under the MIT License – free for personal and commercial use.

# Allah Hafiz
