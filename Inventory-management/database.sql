create database if not exists inventory_system;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    mob VARCHAR(15),
    password VARCHAR(255),
    status ENUM('active', 'inactive') DEFAULT 'inactive',
    role ENUM('admin', 'employee', 'worker')
);

CREATE TABLE inventory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_name VARCHAR(100),
    quantity INT,
    date_stocked_at DATE,
    status ENUM('alright', 'warning', 'danger') DEFAULT 'alright'
);

CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    task TEXT,
    assigned_to INT,
    assigned_by INT,
    assigned_date DATE,
    completed_or_not ENUM('yes', 'no') DEFAULT 'no',
    completion_datetime DATETIME
);
