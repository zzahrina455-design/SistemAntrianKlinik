<?php
session_start();
include 'koneksi.php';

// Proteksi keamanan: pastikan yang akses hanya admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Cek apakah ada ID yang dikirim dari tombol
if (isset($_GET['id'])) {
    $id_selesai = mysqli_real_escape_string($koneksi, $_GET['id']);
    
    // Jalankan query update status menjadi 'Selesai'
    mysqli_query($koneksi, "UPDATE antrian SET status='Selesai' WHERE id='$id_selesai'");
}

// Kembalikan ke halaman dashboard dengan membawa pesan sukses
header("Location: dashboard_admin.php?pesan=update_sukses");
exit;
?>