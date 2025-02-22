<?php
session_start();
include 'ardi_koneksi.php';

if (!isset($_POST['fotoid']) || !isset($_POST['isikomen'])) {
    die("Error: Data tidak dikirim dengan benar.");
}

$ardi_fotoid = trim($_POST['fotoid']);
$ardi_isikomen = trim($_POST['isikomen']);

if (mysqli_query($koneksi, $query)) {
    $result = mysqli_query($koneksi, "SELECT userid FROM foto WHERE fotoid='$fotoid'");
    $row = mysqli_fetch_assoc($result);
    $fotoOwnerId = $row['userid'];

    if ($fotoOwnerId != $userid) {
        $content = "mengomentari foto Anda : $isikomentar";
        mysqli_query($koneksi, "INSERT INTO notifications (userid, action_userid, content, created_at, fotoid) 
                                 VALUES ('$fotoOwnerId', '$userid', '$content', NOW(), '$fotoid')");
    }

    header("Location: home.php?fotoid=$fotoid");
} else {
    echo "Error: " . mysqli_error($koneksi);
}
if (!isset($_SESSION['userid']) || empty($_SESSION['userid'])) {
    die("Error: User belum login!");
}

$ardi_userid = $_SESSION['userid'];
$ardi_tkomen = date('Y-m-d');

if (empty($ardi_fotoid) || empty($ardi_isikomen)) {
    die("Error: Foto ID atau komentar tidak boleh kosong!");
}

$cek_foto = mysqli_query($ardi_conn, "SELECT fotoid FROM foto WHERE fotoid = '$ardi_fotoid'");
if (mysqli_num_rows($cek_foto) == 0) {
    die("Error: Foto ID tidak ditemukan di database.");
}

$query = mysqli_query($ardi_conn, "INSERT INTO komenfoto (fotoid, userid, isikomen, tanggalkomen) 
VALUES ('$ardi_fotoid', '$ardi_userid', '$ardi_isikomen', '$ardi_tkomen')");

if ($query) {
    header("Location: ardi_home.php");
    exit();
} else {
    die("Error: " . mysqli_error($ardi_conn));
}


?>
