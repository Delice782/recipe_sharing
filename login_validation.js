function validateForm(event) {
    event.preventDefault(); 

    let isValid = true;

    // Clear all previous error messages
    document.querySelectorAll('.error').forEach(error =>
error.style.display = 'none');

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
    if (isValid) document.getElementById("login-form").submit();
    
}

// Function to hide the error when user starts typing in an input field
document.querySelectorAll('input').forEach(input => {
    input.addEventListener('input', function() {
        const errorId = this.id + "-error";
        const errorElement = document.getElementById(errorId);
        if (errorElement) errorElement.style.display = "none"; // Hide error message when typing
    });
});


