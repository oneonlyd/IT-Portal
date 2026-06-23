<?php
require_once 'config/security.php';

$passwords = [
    'Lenovo001@',
    'Apel1289',
    'admin',
    'password_with_special_chars_!@#$%^&*()_+{}|:"<>?`-=[]\;',
    'mySecurePassword123'
];

foreach ($passwords as $p) {
    $enc = encrypt_password($p);
    $dec = decrypt_password($enc);
    echo "Original: " . $p . "\n";
    echo "Encrypted: " . $enc . "\n";
    echo "Decrypted: " . $dec . "\n";
    echo "Status: " . ($p === $dec ? "OK" : "FAIL") . "\n\n";
}
?>





