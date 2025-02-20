<?php
session_start();
include 'ardi_koneksi.php';

if ($_SESSION['status'] != 'login') {
    echo "<script>
    alert('anda belum login!');
    location.href='ardi_dasboard_public.php';
    </script>";
}

if (isset($_POST['tambah'])) {
    $judulfoto = mysqli_real_escape_string($ardi_conn, $_POST['judulfoto']);
    $deskripsifoto = mysqli_real_escape_string($ardi_conn, $_POST['deskripsifoto']);
    $albumid = $_POST['albumid'];
    $tempatfile = $_FILES['tempatfile']['name'];
    $targetDir = "assets/img/";

    move_uploaded_file($_FILES['tempatfile']['tmp_name'], $targetDir . $tempatfile);

    $query = "INSERT INTO foto (judulfoto, deskripsifoto, albumid, tempatfile, userid, tanggalupload) 
              VALUES ('$judulfoto', '$deskripsifoto', '$albumid', '$tempatfile', '{$_SESSION['userid']}', NOW())";
    mysqli_query($ardi_conn, $query);
    header("Location: ardi_foto.php");
}

if (isset($_POST['Edit'])) {
    $fotoid = $_POST['fotoid'];
    $judulfoto = mysqli_real_escape_string($ardi_conn, $_POST['judulfoto']);
    $deskripsifoto = mysqli_real_escape_string($ardi_conn, $_POST['deskripsifoto']);
    $albumid = $_POST['albumid'];
    $tempatfile = $_FILES['tempatfile']['name'];

    if ($tempatfile != "") {
        $targetDir = "assets/img/";
        move_uploaded_file($_FILES['tempatfile']['tmp_name'], $targetDir . $tempatfile);
        $query = "UPDATE foto SET judulfoto='$judulfoto', deskripsifoto='$deskripsifoto', albumid='$albumid', tempatfile='$tempatfile' WHERE fotoid='$fotoid'";
    } else {
        $query = "UPDATE foto SET judulfoto='$judulfoto', deskripsifoto='$deskripsifoto', albumid='$albumid' WHERE fotoid='$fotoid'";
    }

    mysqli_query($ardi_conn, $query);
    header("Location: ardi_foto.php");
}

if (isset($_GET['delete'])) {
    $fotoid = $_GET['delete'];
    $query = "SELECT * FROM foto WHERE fotoid='$fotoid'";
    $result = mysqli_query($ardi_conn, $query);
    $data = mysqli_fetch_assoc($result);

    if (file_exists("assets/img/" . $data['tempatfile'])) {
        unlink("assets/img/" . $data['tempatfile']);
        
    }

    $queryDelete = "DELETE FROM foto WHERE fotoid='$fotoid'";
    mysqli_query($ardi_conn, $queryDelete);
    header("Location: ardi_foto.php");
}
?>
