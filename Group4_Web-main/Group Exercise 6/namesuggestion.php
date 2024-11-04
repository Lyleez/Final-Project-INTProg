<?php
// Load members from JSON file
$membersFile = 'members.json';
$members = json_decode(file_get_contents($membersFile), true);

// Extract names and IDs into an associative array for easy access
$names = array_column($members, 'name');
$ids = array_column($members, 'id'); // Assuming each member has a unique 'id'

// Get the search query from request
$query = isset($_REQUEST["q"]) ? strtolower(trim($_REQUEST["q"])) : '';

// Initialize suggestions
$suggestions = [];

// Generate suggestions based on the query
if ($query !== "") {
    foreach ($names as $index => $name) {
        if (stripos($name, $query) === 0) { // Check if name starts with the query
            // Create a clickable link for each suggestion
            $suggestions[] = "<a href='profile.php?id={$ids[$index]}' style='text-decoration: none; color: inherit;'>{$name}</a>";

        }
    }
}

// Output suggestions or a message if none found
echo empty($suggestions) ? "no suggested name" : implode("<br>", $suggestions);
?>