<!-- error_reporting(E_ALL);
ini_set('display_errors', 1); -->

<?php
// db_connect.php
$servername = '169.239.251.102';  
$username = 'delice.ishimwe';
$password = 'Delice@123';
$dbname = 'webtech_fall2024_delice_ishimwe';
$port = 3341;

// Create a new database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
