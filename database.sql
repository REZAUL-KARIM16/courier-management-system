-- ==========================================================
-- Courier Management System - Database Schema

-- ==========================================================

CREATE DATABASE IF NOT EXISTS courier_db;
USE courier_db;

-- 1. Branches
CREATE TABLE Branches (
    branch_id INT AUTO_INCREMENT PRIMARY KEY,
    branch_name VARCHAR(100) NOT NULL,
    location VARCHAR(150) NOT NULL,
    phone VARCHAR(20)
);

-- 2. Customers
CREATE TABLE Customers (
    customer_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100),
    address VARCHAR(200)
);

-- 3. Delivery_Agents
CREATE TABLE Delivery_Agents (
    agent_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    branch_id INT,
    FOREIGN KEY (branch_id) REFERENCES Branches(branch_id)
);

-- 4. Parcels
CREATE TABLE Parcels (
    parcel_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT,
    branch_id INT,
    weight DECIMAL(6,2),
    description VARCHAR(200),
    status VARCHAR(30) DEFAULT 'Booked',
    booking_date DATE,
    FOREIGN KEY (customer_id) REFERENCES Customers(customer_id),
    FOREIGN KEY (branch_id) REFERENCES Branches(branch_id)
);

-- 5. Deliveries
CREATE TABLE Deliveries (
    delivery_id INT AUTO_INCREMENT PRIMARY KEY,
    parcel_id INT,
    agent_id INT,
    delivery_date DATE,
    delivery_status VARCHAR(30) DEFAULT 'Pending',
    FOREIGN KEY (parcel_id) REFERENCES Parcels(parcel_id),
    FOREIGN KEY (agent_id) REFERENCES Delivery_Agents(agent_id)
);

-- 6. Payments
CREATE TABLE Payments (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    parcel_id INT,
    amount DECIMAL(8,2),
    payment_date DATE,
    payment_method VARCHAR(30),
    payment_status VARCHAR(30) DEFAULT 'Unpaid',
    FOREIGN KEY (parcel_id) REFERENCES Parcels(parcel_id)
);

-- ==========================================================
-- Sample Data
-- ==========================================================

INSERT INTO Branches (branch_name, location, phone) VALUES
('Chattogram Main Branch', 'Agrabad, Chattogram', '031-123456'),
('Dhaka Central Branch', 'Motijheel, Dhaka', '02-987654');

INSERT INTO Customers (name, phone, email, address) VALUES
('Rahim Uddin', '01711111111', 'rahim@example.com', 'GEC Circle, Chattogram'),
('Karim Ali', '01822222222', 'karim@example.com', 'Dhanmondi, Dhaka');

INSERT INTO Delivery_Agents (name, phone, branch_id) VALUES
('Jasim Mia', '01911111111', 1),
('Salam Khan', '01922222222', 2);

INSERT INTO Parcels (customer_id, branch_id, weight, description, status, booking_date) VALUES
(1, 1, 2.5, 'Books', 'Booked', CURDATE()),
(2, 2, 1.2, 'Electronics', 'In Transit', CURDATE());

INSERT INTO Deliveries (parcel_id, agent_id, delivery_date, delivery_status) VALUES
(1, 1, CURDATE(), 'Pending'),
(2, 2, CURDATE(), 'In Transit');

INSERT INTO Payments (parcel_id, amount, payment_date, payment_method, payment_status) VALUES
(1, 150.00, CURDATE(), 'Cash', 'Paid'),
(2, 300.00, CURDATE(), 'bKash', 'Unpaid');

-- ==========================================================
