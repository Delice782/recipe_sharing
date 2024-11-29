<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login_recipe.php');
    exit();
}

// Check if user has the right permissions (Super Admin)
if ($_SESSION['role'] != 1) {
    die("Unauthorized access.");
}

// Check if a user_id is provided via POST
if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "recipe_sharing";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the delete query
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        // Send success response
        echo "User deleted successfully!";
    } else {
        // Send error message
        echo "Error deleting user: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
} else {
    // If no user_id is set, send error message
    echo "No user_id provided.";
}
?>
