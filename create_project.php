<?php

	require_once('SMTP.php');
	require_once('PHPMailer.php');
	require_once('Exception.php');

	use \PHPMailer\PHPMailer\PHPMailer;
	use \PHPMailer\PHPMailer\Exception;

	$mail = new PHPMailer(true);	

	include("header.php");

	if(isset($_POST['add_item'])){
		//add other non-picture variables
		$p_n = $_POST['project_name'];
		$p_s = $_POST['project_summary'];

		$p_n = str_replace("'", "\'", $p_n);
		$p_s = str_replace("'", "\'", $p_s);

		$datetime_added = date('Y-m-d H:i:s');

		$first_tags = generate_tags($p_n . " " . $p_s);

		//add the producer to database
		$add_item_query = "INSERT INTO projects VALUES ('', '" . $p_n . "', '" . $user_id . "', '," . $user_id . ",', '" . $p_s . "', ',', ',', '" . $first_tags . "', 'new_project.png', '-', '-', '0.0', 'no', '" . $datetime_added . "', 'no')";

		$add_item_query = str_replace('"', '\"', $add_item_query);

		$add_item_now  = mysqli_query($con, $add_item_query);

		$user_id = $user_array['id'];

		$items_all = mysqli_query($con, "SELECT * FROM projects WHERE project_name = '$p_n' AND project_manager = '$user_id' AND created_datetime = '$datetime_added'");
	    $item_array = mysqli_fetch_array($items_all);
	    $item_id = $item_array['id'];

		header("Location: project.php?tit=" . $p_n . "&id=" . $item_id);
		exit();
	}

?>

	<style>

		.leaderboard_title {
		        width: 750px;
		        padding: 10px;
		        padding-bottom: 0px;
		        display: block;
		        margin: 0 auto;
		        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.4);
		        background-color: black;
		        font-size: 30px;
		        color: white;
		        font-family: orkneyBOLD;
		        text-align: center;
		}

		.leaderboard_block {
		    width: 770px;
		    display: block;
		    margin: 0 auto;
		    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.4);
		    background-color: #fff;
		    font-size: 20px;
		    margin-bottom: 30px;
		}

		.board_block_text {
			display: inline-block;
			margin: 0;
			padding: 20px;
		}

		bolden {
			font-size: 30px;
		}

		p {
			margin-top: 5px;
			margin-bottom: 5px;
		}

		sep {
			display: inline-block;
			width: 20px;
			height: 40px;
		}

		input[type="submit"] {
			display: inline-block;
			border: none;
			font-size: 20px;
		    font-family: orkneyMED;
		    width: 239px;
		    height: 40px;
		    background-color: black;
		    color: white;
		    margin: 0;
		    padding: 0;
		    margin-top: 35px;
		    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.4);
		    cursor: pointer;
		}

		input[type="text"] {
			display: block;
			border: none;
			font-size: 20px;
		    font-family: orkneyMED;
		    width: 239px;
		    height: 60px;
		    background-color: white;
		    color: black;
		    margin: 0;
		    margin-top: 35px;
		    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.4);
		}

		.add_text_button {
			display: inline-block;
			border: none;
			font-size: 20px;
		    font-family: orkneyMED;
		    width: 730px;
		    height: 40px;
		    background-color: black;
		    color: white;
		    margin: 0;
		    margin-top: 10px;
		    padding: 0;
		    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.4);
		    cursor: pointer;
		    float: left;
		}

		select {

		}

		input:focus {
			outline: none;
		}

		.board_block_text img, iframe {
			box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.4);
		}

		@media screen and (max-width: 1080px) {
	      input {
	        -webkit-appearance: none;
	        border-radius: 0;
	      }

	      textarea {
	        -webkit-appearance: none;
	        border-radius: 0;
	      }
	    }

	</style>

	<hr style="margin-bottom: 110px;">

	<div class="leaderboard_title">
		NEW PROJECT
	</div>

	<div class="leaderboard_block">
		<div class="board_block_text" style="width: calc(100% - 40px);">
		<form name="item_form" method="POST" style="padding: 0; margin-left: 0px; margin-bottom: 28px;">

		 <bolden>Project Name</bolden>
		 <input name="project_name" type="text" placeholder="e.g: Peanuts" style="width: calc(100% - 25px); padding-left: 20px; margin: 0; float: left; margin-top: 10px;" autocomplete="off" />
		 <hr style="margin-top: 90px;">

		 <bolden>Project Summary</bolden>
		 <textarea name="project_summary" type="text" placeholder="e.g: A thing that did a stuffs" style="width: calc(100% - 25px); height: 200px; padding-top: 20px; padding-left: 20px; margin: 0; float: left; margin-top: 10px; margin-bottom: 20px; resize: none; font-size: 20px; font-family: orkney; border: none; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.4);"></textarea>
		 <hr style="margin-top: 252px;">


		 <button type="submit" value="uploaded" name="add_item" class="add_text_button">Add Project</button>
		</form><br>
		</div>
	</div>

	<div class="leaderboard_title" style="font-family: orkney; margin-top: 0; font-size: 14px; text-align: left; padding-top: 15px; padding-bottom: 12px; margin-bottom: 30px;">
		Copyright (2018) Aleegence Co.<font style="float: right;"><a href="about.php" style="color: white; text-decoration: none;">About Us</a> | <a href="contact.php" style="color: white; text-decoration: none;">Contact Us</a> | <font style="font-family: orkneyBOLD;"><a href="pledge.php" style="color: white; text-decoration: none;">THE PLEDGE</a></font></font>
	</div>

</body>
</html>