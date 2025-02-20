<?php
$host = 'localhost';
$username = 'root'; 
$password = '';      
$dbname = 'ardidb_galeri';

$ardi_conn = mysqli_connect($host, $username, $password, $dbname);

if ($ardi_conn) {
    echo "";
} else {
    die("Gagal Terhubung: " . mysqli_connect_error());
}
?>
