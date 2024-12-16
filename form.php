<?php
session_start();
$host = "localhost";
$dbname = "admin_db";
$username = "root";
$password = "";

// Database connection
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Check if inputs are not empty
        if (!empty($_POST['username']) && !empty($_POST['password'])) {
            $inputUsername = trim($_POST['username']);
            $inputPassword = trim($_POST['password']);

            // Fetch user from database
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->bindParam(':username', $inputUsername);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verify user and password
            if ($user && $inputPassword === $user['password']) {
                // Start a session and redirect to the dashboard page
                $_SESSION['username'] = $user['username'];
                header("Location: dashboard.html");
                exit();
            } else {
                echo "<script>alert('Invalid username or password!');</script>";
            }
        } else {
            echo "<script>alert('Please enter both username and password!');</script>";
        }
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>