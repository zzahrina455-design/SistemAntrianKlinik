<?php
session_start();
session_destroy(); // Hapus semua data login
header("Location: api/login.php"); // Balik ke halaman login
exit;
?>