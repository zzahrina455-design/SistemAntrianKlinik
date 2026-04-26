<?php
session_start(); 
include 'koneksi.php';

if (isset($_POST['login'])) {
   
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Cari user di database berdasarkan username
    $query = "SELECT * FROM tbl_user WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    // Cek apakah usernamenya ada
    if (mysqli_num_rows($result) === 1) {
        $data = mysqli_fetch_assoc($result);

        if (password_verify($password, $data['password'])) {
            
            setcookie("user", $username, time() + 3600, "/");
            setcookie("role", $role, time() + 3600, "/");
            setcookie("id", $id, time() + 3600, "/");

        if ($role == 'admin') {
            header("Location: dashboard_admin.php");
}           else {
                header("Location: dashboard_user.php");
        }
        exit();

        } else {
            echo "<script>alert('Password salah!'); window.location='login.php';</script>";
        }
        } else {
        // Username tidak ditemukan
            echo "<script>alert('Username tidak terdaftar!'); window.location='login.php';</script>";
        }
        } else {
                header("Location: login.php");
        exit;
        }
?>