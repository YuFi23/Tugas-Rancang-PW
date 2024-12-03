<?php
include('connection.php');
include('functions.php');

// Logout session
if (isset($_GET['logout'])) {
    session_start();
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

// Proses tambah konser
if (isset($_POST['add'])) {
    $nama_artis = $_POST['nama_artis'];
    $tempat = $_POST['tempat'];
    $tanggal = $_POST['tanggal'];
    $harga = $_POST['harga'];
    
    // Menangani upload gambar
    $gambar = $_FILES['gambar']['name'];
    $gambar_tmp = $_FILES['gambar']['tmp_name'];
    
    // Membuat nama file gambar unik untuk menghindari duplikasi
    $gambar_new_name = time() . '_' . $gambar;
    
    // Pindahkan gambar ke direktori 'img/'
    move_uploaded_file($gambar_tmp, "img/" . $gambar_new_name);
    
    // Menyimpan data ke dalam database
    $stmt = $conn->prepare("INSERT INTO konser (nama_artis, tempat, tanggal, harga, gambar) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nama_artis, $tempat, $tanggal, $harga, $gambar_new_name);
    $stmt->execute();
    
    header("Location: daftarKonser.php?added=success");
    exit();
}

// Proses update konser
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nama_artis = $_POST['nama_artis'];
    $tempat = $_POST['tempat'];
    $tanggal = $_POST['tanggal'];
    $harga = $_POST['harga'];

    // Cek jika ada gambar baru yang diupload
    if ($_FILES['gambar']['name'] != "") {
        $gambar = $_FILES['gambar']['name'];
        $gambar_tmp = $_FILES['gambar']['tmp_name'];

        // Membuat nama file gambar unik untuk menghindari duplikasi
        $gambar_new_name = time() . '_' . $gambar;

        // Pindahkan gambar ke direktori 'img/'
        move_uploaded_file($gambar_tmp, "img/" . $gambar_new_name);

        // Update data konser dengan gambar baru
        $stmt = $conn->prepare("UPDATE konser SET nama_artis = ?, tempat = ?, tanggal = ?, harga = ?, gambar = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $nama_artis, $tempat, $tanggal, $harga, $gambar_new_name, $id);
    } else {
        // Jika gambar tidak diubah, cukup update data tanpa mengganti gambar
        $stmt = $conn->prepare("UPDATE konser SET nama_artis = ?, tempat = ?, tanggal = ?, harga = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $nama_artis, $tempat, $tanggal, $harga, $id);
    }

    if ($stmt->execute()) {
        header("Location: daftarKonser.php?updated=success");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
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
            <form method="POST" action="daftarKonser.php" enctype="multipart/form-data" class="form-crud">
                <input type="text" name="nama_artis" placeholder="Nama Artis" required>
                <input type="text" name="tempat" placeholder="Tempat" required>
                <input type="date" name="tanggal" required>
                <input type="number" step="0.01" name="harga" placeholder="Harga Tiket" required>
                <input type="file" name="gambar" accept="image/*" required>
                <button type="submit" name="add">Tambah Konser</button>
            </form>

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

            <!-- Form untuk Edit Konser -->
            <?php
            if (isset($_GET['edit'])) {
                $id = $_GET['edit'];
                
                // Mengambil data konser berdasarkan ID
                $stmt = $conn->prepare("SELECT * FROM konser WHERE id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                $concert = $result->fetch_assoc();
                
                if ($concert) {
                    ?>
                    <!-- Form untuk mengedit konser -->
                    <form method="POST" action="daftarKonser.php" enctype="multipart/form-data" class="form-crud">
                        <input type="hidden" name="id" value="<?php echo $concert['id']; ?>">
                        <input type="text" name="nama_artis" placeholder="Nama Artis" value="<?php echo $concert['nama_artis']; ?>" required>
                        <input type="text" name="tempat" placeholder="Tempat" value="<?php echo $concert['tempat']; ?>" required>
                        <input type="date" name="tanggal" value="<?php echo $concert['tanggal']; ?>" required>
                        <input type="number" step="0.01" name="harga" placeholder="Harga Tiket" value="<?php echo $concert['harga']; ?>" required>
                        <input type="file" name="gambar" accept="image/*">
                        <button type="submit" name="update">Update Konser</button>
                    </form>
                    <?php
                }
            }
            ?>
        </div>
    </div>
</body>
</html>
