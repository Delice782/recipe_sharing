<?php
include 'db_connect.php';
include 'session_check.php';

// Get user details
$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'];

// Pagination setup
$records_per_page = 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $records_per_page;

// Prepare query based on user role
if ($user_role == 1) {
    // Super Admin: Fetch all recipes with total count
    $total_recipes_query = "SELECT COUNT(*) AS total FROM recipes r JOIN foods f ON r.food_id = f.food_id";
    $recipes_query = "SELECT 
        r.recipe_id, 
        f.name AS food_name, 
        f.origin, 
        f.type,
        f.is_healthy, 
        r.created_at, 
        u.fname, 
        u.lname
    FROM recipes r
    JOIN foods f ON r.food_id = f.food_id
    JOIN users u ON f.created_by = u.user_id
    ORDER BY r.created_at DESC
    LIMIT ? OFFSET ?";
} else {
    // Regular Admin: Fetch recipes for current user
    $total_recipes_query = "SELECT COUNT(*) AS total FROM recipes r JOIN foods f ON r.food_id = f.food_id WHERE f.created_by = $user_id";
    $recipes_query = "SELECT 
        r.recipe_id, 
        f.name AS food_name, 
        f.origin,
        f.type, 
        f.is_healthy, 
        r.created_at
    FROM recipes r
    JOIN foods f ON r.food_id = f.food_id
    WHERE f.created_by = ?
    ORDER BY r.created_at DESC
    LIMIT ? OFFSET ?";
}

// Get total recipes
$total_result = $conn->query($total_recipes_query);
$total_recipes = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_recipes / $records_per_page);

// Prepare and execute the statement
$stmt = $conn->prepare($recipes_query);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

if ($user_role == 1) {
    $stmt->bind_param("ii", $records_per_page, $offset);
} else {
    $stmt->bind_param("iii", $user_id, $records_per_page, $offset);
}

// Execute the statement
if (!$stmt->execute()) {
    die("Execute failed: " . $stmt->error);
}

// Get the results
$recipes_result = $stmt->get_result();
if (!$recipes_result) {
    die("Get result failed: " . $stmt->error);
}
?>

<!-- Rest of the HTML remains the same -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Management</title>
    <link rel="stylesheet" href="recipe_page.css">
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="logo">Recipe Rendezvous</div>
            <nav>
                <ul>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <?php if ($user_role == 1) : ?>
                        <li><a href="fetch_users.php">User Management</a></li>
                    <?php endif; ?>
                    <li><a href="#">Recipe Management</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <header>
                <h1>Recipe Management</h1>
                <?php if ($user_role == 1) : ?>
                    <a href="submit_recipe.php" class="create-btn">Create New Recipe</a>
                <?php endif; ?>
            </header>

            <section class="recipe-management">
                <table>
                    <thead>
                        <tr>
                            <th>Recipe ID</th>
                            <th>Food Name</th>
                            <th>Origin</th>
                            <th>type</th>
                            <th>Health Status</th>
                            <th>Created At</th>
                            <?php if ($user_role == 1) : ?>
                                <th>Created By</th>
                            <?php endif; ?>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($recipe = $recipes_result->fetch_assoc()) : ?>
                            <tr>
                                <td><?= htmlspecialchars($recipe['recipe_id']) ?></td>
                                <td><?= htmlspecialchars($recipe['food_name']) ?></td>
                                <td><?= htmlspecialchars($recipe['origin']) ?></td>
                                <td><?= htmlspecialchars($recipe['type']) ?></td>
                                <td><?= $recipe['is_healthy'] ? 'Healthy' : 'Not Healthy' ?></td>
                                <td><?= htmlspecialchars($recipe['created_at']) ?></td>
                                <?php if ($user_role == 1) : ?>
                                    <td><?= htmlspecialchars($recipe['fname'] . ' ' . $recipe['lname']) ?></td>
                                <?php endif; ?>
                                <td>
                                    <a href="view_recipe.php?id=<?= htmlspecialchars($recipe['recipe_id']) ?>" class="view-btn">View</a>
                                    <a href="edit_recipe.php?id=<?= htmlspecialchars($recipe['recipe_id']) ?>" class="edit-btn">Edit</a>
                                    <form action="delete_recipe.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="recipe_id" value="<?= htmlspecialchars($recipe['recipe_id']) ?>">
                                        <button type="submit" onclick="return confirm('Are you sure you want to delete this recipe?');" class="delete-btn">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="pagination">
                    <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                        <a href="?page=<?= $i ?>" class="<?= ($page == $i) ? 'active' : '' ?>"><?= $i ?></a>
                    <?php endfor; ?>
                </div>
            </section>
        </main>
    </div>
</body>
</html>

<?php
// Close the statement and connection
$stmt->close();
$conn->close();
?>