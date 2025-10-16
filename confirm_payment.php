<?php
include 'koneksi.php';

$ticket_id = $_GET['ticket_id'] ?? '';
$tanggal = $_GET['date'] ?? date('Y-m-d');

// Ambil detail tiket
$stmt = $conn->prepare("SELECT * FROM tickets WHERE id = ?");
$stmt->bind_param("i", $ticket_id);
$stmt->execute();
$result = $stmt->get_result();
$ticket = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Konfirmasi Pembayaran</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
  <h3 class="mb-4">Konfirmasi Pembelian Tiket</h3>

  <?php if ($ticket): ?>
    <div class="mb-4">
      <h5><?= htmlspecialchars($ticket['name']) ?></h5>
      <p><?= htmlspecialchars($ticket['description']) ?></p>
      <p><strong>Harga Tiket:</strong> Rp <?= number_format($ticket['price'], 0, ',', '.') ?></p>
      <p><strong>Tanggal Kunjungan:</strong> <?= date('d M Y', strtotime($tanggal)) ?></p>
    </div>

    <form action="process_payment.php" method="POST" enctype="multipart/form-data">
      <!-- Hidden -->
      <input type="hidden" name="ticket_id" value="<?= $ticket['id'] ?>">
      <input type="hidden" name="tanggal" value="<?= htmlspecialchars($tanggal) ?>">

      <!-- Nama Pemesan -->
      <div class="mb-3">
        <label for="nama" class="form-label">Nama Pemesan</label>
        <input type="text" class="form-control" id="nama" name="nama" required>
      </div>

      <!-- Metode Pembayaran -->
      <div class="mb-3">
        <label for="metode" class="form-label">Metode Pembayaran</label>
        <select class="form-select" id="metode" name="metode" required>
          <option value="">-- Pilih Metode --</option>
          <option value="Transfer Bank">Transfer Bank</option>
          <option value="QRIS">QRIS</option>
          <option value="E-Wallet">E-Wallet</option>
        </select>
      </div>

      <!-- Upload Bukti Pembayaran -->
      <div class="mb-3">
        <label for="bukti" class="form-label">Upload Bukti Pembayaran</label>
        <input type="file" class="form-control" id="bukti" name="bukti" accept="image/*" required>
      </div>

      <button type="submit" class="btn btn-success">Kirim & Proses</button>
    </form>

  <?php else: ?>
    <p class="text-danger">Tiket tidak ditemukan.</p>
  <?php endif; ?>
</div>
</body>
</html>
