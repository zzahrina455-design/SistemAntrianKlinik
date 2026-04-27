<?php
session_start();
include 'koneksi.php';

// Proteksi halaman: Jika bukan admin, kembali ke login
if (!isset($_COOKIE['role']) || $_COOKIE['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}


// MENGAMBIL DATA STATISTIK HARI INI
$hari_ini = date('Y-m-d');
$q_total = mysqli_query($conn, "SELECT COUNT(*) as jml FROM tbl_antrian WHERE tanggal_kunjungan='$hari_ini'");
$tot_antrian = mysqli_fetch_assoc($q_total)['jml'];

$q_tunggu = mysqli_query($conn, "SELECT COUNT(*) as jml FROM tbl_antrian WHERE tanggal_kunjungan='$hari_ini' AND status='menunggu'");
$tot_tunggu = mysqli_fetch_assoc($q_tunggu)['jml'];

$q_selesai = mysqli_query($conn, "SELECT COUNT(*) as jml FROM tbl_antrian WHERE tanggal_kunjungan='$hari_ini' AND status='selesai'");
$tot_selesai = mysqli_fetch_assoc($q_selesai)['jml'];

// MENGAMBIL SEMUA DATA ANTRIAN
$query_data = "SELECT * FROM tbl_antrian ORDER BY status ASC, tanggal_kunjungan DESC, id DESC";
$result_data = mysqli_query($conn, $query_data);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Klinik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        :root { 
            --primary-green: #198754; 
            --light-green: #e8f5e9; 
        }

        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: #f4f7f6; 
            overflow-x: hidden; 
        }

        .fade-in { 
            animation: fadeIn 0.8s ease-in; 
        }

        @keyframes fadeIn { 
            from { opacity: 0; transform: translateY(20px); } 
            to { opacity: 1; transform: translateY(0); } 
        }

        .sidebar { 
            min-height: 100vh; 
            background: white; 
            border-right: 1px solid #ddd; 
            padding-top: 20px; 
            position: fixed; 
            width: 16.666667%; 
            z-index: 1000;
        }

        .nav-link { 
            color: #555; 
            border-radius: 8px; 
            margin: 5px 15px; 
            transition: all 0.3s ease; 
            font-weight: 500; 
        }

        .nav-link:hover, 
        .nav-link.active { 
            background: var(--primary-green); 
            color: white; 
            transform: translateX(5px); 
        }

        .main-content { 
            margin-left: 16.666667%; 
        }

        .stat-card { 
            border: none; 
            border-left: 5px solid var(--primary-green); 
            border-radius: 12px; 
            transition: 0.3s; 
            background: white; 
        }

        .stat-card:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 10px 20px rgba(0,0,0,0.05); 
        }

        .table-custom { 
            background: white; 
            border-radius: 12px; 
            overflow: hidden; 
            box-shadow: 0 5px 15px rgba(0,0,0,0.03); 
        }

        .table-custom th { 
            background-color: #f8f9fa; 
            color: #333; 
            font-weight: 600; 
            border-bottom: 2px solid #e9ecef; 
        }

        .table-custom td { 
            vertical-align: middle; 
        }
</style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            
            <nav class="col-md-2 sidebar shadow-sm">
                <div class="text-center mb-4">
                    <h4 class="fw-bold text-success"><i class="bi bi-hospital-fill me-2"></i>Admin Panel</h4>
                </div>
                <div class="nav flex-column">
                    <a class="nav-link active" href="#"><i class="bi bi-speedometer2 me-2"></i> Dashboard Utama</a>
                    <hr class="mx-3 my-3">
                    <a class="nav-link text-danger fw-bold" href="logout.php"><i class="bi bi-box-arrow-left me-2"></i> Keluar</a>
                </div>
            </nav>

            <main class="col-md-10 pt-4 px-4 fade-in main-content">
                
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-3 mb-4 border-bottom">
                    <div>
                        <h2 class="fw-bold text-dark mb-0">Dashboard Admin</h2>
                        <span class="text-muted small">Pusat kendali antrian dan data klinik</span>
                    </div>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="bg-white shadow-sm px-4 py-2 rounded-pill border">
                            <i class="bi bi-person-circle text-success me-2"></i> 
                            <span class="fw-bold"><?php echo htmlspecialchars($_COOKIE['username']); ?></span> (Admin)
                        </div>
                    </div>
                </div>

                <?php if(isset($_GET['pesan'])): ?>
                    <?php if($_GET['pesan'] == 'hapus_sukses'): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">Data antrian berhasil dihapus! <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                    <?php elseif($_GET['pesan'] == 'update_sukses'): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">Status pasien berhasil diperbarui menjadi selesai! <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                    <?php endif; ?>
                <?php endif; ?>

                <div class="d-flex justify-content-between align-items-center mb-3 mt-5">
                    <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-card-list text-success me-2"></i>Kelola Antrian Pasien</h5>
                </div>

                <div class="table-custom mb-5 p-3">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Nomor</th>
                                    <th>Tgl & Jam</th>
                                    <th>Nama Pasien</th>
                                    <th>Poli</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi Admin</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (mysqli_num_rows($result_data) > 0): ?>
                                    <?php while($row = mysqli_fetch_assoc($result_data)): ?>
                                        <tr>
                                            <td><strong class="fs-5 text-success"><?php echo $row['nomor_antrian']; ?></strong></td>
                                            <td>
                                                <div class="fw-semibold text-dark"><?php echo date('d M Y', strtotime($row['tanggal_kunjungan'])); ?></div>
                                                <small class="text-muted"><i class="bi bi-clock me-1"></i><?php echo htmlspecialchars($row['jam_kunjungan'] ?? '-'); ?></small>
                                            </td>
                                            <td class="fw-bold text-dark"><?php echo htmlspecialchars($row['nama_pasien']); ?></td>
                                            <td>
                                                <div class="fw-semibold text-dark"><?php echo htmlspecialchars($row['poli']); ?></div>
                                                <small class="text-muted"><i class="bi bi-person-fill me-1"></i><?php echo htmlspecialchars($row['nama_dokter'] ?? '-'); ?></small>
                                            </td>
                                            <td>
                                                <?php if($row['status'] == 'menunggu'): ?>
                                                    <span class="badge bg-warning text-dark px-2 py-1"><i class="bi bi-hourglass-split me-1"></i>Menunggu</span>
                                                <?php else: ?>
                                                    <span class="badge bg-success px-2 py-1"><i class="bi bi-check-circle me-1"></i>Selesai</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                
                                                <?php if($row['status'] == 'menunggu'): ?>
                                                    <a href="proses_selesai.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm mb-1" onclick="return confirm('Konfirmasi: Tandai pasien ini selesai diperiksa?');" title="Selesaikan Antrian">
                                                        <i class="bi bi-check2-all me-1"></i>Selesai
                                                    </a>
                                                <?php endif; ?>
                                                
                                                <a href="proses_hapus.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3 shadow-sm mb-1" onclick="return confirm('Peringatan: Yakin ingin menghapus antrian ini secara permanen?');" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada data antrian pasien terdaftar.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>