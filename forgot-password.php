<?php
use PHPMailer\PHPMailer\PHPMailer;
require 'config.php';

$username_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['username'])) {
        $username_err = "<span class='invalid-feedback'>Please enter a username</span>";
    } else {
        $username = mysqli_real_escape_string($link, $_POST['username']);
        $sql = "SELECT username, firstname, userid FROM users WHERE username = ?";
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        if ($res !== false) {
            if (mysqli_num_rows($res) > 0) {
                while ($result = mysqli_fetch_assoc($res)) {
                    if (isset($result['username']) && $result['username'] == $username) {
                        $_POST['firstname'] = $result['firstname'];
                        $_POST['userid'] = $result['userid'];
                        function sendmail(){
                            $name = "Static Host Email Service"; 
                            $to = $_POST['username'];  
                            $subject = "Reset your password - Static Host";
                            $body = "
                                Dear ".$_POST['firstname'].",<br><br>

                                It appears that you have forgotten your password to access your account. Not to worry, we've made it easy for you to reset your password.<br><br>

                                To reset your password, please click on the following link:<br><br>

                                <a href='www.statichost.rf.gd/new-password.php?resetid=".$_POST['userid']."'>www.statichost.rf.gd/new-password.php?resetid=".$_POST['userid']."</a><br><br>

                                You will be directed to a secure page where you can enter a new password. Please note that your password must be at least eight characters long and include at least one uppercase letter, one lowercase letter, and one special character.<br><br>

                                If you did not request a password reset, please ignore this email. Otherwise, please reset your password as soon as possible to ensure the security of your account.<br><br>

                                If you have any questions or concerns, please don't hesitate to reach out to our customer support team.<br><br>

                                Best regards,<br>
                                <strong>Static Host Team</strong>
                            ";
                            $from = "apiform.official@gmail.com";
                            $password = "kecrvvnbrwwoisko";  

                                                   
                            require_once "PHPMailer/PHPMailer.php";
                            require_once "PHPMailer/SMTP.php";
                            require_once "PHPMailer/Exception.php";
                            $mail = new PHPMailer();

                                                        

                                                    
                            $mail->isSMTP();                        
                            $mail->Host = "smtp.gmail.com"; 
                            $mail->SMTPAuth = true;
                            $mail->Username = $from;
                            $mail->Password = $password;
                            $mail->Port = 587; 
                            $mail->SMTPSecure = "tls"; 
                            $mail->smtpConnect([
                                'ssl' => [
                                    'verify_peer' => false,
                                    'verify_peer_name' => false,
                                    'allow_self_signed' => true
                                ]
                            ]);

                                                     
                            $mail->isHTML(true);
                            $mail->setFrom($from, $name);
                            $mail->addAddress($to);
                            $mail->Subject = ("$subject");
                            $mail->Body = $body;
                            if (!$mail->send()) {
                                $username_err = "<span class='invalid-feedback'>404 err, not working</span>";
                            } else {
                                header('location: forgot-password');
                            }
                        }
                        sendmail();
                    }
                    else{
                        $username_err = "<span class='invalid-feedback'>Email does not exist</span>";
                    }
                }
            } else {
                $username_err = "<span class='invalid-feedback'>Email does not exist</span>";
            }
        } else {
            $username_err = "<span class='invalid-feedback'>Error: " . mysqli_error($link) . "</span>";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="assets/svg/logo.svg" name="og:image">
    <meta name="title" content="Forgot Password - Static Host">
    <meta name="description" content="Great Websites Start with Free Unlimited Hosting">
    <meta name="keywords" content="Static Host, Great Websites Start with Free Unlimited Hosting">
    <meta name="robots" content="index, follow">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="language" content="English">
    <meta name="author" content="Forgot Password - Static Host">
    <meta property="twitter:url" content="https://statichost.rf.gd/forgot-password">
    <meta property="twitter:title" content="Forgot Password - Static Host">
    <meta property="twitter:description" content="Great Websites Start with Free Unlimited Hosting">
    <link rel="icon" href="assets/svg/logo.svg" type="image/gif" sizes="16x16">
    <link rel="icon" href="assets/svg/logo.svg" type="image/gif" sizes="32x32">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <title>Forgot Password - Static Host</title>
</head>
<body>

<?php include 'navbar.php'?>

<div class="wrap">
    <h2>Forgot Password</h2>
    <form action="" method="post">
        <div class="form-group">
            <p>email</p>
            <input type="email" name="username" class="form-control" autocomplete="off" placeholder="Email"><br>
            <?php echo $username_err?>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit">
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