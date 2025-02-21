<?php


session_start();
session_unset(); 
session_destroy(); 

header("Location: index.php"); 
exit();

echo " <script >
alert( 'logout Berhasil');
location.href='index.php';
</script >";




?>