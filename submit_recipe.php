<?php
include 'db_connect.php';
include 'session_check.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login_recipe.php");
    exit(); // Ensure no further code is executed
}

//check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST['name']));
    $origin = htmlspecialchars(trim($_POST['origin']));
    $type = htmlspecialchars(trim($_POST['type']));
    $is_healthy = isset($_POST['is_healthy']) ? 1 : 0;
    $instructions = htmlspecialchars(trim($_POST['instructions']));
    $description = htmlspecialchars(trim($_POST['description']));
    $preparation_time = intval($_POST['preparation_time']);
    $cooking_time = intval($_POST['cooking_time']);
    $serving_size = intval($_POST['serving_size']);
    $calories_per_serving = intval($_POST['calories_per_serving']);
    $image_url = filter_var($_POST['image_url'], FILTER_SANITIZE_URL);
    $created_by = $_SESSION['user_id'];

    if (filter_var($image_url, FILTER_VALIDATE_URL)) {
        $stmt = $conn->prepare("INSERT INTO foods (name, origin, type, is_healthy, instructions, description, preparation_time, cooking_time, serving_size, calories_per_serving, image_url, created_by) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssiiiiss", $name, $origin, $type, $is_healthy, $instructions, $description, $preparation_time, $cooking_time, $serving_size, $calories_per_serving, $image_url, $created_by);

        if ($stmt->execute()) {
            echo "<p style='color: green;'>New recipe submitted successfully!</p>";
        } else {
            echo "<p style='color: red;'>Error: " . $stmt->error . "</p>";
        }
        $stmt->close();
    } else {
        echo "<p style='color: red;'>Invalid image URL.</p>";
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Recipe</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        body {
            background-color: #f6f9fc;
            color: #333;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        
        /* Form container */
        .form-container {
            background-color: #ffffff;
            padding: 2em;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        
        /* Header and Title */
        h2 {
            text-align: center;
            margin-bottom: 1.5em;
            color: #6a4fef;
            font-weight: 600;
        }
        
        fieldset {
            border: none;
            margin-bottom: 2em;
        }
        
        legend {
            font-size: 1.2em;
            font-weight: bold;
            color: #6a4fef;
            margin-bottom: 0.5em;
        }
        
        /* Label and Input */
        label {
            display: block;
            margin: 0.5em 0 0.2em;
            font-weight: bold;
            color: #555;
        }
        
        input[type="text"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: 0.7em;
            margin-bottom: 1em;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1em;
            transition: border 0.3s;
        }
        
        input:focus,
        textarea:focus,
        select:focus {
            border: 1px solid #6a4fef;
            outline: none;
        }
        
        /* Checkbox */
        .checkbox-container {
            display: flex;
            align-items: center;
            margin-bottom: 1em;
        }
        
        .checkbox-container label {
            margin-left: 0.5em;
            font-weight: normal;
        }
        
        /* Submit Button */
        .submit-btn {
            width: 100%;
            padding: 0.7em;
            background-color: #6a4fef;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 1em;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .submit-btn:hover {
            background-color: #5c42d4;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Submit Your Recipe</h2>
        <form action="submit_recipe.php" method="POST">
            
            <fieldset>
                <legend>Food Details</legend>
                <label for="name">Food Name:</label>
                <input type="text" id="name" name="name" placeholder="Enter the food name" required>

                <label for="origin">Origin:</label>
                <input type="text" id="origin" name="origin" placeholder="E.g., Italian, African">

                <label for="type">Type:</label>
                <select id="type" name="type" required>
                    <option value="breakfast">Breakfast</option>
                    <option value="lunch">Lunch</option>
                    <option value="dinner">Dinner</option>
                    <option value="snack">Snack</option>
                    <option value="dessert">Dessert</option>
                </select>

                <div class="checkbox-container">
                    <input type="checkbox" id="is_healthy" name="is_healthy">
                    <label for="is_healthy">Mark as Healthy</label>
                </div>
            </fieldset>
            
            <fieldset>
                <legend>Recipe Details</legend>
                <label for="instructions">Instructions:</label>
                <textarea id="instructions" name="instructions" rows="4" placeholder="Step-by-step instructions" required></textarea>

                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4" placeholder="Brief description of the dish" required></textarea>
            </fieldset>
            
            <fieldset>
                <legend>Cooking Details</legend>
                <label for="preparation_time">Preparation Time (mins):</label>
                <input type="number" id="preparation_time" name="preparation_time" min="1" required>

                <label for="cooking_time">Cooking Time (mins):</label>
                <input type="number" id="cooking_time" name="cooking_time" min="1" required>

                <label for="serving_size">Serving Size:</label>
                <input type="number" id="serving_size" name="serving_size" min="1" required>

                <label for="calories_per_serving">Calories per Serving:</label>
                <input type="number" id="calories_per_serving" name="calories_per_serving" min="0" required>
            </fieldset>

            <fieldset>
                <legend>Image</legend>
                <label for="image_url">Image URL:</label>
                <input type="text" id="image_url" name="image_url" placeholder="Paste image link here" required>
            </fieldset>

            <button type="submit" class="submit-btn">Submit Recipe</button>
        </form>
    </div>
</body>
</html>



