<?php
require_once "config.php";
$firstname = $lastname = $username = $password = $confirm_password = "";
$firstname_err = $lastname_err = $username_err = $password_err = $confirm_password_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty(trim($_POST["username"]))){
        $username_err = "<span>Please enter a username.</span>";
    }
    else{
        $sql = "SELECT id FROM users WHERE username = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            $param_username = trim($_POST["username"]);
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "<span>This username is already taken.</span>";
                } 
                else{
                    $username = trim($_POST["username"]);
                }
            }
            else{
                echo "<span>Oops! Something went wrong. Please try again later.<span>";
            }
            mysqli_stmt_close($stmt);
        }
    }
    if(empty(trim($_POST["password"]))){
        $password_err = "<span>Please enter a password.</span>";     
    } 
    elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "<span>Password must have atleast 6 characters.</span>";
    } 
    else{
        $password = trim($_POST["password"]);
    }



    if(empty(trim($_POST['firstname']))){
        $firstname_err = "<span>first name cannot be empty</span>";
    }
    else{
        $firstname = trim($_POST["firstname"]);
    }
    if(empty(trim($_POST['lastname']))){
        $lastname_err = "<span>last name cannot be empty</span>";
    }
    else{
        $lastname = trim($_POST["lastname"]);
    }

    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "<span>Please confirm password.</span>";     
    } 
    else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "<span>Password did not match.<span>";
        }
    }

    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){

        $userid = str_pad(rand(0,999999),20, "0", STR_PAD_LEFT);
        setcookie("userid", $userid, time() + (10 * 365 * 24 * 60 * 60));
        $firstname = mysqli_real_escape_string($link, $_POST['firstname']);
        $lastname = mysqli_real_escape_string($link, $_POST['lastname']);
        $date_created = date("M / d / Y");

        $sql = "INSERT INTO users (username, password, firstname, lastname, userid, date_created) VALUES (?, ?, '$firstname', '$lastname', '$userid', '$date_created')";
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT);

            if(mysqli_stmt_execute($stmt)){
                header("location: login");
            } 
            else{
                echo "<span>Oops! Something went wrong. Please try again later.</span>";
            }
            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($link);
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1.0" name="viewport">
	<meta content="assets/svg/logo.svg" name="og:image">
    <meta name="title" content="Create Account - Static Host">
    <meta name="description" content="Great Websites Start with Free Unlimited Hosting">
    <meta name="keywords" content="Static Host, Great Websites Start with Free Unlimited Hosting">
    <meta name="robots" content="index, follow">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="language" content="English">
    <meta name="author" content="Create Account - Static Host">
    <meta property="twitter:url" content="https://statichost.rf.gd/create-account">
    <meta property="twitter:title" content="Create Account - Static Host">
    <meta property="twitter:description" content="Great Websites Start with Free Unlimited Hosting">
	<link rel="icon" href="assets/svg/logo.svg" type="image/gif" sizes="16x16">
	<link rel="icon" href="assets/svg/logo.svg" type="image/gif" sizes="32x32">
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	<title>Create Account - Static Host</title>
</head>
<body>

<?php include 'navbar.php'?>

<div class="wrap">
    <h2>Create Account</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <p>Firstname</p>
            <input type="text" name="firstname" class="form-control <?php echo (!empty($firstname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $firstname; ?>" placeholder="First Name">
            <span class="invalid-feedback"><?php echo $firstname_err; ?></span>
        </div> 
        <div class="form-group">
            <p>Lastname</p>
            <input type="text" name="lastname" class="form-control <?php echo (!empty($lastname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $lastname; ?>" placeholder="Last Name">
            <span class="invalid-feedback"><?php echo $lastname_err; ?></span>
        </div> 
        <div class="form-group">
            <p>Username</p>
            <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>" placeholder="Username">
            <span class="invalid-feedback"><?php echo $username_err; ?></span>
        </div>    
        <div class="form-group">
            <p>Password</p>
            <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>" placeholder="Password">
            <span class="invalid-feedback"><?php echo $password_err; ?></span>
        </div>
        <div class="form-group">
            <p>Confirm Password</p>
            <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>" placeholder="Confirm Password">
            <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit">
        </div>
        <div class="form-flex">
            <div class="left">
                <a href="login">Already have an account?</a>
            </div>
        </div>
    </form>
</div> 

<script src="assets/js/menu-chunk-07197.js"></script>

</body>
</html>