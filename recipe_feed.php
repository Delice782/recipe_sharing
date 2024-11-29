<?php
include 'session_check.php';    // Include session check
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Rendezvous - Recipe Feed</title>
    <link rel="stylesheet" href="recipe_feed.css">
</head>
<body>
    <header>
        <h1>Recipe Feed</h1>
        <div class="search-box">
            <input type="text" placeholder="Search recipes by name or keyword...">
        </div>
    </header>
    <div class="content-box">
        <div class="recipe-grid">
            <div class="recipe-panels">
                <img src="https://images.pexels.com/photos/6412475/pexels-photo-6412475.jpeg?auto=compress&cs=tinysrgb&w=600" alt="Spaghetti Carbonara Image">
                <h3>Spaghetti Carbonara</h3>
                <p>Italian pasta dish made with eggs, cheese, pancetta, and pepper.</p>
                <div class="recipe-rating">★★★★☆</div>
                <a href="#" class="action-btn">View Recipe</a>
            </div>

            <div class="recipe-panels">
                <img src="https://images.pexels.com/photos/13795311/pexels-photo-13795311.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Chicken Curry Image">
                <h3>Chicken Curry</h3>
                <p>A flavor curry made with delicious chicken pieces and a blend of spices.</p>
                <div class="recipe-rating">★★★★★</div>
                <a href="#" class="action-btn">View Recipe</a>
            </div>

            <div class="recipe-panels">
                <img src="https://images.pexels.com/photos/3081657/pexels-photo-3081657.jpeg?auto=compress&cs=tinysrgb&w=600" alt="Chocolate Cake Image">
                <h3>Chocolate Cake</h3>
                <p>A rich and moist chocolate cake perfect for any occasion.</p>
                <div class="recipe-rating">★★★★☆</div>
                <a href="#" class="action-btn">View Recipe</a>
            </div>

            <div class="recipe-panels">
                <img src="https://images.pexels.com/photos/1893556/pexels-photo-1893556.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="French Fries Image">
                <h3>French Fries</h3>
                <p>Crispy, golden sticks of deep-fried potatoes with salt and often served as a side dish or snack.</p>
                <div class="recipe-rating">★★★★☆</div>
                <a href="#" class="action-btn">View Recipe</a>
            </div>

            <div class="recipe-panels">
                <img src="https://images.pexels.com/photos/7625056/pexels-photo-7625056.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Capati Image">
                <h3>Capati</h3>
                <p>A soft, flatbread made from wheat flour, cooked on a hot griddle, perfect for pairing with savory dishes.</p>
                <div class="recipe-rating">★★★★☆</div>
                <a href="#" class="action-btn">View Recipe</a>
            </div>

            <div class="recipe-panels">
                <img src="https://images.pexels.com/photos/10780004/pexels-photo-10780004.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Greek Salad Image">
                <h3>Greek Salad</h3>
                <p>A crisp salad with cucumbers, tomatoes, onions, olives, and feta, drizzled with olive oil and vinegar.</p>
                <div class="recipe-rating">★★★★☆</div>
                <a href="#" class="action-btn">View Recipe</a>
            </div>
        </div>
    </div>
    
    <footer>
        <p>&copy; 2024 Recipe Rendezvous</p>
        <p>
            <a href="#">Contact</a> | 
            <a href="#">Privacy Policy</a>
        </p>
    </footer>

</body>
</html>
