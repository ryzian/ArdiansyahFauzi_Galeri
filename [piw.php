<?php
session_start();
include '../config/koneksi.php';
$userid = $_SESSION['userid'];
if ($_SESSION['status'] != 'login') {
    echo "<script>
    alert('Anda harus login terlebih dahulu!');
    location.href = '../index.php';
    </script>";
}

$albumid = isset($_GET['albumid']) ? $_GET['albumid'] : null;
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'album.namaalbum';
$sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'ASC';

// Pagination
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page > 1) ? ($page * $limit) - $limit : 0;

// Function untuk mencetak laporan
function generateReport($koneksi, $userid, $albumid = null, $sort_by = 'album.namaalbum', $sort_order = 'ASC', $start = 0, $limit = 5)
{
    $whereClause = "WHERE album.userid = '$userid'";
    if ($albumid) {
        $whereClause .= " AND album.albumid = '$albumid'";
    }

    $orderBy = "ORDER BY $sort_by $sort_order";
    $limitClause = "LIMIT $start, $limit";

    $query = "SELECT album.albumid, album.namaalbum, 
                     (SELECT lokasifile FROM foto WHERE albumid = album.albumid ORDER BY tanggalunggah DESC LIMIT 1) as lokasifile,
                     COUNT(DISTINCT foto.fotoid) as jumlah_foto, 
                     COUNT(DISTINCT likefoto.likeid) as jumlah_like,    
                     COUNT(DISTINCT komentarfoto.komentarid) as jumlah_komen
              FROM album
              LEFT JOIN foto ON album.albumid = foto.albumid
              LEFT JOIN likefoto ON foto.fotoid = likefoto.fotoid
              LEFT JOIN komentarfoto ON foto.fotoid = komentarfoto.fotoid
              $whereClause
              GROUP BY album.albumid, album.namaalbum
              $orderBy
              $limitClause";

    $result = mysqli_query($koneksi, $query);
    $data = [];

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }

    return $data;
}

$reportData = generateReport($koneksi, $userid, $albumid, $sort_by, $sort_order, $start, $limit);

// Untuk menghitung total data (untuk pagination)
$totalDataQuery = "SELECT COUNT(*) AS total FROM album WHERE userid = '$userid'";
$totalDataResult = mysqli_query($koneksi, $totalDataQuery);
$totalData = mysqli_fetch_assoc($totalDataResult)['total'];
$totalPages = ceil($totalData / $limit);


$hitung_notif = "SELECT COUNT(*) AS belum_dibaca FROM notifications WHERE userid = '$userid' AND is_read = 0";
$hasil = mysqli_query($koneksi, $hitung_notif);
$belum_dibaca = mysqli_fetch_assoc($hasil)['belum_dibaca'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fikri Galeri | Laporan</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" href="https://cdnjs.com/libraries/Chart.js">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .table th {
            background-color: #000;
            color: #fff;
        }

        .img-thumbnail {
            max-width: 100px;
            max-height: 100px;
            object-fit: cover;
        }

        .filter-form {
            margin-bottom: 20px;
        }

        @media print {
            .container {
                text-align: center;
            }

            .navbar {
                display: none;
            }

            .no-print {
                display: none;
            }

            h1 {
                margin-top: 50px;
                font-size: 24px;
            }

            .table {
                margin-top: 20px;
                margin-left: auto;
                margin-right: auto;
                width: 100%;
            }

            .header-print {
                display: block;
                margin-bottom: 20px;
                text-align: center;
                font-size: 16px;
            }

            .header-print span {
                display: inline-block;
                width: 45%;
            }
        }

        .yes {
            background-color: #000;
            color: #fff;
            width: 190px;
        }

        .yes:hover {
            border-color: #000;
            color: #000;
        }
    </style>
</head>

