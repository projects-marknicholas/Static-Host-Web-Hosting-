<?php
use PHPMailer\PHPMailer\PHPMailer;
require 'config.php';
if (isset($_GET['formid'])) {
	$sql = "SELECT * FROM users WHERE userid = '".$_GET['formid']."'";
	$stmt = mysqli_prepare($link, $sql);
	mysqli_stmt_execute($stmt);
	$res = mysqli_stmt_get_result($stmt);

	if (mysqli_num_rows($res) > 0) {
	    while ($result = mysqli_fetch_assoc($res)) {
	    	$_POST['zxcvbnmasdfghjklqwertyuiop1029384756'] = $result["username"];
	    	function sendmail(){
	    		$body = "";
                $name = "Static Host Email Service"; 
                $to = $_POST['zxcvbnmasdfghjklqwertyuiop1029384756'];  
                $subject = "New submission from Static Host";
                $body .= "
                    Hey there!<br><br>

					I wanted to let you know that someone has reached out to you through your website. Please find the details of the submission below:<br><br>
                ";

		    	foreach ($_POST as $key => $value) {
				    if ($key === 'zxcvbnmasdfghjklqwertyuiop1029384756') {
				        // If the key is the sensitive field, hide both the key and the value
				        $key = '';
				        $value = '';
				    }
				    $body .= "<br><br><strong>".strtoupper(($key ? $key.":" : ""))."</strong> <br>".nl2br($value);
				}

		    	$body .= "
		    		<br><br>
                    Please respond to this submission as soon as possible to keep your visitors engaged.<br><br>

					Thank you!<br><br>

					Best regards,<br>
					Static Host Team
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
                    header('location: success?success');
                }
            }
            sendmail();
	    }
	}
	else{
		header('location: success?failed');
	}
}
else{
	header('location: success?failed');
}
?>