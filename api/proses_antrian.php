<?php
include 'koneksi.php';

// cek login
if (!isset($_COOKIE['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_COOKIE['username'];

// ambil user_id
$query_user = "SELECT id FROM tbl_user WHERE username='$username'";
$result_user = mysqli_query($conn, $query_user);
$data_user = mysqli_fetch_assoc($result_user);
$user_id = $data_user['id'];

if (isset($_POST['submit_antrian'])) {

    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);
    $jam = mysqli_real_escape_string($conn, $_POST['jam']); 
    $poli = mysqli_real_escape_string($conn, $_POST['poli']);

    // generate kode
    $kata_poli = explode(" ", $poli);
    $kode_huruf = isset($kata_poli[1]) ? strtoupper(substr($kata_poli[1], 0, 1)) : 'U';

    // hitung antrian
    $query_count = "SELECT COUNT(*) as total FROM tbl_antrian WHERE poli='$poli' AND tanggal_kunjungan='$tanggal'";
    $res_count = mysqli_query($conn, $query_count);
    $row_count = mysqli_fetch_assoc($res_count);
    $urutan = $row_count['total'] + 1;

    $nomor_antrian = $kode_huruf . "-" . str_pad($urutan, 3, "0", STR_PAD_LEFT);

    // insert
    $query_insert = "INSERT INTO tbl_antrian 
    (user_id, nama_pasien, tanggal_kunjungan, jam_kunjungan, poli, nomor_antrian, status) 
    VALUES 
    ('$user_id', '$nama', '$tanggal', '$jam', '$poli', '$nomor_antrian', 'menunggu')";
    
    if(mysqli_query($conn, $query_insert)){
        header("Location: dashboard_user.php");
        exit;
    } else {
        echo "Gagal: " . mysqli_error($conn);
    }

} else {
    header("Location: dashboard_user.php");
    exit;
}
?>