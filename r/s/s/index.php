<?php
    require '../../../config.php';
    $current_url = $_SERVER['REQUEST_URI'];
    $parsed_url = parse_url($current_url);

    $user_folder = explode('/', $parsed_url['path'])[2];
    $repository = explode('/', $parsed_url['path'])[3];
    $file = explode('/', $parsed_url['path'])[5];

    // Insert a new record for each page view
    $sql = 'INSERT INTO views (ipaddress, user_folder, repository_name, view) VALUES (?, ?, ?, 1)';
    $stmt = mysqli_prepare($link, $sql);
    $ipaddress = $_SERVER['REMOTE_ADDR'];
    mysqli_stmt_bind_param($stmt, 'sss', $ipaddress, $user_folder, $repository);
    mysqli_stmt_execute($stmt); // Execute the query here

    // Check if index.html exists in the folder
    $index_file = 'index.html';
    if (file_exists($index_file)) {
        header('location: index.html');
    } else {
        echo 'Error: index.html file not found.';
    }
?>
