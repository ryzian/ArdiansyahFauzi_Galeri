<?php
session_start();
include 'ardi_koneksi.php';

if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['komenid']) || !isset($_GET['fotoid'])) {
    header("Location: ardi_home.php");
    exit();
}

$komenid = mysqli_real_escape_string($ardi_conn, $_GET['komenid']);
$fotoid = mysqli_real_escape_string($ardi_conn, $_GET['fotoid']);
$userid = $_SESSION['userid'];

$query = "SELECT * FROM komenfoto WHERE komenid='$komenid' AND userid='$userid'";
$result = mysqli_query($ardi_conn, $query);

if (mysqli_num_rows($result) > 0 || $_SESSION['roleid'] == 2) {
    $delete_query = "DELETE FROM komenfoto WHERE komenid='$komenid'";
    mysqli_query($ardi_conn, $delete_query);
}

header("Location: ardi_home.php");
exit();
?>
