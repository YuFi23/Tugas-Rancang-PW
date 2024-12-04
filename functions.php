<?php
include 'connection.php';
require_once 'database.php';

function getConcertById($conn, $id) {

    $query = "SELECT * FROM konser WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id); 
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}

// Fungsi untuk menambah konser
function createConcert($conn, $nama_artis, $tempat, $tanggal, $harga, $gambar) {
    $target_dir = "img/"; 
    $target_file = $target_dir . basename($gambar["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if (in_array($imageFileType, ["jpg", "png", "jpeg", "gif"])) {
        if (move_uploaded_file($gambar["tmp_name"], $target_file)) {
            $sql = "INSERT INTO konser (nama_artis, tempat, tanggal, harga, gambar) 
                    VALUES ('$nama_artis', '$tempat', '$tanggal', '$harga', '$gambar[name]')";
            if ($conn->query($sql) === TRUE) {
                header("Location: daftarKonser.php?added=success");
                exit();
            } else {
                echo "Error: " . $conn->error;
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    }
}

// Fungsi untuk memperbarui konser
function updateConcert($conn, $id, $nama_artis, $tempat, $tanggal, $harga, $gambar = null) {
    $sql = "UPDATE konser SET nama_artis = '$nama_artis', tempat = '$tempat', tanggal = '$tanggal', harga = '$harga' WHERE id = $id";

    if ($gambar) {
        $target_dir = "img/"; 
        $target_file = $target_dir . basename($gambar["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if (in_array($imageFileType, ["jpg", "png", "jpeg", "gif"])) {
            if (move_uploaded_file($gambar["tmp_name"], $target_file)) {
                $sql = "UPDATE konser SET nama_artis = '$nama_artis', tempat = '$tempat', tanggal = '$tanggal', harga = '$harga', gambar = '$gambar[name]' WHERE id = $id";
            } else {
                echo "Error uploading file.";
                return;
            }
        } else {
            echo "Only JPG, PNG, and GIF files are allowed.";
            return;
        }
    }

    if ($conn->query($sql) === TRUE) {
        header("Location: daftarKonser.php?updated=success");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}


// Fungsi untuk menghapus konser
function deleteConcert($conn, $id) {
    $sql = "SELECT gambar FROM konser WHERE id = $id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $gambar = $row['gambar'];

    if ($gambar) {
        unlink("img/" . $gambar); // Menghapus file gambar
    }

    $sql = "DELETE FROM konser WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        header("Location: daftarKonser.php?deleted=success");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}



function getTicketFromRequest($conn) {
    $ticket_id = isset($_REQUEST['ticket_id']) ? $_REQUEST['ticket_id'] : null;

    if ($ticket_id) {
        $stmt = $conn->prepare("SELECT * FROM tiket WHERE id = ?");
        $stmt->bind_param("i", $ticket_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $ticket = $result->fetch_assoc();

        if ($ticket) {
            return $ticket;
        } else {
            echo "<div class='alert alert-warning'>Ticket not found.</div>";
            return null;
        }
    } else {
        echo "<div class='alert alert-warning'>Ticket ID is missing in the request.</div>";
        return null;
    }
}

function generateRandomTicketCode($length = 8) {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


if (isset($_GET['ticket_code'])) {
    $ticket_code = $_GET['ticket_code'];
    $_SESSION['ticket_code'] = $ticket_code;
} else {
    if (!isset($_SESSION['ticket_code'])) {
        $ticket_code = generateRandomTicketCode();
        $_SESSION['ticket_code'] = $ticket_code;

       
        try {
            $stmt = $pdo->prepare("INSERT INTO pembayaran (ticket_code) VALUES (:ticket_code)");
            $stmt->bindParam(':ticket_code', $ticket_code);
            $stmt->execute();
        } catch (PDOException $e) {
            die("Gagal menyimpan ke database: " . $e->getMessage());
        }
    } else {
        $ticket_code = $_SESSION['ticket_code'];
    }
}

?>