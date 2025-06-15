<?php
require_once '../../functions/functions.php';
redirectIfNotLoggedIn();
require_once '../../config/database.php';

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "ID Petugas tidak valid";
    header("Location: index.php");
    exit();
}

$id_petugas = $_GET['id'];

// Validate if petugas exists
$check_query = "SELECT id_petugas FROM petugas WHERE id_petugas = ?";
$check_stmt = $conn->prepare($check_query);
$check_stmt->bind_param("i", $id_petugas);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error'] = "Petugas tidak ditemukan";
    header("Location: index.php");
    exit();
}

// Check if petugas is trying to delete themselves
if ($id_petugas == $_SESSION['user_id']) {
    $_SESSION['error'] = "Anda tidak dapat menghapus akun Anda sendiri";
    header("Location: index.php");
    exit();
}

// Delete the petugas
$delete_query = "DELETE FROM petugas WHERE id_petugas = ?";
$delete_stmt = $conn->prepare($delete_query);
$delete_stmt->bind_param("i", $id_petugas);

if ($delete_stmt->execute()) {
    $_SESSION['success'] = "Data petugas berhasil dihapus";
} else {
    $_SESSION['error'] = "Gagal menghapus data petugas: " . $conn->error;
}

header("Location: index.php");
exit();
?> 