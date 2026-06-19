<?php
try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=it_portal", "root", "Apel1289", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    echo "=== USERS ===\n";
    $stmt = $pdo->query("SELECT * FROM users");
    print_r($stmt->fetchAll());
    
    echo "=== APLIKASI ===\n";
    $stmt = $pdo->query("SELECT * FROM aplikasi");
    print_r($stmt->fetchAll());

    echo "=== KREDENSIAL ===\n";
    $stmt = $pdo->query("SELECT * FROM kredensial_app");
    print_r($stmt->fetchAll());
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
