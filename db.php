<?php
// =============================================
// TITAN - Database Connection
// db.php
// =============================================

define('DB_HOST',   'localhost');
define('DB_USER',   'root');
define('DB_PASS',   '');          // Change in production
define('DB_NAME',   'titan');
define('DB_CHARSET','utf8mb4');

function getConnection(): mysqli {
    static $conn = null;
    if ($conn === null) {
        $conn = new mysqli("localhost", "root", "", "titan");
        if ($conn->connect_error) {
            error_log('DB Connect Error: ' . $conn->connect_error);
            die(json_encode(['error' => 'Database connection failed.']));
        }
        $conn->set_charset(DB_CHARSET);
    }
    return $conn;
}

// ── SQL to create tables (run once) ──────────
/*
CREATE DATABASE IF NOT EXISTS titan_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE titan_db;

CREATE TABLE IF NOT EXISTS users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(120)  NOT NULL,
    email      VARCHAR(180)  NOT NULL UNIQUE,
    password   VARCHAR(255)  NOT NULL,
    role       ENUM('customer','admin') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS products (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(200)  NOT NULL,
    description TEXT,
    price       DECIMAL(10,2) NOT NULL,
    old_price   DECIMAL(10,2),
    category    VARCHAR(80),
    emoji       VARCHAR(10)   DEFAULT '📦',
    badge       VARCHAR(30),
    stock       INT DEFAULT 0,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS contacts (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(120)  NOT NULL,
    email      VARCHAR(180)  NOT NULL,
    subject    VARCHAR(255),
    message    TEXT          NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Sample products
INSERT INTO products (name, description, price, old_price, category, emoji, badge, stock) VALUES
('Quantum Watch Pro',  'Swiss movement, sapphire crystal, 42mm titanium case.', 2499.00, 2999.00, 'Watches',      '⌚', 'SALE',    50),
('Noir Leather Tote',  'Full-grain vegetable-tanned Italian leather, 12-pocket.', 899.00,  null,    'Bags',         '👜', 'NEW',     30),
('Apex Sunglasses',    'Polarized acetate frames, UV400 lenses, hand-crafted.',  349.00,  null,    'Accessories',  '🕶️', null,     80),
('Carbon Slim Wallet', '12-card RFID blocking, aerospace-grade carbon fibre.',   199.00,  249.00,  'Accessories',  '💳', 'SALE',   120),
('Merino Crew Neck',   'Grade-A New Zealand merino wool, machine washable.',     289.00,  null,    'Clothing',     '🧥', 'NEW',     60),
('Titanium Pen',       'Aerospace titanium, pressurised Schmidt cartridge.',     159.00,  null,    'Stationery',   '🖊️', null,    200);
*/

$conn = getConnection();
