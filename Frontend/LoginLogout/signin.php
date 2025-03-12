<?php
// Database connection
$host = 'localhost';
$dbname = 'login_db';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Retrieve user from database
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        echo "Welcome, " . $user['name'] . "!";
        // Optionally, start a session or create a login token here
        session_start();
        $_SESSION['user_id'] = $user['id'];
        header("Location: http://localhost/ecommercephp/Frontend"); // Redirect after login
    } else {
        echo "Invalid email or password.";
    }
}
?>
