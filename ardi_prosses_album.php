<?php
session_start();
include 'ardi_koneksi.php';

if(isset($_POST['tambah'])){
    $ardi_album = $_POST['namaalbum'];
    $ardi_des = $_POST['deskripsi'];
    $ardi_Tanggal = date('y-m-d');
    $ardi_user = $_SESSION['userid'];

    $sql = mysqli_query($ardi_conn, "INSERT INTO album VALUES ('','$ardi_album','$ardi_des','$ardi_Tanggal','$ardi_user')");
    
   echo "<script>
alert('Data Berhasil Di Simpan');
location.href='ardi_album.php';
   </script>";
}

include 'ardi_koneksi.php';

if(isset($_POST['Edit'])){
    $ardi_albumid = $_POST['albumid'];
    $ardi_album = $_POST['namaalbum'];
    $ardi_des = $_POST['deskripsi'];
    $ardi_Tanggal = date('y-m-d');
    $ardi_user = $_SESSION['userid'];

    $sql = mysqli_query($ardi_conn, "UPDATE album SET namaalbum='$ardi_album',deskripsi='$ardi_des',tanggalbuat='$ardi_Tanggal' WHERE albumid='$ardi_albumid'");
    
   echo "<script>
alert('Data Berhasil Di Perbarui');
location.href='ardi_album.php';
   </script>";
}

if(isset($_POST['Hapus'])){
    $ardi_albumid = $_POST['albumid'];

    $sql = mysqli_query($ardi_conn,"DELETE FROM album WHERE albumid='$ardi_albumid'");

    echo "<script>
    alert('Data Berhasil Di Hapus');
    location.href='ardi_album.php';
       </script>";

}

?>