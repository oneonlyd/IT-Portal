<?php
// test_login_verification.php
require_once 'config/db.php';
$username = 'devina';
$password = 'Apel1289';

$query = "SELECT id, username, password_hash, nama_lengkap, role FROM users WHERE username = :username LIMIT 1";
$stmt = $pdo->prepare($query);
$stmt->execute([':username' => $username]);
$user = $stmt->fetch();

if ($user) {
    echo "User found: " . $user['username'] . "\n";
    echo "Full Name: " . $user['nama_lengkap'] . "\n";
    echo "Role: " . $user['role'] . "\n";
    echo "Hash in DB: " . $user['password_hash'] . "\n";
    
    if (password_verify($password, $user['password_hash'])) {
        echo "Password verification: SUCCESS!\n";
    } else {
        echo "Password verification: FAILED!\n";
        // Generate correct hash and update database
        $newHash = password_hash($password, PASSWORD_BCRYPT);
        echo "Generated correct hash: " . $newHash . "\n";
        
        $update = $pdo->prepare("UPDATE users SET password_hash = :hash WHERE username = :username");
        $update->execute([':hash' => $newHash, ':username' => $username]);
        echo "Updated database with correct password hash!\n";
    }
} else {
    echo "User not found in DB! Creating user...\n";
    $newHash = password_hash($password, PASSWORD_BCRYPT);
    $insert = $pdo->prepare("INSERT INTO users (username, password_hash, nama_lengkap, role) VALUES (:username, :hash, 'Devina Maharani', 'superadmin')");
    $insert->execute([':username' => $username, ':hash' => $newHash]);
    echo "Created user devina with password Apel1289!\n";
}
