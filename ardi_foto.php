<?php
session_start();
include 'ardi_koneksi.php';
if($_SESSION['status'] != 'login'){
   echo "<script>
   alert('anda belum login!');
   location.href='ardi_dasboard_public.php';
</script>";
}
$ardi_UserID = $_SESSION['userid'];
$search = isset($_GET['search']) ? mysqli_real_escape_string($ardi_conn, $_GET['search']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 3;
$offset = ($page - 1) * $limit;

$whereClause = "WHERE userid='$ardi_UserID'";
if (!empty($search)) {
    $whereClause .= " AND (fotoid LIKE '%$search%' OR judulfoto LIKE '%$search%' OR deskripsifoto LIKE '%$search%' OR tanggalupload LIKE '%$search%')";
}

$queryCount = "SELECT COUNT(*) as total FROM foto $whereClause";
$resultCount = mysqli_query($ardi_conn, $queryCount);
$totalData = mysqli_fetch_assoc($resultCount)['total'];
$totalPages = ceil($totalData / $limit);

$query = "SELECT * FROM foto $whereClause ORDER BY tanggalupload DESC LIMIT $limit OFFSET $offset";
$result = mysqli_query($ardi_conn, $query);
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

<div class="container mt-4">
    <div class="row">
        <div class="col-md-4">
            <div class="card mt-2">
                <div class="card-header">Tambah Foto</div>
                <div class="card-body">
                    <form action="ardi_prosses_foto.php" method="POST" enctype="multipart/form-data">
                        <label class="form-label fw-bold">Judul Foto</label>
                        <input type="text" name="judulfoto" class="form-control" required>
                        <label class="form-label fw-bold">Deskripsi</label>
                        <textarea class="form-control" name="deskripsifoto" required></textarea>
                        <label class="form-label fw-bold">Album</label>
                        <select class="form-control" name="albumid" required>
                            <?php
                            $sql_album = mysqli_query($ardi_conn, "SELECT * FROM album WHERE userid='$ardi_UserID'");
                            while($ardi_data_album = mysqli_fetch_array($sql_album)) {
                            ?>
                                <option value="<?php echo $ardi_data_album['albumid']; ?>">
                                    <?php echo $ardi_data_album['namaalbum']; ?>
                                </option>
                            <?php } ?>
                        </select>
                        <label class="form-label fw-bold">Lokasi File</label>
                        <input type="file" class="form-control" name="tempatfile" required>
                        <hr>
                        <button type="submit" class="btn btn-primary" name="tambah">Tambah Data</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card mt-2">
                <div class="card-header">Data Galeri Foto</div>
                <div class="card-body">
                    <table class="table table-striped-primary">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Foto</th>
                                <th>Judul Foto</th>
                                <th>Deskripsi</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $ardi_No = $offset + 1;
                            while($ardi_Data = mysqli_fetch_array($result)) {
                            ?>
                                <tr>
                                    <td><?php echo $ardi_No++; ?></td>
                                    <td><img src="assets/img/<?php echo $ardi_Data['tempatfile']; ?>" width="100"></td>
                                    <td><?php echo $ardi_Data['judulfoto']; ?></td>
                                    <td><?php echo $ardi_Data['deskripsifoto']; ?></td>
                                    <td><?php echo $ardi_Data['tanggalupload']; ?></td>
                                    <td>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Edit<?php echo $ardi_Data['fotoid']; ?>">Edit</button>
                                        <div class="modal fade" id="Edit<?php echo $ardi_Data['fotoid']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Data</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="ardi_prosses_foto.php" method="POST" enctype="multipart/form-data">
                                                            <input type="hidden" name="fotoid" value="<?php echo $ardi_Data['fotoid']; ?>">
                                                            <label class="form-label fw-bold">Judul Foto</label>
                                                            <input type="text" name="judulfoto" value="<?php echo $ardi_Data['judulfoto']; ?>" class="form-control" required>
                                                            <label class="form-label fw-bold">Deskripsi</label>
                                                            <textarea class="form-control" name="deskripsifoto" required><?php echo $ardi_Data['deskripsifoto']; ?></textarea>
                                                            <label class="form-label fw-bold">Album</label>
                                                            <select class="form-control" name="albumid">
                                                                <?php
                                                                $sql_album = mysqli_query($ardi_conn, "SELECT * FROM album WHERE userid='$ardi_UserID'");
                                                                while($ardi_data_album = mysqli_fetch_array($sql_album)) {
                                                                ?>
                                                                    <option <?php if($ardi_data_album['albumid'] == $ardi_Data['albumid']) { echo 'selected="selected"'; } ?>
                                                                        value="<?php echo $ardi_data_album['albumid']; ?>">
                                                                        <?php echo $ardi_data_album['namaalbum']; ?>
                                                                    </option>
                                                                <?php } ?>
                                                            </select>
                                                            <label class="form-label fw-bold">Foto</label>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <img src="assets/img/<?php echo $ardi_Data['tempatfile']; ?>" width="100">
                                                                </div>
                                                                <div class="col-md-8">
                                                                    <label class="form-label fw-bold">Ganti File</label>
                                                                    <input type="file" class="form-control" name="tempatfile">
                                                                </div>
                                                            </div>
                                                    </div>
                                                    
                                                    <div class="modal-footer">
                                                        <button type="submit" name="Edit" class="btn btn-primary">Edit Data</button>
    <button type="button" class="btn btn-danger" onclick="confirmDelete('<?php echo $ardi_Data['fotoid']; ?>')">Hapus</button>
</div>

<script>
    function confirmDelete(fotoid) {
        if (confirm('Apakah Anda yakin ingin menghapus foto ini?')) {
            window.location.href = 'ardi_prosses_foto.php?delete=' + fotoid;
        }
    }
</script>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>

                    <nav>
                        <ul class="pagination content-center">
                            <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
                                <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo $search; ?>"> <?php echo $i; ?> </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="d-flex justify-content-center border-top mt-3 bg-light fixed-bottom">
    <p>&copy; 2025 Ardi - Galeri Foto</p>
</footer>

<script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
</body>
</html>
