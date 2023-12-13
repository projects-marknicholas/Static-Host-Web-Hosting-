<?php 
use PHPMailer\PHPMailer\PHPMailer;
if(!isset($_COOKIE['userid'])){
    header('location: ../');
}
require '../config.php';

if (isset($_GET['track'])) {
	$sql = "SELECT * FROM repository WHERE date_created = ?";
	$stmt = mysqli_prepare($link, $sql);
	mysqli_stmt_bind_param($stmt, "s", $_GET['track']);
	mysqli_stmt_execute($stmt);
	$res = mysqli_stmt_get_result($stmt);

	if (mysqli_num_rows($res) > 0) {
		while ($result = mysqli_fetch_assoc($res)) {

            $ssql = "SELECT * FROM users WHERE userid = ?";
            $sstmt = mysqli_prepare($link, $ssql);
            mysqli_stmt_bind_param($sstmt, "s", $result['repository_id']);
            mysqli_stmt_execute($sstmt);
            $sres = mysqli_stmt_get_result($sstmt);

            if (mysqli_num_rows($sres) > 0) {
                while ($row = mysqli_fetch_assoc($sres)) {
                    $_POST['firstname'] = $row['firstname'];
                    $_POST['repository_name'] = $result['repository_name'];
                    function sendmail(){
                        $name = "Static Host Email Service"; 
                        $to = "razonmarknicholas.cdlb@gmail.com";  
                        $subject = "Request Repository Deletion";
                        $body = "
                            Dear ".$_POST['firstname'].",<br><br>

                            We have received your request to delete the repository <strong>".$_POST['repository_name']."</strong> from our platform. We appreciate your decision and will process your request shortly.<br><br>

                            However, before we proceed with the deletion, we would like to inform you that we will wait for a few moments before deleting your repository. This is to ensure that the deletion process is authorized by you and that you have sufficient time to reconsider your decision.<br><br>

                            Rest assured that we will keep your repository data secure during this time, and we will delete it as soon as possible. If you change your mind and wish to cancel the deletion request, please inform us immediately.<br><br>

                            Thank you for using our platform, and we apologize for any inconvenience this may cause.<br><br>

                            Best regards,<br>
                            Static Host Team<br>
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
            }
		}
	}
	mysqli_stmt_close($stmt);
	mysqli_close($link);
	header('location: ../account');
}
?>
