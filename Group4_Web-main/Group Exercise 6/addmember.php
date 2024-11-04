<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name'])) {
    $name = htmlspecialchars($_POST['name']);
    $namesuggestion_file = 'namesuggestion.php';
    $namesuggestion_array = array();

    // Load the existing names from namesuggestion.php
    if (file_exists($namesuggestion_file)) {
        $namesuggestion_array = include $namesuggestion_file;
    }

    // Add the new member's name to the array
    $namesuggestion_array[] = $name;

    // Save the updated array to namesuggestion.php
    file_put_contents($namesuggestion_file, '<?php $a = ' . var_export($namesuggestion_array, true) . '; ?>');
}
?>