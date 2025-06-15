<?php
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function redirectIfNotLoggedIn() {
    if (!isLoggedIn()) {
        header("Location: ../../auth/login.php");
        exit();
    }
}

function isAdmin() {
    return isset($_SESSION['level']) && $_SESSION['level'] === 'admin';
}

function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function formatRupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

function getCurrentDate() {
    return date('Y-m-d');
}
?>