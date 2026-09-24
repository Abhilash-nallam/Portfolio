-- =========================================================
-- LeadDesk Mini - Database Schema
-- Database: leaddesk
-- =========================================================

CREATE DATABASE IF NOT EXISTS leaddesk CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE leaddesk;

-- ---------------------------------------------------------
-- Table: admins
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Default admin credentials:
-- username: admin
-- password: Admin@123
-- (hash generated with PHP password_hash(), bcrypt)
INSERT INTO admins (username, password, full_name) VALUES
('admin', '$2b$12$EChIsPZgxlPAuZNlVdb5O.hgPO/hPX9sQje8Ex7ziYgvj4EZ1HB.6', 'Site Administrator')
ON DUPLICATE KEY UPDATE username = username;

-- ---------------------------------------------------------
-- Table: leads
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS leads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    budget VARCHAR(50) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('New', 'Contacted', 'Closed') NOT NULL DEFAULT 'New',
    ip_address VARCHAR(45) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_created_at (created_at),
    INDEX idx_email (email)
) ENGINE=InnoDB;

-- Sample leads for demo/testing purposes
INSERT INTO leads (name, email, budget, message, status, created_at) VALUES
('Ravi Kumar', 'ravi.kumar@example.com', '₹50,000 - ₹1,00,000', 'Need a full stack CRM for my dealership.', 'New', NOW() - INTERVAL 1 DAY),
('Sneha Reddy', 'sneha.reddy@example.com', '₹1,00,000 - ₹3,00,000', 'Looking to automate our lead intake process.', 'Contacted', NOW() - INTERVAL 3 DAY),
('Arjun Mehta', 'arjun.mehta@example.com', 'Above ₹3,00,000', 'Enterprise plan enquiry for our sales team.', 'Closed', NOW() - INTERVAL 7 DAY);
