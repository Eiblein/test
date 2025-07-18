<?php
session_start();
require_once __DIR__ . '/includes/functions.php';

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
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title><?php echo t('login_title'); ?></title>
</head>
<body>
<?php if ($show_welcome): ?>
<h1><?php echo t('welcome'); ?></h1>
<p><?php echo t('logged_in_success'); ?></p>
<?php endif; ?>
<p><?php echo t('second_login'); ?></p>
</body>
</html>