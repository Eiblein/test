<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Index</title>
</head>
<body>
<h1>Willkommen!</h1>
<p>Sie sind erfolgreich eingeloggt.</p>
</body>
</html>
