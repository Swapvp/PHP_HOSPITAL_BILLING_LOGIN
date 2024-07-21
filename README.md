#Data base schema

CREATE DATABASE db_name;
USE db_name;

-- Table for users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table for room rates and charges
CREATE TABLE room_rates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    icu_rate DECIMAL(10, 2) NOT NULL,
    deluxe_rate DECIMAL(10, 2) NOT NULL,
    general_rate DECIMAL(10, 2) NOT NULL,
    doctor_charges DECIMAL(10, 2) NOT NULL,
    rmo_charges DECIMAL(10, 2) NOT NULL,
    nurse_charges DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Table for medical expenses
CREATE TABLE medical_expenses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    date DATE NOT NULL,
    type ENUM('medical', 'non-medical') NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Table for room usage
CREATE TABLE room_usage (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    icu_days INT NOT NULL,
    deluxe_days INT NOT NULL,
    general_days INT NOT NULL,
    doctor_visits INT NOT NULL,
    rmo_visits INT NOT NULL,
    nurse_visits INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
