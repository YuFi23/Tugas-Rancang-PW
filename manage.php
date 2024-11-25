<?php
include 'database.php'; // Menghubungkan ke database
include 'functions.php'; // Memasukkan file fungsi

// Memulai session
session_start();

// Memeriksa apakah pengguna sudah login sebagai admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit(); // Menghentikan eksekusi jika tidak ada session yang sesuai
}

// Menangani aksi penambahan tiket
if (isset($_POST['create'])) {
    $namakonser = trim($_POST['namakonser']);
    $price = (int) $_POST['price'];

    // Menyaring input untuk mencegah XSS dan SQL Injection
    $namakonser = htmlspecialchars($namakonser);
    
    if ($price <= 0) {
        $errorMessage = "Harga tiket harus lebih besar dari nol!";
    } else {
        // Memanggil fungsi untuk menambah tiket
        createTicket($conn, $namakonser, $price);
        $successMessage = "Tiket berhasil ditambahkan!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encore Shield Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEJX3+0fOmVmXZtFk7p8eB6vXLR6HoHEqx0g1cUs0VWx9E3behl6fd7lktH3a" crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css"> <!-- Pastikan sudah ada file styles.css -->
</head>

<body>
    <!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <!-- Brand -->
        <a class="navbar-brand" href="manage.php">Encore Shield</a>
        
        <!-- Toggler for Mobile View -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Navbar Links -->
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="manage.php">Ticket Manage</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="user_manage.php">User Manage</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <!-- Avatar Dropdown -->
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="img/avatar.png" alt="Avatar" class="rounded-circle me-2" width="30" height="30">
                        <span class="d-none d-lg-inline text-white">Admin</span> <!-- Nama/Role -->
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>


    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h2>Encore Shield Management</h2>
            </div>
            <div class="card-body">
                <!-- Formulir untuk menambah tiket -->
                <h3>Add New Ticket</h3>

                <!-- Menampilkan pesan sukses atau error -->
                <?php
                if (isset($successMessage)) {
                    echo "<div class='alert alert-success'>$successMessage</div>";
                }
                if (isset($errorMessage)) {
                    echo "<div class='alert alert-danger'>$errorMessage</div>";
                }
                ?>

                <form action="manage.php" method="POST">
                    <div class="form-group">
                        <label for="namakonser">Nama Konser - Tanggal</label>
                        <input type="text" name="namakonser" placeholder="Nama Konser - Tanggal" required>
                    </div>
                    <div class="form-group">
                        <label for="price">Harga</label>
                        <input type="number" name="price" placeholder="Harga" required min="1">
                    </div>
                    <button type="submit" name="create" class="btn btn-primary">Add Ticket</button>
                </form>

                <!-- Menampilkan tiket yang ada -->
                <h3 class="mt-4">All Tickets</h3>

                <?php displayTickets($conn); ?>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76A8fDlm8zhB8E5gEiENmGqEwJ6cZ8eEr3XZ1wRW2D7LvKdI1Jp2S01gAfVz5KkN" crossorigin="anonymous"></script>

</body>

</html>
