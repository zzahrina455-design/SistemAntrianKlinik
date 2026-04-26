<?php
// File ini menggantikan session PHP biasa dengan session yang disimpan di TiDB
// Wajib di-include SEBELUM session_start() di setiap halaman

include_once __DIR__ . '/koneksi.php';

// Buat tabel sessions jika belum ada
mysqli_query($conn, "
    CREATE TABLE IF NOT EXISTS sessions (
        session_id VARCHAR(128) NOT NULL PRIMARY KEY,
        session_data TEXT NOT NULL,
        last_activity INT NOT NULL
    )
");

// Handler: baca session dari TiDB
function session_read($id) {
    global $conn;
    $id = mysqli_real_escape_string($conn, $id);
    $result = mysqli_query($conn, "SELECT session_data FROM sessions WHERE session_id='$id' AND last_activity > " . (time() - 3600));
    if ($row = mysqli_fetch_assoc($result)) {
        return $row['session_data'];
    }
    return '';
}

// Handler: tulis session ke TiDB
function session_write($id, $data) {
    global $conn;
    $id   = mysqli_real_escape_string($conn, $id);
    $data = mysqli_real_escape_string($conn, $data);
    $time = time();
    mysqli_query($conn, "
        REPLACE INTO sessions (session_id, session_data, last_activity)
        VALUES ('$id', '$data', $time)
    ");
    return true;
}

// Handler: hapus session (logout)
function session_destroy_handler($id) {
    global $conn;
    $id = mysqli_real_escape_string($conn, $id);
    mysqli_query($conn, "DELETE FROM sessions WHERE session_id='$id'");
    return true;
}

// Handler: bersihkan session kadaluarsa
function session_gc($maxlifetime) {
    global $conn;
    mysqli_query($conn, "DELETE FROM sessions WHERE last_activity < " . (time() - $maxlifetime));
    return true;
}

// Daftarkan semua handler ke PHP
session_set_save_handler(
    function($path, $name) { return true; }, // open
    function() { return true; },             // close
    'session_read',
    'session_write',
    'session_destroy_handler',
    'session_gc'
);

// Mulai session
session_start();