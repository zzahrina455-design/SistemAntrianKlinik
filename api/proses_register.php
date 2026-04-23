<?php
include 'koneksi.php';

if (isset($_POST['register'])) {
    
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $role     = mysqli_real_escape_string($conn, $_POST['role']);
    
    $password_raw = $_POST['password'];
    $password_hashed = password_hash($password_raw, PASSWORD_DEFAULT);

    // Query SQL untuk memasukkan data ke tabel 'users'
    $query = "INSERT INTO user (username, email, password, role) 
              VALUES ('$username', '$email', '$password_hashed', '$role')";
    
    if (mysqli_query($conn, $query)) {
        // Jika berhasil, munculkan alert dan pindah ke halaman login
        echo "<script>
                alert('Registrasi Berhasil! Silahkan Login sebagai " . $role . ".');
                window.location='api/login.php';
              </script>";
    } else {

        echo "<script>
                alert('Registrasi Gagal: " . mysqli_error($conn) . "');
                window.history.back();
              </script>";
    }
} else {
    // Jika mencoba akses file ini tanpa melalui form register
    header("Location: api/register.php");
    exit();
}
?>