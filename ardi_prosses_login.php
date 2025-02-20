<?php
session_start();
include 'ardi_koneksi.php';

$username = mysqli_real_escape_string($ardi_conn, $_POST['username']);
$password = md5($_POST['password']);

$sql = mysqli_query($ardi_conn, "SELECT * FROM user WHERE username='$username' AND password='$password'");
$ardi_cek = mysqli_num_rows($sql);

if ($ardi_cek > 0) {
    $ardi_Data = mysqli_fetch_array($sql);
    $_SESSION['username'] = $ardi_Data['username'];
    $_SESSION['userid'] = $ardi_Data['userid'];
    $_SESSION['roleid'] = $ardi_Data['roleid'];
    $_SESSION['status'] = 'login';

    if ($ardi_Data['roleid'] == 1) {
        header("Location: ardi_admin.php");
    } else {
        header("Location: ardi_home.php");
    }
} else {
    echo "<script>
    alert('Username atau Password salah!');
    location.href='ardi_login.php';
    </script>";
}
?>

