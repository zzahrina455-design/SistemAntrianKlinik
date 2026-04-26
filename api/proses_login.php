<?php
// PENTING: Tidak boleh ada spasi atau baris kosong sebelum tag ini!
session_start();
include 'koneksi.php';

if (isset($_POST['login'])) {

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Cari user di database berdasarkan username
    $query = "SELECT * FROM tbl_user WHERE username='$username'";
    $result = mysqli_query($conn, $query);

    // Cek apakah usernamenya ada
    if (mysqli_num_rows($result) === 1) {
        $data = mysqli_fetch_assoc($result);

        if (password_verify($password, $data['password'])) {

            // Simpan data ke SESSION
            $_SESSION['id']       = $data['id'];
            $_SESSION['username'] = $data['username'];
            $_SESSION['role']     = $data['role'];

            // REDIRECT BERDASARKAN ROLE menggunakan header() - BUKAN JavaScript
            if ($data['role'] === 'admin') {
                header("Location: dashboard_admin.php");
                exit;
            } else {
                header("Location: dashboard_user.php");
                exit;
            }

        } else {
            // Password salah - simpan pesan ke session, redirect ke login
            $_SESSION['flash_error'] = 'Password yang Anda masukkan salah!';
            header("Location: login.php");
            exit;
        }
    } else {
        // Username tidak ditemukan
        $_SESSION['flash_error'] = 'Username tidak terdaftar!';
        header("Location: login.php");
        exit;
    }

} else {
    header("Location: login.php");
    exit;
}
?>