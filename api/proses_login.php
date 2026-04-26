<?php
include __DIR__ . '/session_handler.php'; // session_start() sudah ada di dalam sini

include_once __DIR__ . '/koneksi.php';

if (isset($_POST['login'])) {

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $query  = "SELECT * FROM tbl_user WHERE username='$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $data = mysqli_fetch_assoc($result);

        if (password_verify($password, $data['password'])) {

            $_SESSION['id']       = $data['id'];
            $_SESSION['username'] = $data['username'];
            $_SESSION['role']     = $data['role'];

            if ($data['role'] === 'admin') {
                header("Location: /api/dashboard_admin.php");
            } else {
                header("Location: /api/dashboard_user.php");
            }
            exit;

        } else {
            $_SESSION['flash_error'] = 'Password yang Anda masukkan salah!';
            header("Location: /api/login.php");
            exit;
        }
    } else {
        $_SESSION['flash_error'] = 'Username tidak terdaftar!';
        header("Location: /api/login.php");
        exit;
    }

} else {
    header("Location: /api/login.php");
    exit;
}