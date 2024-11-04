<?php
session_start();
$error_message = "";
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Dummy user data for demonstration (Replace this with a database query in a real application)
    $valid_email = "user@example.com"; // Example existing email
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    // Server-side validation
    if (empty($username) || empty($email) || empty($password)) {
        $error_message = "All fields are required.";
    } elseif ($email === $valid_email) {
        $error_message = "Email is already in use.";
    } else {
        // Here you would typically save the user information in a database
        // For example: saveUser ($username, $email, password_hash($password, PASSWORD_DEFAULT));

        $success_message = "Account created successfully! You can now log in.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="login.css"> <!-- Link to the CSS file -->
</head>
<body>
    <div class="container">
        <h2>Sign Up</h2>
        <form method="POST" action="register.php">
            <div class="input-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="input-group">
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    <span class="toggle-password" onclick="togglePassword('password')">&#128065;</span> <!-- Eye icon -->
</div>
            <button type="submit" class="btn" name="signUp">Sign Up</button>
        </form>
        <?php if ($error_message): ?>
            <div class=" error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <?php if ($success_message): ?>
            <div class="success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <br>
        <div class="links">
            <a href="login.php">Back to Login</a>
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