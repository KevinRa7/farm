<?php
include 'connection.php';
session_start();

/* Optional: Check if farmer is logged in
if (!isset($_SESSION['farmer_id'])) {
    die("Unauthorized access. Please login first.");
}*/

// Validate and sanitize inputs
$farmerName = htmlspecialchars(trim($_POST['farmer_name']));
$vegetableName = htmlspecialchars(trim($_POST['vegetable_name']));
$quantity = intval($_POST['quantity']);
$basePrice = floatval($_POST['base_price']);
$endTime = htmlspecialchars(trim($_POST['end_time']));

// Image upload handling
$file_name=$_FILES['photos']['name'];
$allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
$fileType = mime_content_type($_FILES["photos"]["tmp_name"]);
$targetDir = "images/".$file_name;
if (!in_array($fileType, $allowedTypes)) {
    die("Invalid file type. Only JPG, JPEG, PNG, and GIF allowed.");
}

// Secure filename
$uniqueName = uniqid("veg_", true) . "_" . basename($_FILES["photos"]["name"]);
$targetFilePath = $targetDir . $uniqueName;

// Move file
if (move_uploaded_file($_FILES["photos"]["tmp_name"], $targetFilePath)) {

    // SQL Insert
    $sql = "INSERT INTO vegetable (farmer_name, vegetable_name, quantity, base_price, end_time, image_name)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sssdss", $farmerName, $vegetableName, $quantity, $basePrice, $endTime, $uniqueName);

    if ($stmt->execute()) {
        echo "<script>alert('Vegetable posted successfully!'); window.location.href='farmer.htm';</script>";
    } else {
        echo "Database error: " . $stmt->error;
    }

    $stmt->close();

} else {
    echo "Error uploading image. Please try again.";
}

$conn->close();
?>
