<?php
session_start();

// Include database configuration
require_once __DIR__ . '/../config/database.php';

// Set default timezone
date_default_timezone_set('Asia/Jakarta');

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>