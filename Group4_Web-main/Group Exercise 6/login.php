<?php
session_start();

// Initialize a variable to store the error message
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the username and password from the form
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    // Server-side validation
    if (empty($username) || empty($password)) {
        $error_message = "Both fields are required.";
    } else {
        // Simple authentication (replace with database check)
        if ($username === $valid_username && $password === $valid_password) {
            // Set session variables
            $_SESSION['username'] = $username;

            // Set a cookie that lasts for 1 hour
            setcookie("username", $username, time() + 3600, "/");

            // Redirect to a welcome page (or dashboard)
            header("Location: Group exercise.php");
            exit();
        } else {
            $error_message = "Invalid Username or Password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="login.css"> <!-- Link to the CSS file -->
    <script>
        function validateForm() {
            const username = document.getElementById("username").value;
            const password = document.getElementById("password").value;

            // Basic validation
            if (username === "" || password === "") {
                alert("Both fields are required.");
                return false;
            }

            return true; // Form is valid
        }

        function showError(message) {
            alert(message);
            // Clear input fields
            document.getElementById("username").value = '';
            document.getElementById("password").value = '';
        }

        // Check if there's an error message and show it
        window.onload = function() {
            const errorMessage = "<?php echo addslashes($error_message); ?>";
            if (errorMessage) {
                showError(errorMessage);
            }
        };
    </script>
</head>
<body>
    <div class="container">
        <h2>Login Form</h2>
        <form method="POST" onsubmit="return validateForm();" action="register.php">
            <div class="input-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group">
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    <span class="toggle-password" onclick="togglePassword('password')">&#128065;</span> <!-- Eye icon -->
</div>
            <button type="submit" class="btn" name="login">Login</button>
        </form>
        <br>
        <div class="links">
            <a href="forgot_password.php">Forgot Password?</a> | 
            <a href="sign_up.php">Sign Up</a>
        </div>
    </div>
    <script>

function togglePassword(id) {
    const passwordField = document.getElementById(id);
    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordField.setAttribute('type', type);
}
    </script>
</body>
</html>