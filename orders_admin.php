<?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: login_form.php");
    exit;
}

include "db.php";
$db = new Database();
$conn = $db->getConnection();

// Aksi admin: approve / reject
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $status = ($_GET['action'] == 'approve') ? 'success' :
              (($_GET['action'] == 'reject') ? 'rejected' : 'pending');
    $conn->query("UPDATE orders SET status='$status' WHERE id=$id");
}

// Ambil semua data order
$sql = "SELECT o.id, u.username, o.nama_pemesan, t.name AS tiket, t.price, 
               o.tanggal_kunjungan, o.quantity, o.total_harga, o.payment_method,
               o.bukti_pembayaran, o.status, o.order_date
        FROM orders o
        JOIN users u ON o.user_id = u.id
        JOIN tickets t ON o.ticket_id = t.id
        ORDER BY o.order_date DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Laporan Semua Order</title>
  <!-- 🔹 Bootstrap dari folder kamu -->
  <link rel="stylesheet" href="bs-4.6.2/css/bootstrap.min.css">
  <style>
    body {
      background: #f5f7fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    h2 {
      text-align: center;
      margin: 30px 0 20px;
      font-weight: 600;
      color: #333;
    }
    .table-container {
      background: #fff;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    .table thead th {
      background: #e9eefb;
      color: #333;
      font-weight: 600;
      text-align: center;
    }
    .table td, .table th {
      text-align: center;
      vertical-align: middle;
      border: 1px solid #bbb !important;
    }
    .btn-sm {
      padding: 4px 8px;
      font-size: 0.8rem;
    }
  </style>
</head>
<body>

<div class="container">
  <h2>📊 Laporan Semua Order</h2>

  <div class="mb-3 text-center">
    <a href="admin_dashboard.php" class="btn btn-secondary">⬅️ Kembali ke Dashboard</a>
    <a href="logout.php" class="btn btn-danger ml-2">Logout</a>
  </div>

  <div class="table-container">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>ID</th>
          <th>Username</th>
          <th>Nama Pemesan</th>
          <th>Tiket</th>
          <th>Harga</th>
          <th>Qty</th>
          <th>Total</th>
          <th>Tanggal Kunjungan</th>
          <th>Bukti Pembayaran</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= htmlspecialchars($row['username']) ?></td>
          <td><?= htmlspecialchars($row['nama_pemesan']) ?></td>
          <td><?= htmlspecialchars($row['tiket']) ?></td>
          <td>Rp <?= number_format($row['price'],0,',','.') ?></td>
          <td><?= $row['quantity'] ?></td>
          <td>Rp <?= number_format($row['total_harga'],0,',','.') ?></td>
          <td><?= $row['tanggal_kunjungan'] ?></td>
          <td>
            <?php if($row['bukti_pembayaran']): ?>
              <a href="uploads/<?= htmlspecialchars($row['bukti_pembayaran']) ?>" target="_blank" class="btn btn-info btn-sm">Lihat</a>
            <?php else: ?>
              <span class="text-muted">Belum upload</span>
            <?php endif; ?>
          </td>
          <td>
            <?php if($row['status'] == 'pending'): ?>
              <span class="badge badge-warning">Pending</span>
            <?php elseif($row['status'] == 'success'): ?>
              <span class="badge badge-success">Sukses</span>
            <?php elseif($row['status'] == 'rejected'): ?>
              <span class="badge badge-danger">Ditolak</span>
            <?php endif; ?>
          </td>
          <td>
            <?php if($row['status'] == 'pending'): ?>
              <a href="?action=approve&id=<?= $row['id'] ?>" class="btn btn-success btn-sm">✅ ACC</a>
              <a href="?action=reject&id=<?= $row['id'] ?>" class="btn btn-danger btn-sm">❌ Tolak</a>
            <?php else: ?>
              <span class="text-muted">-</span>
            <?php endif; ?>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- 🔹 Bootstrap JS -->
<script src="bs-4.6.2/js/jquery.min.js"></script>
<script src="bs-4.6.2/js/bootstrap.bundle.min.js"></script>

</body>
</html>
