<?php

// Datenbank-Zugangsdaten aus Umgebungsvariablen (gesetzt via .htaccess oder .env)
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'your_database');
define('DB_USER', getenv('DB_USER') ?: 'your_user');
define('DB_PASS', getenv('DB_PASSWORD') ?: 'your_password');

// PDO-Verbindung ohne SSL/TLS (Strato Shared Hosting!)
try {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,

        // SSL-Optionen explizit deaktivieren
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
        PDO::MYSQL_ATTR_SSL_CA => null,
    ];
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    die('Datenbankverbindung fehlgeschlagen: ' . $e->getMessage());
}

// Session-Konstanten für Handler & Timeouts
define('SESSION_DB', [
    'host' => DB_HOST,
    'dbname' => DB_NAME,
    'user' => DB_USER,
    'pass' => DB_PASS
]);

define('SESSION_TTL', 1800); // Datenbank-Speicherzeit für Sessions
define('SESSION_TIMEOUT', 900); // Inaktivitäts-Logout nach 15 Minuten

// Session-Cookie-Konfiguration
define('SESSION_COOKIE_LIFETIME', 0); // bis Browser geschlossen
define('SESSION_COOKIE_SECURE', isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'); // nur HTTPS
define('SESSION_COOKIE_HTTPONLY', true);
define('SESSION_COOKIE_SAMESITE', 'Strict');
?>
