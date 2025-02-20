<?php
session_start();
include 'ardi_koneksi.php';

if ($_SESSION['status'] != 'login') {
    echo "<script>
    alert('Anda harus login terlebih dahulu!');
    location.href = 'ardi_login.php';
    </script>";
}


$per_page = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($page - 1) * $per_page;
$userid = $_SESSION['userid'];
$search = isset($_GET['search']) ? mysqli_real_escape_string($ardi_conn, $_GET['search']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 3;
$offset = ($page - 1) * $limit;

$whereClause = "WHERE userid='$userid'";
if (!empty($search)) {
    $whereClause .= " AND (fotoid LIKE '%$search%' OR judulfoto LIKE '%$search%' OR deskripsifoto LIKE '%$search%' OR tanggalupload LIKE '%$search%')";
}

$queryCount = "SELECT COUNT(*) as total FROM foto $whereClause";
$resultCount = mysqli_query($ardi_conn, $queryCount);
$totalData = mysqli_fetch_assoc($resultCount)['total'];
$totalPages = ceil($totalData / $limit);

$query = "SELECT * FROM foto $whereClause ORDER BY tanggalupload DESC LIMIT $limit OFFSET $offset";
$result = mysqli_query($ardi_conn, $query);

$query = "SELECT notifications.id, notifications.content, notifications.created_at, notifications.is_read, user.username AS action_user, foto.tempatfile AS foto_path
    FROM notifications
    JOIN user ON notifications.action_userid = user.userid
    LEFT JOIN foto ON foto.fotoid = notifications.fotoid
    WHERE notifications.userid = '$userid'
    ORDER BY notifications.created_at DESC
    LIMIT $start_from, $per_page";

$result = mysqli_query($ardi_conn, $query);

// Query untuk menghitung total notifikasi (untuk pagination)
$total_query = "SELECT COUNT(*) AS total FROM notifications WHERE userid = '$userid'";
$total_result = mysqli_query($ardi_conn, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total = $total_row['total'];
$pages = ceil($total / $per_page);

$hitung_notif = "SELECT COUNT(*) AS belum_dibaca FROM notifications WHERE userid = '$userid' AND is_read = 0";
$hasil = mysqli_query($ardi_conn, $hitung_notif);
$belum_dibaca = mysqli_fetch_assoc($hasil)['belum_dibaca'];

$no = $start_from + 1;

?>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ardi notif</title>
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <style>
       .navbar {
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            background-color: #343a40 !important;
        }
        .navbar-brand, .nav-link, .btn-outline-danger {
            color: white !important;
        }.footer {
            text-align: center;
            padding: 15px;
            color: #6c757d;
            background-color: #fff;
            box-shadow: 0 -2px 8px rgba(0, 0, 0, 0.1);
            margin-top: auto;
        }

      
    </style>
</head>

<body>
<nav class="navbar navbar-expand-lg bg-dark navbar-dark">
    <div class="container-fluid">
    <a class="navbar-brand" href="#">GALERI FOTO</a>
        <div class="collapse navbar-collapse justify-content-end">
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
      <a href="ardi_notifikasi.php" class="nav-link position-relative">
                    <i class="fa fa-bell-o" style="font-weight: bold; font-size: 1.3em; margin-top:4px;"></i>
                    <span class="top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        <?php echo $belum_dibaca ?: '0'; ?>
                    </span>
                </a>
        <a href="ardi_admin.php" class="nav-link fw-bold btn btn-success">Foto</a>
        <a href="ardi_album.php" class="nav-link fw-bold btn btn-success">Data Album</a>
        <a href="ardi_foto.php" class="nav-link fw-bold btn btn-success">Data Foto</a>
        <a href="ardi_laporan.php" class="nav-link fw-bold btn btn-success">laporan</a>
       
      </ul>
    </div>
            <a href="ardi_prosses_logout.php" class="btn btn-danger ms-2">Logout</a>
        </div>
    </div>
</nav>
    
<div class="container mt-3" style="margin-bottom:20px;">
        <div class="header-container">
            <h2 class="text-secondary" style="margin-bottom: 20px;">Notifikasi</h2>

            <?php if (mysqli_num_rows($result) > 0) : ?>
                <div class="action-buttons">
                    <form action="proses_tandai_baca.php" method="POST" class="action-form">
                        <button type="submit" class="btn btn-warning btn-sm">
                            <i class="fa fa-check-circle-o"></i>
                            <span class="tooltip-text">Tandai Semua Dibaca</span>
                        </button>
                    </form>

                    <form action="proses_clear_notifikasi.php" method="POST" class="action-form">
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fa fa-trash-o"></i>
                            <span class="tooltip-text">Hapus Semua Notifikasi</span>
                        </button>
                    </form>
                </div>
            <?php endif; ?>
        </div>

        <!-- Notifikasi Status -->
        <?php if (isset($_GET['status'])) : ?>
            <?php
            $statusMessages = [
                'success' => 'Semua notifikasi berhasil dihapus.',
                'error' => 'Terjadi kesalahan saat menghapus notifikasi.',
                'berhasil_tandai' => 'Semua notifikasi berhasil ditandai sebagai dibaca.',
                'gagal_tandai' => 'Terjadi kesalahan saat menandai sebagai dibaca.'
            ];
            if (isset($statusMessages[$_GET['status']])) : ?>
                <div class="alert alert-<?php echo ($_GET['status'] == 'success' || $_GET['status'] == 'berhasil_tandai') ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
                    <?php echo $statusMessages[$_GET['status']]; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Daftar Notifikasi -->
        <?php if (mysqli_num_rows($result) > 0) : ?>
            <?php while ($row = mysqli_fetch_assoc($result)) :
                // Format waktu ke Asia/Jakarta
                $date = new DateTime($row['created_at'], new DateTimeZone('Asia/Jakarta'));
                $timeAgo = $date->getTimestamp();
                $isUnread = ($row['is_read'] == 0);
                $cardClass = $isUnread ? 'notification-card notification-unread' : 'notification-card';
            ?>
                <div class="<?php echo $cardClass; ?>">
                    <div class="notification-content">
                        <p style="width: 90%;">
                            <span class="username"><?php echo htmlspecialchars($row['action_user']); ?></span>
                            <?php echo htmlspecialchars($row['content']); ?>
                        </p>
                        <div class="notification-time" style="margin-top: -10px;">
                            <small class="time-ago" data-time="<?php echo $timeAgo; ?>">
                                <?php echo $date->format('Y-m-d H:i:s'); ?>
                            </small>
                        </div>
                    </div>
                    <img src="../assets/img/<?php echo htmlspecialchars($row['foto_path']); ?>" alt="Foto yang di-like atau dikomentari" class="notification-img">
                </div>
            <?php endwhile; ?>
        <?php else : ?>
            <p class="text-muted text-center">Tidak ada notifikasi untuk Anda.</p>
        <?php endif; ?>
    </div>

    
    <nav>
                        <ul class="pagination content-center">
                            <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
                                <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo $search; ?>"> <?php echo $i; ?> </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </nav>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    alert.classList.remove('show');
                    alert.classList.add('fade');
                });
            }, 5000);
        });

        function updateTimeAgo() {
            let elements = document.querySelectorAll('.time-ago');
            let now = Math.floor(Date.now() / 1000);

            elements.forEach(el => {
                let commentTime = parseInt(el.getAttribute('data-time'));
                console.log(`Debug JS: Komentar pada ${commentTime}, sekarang ${now}, selisih ${now - commentTime} detik`);

                let timeDiff = now - commentTime;
                el.textContent = getTimeAgoText(timeDiff);
            });
        }

        function getTimeAgoText(seconds) {
            if (seconds < 60) return `${seconds} detik yang lalu`;
            let minutes = Math.floor(seconds / 60);
            if (minutes < 60) return `${minutes} menit yang lalu`;
            let hours = Math.floor(minutes / 60);
            if (hours < 24) return `${hours} jam yang lalu`;
            let days = Math.floor(hours / 24);
            if (days < 7) return `${days} hari yang lalu`;
            let weeks = Math.floor(days / 7);
            if (weeks < 4) return `${weeks} minggu yang lalu`;
            let months = Math.floor(days / 30);
            if (months < 12) return `${months} bulan yang lalu`;
            let years = Math.floor(days / 365);
            return `${years} tahun yang lalu`;
        }

        setInterval(updateTimeAgo, 1000);
        updateTimeAgo();
    </script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>

</body>

</html>