<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Klinik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            /* Background transparan gelap di atas gambar rumah sakit */
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
            background: rgba(255, 255, 255, 0.9); 
        }
        .btn-success { 
            border-radius: 10px; 
            padding: 10px; 
            font-weight: 600; 
            transition: 0.3s;
        }
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(25, 135, 84, 0.4);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow-lg p-4">
                    <h3 class="text-center mb-4 fw-bold text-success">Buat Akun</h3>
                    
                    <form action="proses_register.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="nama@mail.com" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" placeholder="username" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" name="password" id="password" class="form-control" placeholder="******" required>
                                <span class="input-group-text" onclick="togglePass()" style="cursor:pointer">
                                    <i id="icon" class="bi bi-eye"></i>
                                </span>
                            </div>
                        </div>

                        <input type="hidden" name="role" value="admin">

                        <button type="submit" name="register" class="btn btn-success w-100 shadow-sm">
                            Daftar Sekarang
                        </button>

                    </form>
                    
                    <div class="text-center mt-3">
                        <small>Sudah punya akun? <a href="login.php" class="text-success text-decoration-none fw-bold">Login</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fungsi untuk melihat/menyembunyikan password
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