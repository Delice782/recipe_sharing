<?php
// edit_user.php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login_recipe.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'] ?? '';
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $role = $_POST['role'] ?? '';
    
    // Split name into first name and last name
    $name_parts = explode(' ', $name, 2);
    $fname = $name_parts[0];
    $lname = isset($name_parts[1]) ? $name_parts[1] : '';

    if (empty($user_id) || empty($name) || empty($email) || empty($role)) {
        die('Missing required fields');
    }

    // Prepare the update statement
    $sql = "UPDATE users SET fname = ?, lname = ?, email = ?, role = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("sssii", $fname, $lname, $email, $role, $user_id);
        
        if ($stmt->execute()) {
            echo "User updated successfully";
        } else {
            echo "Error updating user: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
} else {
    echo "Invalid request method";
}

$conn->close();
?>