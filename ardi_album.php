<?php
session_start();
include 'ardi_koneksi.php';
if ($_SESSION['status'] != 'login') {
    echo "<script>
    alert('anda belum login!');
    location.href='ardi_dasboard_public.php';
</script>";
}

$limit = 3;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$search = isset($_POST['search']) ? $_POST['search'] : '';
$sql_query = "SELECT * FROM album WHERE userid='{$_SESSION['userid']}' AND (namaalbum LIKE '%$search%' OR deskripsi LIKE '%$search%' OR albumid LIKE '%$search%' OR tanggalbuat LIKE '%$search%') LIMIT $offset, $limit";
$sql = mysqli_query($ardi_conn, $sql_query);

$count_query = "SELECT COUNT(*) AS total FROM album WHERE userid='{$_SESSION['userid']}' AND (namaalbum LIKE '%$search%' OR deskripsi LIKE '%$search%' OR albumid LIKE '%$search%' OR tanggalbuat LIKE '%$search%')";
$count_result = mysqli_query($ardi_conn, $count_query);
$total_data = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_data / $limit);

if (isset($_GET['hapus_albumid'])) {
    $albumid = $_GET['hapus_albumid'];
    $hapus_query = "DELETE FROM album WHERE albumid='$albumid'";
    mysqli_query($ardi_conn, $hapus_query);
    header('Location: ardi_album.php');
    exit();
}

if (isset($_POST['edit_album'])) {
    $albumid = $_POST['albumid'];
    $namaalbum = $_POST['namaalbum'];
    $deskripsi = $_POST['deskripsi'];
    
    $update_query = "UPDATE album SET namaalbum='$namaalbum', deskripsi='$deskripsi' WHERE albumid='$albumid'";
    mysqli_query($ardi_conn, $update_query);
    header('Location: ardi_album.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Galery Poto</title>
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
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
    </style>
</head>
<body>
<nav class="navbar justify- navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
    <div class="d-flex w-100 justify-content-start">
    <a class="navbar-brand" href="#">GALERI FOTO</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
    </div>
    <div class="d-flex w-100 justify-content-end">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav ">
      <a href = "ardi_admin.php" class="nav-link fw-bold btn btn-success" > Home</a>
        <a href = "ardi_album.php" class="nav-link fw-bold btn btn-success"> Data Album</a>
        <a href = "ardi_foto.php" class="nav-link fw-bold btn btn-success"> Data Foto</a>
        <a href="ardi_laporan.php" class="nav-link fw-bold btn btn-success">laporan</a>
        <a href="ardi_prosses_logout.php" class="btn btn-danger m-1">logout</a>
      </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header ">Tambah Album</div>
                <div class="card-body">
                    <form action="ardi_prosses_album.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Album</label>
                            <input type="text" name="namaalbum" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" name="tambah">Tambah Data</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Data Album</div>
                <div class="card-body">
                    <form method="POST" action="ardi_album.php">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Cari berdasarkan ID, Nama, Deskripsi, Tanggal" name="search" value="<?php echo $search; ?>">
                            <button class="btn btn-outline-primary" type="submit">Cari</button>
                        </div>
                    </form>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Deskripsi</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $ardi_No = 1;
                            while ($ardi_Data = mysqli_fetch_array($sql)) {
                            ?>
                            <tr>
                                <td><?php echo $ardi_No++ ?></td>
                                <td><?php echo $ardi_Data['namaalbum'] ?></td>
                                <td><?php echo $ardi_Data['deskripsi'] ?></td>
                                <td><?php echo $ardi_Data['tanggalbuat'] ?></td>
                                <td>
                                <button type="button" class="btn btn-primary px-4" data-bs-toggle="modal" data-bs-target="#Edit<?php echo $ardi_Data['albumid'] ?>">Edit</button>
<a href="#" class="btn btn-danger px-3" onclick="confirmDeleteAlbum('<?php echo $ardi_Data['albumid']; ?>')">Hapus</a>

<script>
    function confirmDeleteAlbum(albumid) {
        if (confirm('Apakah Anda yakin ingin menghapus album ini?')) {
            window.location.href = '?hapus_albumid=' + albumid;
        }
    }
</script>
</td>
                            </tr>

                            <div class="modal fade" id="Edit<?php echo $ardi_Data['albumid'] ?>" tabindex="-1" aria-labelledby="EditModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="ardi_album.php" method="POST">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="EditModalLabel">Edit Album</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="albumid" value="<?php echo $ardi_Data['albumid']; ?>">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Nama Album</label>
                                                    <input type="text" name="namaalbum" class="form-control" value="<?php echo $ardi_Data['namaalbum']; ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Deskripsi</label>
                                                    <textarea class="form-control" name="deskripsi" required><?php echo $ardi_Data['deskripsi']; ?></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary" name="edit_album">Save changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </tbody>
                    </table>

                    <nav>
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?php echo ($page == 1) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo $search; ?>">Previous</a>
                            </li>
                            <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                            <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo $search; ?>"><?php echo $i; ?></a>
                            </li>
                            <?php } ?>
                            <li class="page-item <?php echo ($page == $total_pages) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo $search; ?>">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="d-flex justify-content-center fixed-bottom mt-3 bg-light py-3">
    <p class="mb-0">&copy;  - Ardi 2025 Galeri Foto</p>
</footer>

<script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
</body>
</html>
