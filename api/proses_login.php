<?php
include 'koneksi.php';

// Ambil data dari form
$username = $_POST['username'];
$password = $_POST['password'];

// Cek ke database
$query = "SELECT * FROM tbl_user WHERE username='$username'";
$result = mysqli_query($conn, $query);

// Cek apakah user ada
if (mysqli_num_rows($result) > 0) {
    $data = mysqli_fetch_assoc($result);

    // Verifikasi password
    if (password_verify($password, $data['password'])) {

        // ✅ AMBIL DATA DARI DATABASE
        $id = $data['id'];
        $role = $data['role'];
        $username = $data['username'];

        // ✅ SET COOKIE
        setcookie("id", $id, time() + 3600, "/");
        setcookie("user", $username, time() + 3600, "/");
        setcookie("role", $role, time() + 3600, "/");

        // ✅ REDIRECT SESUAI ROLE
        if ($role == 'admin') {
            header("Location: dashboard_admin.php");
        } else {
            header("Location: dashboard_user.php");
        }
        exit();

    } else {
        echo "Password salah!";
    }

} else {
    echo "Username tidak ditemukan!";
}