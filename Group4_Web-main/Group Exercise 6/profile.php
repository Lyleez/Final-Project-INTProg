<?php
session_start(); // Start session to track user sessions

// Assuming you have a function to retrieve member data from the JSON file
function getMemberData($id) {
    $jsonData = file_get_contents('members.json');
    $members = json_decode($jsonData, true);
    foreach ($members as $member) {
        if ($member['id'] == $id) {
            return $member;
        }
    }
    return null;
}

// Get the member ID from the query parameter
$memberId = $_GET['id'];

// Retrieve the member data
$memberData = getMemberData($memberId);

// Check if a theme cookie exists, if not set a default theme
if (!isset($_COOKIE['theme'])) {
    setcookie('theme', 'light', time() + (86400 * 30), "/"); // 30 days expiration
}

// Update the theme based on the user's selection from the switch
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['theme'])) {
    $theme = htmlspecialchars($_POST['theme']);
    setcookie('theme', $theme, time() + (86400 * 30), "/"); // Save the theme in a cookie
    $_SESSION['theme'] = $theme; // Store the theme in the session
} else {
    $theme = $_COOKIE['theme']; // Get the theme from the cookie
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/423ec1df8a.js" crossorigin="anonymous"></script>
    <title><?php echo $memberData ? $memberData['name'] . "'s Profile" : "Profile Not Found"; ?></title>
    <link rel="stylesheet" href="Group exercise.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: <?php echo $theme == 'dark' ? '#333' : 'rgb(250, 246, 224)'; ?>;
            color: <?php echo $theme == 'dark' ? 'white' : 'black'; ?>;
            transition: background-color 0.3s, color 0.3s;
        }  
        .profile-container {
            max-width: 600px;
            margin: auto;
            background-color: <?php echo $theme == 'dark' ? 'rgb(250, 246, 224)' : '#333'; ?>; /* Reciprocal color */
            color: <?php echo $theme == 'dark' ? 'black' : 'white'; ?>;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .profile-header img {
            border-radius: 50%;
            width: 100px;
            height: 100px;
            margin-right: 20px;
        }
        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <header>
        <a href="Group exercise.php" style="text-decoration: none; color: inherit;">
            <h1>When someone is in need <br>Group 4 is indeed</h1>
        </a>
        <div class="icon">
            <i class='bx bx-search' id="search-btn"></i>
        </div>
        <div class="search-form">
            <input type="text" placeholder="Search your style" id="search-box" onkeyup="showHint(this.value)">
            <div class="suggestions">
                <p id="txtHint"></p>
            </div>
        </div>
    </header>

    <div class="container vh-100 d-flex justify-content-center align-items-center">
        <div class="one-quarter" id="switch">
            <form method="POST" action="">
                <input type="hidden" name="theme" value="<?php echo $theme == 'dark' ? 'light' : 'dark'; ?>" />
                <input type="checkbox" class="checkbox" id="chk" <?php echo $theme == 'dark' ? 'checked' : ''; ?> onclick="this.form.submit();" />
                <label class="label" for="chk">
                    <i class="fas fa-moon"></i>
                    <i class="fas fa-sun"></i>
                    <div class="ball"></div>
                </label>
            </form>
        </div>
    </div>
</div>

<div class="profile-container">
    <?php if ($memberData): ?>
        <div class="profile-header">
            <img src="<?php echo $memberData['image']; ?>" alt="<?php echo $memberData['name']; ?>">
            <div>
                <h2><?php echo $memberData['name']; ?></h2>
                <p>Age: <?php echo $memberData['age']; ?></p>
                <p>Hobby: <?php echo $memberData['hobby']; ?></p>
            </div>
        </div>
        <div class="profile-info">
            <h3>Description</h3>
            <p><?php echo $memberData['description']; ?></p>
        </div>
    <?php else: ?>
        <h1 class="error">Error</h1>
        <p class="error">Member not found.</p>
    <?php endif; ?>
</div>

<script src="Group exercise.js"></script>
</body>
</html>
