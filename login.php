<?php
if (isset($_COOKIE['userid'])) {
    header('location: ./account');
}
require_once "config.php";

$username = $password = "";
$username_err = $password_err = $login_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty(trim($_POST["username"]))){
        $username_err = "<span>Please enter username.</span>";
    } 
    else{
        $username = trim($_POST["username"]);
    }

    if(empty(trim($_POST["password"]))){
        $password_err = "<span>Please enter your password.</span>";
    } 
    else{
        $password = trim($_POST["password"]);
    }

    if(empty($username_err) && empty($password_err)){
        $sql = "SELECT userid, username, password FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            $param_username = $username;
            
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){           
                    mysqli_stmt_bind_result($stmt, $userid, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){  

                            $otp = rand(00000, 99999);

                            setcookie("userid", $userid, time() + (10 * 365 * 24 * 60 * 60));   

                            $updateSql = "UPDATE users SET otp = '$otp' WHERE userid = '$userid'";
                            mysqli_query($link, $updateSql);                 
                            
                            header("location: ./account");
                        } else{
                            $login_err = "<span>Invalid username or password.</span>";
                        }
                    }
                } else{
                    $login_err = "<span>Invalid username or password.</span>";
                }
            } else{
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
    <meta name="title" content="Log In - Static Host">
    <meta name="description" content="Great Websites Start with Free Unlimited Hosting">
    <meta name="keywords" content="Static Host, Great Websites Start with Free Unlimited Hosting">
    <meta name="robots" content="index, follow">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="language" content="English">
    <meta name="author" content="Log In - Static Host">
    <meta property="twitter:url" content="https://statichost.rf.gd/login">
    <meta property="twitter:title" content="Log In - Static Host">
    <meta property="twitter:description" content="Great Websites Start with Free Unlimited Hosting">
	<link rel="icon" href="assets/svg/logo.svg" type="image/gif" sizes="16x16">
	<link rel="icon" href="assets/svg/logo.svg" type="image/gif" sizes="32x32">
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	<title>Log In - Static Host</title>
</head>
<body>

<?php include 'navbar.php'?>

<div class="wrap">
    <h2>Sign In</h2>
    <?php 
        if(!empty($login_err)){
            echo '<span class="invalid-feedback">' . $login_err . '</span>';
        }        
    ?>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <div class="form-group">
            <p>Username</p>
            <input type="email" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>" autocomplete="off" placeholder="Username"><br>
            <span class="invalid-feedback"><?php echo $username_err; ?></span>
        </div>
        <div class="form-group">
            <p>Password</p>
            <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" autocomplete="off" placeholder="Password"><br>
            <span class="invalid-feedback"><?php echo $password_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Login">
        </div>
        <div class="form-flex">
            <div class="left">
                <a href="forgot-password">Forgot password?</a>
            </div>
            <div class="right">
                <a href="create-account">Create account</a>
            </div>
        </div>
    </form>
</div>

<script src="assets/js/menu-chunk-07197.js"></script>

</body>
</html>