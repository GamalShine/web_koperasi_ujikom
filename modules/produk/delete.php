<?php
require_once '../../functions/functions.php';
redirectIfNotLoggedIn();
require_once '../../config/database.php';

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "ID Produk tidak valid";
    header("Location: index.php");
    exit();
}

$id_item = $_GET['id'];

// Validate if produk exists
$check_query = "SELECT id_item FROM item WHERE id_item = ?";
$check_stmt = $conn->prepare($check_query);
$check_stmt->bind_param("i", $id_item);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error'] = "Produk tidak ditemukan";
    header("Location: index.php");
    exit();
}

// Delete the produk
$delete_query = "DELETE FROM item WHERE id_item = ?";
$delete_stmt = $conn->prepare($delete_query);
$delete_stmt->bind_param("i", $id_item);

if ($delete_stmt->execute()) {
    $_SESSION['success'] = "Data produk berhasil dihapus";
} else {
    $_SESSION['error'] = "Gagal menghapus data produk: " . $conn->error;
}

header("Location: index.php");
exit();
?> 