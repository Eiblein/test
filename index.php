<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/session_handler.php';
require_once __DIR__ . '/includes/functions.php';

// Session-Handler aktivieren
$handler = new DbSessionHandler(SESSION_DB, SESSION_TTL);
session_set_save_handler($handler, true);
session_set_cookie_params([
    'lifetime' => SESSION_COOKIE_LIFETIME,
    'secure' => SESSION_COOKIE_SECURE,
    'httponly' => SESSION_COOKIE_HTTPONLY,
    'samesite' => SESSION_COOKIE_SAMESITE,
]);
session_start();

// Sprache setzen aus URL oder Session oder Browser
if (isset($_GET['lang']) && in_array($_GET['lang'], ['de', 'en'])) {
    $_SESSION['lang'] = $_GET['lang'];
} elseif (!isset($_SESSION['lang']) && isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
    $_SESSION['lang'] = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
}
if (!in_array($_SESSION['lang'] ?? '', ['de', 'en'])) {
    $_SESSION['lang'] = 'de';
}

// Automatischer Logout nach 15 Min Inaktivität
define('SESSION_TIMEOUT', 900);
if (isset($_SESSION['last_activity']) && time() - $_SESSION['last_activity'] > SESSION_TIMEOUT) {
    session_unset();
    session_destroy();
    header('Location: login.php?timeout=1');
    exit();
}
$_SESSION['last_activity'] = time();

// Logout manuell
if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: login.php?logged_out=1');
    exit();
}

// Begrüßung je nach Tageszeit
$hour = (int)date('H');
if ($hour < 12) {
    $greeting = t('greeting_morning');
} elseif ($hour < 18) {
    $greeting = t('greeting_afternoon');
} else {
    $greeting = t('greeting_evening');
}

// Zugriffsschutz
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

// Willkommen nur einmal
$show_welcome = false;
if (!empty($_SESSION['just_logged_in'])) {
    $show_welcome = true;
    unset($_SESSION['just_logged_in']);
}
?>
<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($_SESSION['lang']); ?>">
<head>
    <meta charset="UTF-8">
    <title><?php echo t('login_title'); ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Sprachumschalter -->
    <div style="text-align: right;">
        <a href="?lang=de">🇩🇪 Deutsch</a> |
        <a href="?lang=en">🇬🇧 English</a>
    </div>

    <p><?php echo htmlspecialchars($greeting); ?></p>

    <?php if ($show_welcome): ?>
        <h1><?php echo t('welcome'); ?></h1>
        <p><?php echo t('logged_in_success'); ?></p>
    <?php endif; ?>

    <form method="post">
        <button type="submit" name="logout"><?php echo t('logout_button'); ?></button>
    </form>
</body>
</html>
