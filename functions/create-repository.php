<?php
if(!isset($_COOKIE['userid'])){
    header('location: ../');
}

require '../config.php';

$file_id = rand(00000, 99999);
$repository_id = $_COOKIE['userid'];
$repository_name = mysqli_real_escape_string($link, $_POST['repository']);
$description = mysqli_real_escape_string($link, $_POST['description']);

$sql = "INSERT INTO repository (file_id, repository_id, repository_name, description) VALUES (?, ?, ?, ?)";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "ssss", $file_id, $repository_id, $repository_name, $description);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

$select = "SELECT firstname, lastname FROM users WHERE userid = ?";
$s_stmt = mysqli_prepare($link, $select);
mysqli_stmt_bind_param($s_stmt, "s", $repository_id);
mysqli_stmt_execute($s_stmt);
$res = mysqli_stmt_get_result($s_stmt);
$name = "";

if (mysqli_num_rows($res) > 0) {
    while ($result = mysqli_fetch_assoc($res)) {
        $name = strtolower(str_replace(' ', '', $result['firstname'].$result['lastname']));
    }
}

$user_folder = "../r/$name/";

// Create user folder if it doesn't exist
if (!file_exists($user_folder)) {
    mkdir($user_folder, 0777, true);
}

foreach ($_FILES['files']['name'] as $key => $file_n) {
    $file_id = rand(00000, 99999);
	$sql = "INSERT INTO files (file_id, repository_id, file_name, repository_name) VALUES (?, ?, ?, ?)";
	$stmt = mysqli_prepare($link, $sql);
	mysqli_stmt_bind_param($stmt, "ssss", $file_id, $repository_id, $file_n, $repository_name);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
}

if (isset($_FILES['files'])) {
    foreach ($_FILES['files']['tmp_name'] as $key => $tmp_name) {
        $file_name = $_FILES['files']['name'][$key];
        $file_size = $_FILES['files']['size'][$key];
        $file_type = $_FILES['files']['type'][$key];
        $file_error = $_FILES['files']['error'][$key];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = array('html', 'htm', 'css', 'js', 'ico', 'jpg', 'jpeg', 'png', 'gif', 'mp4', 'ttf', 'otf', 'woff', 'woff2', 'eot', 'svg', 'directory');
        $max_size = 200 * 1024 * 1024; // 200 MB

        // Check if file type is allowed and size is within limit
        if (in_array($file_ext, $allowed_ext) && $file_size <= $max_size) {
            // Check if file is a directory
            if (is_dir($tmp_name)) {
                // Save all files in directory
                $dir_iterator = new RecursiveDirectoryIterator($tmp_name);
                $iterator = new RecursiveIteratorIterator($dir_iterator, RecursiveIteratorIterator::SELF_FIRST);
                foreach ($iterator as $file) {
                    if ($file->isDir()) {
                        continue;
                    }
                    $file_path = $user_folder . $repository_name . '/' . $file->getFilename();
                    if (!file_exists(dirname($file_path))) {
                        mkdir(dirname($file_path), 0777, true);
                    }
                    move_uploaded_file($file->getPathname(), $file_path);
                }
            } else {
                // Save single file
                $file_path = $user_folder . $repository_name . '/' . $file_name;
                if (!file_exists(dirname($file_path))) {
                    mkdir(dirname($file_path), 0777, true);
                }
                move_uploaded_file($tmp_name, $file_path);
            }
        } else {
            // File type or size is not allowed
            echo "<script>alert('Certain files are prohibited from being uploaded.')</script>";
            exit();
        }
    }
}

$track_file = $user_folder . $repository_name . '/index.php';
if (!file_exists(dirname($track_file))) {
    mkdir(dirname($track_file), 0777, true);
}
file_put_contents($track_file, "<?php
    require '../../../config.php';
    \$current_url = \$_SERVER['REQUEST_URI'];
    \$parsed_url = parse_url(\$current_url);

    \$user_folder = explode('/', \$parsed_url['path'])[2];
    \$repository = explode('/', \$parsed_url['path'])[3];
    \$file = explode('/', \$parsed_url['path'])[5];

    // Insert a new record for each page view
    \$sql = 'INSERT INTO views (ipaddress, user_folder, repository_name, view) VALUES (?, ?, ?, 1)';
    \$stmt = mysqli_prepare(\$link, \$sql);
    \$ipaddress = \$_SERVER['REMOTE_ADDR'];
    mysqli_stmt_bind_param(\$stmt, 'sss', \$ipaddress, \$user_folder, \$repository);
    mysqli_stmt_execute(\$stmt); // Execute the query here

    // Check if index.html exists in the folder
    \$index_file = 'index.html';
    if (file_exists(\$index_file)) {
        header('location: index.html');
    } else {
        echo 'Error: index.html file not found.';
    }
?>
");

// Redirect to user's repository page
header("location: ../account");
exit();

?>