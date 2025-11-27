<?php
require_once('../db_connect.php');

// 1. Get Input
$input = json_decode(file_get_contents("php://input"), true);
$email = trim($input["email"] ?? '');
$password = $input["password"] ?? '';

if (!$email || !$password) {
    jsonResponse(["error" => "Missing email or password"], 400);
}

// 2. Fetch user
$stmt = $pdo->prepare("SELECT user_id, password_hash FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

// 3. Verify User & Password
if (!$user || !password_verify($password, $user["password_hash"])) {
    jsonResponse(["error" => "Invalid email or password"], 401);
}

// 4. Start Session
if (session_status() === PHP_SESSION_NONE) session_start();

$_SESSION["user_id"] = $user["user_id"];
// We don't strictly need a token for session-based auth, but we can generate one if you want frontend usage later.
$token = bin2hex(random_bytes(32)); 

jsonResponse([
    "success" => true,
    "message" => "Login successful",
    "user_id" => $user["user_id"],
    "token" => $token // Optional, but good to have
]);
?>