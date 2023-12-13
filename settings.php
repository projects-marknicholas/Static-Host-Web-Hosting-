<?php
if(!isset($_COOKIE['userid'])){
    header('location: ./');
}
require 'config.php';

$sql = "SELECT firstname, lastname, userid, username, date_created FROM users WHERE userid = '".$_COOKIE['userid']."'";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($res) > 0) {
    while ($result = mysqli_fetch_assoc($res)) {
        if (isset($_POST['lastname']) && isset($_POST['lastname'])) {
            if (isset($_POST['lastname']) && isset($_POST['lastname'])) {
                $ustmt = mysqli_prepare($link, "UPDATE users SET firstname=?, lastname=? WHERE userid=?");
                mysqli_stmt_bind_param($ustmt, "sss", $_POST['firstname'], $_POST['lastname'], $_COOKIE['userid']);
                mysqli_stmt_execute($ustmt);
                mysqli_stmt_close($ustmt);

                $name = "";
                $named = "";
                $name = strtolower(str_replace(' ', '', $_POST['firstname'].$_POST['lastname']));
                $named = strtolower(str_replace(' ', '', $result['firstname'].$result['lastname']));

                // Rename the folder
                $oldfoldername = "r/".$named;
                $newfoldername = "r/".$name;
                if (!rename($oldfoldername, $newfoldername)) {
                    die("Failed to rename folder");
                }

                // Loop through the files in the folder
                $handle = opendir($newfoldername);
                if (!$handle) {
                    die("Failed to open folder");
                }

                while (false !== ($file = readdir($handle))) {
                    if ($file != "." && $file != "..") {
                        // Check if the file needs to be updated
                        $filename = $newfoldername . "/" . $file;
                        $data = file_get_contents($filename);
                        $names = explode(" ", $data);
                        if ($names[1] == $_COOKIE['userid']) {
                            // Update the file with the new data
                            $newdata = $_POST['firstname'] . " " . $_POST['lastname'];
                            file_put_contents($filename, $newdata);
                        }
                    }
                }
                closedir($handle);
                header('location: account');
            }
        }
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1.0" name="viewport">
	<meta content="assets/svg/logo.svg" name="og:image">
    <meta name="title" content="Settings - Static Host">
    <meta name="description" content="Great Websites Start with Free Unlimited Hosting">
    <meta name="keywords" content="Static Host, Great Websites Start with Free Unlimited Hosting">
    <meta name="robots" content="index, follow">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="language" content="English">
    <meta name="author" content="Settings - Static Host">
    <meta property="twitter:url" content="https://statichost.rf.gd/">
    <meta property="twitter:title" content="Settings - Static Host">
    <meta property="twitter:description" content="Great Websites Start with Free Unlimited Hosting">
	<link rel="icon" href="assets/svg/logo.svg" type="image/gif" sizes="16x16">
	<link rel="icon" href="assets/svg/logo.svg" type="image/gif" sizes="32x32">
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	<title>Settings - Static Host</title>
</head>
<body>

<?php include 'navbar-log.php'?>

<div class="analytics">
    <div class="analytics-nav">
        <a href="account">Back</a>
        <h1>Settings</h1>
    </div>
</div>

<form method="post" action="" class="package-integrate">
    <h2>Username</h2>
    <div class="form-endpoint">
        <div>
            <input type="text" readonly="" id="" value="<?php echo $result['username']?>">
        </div>
        <button class="disabled">Save</button>
    </div>
    <p>This will be the target email.</p>

    <h2>First Name</h2>
    <div class="form-endpoint">
        <div>
            <input type="text" id="" name="firstname" value="<?php echo $result['firstname']?>" required="">
        </div>
    </div>
    <h2>Last Name</h2>
    <div class="form-endpoint">
        <div>
            <input type="text" id="" name="lastname" value="<?php echo $result['lastname']?>">
        </div>
        <button>Update</button>
    </div>
    <p>To personalised your repository.</p>

    <h2>Date Joined</h2>
    <div class="form-endpoint">
        <div>
            <input type="text" readonly="" id="" value="<?php echo $result['date_created']?>">
        </div>
        <button class="disabled">Save</button>
    </div>
    <p>Date when your first sign in.</p>
</form><br>

<script src="assets/js/menu-chunk-07197.js"></script>

</body>
</html>
<?php
    }
}
?>