<?php
// // Start the session
session_start();

include 'db_connect.php';      // Include database connection

$errorMsg = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate input
    if (empty($email) || empty($password)) {
        $errorMsg = "Email and password are required.";
    } else {
        // Prepare and execute SQL statement
        $stmt = $conn->prepare("SELECT user_id, fname, lname, role, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if user exists
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Password is correct, set session variables
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['fname'] = $user['fname'];
                $_SESSION['lname'] = $user['lname'];
                $_SESSION['role'] = $user['role'];
                header("Location: index.php"); // Redirect to dashboard
                exit();
            } else {
                $errorMsg = "Invalid email or password.";
            }
        } else {
            $errorMsg = "Invalid email or password.";
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
    <title>Recipe Rendezvous - Login</title>
    <link rel="stylesheet" href="login_recipe.css">
    <style>
        .error {
        color: red;
        font-size: 0.875em;
        display: <?php echo !empty($errorMsg) ? 'block' : 'none'; ?>;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="index.php">Home</a>
        <a href="#">About Us</a>
        <a href="#">Help</a>
    </div>
    <main>
        <section class="form-box">
            <h1>Login</h1>
            <h4>Access your account</h4>
            <form id="login-form" action="" method="post" onsubmit="return validateForm(event)" novalidate>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
                <span class="error"><?php echo $errorMsg; ?></span>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter your password">
                <span class="error" id="password-error"></span>

                <button type="submit">Login</button>

                <div class="auth-switch">
                    Don't have an account? <a href="register_recipe.php">Sign Up</a>
                </div>
            </form>
        </section>
    </main>

    <script>
        function validateForm(event) {
            event.preventDefault(); 

            let isValid = true;

            // Clear all previous error messages
            document.querySelectorAll('.error').forEach(error => {
                error.style.display = 'none';
            });

            // Get values
            const email = document.getElementById("email").value;
            const password = document.getElementById("password").value;

            // Email validation
            const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (!email.match(emailPattern)) {
                document.getElementById("email-error").textContent = "Please enter a valid email address.";
                document.getElementById("email-error").style.display = "block";
                isValid = false;
            }

            // Password validation (second regex)
            const passwordPattern = /^(?=.*[A-Z])(?=(.*\d){3,})(?=.*[\W_])[A-Za-z\d\W_]{8,}$/
            if (!password.match(passwordPattern)) {
                document.getElementById("password-error").textContent = "Password must contain at least one uppercase letter, three digits, and one special character.";
                document.getElementById("password-error").style.display = "block";
                isValid = false;
            }

            // If validation is successful, submit the form
            if (isValid) {
                document.getElementById("login-form").submit();
            }

            return false; 
        }

        // Function to hide the error when user starts typing in an input field
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('input', function() {
                const errorId = this.id + "-error";
                const errorElement = document.getElementById(errorId);
                if (errorElement) {
                    errorElement.style.display = "none"; // Hide error message when typing
                }
            });
        });
    </script>
</body>
</html>
