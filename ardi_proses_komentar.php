<?php
session_start();
include 'ardi_koneksi.php'; 

if (isset($_POST['kirimkomentar'])) {
    if (!isset($_SESSION['userid'])) {
        echo "Error: Anda harus login terlebih dahulu!";
        exit();
    }

    $fotoid = $_POST['fotoid'];
    $userid = $_SESSION['userid']; 
    $isikomen = mysqli_real_escape_string($ardi_conn, $_POST['isikomen']);

    $sql = "INSERT INTO komenfoto (fotoid, userid, isikomen, tanggalkomen) 
            VALUES ('$fotoid', '$userid', '$isikomen', NOW())";
    
    if (mysqli_query($ardi_conn, $sql)) {
        header("Location: ardi_home.php?fotoid=$fotoid"); 
        exit();
    } else {
        echo "Error: " . mysqli_error($ardi_conn);
    }
}
?>
