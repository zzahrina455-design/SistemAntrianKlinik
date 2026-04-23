<?php
session_start(); 
include 'koneksi.php';

if (isset($_POST['login'])) {
   
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Cari user di database berdasarkan username
    $query = "SELECT * FROM user WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    // Cek apakah usernamenya ada
    if (mysqli_num_rows($result) === 1) {
        $data = mysqli_fetch_assoc($result);

        if (password_verify($password, $data['password'])) {
            
            $_SESSION['id'] = $data['id'];
            $_SESSION['username'] = $data['username'];
            $_SESSION['role'] = $data['role'];

            // REDIRECT BERDASARKAN ROLE
            if ($data['role'] == 'admin') {
                echo "<script>alert('Login Berhasil sebagai Admin!'); window.location='api/dashboard_admin.php';</script>";
            } else {
                echo "<script>alert('Login Berhasil sebagai User!'); window.location='api/dashboard_user.php';</script>";
            }
            exit;

        } else {
            echo "<script>alert('Password salah!'); window.location='api/login.php';</script>";
        }
    } else {
        // Username tidak ditemukan
        echo "<script>alert('Username tidak terdaftar!'); window.location='api/login.php';</script>";
    }
} else {
    header("Location: api/login.php");
    exit;
}
?>