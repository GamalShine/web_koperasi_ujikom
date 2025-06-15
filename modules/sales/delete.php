<?php
// Start output buffering to prevent headers already sent error
ob_start();

require_once '../../functions/functions.php';
redirectIfNotLoggedIn();
require_once '../../config/database.php';

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "ID Sales tidak valid";
    header("Location: index.php");
    exit();
}

$id_sales = $_GET['id'];

// Validate if sales exists
$check_query = "SELECT id_sales FROM sales WHERE id_sales = ?";
$check_stmt = $conn->prepare($check_query);
if (!$check_stmt) {
    $_SESSION['error'] = "Error preparing query: " . $conn->error;
    header("Location: index.php");
    exit();
}
$check_stmt->bind_param("i", $id_sales);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error'] = "Sales tidak ditemukan";
    header("Location: index.php");
    exit();
}

// Check if sales is used in transactions
$check_transaction_query = "SELECT id_transaksi FROM transaction WHERE id_sales = ?";
$check_transaction_stmt = $conn->prepare($check_transaction_query);
if (!$check_transaction_stmt) {
    $_SESSION['error'] = "Error preparing transaction check query: " . $conn->error;
    header("Location: index.php");
    exit();
}
$check_transaction_stmt->bind_param("i", $id_sales);
$check_transaction_stmt->execute();
$transaction_result = $check_transaction_stmt->get_result();

if ($transaction_result->num_rows > 0) {
    $_SESSION['error'] = "Sales tidak dapat dihapus karena masih digunakan dalam transaksi";
    header("Location: index.php");
    exit();
}

// Delete the sales
$delete_query = "DELETE FROM sales WHERE id_sales = ?";
$delete_stmt = $conn->prepare($delete_query);
if (!$delete_stmt) {
    $_SESSION['error'] = "Error preparing delete query: " . $conn->error;
    header("Location: index.php");
    exit();
}
$delete_stmt->bind_param("i", $id_sales);

if ($delete_stmt->execute()) {
    $_SESSION['success'] = "Data sales berhasil dihapus";
} else {
    $_SESSION['error'] = "Gagal menghapus data sales: " . $conn->error;
}

header("Location: index.php");
exit();
?>
<?php ob_end_flush(); ?> 