<?php


session_start();
session_unset(); 
session_destroy(); 

header("Location: ardi_publik.php"); 
exit();

echo " <script >
alert( 'logout Berhasil');
location.href='ardi_publik.php';
</script >";




?>