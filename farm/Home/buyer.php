<?php
include 'connection.php';
$sql = "SELECT * FROM vegetable ORDER BY end_time ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Buyer Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <style>
    body { background: #f4f4f4; font-family: 'Segoe UI', sans-serif; }
    .card { border-radius: 1rem; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
    .dark-mode { background: #121212; color: white; }
    .dark-mode .card { background-color: #1e1e1e; color: white; }
    .ra{display: block;text-align: right;
    text-decoration: none;}
  </style>
</head>
<body>
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="text-success"><a href="main.html" style="text-decoration: none; color:green">Available Vegetables for Bidding</a></h2>
      <button id="toggleTheme" class="btn btn-outline-secondary">🌙 Toggle Dark Mode</button>
    </div>
    <div class="row">
      <?php while ($row = $result->fetch_assoc()): ?>
  <?php
$veg_id = $row['id'];
$now = new DateTime();
$end_time = new DateTime($row['end_time']);
// Fetch highest bid
$bid_res = $conn->query("SELECT buyer_name, MAX(bid_amount) AS highest FROM bids WHERE vegetable_id = $veg_id");
$bid = $bid_res->fetch_assoc();
$highest_bid = $bid['highest'] ?? $row['base_price'];
$highest_bidder = $bid['buyer_name'] ?? 'No bids yet';

// Check if auction should be closed
if ($now > $end_time->modify('+10 minutes') && $row['status'] === 'available' && $highest_bidder !== 'No bids yet') {
    $conn->query("UPDATE vegetable SET status = 'sold', sold_to = '$highest_bidder', base_price = '$highest_bid' WHERE id = $veg_id");
    $row['status'] = 'sold';
    $row['sold_to'] = $highest_bidder;
}
?>
        <div class="col-md-4 mb-4">
          <div class="card p-3">
  <h5 class="text-success"><?= htmlspecialchars($row['vegetable_name']) ?></h5>
  <p><strong>Farmer:</strong> <?= htmlspecialchars($row['farmer_name']) ?></p>
  <p><strong>Quantity:</strong> <?= $row['quantity'] ?> kg</p>
  <p><strong>Current Price:</strong> ₹<?= $highest_bid ?> / kg</p>
  <p><strong>Ends:</strong> <?= date("d M Y, h:i A", strtotime($row['end_time'])) ?></p>

  <?php if ($row['status'] === 'sold'): ?>
    <div class="alert alert-warning">❗ Sold to <strong><?= $row['sold_to'] ?></strong></div>
  <?php else: ?>
    <a href="placebid.php?id=<?= $row['id'] ?>" class="btn btn-primary w-100">Place Bid</a>
  <?php endif; ?>
</div>

        </div>
      <?php endwhile; ?>
    </div>
  </div>

  <script>
    const toggleBtn = document.getElementById('toggleTheme');
    const body = document.body;
    if (localStorage.getItem('theme') === 'dark') body.classList.add('dark-mode');

    toggleBtn.addEventListener('click', () => {
      body.classList.toggle('dark-mode');
      localStorage.setItem('theme', body.classList.contains('dark-mode') ? 'dark' : 'light');
    });
  </script>
</body>
</html>

<?php $conn->close(); ?>
