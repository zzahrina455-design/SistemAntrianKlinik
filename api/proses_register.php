<?php
include 'koneksi.php';

if (isset($_POST['register'])) {
    
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    
    // Ambil domain email
    $domain = substr(strrchr($email, "@"), 1);

    // Tentukan role berdasarkan domain
    if ($domain == "gmail.com") {
        $role = "user";
    } elseif ($domain == "admin.com") {
        $role = "admin";
    } else {
        echo "<script>
                alert('Email harus menggunakan @gmail.com atau @admin.com!');
                window.history.back();
              </script>";
        exit();
    }

    $password_raw = $_POST['password'];
    $password_hashed = password_hash($password_raw, PASSWORD_DEFAULT);

    $query = "INSERT INTO tbl_user (username, email, password, role) 
              VALUES ('$username', '$email', '$password_hashed', '$role')";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>
                alert('Registrasi Berhasil sebagai $role!');
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