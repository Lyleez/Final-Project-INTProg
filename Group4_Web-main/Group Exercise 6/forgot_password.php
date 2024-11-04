<?php
session_start();
$error_message = "";
$success_message = "";

// Database connection
$host = 'localhost';
$db_name = 'inprog_db'; 
$user = 'root';  
$password = '';      

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Step 1: Check if the email is being submitted
    if(isset($_POST['email']) && !isset($_POST['new_password'])) {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

        if (empty($email)) {
            $error_message = "Email is required.";
        } else {
            // Check if the email exists
            $stmt = $pdo->prepare("SELECT email FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch();

            if (!$user) {
                $error_message = "Email not found.";
            } else {
                // Email exists, set session variable
                $_SESSION['reset_email'] = $email;
                $success_message = "Email verified. Please enter your new password.";
            }
        }
    } 
    // Step 2: Check if the new password is being submitted
    elseif(isset($_POST['new_password']) && isset($_SESSION['reset_email'])) {
        $new_password = $_POST['new_password'];
        $email = $_SESSION['reset_email'];
        
        // Hash the new password
        $hashed_password = md5($new_password);
        
        // Update the password in database
        $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE email = :email");
        $stmt->execute([
            'password' => $hashed_password,
            'email' => $email
        ]);
        
        $success_message = "Password has been reset successfully!";
        unset($_SESSION['reset_email']); // Clear the session variable
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .password-container {
            position: relative;
        }
        .password-container input[type="password"],
        .password-container input[type="text"] {
            width: 86%;
            padding-right: 30px;
        }
        .toggle-password {
            position: absolute;
            left: 270px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Reset Password</h2>
        <?php if (!isset($_SESSION['reset_email'])): ?>
            <!-- First form - Email verification -->
            <form method="POST">
                <div class="input-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <button type="submit" class="btn">Verify Email</button>
            </form>
        <?php else: ?>
            <!-- Second form - New password -->
            <form method="POST">
                <div class="input-group">
                    <label for="new_password">New Password:</label>
                    <div class="password-container">
                        <input type="password" id="new_password" name="new_password" required>
                        <i class="toggle-password fas fa-eye" onclick="togglePassword()"></i>
                    </div>
                </div>
                <button type="submit" class="btn">Reset Password</button>
            </form>
        <?php endif; ?>

        <?php if ($error_message): ?>
            <div class="error"><?php echo $error_message; ?></div>
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
        function togglePassword() {
            var passwordField = document.getElementById("new_password");
            var toggleIcon = document.querySelector(".toggle-password");
            
            if (passwordField.type === "password") {
                passwordField.type = "text";
                toggleIcon.classList.remove("fa-eye");
                toggleIcon.classList.add("fa-eye-slash");
            } else {
                passwordField.type = "password";
                toggleIcon.classList.remove("fa-eye-slash");
                toggleIcon.classList.add("fa-eye");
            }
        }
    </script>
</body>
</html>