
<?php
session_start();
include 'ardi_koneksi.php';

$userid = $_SESSION['userid'];

if (isset($_GET['like_fotoid'])) {
    $fotoid = $_GET['like_fotoid'];

    $query = mysqli_query($koneksi, "SELECT f.userid AS foto_owner_id, f.judul_foto, f.tempatfile, u.username 
                                      FROM foto f 
                                      JOIN user u ON u.userid = f.userid 
                                      WHERE f.fotoid = '$fotoid'");
    $foto_data = mysqli_fetch_assoc($query);
    $foto_owner_id = $foto_data['foto_owner_id'];
    $judul_foto = $foto_data['judul_foto'];
    $foto_path = "../assets/img/" . $foto_data['tempatfile']; 
    $username = $foto_data['username'];

    if ($foto_owner_id != $userid) {
        $notif_message = "$username menyukai postingan Anda: '$judul_foto'"; 

        $insert_notif = mysqli_query($koneksi, "INSERT INTO notifikasi (userid, message, fotoid, send_id, status) 
                                        VALUES ('$foto_owner_id', '$notif_message', '$fotoid', '$userid', 'unread')");

        if (!$insert_notif) {
            echo "Error: " . mysqli_error($koneksi);
        }
    }
}

if (isset($_GET['komentar_fotoid']) && isset($_GET['isi_komentar'])) {
    $fotoid = $_GET['komentar_fotoid'];
    $isi_komentar = $_GET['isi_komentar'];
 die($isi_komentar);
 exit;

    $query = mysqli_query($koneksi, "SELECT f.userid AS foto_owner_id, f.judul_foto, f.tempatfile, u.username 
                                      FROM foto f 
                                      JOIN user u ON u.userid = f.userid 
                                      WHERE f.fotoid = '$fotoid'");
    $foto_data = mysqli_fetch_assoc($query);
    $foto_owner_id = $foto_data['foto_owner_id'];
    $judul_foto = $foto_data['judul_foto'];
    $foto_path = "../assets/img/" . $foto_data['tempatfile']; 
    $username = $foto_data['username'];

    if ($foto_owner_id != $userid) {
        $notif_message = "$username mengomentari postingan Anda: '$judul_foto' - $isi_komentar"; // Tidak membawa $userid lagi

        $insert_notif = mysqli_query($koneksi, "INSERT INTO notifikasi (userid, message, fotoid, send_id, status) 
                                        VALUES ('$foto_owner_id', '$notif_message', '$fotoid', '$userid', 'unread')");

        if (!$insert_notif) {
            echo "Error: " . mysqli_error($koneksi);
        }
    }
}
?>

