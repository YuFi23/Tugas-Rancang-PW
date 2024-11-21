<?php
include 'database.php'; // Menghubungkan ke file database.php

// Fungsi untuk menambah tiket baru
function createTicket($conn, $namakonser, $price) {
    // Validasi harga untuk memastikan tidak bernilai negatif
    if ($price <= 0) {
        echo "<div class='alert alert-danger'>Price must be a positive number.</div>";
        return;
    }

    // Query dengan prepared statement untuk mencegah SQL injection
    $stmt = $conn->prepare("INSERT INTO tiket (namakonser, price) VALUES (?, ?)");
    $stmt->bind_param("si", $namakonser, $price);

    if ($stmt->execute()) {
        header('Location: manage.php'); // Redirect setelah berhasil
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }

    $stmt->close(); // Menutup statement
}

// Fungsi untuk menampilkan daftar tiket
function displayTickets($conn) {
    $sql = "SELECT * FROM tiket";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table class='table'>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Konser - Tanggal</th>
                        <th>Harga</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row['id'] . "</td>
                    <td>" . $row['namakonser'] . "</td>
                    <td>" . number_format($row['price'], 0, ',', '.') . "</td>
                    <td>
                        <a href='edit.php?id=" . $row['id'] . "' class='btn btn-primary'>Edit</a>
                        <a href='process.php?delete=" . $row['id'] . "' class='btn btn-danger'>Delete</a>
                    </td>
                  </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>No tickets found.</p>";
    }
}

// Fungsi untuk menghapus tiket berdasarkan ID
function deleteTicket($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM tiket WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header('Location: manage.php'); // Redirect setelah berhasil
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }

    $stmt->close();
}

// Fungsi untuk mengambil data tiket berdasarkan ID (untuk edit)
function getTicketById($conn, $id) {
    $stmt = $conn->prepare("SELECT * FROM tiket WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc(); // Mengembalikan data tiket
}

// Fungsi untuk memperbarui tiket
function updateTicket($conn, $id, $namakonser, $price) {
    // Validasi harga untuk memastikan tidak bernilai negatif
    if ($price <= 0) {
        echo "<div class='alert alert-danger'>Price must be a positive number.</div>";
        return;
    }

    $stmt = $conn->prepare("UPDATE tiket SET namakonser = ?, price = ? WHERE id = ?");
    $stmt->bind_param("sii", $namakonser, $price, $id);

    if ($stmt->execute()) {
        header('Location: manage.php'); // Redirect setelah berhasil
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }

    $stmt->close(); // Menutup statement
}

// Fungsi untuk mengambil data tiket dari request (untuk edit)
function getTicketFromRequest($conn) {
    // Memeriksa jika parameter 'id' ada di URL
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Menggunakan fungsi yang sudah ada untuk mendapatkan tiket berdasarkan ID
        $ticket = getTicketById($conn, $id);

        // Memeriksa apakah tiket ada
        if ($ticket) {
            return $ticket; // Mengembalikan data tiket
        } else {
            echo "<p>Ticket not found!</p>";
            exit(); // Menghentikan eksekusi jika tiket tidak ditemukan
        }
    } else {
        echo "<p>No ticket ID provided!</p>";
        exit(); // Menghentikan eksekusi jika ID tidak ada
    }
}
?>
