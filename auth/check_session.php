<?php
require_once "../db_connect.php";

// Ensure session isn't started twice
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user_id exists in session
if (!isset($_SESSION["user_id"])) {
    // If this file is included, it acts as a gatekeeper.
    // If not logged in, stop everything and return error.
    jsonResponse(["authenticated" => false, "error" => "Unauthorized: No active session"], 401);
}

// If we pass this point, the user is logged in.
$user_id = $_SESSION["user_id"];
?>