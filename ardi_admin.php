<?php
session_start();
include 'ardi_koneksi.php';

if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

$ardi_userid = $_SESSION['userid'];

if (!isset($ardi_conn)) {
    die("Koneksi database tidak ditemukan.");
}

if (!isset($_SESSION['userid']) || $_SESSION['roleid'] != 1) {
    header("Location: ardi_login.php");
    exit();
}

date_default_timezone_set('Asia/Jakarta'); 




$ardi_userid = $_SESSION['userid'];
$items_per_page = 8;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;
$search = isset($_GET['search']) ? mysqli_real_escape_string($ardi_conn, $_GET['search']) : '';
$albumid = isset($_GET['albumid']) ? mysqli_real_escape_string($ardi_conn, $_GET['albumid']) : '';

$album_filter = $albumid ? "AND albumid='$albumid'" : '';

$query = "SELECT * FROM foto WHERE (judulfoto LIKE '%$search%' OR deskripsifoto LIKE '%$search%') $album_filter LIMIT $items_per_page OFFSET $offset";
$total_query = "SELECT COUNT(*) AS total FROM foto WHERE (judulfoto LIKE '%$search%' OR deskripsifoto LIKE '%$search%') $album_filter";

$result = mysqli_query($ardi_conn, $query);
$total_result = mysqli_query($ardi_conn, $total_query);
$total_rows = mysqli_fetch_assoc($total_result)['total'];
$total_pages = ceil($total_rows / $items_per_page);
$albums = mysqli_query($ardi_conn, "SELECT DISTINCT albumid, namaalbum FROM album");


if (isset($_GET['delete_comment_id'])) {
    $comment_id = $_GET['delete_comment_id'];
    $delete_query = "DELETE FROM komenfoto WHERE komenid = '$comment_id' AND userid = '$ardi_userid'";
    mysqli_query($ardi_conn, $delete_query);
    header("Location: " . $_SERVER['PHP_SELF'] . "?search=" . urlencode($search) . "&page=" . $page);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Galeri Foto</title>
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <style>
      body {
          background-color: #f8f9fa;
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
        <a href="ardi_album.php" class="nav-link fw-bold btn btn-success">Data Album</a>
        <a href="ardi_foto.php" class="nav-link fw-bold btn btn-success">Data Foto</a>
        <a href="ardi_laporan.php" class="nav-link fw-bold btn btn-success">laporan</a>
      </ul>
            </div>
            <a href="ardi_prosses_logout.php" class="btn btn-danger ms-2">Logout</a>
        </div>
    </div>
</nav>
<div class="container mt-3">
  <div class="row">
    
  <form class="d-flex" method="GET">
                <select class="form-control me-2" name="albumid" onchange="this.form.submit()">
                    <option value="">Semua Album</option>
                    <?php while ($album = mysqli_fetch_assoc($albums)) { ?>
                        <option value="<?php echo $album['albumid']; ?>" <?php echo ($albumid == $album['albumid']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($album['namaalbum']); ?></option>
                    <?php } ?>
                </select>
                <button class="btn btn-outline-light" type="submit">Cari</button>
            </form>
    <?php
    
    while ($ardi_data = mysqli_fetch_array($result)) {
        $fotoid = $ardi_data['fotoid'];

        $ardi_jumkomen = mysqli_query($ardi_conn, "SELECT COUNT(*) AS jumlah FROM komenfoto WHERE fotoid='$fotoid'");
        $jumkomen = mysqli_fetch_assoc($ardi_jumkomen)['jumlah'];

        $ardi_ceksuka = mysqli_query($ardi_conn, "SELECT * FROM likefoto WHERE fotoid='$fotoid' AND userid='$ardi_userid'");
        $liked = (mysqli_num_rows($ardi_ceksuka) > 0);

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

<div class="modal fade" id="komentar<?php echo $fotoid; ?>" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8">
                        <img id="printableImage<?php echo $fotoid; ?>" style="width: 100%;" src="assets/img/<?php echo htmlspecialchars($ardi_data['tempatfile']); ?>">
                    </div>
                    <div class="col-md-4">
                        <h5><?php echo htmlspecialchars($ardi_data['judulfoto']); ?></h5>
                        <p><strong>Tanggal:</strong> <?php echo htmlspecialchars($ardi_data['tanggalupload']); ?></p>
                        <p><?php echo htmlspecialchars($ardi_data['deskripsifoto']); ?></p>
                        <hr>
                        <h5>Komentar:</h5>

<?php
$ardi_komen = mysqli_query($ardi_conn, "SELECT komenfoto.*, user.namalengkap 
                                        FROM komenfoto 
                                        INNER JOIN user ON komenfoto.userid = user.userid 
                                        WHERE komenfoto.fotoid='$fotoid' 
                                        ORDER BY komenfoto.tanggalkomen DESC");

while ($row = mysqli_fetch_array($ardi_komen)) {
    $date = new DateTime($row['tanggalkomen'], new DateTimeZone('Asia/Jakarta'));
    $timeAgo = $date->getTimestamp();

    echo '<p><strong>' . htmlspecialchars($row['namalengkap']) . ':</strong> ' . htmlspecialchars($row['isikomen']) . '<br>';
    echo '<small class="time-ago" data-time="' . $timeAgo . '">' . $date->format('Y-m-d H:i:s') . '</small>';
    echo '</p>';
}
?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
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