<body>
    <nav class="p-10 shadow-lg navbar navbar-expand-lg navbar-light bg-body-tertiary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">Fikri Galeri</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="mt-1 collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a href="index.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="album.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'album.php') ? 'active' : ''; ?>">Album</a>
                    </li>
                    <li class="nav-item">
                        <a href="foto.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'foto.php') ? 'active' : ''; ?>">Foto</a>
                    </li>
                </ul>
                <a href="profile.php" class="nav-link position-relative" style="margin-right: 30px; margin-bottom: 15px; margin-top:14px;">
                    <i class="fa fa-user-o" style="font-weight: bold; font-size: 1.3em;"></i>
                </a>
                <a href="notifikasi.php" class="nav-link position-relative">
                    <i class="fa fa-bell-o" style="font-weight: bold; font-size: 1.3em;"></i>
                    <span class="top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        <?php echo $belum_dibaca ?: '0'; ?>
                    </span>
                </a>
                <a href="laporan.php" class="nav-link position-relative" style="margin-right: 30px; margin-bottom: 15px; margin-top:14px;">
                    <i class="fa fa-file-text-o" style="font-weight: bold; font-size: 1.3em;"></i>
                </a>
            </div>
        </div>
    </nav>

    <div class="container">
        <h1 class="mt-3 text-secondary">Laporan</h1>
        <div class="chart-container">
            <canvas id="laporanChart"></canvas>
        </div>
        <br>
        <br>
        <div class="filter-form no-print">
            <form method="GET" action="laporan.php">
                <div class="row">
                    <div class="col-md-3">
                        <select name="albumid" class="form-control">
                            <option value="">Semua Album</option>
                            <?php
                            $albums_query = "SELECT * FROM album WHERE userid = '$userid'";
                            $albums_result = mysqli_query($koneksi, $albums_query);
                            while ($album = mysqli_fetch_assoc($albums_result)) :
                            ?>
                                <option value="<?php echo $album['albumid']; ?>" <?php if ($albumid == $album['albumid']) echo 'selected'; ?>>
                                    <?php echo $album['namaalbum']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <!-- Filter Berdasarkan -->
                    <div class="col-md-3">
                        <select name="sort_by" class="form-control">
                            <option value="album.namaalbum" <?php if ($sort_by == 'album.namaalbum') echo 'selected'; ?>>Nama Album</option>
                            <option value="jumlah_like" <?php if ($sort_by == 'jumlah_like') echo 'selected'; ?>>Jumlah Like</option>
                            <option value="jumlah_komen" <?php if ($sort_by == 'jumlah_komen') echo 'selected'; ?>>Jumlah Komentar</option>
                            <option value="jumlah_foto" <?php if ($sort_by == 'jumlah_foto') echo 'selected'; ?>>Jumlah Foto</option>
                        </select>
                    </div>

                    <!-- Urutan -->
                    <div class="col-md-3">
                        <select name="sort_order" class="form-control">
                            <option value="ASC" <?php if ($sort_order == 'ASC') echo 'selected'; ?>>Asc/Tersedikit</option>
                            <option value="DESC" <?php if ($sort_order == 'DESC') echo 'selected'; ?>>Desc/Terbanyak</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="cetak_laporan.php?albumid=<?php echo $albumid; ?>&sort_by=<?php echo $sort_by; ?>&sort_order=<?php echo $sort_order; ?>" class="btn yes">Download Laporan</a>
                    </div>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Album</th>
                        <th>Jumlah Foto</th>
                        <th>Jumlah Like</th>
                        <th>Jumlah Komentar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($reportData) > 0) : ?>
                        <?php foreach ($reportData as $key => $row) : ?>
                            <tr>
                                <td><?php echo $key + 1 + $start; ?></td>
                               
                                <td><?php echo $row['namaalbum']; ?></td>
                                <td><?php echo $row['jumlah_foto']; ?></td>
                                <td><?php echo $row['jumlah_like']; ?></td>
                                <td><?php echo $row['jumlah_komen']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="6">Tidak ada data.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <nav aria-label="Page navigation" class="no-print">
            <ul class="pagination justify-content-center">
                <li class="page-item <?php if ($page <= 1) {
                                            echo 'disabled';
                                        } ?>">
                    <a class="page-link" href="<?php if ($page <= 1) {
                                                    echo '#';
                                                } else {
                                                    echo "?page=" . ($page - 1);
                                                } ?>">Kembali</a>
                </li>
                <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                    <li class="page-item <?php if ($page == $i) {
                                                echo 'active';
                                            } ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?php if ($page >= $totalPages) {
                                            echo 'disabled';
                                        } ?>">
                    <a class="page-link" href="<?php if ($page >= $totalPages) {
                                                    echo '#';
                                                } else {
                                                    echo "?page=" . ($page + 1);
                                                } ?>">Lanjut</a>
                </li>
            </ul>
        </nav>
    </div>

    <script>
        function printReport() {
            // Menampilkan header untuk username dan tanggal cetak
            var header = document.getElementById('print-header');
            header.style.display = 'block';

            // Mengambil tanggal saat ini
            var currentDate = new Date();
            var dateString = currentDate.toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            // Menampilkan tanggal cetak di header
            document.getElementById('print-date').innerText = '' + dateString;

            // Menambahkan event listener untuk menyembunyikan header setelah cetak
            window.onafterprint = function() {
                header.style.display = 'none';
            };

            // Mencetak laporan
            window.print();
        }
        const chartLabels = <?php echo json_encode(array_column($reportData, 'namaalbum')); ?>;
        const jumlahFoto = <?php echo json_encode(array_column($reportData, 'jumlah_foto')); ?>;
        const jumlahLike = <?php echo json_encode(array_column($reportData, 'jumlah_like')); ?>;
        const jumlahKomen = <?php echo json_encode(array_column($reportData, 'jumlah_komen')); ?>;

        const ctx = document.getElementById('laporanChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [
                    {
                        label: 'Jumlah Foto',
                        data: jumlahFoto,
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Jumlah Like',
                        data: jumlahLike,
                        backgroundColor: 'rgba(255, 99, 132, 0.6)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Jumlah Komentar',
                        data: jumlahKomen,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Statistik Album'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

    <script src="../assets/js/bootstrap.min.js"></script>
</body>

</html>