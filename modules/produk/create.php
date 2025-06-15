<?php
// Start output buffering to prevent headers already sent error
ob_start();

require_once '../../functions/functions.php';
redirectIfNotLoggedIn();
require_once '../../config/database.php';
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';

// Proses form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_item = sanitizeInput($_POST['nama_item']);
    $kategori = sanitizeInput($_POST['kategori']);
    $harga_beli = sanitizeInput($_POST['harga_beli']);
    $harga_jual = sanitizeInput($_POST['harga_jual']);
    $stok = sanitizeInput($_POST['stok']);
    $satuan = sanitizeInput($_POST['satuan']);

    // Validasi input
    $errors = [];
    if (empty($nama_item)) {
        $errors[] = "Nama produk harus diisi";
    }
    if (empty($kategori)) {
        $errors[] = "Kategori harus diisi";
    }
    if (empty($harga_beli) || $harga_beli <= 0) {
        $errors[] = "Harga beli harus diisi dengan benar";
    }
    if (empty($harga_jual) || $harga_jual <= 0) {
        $errors[] = "Harga jual harus diisi dengan benar";
    }
    if (empty($stok) || $stok < 0) {
        $errors[] = "Stok harus diisi dengan benar";
    }
    if (empty($satuan)) {
        $errors[] = "Satuan harus diisi";
    }

    // Jika tidak ada error, simpan ke database
    if (empty($errors)) {
        $query = "INSERT INTO item (nama_item, kategori, harga_beli, harga_jual, stok, satuan) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssddss", $nama_item, $kategori, $harga_beli, $harga_jual, $stok, $satuan);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Data produk berhasil ditambahkan";
            header("Location: index.php");
            exit();
        } else {
            $errors[] = "Gagal menambahkan data: " . $conn->error;
        }
    }
}
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Tambah Produk</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="../../dashboard.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="index.php">Produk</a></li>
                        <li class="breadcrumb-item active">Tambah Produk</li>
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
                            <h3 class="card-title">Form Tambah Produk</h3>
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
                                    <label for="nama_item">Nama Produk</label>
                                    <input type="text" class="form-control" id="nama_item" name="nama_item" 
                                           value="<?php echo isset($_POST['nama_item']) ? htmlspecialchars($_POST['nama_item']) : ''; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="kategori">Kategori</label>
                                    <select class="form-control" id="kategori" name="kategori" required>
                                        <option value="">Pilih Kategori</option>
                                        <option value="Makanan" <?php echo (isset($_POST['kategori']) && $_POST['kategori'] == 'Makanan') ? 'selected' : ''; ?>>Makanan</option>
                                        <option value="Minuman" <?php echo (isset($_POST['kategori']) && $_POST['kategori'] == 'Minuman') ? 'selected' : ''; ?>>Minuman</option>
                                        <option value="Snack" <?php echo (isset($_POST['kategori']) && $_POST['kategori'] == 'Snack') ? 'selected' : ''; ?>>Snack</option>
                                        <option value="Rokok" <?php echo (isset($_POST['kategori']) && $_POST['kategori'] == 'Rokok') ? 'selected' : ''; ?>>Rokok</option>
                                        <option value="Pulsa" <?php echo (isset($_POST['kategori']) && $_POST['kategori'] == 'Pulsa') ? 'selected' : ''; ?>>Pulsa</option>
                                        <option value="Lainnya" <?php echo (isset($_POST['kategori']) && $_POST['kategori'] == 'Lainnya') ? 'selected' : ''; ?>>Lainnya</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="harga_beli">Harga Beli</label>
                                    <input type="number" class="form-control" id="harga_beli" name="harga_beli" 
                                           value="<?php echo isset($_POST['harga_beli']) ? $_POST['harga_beli'] : ''; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="harga_jual">Harga Jual</label>
                                    <input type="number" class="form-control" id="harga_jual" name="harga_jual" 
                                           value="<?php echo isset($_POST['harga_jual']) ? $_POST['harga_jual'] : ''; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="stok">Stok</label>
                                    <input type="number" class="form-control" id="stok" name="stok" 
                                           value="<?php echo isset($_POST['stok']) ? $_POST['stok'] : ''; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="satuan">Satuan</label>
                                    <select class="form-control" id="satuan" name="satuan" required>
                                        <option value="">Pilih Satuan</option>
                                        <option value="PCS" <?php echo (isset($_POST['satuan']) && $_POST['satuan'] == 'PCS') ? 'selected' : ''; ?>>PCS</option>
                                        <option value="BOX" <?php echo (isset($_POST['satuan']) && $_POST['satuan'] == 'BOX') ? 'selected' : ''; ?>>BOX</option>
                                        <option value="PACK" <?php echo (isset($_POST['satuan']) && $_POST['satuan'] == 'PACK') ? 'selected' : ''; ?>>PACK</option>
                                        <option value="BOTOL" <?php echo (isset($_POST['satuan']) && $_POST['satuan'] == 'BOTOL') ? 'selected' : ''; ?>>BOTOL</option>
                                        <option value="GELAS" <?php echo (isset($_POST['satuan']) && $_POST['satuan'] == 'GELAS') ? 'selected' : ''; ?>>GELAS</option>
                                        <option value="KG" <?php echo (isset($_POST['satuan']) && $_POST['satuan'] == 'KG') ? 'selected' : ''; ?>>KG</option>
                                        <option value="GRAM" <?php echo (isset($_POST['satuan']) && $_POST['satuan'] == 'GRAM') ? 'selected' : ''; ?>>GRAM</option>
                                        <option value="LITER" <?php echo (isset($_POST['satuan']) && $_POST['satuan'] == 'LITER') ? 'selected' : ''; ?>>LITER</option>
                                        <option value="ML" <?php echo (isset($_POST['satuan']) && $_POST['satuan'] == 'ML') ? 'selected' : ''; ?>>ML</option>
                                        <option value="Lainnya" <?php echo (isset($_POST['satuan']) && $_POST['satuan'] == 'Lainnya') ? 'selected' : ''; ?>>Lainnya</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
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