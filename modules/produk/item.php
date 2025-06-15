<?php
// Start output buffering to prevent headers already sent error
ob_start();

require_once 'includes/config.php';
require_once 'includes/functions.php';

// Cek login dan admin - HARUS SEBELUM INCLUDE HEADER
if (!isset($_SESSION['user_id']) || !isAdmin()) {
    header("Location: login.php");
    exit();
}

require_once 'includes/header.php';
define('SIDEBAR_INCLUDED', true);
require_once 'includes/sidebar.php';

// Proses hapus data
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    // Check if item exists
    $check_query = "SELECT id_item FROM item WHERE id_item = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("i", $id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        $delete_query = "DELETE FROM item WHERE id_item = ?";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Data item berhasil dihapus!";
        } else {
            $_SESSION['error'] = "Gagal menghapus data item: " . $conn->error;
        }
    } else {
        $_SESSION['error'] = "Item tidak ditemukan!";
    }
    
    header("Location: item.php");
    exit();
}

// Ambil semua data item
$query = "SELECT * FROM item ORDER BY nama_item ASC";
$result = $conn->query($query);
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Data Item</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active">Item</li>
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
                            <h3 class="card-title">Daftar Item</h3>
                            <div class="card-tools">
                                <a href="add-item.php" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Tambah Item
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
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

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Item</th>
                                            <th>Kategori</th>
                                            <th>Harga Beli</th>
                                            <th>Harga Jual</th>
                                            <th>Stok</th>
                                            <th>Satuan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $no = 1;
                                        while ($row = $result->fetch_assoc()): 
                                        ?>
                                        <tr>
                                            <td><?php echo $no++; ?></td>
                                            <td><?php echo htmlspecialchars($row['nama_item']); ?></td>
                                            <td><?php echo htmlspecialchars($row['kategori']); ?></td>
                                            <td>Rp <?php echo number_format($row['harga_beli'], 0, ',', '.'); ?></td>
                                            <td>Rp <?php echo number_format($row['harga_jual'], 0, ',', '.'); ?></td>
                                            <td><?php echo htmlspecialchars($row['stok']); ?></td>
                                            <td><?php echo htmlspecialchars($row['satuan']); ?></td>
                                            <td>
                                                <a href="edit-item.php?id=<?php echo $row['id_item']; ?>"
                                                    class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="?delete=<?php echo $row['id_item']; ?>" 
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

<?php require_once 'includes/footer.php'; ?>
<?php ob_end_flush(); ?>