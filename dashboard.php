<?php
require_once 'functions/functions.php';
redirectIfNotLoggedIn();
require_once 'config/database.php';
require_once 'includes/header.php';
require_once 'includes/sidebar.php';

// Hitung total data
$query_customer = "SELECT COUNT(*) as total FROM customer";
$query_sales = "SELECT COUNT(*) as total FROM sales";
$query_item = "SELECT COUNT(*) as total FROM item";
$query_transaction = "SELECT COUNT(*) as total FROM transaction";

$result_customer = $conn->query($query_customer);
$result_sales = $conn->query($query_sales);
$result_item = $conn->query($query_item);
$result_transaction = $conn->query($query_transaction);

$total_customer = $result_customer->fetch_assoc()['total'];
$total_sales = $result_sales->fetch_assoc()['total'];
$total_item = $result_item->fetch_assoc()['total'];
$total_transaction = $result_transaction->fetch_assoc()['total'];

$conn->close();
?>

<style>
.custom-card {
    background: white !important;
    border-radius: 8px !important;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1) !important;
    border-left: 5px solid !important;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    margin-bottom: 20px;
    cursor: pointer;
    text-decoration: none;
    display: block;
}

.custom-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.15) !important;
    text-decoration: none;
}

.custom-card .inner {
    padding: 20px;
    color: #333 !important;
}

.custom-card .inner h3 {
    font-size: 2.2rem;
    font-weight: 700;
    margin: 0;
    color: #333 !important;
}

.custom-card .inner p {
    font-size: 1rem;
    margin: 5px 0 0 0;
    color: #666 !important;
    font-weight: 500;
}

.custom-card .icon {
    position: absolute;
    top: 15px;
    right: 15px;
    font-size: 2.5rem;
    opacity: 0.3;
}

.card-customer {
    border-left-color: #17a2b8 !important;
}

.card-customer .icon {
    color: #17a2b8 !important;
}

.card-sales {
    border-left-color: #28a745 !important;
}

.card-sales .icon {
    color: #28a745 !important;
}

.card-produk {
    border-left-color: #ffc107 !important;
}

.card-produk .icon {
    color: #ffc107 !important;
}

.card-transaksi {
    border-left-color: #dc3545 !important;
}

.card-transaksi .icon {
    color: #dc3545 !important;
}
</style>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active">
                            <?php echo ucfirst(basename($_SERVER['PHP_SELF'], '.php')); ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-6">
                    <a href="modules/customer/index.php" class="custom-card card-customer">
                        <div class="small-box">
                            <div class="inner">
                                <h3><?php echo $total_customer; ?></h3>
                                <p>Customer</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-6">
                    <a href="modules/sales/index.php" class="custom-card card-sales">
                        <div class="small-box">
                            <div class="inner">
                                <h3><?php echo $total_sales; ?></h3>
                                <p>Sales</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-user-tie"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-6">
                    <a href="modules/produk/index.php" class="custom-card card-produk">
                        <div class="small-box">
                            <div class="inner">
                                <h3><?php echo $total_item; ?></h3>
                                <p>Produk</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-boxes"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-6">
                    <a href="modules/transaksi/index.php" class="custom-card card-transaksi">
                        <div class="small-box">
                            <div class="inner">
                                <h3><?php echo $total_transaction; ?></h3>
                                <p>Transaksi</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>

<?php require_once 'includes/footer.php'; ?>