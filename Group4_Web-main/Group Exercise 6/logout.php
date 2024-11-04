<?php
session_start();
session_destroy(); // Destroy the session

// Delete the cookie
if (isset($_COOKIE['username'])) {
    setcookie("username", "", time() - 3600, "/"); // Expire the cookie
}

header("Location: login.php"); // Redirect to login page
exit();
?>