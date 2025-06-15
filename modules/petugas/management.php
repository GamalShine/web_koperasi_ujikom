<?php
require_once 'functions/functions.php';
redirectIfNotLoggedIn();
// Hanya admin yang bisa akses
if (!isAdmin()) {
    header("Location: dashboard.php");
    exit();
}
require_once 'config/database.php';
require_once 'includes/header.php';
require_once 'includes/sidebar.php';

// Query untuk mengambil data petugas
$query = "SELECT * FROM petugas ORDER BY nama_petugas ASC";
$result = $conn->query($query);
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Data Petugas</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active">Petugas</li>
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
                            <h3 class="card-title">Daftar Petugas</h3>
                            <div class="card-tools">
                                <a href="includes/add_petugas.php" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Tambah Petugas
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover" style="width: 100%;">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th style="width: 5%;">No</th>
                                            <th style="width: 20%;">Username</th>
                                            <th style="width: 35%;">Nama Petugas</th>
                                            <th style="width: 15%;">Status</th>
                                            <th style="width: 25%;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $no = 1;
                                        while ($row = $result->fetch_assoc()): 
                                        ?>
                                        <tr>
                                            <td><?php echo $no++; ?></td>
                                            <td><?php echo $row['username']; ?></td>
                                            <td><?php echo $row['nama_petugas']; ?></td>
                                            <td>
                                                <span
                                                    class="badge badge-<?php echo $row['level'] == 'admin' ? 'danger' : 'info'; ?>">
                                                    <?php echo ucfirst($row['level']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="/edit_petugas.php?id=<?php echo $row['id_petugas']; ?>"
                                                    class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <?php if ($row['id_petugas'] != $_SESSION['user_id']): ?>
                                                <a href="/delete_petugas.php?id=<?php echo $row['id_petugas']; ?>"
                                                    class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                <?php endif; ?>
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
require_once 'includes/footer.php'; 
?>