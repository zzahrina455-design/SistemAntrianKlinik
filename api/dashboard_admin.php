<?php
include __DIR__ . '/session_handler.php';
include_once __DIR__ . '/koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: /api/login.php");
    exit;
}

$hari_ini = date('Y-m-d');

$q_total     = mysqli_query($conn, "SELECT COUNT(*) as jml FROM antrian WHERE tanggal_kunjungan='$hari_ini'");
$tot_antrian = mysqli_fetch_assoc($q_total)['jml'];

$q_tunggu   = mysqli_query($conn, "SELECT COUNT(*) as jml FROM antrian WHERE tanggal_kunjungan='$hari_ini' AND status='Menunggu'");
$tot_tunggu = mysqli_fetch_assoc($q_tunggu)['jml'];

$q_selesai   = mysqli_query($conn, "SELECT COUNT(*) as jml FROM antrian WHERE tanggal_kunjungan='$hari_ini' AND status='Selesai'");
$tot_selesai = mysqli_fetch_assoc($q_selesai)['jml'];

$query_data  = "SELECT * FROM antrian ORDER BY status ASC, tanggal_kunjungan DESC, id DESC";
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
    <link rel="stylesheet" href="/style_admin.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">

            <nav class="col-md-2 d-none d-md-block sidebar shadow-sm">
                <div class="text-center mb-4">
                    <h4 class="fw-bold text-success"><i class="bi bi-hospital-fill me-2"></i>Admin Panel</h4>
                </div>
                <div class="nav flex-column">
                    <a class="nav-link active" href="#"><i class="bi bi-speedometer2 me-2"></i> Dashboard Utama</a>
                    <hr class="mx-3 my-3">
                    <a class="nav-link text-danger fw-bold" href="/api/logout.php"><i class="bi bi-box-arrow-left me-2"></i> Keluar</a>
                </div>
            </nav>

            <main class="col-md-10 pt-4 px-4 fade-in main-content">

                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-3 mb-4 border-bottom">
                    <div>
                        <h2 class="fw-bold text-dark mb-0">Dashboard Admin</h2>
                        <span class="text-muted small">Pusat kendali antrian dan data klinik</span>
                    </div>
                    <div class="bg-white shadow-sm px-4 py-2 rounded-pill border">
                        <i class="bi bi-person-circle text-success me-2"></i>
                        <span class="fw-bold"><?php echo htmlspecialchars($_SESSION['username']); ?></span> (Admin)
                    </div>
                </div>

                <!-- Statistik -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm rounded-4 p-3 text-center">
                            <div class="text-muted small mb-1">Total Antrian Hari Ini</div>
                            <div class="fw-bold fs-2 text-primary"><?php echo $tot_antrian; ?></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm rounded-4 p-3 text-center">
                            <div class="text-muted small mb-1">Sedang Menunggu</div>
                            <div class="fw-bold fs-2 text-warning"><?php echo $tot_tunggu; ?></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm rounded-4 p-3 text-center">
                            <div class="text-muted small mb-1">Selesai Diperiksa</div>
                            <div class="fw-bold fs-2 text-success"><?php echo $tot_selesai; ?></div>
                        </div>
                    </div>
                </div>

                <?php if (isset($_GET['pesan'])): ?>
                    <?php if ($_GET['pesan'] == 'hapus_sukses'): ?>
                        <div class="alert alert-danger alert-dismissible fade show">Data antrian berhasil dihapus! <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                    <?php elseif ($_GET['pesan'] == 'update_sukses'): ?>
                        <div class="alert alert-success alert-dismissible fade show">Status pasien berhasil diperbarui! <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                    <?php endif; ?>
                <?php endif; ?>

                <div class="d-flex justify-content-between align-items-center mb-3">
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
                                    <?php while ($row = mysqli_fetch_assoc($result_data)): ?>
                                        <tr>
                                            <td><strong class="fs-5 text-success"><?php echo $row['nomor_antrian']; ?></strong></td>
                                            <td>
                                                <div class="fw-semibold"><?php echo date('d M Y', strtotime($row['tanggal_kunjungan'])); ?></div>
                                                <small class="text-muted"><i class="bi bi-clock me-1"></i><?php echo htmlspecialchars($row['jam_kunjungan'] ?? '-'); ?></small>
                                            </td>
                                            <td class="fw-bold"><?php echo htmlspecialchars($row['nama_pasien']); ?></td>
                                            <td><?php echo htmlspecialchars($row['poli']); ?></td>
                                            <td>
                                                <?php if ($row['status'] == 'Menunggu'): ?>
                                                    <span class="badge bg-warning text-dark px-2 py-1"><i class="bi bi-hourglass-split me-1"></i>Menunggu</span>
                                                <?php else: ?>
                                                    <span class="badge bg-success px-2 py-1"><i class="bi bi-check-circle me-1"></i>Selesai</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <?php if ($row['status'] == 'Menunggu'): ?>
                                                    <a href="/api/proses_selesai.php?id=<?php echo $row['id']; ?>"
                                                       class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm mb-1"
                                                       onclick="return confirm('Tandai pasien ini selesai?');">
                                                        <i class="bi bi-check2-all me-1"></i>Selesai
                                                    </a>
                                                <?php endif; ?>
                                                <a href="/api/proses_hapus.php?id=<?php echo $row['id']; ?>"
                                                   class="btn btn-sm btn-outline-danger rounded-pill px-3 shadow-sm mb-1"
                                                   onclick="return confirm('Yakin ingin menghapus?');">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada data antrian.</td></tr>
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