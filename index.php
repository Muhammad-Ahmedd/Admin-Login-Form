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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Admin Login</title>

    <!-- Google Font: Luxurious Font -->
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Montserrat:wght@400;700&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #2C3E50, #000000);
            color: #fff;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-attachment: fixed;
            overflow: hidden;
        }

        /* Luxury Login Container */
        .login-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            padding: 40px;
            max-width: 400px;
            width: 100%;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .login-container:before {
            content: "";
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                45deg, rgba(255, 223, 0, 0.3), rgba(255, 69, 0, 0.3)
            );
            z-index: -1;
            animation: rotate-bg 10s linear infinite;
        }

        @keyframes rotate-bg {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Title */
        h2 {
            font-family: 'Great Vibes', cursive;
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: #F8C471;
            text-shadow: 0 3px 6px rgba(0, 0, 0, 0.5);
        }

        /* Form Elements */
        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        label {
            font-size: 1.1rem;
            text-align: left;
            color: #F8C471;
            font-weight: bold;
        }

        input {
            padding: 12px;
            border: none;
            border-radius: 25px;
            background: #fff;
            font-size: 1rem;
            color: #333;
            box-shadow: inset 0 5px 10px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        input:focus {
            outline: none;
            transform: scale(1.05);
            box-shadow: 0 0 10px rgba(255, 215, 0, 0.8);
        }

        /* Button Styling */
        button {
            background: linear-gradient(to right, #FFD700, #FF8C00);
            border: none;
            border-radius: 25px;
            padding: 12px;
            font-size: 1.2rem;
            font-weight: bold;
            color: #000;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(255, 215, 0, 0.6);
        }

        button:hover {
            background: linear-gradient(to right, #FF8C00, #FFD700);
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(255, 215, 0, 0.8);
        }

        /* Animated Alert */
        .alert {
            position: absolute;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            background-color: rgba(255, 0, 0, 0.9);
            color: #fff;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: bold;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.3);
            animation: slide-in 0.5s ease-out;
        }

        @keyframes slide-in {
            0% { top: -50px; opacity: 0; }
            100% { top: 10px; opacity: 1; }
        }
    </style>
</head>
<body>
    <!-- Login Form -->
    <div class="login-container">
        <h2>Admin Login</h2>

        <?php
        // Display an alert for invalid login
        if (isset($_GET['error'])) {
            echo "<div class='alert'>Invalid Username or Password!</div>";
        }
        ?>

        <form method="POST" action="">
            <label>Username:</label>
            <input type="text" name="username" required>

            <label>Password:</label>
            <input type="password" name="password" required>

            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>

