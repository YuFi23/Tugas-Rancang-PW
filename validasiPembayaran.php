<?php
include('database.php');
include('functions.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_id = $_POST['payment_id']; 
    $status = 'Validated'; 

    // Persiapkan query untuk update status pembayaran
    $stmt = $conn->prepare("UPDATE pembayaran SET payment_status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $payment_id);

    // Eksekusi query dan cek hasilnya
    if ($stmt->execute()) {
        // Cek apakah kode tiket sudah ada atau belum
        $stmt_check = $conn->prepare("SELECT ticket_code FROM pembayaran WHERE id = ?");
        $stmt_check->bind_param("i", $payment_id);
        $stmt_check->execute();
        $result = $stmt_check->get_result();
        $data = $result->fetch_assoc();
        
        // Jika kode tiket belum ada, maka generate kode tiket baru
        if (empty($data['ticket_code'])) {
            $ticket_code = generateRandomTicketCode();  // Generate ticket code
            // Update ticket code di database
            $update_ticket_code_stmt = $conn->prepare("UPDATE pembayaran SET ticket_code = ? WHERE id = ?");
            $update_ticket_code_stmt->bind_param("si", $ticket_code, $payment_id);
            $update_ticket_code_stmt->execute();
        } else {
            // Jika kode tiket sudah ada, tidak perlu generate ulang
            $ticket_code = $data['ticket_code'];  // Ambil kode tiket yang sudah ada
        }
        
        // Simpan ticket_code di session dan alihkan ke success.php
        $_SESSION['ticket_code'] = $ticket_code;
        echo "<script>alert('Pembayaran berhasil divalidasi!');</script>";
        header("Location: success.php");
        exit();  // Pastikan tidak ada kode lain yang dijalankan setelah header()
    } else {
        // Jika gagal, tampilkan alert gagal
        echo "<script>alert('Gagal memvalidasi pembayaran.');</script>";
    }

    // Menutup statement setelah digunakan
    $stmt->close();
}
?>
