<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Galeri Foto</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .card {
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border: none;
        }
    
        .footer {
            text-align: center;
            padding: 15px;
            color: #6c757d;
            background-color: #fff;
            box-shadow: 0 -2px 8px rgba(0, 0, 0, 0.1);
            margin-top: auto;
        }
        .navbar {
            padding: 15px 20px;
            background-color: #ffffff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: #007bff !important;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .navbar-brand:hover {
            color: #0056b3 !important;
        }
        .navbar-nav {
            display: flex;
            gap: 10px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light">
  <div class="container d-flex justify-content-between align-items-center">
    <a class="navbar-brand" href="#">GALERI FOTO</a>
    <div class="d-flex">
    <a href="ardi_register.php" class="btn btn-outline-success m-1">Daftar</a>
    <a href="ardi_login.php" class="btn btn-success m-1">Masuk</a>
    </div>
  </div>
</nav>

<div class="container d-flex justify-content-center align-items-center flex-grow-1 py-5">
    <div class="col-md-4">
        <div class="card p-4">
            <div class="text-center mb-3">
                <h4 class="fw-bold">Daftar Akun</h4>
            </div>
            <form action="ardi_prosses_register.php" method="POST">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Lengkap</label>
                    <input type="text" name="namalengkap" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Alamat</label>
                    <input type="text" name="alamat" class="form-control" required>
                </div>
                <button class="btn btn-success w-100" type="submit" name="kirim">Daftar</button>
            </form>
            <hr>
            <p class="text-center">Sudah punya akun? <a href="ardi_login.php" class="text-decoration-none fw-bold">Masuk di sini</a></p>
        </div>
    </div>
</div>

<footer class="footer">
    <p>&copy;  - Ardi 2025 Galeri Foto</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

