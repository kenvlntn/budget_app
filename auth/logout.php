<?php
require_once "../db_connect.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Destroy session data
$_SESSION = [];
session_destroy();

// Verify it's gone
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

jsonResponse([
    "success" => true,
    "message" => "Logged out successfully"
]);
?>