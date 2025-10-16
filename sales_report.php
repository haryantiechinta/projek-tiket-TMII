<?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: login_form.php");
    exit;
}

include "db.php";
$db = new Database();
$conn = $db->getConnection();

// Query: hitung jumlah order & total pendapatan per tiket
$sql = "SELECT 
            t.name, 
            COUNT(o.id) AS total_terjual, 
            SUM(t.price) AS total_pendapatan
        FROM tickets t
        LEFT JOIN orders o ON t.id = o.ticket_id
        GROUP BY t.id
        ORDER BY total_pendapatan DESC";

$result = $conn->query($sql);
?>

<h2>💰 Rekap Penjualan Tiket</h2>
<a href="admin_dashboard.php">⬅️ Kembali ke Dashboard</a> | 
<a href="logout.php">Logout</a>

<table border="1" cellpadding="10">
<tr><th>Nama Tiket</th><th>Total Terjual</th><th>Total Pendapatan (Rp)</th></tr>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= $row['name'] ?></td>
    <td><?= $row['total_terjual'] ?? 0 ?></td>
    <td><?= $row['total_pendapatan'] ?? 0 ?></td>
</tr>
<?php endwhile; ?>
</table>
