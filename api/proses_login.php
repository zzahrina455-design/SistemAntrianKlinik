<?php
session_start(); 
include 'koneksi.php';

if (isset($_POST['login'])) {
   
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM tbl_user WHERE username='$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $data = mysqli_fetch_assoc($result);

        if (password_verify($password, $data['password'])) {
            
            $_SESSION['id'] = $data['id'];
            $_SESSION['username'] = $data['username'];
            $_SESSION['role'] = $data['role'];

            // REDIRECT FIX
            if ($data['role'] == 'admin') {
                header("Location: dashboard_admin.php");
            } else {
                header("Location: dashboard_user.php");
            }
            exit;

        } else {
            header("Location: login.php?error=password");
            exit;
        }
    } else {
        header("Location: login.php?error=username");
        exit;
    }
} else {
    header("Location: login.php");
    exit;
}
?>