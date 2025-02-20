<?php
session_start();
include 'ardi_koneksi.php';

if ($_SESSION['status'] != 'login') {
    echo "<script>
    alert('Anda belum login!');
    location.href='ardi_dasboard_public.php';
    </script>";
}

$ardi_UserID = $_SESSION['userid'];

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

$whereClause = "WHERE foto.userid = '$ardi_UserID'";

if ($filter == 'week') {
    $whereClause .= " AND YEARWEEK(tanggalupload, 1) = YEARWEEK(CURDATE(), 1)";
} elseif ($filter == 'month') {
    $whereClause .= " AND MONTH(tanggalupload) = MONTH(CURDATE()) 
                      AND YEAR(tanggalupload) = YEAR(CURDATE())";
} elseif ($filter == 'day') {
    $whereClause .= " AND DATE(tanggalupload) = CURDATE()";
}

$queryFoto = mysqli_query($ardi_conn, "SELECT foto.*, 
    (SELECT COUNT(*) FROM likefoto WHERE likefoto.fotoid = foto.fotoid) AS jumlah_like
    FROM foto 
    $whereClause");

$totalFoto = mysqli_fetch_assoc(mysqli_query($ardi_conn, "SELECT COUNT(*) AS total FROM foto WHERE userid = '$ardi_UserID'"))['total'];
$totalAlbum = mysqli_fetch_assoc(mysqli_query($ardi_conn, "SELECT COUNT(*) AS total FROM album WHERE userid = '$ardi_UserID'"))['total'];
$totalLike = mysqli_fetch_assoc(mysqli_query($ardi_conn, "SELECT COUNT(*) AS total FROM likefoto WHERE fotoid IN (SELECT fotoid FROM foto WHERE userid = '$ardi_UserID')"))['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Galeri Foto</title>
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
   
    <style>
        body { background-color: #f8f9fa; }
        .navbar { box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); background-color: #343a40 !important; }
        .navbar-brand, .nav-link, .btn-outline-danger { color: white !important; }
        
        .stat-card { 
            background: linear-gradient(135deg, #007bff, #0056b3); 
            color: white; 
            padding: 20px; 
            border-radius: 10px; 
            text-align: center; 
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .stat-card h4 { font-size: 1.2rem; margin-bottom: 5px; font-weight: 600; }
        .stat-card p { font-size: 2rem; font-weight: bold; margin: 0; }

        .filter-btn { margin-right: 10px; font-weight: 500; }
        .table { background: white; border-radius: 8px; overflow: hidden; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); }

        @media print { 
            body * { visibility: hidden; }
            .table, .table * { visibility: visible; }
            .container { width: 100%; margin-top: 20px; }
            .navbar, .stat-card { display: none; }
            .report-title {
                text-align: center;
                font-size: 1.8rem;
                font-weight: bold;
                margin-bottom: 10px;
            }
            .report-date {
                text-align: center;
                font-size: 1.1rem;
                margin-bottom: 15px;
            }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
    <a class="navbar-brand" href="#">GALERI FOTO</a>
        <div class="d-flex">
            <ul class="navbar-nav">
                <a href="ardi_admin.php" class="nav-link fw-bold btn btn-success">Foto</a>
                <a href="ardi_album.php" class="nav-link fw-bold btn btn-success">Data Album</a>
                <a href="ardi_foto.php" class="nav-link fw-bold btn btn-success">Data Foto</a>
                <a href="ardi_laporan.php" class="nav-link fw-bold btn btn-success">Laporan</a>
                <a href="ardi_prosses_logout.php" class="btn btn-danger ms-2">Logout</a>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-3">
    <h2 class="text-center fw-bold mb-4">Laporan Galeri Foto</h2>
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="stat-card">
                <h4>Total Foto</h4>
                <p><?php echo $totalFoto; ?></p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <h4>Total Album</h4>
                <p><?php echo $totalAlbum; ?></p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <h4>Total Like</h4>
                <p><?php echo $totalLike; ?></p>
            </div>
        </div>
    </div>

    <div class="d-flex mb-3">
        <a href="?filter=all" class="btn btn-outline-secondary filter-btn">Semua</a>
        <a href="?filter=day" class="btn btn-outline-primary filter-btn">Hari Ini</a>
        <a href="?filter=week" class="btn btn-outline-primary filter-btn">Minggu Terakhir</a>
        <a href="?filter=month" class="btn btn-outline-primary filter-btn">Bulan Terakhir</a>
        <button class="btn btn-success" onclick="printLaporan()">Cetak Laporan</button>
    </div>
    
    <script>
        function printLaporan() {
            window.print();
        }
    </script>

    <div class="card">
        <div class="card-header bg-primary text-white">Data Foto</div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr class="table-primary">
                        <th>No</th>
                        <th>Foto</th>
                        <th>Judul</th>
                        <th>Deskripsi</th>
                        <th>Tanggal</th>
                        <th>Jumlah Like</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $no = 1;
                    $totalLikeKeseluruhan = 0;
                    while ($data = mysqli_fetch_array($queryFoto)) {
                        $totalLikeKeseluruhan += $data['jumlah_like'];
                    ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><img src="assets/img/<?php echo $data['tempatfile']; ?>" width="80" class="rounded"></td>
                            <td><?php echo $data['judulfoto']; ?></td>
                            <td><?php echo $data['deskripsifoto']; ?></td>
                            <td><?php echo date("d M Y", strtotime($data['tanggalupload'])); ?></td>
                            <td><?php echo $data['jumlah_like']; ?> Like</td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr class="table-secondary">
                        <td colspan="5"><strong>Total Like Keseluruhan:</strong></td>
                        <td><strong><?php echo $totalLikeKeseluruhan; ?> Like</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

</body>
</html>