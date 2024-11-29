<?php
// session_check.php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login_recipe.php');
    exit();
}
