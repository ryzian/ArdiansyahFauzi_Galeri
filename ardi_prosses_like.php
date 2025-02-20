<?php
session_start();
include 'ardi_koneksi.php';

if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

$ardi_userid = $_SESSION['userid'];

if (!isset($_GET['fotoid'])) {
    header("Location: ardi_home.php");
    exit();
}

$fotoid = mysqli_real_escape_string($ardi_conn, $_GET['fotoid']);

$ardi_ceksuka = mysqli_query($ardi_conn, "SELECT * FROM likefoto WHERE fotoid='$fotoid' AND userid='$ardi_userid'");
if (mysqli_num_rows($ardi_ceksuka) > 0) {
    mysqli_query($ardi_conn, "DELETE FROM likefoto WHERE fotoid='$fotoid' AND userid='$ardi_userid'");
} else {
    $query = "INSERT INTO likefoto (fotoid, userid, tanggallike) VALUES ('$fotoid', '$ardi_userid', NOW())";
    mysqli_query($ardi_conn, $query);
}

header("Location: ardi_home.php");
exit();
?>
