<?php
//$con include
require 'config.php';

if(isset($_SESSION['username'])){
    header('Location: index.php?tit=Home');
    exit;
}

if(isset($_GET['cc']) == 0){
    header('Location: index.php?tit=Home');
    exit;
}

$cc = $_GET['cc'];
$user_confirm_query = mysqli_query($con, "UPDATE users SET account_confirmation = 'yes' WHERE account_confirmation = '$cc'");

header('Location: login.php?alert=Your account has been confirmed! Now, login to access the site.');
exit;

?>