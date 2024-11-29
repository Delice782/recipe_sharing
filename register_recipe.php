<?php
// Start the session to handle messages
session_start();

// Set the time zone to Ghana
date_default_timezone_set('Africa/Accra');

include 'db_connect.php';      // Include database connection

// Initialize variables for errors and success messages
$errors = [];
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize inputs
    $fullName = trim($_POST['full-name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];
    $createdAt = $updatedAt = date('Y-m-d H:i:s');
    $role = 2; // Set initial user role as 2 (regular admin)

    // Split full name into first and last names
    $nameParts = explode(' ', $fullName, 2);
    $fname = $nameParts[0];
    $lname = isset($nameParts[1]) ? $nameParts[1] : '';

    // Server-side validation
    if (empty($fname) || empty($lname)) {
        $errors['full-name'] = "Full name with both first and last name is required.";
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Please enter a valid email address.";
    } else {
        // Check for duplicate email
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
        if ($stmt === false) {
            // Output the SQL error
            die("Error preparing statement: " . $conn->error);
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errors['email'] = "Email already exists.";
        }
        $stmt->close();
    }

    if (empty($password)) {
        $errors['password'] = "Password is required.";
    } elseif (!preg_match('/^(?=.*[A-Z])(?=(.*\d){3,})(?=.*[\W_])[A-Za-z\d\W_]{8,}$/', $password)) {
        $errors['password'] = "Password must be at least 8 characters long, include one uppercase letter, three digits, and one special character.";
    }

    if ($password !== $confirmPassword) {
        $errors['confirm-password'] = "Passwords do not match.";
    }

    // If no errors, proceed with registration
    if (empty($errors)) {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Prepare and execute the insert statement
        $stmt = $conn->prepare("INSERT INTO users (fname, lname, email, password, role, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if ($stmt === false) {
            // Display the SQL error
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }
        $stmt->bind_param("sssssss", $fname, $lname, $email, $hashedPassword, $role, $createdAt, $updatedAt);
        
        if ($stmt->execute()) {
            $success_message = "Registration successful! You can now log in.";
        } else {
            $errors['general'] = "Error occurred while registering. Please try again.";
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Rendezvous - Sign Up</title>
    <link rel="stylesheet" href="register_recipe.css">
</head>
<body>
    <div class="navbar">
        <a href="landing_recipe.html">Home</a>
        <a href="#">About Us</a>
        <a href="#">Help</a>
    </div>
    <main>
        <section class="form-box">
            <h1>Sign Up</h1>
            <h4>Create an account</h4>

            <?php if (!empty($success_message)) : ?>
                <p style="color: green;"><?php echo $success_message; ?></p>
            <?php endif; ?>

            <?php if (!empty($errors)) : ?>
                <p style="color: red;"><?php echo implode('<br>', $errors); ?></p>
            <?php endif; ?>

            <form id="sign-up" action="" method="post" novalidate>
                <label for="full-name">Full Name:</label>
                <input type="text" id="full-name" name="full-name" placeholder="Enter your full name" value="<?php echo htmlspecialchars($fullName ?? ''); ?>">
                <span class="error" id="full-name-error"></span>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" value="<?php echo htmlspecialchars($email ?? ''); ?>">
                <span class="error" id="email-error"></span>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Create a password">
                <span class="error" id="password-error"></span>

                <label for="confirm-password">Confirm Password:</label>
                <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm your password">
                <span class="error" id="confirm-password-error"></span>

                <button type="submit" onclick="return validateForm()">Register</button>

                <div class="auth-switch">
                    Already have an account? <a href="login_recipe.php">Log In</a>
                </div>
            </form>
        </section>
    </main>

    <script>
        function validateForm() {
            const fullName = document.getElementById('full-name').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm-password').value;
            let valid = true;

            if (fullName.split(" ").length < 2) {
                document.getElementById('full-name-error').innerText = "Please enter both first and last names.";
                document.getElementById('full-name-error').style.display = "block";
                valid = false;
            } else {
                document.getElementById('full-name-error').style.display = "none";
            }

            if (!/^\S+@\S+\.\S+$/.test(email)) {
                document.getElementById('email-error').innerText = "Please enter a valid email address.";
                document.getElementById('email-error').style.display = "block";
                valid = false;
            } else {
                document.getElementById('email-error').style.display = "none";
            }

            const passwordRegex = /^(?=.*[A-Z])(?=(.*\d){3,})(?=.*[\W_])[A-Za-z\d\W_]{8,}$/;
            if (!passwordRegex.test(password)) {
                document.getElementById('password-error').innerText = "Password must be at least 8 characters long, include one uppercase letter, three digits, and one special character.";
                document.getElementById('password-error').style.display = "block";
                valid = false;
            } else {
                document.getElementById('password-error').style.display = "none";
            }

            if (password !== confirmPassword) {
                document.getElementById('confirm-password-error').innerText = "Passwords do not match.";
                document.getElementById('confirm-password-error').style.display = "block";
                valid = false;
            } else {
                document.getElementById('confirm-password-error').style.display = "none";
            }

            return valid;
        }
    </script>
</body>
</html>