<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Split name into first and last name
    $nameParts = explode(' ', $name);
    $fname = $nameParts[0];
    $lname = isset($nameParts[1]) ? $nameParts[1] : '';

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL statement
    $sql = "INSERT INTO users (fname, lname, email, password, role, created_at) 
            VALUES (?, ?, ?, ?, ?, NOW())";
    
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $fname, $lname, $email, $hashedPassword, $role);
        
        if ($stmt->execute()) {
            echo "User added successfully";
        } else {
            echo "Error adding user: " . $stmt->error;
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
    
    $stmt->close();
    $conn->close();
}
?>