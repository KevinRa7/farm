<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve input values
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validation flags
    $errors = [];

    // Validate username (alphabets only)
    if (!preg_match("/^[a-zA-Z]+$/", $username)) {
        $errors[] = "Username should contain alphabets only.";
    }

    // Validate password (length greater than 8)
    if (strlen($password) < 8) {
        $errors[] = "Password should be at least 8 characters long.";
    }

    // If there are errors, display them
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
    } else {
        // Process the form (e.g., login logic)
        echo "<p style='color:green;'>Login successful!</p>";
    }
}
?>
