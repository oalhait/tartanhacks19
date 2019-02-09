<?php
  //$con include
  require 'config.php';
  
  if(isset($_SESSION['username'])){
    //good to go
  } else {
    header('Location: index.php');
    exit;
  }

  function generate_tags($words) {
    $words = " " . strtolower($words) . " ";

    $words = str_replace("...", ".", $words);
    $words = str_replace(".", " ", $words);
    $words = str_replace(",", " ", $words);
    $words = str_replace(";", " ", $words);
    $words = str_replace(":", " ", $words);
    $words = str_replace("-", " ", $words);
    $words = str_replace("_", " ", $words);
    $words = str_replace("", " ", $words);
    $words = str_replace("&", " ", $words);
    $words = str_replace("%", " ", $words);
    $words = str_replace("$", " ", $words);
    $words = str_replace("/", " ", $words);
    $words = str_replace("=", " ", $words);
    $words = str_replace("+", " ", $words);
    $words = str_replace("*", " ", $words);
    $words = str_replace("(", " ", $words);
    $words = str_replace(")", " ", $words);
    $words = str_replace("[", " ", $words);
    $words = str_replace("]", " ", $words);
    $words = str_replace("?", " ", $words);
    $words = str_replace("!", " ", $words);
    $words = str_replace("^", " ", $words);
    $words = str_replace("{", " ", $words);
    $words = str_replace("}", " ", $words);
    $words = str_replace("|", " ", $words);
    $words = str_replace('\n', " ", $words);
    $words = str_replace("0", " ", $words);
    $words = str_replace("1", " ", $words);
    $words = str_replace("2", " ", $words);
    $words = str_replace("3", " ", $words);
    $words = str_replace("4", " ", $words);
    $words = str_replace("5", " ", $words);
    $words = str_replace("6", " ", $words);
    $words = str_replace("7", " ", $words);
    $words = str_replace("8", " ", $words);
    $words = str_replace("9", " ", $words);
    $words = str_replace("’", " ", $words);
    $words = str_replace("'", " ", $words);
    $words = str_replace('"', " ", $words);

    while(strstr($words, "  ")){
      $words = str_replace("  ", " ", $words);
    }

    $generic_words = explode(" ", "the of to and a b c d e f g h i j k l m n o p q r s t u v w x y z in is it you that he was for on are with as his they be at one have this from or had by but what some we can out other were all there when up use your how said an each she which do their if will way about many then them wouldlike so these her long see him two has look more could go come did no most my over know than first who may down side been now find any new part take get place made where after back little only round man year came show every good me give our under name very through just form great say help low line differ turn cause much mean before move right boy old too same tell does set three want well also small end put large add even here must high such follow act why ask men went kind off need try us again near own should found still four between keep never last let thought cross hard start might saw far left late run dont while press close real few seem together next begin got walk example ease group always those both often until care second took began once hear cut sure main enough plain usual ready above ever list though soon leave short happen half piece told knew pass since top whole heard best better true during hundred five step hold interest reach fast six less ten simple several toward lay against able done stood front final gave oh quick nothing course stay full thousand ago ran yes among yet drop am felt perhaps sudden gone eight seven shall held either else quite lot bottom least except fell whose expect agree thus wont rather nor occur meant really however center ahead its");

    $word_length = 0;

    for ($i=0; $i < sizeof($generic_words); $i++) { 
      $words = str_replace(" " . $generic_words[$i] . " ", " ", $words);
    }

    $words_list = explode(" ", $words);
    $word_counts = array_count_values($words_list);
    $words_list = array_unique($words_list);

    $out_string = ",";

    $num_occurences = 0;
    for ($i=0; $i < sizeof($words_list); $i++) { 
      if(strlen($words_list[$i])==0){
        continue;
      }
      $num_occurences = $word_counts[$words_list[$i]];
      $out_string .= $words_list[$i] . ":" . $num_occurences . ",";
    }

    return $out_string;
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
	  if(basename($_SERVER['PHP_SELF'])=="explore.php"){
	  	$titly = "Explore Projects";
	  }
	  if(basename($_SERVER['PHP_SELF'])=="profile.php"){
	  	$titly = "Profile";
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
  <title>
    Work Well | <?php echo $titly; ?>
  </title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
  <link rel="icon" type="image/png" href ="logo.png" />
  <link rel="stylesheet" href="assets/css/main.css" />
  <noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
</head>

<body class="is-preload" style="margin: 0; font-family: orkney; background-color: #ccc; width: 100%; height: 100%; padding: 0;">
  <div class="topnav" id="myTopnav" style="margin: 0; position: fixed; top: 0;">
    <a href="index.php?tit=Home" class="active" style="font-family: optimal; font-size: 28px; padding-left: 250px; color: white;"><img src="preston.png" height=40px width=32px style="position: absolute; top: 7px; left: 10px; border-radius: 10px;"> <div style="position: absolute; top: 14px; left: 55px;"><b>theResearchApp</b></div></a>
    <a href="logout.php" style="float: right;">Logout</a>
    <a href="javascript:void(0);" class="icon" onclick="myFunction()">&#9776;</a>
  </div>
  <script src="navFunc.js"></script>