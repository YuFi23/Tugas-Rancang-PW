<?php 
include('database.php'); 
include('functions.php');

if (isset($_POST['add'])) {
    $nama_artis = $_POST['nama_artis'];
    $tempat = $_POST['tempat'];
    $tanggal = $_POST['tanggal'];
    $harga = $_POST['harga'];
    
    // Proses upload gambar dan tambahkan konser
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $gambar = $_FILES['gambar'];
        createConcert($conn, $nama_artis, $tempat, $tanggal, $harga, $gambar);
    } else {
        echo "Error: Tidak ada gambar yang di-upload.";
    }
}

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nama_artis = $_POST['nama_artis'];
    $tempat = $_POST['tempat'];
    $tanggal = $_POST['tanggal'];
    $harga = $_POST['harga'];

    // Update konser jika ada gambar baru
    if ($_FILES['gambar']['error'] == 0) {
        updateConcert($conn, $id, $nama_artis, $tempat, $tanggal, $harga, $_FILES['gambar']);
    } else {
        updateConcert($conn, $id, $nama_artis, $tempat, $tanggal, $harga);
    }
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    deleteConcert($conn, $id);
}
?>
