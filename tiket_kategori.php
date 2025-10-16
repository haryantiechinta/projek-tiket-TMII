<?php
include 'koneksi.php';

$category = $_GET['category'] ?? '';
$tanggal = $_GET['date'] ?? date('Y-m-d'); // kalau belum pilih, default hari ini

$stmt = $conn->prepare("SELECT * FROM tickets WHERE category = ?");
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($category) ?> - TMII</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
    .ticket-card {
      border-radius: 12px;
      border: 1px solid #ddd;
      background-color: #fff;
      transition: all 0.2s ease;
    }
    .ticket-card:hover {
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      transform: translateY(-2px);
    }
    .ticket-title { font-weight: 600; color: #198754; }
    .ticket-price { font-size: 1.2rem; font-weight: 700; color: #000; }
    .btn-beli {
      background-color: #198754;
      border: none;
      border-radius: 10px;
      color: white;
      font-weight: 500;
    }
    .btn-beli:hover { background-color: #157347; }
  </style>
</head>
<body>

<div class="container py-5">
  <h3 class="text-primary mb-4 fw-bold">
    Tiket untuk <span class="text-success"><?= htmlspecialchars($category) ?></span>  
    (Tanggal: <?= date('d M Y', strtotime($tanggal)) ?>)
  </h3>

  <?php if ($result->num_rows > 0): ?>
    <div class="d-flex flex-column gap-4">
      <?php while($row = $result->fetch_assoc()): ?>
        <div class="ticket-card p-4">
          <div class="d-flex justify-content-between flex-wrap align-items-center">
            <div>
              <h5 class="ticket-title mb-1"><?= htmlspecialchars($row['name']) ?></h5>
              <p class="mb-2 text-muted"><?= htmlspecialchars($row['description']) ?></p>
              <p class="ticket-price">Rp <?= number_format($row['price'], 0, ',', '.') ?></p>
            </div>
            <div>
              <a href="confirm_payment.php?ticket_id=<?= $row['id'] ?>&date=<?= urlencode($tanggal) ?>"
                 class="btn btn-beli px-4 py-2">Beli Tiket</a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  <?php else: ?>
    <p class="text-center text-muted mt-5">Belum ada tiket untuk kategori ini.</p>
  <?php endif; ?>
</div>

</body>
</html>
