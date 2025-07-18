<?php
require_once 'config.php';
require_once 'functions.php';
session_start();

// CSRF-Token generieren
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// IP-Adresse ermitteln
$ip_address = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

// Login-Versuche aus der Datenbank lesen
$pdo->exec("CREATE TABLE IF NOT EXISTS login_attempts (
    ip VARCHAR(45) PRIMARY KEY,
    attempts INT DEFAULT 0,
    last_attempt INT
)");

$stmt = $pdo->prepare("SELECT * FROM login_attempts WHERE ip = :ip");
$stmt->execute(['ip' => $ip_address]);
$attempt = $stmt->fetch(PDO::FETCH_ASSOC);

// Timeout prüfen (z. B. 1 Stunde)
$now = time();
if ($attempt && $now - $attempt['last_attempt'] > 3600) {
    $pdo->prepare("DELETE FROM login_attempts WHERE ip = :ip")->execute(['ip' => $ip_address]);
    $attempt = null;
}

// Session-Login-Zähler initialisieren
$_SESSION['login_attempts'] = $_SESSION['login_attempts'] ?? 0;
$errors = [];

// Login-Verarbeitung
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $errors[] = "Ungültiges CSRF-Token.";
    }

    if ($_SESSION['login_attempts'] >= 5) {
        $errors[] = "Zu viele Login-Versuche in dieser Sitzung.";
    }

    if ($attempt && $attempt['attempts'] >= 20) {
        $errors[] = "Zu viele Login-Versuche von dieser IP.";
    }

    if (empty($errors)) {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user'] = $user['username'];

            // IP-Versuche zurücksetzen
            $pdo->prepare("DELETE FROM login_attempts WHERE ip = :ip")->execute(['ip' => $ip_address]);
            $_SESSION['login_attempts'] = 0;

            header('Location: index.php');
            exit;
        } else {
            $_SESSION['login_attempts']++;
            if ($attempt) {
                $stmt = $pdo->prepare("UPDATE login_attempts SET attempts = attempts + 1, last_attempt = :time WHERE ip = :ip");
            } else {
                $stmt = $pdo->prepare("INSERT INTO login_attempts (ip, attempts, last_attempt) VALUES (:ip, 1, :time)");
            }
            $stmt->execute(['ip' => $ip_address, 'time' => $now]);
            $errors[] = "Ungültiger Benutzername oder Passwort.";

            if (isset($_GET['logged_out'])) {
    echo "<p>Sie wurden erfolgreich ausgeloggt.</p>";
}

        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Login</h1>

    <?php foreach ($errors as $error): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endforeach; ?>

    <form method="post">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        <label for="username">Benutzername:</label><br>
        <input type="text" name="username" id="username" required><br>
        <label for="password">Passwort:</label><br>
        <input type="password" name="password" id="password" required><br><br>
        <button type="submit">Einloggen</button>
    </form>
</body>
</html>
