<?php
// Start output buffering to prevent headers already sent error
ob_start();

require_once '../../functions/functions.php';
redirectIfNotLoggedIn();
require_once '../../config/database.php';
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';

// Query untuk mengambil data customer
$query_customer = "SELECT id_customer, nama_customer FROM customer ORDER BY nama_customer ASC";
$result_customer = $conn->query($query_customer);

// Query untuk mengambil data sales
$query_sales = "SELECT id_sales, nama_sales FROM sales ORDER BY nama_sales ASC";
$result_sales = $conn->query($query_sales);

// Proses form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_customer = sanitizeInput($_POST['id_customer']);
    $id_sales = sanitizeInput($_POST['id_sales']);
    $tanggal_transaksi = sanitizeInput($_POST['tanggal_transaksi']);
    $total_harga = sanitizeInput($_POST['total_harga']);
    $status_pembayaran = sanitizeInput($_POST['status_pembayaran']);
    $catatan = sanitizeInput($_POST['catatan']);

    // Validasi input
    $errors = [];
    if (empty($id_customer)) {
        $errors[] = "Customer harus dipilih";
    }
    if (empty($id_sales)) {
        $errors[] = "Sales harus dipilih";
    }
    if (empty($tanggal_transaksi)) {
        $errors[] = "Tanggal transaksi harus diisi";
    }
    if (empty($total_harga) || $total_harga <= 0) {
        $errors[] = "Total harga harus diisi dengan benar";
    }
    if (empty($status_pembayaran)) {
        $errors[] = "Status pembayaran harus dipilih";
    }

    if (empty($errors)) {
        $id_petugas = $_SESSION['user_id']; // Ambil ID petugas dari session
        $query = "INSERT INTO transaction (id_customer, id_sales, id_petugas, tanggal_transaksi, total_harga, status_pembayaran, catatan) 
                 VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iiissis", $id_customer, $id_sales, $id_petugas, $tanggal_transaksi, $total_harga, $status_pembayaran, $catatan);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Transaksi berhasil ditambahkan!";
            header("Location: index.php");
            exit();
        } else {
            $errors[] = "Gagal menambahkan transaksi: " . $conn->error;
        }
    }
}
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Tambah Transaksi</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="../../dashboard.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="index.php">Transaksi</a></li>
                        <li class="breadcrumb-item active">Tambah Transaksi</li>
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
                            <h3 class="card-title">Form Tambah Transaksi</h3>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php foreach ($errors as $error): ?>
                                    <li><?php echo $error; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <?php endif; ?>

                            <form action="" method="POST">
                                <div class="form-group">
                                    <label for="id_customer">Customer</label>
                                    <select class="form-control" id="id_customer" name="id_customer" required>
                                        <option value="">Pilih Customer</option>
                                        <?php while ($customer = $result_customer->fetch_assoc()): ?>
                                        <option value="<?php echo $customer['id_customer']; ?>"
                                            <?php echo (isset($_POST['id_customer']) && $_POST['id_customer'] == $customer['id_customer']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($customer['nama_customer']); ?>
                                        </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="id_sales">Sales</label>
                                    <select class="form-control" id="id_sales" name="id_sales" required>
                                        <option value="">Pilih Sales</option>
                                        <?php while ($sales = $result_sales->fetch_assoc()): ?>
                                        <option value="<?php echo $sales['id_sales']; ?>"
                                            <?php echo (isset($_POST['id_sales']) && $_POST['id_sales'] == $sales['id_sales']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($sales['nama_sales']); ?>
                                        </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="tanggal_transaksi">Tanggal Transaksi</label>
                                    <input type="date" class="form-control" id="tanggal_transaksi"
                                        name="tanggal_transaksi"
                                        value="<?php echo isset($_POST['tanggal_transaksi']) ? $_POST['tanggal_transaksi'] : date('Y-m-d'); ?>"
                                        required>
                                </div>

                                <div class="form-group">
                                    <label for="total_harga">Total Harga</label>
                                    <input type="number" class="form-control" id="total_harga" name="total_harga"
                                        value="<?php echo isset($_POST['total_harga']) ? $_POST['total_harga'] : ''; ?>"
                                        required>
                                </div>

                                <div class="form-group">
                                    <label for="status_pembayaran">Status Pembayaran</label>
                                    <select class="form-control" id="status_pembayaran" name="status_pembayaran"
                                        required>
                                        <option value="">Pilih Status</option>
                                        <option value="lunas"
                                            <?php echo (isset($_POST['status_pembayaran']) && $_POST['status_pembayaran'] == 'lunas') ? 'selected' : ''; ?>>
                                            Lunas</option>
                                        <option value="cicilan"
                                            <?php echo (isset($_POST['status_pembayaran']) && $_POST['status_pembayaran'] == 'cicilan') ? 'selected' : ''; ?>>
                                            Cicilan</option>
                                        <option value="belum lunas"
                                            <?php echo (isset($_POST['status_pembayaran']) && $_POST['status_pembayaran'] == 'belum lunas') ? 'selected' : ''; ?>>
                                            Belum Lunas</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="catatan">Catatan</label>
                                    <textarea class="form-control" id="catatan" name="catatan"
                                        rows="3"><?php echo isset($_POST['catatan']) ? htmlspecialchars($_POST['catatan']) : ''; ?></textarea>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
                                    <a href="index.php" class="btn btn-secondary">Kembali</a>
                                </div>
                            </form>
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