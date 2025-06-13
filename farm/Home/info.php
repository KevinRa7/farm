<?php
include 'connection.php';

// Check if 'img' is provided via GET
if (isset($_GET['img'])) {
    $imageName = basename($_GET['img']); // Prevent path traversal
    $query = "SELECT image_name FROM vegetable WHERE image_name = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $imageName);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $imagePath = "images/". $row["image_name"];
        echo "<img src='$imagePath' alt='Uploaded Image' style='max-width: 90%; height:auto; display:block; margin:auto;'>";
    } else {
        echo "Image not found.";
    }

    $stmt->close();
} else {
    echo "No image specified.";
}

$conn->close();
?>
