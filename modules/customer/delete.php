<?php
require_once '../../functions/functions.php';
redirectIfNotLoggedIn();
require_once '../../config/database.php';

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "ID Customer tidak valid";
    header("Location: index.php");
    exit();
}

$id_customer = $_GET['id'];

// Validate if customer exists
$check_query = "SELECT id_customer FROM customer WHERE id_customer = ?";
$check_stmt = $conn->prepare($check_query);
$check_stmt->bind_param("i", $id_customer);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error'] = "Customer tidak ditemukan";
    header("Location: index.php");
    exit();
}

// Delete the customer
$delete_query = "DELETE FROM customer WHERE id_customer = ?";
$delete_stmt = $conn->prepare($delete_query);
$delete_stmt->bind_param("i", $id_customer);

if ($delete_stmt->execute()) {
    $_SESSION['success'] = "Data customer berhasil dihapus";
} else {
    $_SESSION['error'] = "Gagal menghapus data customer: " . $conn->error;
}

header("Location: index.php");
exit();
?> 