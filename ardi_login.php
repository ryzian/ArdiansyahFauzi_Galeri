<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Galeri Foto</title>
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
        .btn-primary {
            background-color: #007bff;
            border: none;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #0056b3;
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
            padding: 10px 0;
        }
        .form-control {
            border-radius: 8px;
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
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
  <div class="container">
  <a class="navbar-brand" href="ardi_publik.php">GALERI FOTO</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <a href="ardi_register.php" class="btn btn-outline-success m-1">Daftar</a>
      <a href="ardi_login.php" class="btn btn-success m-1">Masuk</a>
    </div>
  </div>
</nav>

<div class="container d-flex justify-content-center align-items-center flex-grow-1">
    <div class="col-md-4">
        <div class="card p-4">
            <div class="text-center mb-3">
                <h4 class="fw-bold">Login</h4>
            </div>
            <form action="ardi_prosses_login.php" method="POST">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button class="btn btn-success w-100" type="submit" name="kirim">Masuk</button>
            </form>
            <hr>
            <p class="text-center">Belum punya akun? <a href="ardi_register.php" class="text-decoration-none fw-bold">Daftar di sini</a></p>
        </div>
    </div>
</div>

<footer class="footer">
    <p>&copy;  - Ardi 2025 Galeri Foto</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
