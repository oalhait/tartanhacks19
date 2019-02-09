<?php
	require 'config.php';

	if(isset($_SESSION['username'])){
        $username = $_SESSION['username'];
        $log_out_time = date('Y-m-d H:i:s');
		$user_logout_query = mysqli_query($con, "UPDATE users SET last_active = '$log_out_time' WHERE username = '$username'");
    }

	session_start();
	session_destroy();
	header('Location: login.php');
	exit;
?>