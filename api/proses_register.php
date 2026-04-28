<?php
include 'koneksi.php';

if (isset($_POST['register'])) {
    
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    
    // Paksa role jadi admin
    $role = 'admin';
    
    $password_raw = $_POST['password'];
    $password_hashed = password_hash($password_raw, PASSWORD_DEFAULT);

    $query = "INSERT INTO tbl_user (username, email, password, role) 
              VALUES ('$username', '$email', '$password_hashed', '$role')";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>
                alert('Registrasi Admin Berhasil!');
                window.location='login.php';
              </script>";
    } else {
        echo "<script>
                alert('Registrasi Gagal: " . mysqli_error($conn) . "');
                window.history.back();
              </script>";
    }
} else {
    header("Location: register.php");
    exit();
}
?>