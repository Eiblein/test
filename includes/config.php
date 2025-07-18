<?php

// Datenbankverbindungskonstanten (MySQL)
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'myapp');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');

// PDO-Verbindung bereitstellen
try {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die('Verbindung zur Datenbank fehlgeschlagen: ' . $e->getMessage());
}

// Session-Konstanten
define('SESSION_DB', [
    'host' => DB_HOST,
    'dbname' => DB_NAME,
    'user' => DB_USER,
    'pass' => DB_PASS
]);

define('SESSION_TTL', 1800); // Session-Datenbank-Einträge: 30 Minuten Lebensdauer
define('SESSION_TIMEOUT', 900); // Benutzer wird nach 15 Minuten Inaktivität ausgeloggt

// Session-Cookie-Einstellungen
define('SESSION_COOKIE_LIFETIME', 0); // Gültig bis zum Schließen des Browsers
define('SESSION_COOKIE_SECURE', isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on');
define('SESSION_COOKIE_HTTPONLY', true);
define('SESSION_COOKIE_SAMESITE', 'Strict');
?>
