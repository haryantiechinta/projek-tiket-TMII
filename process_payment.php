<?php
session_start();
include 'koneksi.php';

// Debug cek session user
// echo '<pre>'; print_r($_SESSION); echo '</pre>';

$user_id = $_SESSION['user']['id'] ?? null;
if (!$user_id) {
    die("Anda harus login dulu sebelum membeli tiket.");
}

// Ambil data POST dari form
$ticket_id = $_POST['ticket_id'] ?? null;
$tanggal = $_POST['tanggal'] ?? null;
$nama = $_POST['nama'] ?? null;
$metode = $_POST['metode'] ?? null;
$quantity = 1;
$status = 'pending';

if (!$ticket_id || !$tanggal || !$nama || !$metode) {
    die("Data tidak lengkap.");
}

// Ambil harga tiket dari database
$stmt = $conn->prepare("SELECT price FROM tickets WHERE id = ?");
$stmt->bind_param("i", $ticket_id);
$stmt->execute();
$result = $stmt->get_result();
$tiket = $result->fetch_assoc();
if (!$tiket) {
    die("Tiket tidak ditemukan.");
}
$harga = $tiket['price'];
$total_harga = $harga * $quantity;

// Upload bukti pembayaran
if (!isset($_FILES['bukti']) || $_FILES['bukti']['error'] !== UPLOAD_ERR_OK) {
    die("Upload bukti pembayaran gagal atau belum diupload.");
}

$uploadDir = 'uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}
$uploadFileName = uniqid() . '_' . basename($_FILES['bukti']['name']);
$uploadPath = $uploadDir . $uploadFileName;

if (!move_uploaded_file($_FILES['bukti']['tmp_name'], $uploadPath)) {
    die("Gagal memindahkan file bukti pembayaran.");
}

// Simpan data ke tabel orders
$stmt = $conn->prepare("INSERT INTO orders (user_id, ticket_id, nama_pemesan, tanggal_kunjungan, order_date, bukti_pembayaran, status, quantity, total_harga, payment_method) VALUES (?, ?, ?, ?, NOW(), ?, ?, ?, ?, ?)");
$stmt->bind_param("iissssids", $user_id, $ticket_id, $nama, $tanggal, $uploadFileName, $status, $quantity, $total_harga, $metode);

if ($stmt->execute()) {
    echo "<script>alert('Pembayaran berhasil dikirim!'); window.location.href='purchase_history.php';</script>";
} else {
    echo "Gagal menyimpan data: " . $stmt->error;
}
