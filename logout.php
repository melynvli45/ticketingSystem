<?php
// logout.php - destroy session and redirect to index
session_start();
// Unset all session variables
$_SESSION = [];
// Destroy session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params['path'], $params['domain'],
        $params['secure'], $params['httponly']
    );
}
session_destroy();
header('Location: index.php');
exit;

