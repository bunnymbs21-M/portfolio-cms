<?php
// config/db.php
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'portfolio_db');
define('DB_USER', 'portfolio_user');
define('DB_PASS', 'secure_password');
define('DB_SOCKET', '/var/lib/mysql/mysql.sock');
function getDBConnection() {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = "mysql:unix_socket=" . DB_SOCKET . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            http_response_code(500);
            die(json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]));
        }
    }
    return $pdo;
}

// XSS Prevention helper
function sanitize($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}