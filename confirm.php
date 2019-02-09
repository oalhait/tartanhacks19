<?php
//$con include
require 'config.php';

require_once('SMTP.php');
require_once('PHPMailer.php');
require_once('Exception.php');

use \PHPMailer\PHPMailer\PHPMailer;
use \PHPMailer\PHPMailer\Exception;

if(isset($_SESSION['username'])){
    header('Location: index.php?tit=Home');
    exit;
}

if(isset($_GET['ema']) == 0){
    header('Location: index.php?tit=Home');
    exit;
}

$ema = $_GET['ema'];
$user_details_query = mysqli_query($con, "SELECT * FROM users WHERE email_address = '$ema' AND account_confirmation != 'yes'");
$num_users = mysqli_num_rows($user_details_query);
if($num_users == 0){
    header('Location: login.php');
    exit;
}
$user_array = mysqli_fetch_array($user_details_query);

$mail=new PHPMailer(true); // Passing `true` enables exceptions

try {
    //settings
    $mail->SMTPDebug=0; // Enable verbose debug output
    $mail->isSMTP(); // Set mailer to use SMTP
    $mail->Host=$our_smtp;
    $mail->SMTPAuth=$our_auth; // Enable SMTP authentication
    $mail->Username=$our_email; // SMTP username
    $mail->Password=$our_email_password; // SMTP password
    $mail->SMTPSecure=$our_secure;
    $mail->Port=$our_port;

    $mail->setFrom($our_email, $our_email_name);

    //recipient
    $mail->addAddress($ema, '');     // Add a recipient

    //content
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject='Aleegence Account Confirmation';
    $mail->Body='<b><h3>Hey ' . $user_array['first_name'] . ',</h3></b>To confirm your <b>aleegence</b> account, click the following link:<br><a href="' . $server . 'confirmed.php?cc=' . $user_array['account_confirmation'] . '">Confirm my account!</a>';
    //$mail->AltBody='This is the body in plain text for non-HTML mail clients';

    $mail->send();

    $bodyHTML = "<h1>Nice!</h1><br>We just sent an e-mail to the address '<font color=red>" . $ema . "</font>'...<br>It contains the link for you to confirm your account.";
    $linkTEXT = "Resend E-mail";
    $linkURL = "confirm.php?ema=" . $ema;
} 

catch(Exception $e) {
    $bodyHTML = "<h1>Sorry!</h1><br>We couldn't find any e-mail address like '<font color=red>" . $ema . "</font>'...<br>Please sign up with a valid e-mail address.";
    $linkTEXT = "Let me re-register";
    $linkURL = "login.php";
    $delete_attempt_query = mysqli_query($con, "DELETE FROM users WHERE email_address = '$ema' AND account_confirmation != 'yes'");
}

?>

<!DOCTYPE html>
<html>
<head>
  <link rel = "stylesheet" type = "text/css" href = "style.css" />
  <style>
    @font-face {
      font-family: optimal;
      src: url(avnext.otf); /* Safari, Android, iOS */
    }
  </style>
  <title>
    Aleegence | Confirm Acoount
  </title>
  <link rel = "icon" type = "image/png" href = "n_logo.png" />
  <meta name="screen" content="width=device-width">
</head>

<body style="margin: 0; font-family: orkney; background-color: #333; width: 100%; height: 100%; padding: 0;">
  <div class="topnav" id="myTopnav" style="margin: 0; position: fixed; top: 0;">
    <a class="active" style="font-family: optimal; font-size: 30px; padding-left: 250px; color: white;"><img src="n_logo.png" height=50px width=56px style="position: absolute; top: 7px; left: 10px;"> <div style="position: absolute; top: 14px; left: 77px;"><b>aleegence</b></div></a>
  </div>
  <div id="fancy_stripe"></div>
  <div id="not_fancy_stripe"></div>
  <hr style="margin-top: 80px; margin-bottom: 50px;">
  <div style="margin-left: 40px;"><bolden style="font-size: 30px; color: white;">
  <?php
    echo $bodyHTML . "<p></p><a style='text-decoration: none; color: #0077ff;' href='" . $linkURL . "'>" . $linkTEXT . "</a>";
  ?></bolden></div>
</body>
</html>