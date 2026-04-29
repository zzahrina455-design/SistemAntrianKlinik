<?php
include 'koneksi.php';

// 1. Cek apakah form benar-benar disubmit melalui metode POST dan variabelnya ada
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username']) && isset($_POST['password'])) {
    
    // 2. Gunakan mysqli_real_escape_string untuk mencegah SQL Injection (Sangat Penting!)
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM tbl_user WHERE username='$username'";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0){
        $data = mysqli_fetch_assoc($result);

        if(password_verify($password, $data['password'])){
            
            // simpan cookie
            setcookie("username", $data['username'], time() + 3600, "/");
            setcookie("role", $data['role'], time() + 3600, "/");

            // redirect sesuai role
            if($data['role'] == 'admin'){
                header("Location: dashboard_admin.php");
            } else {
                header("Location: dashboard_user.php");
            }
            exit;

        } else {
            // 3. Perbaikan Path: Gunakan 'login.php', bukan '../login.php'
            header("Location: login.php?error=password");
            exit;
        }

    } else {
        // 3. Perbaikan Path
        header("Location: login.php?error=username");
        exit;
    }
} else {
    // 4. Jika file diakses langsung tanpa mengirim POST, kembalikan ke halaman login
    header("Location: login.php");
    exit;
}
?>