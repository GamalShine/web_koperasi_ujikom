<?php
// Start output buffering to prevent headers already sent error
ob_start();

require_once '../../functions/functions.php';
redirectIfNotLoggedIn();
require_once '../../config/database.php';
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "ID Transaksi tidak valid";
    header("Location: index.php");
    exit();
}

$id_transaksi = $_GET['id'];

// Fetch transaksi data with customer and sales information
$query = "SELECT t.*, c.nama_customer, c.alamat, c.no_telepon, s.nama_sales, p.nama_petugas 
          FROM transaction t 
          LEFT JOIN customer c ON t.id_customer = c.id_customer 
          LEFT JOIN sales s ON t.id_sales = s.id_sales 
          LEFT JOIN petugas p ON t.id_petugas = p.id_petugas 
          WHERE t.id_transaksi = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_transaksi);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error'] = "Transaksi tidak ditemukan";
    header("Location: index.php");
    exit();
}

$transaksi = $result->fetch_assoc();
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Detail Transaksi</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="../../dashboard.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="index.php">Transaksi</a></li>
                        <li class="breadcrumb-item active">Detail Transaksi</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Informasi Transaksi</h3>
                            <div class="card-tools">
                                <a href="edit.php?id=<?php echo $transaksi['id_transaksi']; ?>"
                                    class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="index.php" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Informasi Transaksi</h5>
                                    <table class="table table-bordered">
                                        <tr>
                                            <td><strong>ID Transaksi:</strong></td>
                                            <td><?php echo $transaksi['id_transaksi']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tanggal Transaksi:</strong></td>
                                            <td><?php echo date('d/m/Y', strtotime($transaksi['tanggal_transaksi'])); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Total Harga:</strong></td>
                                            <td>Rp
                                                <?php echo number_format($transaksi['total_harga'] ?? 0, 0, ',', '.'); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status Pembayaran:</strong></td>
                                            <td>
                                                <?php 
                                                $status = $transaksi['status_pembayaran'] ?? 'belum lunas';

                                                // Debug: Tampilkan nilai mentah dari status_pembayaran
                                                echo "<!-- Raw Status: " . htmlspecialchars($transaksi['status_pembayaran'] ?? 'N/A') . " -->";

                                                // Jika status adalah string kosong, atur ke 'belum lunas'
                                                if ($status === '') {
                                                    $status = 'belum lunas';
                                                }

                                                $statusClass = '';
                                                switch($status) {
                                                    case 'lunas':
                                                        $statusClass = 'success';
                                                        break;
                                                    case 'cicilan':
                                                        $statusClass = 'warning';
                                                        break;
                                                    case 'belum lunas':
                                                    default:
                                                        $statusClass = 'danger';
                                                        break;
                                                }
                                                ?>
                                                <span class="badge badge-<?php echo $statusClass; ?>">
                                                    <?php echo ucfirst($status); ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Petugas:</strong></td>
                                            <td><?php echo htmlspecialchars($transaksi['nama_petugas'] ?? '-'); ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Catatan:</strong></td>
                                            <td><?php echo $transaksi['catatan'] ? htmlspecialchars($transaksi['catatan']) : '-'; ?>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h5>Informasi Customer</h5>
                                    <table class="table table-bordered">
                                        <tr>
                                            <td><strong>Nama Customer:</strong></td>
                                            <td><?php echo htmlspecialchars($transaksi['nama_customer'] ?? '-'); ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Alamat:</strong></td>
                                            <td><?php echo htmlspecialchars($transaksi['alamat'] ?? '-'); ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>No. Telepon:</strong></td>
                                            <td><?php echo htmlspecialchars($transaksi['no_telepon'] ?? '-'); ?></td>
                                        </tr>
                                    </table>

                                    <h5>Informasi Sales</h5>
                                    <table class="table table-bordered">
                                        <tr>
                                            <td><strong>Nama Sales:</strong></td>
                                            <td><?php echo htmlspecialchars($transaksi['nama_sales'] ?? '-'); ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php 
$conn->close();
require_once '../../includes/footer.php'; 
?>
<?php ob_end_flush(); ?>