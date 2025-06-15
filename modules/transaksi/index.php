<?php
// Start output buffering to prevent headers already sent error
ob_start();

require_once '../../functions/functions.php';
redirectIfNotLoggedIn();
require_once '../../config/database.php';
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';

// Query untuk mengambil data transaksi
$query = "SELECT t.*, c.nama_customer, s.nama_sales, p.nama_petugas 
          FROM transaction t 
          LEFT JOIN customer c ON t.id_customer = c.id_customer 
          LEFT JOIN sales s ON t.id_sales = s.id_sales 
          LEFT JOIN petugas p ON t.id_petugas = p.id_petugas 
          ORDER BY t.tanggal_transaksi DESC";
$result = $conn->query($query);
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Data Transaksi</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="../../dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active">Transaksi</li>
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
                            <h3 class="card-title">Daftar Transaksi</h3>
                            <div class="card-tools">
                                <a href="create.php" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Tambah Transaksi
                                </a>
                            </div>
                        </div>
                        <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <?php 
                                echo $_SESSION['success'];
                                unset($_SESSION['success']);
                                ?>
                        </div>
                        <?php endif; ?>
                        <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <?php 
                                echo $_SESSION['error'];
                                unset($_SESSION['error']);
                                ?>
                        </div>
                        <?php endif; ?>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover" style="width: 100%;">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th style="width: 5%;">No</th>
                                            <th style="width: 12%;">Tanggal</th>
                                            <th style="width: 18%;">Customer</th>
                                            <th style="width: 12%;">Sales</th>
                                            <th style="width: 12%;">Petugas</th>
                                            <th style="width: 15%;">Total</th>
                                            <th style="width: 10%;">Status</th>
                                            <th style="width: 16%;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $no = 1;
                                        while ($row = $result->fetch_assoc()): 
                                        ?>
                                        <tr>
                                            <td><?php echo $no++; ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($row['tanggal_transaksi'])); ?></td>
                                            <td><?php echo htmlspecialchars($row['nama_customer'] ?? '-'); ?></td>
                                            <td><?php echo htmlspecialchars($row['nama_sales'] ?? '-'); ?></td>
                                            <td><?php echo htmlspecialchars($row['nama_petugas'] ?? '-'); ?></td>
                                            <td>Rp <?php echo number_format($row['total_harga'] ?? 0, 0, ',', '.'); ?></td>
                                            <td>
                                                <?php 
                                                $status = $row['status_pembayaran'] ?? 'belum lunas';

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
                                            <td>
                                                <a href="detail.php?id=<?php echo $row['id_transaksi']; ?>"
                                                    class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="edit.php?id=<?php echo $row['id_transaksi']; ?>"
                                                    class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="delete.php?id=<?php echo $row['id_transaksi']; ?>"
                                                    class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
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