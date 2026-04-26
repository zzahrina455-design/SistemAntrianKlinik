<?php
if (!isset($_COOKIE['role']) || $_COOKIE['role'] !== 'user') {
    header("Location: login.php");
    exit;
}

$user_id = $_COOKIE['id'];
$username = $_COOKIE['user'];

// --- LOGIKA MENYIMPAN ANTRIAN KE DATABASE ---
if (isset($_POST['submit_antrian'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);
    $jam = mysqli_real_escape_string($conn, $_POST['jam']); 
    $poli = mysqli_real_escape_string($conn, $_POST['poli']);

    // Generate Format Nomor Antrian Otomatis (Contoh: G-001)
    $kata_poli = explode(" ", $poli);
    $kode_huruf = isset($kata_poli[1]) ? strtoupper(substr($kata_poli[1], 0, 1)) : 'U';

    // Hitung jumlah antrian di poli yang sama pada tanggal tersebut
    $query_count = "SELECT COUNT(*) as total FROM antrian WHERE poli='$poli' AND tanggal_kunjungan='$tanggal'";
    $res_count = mysqli_query($conn, $query_count);
    $row_count = mysqli_fetch_assoc($res_count);
    $urutan = $row_count['total'] + 1;
    
    // Format angka menjadi 3 digit (001, 002, dst)
    $nomor_antrian = $kode_huruf . "-" . str_pad($urutan, 3, "0", STR_PAD_LEFT);

    // Simpan ke database 
    $query_insert = "INSERT INTO antrian (user_id, nama_pasien, tanggal_kunjungan, jam_kunjungan, poli, nomor_antrian, status) 
                     VALUES ('$user_id', '$nama', '$tanggal', '$jam', '$poli', '$nomor_antrian', 'Menunggu')";
    
    if(mysqli_query($conn, $query_insert)){
        // Refresh halaman setelah sukses menyimpan
        header("Location: dashboard_user.php?sukses=1");
        exit;
    }
}

// --- LOGIKA MENGECEK TIKET AKTIF ---
$query_cek = "SELECT * FROM antrian WHERE user_id='$user_id' AND status='Menunggu' ORDER BY id DESC LIMIT 1";
$res_cek = mysqli_query($conn, $query_cek);
$tiket_aktif = mysqli_fetch_assoc($res_cek);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Pasien</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style_user.css">
</head>
<body>
    <nav class="navbar navbar-dark shadow-sm py-3">
        <div class="container">
            <a class="navbar-brand fw-bold fs-4" href="#">
                <i class="bi bi-heart-pulse-fill text-light me-2"></i>Klinik Pasien
            </a>
            <div class="dropdown">
                <button class="btn btn-outline-light dropdown-toggle rounded-pill px-4" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle me-2"></i>Halo, <?php echo htmlspecialchars($username); ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 rounded-3 z-3">
                    <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profil Saya</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger fw-bold" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Keluar</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container fade-up">
        
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="hero-section mb-4 text-center">
                    <h1 class="fw-bold text-success mb-2">Selamat Datang, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
                    <p class="text-muted fs-5 mb-0">Sistem Antrian Terpadu. Kesehatan Anda adalah prioritas kami.</p>
                </div>
            </div>
        </div>

        <div class="row justify-content-center g-4">
            
            <div class="col-md-7">
                <div class="content-card">
                    <h4 class="fw-bold text-dark mb-4"><i class="bi bi-pencil-square text-success me-2"></i>Form Pendaftaran</h4>
                    
                    <?php if($tiket_aktif): ?>
                        <div class="alert alert-success border-0 shadow-sm rounded-4 text-center py-5">
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                            <h5 class="fw-bold mt-3">Antrian Anda Sedang Berjalan</h5>
                            <p class="text-muted mb-0">Anda tidak dapat mengambil nomor baru sebelum antrian saat ini diselesaikan oleh Petugas.</p>
                        </div>
                    <?php else: ?>
                        <form method="POST" action="">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Nama Pasien</label>
                                    <input type="text" name="nama" class="form-control bg-light" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Tanggal Kunjungan</label>
                                    <input type="date" name="tanggal" class="form-control bg-light" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Pilih Jam</label>
                                    <select name="jam" class="form-select bg-light" required>
                                        <option value="" disabled selected>-- Pilih Jam --</option>
                                        <option value="08:00 - 10:00 WIB">08:00 - 10:00 WIB</option>
                                        <option value="10:00 - 12:00 WIB">10:00 - 12:00 WIB</option>
                                        <option value="13:00 - 15:00 WIB">13:00 - 15:00 WIB</option>
                                        <option value="15:00 - 17:00 WIB">15:00 - 17:00 WIB</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Pilih Poliklinik</label>
                                    <select name="poli" class="form-select bg-light" required>
                                        <option value="" disabled selected>-- Pilih Poli --</option>
                                        <option value="Poli Umum">Poli Umum</option>
                                        <option value="Poli Gigi">Poli Gigi</option>
                                        <option value="Poli Anak">Poli Anak</option>
                                        <option value="Poli THT">Poli THT</option>
                                        <option value="Poli Penyakit Dalam">Poli Penyakit Dalam</option>
                                    </select>
                                </div>
                            </div>
                            
                            <button type="submit" name="submit_antrian" class="btn btn-success btn-lg w-100 fw-bold rounded-pill shadow-sm mt-2">
                                <i class="bi bi-ticket-detailed me-2"></i>Ambil Nomor Antrian
                            </button>
                        </form>
                    <?php endif; ?>

                </div>
            </div>

            <div class="col-md-5">
                <div class="content-card d-flex flex-column justify-content-center align-items-center text-center">
                    <h4 class="fw-bold text-dark mb-4 w-100 border-bottom pb-2"><i class="bi bi-display text-success me-2"></i>Status Antrian</h4>
                    
                    <?php if(!$tiket_aktif): ?>
                        <div id="statusKosong">
                            <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
                            <h5 class="text-muted mt-3">Belum ada antrian.</h5>
                            <p class="small text-muted mb-0">Silakan isi form untuk mendapatkan nomor.</p>
                        </div>
                    <?php else: ?>
                        <div class="ticket-box w-100 fade-up" style="display: block;">
                            <p class="mb-0 text-white-50 fw-semibold text-uppercase">Nomor Antrian</p>
                            <div class="nomor-antrian"><?php echo $tiket_aktif['nomor_antrian']; ?></div>
                            
                            <div class="bg-white text-success rounded-3 p-3 mt-3 text-start">
                                <div class="row g-2">
                                    <div class="col-12 border-bottom pb-1 mb-1">
                                        <small class="text-muted d-block" style="font-size: 0.75rem;">Pasien</small>
                                        <div class="fw-bold text-dark"><?php echo htmlspecialchars($tiket_aktif['nama_pasien']); ?></div>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block" style="font-size: 0.75rem;">Poliklinik</small>
                                        <div class="fw-bold text-dark small"><?php echo htmlspecialchars($tiket_aktif['poli']); ?></div>
                                    </div>
                                    <div class="col-6 mt-2 pt-2 border-top">
                                        <small class="text-muted d-block" style="font-size: 0.75rem;">Tanggal</small>
                                        <div class="fw-bold text-dark small"><?php echo date('d-m-Y', strtotime($tiket_aktif['tanggal_kunjungan'])); ?></div>
                                    </div>
                                    <div class="col-6 mt-2 pt-2 border-top">
                                        <small class="text-muted d-block" style="font-size: 0.75rem;">Jam</small>
                                        <div class="fw-bold text-dark small"><?php echo htmlspecialchars($tiket_aktif['jam_kunjungan']); ?></div>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 small opacity-75"><i class="bi bi-info-circle me-1"></i>Tunjukkan tiket ini kepada petugas.</p>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>

        <div class="row justify-content-center mt-4">
            <div class="col-md-12">
                <div class="content-card">
                    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                        <h4 class="fw-bold text-dark mb-0"><i class="bi bi-bar-chart-fill text-info me-2"></i>Statistik Kesehatan Nasional</h4>
                        
                        <div class="d-flex align-items-center gap-2">
                            <button id="btn-refresh-bps" onclick="loadBpsData()" class="btn btn-sm btn-outline-info rounded-pill fw-bold shadow-sm">
                                <i class="bi bi-arrow-clockwise"></i> Refresh
                            </button>
                        </div>
                    </div>
                    
                    <div class="text-center" id="bps-container">
                        </div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function loadBpsData() {
            const container = document.getElementById('bps-container');
            const btnRefresh = document.getElementById('btn-refresh-bps');

            if (btnRefresh) btnRefresh.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Memuat...';
            container.innerHTML = `
                <div class="py-4">
                    <div class="spinner-border text-info" role="status"></div>
                    <p class="mt-2 text-muted fw-bold">Menarik data dari server BPS RI...</p>
                </div>
            `;

            fetch('api.php')
                .then(response => response.json())
                .then(data => {
                    if (btnRefresh) btnRefresh.innerHTML = '<i class="bi bi-arrow-clockwise"></i> Refresh'; 

                    // MENGGUNAKAN TANDA HUBUNG (Sesuai output Postman)
                    if (data['data-availability'] === 'available') {
                        const dataPoints = data.datacontent;
                        
                        // Mengambil nilai pertama dari object datacontent
                        const keyTerakhir = Object.keys(dataPoints)[0];
                        const angkaHarapanHidup = dataPoints[keyTerakhir];

                        container.innerHTML = `
                            <div class="py-3 fade-up">
                                <h5 class="text-muted fw-normal mb-2">Angka Harapan Hidup (AHH) Nasional</h5>
                                <h1 class="display-3 fw-bold text-info mb-3">${angkaHarapanHidup} Tahun</h1>
                                <p class="small text-muted w-75 mx-auto">
                                    <i class="bi bi-info-circle me-1"></i> Berdasarkan rilis data resmi dari Badan Pusat Statistik (BPS), indikator ini menunjukkan rata-rata perkiraan lama hidup penduduk Indonesia. Klinik kami berkomitmen untuk terus mendukung peningkatan kesehatan Anda.
                                </p>
                            </div>
                        `;
                    } else {
                        container.innerHTML = '<div class="text-muted py-4 fw-bold">Data BPS saat ini sedang tidak tersedia.</div>';
                    }
                })
                .catch(error => {
                    container.innerHTML = '<div class="text-danger py-4"><i class="bi bi-exclamation-triangle fs-3 d-block mb-2"></i> Gagal menghubungkan ke sistem BPS.</div>';
                    if (btnRefresh) btnRefresh.innerHTML = '<i class="bi bi-arrow-clockwise"></i> Refresh';
                    console.error("Terjadi error fetch BPS: ", error);
                });
        }

        document.addEventListener("DOMContentLoaded", function() {
            loadBpsData();
        });
    </script>
</body>
</html>