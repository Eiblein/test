<?php
session_start();
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
    <title>Index</title>
</head>
<body>
<?php if ($show_welcome): ?>
<h1>Willkommen!</h1>
<p>Sie sind erfolgreich eingeloggt.</p>
<?php endif; ?>
<p>Zweiter Login</p>
</body>
</html>