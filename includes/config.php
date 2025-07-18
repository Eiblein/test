<?php
// Lebensdauer der Session in Sekunden (Standard: 30 Minuten)
define('SESSION_TTL', 1800);
// Pfad zur Session-Datenbank
define('SESSION_DB', __DIR__ . '/../sessions.sqlite');

// Cookie settings for sessions
if (!defined('SESSION_COOKIE_LIFETIME')) {
    define('SESSION_COOKIE_LIFETIME', 0); // Session cookie
}
if (!defined('SESSION_COOKIE_SECURE')) {
    define('SESSION_COOKIE_SECURE', true);
}
if (!defined('SESSION_COOKIE_HTTPONLY')) {
    define('SESSION_COOKIE_HTTPONLY', true);
}
if (!defined('SESSION_COOKIE_SAMESITE')) {
    define('SESSION_COOKIE_SAMESITE', 'Strict');
}
