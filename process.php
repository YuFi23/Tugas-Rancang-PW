<?php 
include('connection.php'); 
include('functions.php');

// Menambah konser
if (isset($_POST['add'])) {
    $nama_artis = $_POST['nama_artis'];
    $tempat = $_POST['tempat'];
    $tanggal = $_POST['tanggal'];
    $harga = $_POST['harga'];

    createConcert($conn, $nama_artis, $tempat, $tanggal, $harga);
}

// Menghapus konser
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    deleteConcert($conn, $id);
}

// Mengupdate konser
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nama_artis = $_POST['nama_artis'];
    $tempat = $_POST['tempat'];
    $tanggal = $_POST['tanggal'];
    $harga = $_POST['harga'];

    updateConcert($conn, $id, $nama_artis, $tempat, $tanggal, $harga);
}
?>
