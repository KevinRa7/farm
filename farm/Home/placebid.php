<?php
include 'connection.php';

$veg_id = $_GET['id'];
$result = $conn->query("SELECT * FROM vegetable WHERE id = $veg_id");
$veg = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Place Bid</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-5">
  <h2 class="text-success">Place a Bid for <?= htmlspecialchars($veg['vegetable_name']) ?></h2>
  <p><strong>Farmer:</strong> <?= htmlspecialchars($veg['farmer_name']) ?></p>
  <p><strong>Base Price:</strong> ₹<?= $veg['base_price'] ?> / kg</p>

  <form action="submit_bid.php" method="POST">
    <input type="hidden" name="vegetable_id" value="<?= $veg['id'] ?>" />
    <div class="mb-3">
      <label>Your Name</label>
      <input type="text" class="form-control" name="buyer_name" required />
    </div>
    <div class="mb-3">
      <label>Bid Amount (₹/kg)</label>
      <input type="number" class="form-control" name="bid_amount" step="0.01" min="<?= $veg['base_price'] ?>" required />
    </div>
    <button type="submit" class="btn btn-success">Submit Bid</button>
  </form>
</div>
</body>
</html>
