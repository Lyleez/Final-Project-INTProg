<?php
/*
 * ===============================================================
 *  Project Name: Group Member Management
 *  File Name: Group exercise.php
 *  Author: [Roderick John B. Bandejes]
 *  Date: [10/03/2024]
 *  Description: This PHP file handles member management, including 
 *               adding new members, deleting existing members, and 
 *               displaying member details dynamically using JSON files 
 *               and HTML cards.
 * ===============================================================
 */
session_start(); // Start session to track user sessions

header("Content-Type: text/html; charset=UTF-8"); 

 include("connect.php");

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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/423ec1df8a.js" crossorigin="anonymous"></script>
    <title>Personal Web</title>
    <link rel="stylesheet" href="Group exercise.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <style>
        /* Add your CSS styles here based on the selected theme */
        body {
            background-color: <?php echo $theme == 'dark' ? '#333' : 'rgb(250, 246, 224)'; ?>;
            color: <?php echo $theme == 'dark' ? '#fff' : '#000'; ?>;
        }
        .team-member-front {
            background: <?php echo $theme == 'dark' ? 'linear-gradient(#292626, #979595)' : 'linear-gradient(#000000, #292626)'; ?>;
            color: <?php echo $theme == 'dark' ? '#000' : '#fff'; ?>;
        }
        
        .logout {
            position: fixed; /* Position it in the upper left corner */
            top: 15px; 
            left: 20px; /* Adjust as needed */
            margin-left: 20px;

        }

        .logout button {
            font-size: 25px; /* Adjust font size */
            color: <?php echo $theme == 'dark' ? '#fff' : '#000'; ?>; /* Match text color with theme */
            background-color: transparent; /* No background */
            border: none; /* No border */
            cursor: pointer; /* Pointer cursor */
            transition: color 0.3s; /* Smooth color transition */
}

        .logout button:hover {
             color: <?php echo $theme == 'dark' ? '#ccc' : '#333'; ?>; /* Change color on hover */
}
    </style>
</head>

<body>
    <div class="container">
        <header>
        <div class="logout">
        <form method="POST" action="logout.php"> 
            <button type="submit" style="background: none; border: none; color: inherit; cursor: pointer;">
            <i class="fa fa-sign-out" aria-hidden="true"></i> Logout
            </button>
        </form>
    </div>
            <h1>When someone is in need <br>Group 4 is indeed</h1>
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
    
    <div class="container-team">
        <?php
            $members_file = 'members.json';

            if (!file_exists($members_file)) {
                file_put_contents($members_file, json_encode([]));
            }

            $members = json_decode(file_get_contents($members_file), true);

            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name'])) {
                $name = htmlspecialchars($_POST['name']);
                $age = htmlspecialchars($_POST['age']);
                $hobby = htmlspecialchars($_POST['hobby']);
                $description = htmlspecialchars($_POST['description']);

                if (isset($_FILES['picture']) && $_FILES['picture']['error'] == 0) {
                    $target_dir = "uploads/";
                    $target_file = $target_dir . basename($_FILES["picture"]["name"]);
                    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                    $check = getimagesize($_FILES["picture"]["tmp_name"]);
                    if ($check !== false) {
                        if (move_uploaded_file($_FILES["picture"]["tmp_name"], $target_file)) {
                            $image_url = $target_file;
                        } else {
                            echo "Error uploading the file.";
                            $image_url = null;
                        }
                    } else {
                        echo "File is not an image.";
                        $image_url = null;
                    }
                }

                $new_member = [
                    'id' => uniqid(),
                    'name' => $name,
                    'age' => $age,
                    'hobby' => $hobby,
                    'description' => $description,
                    'image' => $image_url
                ];
                $members[] = $new_member;

                file_put_contents($members_file, json_encode($members));
            }

            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id'])) {
                $delete_id = htmlspecialchars($_POST['delete_id']);

                $members = array_filter($members, function ($member) use ($delete_id) {
                    return $member['id'] !== $delete_id;
                });

                $members = array_values($members);
                file_put_contents($members_file, json_encode($members));
            }

            $members = json_decode(file_get_contents($members_file), true);
            $container_count = 0;
            $card_count = 0;
            
            foreach ($members as $member) {
                if ($card_count % 3 == 0) {
                    if ($card_count > 0) {
                        echo "</div>";
                    }
                    $container_count++;
                    echo "<div class='grid-container' id='container-$container_count'>";
                }

                echo "
                    <div class='card'>
                        <div class='team-member-front'>
                            " . ($member['image'] ? "<img src='{$member['image']}'
                            alt='{$member['name']}' style='width:100px;height:100px;'>" : "") . "
                            <h2>{$member['name']}</h2>
                            <p><b>Age:</b> {$member['age']} years old</p>
                            <p><b>Hobby:</b> {$member['hobby']}</p>
                        </div>
                        <div class='team-member-back'>
                            <h2>{$member['name']}</h2>
                            <p>{$member['description']}</p>
                            <form method='POST' action=''>
                                <input type='hidden' name='delete_id' value='{$member['id']}'>
                                <button type='submit'>Delete</button>
                            </form>
                        </div>
                    </div>";
                
                $card_count++;
            }

            if ($card_count > 0) {
                echo "</div>";
            }
        ?>
    </div>
    
    <div class="add-member">
        <h2>Add a New Member</h2>
        <form id="add-member-form" action="Group exercise.php" method="POST" enctype="multipart/form-data">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required><br><br>
            
            <label for="age">Age:</label>
            <input type="number" id="age" name="age" required><br><br>

            <label for="hobby">Hobby:</label>
            <input type="text" id="hobby" name="hobby" required><br><br>

            <label for="description">Description:</label>
            <input type="text" id="description" name="description" required><br><br>
            
            <label for="picture">Profile Picture:</label>
            <input type="file" name="picture" id="picture" accept="image/*" required>

            <img id="imagePreview" src="#" alt="Image Preview" style="display: none; width: 150px; height: 150px;" />
            
            <button type="submit" id="addbtn">Add Member</button>
        </form>
    </div>

    <script src="Group exercise.js">
        $(document).ready(function() {
            $('#add-member-form').submit(function(event) {
                event.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: 'addmember.php',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        console.log(data);
                    }
                });
            });
        });
    </script>
    
    <footer>
        <p>&copy; 2024 Group 4 Web Project. All rights reserved.</p>
        <p>When someone is in need, Group 4 is indeed!</p>
    </footer>
</body>
</html>
