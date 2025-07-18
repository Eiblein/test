<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/session_handler.php';
require_once __DIR__ . '/includes/functions.php';

$handler = new DbSessionHandler(SESSION_DB, SESSION_TTL);
session_set_save_handler($handler, true);
session_start();

$login_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    // Standard Test-User und Passwort: admin / admin
    if ($username === 'admin' && $password === 'admin') {
        $_SESSION['logged_in'] = true;
        $_SESSION['just_logged_in'] = true;
        header('Location: index.php');
        exit();
    } else {
        $login_error = t('login_error');
    }
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
<?php if ($login_error) : ?>
<p style="color:red;"><?php echo htmlspecialchars($login_error); ?></p>
<?php endif; ?>
<form method="post" action="login.php">
    <label for="username"><?php echo t('username'); ?>:</label>
    <input type="text" name="username" id="username" required>
    <br>
    <label for="password"><?php echo t('password'); ?>:</label>
    <input type="password" name="password" id="password" required>
    <br>
    <button type="submit"><?php echo t('login_button'); ?></button>
</form>
</body>
</html>