<?php
session_start();
include 'ardi_koneksi.php';

// Pastikan koneksi database ada
if (!isset($koneksi) || !$koneksi) {
    die("Koneksi database tidak ditemukan.");
}

// Pastikan user sudah login
if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
    echo "<script>alert('Anda Belum Login !'); location.href='../index.php';</script>";
    exit;
}

if (!isset($_SESSION['userid'])) {
    echo "<script>alert('User ID tidak ditemukan!'); location.href='../index.php';</script>";
    exit;
}

$userid = mysqli_real_escape_string($koneksi, $_SESSION['userid']);

// Query untuk mendapatkan notifikasi
$notif_query = mysqli_query($koneksi, "SELECT n.*, f.tempatfile, f.judulfoto, u.username 
                                       FROM notifikasi n
                                       JOIN foto f ON n.fotoid = f.fotoid
                                       JOIN users u ON n.send_id = u.userid
                                       WHERE n.userid = '$userid' 
                                       AND n.status = 'unread' 
                                       ORDER BY n.tanggal DESC");

$notif_count_query = mysqli_query($koneksi, "SELECT COUNT(*) as unread_count FROM notifikasi WHERE userid = '$userid' AND status = 'unread'");
$notif_count_result = mysqli_fetch_assoc($notif_count_query);
$unread_count = $notif_count_result['unread_count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <style>
        .navbar {
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            background-color: #343a40 !important;
        }
        .navbar-brand, .nav-link, .btn-outline-danger {
            color: white !important;
        }
        .footer {
            text-align: center;
            padding: 15px;
            color: #6c757d;
            background-color: #fff;
            box-shadow: 0 -2px 8px rgba(0, 0, 0, 0.1);
            margin-top: auto;
        }
        .notification-card {
            border-radius: 10px;
            transition: 0.3s;
            background-color: #fff;
            padding: 1rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .notification-card:hover {
            background-color: #e9ecef;
        }
        .badge {
            font-size: 0.75rem;
            padding: 0.5rem;
        }
    </style>
</head>
<body class="bg-black">

<nav class="navbar navbar-expand-lg bg-dark navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Galeri Foto</a>
        <div class="collapse navbar-collapse justify-content-end">
            <a href="ardi_prosses_logout.php" class="btn btn-danger ms-2">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h2 class="mb-3 text-white">Notifikasi</h2>
    <div class="row col-12 m-1">
        <?php if (mysqli_num_rows($notif_query) > 0) { ?>
            <div class="list-group">
                <?php while ($notif = mysqli_fetch_array($notif_query)) { ?>
                    <div class="card notification-card mb-2">
                        <div class="card-body d-flex align-items-center">
                            <div class="col-1">
                                <img src='../assets/img/<?php echo htmlspecialchars($notif['tempatfile']); ?>' class='rounded me-3' width='50' height='50'>
                            </div>
                            <div class="col-10">
                                <div>
                                    <strong><?php echo htmlspecialchars($notif['username']); ?></strong> <?php echo htmlspecialchars(str_replace($notif['username'], '', $notif['message'])); ?>
                                    <br><small class='text-muted'><?php echo htmlspecialchars($notif['tanggal']); ?></small>
                                </div>
                            </div>
                            <div class="col-1">
                                <a href='foto_detail.php?fotoid=<?php echo htmlspecialchars($notif['fotoid']); ?>' class='btn btn-sm btn-primary ms-auto'>Lihat Foto</a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } else { ?>
            <div class='alert alert-warning text-center'>Tidak ada notifikasi baru.</div>
        <?php } ?>
    </div>
</div>

<script type="text/javascript" src="../assets/js/bootstrap.min.js"></script>
</body>
</html>
