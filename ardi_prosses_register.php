<?php
include 'ardi_koneksi.php';

$username = mysqli_real_escape_string($ardi_conn, $_POST['username']);
$password = md5($_POST['password']);
$email = mysqli_real_escape_string($ardi_conn, $_POST['email']);
$namalengkap = mysqli_real_escape_string($ardi_conn, $_POST['namalengkap']);
$alamat = mysqli_real_escape_string($ardi_conn, $_POST['alamat']);
$roleid = 2;

$sql = mysqli_query($ardi_conn, "INSERT INTO user (username, password, email, namalengkap, alamat, roleid) VALUES ('$username', '$password', '$email', '$namalengkap', '$alamat', '$roleid')");

if ($sql) {
    echo "<script>
    alert('Pendaftaran Berhasil!');
    location.href='ardi_login.php';
    </script>";
} else {
    echo "<script>
    alert('Pendaftaran Gagal!');
    location.href='ardi_registrasi.php';
    </script>";
}
