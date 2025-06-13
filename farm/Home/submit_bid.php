<?php
include 'connection.php';

$veg_id = $_POST['vegetable_id'];
$buyer = $_POST['buyer_name'];
$amount = $_POST['bid_amount'];

$sql = "INSERT INTO bids (vegetable_id, buyer_name, bid_amount) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isd", $veg_id, $buyer, $amount);

if ($stmt->execute()) {
    echo "<script>alert('Bid placed successfully!'); window.location.href='buyer.php';</script>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
