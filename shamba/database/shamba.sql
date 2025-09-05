-- Create the database
CREATE DATABASE shamba_palace;

-- Use the database
USE shamba_palace;

-- Create table for machinery
CREATE TABLE machinery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    type VARCHAR(100) NOT NULL,
    price_per_hour DECIMAL(10, 2) NOT NULL,
    status ENUM('Available', 'Not Available') DEFAULT 'Available'
);

-- Create table for bookings
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    machinery_id INT NOT NULL,
    customer_name VARCHAR(100) NOT NULL,
    date DATE NOT NULL,
    hours INT NOT NULL,
    FOREIGN KEY (machinery_id) REFERENCES machinery(id)
);

-- Create table for admin
CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(50) NOT NULL  
);

-- Insert a default admin user
INSERT INTO admin (username, email, password) VALUES ('admin', 'admin@shambapalace.com', 'Pass123');


-- Create table for users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(50) NOT NULL  
);

-- Create settings table
CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    site_title VARCHAR(100) NOT NULL DEFAULT 'Shamba Palace',
    admin_email VARCHAR(100) NOT NULL DEFAULT 'admin@shambapalace.com'
);

-- Insert default settings
INSERT INTO settings (site_title, admin_email) 
VALUES ('Shamba Palace', 'admin@shambapalace.com');

-- Insert these queries into bookings table:
ALTER TABLE bookings ADD COLUMN status VARCHAR(20) DEFAULT 'pending';

ALTER TABLE BOOKINGS ADD COLUMN USER_ID INT;

ALTER TABLE BOOKINGS ADD COLUMN REJECTION_REASON TEXT;