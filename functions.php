<?php
include 'connection.php';

// functions.php

// Function to create a new concert
function createConcert($conn, $nama_artis, $tempat, $tanggal, $harga) {
    $stmt = $conn->prepare("INSERT INTO konser (nama_artis, tempat, tanggal, harga) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssd", $nama_artis, $tempat, $tanggal, $harga);

    if ($stmt->execute()) {
        header('Location: daftarKonser.php?added=success');
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }

    $stmt->close();
}

// Function to get concert data by ID for editing
function getConcertById($conn, $id) {
    $stmt = $conn->prepare("SELECT * FROM konser WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Function to update concert data
function updateConcert($conn, $id, $nama_artis, $tempat, $tanggal, $harga) {
    $stmt = $conn->prepare("UPDATE konser SET nama_artis = ?, tempat = ?, tanggal = ?, harga = ? WHERE id = ?");
    $stmt->bind_param("sssdi", $nama_artis, $tempat, $tanggal, $harga, $id);

    if ($stmt->execute()) {
        header('Location: daftarKonser.php');
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }

    $stmt->close();
}

// Function to delete a concert
function deleteConcert($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM konser WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header('Location: daftarKonser.php');
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }

    $stmt->close();
}

?>
