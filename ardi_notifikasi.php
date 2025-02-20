<?php
session_start();
include 'ardi_koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
    echo "
        <script>
            alert('Anda Belum Login !');
            location.href='../index.php';
        </script>";
    exit;
}

if (!isset($_SESSION['userid'])) {
    echo "
        <script>
            alert('User ID tidak ditemukan!');
            location.href='../index.php';
        </script>";
    exit;
}

$userid = $_SESSION['userid'];
 

$notif_query = mysqli_query($koneksi, "SELECT n.*, f.tempatfile, f.judulfoto, u.username 
                                        FROM notifikasi n
                                        JOIN foto f ON n.fotoid = f.fotoid
                                        JOIN users u ON n.send_id = u.userid
                                        WHERE n.userid = '$userid' 
                                        AND n.status = 'unread' ORDER BY n.tanggal DESC");
                                    

$notif_count_query = mysqli_query($koneksi, "SELECT COUNT(*) as unread_count FROM notifikasi WHERE userid = '$userid' AND status = 'unread'");
$notif_count_result = mysqli_fetch_assoc($notif_count_query);
$unread_count = $notif_count_result['unread_count'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
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
        }.footer {
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
        .position-relative {
            position: relative;
        }

        .position-absolute {
            position: absolute;
        }

        .translate-middle {
            transform: translate(-50%, -50%);
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.5rem;
        }

        .bg-danger {
            background-color: #dc3545;
        }
        .card-body {
            padding: 0.75rem;
        }
    </style>
</head>
<body class="bg-black">

<nav class="navbar navbar-expand-lg bg-dark navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Galeri Foto</a>
        <div class="collapse navbar-collapse justify-content-end">
            <form class="d-flex" method="GET">
                <input class="form-control me-2" type="search" name="search" placeholder="Cari Foto/Album" value="<?php echo htmlspecialchars($search); ?>">
                <button class="btn btn-outline-light" type="submit">Cari</button>
            </form>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
        <a href="ardi_admin.php" class="nav-link fw-bold btn btn-success">Foto</a>
        <a href="notifikasi.php" class="nav-link m-2 position-relative">
                        Notifikasi <i class="bi bi-bell"></i>
                        <?php if ($unread_count > 0) { ?>
                            <span class="top-0 start-100 translate-middle badge rounded-circle bg-danger  w-auto h-auto">
                                <?php echo $unread_count; ?>
                            </span>
                        <?php } ?>
                    </a>
        <a href="ardi_album.php" class="nav-link fw-bold btn btn-success">Data Album</a>
        <a href="ardi_foto.php" class="nav-link fw-bold btn btn-success">Data Foto</a>
        <a href="ardi_laporan.php" class="nav-link fw-bold btn btn-success">laporan</a>
      </ul>
            </div>
            <a href="ardi_prosses_logout.php" class="btn btn-danger ms-2">Logout</a>
        </div>
    </div>
</nav>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
  <div class="container">
  <a class="navbar-brand" href="#">GALERI FOTO</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
    <ul class="navbar-nav">
        <a href="ardi_home.php" class="nav-link fw-bold btn btn-success">Foto</a>

      </ul>      <a href="ardi_prosses_logout.php" class="btn btn-danger ms-2">Logout</a>
    </div>
  </div>
</nav>

    <div class="container mt-4">
        <h2 class="mb-3 text-white">Notifikasi</h2>
        <div class="row">
            <div class="col-6">
                <div class="d-flex justify-content-start">
                <button id="markRead" class="btn btn-primary mb-3"  data-toggle='tooltip' title='Bersihkan Notifikasi'>Tandai Semua Telah Dibaca</button> 
                </div>
            </div>
            <div class="col-6">
                <div class="d-flex justify-content-end ms-auto">
                    <a href="notifikasi_read.php" class="btn btn-success mb-3 ms-5"  data-toggle='tooltip' title='Lihat Notifikasi'>
                    Notifikasi Telah Dibaca
                    </a>
                </div>
            </div>
        </div>

        <div class="row col-12 m-1">
            <?php if (mysqli_num_rows($notif_query) > 0) { ?>
                <div class="list-group">
                    <?php while ($notif = mysqli_fetch_array($notif_query)) { ?>
                        <div class="card notification-card mb-2">
                            <div class="card notification-card mb-2">
                                <div class="card-body d-flex align-items-center">
                                    <div class="col-1">
                                        <img src='../assets/img/<?php echo $notif['tempatfile']; ?>' class='rounded me-3' width='50' height='50'>
                                    </div>
                                    <div class="col-10">
                                        <div>
                                            <strong><?php echo $notif['username']; ?></strong> <?php echo str_replace($notif['username'], '', $notif['message']); ?>
                                            <br><small class='text-muted'><?php echo $notif['tanggal']; ?></small>
                                        </div>
                                    </div>
                                    <div class="col-1">
                                    <a href='foto_detail.php?fotoid=<?php echo $notif['fotoid']; ?>' class='btn btn-sm btn-primary ms-auto'>Lihat Foto</a>
                                    </div>
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
    
    
    
    <script type="text/javascript" src="../assets/js/bootstrap.min.js">

    </script>

    <script>
        document.getElementById('markRead').addEventListener('click', function() {
            fetch('../c/tandai_read.php', { method: 'POST' })
                .then(response => response.text())
                .then(data => {
                    alert(data);
                    location.reload();
                });
        });
    </script>
</body>
</html>
