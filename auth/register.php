<?php
require_once('../db_connect.php');

// 1. Get Input
$input = json_decode(file_get_contents("php://input"), true);
$username = trim($input["username"] ?? '');
$email = trim($input["email"] ?? '');
$password = $input["password"] ?? '';

if (!$username || !$email || !$password) {
    jsonResponse(["error" => "Missing required fields"], 400);
}

// 2. Check duplicate email
$stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    jsonResponse(["error" => "Email already registered"], 409);
}

// 3. Check duplicate username
$stmt = $pdo->prepare("SELECT user_id FROM users WHERE username = ?");
$stmt->execute([$username]);
if ($stmt->fetch()) {
    jsonResponse(["error" => "Username already taken"], 409);
}

// 4. Insert User
try {
    $pdo->beginTransaction(); // Start transaction to ensure both inserts happen or neither does

    // A. Create User
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
    $stmt->execute([
        $username,
        $email,
        password_hash($password, PASSWORD_DEFAULT) // Standard PHP hashing
    ]);
    
    $userId = $pdo->lastInsertId();

    // B. Create Default Profile (Important!)
    $stmt = $pdo->prepare("INSERT INTO user_profile (user_id, budget_cycle) VALUES (?, 'monthly')");
    $stmt->execute([$userId]);

    $pdo->commit(); // Save changes

    jsonResponse([
        "success" => true,
        "message" => "Registration successful",
        "user_id" => $userId
    ]);

} catch (Exception $e) {
    $pdo->rollBack(); // Undo if something failed
    jsonResponse(["error" => "Registration failed: " . $e->getMessage()], 500);
}
?>