<?php
session_start(); 
include 'ardi_koneksi.php';

$ardi_userid = isset($_SESSION['userid']) ? $_SESSION['userid'] : null;

if ($ardi_userid) {
    echo "Selamat datang, User ID: " . htmlspecialchars($ardi_userid);
} else {
    echo "";
}

$search = isset($_GET['search']) ? mysqli_real_escape_string($ardi_conn, $_GET['search']) : '';

$limit = 8;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;
$total_query = "SELECT COUNT(*) AS total FROM foto";
if (!empty($search)) {
    $total_query .= " WHERE judulfoto LIKE '%$search%'";
}
$total_result = mysqli_query($ardi_conn, $total_query);
$total_data = mysqli_fetch_assoc($total_result)['total'];
$total_pages = ceil($total_data / $limit);

$query = "SELECT * FROM foto";
if (!empty($search)) {
    $query .= " WHERE judulfoto LIKE '%$search%'";
}
$query .= " LIMIT $limit OFFSET $offset";
$result = mysqli_query($ardi_conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri Foto</title>
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <style>
            body {
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
             .btn-primary:hover {
            background-color: #0056b3;
        }
        .footer {
            text-align: center;
            padding: 15px;
            color: #6c757d;
            background-color: #fff;
            box-shadow: 0 -2px 8px rgba(0, 0, 0, 0.1);
            margin-top: auto;
        }
        .navbar {
            padding: 10px 0;
        }
        .form-control {
            border-radius: 8px;
        }

      .navbar {
          box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      }
      .card {
          border-radius: 12px;
          overflow: hidden;
          transition: transform 0.3s ease;
      }
      .card:hover {
          transform: scale(1.05);
      }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container-fluid">
    <a class="navbar-brand" href="#">GALERI FOTO</a>
        <div class="collapse navbar-collapse justify-content-end">
            <form class="d-flex" method="GET">
                <input class="form-control me-2" type="search" name="search" placeholder="Cari Foto/Album" value="<?php echo htmlspecialchars($search); ?>">
                <button class="btn btn-outline-dark" type="submit">Cari</button>
            </form>
            <div class="d-flex">
            <a href="ardi_login.php" class="btn btn-success m-1">Masuk</a>
            <a href="ardi_register.php" class="btn btn-outline-success m-1">Daftar</a>
    </div>  
        </div>
    </div>
</nav>
<div class="container mt-3">
  <div class="row">
    <?php while ($ardi_data = mysqli_fetch_array($result)) {
        $fotoid = $ardi_data['fotoid'];
        $ardi_jumkomen = mysqli_query($ardi_conn, "SELECT COUNT(*) AS jumlah FROM komenfoto WHERE fotoid='$fotoid'");
        $jumkomen = mysqli_fetch_assoc($ardi_jumkomen)['jumlah'];
        
        $ardi_ceksuka = mysqli_query($ardi_conn, "SELECT * FROM likefoto WHERE fotoid='$fotoid' AND userid='$ardi_userid'");
        $liked = ($ardi_userid && mysqli_num_rows($ardi_ceksuka) > 0);
        
        $total_like_query = mysqli_query($ardi_conn, "SELECT COUNT(*) AS total FROM likefoto WHERE fotoid='$fotoid'");
        $total_like = mysqli_fetch_assoc($total_like_query)['total'];
    ?>
     <div class="col-md-3 mt-2">
        <div class="card">
            <img style="height: 12rem;" src="assets/img/<?php echo htmlspecialchars($ardi_data['tempatfile']); ?>" class="card-img-top" title="<?php echo htmlspecialchars($ardi_data['judulfoto']); ?>">
            <div class="card-footer text-center">
                <a href="#">
                    <i class="<?php echo $liked ? 'fa fa-heart' : 'fa-regular fa-heart'; ?>"></i>
                </a> <?php echo $total_like; ?> Suka
                <a href="#" data-bs-toggle="modal" data-bs-target="#komentar<?php echo $fotoid; ?>">
                    <i class="fa-regular fa-comment"></i>
                </a> <?php echo $jumkomen; ?> komentar
            </div>
        </div>
    </div>
    <?php } ?>
  </div>
</div>
<nav aria-label="Page navigation">
    <ul class="pagination justify-content-center mt-4">
        <?php if ($page > 1) { ?>
            <li class="page-item">
                <a class="page-link" href="?search=<?php echo $search; ?>&page=<?php echo ($page - 1); ?>">Sebelumnya</a>
            </li>
        <?php } ?>
        <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                <a class="page-link" href="?search=<?php echo $search; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>
        <?php } ?>
        <?php if ($page < $total_pages) { ?>
            <li class="page-item">
                <a class="page-link" href="?search=<?php echo $search; ?>&page=<?php echo ($page + 1); ?>">Berikutnya</a>
            </li>
        <?php } ?>
    </ul>
</nav>
<footer class="footer text-center mt-5 py-3 bg-dark text-white">
    <p>&copy; 2025 - Ardi Galeri Foto</p>
</footer>
<script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
