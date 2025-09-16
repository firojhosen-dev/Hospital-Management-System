# 🏥 Hospital Management System (HMS)

The Hospital Management System (HMS) is a complete and professional web-based solution designed to simplify the day-to-day management of hospitals, clinics, and healthcare organizations.
This project automates and digitizes all hospital processes including Patient Registration, Doctor Management, Appointments, Pharmacy, Billing, Reporting, and Messaging.

It is a multi-role system that supports:

👨‍💼 Admin (full control & management)

👨‍⚕️ Doctors (patient care & communication)

🧑 Patients (appointments & health records)

## 📖 Why HMS is Needed?

Managing a hospital involves multiple complex operations – from patient admission to billing and pharmacy management. Traditionally, hospitals rely on manual systems which are slow, error-prone, and inefficient.

A digital Hospital Management System solves these problems by:

📊 Centralizing patient data in a secure database.

🔐 Providing role-based access to admins, doctors, and patients.

💻 Offering a paperless solution for appointments, prescriptions, and reports.

⏳ Saving time and increasing efficiency of hospital staff.

💡 Helping hospitals make data-driven decisions through analytics.

🌟 Core Features (Detailed)
## 👨‍💼 Admin Panel

Manage Doctors (Add, Edit, Delete, Assign Departments).

Manage Patients (Registration, History, Records).

Control Appointments (Approve, Cancel, Reschedule).

Manage Departments & Wards.

Handle Pharmacy & Inventory.

Generate Bills & Manage Payments.

Add Birth, Death, and Accident Cases.

Dashboard with Analytics (Charts & Reports).

Role-based User Access Control (Admin/Doctor/Patient).

Generate & Export Reports (PDF, Excel).

## 👨‍⚕️ Doctor Panel

Secure Login & Profile Management.

View, Accept, or Reject Appointments.

Access & Update Patient Records.

Write & Update Prescriptions.

Communicate with Patients & Admin via Chat Module.

Track Hospital Cases (Birth/Death/Accidents).

Dashboard with Today’s Appointments & Patient List.

## 🧑 Patient Portal

Easy Registration & Login.

Book Appointments with Doctors.

View Admission & Treatment History.

Pharmacy Purchase & Bill Payment.

Download Prescriptions & Medical Reports.

Chat with Doctors or Admin.

Update Profile (Name, Photo).

## 💊 Pharmacy Management

Add, Edit, Delete Medicines.

Maintain Medicine Stock.

Auto alert for low stock.

Generate Invoices for Patient Purchase.

## 💳 Billing & Payments

Generate automated bills for each patient.

Track pending and completed payments.

Integration-ready for Online Payment Gateway (SSLCommerz, Stripe, PayPal).

Export billing data to PDF/Excel.

## 📈 Reports & Analytics

Number of Patients (Daily, Monthly, Yearly).

Number of Doctors & Appointments.

Birth/Death/Accident Case Reports.

Income & Expense Statistics.

Department Performance Report.

Graphical Dashboard (Chart.js).

## 🛠️ Technology Stack

Frontend:
HTML5, CSS3, JavaScript (AJAX, Bootstrap 5)

Backend:
PHP (Core PHP + OOP Concepts)

Database:
MySQL (Relational Database with Foreign Keys)

Authentication:
PHP Sessions, Password Hashing

Reports:
FPDF (for PDF generation)

Charts & Analytics:
Chart.js

Real-time Messaging:
AJAX / WebSocket (optional upgrade)


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
git clone https://github.com/yourusername/hospital-management-system.git
```

Import Database:

Open phpMyAdmin.

Create a new database hospital_db.

Import the file database.sql from /config/.

Configure Database Connection:
Edit config/config.php:
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

Default User Credentials:

Admin: admin@hms.com
 | admin123

Doctor: doctor@hms.com
 | doctor123

Patient: patient@hms.com
 | patient123

## 🔐 User Roles & Permissions
Role	Features
Admin	Manage doctors, patients, pharmacy, billing, reports, analytics
Doctor	Appointments, patient records, prescriptions, chat
Patient	Book appointments, view records, pharmacy, billing, chat
## 📈 Future Scope

AI-based Appointment Recommendation.

Telemedicine (Video/Audio Consultation using WebRTC).

Mobile App version (Flutter/React Native).

Online Payment Gateway (SSLCommerz, Stripe, PayPal).

Push Notifications (Firebase).

Cloud Deployment (AWS, Azure, Vercel).

Multi-language Support (English, Bangla).

## 📸 Demo Screenshots

(Add your screenshots here for better presentation)

Dashboard Overview

Admin Panel

Doctor’s Appointment List

Patient Booking Page

Pharmacy Management

Billing & Reports

## 🤝 Contribution Guidelines

We welcome contributions!

Fork this repository.

Create a new branch.

Make your changes.

Submit a Pull Request.

## 📜 License

This project is licensed under the MIT License – free for personal and commercial use.
# Allah Hafiz