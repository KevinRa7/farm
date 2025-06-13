<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate inputs
    if (!empty($_POST['fname']) && !empty($_POST['email']) && !empty($_POST['pass'])) {
        $name = htmlspecialchars(trim($_POST['fname']));
        $email = htmlspecialchars(trim($_POST['email']));
        $password = htmlspecialchars(trim($_POST['pass']));

        // Hash the password
        $hashedPassword = password_hash($_POST['pass'], PASSWORD_DEFAULT);
        $sql = "SELECT * FROM details WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Error: Email already exists.";
        exit ();
    }
        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO details (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $hashedPassword);

        if ($stmt->execute()) {
            echo "Registration successful!";
            header("Location: login.html");
            $stmt->close();
        } else {
            echo "Error: " . $conn->error;
        }

        
    } else {
        echo "Please fill in all fields.";
    }

    $conn->close();
}
?>
