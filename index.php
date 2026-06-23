<?php
// index.php
// File redirector otomatis ke dashboard.html (jika sudah login) atau login.html (jika belum login)

session_start();

if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
    header("Location: dashboard.html");
} else {
    header("Location: login.html");
}
exit;
