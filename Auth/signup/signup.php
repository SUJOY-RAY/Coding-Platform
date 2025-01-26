<?php
// Database connection
$host = 'localhost';
$dbname = 'user_database';
$username = 'root';
$password = '';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST['UserName'];
    $email = $_POST['GMAIL'];
    $pass = $_POST['Password'];
    $confirmPass = $_POST['ConfirmPassword'];

    // Validate that the passwords match
    if ($pass !== $confirmPass) {
        echo "Passwords do not match!";
        exit;
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format!";
        exit;
    }

    // Hash the password for security
    $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

    // Check if the email already exists in the database
    $checkEmailQuery = "SELECT * FROM users WHERE email = ?";
    if ($stmt = $conn->prepare($checkEmailQuery)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            echo "Email is already taken!";
            $stmt->close();
            exit;
        }
        $stmt->close();
    }

    // Prepare the SQL statement to insert user data into the database
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    // Prepare and bind
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sss", $username, $email, $hashed_password);
        
        // Execute the query
        if ($stmt->execute()) {
            header("Location: /Coding-Platform/Auth/login/signin.html"); // Redirect to login page
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error preparing the SQL statement.";
    }
}

// Close the database connection
$conn->close();
?>
