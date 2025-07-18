<?php
session_start();
require_once __DIR__ . '/includes/functions.php';

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
</body>
</html>