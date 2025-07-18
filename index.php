<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/session_handler.php';
require_once __DIR__ . '/includes/functions.php';

$handler = new DbSessionHandler(SESSION_DB, SESSION_TTL);
session_set_save_handler($handler, true);
session_set_cookie_params([
    'lifetime' => SESSION_COOKIE_LIFETIME,
    'secure' => SESSION_COOKIE_SECURE,
    'httponly' => SESSION_COOKIE_HTTPONLY,
    'samesite' => SESSION_COOKIE_SAMESITE,
]);
session_start();

if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit();
}

// Begrüßung je nach Tageszeit wählen
$hour = (int)date('H');
if ($hour < 12) {
    $greeting = t('greeting_morning');
} elseif ($hour < 18) {
    $greeting = t('greeting_afternoon');
} else {
    $greeting = t('greeting_evening');
}

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit();
}
// Nachricht nur beim ersten Login anzeigen
$show_welcome = false;
if (!empty($_SESSION['just_logged_in'])) {
    $show_welcome = true;
    unset($_SESSION['just_logged_in']);
}
?>
<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($_SESSION['lang'] ?? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'de', 0, 2)); ?>">
<head>
    <meta charset="UTF-8">
    <title><?php echo t('login_title'); ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<p><?php echo htmlspecialchars($greeting); ?></p>
<?php if ($show_welcome): ?>
<h1><?php echo t('welcome'); ?></h1>
<p><?php echo t('logged_in_success'); ?></p>
<?php endif; ?>
<form method="post" action="index.php">
    <button type="submit" name="logout"><?php echo t('logout_button'); ?></button>
</form>
</body>
</html>
