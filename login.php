<?php
session_start();
$login_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    // Standard Test-User und Passwort: admin / admin
    if ($username === 'admin' && $password === 'admin') {
        $_SESSION['logged_in'] = true;
        header('Location: index.php');
        if ($username === 'admin' && $password === 'admin') {
    $_SESSION['logged_in'] = true;
    $_SESSION['just_logged_in'] = true; // Diese Zeile ergänzen
    header('Location: index.php');
    exit();
}
//
        exit();
    } else {
        $login_error = 'Falscher Benutzername oder falsches Passwort.';
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
<?php if ($login_error) : ?>
<p style="color:red;"><?php echo htmlspecialchars($login_error); ?></p>
<?php endif; ?>
<form method="post" action="login.php">
    <label for="username">Benutzername:</label>
    <input type="text" name="username" id="username" required>
    <br>
    <label for="password">Passwort:</label>
    <input type="password" name="password" id="password" required>
    <br>
    <button type="submit">Login</button>
</form>
</body>
</html>