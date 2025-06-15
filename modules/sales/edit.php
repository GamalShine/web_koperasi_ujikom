<?php
// Start output buffering to prevent headers already sent error
ob_start();

require_once '../../functions/functions.php';
redirectIfNotLoggedIn();
require_once '../../config/database.php';
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';

$sales_data = [];
$errors = [];

// Check if ID is provided for editing
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_sales = $_GET['id'];

    // Fetch existing sales data
    $query = "SELECT * FROM sales WHERE id_sales = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_sales);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $sales_data = $result->fetch_assoc();
    } else {
        $_SESSION['error'] = "Sales tidak ditemukan";
        header("Location: index.php");
        exit();
    }
} else {
    $_SESSION['error'] = "ID Sales tidak valid";
    header("Location: index.php");
    exit();
}

// Proses form submission for update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_sales = sanitizeInput($_POST['nama_sales']);
    $alamat = sanitizeInput($_POST['alamat']);
    $no_telepon = sanitizeInput($_POST['no_telepon']);
    $email = sanitizeInput($_POST['email']);

    // Validasi input
    if (empty($nama_sales)) {
        $errors[] = "Nama sales harus diisi";
    }
    if (empty($alamat)) {
        $errors[] = "Alamat harus diisi";
    }
    if (empty($no_telepon)) {
        $errors[] = "Nomor telepon harus diisi";
    }
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format email tidak valid";
    }

    // If no errors, update to database
    if (empty($errors)) {
        $update_query = "UPDATE sales SET nama_sales = ?, alamat = ?, no_telepon = ?, email = ? WHERE id_sales = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("ssssi", $nama_sales, $alamat, $no_telepon, $email, $id_sales);
        
        if ($update_stmt->execute()) {
            $_SESSION['success'] = "Data sales berhasil diperbarui";
            header("Location: index.php");
            exit();
        } else {
            $errors[] = "Gagal memperbarui data: " . $conn->error;
        }
    }
}
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Sales</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="../../dashboard.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="index.php">Sales</a></li>
                        <li class="breadcrumb-item active">Edit Sales</li>
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
                            <h3 class="card-title">Form Edit Sales</h3>
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
                                    <label for="nama_sales">Nama Sales</label>
                                    <input type="text" class="form-control" id="nama_sales" name="nama_sales" 
                                           value="<?php echo isset($_POST['nama_sales']) ? htmlspecialchars($_POST['nama_sales']) : htmlspecialchars($sales_data['nama_sales'] ?? ''); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="alamat">Alamat</label>
                                    <textarea class="form-control" id="alamat" name="alamat" rows="3" required><?php echo isset($_POST['alamat']) ? htmlspecialchars($_POST['alamat']) : htmlspecialchars($sales_data['alamat'] ?? ''); ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="no_telepon">No. Telepon</label>
                                    <input type="text" class="form-control" id="no_telepon" name="no_telepon" 
                                           value="<?php echo isset($_POST['no_telepon']) ? htmlspecialchars($_POST['no_telepon']) : htmlspecialchars($sales_data['no_telepon'] ?? ''); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : htmlspecialchars($sales_data['email'] ?? ''); ?>">
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
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