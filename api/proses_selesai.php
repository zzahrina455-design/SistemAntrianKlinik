<?php
include 'koneksi.php';

if (!isset($_COOKIE['role']) || $_COOKIE['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// cek id
if (isset($_GET['id'])) {
    $id_selesai = mysqli_real_escape_string($conn, $_GET['id']);
    
    mysqli_query($conn, "UPDATE tbl_antrian SET status='selesai' WHERE id='$id_selesai'");
}

// redirect kembali ke dashboard
header("Location: dashboard_admin.php?pesan=update_sukses");
exit;
?>