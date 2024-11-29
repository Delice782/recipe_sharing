<?php

include 'db_connect.php';      // Include database connection
include 'session_check.php';    // Include session check

// Get user details
$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role']; // 1 for Super Admin, 2 for Regular Admin, etc.

// Initialize variables for dashboard data
$total_users = $total_recipes = $pending_approvals = null;
$recent_recipes = $all_users = null;

switch ($user_role) {
    case 1: // Super Admin
        // Super Admin specific queries with error checking
        $total_users_result = $conn->query("SELECT COUNT(*) AS total FROM users");
        if ($total_users_result) {
            $total_users = $total_users_result->fetch_assoc()['total'];
        } else {
            echo "Error fetching total users: " . $conn->error;
        }

        $total_recipes_result = $conn->query("SELECT COUNT(*) AS total FROM recipes");
        if ($total_recipes_result) {
            $total_recipes = $total_recipes_result->fetch_assoc()['total'];
        } else {
            echo "Error fetching total recipes: " . $conn->error;
        }

        $pending_approvals_result = $conn->query("SELECT COUNT(*) AS total FROM users WHERE approval_status='pending'");
        if ($pending_approvals_result) {
            $pending_approvals = $pending_approvals_result->fetch_assoc()['total'];
        } else {
            echo "Error fetching pending approvals: " . $conn->error;
        }

        $recent_recipes = $conn->query("SELECT recipe_id, food_id, ingredient_id, quantity, unit, created_at FROM recipes ORDER BY created_at DESC LIMIT 5");
        if (!$recent_recipes) {
            echo "Error fetching recent recipes: " . $conn->error;
        }

        $all_users = $conn->query("SELECT user_id, fname, lname, email, role, created_at, approval_status FROM users");
        if (!$all_users) {
            echo "Error fetching all users: " . $conn->error;
        }
        break;
    
    case 2: // Regular Admin
        $user_recipes_result = $conn->query("SELECT COUNT(*) AS total FROM recipes");
        if ($user_recipes_result) {
            $user_recipes = $user_recipes_result->fetch_assoc()['total'];
        } else {
            echo "Error fetching user recipes: " . $conn->error;
        }

        $recent_user_recipes = $conn->query("SELECT recipe_id, food_id, ingredient_id, quantity, unit, created_at FROM recipes ORDER BY created_at DESC LIMIT 5");
        if (!$recent_user_recipes) {
            echo "Error fetching recent user recipes: " . $conn->error;
        }
        break;
}
?>

<!DOCTYPE html>d
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
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
                    <li><a href="recipe_page.php">Recipe Management</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>
        <main class="main-content">
            <header>
                <h1>Welcome back, <?= htmlspecialchars($_SESSION['fname']) ?>!</h1>
                <div class="analytics">
                    <?php
                    switch ($user_role) {
                        case 1: // Super Admin
                            echo "
                                <div class='analytics-section small'>
                                    <h2>Total Users</h2>
                                    <p>{$total_users}</p>
                                </div>
                                <div class='analytics-section small'>
                                    <h2>Total Recipes</h2>
                                    <p>{$total_recipes}</p>
                                </div>
                                <div class='analytics-section small'>
                                    <h2>Pending Approvals</h2>
                                    <p>{$pending_approvals}</p>
                                </div>";
                            break;

                        case 2: // Regular Admin
                            echo "
                                <div class='analytics-section small'>
                                    <h2>Your Recipes</h2>
                                    <p>{$user_recipes}</p>
                                </div>";
                            break;
                    }
                    ?>
                </div>
            </header>

            <?php if ($user_role == 1) : ?>
                <section class="user-management">
                    <h2>User Management</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Registration Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($user = $all_users->fetch_assoc()) : ?>
                                <tr>
                                    <td><?= htmlspecialchars($user['fname'] . ' ' . $user['lname']) ?></td>
                                    <td><?= htmlspecialchars($user['email']) ?></td>
                                    <td><?= htmlspecialchars($user['role']) ?></td>
                                    <td><?= htmlspecialchars($user['created_at']) ?></td>
                                    <td>
                                        <?php if ($user['approval_status'] === 'pending') : ?>
                                            <form action="approve_user.php" method="POST" style="display:inline;">
                                                <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['user_id']) ?>">
                                                <button type="submit" class="approve-btn">Approve</button>
                                            </form>
                                        <?php endif; ?>
                                        <form action="delete_for_fetch_users.php" method="POST" style="display:inline;">
                                            <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['user_id']) ?>">
                                            <button type="submit" class="delete-btn" onclick="return confirm('Are you sure you want to delete this user?');">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </section>
            <?php endif; ?>

            <section class="recipe-overview">
                <h2><?= $user_role == 1 ? 'All Recipes Overview' : 'Your Recipe Overview' ?></h2>
                <ul>
                    <?php
                    $recipes = $user_role == 1 ? $recent_recipes : $recent_user_recipes;
                    while ($recipe = $recipes->fetch_assoc()) :
                    ?>
                        <li>
                            Recipe ID: <?= htmlspecialchars($recipe['recipe_id']) ?> -
                            Food ID: <?= htmlspecialchars($recipe['food_id']) ?> -
                            Ingredient ID: <?= htmlspecialchars($recipe['ingredient_id']) ?> -
                            Quantity: <?= htmlspecialchars($recipe['quantity']) ?> -
                            Unit: <?= htmlspecialchars($recipe['unit']) ?> -
                            Created on <?= htmlspecialchars($recipe['created_at']) ?>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </section>
        </main>
    </div>
</body>
</html>