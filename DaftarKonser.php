<?php 
include('database.php'); 
include('functions.php'); 

if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Konser</title>
    <link rel="stylesheet" href="DaftarKonser.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h2>ADMIN</h2>
            <ul>
                <li><a href="DataPelanggan.php">Data Akun</a></li>
                <li><a href="DaftarPesanan.php">Daftar Pesanan</a></li>
                <li><a href="daftarKonser.php" class="active">Daftar Konser</a></li>
            </ul>
            <a href="DataPelanggan.php?logout=true" class="logout-btn">Logout</a>
        </div>
        <div class="main-content">
            <h1>Daftar Konser</h1>

            <!-- Form untuk Menambah Konser -->
            <form method="POST" action="process.php" enctype="multipart/form-data" class="form-crud">
                <input type="text" name="nama_artis" placeholder="Nama Artis" required>
                <input type="text" name="tempat" placeholder="Tempat" required>
                <input type="date" name="tanggal" required>
                <input type="number" step="0.01" name="harga" placeholder="Harga Tiket" required>
                <input type="file" name="gambar" accept="image/*" required>
                <button type="submit" name="add">Tambah Konser</button>
            </form>

            <!-- Pesan jika data berhasil ditambahkan -->
            <?php
            if (isset($_GET['added']) && $_GET['added'] == 'success') {
                echo "<p>Data berhasil ditambahkan!</p>";
            }
            ?>

            <!-- Tabel Daftar Konser -->
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Artis</th>
                        <th>Tempat</th>
                        <th>Tanggal</th>
                        <th>Harga</th>
                        <th>Gambar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $conn->query("SELECT * FROM konser");
                    while ($row = $result->fetch_assoc()) {
                        echo "
                        <tr>
                            <td>{$row['id']}</td>
                            <td>{$row['nama_artis']}</td>
                            <td>{$row['tempat']}</td>
                            <td>{$row['tanggal']}</td>
                            <td>{$row['harga']}</td>
                            <td><img src='img/{$row['gambar']}' width='100'></td>
                            <td>
                                <a href='daftarKonser.php?edit={$row['id']}'>Edit</a> |
                                <a href='process.php?delete={$row['id']}'>Hapus</a>
                            </td>
                        </tr>
                        ";
                    }
                    ?>
                </tbody>
            </table>

            <!-- Form Edit Konser -->
            <?php if (isset($_GET['edit'])): 
                $id = $_GET['edit'];
                $edit_data = getConcertById($conn, $id);
                if ($edit_data):
            ?>
                <form method="POST" action="process.php" enctype="multipart/form-data" class="form-crud">
                    <input type="hidden" name="id" value="<?php echo $edit_data['id']; ?>">
                    <input type="text" name="nama_artis" value="<?php echo $edit_data['nama_artis']; ?>" required>
                    <input type="text" name="tempat" value="<?php echo $edit_data['tempat']; ?>" required>
                    <input type="date" name="tanggal" value="<?php echo $edit_data['tanggal']; ?>" required>
                    <input type="number" step="0.01" name="harga" value="<?php echo $edit_data['harga']; ?>" required>

                    <?php if (!empty($edit_data['gambar'])): ?>
                        <label>Gambar Saat Ini:</label>
                        <img src="img/<?php echo $edit_data['gambar']; ?>" alt="Gambar Konser" width="100">
                    <?php else: ?>
                        <p>Tidak ada gambar saat ini.</p>
                    <?php endif; ?>

                    <input type="file" name="gambar" accept="image/*"> <!-- Input untuk gambar baru -->
                    <button type="submit" name="update">Update</button>
                </form>
            <?php else: ?>
                <p>Data konser tidak ditemukan.</p>
            <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
