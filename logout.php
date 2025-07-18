<?php
require_once __DIR__ . '/includes/config.php';

session_start();
session_unset();
session_destroy();

// Optional: Session-Cookie löschen
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

header('Location: login.php?logged_out=1');
exit;
?>
<