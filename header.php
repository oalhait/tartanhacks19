<?php
  //$con include
  require 'config.php';
  
  if(isset($_SESSION['username'])){
    //good to go
  } else {
    if(basename($_SERVER['PHP_SELF'])=="explore.php"){
      header('Location: login.php');
      exit;
    }
  }

  $username = $_SESSION['username'];

  $last_act_time = date('Y-m-d H:i:s');
  $user_update_query = mysqli_query($con, "UPDATE users SET last_seen_datetime = '$last_act_time' WHERE username = '$username'");

  $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username = '$username'");
  $user_array = mysqli_fetch_array($user_details_query);

  if(isset($_GET['tit'])){
	  $titly = $_GET['tit'];
  } else {
	  if($_SERVER['REQUEST_URI']=="/"){
	  	$titly = "Home";
	  }
	  if(basename($_SERVER['PHP_SELF'])=="index.php"){
	  	$titly = "Home";
	  }
	  if(basename($_SERVER['PHP_SELF'])=="newsfeed.php"){
	  	$titly = "Newsfeed";
	  }
	  if(basename($_SERVER['PHP_SELF'])=="profile.php"){
	  	$titly = "My Profile";
	  }
	  if(basename($_SERVER['PHP_SELF'])=="search.php"){
	  	$titly = "Search";
	  }
	  if(basename($_SERVER['PHP_SELF'])=="whats_new.php"){
	  	$titly = "What's New";
	  }
	  if(basename($_SERVER['PHP_SELF'])=="create_producer.php"){
	  	$titly = "Create Producer";
	  }
	  if(basename($_SERVER['PHP_SELF'])=="create_item.php"){
	  	$titly = "Create Item";
	  }
	  if(basename($_SERVER['PHP_SELF'])=="create_news_story.php"){
	  	$titly = "Create News Story";
	  }
	  if(basename($_SERVER['PHP_SELF'])=="create_news_post.php"){
	  	$titly = "Create News Post";
	  }
	  if(basename($_SERVER['PHP_SELF'])=="create_tagline.php"){
	  	$titly = "Create Tagline";
	  }
	  if(basename($_SERVER['PHP_SELF'])=="delete_comment.php"){
	  	$titly = "Delete Comment";
	  }
	  if(basename($_SERVER['PHP_SELF'])=="producer_listing.php"){
	  	$titly = "Producer Listing";
	  }
	  if(basename($_SERVER['PHP_SELF'])=="leaderboards.php"){
	  	$titly = "Leaderboards";
	  }
	  if(basename($_SERVER['PHP_SELF'])=="change_password.php"){
	  	$titly = "Change Password";
	  }
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
    theResearchApp | <?php echo $titly; ?>
  </title>
  <link rel = "icon" type = "image/png" href = "preston.png" />
  <meta name="screen" content="width=device-width">
</head>

<body style="margin: 0; font-family: orkney; background-color: #ccc; width: 100%; height: 100%; padding: 0;">
  <div class="topnav" id="myTopnav" style="margin: 0; position: fixed; top: 0;">
    <a href="index.php?tit=Home" class="active" style="font-family: optimal; font-size: 28px; padding-left: 250px; color: white;"><img src="preston.png" height=40px width=32px style="position: absolute; top: 7px; left: 10px; border-radius: 10px;"> <div style="position: absolute; top: 14px; left: 55px;"><b>theResearchApp</b></div></a>
    <!--
    <a href="newsfeed.php?tit=Newsfeed" style="color: <?php
    if(basename($_SERVER['PHP_SELF']) == 'newsfeed.php'){
        echo 'white';
    } else {
        echo '#bbb';
    } ?>;">Newsfeed</a>
    <a href="leaderboards.php?tit=Leaderboards" style="color: <?php
    if(basename($_SERVER['PHP_SELF']) == 'leaderboards.php'){
        echo 'white';
    } else {
        echo '#bbb';
    } ?>;">Leaderboards</a>
    <a href="profile.php?tit=My Profile" style="color: <?php
    if(basename($_SERVER['PHP_SELF']) == 'profile.php'){
        echo 'white';
    } else {
        echo '#bbb';
    } ?>;">Profile</a>
    <a href="search.php?tit=Search&q=" style="color: <?php
    if(basename($_SERVER['PHP_SELF']) == 'search.php'){
        echo 'white';
    } else {
        echo '#bbb';
    } ?>;">Search</a>
  -->
    <a href="logout.php" style="float: right;">Logout</a>
    <a href="javascript:void(0);" class="icon" onclick="myFunction()">&#9776;</a>
  </div>
  <script src="navFunc.js"></script>