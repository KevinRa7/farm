<?php
session_start();
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['pass']);

    if (empty($email) || empty($password)) {
        echo "Email and password are required.";
        exit;
    }

    $sql = "SELECT * FROM details WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        echo "Error preparing statement.";
        exit;
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();

        
        if ($password === $row['password']) {
            $_SESSION['email'] = $row['email'];
            header('Location: /WEBFORM/Home/main.html');
            exit;}
        
        else if (password_verify($password, $row['password'])) {
             $_SESSION['email'] = $row['email'];
            header('Location: /WEBFORM/Home/main.html');
            exit;}

    }
    

    echo "Invalid email or password.";
    $stmt->close();
    $conn->close();
} else {
    echo "Only POST requests are allowed.";
}
