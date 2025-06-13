<?php
$conn = new mysqli("localhost", "root", "", "farmdb");
$farmer = "Ravi"; // <- You can make this dynamic with login

$result = $conn->query("SELECT * FROM vegetables WHERE farmer_name = '$farmer'");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Farmer Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
  <div class="container mt-5">
    <h2 class="text-success mb-4">Your Posted Vegetables</h2>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Vegetable</th>
          <th>Quantity</th>
          <th>Status</th>
          <th>Sold To</th>
          <th>Final Price</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($veg = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($veg['vegetable_name']) ?></td>
            <td><?= $veg['quantity'] ?> kg</td>
            <td>
              <?= $veg['status'] === 'sold' ? '<span class="text-danger">Sold</span>' : '<span class="text-warning">Open</span>' ?>
            </td>
            <td><?= $veg['sold_to'] ?? '-' ?></td>
            <td>₹<?= $veg['base_price'] ?> /kg</td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
