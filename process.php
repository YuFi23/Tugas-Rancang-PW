<?php
// Koneksi ke database
include 'database.php';

// Cek jika form create dikirimkan
if (isset($_POST['create'])) {
    $namakonser = $_POST['namakonser'];
    $price = $_POST['price'];

    // Periksa koneksi
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Gunakan prepared statement untuk menambah tiket baru
    $stmt = $conn->prepare("INSERT INTO tiket (namakonser, price) VALUES (?, ?)");
    $stmt->bind_param("si", $namakonser, $price);  // "s" untuk string, "i" untuk integer

    if ($stmt->execute()) {
        // Redirect setelah berhasil menambah tiket
        header('Location: manage.php');  // Ganti dengan halaman yang sesuai
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Tutup statement dan koneksi
    $stmt->close();
    $conn->close();
}

// Proses Delete Tiket
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Periksa koneksi
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Gunakan prepared statement untuk menghapus tiket berdasarkan ID
    $stmt = $conn->prepare("DELETE FROM tiket WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Redirect ke halaman manage.php setelah tiket dihapus
        header('Location: manage.php');
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Tutup statement dan koneksi
    $stmt->close();
    $conn->close();
}

// Proses Update Tiket
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $namakonser = $_POST['namakonser'];
    $price = $_POST['price'];

    // Periksa koneksi
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Gunakan prepared statement untuk mengupdate tiket berdasarkan ID
    $stmt = $conn->prepare("UPDATE tiket SET namakonser = ?, price = ? WHERE id = ?");
    $stmt->bind_param("sii", $namakonser, $price, $id);

    if ($stmt->execute()) {
        // Redirect ke halaman manage.php setelah tiket diupdate
        header('Location: manage.php');
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Tutup statement dan koneksi
    $stmt->close();
    $conn->close();
}

// Koneksi untuk menampilkan tiket
include 'database.php';

// Periksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query untuk mendapatkan semua tiket
$sql = "SELECT * FROM tiket";
$result = $conn->query($sql);

echo "<h3>All Tickets</h3>";

if ($result->num_rows > 0) {
    echo "<table border='1'>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Konser</th>
                    <th>Harga</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>";
    // Menampilkan data tiap tiket
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row["id"]. "</td>
                <td>" . $row["namakonser"]. "</td>
                <td>" . $row["price"]. "</td>
                <td>
                    <a href='edit.php?id=" . $row['id'] . "'>Edit</a> | 
                    <a href='manage.php?delete=" . $row['id'] . "'>Delete</a>
                </td>
              </tr>";
    }
    echo "</tbody></table>";
} else {
    echo "<p>No tickets found.</p>";
}

// Tutup koneksi
$conn->close();
?>
