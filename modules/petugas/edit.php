<?php
// Start output buffering to prevent headers already sent error
ob_start();

require_once '../../functions/functions.php';
redirectIfNotLoggedIn();
require_once '../../config/database.php';
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';

// Ambil ID petugas dari parameter URL
$id_petugas = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_petugas <= 0) {
    $_SESSION['error'] = "ID petugas tidak valid!";
    header("Location: index.php");
    exit();
}

// Ambil data petugas yang akan diedit
$query = "SELECT * FROM petugas WHERE id_petugas = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_petugas);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $_SESSION['error'] = "Petugas tidak ditemukan!";
    header("Location: index.php");
    exit();
}

$petugas = $result->fetch_assoc();

// Proses form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_petugas = sanitizeInput($_POST['nama_petugas']);
    $username = sanitizeInput($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $level = sanitizeInput($_POST['level']);
    
    // Validasi input
    $errors = [];
    
    if (empty($nama_petugas)) {
        $errors[] = "Nama petugas harus diisi";
    }
    
    if (empty($username)) {
        $errors[] = "Username harus diisi";
    } else {
        // Check if username already exists (except current user)
        $check_query = "SELECT id_petugas FROM petugas WHERE username = ? AND id_petugas != ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param("si", $username, $id_petugas);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            $errors[] = "Username sudah digunakan";
        }
    }
    
    // Password validation (optional for edit)
    if (!empty($password)) {
        if (strlen($password) < 6) {
            $errors[] = "Password minimal 6 karakter";
        }
        
        if ($password !== $confirm_password) {
            $errors[] = "Konfirmasi password tidak cocok";
        }
    }
    
    if (empty($level)) {
        $errors[] = "Level harus dipilih";
    }
    
    // Jika tidak ada error, update ke database
    if (empty($errors)) {
        if (!empty($password)) {
            // Update with new password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $update_query = "UPDATE petugas SET nama_petugas = ?, username = ?, password = ?, level = ? WHERE id_petugas = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param("ssssi", $nama_petugas, $username, $hashed_password, $level, $id_petugas);
        } else {
            // Update without changing password
            $update_query = "UPDATE petugas SET nama_petugas = ?, username = ?, level = ? WHERE id_petugas = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param("sssi", $nama_petugas, $username, $level, $id_petugas);
        }
        
        if ($update_stmt->execute()) {
            $_SESSION['success'] = "Petugas berhasil diperbarui!";
            header("Location: index.php");
            exit();
        } else {
            $errors[] = "Gagal memperbarui petugas: " . $conn->error;
        }
    }
}
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Petugas</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="../../dashboard.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="index.php">Petugas</a></li>
                        <li class="breadcrumb-item active">Edit Petugas</li>
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
                            <h3 class="card-title">Form Edit Petugas</h3>
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
                                    <label for="nama_petugas">Nama Petugas <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nama_petugas" name="nama_petugas" 
                                           value="<?php echo htmlspecialchars($petugas['nama_petugas']); ?>" 
                                           required>
                                </div>

                                <div class="form-group">
                                    <label for="username">Username <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="username" name="username" 
                                           value="<?php echo htmlspecialchars($petugas['username']); ?>" 
                                           required>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password">Password Baru (Kosongkan jika tidak ingin mengubah)</label>
                                            <input type="password" class="form-control" id="password" name="password" 
                                                   minlength="6">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="confirm_password">Konfirmasi Password Baru</label>
                                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                                                   minlength="6">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="level">Level <span class="text-danger">*</span></label>
                                    <select class="form-control" id="level" name="level" required>
                                        <option value="">Pilih Level</option>
                                        <option value="admin" <?php echo ($petugas['level'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                                        <option value="petugas" <?php echo ($petugas['level'] == 'petugas') ? 'selected' : ''; ?>>Petugas</option>
                                    </select>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update
                                </button>
                                <a href="index.php" class="btn btn-secondary">
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

<?php 
$conn->close();
require_once '../../includes/footer.php'; 
?>
<?php ob_end_flush(); ?> 