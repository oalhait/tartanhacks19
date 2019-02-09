<?php 
	ob_start();
	session_start();

	$timezone = date_default_timezone_set("America/New_York");

	$con = mysqli_connect("localhost", "root", "", "tra_database");

	$server = "http://localhost/theResearchApp/";

  	$personnel = array("alee_master_1", "alee_master_2", "alee_editor_1", "alee_editor_2", "alee_editor_3", "alee_editor_4", "alee_editor_5", "alee_journalist_1", "alee_journalist_2");

  	$our_smtp = 'pod51000.outlook.com';
  	$our_port = 587;
  	$our_secure = 'tls';
	$our_auth = true;

  	$our_email = 'notifications@aleegence.com';
  	$our_email_name = 'Aleegence';
  	$our_email_password = 'Saafari8$';

  	$his_email = 'ceo@aleegence.com';
	
	if(mysqli_connect_errno()){
		echo "Could not connect: " . mysqli_connect_errno();
	}
?>