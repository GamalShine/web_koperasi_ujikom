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

// Ambil ID item dari parameter URL
$id_item = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_item <= 0) {
    $_SESSION['error'] = "ID item tidak valid!";
    header("Location: item.php");
    exit();
}

// Ambil data item yang akan diedit
$query = "SELECT * FROM item WHERE id_item = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_item);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $_SESSION['error'] = "Item tidak ditemukan!";
    header("Location: item.php");
    exit();
}

$item = $result->fetch_assoc();

// Proses form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_item = trim($_POST['nama_item']);
    $kategori = trim($_POST['kategori']);
    $harga_beli = (float)$_POST['harga_beli'];
    $harga_jual = (float)$_POST['harga_jual'];
    $stok = (int)$_POST['stok'];
    $satuan = trim($_POST['satuan']);
    
    // Validasi input
    $errors = [];
    
    if (empty($nama_item)) {
        $errors[] = "Nama item harus diisi";
    }
    
    if (empty($kategori)) {
        $errors[] = "Kategori harus diisi";
    }
    
    if ($harga_beli <= 0) {
        $errors[] = "Harga beli harus lebih dari 0";
    }
    
    if ($harga_jual <= 0) {
        $errors[] = "Harga jual harus lebih dari 0";
    }
    
    if ($harga_jual <= $harga_beli) {
        $errors[] = "Harga jual harus lebih besar dari harga beli";
    }
    
    if ($stok < 0) {
        $errors[] = "Stok tidak boleh negatif";
    }
    
    if (empty($satuan)) {
        $errors[] = "Satuan harus diisi";
    }
    
    // Jika tidak ada error, update ke database
    if (empty($errors)) {
        $update_query = "UPDATE item SET nama_item = ?, kategori = ?, harga_beli = ?, harga_jual = ?, stok = ?, satuan = ? WHERE id_item = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("ssddssi", $nama_item, $kategori, $harga_beli, $harga_jual, $stok, $satuan, $id_item);
        
        if ($update_stmt->execute()) {
            $_SESSION['success'] = "Item berhasil diperbarui!";
            header("Location: item.php");
            exit();
        } else {
            $errors[] = "Gagal memperbarui item: " . $conn->error;
        }
    }
}

require_once 'includes/header.php';
define('SIDEBAR_INCLUDED', true);
require_once 'includes/sidebar.php';
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Item</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="item.php">Item</a></li>
                        <li class="breadcrumb-item active">Edit Item</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Form Edit Item</h3>
                        </div>
                        <form method="POST" action="">
                            <div class="card-body">
                                <?php if (!empty($errors)): ?>
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        <?php foreach ($errors as $error): ?>
                                        <li><?php echo htmlspecialchars($error); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                                <?php endif; ?>

                                <div class="form-group">
                                    <label for="nama_item">Nama Item <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nama_item" name="nama_item" 
                                           value="<?php echo htmlspecialchars($item['nama_item']); ?>" 
                                           required>
                                </div>

                                <div class="form-group">
                                    <label for="kategori">Kategori <span class="text-danger">*</span></label>
                                    <select class="form-control" id="kategori" name="kategori" required>
                                        <option value="">Pilih Kategori</option>
                                        <option value="Elektronik" <?php echo ($item['kategori'] == 'Elektronik') ? 'selected' : ''; ?>>Elektronik</option>
                                        <option value="Furniture" <?php echo ($item['kategori'] == 'Furniture') ? 'selected' : ''; ?>>Furniture</option>
                                        <option value="Dapur" <?php echo ($item['kategori'] == 'Dapur') ? 'selected' : ''; ?>>Dapur</option>
                                        <option value="Pakaian" <?php echo ($item['kategori'] == 'Pakaian') ? 'selected' : ''; ?>>Pakaian</option>
                                        <option value="Olahraga" <?php echo ($item['kategori'] == 'Olahraga') ? 'selected' : ''; ?>>Olahraga</option>
                                        <option value="Lainnya" <?php echo ($item['kategori'] == 'Lainnya') ? 'selected' : ''; ?>>Lainnya</option>
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="harga_beli">Harga Beli <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="number" class="form-control" id="harga_beli" name="harga_beli" 
                                                       value="<?php echo $item['harga_beli']; ?>" 
                                                       min="0" step="100" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="harga_jual">Harga Jual <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="number" class="form-control" id="harga_jual" name="harga_jual" 
                                                       value="<?php echo $item['harga_jual']; ?>" 
                                                       min="0" step="100" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="stok">Stok <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="stok" name="stok" 
                                                   value="<?php echo $item['stok']; ?>" 
                                                   min="0" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="satuan">Satuan <span class="text-danger">*</span></label>
                                            <select class="form-control" id="satuan" name="satuan" required>
                                                <option value="">Pilih Satuan</option>
                                                <option value="unit" <?php echo ($item['satuan'] == 'unit') ? 'selected' : ''; ?>>Unit</option>
                                                <option value="buah" <?php echo ($item['satuan'] == 'buah') ? 'selected' : ''; ?>>Buah</option>
                                                <option value="set" <?php echo ($item['satuan'] == 'set') ? 'selected' : ''; ?>>Set</option>
                                                <option value="kg" <?php echo ($item['satuan'] == 'kg') ? 'selected' : ''; ?>>Kilogram</option>
                                                <option value="liter" <?php echo ($item['satuan'] == 'liter') ? 'selected' : ''; ?>>Liter</option>
                                                <option value="meter" <?php echo ($item['satuan'] == 'meter') ? 'selected' : ''; ?>>Meter</option>
                                                <option value="lembar" <?php echo ($item['satuan'] == 'lembar') ? 'selected' : ''; ?>>Lembar</option>
                                                <option value="pack" <?php echo ($item['satuan'] == 'pack') ? 'selected' : ''; ?>>Pack</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update
                                </button>
                                <a href="item.php" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php require_once 'includes/footer.php'; ?>
<?php ob_end_flush(); ?> 