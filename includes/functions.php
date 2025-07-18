<?php

// Dynamische Übersetzungsfunktion mit Sprachdatei-Ladefunktion
function t($key) {
    static $lang = null;
    if ($lang === null) {
        $locale = $_SESSION['lang'] ?? null;

        if ($locale === null && !empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $locale = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        }

        if (!in_array($locale, ['de', 'en'])) {
            $locale = 'de';
        }

        $file = __DIR__ . '/../lang/' . $locale . '.php';
        $lang = file_exists($file) ? include $file : include __DIR__ . '/../lang/de.php';
    }

    return $lang[$key] ?? $key;
}

// CSRF-Token erzeugen
function generate_csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// CSRF-Token prüfen
function validate_csrf_token(?string $token): bool {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Login-Status setzen
function set_logged_in(string $username): void {
    $_SESSION['logged_in'] = true;
    $_SESSION['user'] = $username;
    $_SESSION['just_logged_in'] = true;
    $_SESSION['last_activity'] = time();
    session_regenerate_id(true);
}

// Sicherer Passwort-Hash
function hash_password(string $password): string {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Passwort prüfen
function verify_password(string $password, string $hash): bool {
    return password_verify($password, $hash);
}
?>
