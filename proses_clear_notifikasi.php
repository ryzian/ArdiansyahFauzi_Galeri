<?php
session_start();
include 'ardi_koneksi.php';

if ($_SESSION['status'] != 'login') {
    header("Location: ardi_notifikasi.php");
    exit;
}

$userid = $_SESSION['userid'];

$query = "DELETE FROM notifications WHERE userid = '$userid'";

if (mysqli_query($koneksi, $query)) {
    header("Location: ardi_notifikasi.php?status=success");
    exit;
} else {
    header("Location: ardi_notifikasi.php?status=error");
    exit;
}
?>