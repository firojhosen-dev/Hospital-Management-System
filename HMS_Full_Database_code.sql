-- -----------------------------------------------------------
--        HOSPITAL MANAGEMENT SYSTEM - COMPLETE DATABASE
-- -----------------------------------------------------------

DROP DATABASE IF EXISTS hospital_db;
CREATE DATABASE hospital_db;
USE hospital_db;

-- ===========================================================
-- USERS & AUTH MODULE
-- ===========================================================

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(150) NOT NULL,
    email VARCHAR(200) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin','doctor','nurse','receptionist','pharmacist','lab','patient','accountant') NOT NULL,
    full_name VARCHAR(150),
    phone VARCHAR(20),
    address TEXT,
    status ENUM('active','inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (username,email,password_hash,role,full_name)
VALUES ('admin','admin@example.com',
'$2y$10$3ZqHdn4CyP7y6cgfCKCq1e6XJzGrz0k.lsE3Zl/Eo8bPClcF3KCVS',
'admin','System Admin');

-- ===========================================================
-- DEPARTMENTS
-- ===========================================================

CREATE TABLE departments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    description TEXT
);

-- ===========================================================
-- DOCTORS
-- ===========================================================

CREATE TABLE doctors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    department_id INT,
    specialization VARCHAR(200),
    fees DECIMAL(10,2) DEFAULT 0.00,
    availability TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL
);

-- ===========================================================
-- NURSES / STAFF
-- ===========================================================

CREATE TABLE nurses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    department_id INT,
    shift ENUM('morning','evening','night'),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL
);

CREATE TABLE staff (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    position VARCHAR(150),
    salary DECIMAL(10,2),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- ===========================================================
-- PATIENTS
-- ===========================================================

CREATE TABLE patients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    patient_code VARCHAR(50) UNIQUE NOT NULL,
    dob DATE,
    gender ENUM('male','female','other'),
    blood_group VARCHAR(5),
    emergency_contact VARCHAR(30),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ===========================================================
-- APPOINTMENTS
-- ===========================================================

CREATE TABLE appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    doctor_id INT NOT NULL,
    scheduled_at DATETIME NOT NULL,
    status ENUM('pending','confirmed','completed','cancelled') DEFAULT 'pending',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
    FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE
);

-- ===========================================================
-- PRESCRIPTIONS
-- ===========================================================

CREATE TABLE prescriptions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    appointment_id INT NOT NULL,
    doctor_id INT NOT NULL,
    patient_id INT NOT NULL,
    prescription_date DATE NOT NULL,
    notes TEXT,
    FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE CASCADE,
    FOREIGN KEY (doctor_id) REFERENCES doctors(id),
    FOREIGN KEY (patient_id) REFERENCES patients(id)
);

CREATE TABLE prescription_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    prescription_id INT NOT NULL,
    medicine_name VARCHAR(200) NOT NULL,
    dosage VARCHAR(100),
    duration VARCHAR(100),
    instructions TEXT,
    FOREIGN KEY (prescription_id) REFERENCES prescriptions(id) ON DELETE CASCADE
);

-- ===========================================================
-- MEDICINES / PHARMACY
-- ===========================================================

CREATE TABLE medicines (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    category VARCHAR(150),
    stock INT DEFAULT 0,
    price DECIMAL(10,2) DEFAULT 0.00,
    expiry_date DATE
);

CREATE TABLE pharmacy_sales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT,
    sale_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2),
    FOREIGN KEY (patient_id) REFERENCES patients(id)
);

CREATE TABLE pharmacy_sale_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sale_id INT NOT NULL,
    medicine_id INT,
    quantity INT,
    price DECIMAL(10,2),
    FOREIGN KEY (sale_id) REFERENCES pharmacy_sales(id) ON DELETE CASCADE,
    FOREIGN KEY (medicine_id) REFERENCES medicines(id)
);

-- ===========================================================
-- LAB TESTS
-- ===========================================================

CREATE TABLE lab_tests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    price DECIMAL(10,2)
);

CREATE TABLE lab_reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    test_id INT NOT NULL,
    patient_id INT NOT NULL,
    doctor_id INT,
    result TEXT,
    test_date DATE,
    FOREIGN KEY (test_id) REFERENCES lab_tests(id),
    FOREIGN KEY (patient_id) REFERENCES patients(id),
    FOREIGN KEY (doctor_id) REFERENCES doctors(id)
);

-- ===========================================================
-- ROOMS / BEDS
-- ===========================================================

CREATE TABLE rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_number VARCHAR(20) UNIQUE NOT NULL,
    type ENUM('general','semi-private','private','icu'),
    price_per_day DECIMAL(10,2)
);

CREATE TABLE beds (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_id INT NOT NULL,
    bed_number VARCHAR(20),
    status ENUM('available','occupied') DEFAULT 'available',
    FOREIGN KEY (room_id) REFERENCES rooms(id)
);

-- ===========================================================
-- ADMISSION / DISCHARGE
-- ===========================================================

CREATE TABLE admissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    bed_id INT,
    admit_date DATETIME NOT NULL,
    discharge_date DATETIME,
    diagnosis TEXT,
    FOREIGN KEY (patient_id) REFERENCES patients(id),
    FOREIGN KEY (bed_id) REFERENCES beds(id)
);

-- ===========================================================
-- BILLING SYSTEM
-- ===========================================================

CREATE TABLE bills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    admission_id INT,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('unpaid','paid','refunded') DEFAULT 'unpaid',
    issued_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(id),
    FOREIGN KEY (admission_id) REFERENCES admissions(id)
);

CREATE TABLE bill_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bill_id INT NOT NULL,
    description VARCHAR(255),
    amount DECIMAL(10,2),
    FOREIGN KEY (bill_id) REFERENCES bills(id) ON DELETE CASCADE
);

CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bill_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    method ENUM('cash','card','bank','mobile_banking'),
    paid_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (bill_id) REFERENCES bills(id) ON DELETE CASCADE
);

-- ===========================================================
-- MEDICAL RECORDS
-- ===========================================================

CREATE TABLE medical_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    doctor_id INT,
    record_date DATE,
    diagnosis TEXT,
    treatment TEXT,
    FOREIGN KEY (patient_id) REFERENCES patients(id),
    FOREIGN KEY (doctor_id) REFERENCES doctors(id)
);

-- ===========================================================
-- NOTIFICATIONS
-- ===========================================================

CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    message TEXT,
    type VARCHAR(50),
    status ENUM('unread','read') DEFAULT 'unread',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- ===========================================================
-- LOGS
-- ===========================================================

CREATE TABLE activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(255),
    details TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- ===========================================================
-- INDEXES
-- ===========================================================

CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_patient_code ON patients(patient_code);
CREATE INDEX idx_doctor_department ON doctors(department_id);
CREATE INDEX idx_appointment_date ON appointments(scheduled_at);

