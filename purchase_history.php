<?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'user') {
    header("Location: login_form.php");
    exit;
}

include "db.php";
$db = new Database();
$conn = $db->getConnection();

$user_id = $_SESSION['user']['id'];

$sql = "SELECT o.id, t.name AS ticket_name, t.price, o.quantity, o.total_harga,
               o.bukti_pembayaran, o.order_date, o.status
        FROM orders o 
        JOIN tickets t ON o.ticket_id = t.id 
        WHERE o.user_id = ?
        ORDER BY o.order_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Riwayat Pembelian</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="dashboard_user.php">TMII Ticketing</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a href="dashboard_user.php" class="nav-link">Home</a></li>
        <li class="nav-item"><a href="dashboard_user.php?page=tiket" class="nav-link">Tiket</a></li>
        <li class="nav-item"><a href="purchase_history.php" class="nav-link active">Riwayat</a></li>
        <li class="nav-item"><a href="logout.php" class="nav-link text-danger">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4">
  <h2 class="mb-4">📜 Riwayat Pembelian Tiket</h2>

  <table class="table table-bordered table-hover bg-white shadow-sm">
    <thead class="table-dark">
      <tr>
        <th>ID Pesanan</th>
        <th>Nama Tiket</th>
        <th>Jumlah</th>
        <th>Total Harga</th>
        <th>Bukti Pembayaran</th>
        <th>Status</th>
        <th>Tanggal Pesan</th>
      </tr>
    </thead>
    <tbody>
      <?php if($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= htmlspecialchars($row['ticket_name']) ?></td>
          <td><?= $row['quantity'] ?></td>
          <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
          <td>
            <?php if(!empty($row['bukti_pembayaran'])): ?>
              <a href="uploads/<?= htmlspecialchars($row['bukti_pembayaran']) ?>" target="_blank">Lihat Bukti</a>
            <?php else: ?>
              <span class="text-muted">Belum diupload</span>
            <?php endif; ?>
          </td>
          <td>
            <?php if($row['status'] == 'pending'): ?>
              <span class="badge bg-warning text-dark">Pending</span>
            <?php elseif($row['status'] == 'success'): ?>
              <span class="badge bg-success">Success</span>
            <?php else: ?>
              <span class="badge bg-secondary"><?= htmlspecialchars($row['status']) ?></span>
            <?php endif; ?>
          </td>
          <td><?= $row['order_date'] ?></td>
        </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="7" class="text-center">Belum ada pembelian tiket.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

</body>
</html>
