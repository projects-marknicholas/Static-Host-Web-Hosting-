<?php
require_once "config.php";

$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";
 
if(isset($_GET['resetid'])){
    $query = mysqli_query($link, "SELECT * FROM users WHERE `userid` = '".$_GET['resetid']."'") or die(mysqli_error());
    $fetch = mysqli_fetch_array($query); 
    if(isset($_POST['submit'])){
        if(empty(trim($_POST["new_password"]))){
            $new_password_err = "Please enter the new password.";     
        } elseif(strlen(trim($_POST["new_password"])) < 6){
            $new_password_err = "Password must have atleast 6 characters.";
        } else{
            $new_password = trim($_POST["new_password"]);
        }
        if(empty(trim($_POST["confirm_password"]))){
            $confirm_password_err = "Please confirm the password.";
        } else{
            $confirm_password = trim($_POST["confirm_password"]);
            if(empty($new_password_err) && ($new_password != $confirm_password)){
                $confirm_password_err = "Password did not match.";
            }
        }
        if(empty($new_password_err) && empty($confirm_password_err)){
            $hashed = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
            $sql = "UPDATE users SET password = '".$hashed."' WHERE userid = '".$_GET['resetid']."' ";
            
            if($stmt = mysqli_prepare($link, $sql)){
           
                mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);
                
          
                $param_password = password_hash($new_password, PASSWORD_DEFAULT);
                setcookie("userid", $param_id, time() + (10 * 365 * 24 * 60 * 60));  
                if(mysqli_stmt_execute($stmt)){
                    header("location: login");
                    exit();
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }
                mysqli_stmt_close($stmt);
            }
        }
        

        mysqli_close($link);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1.0" name="viewport">
	<meta content="assets/svg/logo.svg" name="og:image">
    <meta name="title" content="New Password - Static Host">
    <meta name="description" content="Great Websites Start with Free Unlimited Hosting">
    <meta name="keywords" content="Static Host, Great Websites Start with Free Unlimited Hosting">
    <meta name="robots" content="index, follow">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="language" content="English">
    <meta name="author" content="New Password - Static Host">
    <meta property="twitter:url" content="https://statichost.rf.gd/">
    <meta property="twitter:title" content="New Password - Static Host">
    <meta property="twitter:description" content="Great Websites Start with Free Unlimited Hosting">
	<link rel="icon" href="assets/svg/logo.svg" type="image/gif" sizes="16x16">
	<link rel="icon" href="assets/svg/logo.svg" type="image/gif" sizes="32x32">
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	<title>New Password - Static Host</title>
</head>
<body>

<?php include 'navbar.php'?>

<div class="wrap">
    <h2>New Password</h2>
    <form action="" method="post">
        <div class="form-group">
            <p>Password</p>
            <input type="password" name="new_password" class="form-control <?php echo (!empty($new_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_password; ?>" placeholder="New Password"><br>
            <span class="invalid-feedback"><?php echo $new_password_err; ?></span>
        </div>
        <div class="form-group">
            <p>Confirm Password</p>
            <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" placeholder="Confirm Password"><br>
            <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" name="submit" class="btn btn-primary" value="Update">
        </div>
        <div class="form-flex">
            <div class="left">
                <a href="login">Back</a>
            </div>
            <div class="right">
                <a href="contact-us">Report a problem</a>
            </div>
        </div>
    </form>
</div>

<script src="assets/js/menu-chunk-07197.js"></script>

</body>
</html>