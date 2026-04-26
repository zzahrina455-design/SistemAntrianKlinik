<?php
include __DIR__ . '/session_handler.php';

// Jika sudah login, redirect langsung
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: /api/dashboard_admin.php");
    } else {
        header("Location: /api/dashboard_user.php");
    }
    exit;
}

$flash_error = '';
if (isset($_SESSION['flash_error'])) {
    $flash_error = $_SESSION['flash_error'];
    unset($_SESSION['flash_error']);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Klinik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)),
                        url("https://rsudibnusina.gresikkab.go.id/web/uploads/igdprofile/igdprofile.jpeg");
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            align-items: center;
        }
        .card {
            border: none;
            border-radius: 15px;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }
        .btn-success {
            border-radius: 10px;
            padding: 10px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow-lg p-4">
                    <h3 class="text-center mb-4 fw-bold text-success">Login Klinik</h3>

                    <?php if ($flash_error): ?>
                        <div class="alert alert-danger alert-dismissible fade show py-2 small" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i>
                            <?php echo htmlspecialchars($flash_error); ?>
                            <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form action="/api/proses_login.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" name="username" class="form-control" placeholder="Masukkan username" required autofocus>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password" id="password" class="form-control" placeholder="******" required>
                                <span class="input-group-text" onclick="togglePass()" style="cursor:pointer">
                                    <i id="icon" class="bi bi-eye"></i>
                                </span>
                            </div>
                        </div>

                        <button type="submit" name="login" class="btn btn-success w-100 shadow-sm mb-3">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Masuk Sekarang
                        </button>
                    </form>

                    <div class="text-center">
                        <small>Belum punya akun? <a href="/api/register.php" class="text-success text-decoration-none fw-bold">Daftar di sini</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePass() {
            const pass = document.getElementById("password");
            const icon = document.getElementById("icon");
            if (pass.type === "password") {
                pass.type = "text";
                icon.className = "bi bi-eye-slash";
            } else {
                pass.type = "password";
                icon.className = "bi bi-eye";
            }
        }
    </script>
</body>
</html>