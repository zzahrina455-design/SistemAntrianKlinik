<?php
include 'koneksi.php';

$username = $_POST['username'];
$password = $_POST['password'];

// Ambil data user
$query = "SELECT * FROM tbl_user WHERE username='$username'";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) > 0){
    $data = mysqli_fetch_assoc($result);

    // ✅ Cek password
    if(password_verify($password, $data['password'])){
        
        // ✅ TARUH DI SINI (SETELAH PASSWORD BENAR)
        setcookie("id", $data['id'], time() + 3600, "/");
        setcookie("username", $data['username'], time() + 3600, "/");
        setcookie("role", $data['role'], time() + 3600, "/");

        // ✅ BARU REDIRECT
        if($data['role'] == 'admin'){
            header("Location: dashboard_admin.php");
        } else {
            header("Location: dashboard_user.php");
        }
        exit;

    } else {
        header("Location: ../login.php?error=password");
        exit;
    }

} else {
    header("Location: ../login.php?error=username");
    exit;
}
?>