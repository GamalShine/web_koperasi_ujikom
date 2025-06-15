<?php
require_once '../../functions/functions.php';
redirectIfNotLoggedIn();
require_once '../../config/database.php';

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "ID Transaksi tidak valid";
    header("Location: index.php");
    exit();
}

$id_transaksi = $_GET['id'];

// Validate if transaksi exists
$check_query = "SELECT id_transaksi FROM transaction WHERE id_transaksi = ?";
$check_stmt = $conn->prepare($check_query);
$check_stmt->bind_param("i", $id_transaksi);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error'] = "Transaksi tidak ditemukan";
    header("Location: index.php");
    exit();
}

// Delete related detail_transaction records first
$delete_detail_query = "DELETE FROM detail_transaction WHERE id_transaksi = ?";
$delete_detail_stmt = $conn->prepare($delete_detail_query);
if (!$delete_detail_stmt) {
    $_SESSION['error'] = "Error preparing detail transaction delete query: " . $conn->error;
    header("Location: index.php");
    exit();
}
$delete_detail_stmt->bind_param("i", $id_transaksi);
if (!$delete_detail_stmt->execute()) {
    $_SESSION['error'] = "Gagal menghapus detail transaksi terkait: " . $delete_detail_stmt->error;
    header("Location: index.php");
    exit();
}

// Delete the transaksi
$delete_query = "DELETE FROM transaction WHERE id_transaksi = ?";
$delete_stmt = $conn->prepare($delete_query);
$delete_stmt->bind_param("i", $id_transaksi);

if ($delete_stmt->execute()) {
    $_SESSION['success'] = "Data transaksi berhasil dihapus";
} else {
    $_SESSION['error'] = "Gagal menghapus data transaksi: " . $conn->error;
}

header("Location: index.php");
exit();
?> 