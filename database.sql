-- Create database
CREATE DATABASE IF NOT EXISTS portfolio_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE portfolio_db;

-- 1. Users / Admins table for Authentication
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Projects table for Full CRUD
CREATE TABLE IF NOT EXISTS projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    tech_stack VARCHAR(150) NOT NULL,
    github_url VARCHAR(255) NULL,
    live_url VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3. Messages table for Form Processing (Read/Delete in admin)
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(150) NOT NULL,
    body TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Default Admin Account (Username: admin | Password: adminpassword123)
-- Hash generated via password_hash('adminpassword123', PASSWORD_DEFAULT)
INSERT INTO users (username, password_hash) 
VALUES ('admin', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.qH0vRz9y54Qp8R1E3gXyTjZ6zQhWv2e')
ON DUPLICATE KEY UPDATE id=id;

-- Seed Projects
INSERT INTO projects (title, description, tech_stack, github_url, live_url) VALUES 
('Terminal-Based Task Manager', 'High performance CLI task tracker built with strict zero-dependency philosophy and keyboard navigation.', 'C, Make, POSIX', 'https://github.com/example/cli-tracker', NULL),
('Algorithmic Visualizer', 'Visual breakdown of graph traversal and pathfinding algorithms with real-time step inspection.', 'JavaScript, HTML5 Canvas', 'https://github.com/example/algo-vis', 'https://example.com/algo-vis'),
('Dynamic Portfolio CMS', 'Minimalist, performant portfolio with an authenticated PHP/MySQL CRUD manager.', 'PHP, MySQL, CSS Grid', 'https://github.com/example/portfolio-cms', NULL);