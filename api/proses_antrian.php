<?php
session_start();
include 'koneksi.php';

// Proteksi halaman
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}

if (isset($_POST['submit_antrian'])) {
    $user_id = $_SESSION['id'];
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);
    $jam = mysqli_real_escape_string($conn, $_POST['jam']); 
    $poli = mysqli_real_escape_string($conn, $_POST['poli']);

    // Generate Format Nomor Antrian Otomatis (Contoh: G-001)
    $kata_poli = explode(" ", $poli);
    $kode_huruf = isset($kata_poli[1]) ? strtoupper(substr($kata_poli[1], 0, 1)) : 'U';

    // Hitung jumlah antrian di poli yang sama pada tanggal tersebut
    $query_count = "SELECT COUNT(*) as total FROM tbl_antrian WHERE poli='$poli' AND tanggal_kunjungan='$tanggal'";
    $res_count = mysqli_query($conn, $query_count);
    $row_count = mysqli_fetch_assoc($res_count);
    $urutan = $row_count['total'] + 1;
    
    // Format angka menjadi 3 digit (001, 002, dst)
    $nomor_antrian = $kode_huruf . "-" . str_pad($urutan, 3, "0", STR_PAD_LEFT);
    
    $query_insert = "INSERT INTO tbl_antrian (user_id, nama_pasien, tanggal_kunjungan, jam_kunjungan, poli, nomor_antrian, status) 
                     VALUES ('$user_id', '$nama', '$tanggal', '$jam', '$poli', '$nomor_antrian', 'Menunggu')";
    
    if(mysqli_query($conn, $query_insert)){
        // Refresh halaman setelah sukses menyimpan
        header("Location: dashboard_user.php?sukses=1");
        exit;
    } else {
        echo "<script>alert('Gagal mengambil antrian!'); window.history.back();</script>";
    }
} else {
    header("Location: dashboard_user.php");
    exit;
}
?>