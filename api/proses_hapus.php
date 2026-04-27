<?php
session_start();
include 'koneksi.php';

// Proteksi admin
if (!isset($_COOKIE['role']) || $_COOKIE['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Cek apakah ada parameter hapus
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    mysqli_query($conn, "DELETE FROM tbl_antrian WHERE id='$id'");
}

// Kembali ke dashboard
header("Location: dashboard_admin.php?pesan=hapus_sukses");
exit;