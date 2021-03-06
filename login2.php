<?php
  //$con include
  require 'config.php';

  function getRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $string = '';

    for ($i = 0; $i < $length; $i++) {
        $string .= $characters[mt_rand(0, strlen($characters) - 1)];
    }

    return $string;
  }
  
  if(isset($_SESSION['username'])){
    header('Location: explore.php');
    exit;
  }
  
  $f_name = "";
  $l_name = "";
  $username = "";
  $email = "";
  $password = "";
  $password2 = "";
  
  $fn_errors = ""; 
  $ln_errors = ""; 
  $e_errors = ""; 
  $u_errors = "";
  $p_errors = "";
  $cp_errors = "";
  $e_errors_log = "";
  $p_errors_log = "";
  
  if(isset($_POST['register_b'])){
    $f_name = strip_tags($_POST['f_name']);
    $f_name = str_replace(' ', '', $f_name);
    $l_name = strip_tags($_POST['l_name']);
    $l_name = str_replace(' ', '', $l_name);
    $username = strip_tags($_POST['uname']);
    $username = str_replace(' ', '', $username);
    $email = $_POST['em'];
    $password = $_POST['pword'];
    $password2 = $_POST['pword2'];
    
    $_SESSION['f_name'] = $f_name;
    $_SESSION['l_name'] = $l_name;
    $_SESSION['r_username'] = $username;
    $_SESSION['email'] = $email;
    
    $allowed = "yes";
    
    $username = str_replace(" ", "", $username);
    $_SESSION['r_username'] = $username;
    
    //check vars
    //username
    $usernames = mysqli_query($con, "SELECT username FROM users WHERE username = '$username'");
    $num_rows = mysqli_num_rows($usernames);

    if($num_rows > 0){
      $allowed = "no";
      $u_errors .= "Username is already in use<br>";
    }

    //Check if email is in valid format 
    if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $email = filter_var($email, FILTER_VALIDATE_EMAIL);
      //Check if email already exists 
      $e_check = mysqli_query($con, "SELECT email_address FROM users WHERE email='$email'");
      //Count the number of rows returned
      $num_rows = mysqli_num_rows($e_check);
      if($num_rows > 0) {
        $allowed = "no";
        $e_errors .= "E-mail address already in use<br>";
      }
    } else {
      $allowed = "no";
      $e_errors .= "Invalid e-mail address format<br>";
    }
    
    $fn_length = strlen($f_name);
    if($fn_length < 2){
      $allowed = "no";
      $fn_errors .= "First name is too short<br>";
    }
    if($fn_length > 20){
      $allowed = "no";
      $fn_errors .= "First name is too long<br>";
    }
    if(preg_match('/[^a-zA-Z]/', str_replace("-", "", str_replace("\'", "", $f_name)))){
      $allowed = "no";
      $fn_errors .= "Invalid first name<br>";
    }

    $ln_length = strlen($l_name);
    if($ln_length < 2){
      $allowed = "no";
      $ln_errors .= "Last name is too short<br>";
    }
    if($ln_length > 20){
      $allowed = "no";
      $ln_errors .= "Last name is too long<br>";
    }
    if(preg_match('/[^a-zA-Z]/', str_replace("-", "", str_replace("\'", "", $l_name)))){
      $allowed = "no";
      $ln_errors .= "Invalid last name<br>";
    }

    $u_length = strlen($username);
    if($u_length < 2){
      $allowed = "no";
      $u_errors .= "Username is too short<br>";
    }
    if($u_length > 20){
      $allowed = "no";
      $u_errors .= "Username is too long<br>";
    }

    if((preg_match('/[a-z]/', $username) || preg_match('/[A-Z]/', $username)) == 0){
        $allowed = "no";
        $u_errors .= "Username must contain alphabetical characters<br>";
      }
    
    //password
    $p_length = strlen($password);
    if($p_length < 8){
      $allowed = "no";
      $p_errors .= "Password is too short<br>";
    }
    if($p_length > 20){
      $allowed = "no";
      $p_errors .= "Password is too long<br>";
    }

    if($password == $password2){
      //You don did good!
    } else {
      $allowed = "no";
      $cp_errors .= "Passwords do not match<br>";
    }
    
    if(preg_match('/[a-z]/', $password) && preg_match('/[A-Z]/', $password) && preg_match('/\d/', $password)){
      //s-all good
    } else {
      $allowed = "no";
      $p_errors .= "Password must contain upper and lower case letters as well as numbers<br>";
    }
    
    //hashing password
    $hash_pass = md5($password);

    //getting time joined
    $datetime_joined = date('Y-m-d H:i:s');
    
    //insert vars
    if($allowed == "yes"){
      $f_name = ucfirst($f_name);
      $l_name = ucfirst($l_name);

      $f_name = str_replace("'", "\'", $f_name);
      $l_name = str_replace("'", "\'", $l_name);
      $username = str_replace("'", "\'", $username);
      $email = str_replace("'", "\'", $email);

      $email_parts = explode("@", $email);
      $school_domain = $email_parts[1];

      $confirm_code = getRandomString(255);

      $add_new_user_query = "INSERT INTO users VALUES ('', '" . $username . "', '" . $hash_pass . "', '" . $f_name . "', '" . $l_name . "', '" . $email . "', 'new_user.png', '" . $datetime_joined . "', '" . $datetime_joined . "', 'no', '-', '" . $school_domain . "', '-', '-', '-1', '-', '-', '-', '-', '," . $f_name . "," . $l_name . ",', 'no', '" . $confirm_code . "')";

      $add_new_user_query = str_replace('"', '\"', $add_new_user_query);

      $add_new_user = mysqli_query($con, $add_new_user_query);
      
      //clear session variables
      $_SESSION['f_name'] = "";
      $_SESSION['l_name'] = "";
      $_SESSION['email'] = "";
      $_SESSION['r_username'] = "";
      
      header('Location: confirm.php?ema=' . $email);
      exit;
    }
  }

  if(isset($_POST['login_b'])){
    $email = $_POST['em'];
    $password = $_POST['pword'];

    $_SESSION['email'] = $email;

    //verifying password
    $find_attempted_user = mysqli_query($con, "SELECT * FROM users WHERE email_address = '$email' AND account_confirmation = 'yes'");
    $attempted_user = mysqli_fetch_array($find_attempted_user);
    $num_rows = mysqli_num_rows($find_attempted_user);

    if($num_rows > 0){
      $hash_pass = md5($password);
      if($attempted_user['hashed_password'] == $hash_pass){
        $_SESSION['username'] = $attempted_user['username'];

        //clear session variable
        $_SESSION['email'] = "";

        $username = $_SESSION['username'];
        $log_in_time = date('Y-m-d H:i:s');
        $user_login_query = mysqli_query($con, "UPDATE users SET last_seen_datetime = '$log_in_time' WHERE username = '$username'");
        $user_online_query = mysqli_query($con, "UPDATE users SET is_online = 'yes' WHERE username = '$username'");

        header('Location: explore.php');
        exit;
      } else {
        $p_errors_log .= "This e-mail address and password do not match<br>";
      }
    } else {
      $e_errors_log .= "This e-mail address is not registered with any account<br>";
    }
  }
?>

<!DOCTYPE HTML>
<html>
    <head>
        <title>LOGIN PAGE</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
        <link rel="stylesheet" href="assets/css/main.css" />
        <noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
        <script src="assets/js/main.js"></script>

        <style>
            hidden {
              display: none;
            }

            showy {
              display: block;
            }
        </style>
    </head>
    <body class="landing is-preload">

        <!-- Page Wrapper -->
            <div id="page-wrapper">

                <!-- Header -->
                    <header id="header" class="alt">
                        <h1><a href="index.php">WORK WELL</a></h1>
                        
                    </header>

                <!-- Banner -->
                    <section id="banner">
                        <div class="inner">
                            <h2>Work Well</h2>
                            <section>
                                <div id="loginBoxes">
                                    <form method="post">
                                        <div class="row gtr-uniform">
                                            <div class="col-2 col-0-xsmall"></div>
                                            <div class="col-8 col-12-xsmall">
                                                <input type="email" name="em" id="email" placeholder="Email Address" />
                                            </div>
                                            <p></p>
                                            <div class="col-2 col-0-xsmall"></div>
                                        </div>
                                        <div class="row gtr-uniform">
                                            <div class="col-2 col-0-xsmall"></div>
                                            <div class="col-8 col-12-xsmall">
                                                <input type="password" name="pword" id="password" placeholder="Password" />
                                            </div>
                                            <div class="col-2 col-0-xsmall"></div>
                                        </div>
                                    
                                        <p></p>
                                        <ul class="actions special">
                                            <input name="login_b" class="button fit primary" type="submit" value="Log In">
                                            <li><a href="#" class="button fit" onclick = "Registering()">I'm New!</a></li>
                                         </ul>
                                    </form>
                                </div>
                            </section>
                            
                        </div>
                        <a href="#one" class="more scrolly">Learn More</a>
                    </section>

                <!-- Footer -->
                    <footer id="footer">
                        <ul class="icons">
    
                        <ul class="copyright">
                            <li>&copy; Emily deGrandpré, Ifeanyi Ene, Maya Pandurangan, Omar Alhait </li>
                        </ul>
                    </footer>

            </div>

        <!-- Scripts -->
            <script src="assets/js/jquery.min.js"></script>
            <script src="assets/js/jquery.scrollex.min.js"></script>
            <script src="assets/js/jquery.scrolly.min.js"></script>
            <script src="assets/js/browser.min.js"></script>
            <script src="assets/js/breakpoints.min.js"></script>
            <script src="assets/js/util.js"></script>
            <script src="assets/js/main.js"></script>

            <script>
            <?php
              if(isset($_GET['preset'])){
                echo "Registering();";
              }

              if(($fn_errors != "") || ($ln_errors != "") || ($u_errors != "") || ($e_errors != "") || ($p_errors != "") || ($cp_errors != "")){
                echo "Registering();";
              }

              if(($e_errors_log != "") || ($p_errors_log != "")){
                echo "LoggingIn();";
              }
            ?>

            function LoggingIn() {
                var x = document.getElementsByTagName("showy");
                var y = document.getElementsByTagName("hidden");
                for(var i=0; i<x.length; i++) {
                    x[i].style.display = "block";
                }
                for(var j=0; j<y.length; j++) {
                    y[j].style.display = "none";
                }
            }

            function Registering() {
                var y = document.getElementsByTagName("showy");
                var x = document.getElementsByTagName("hidden");
                for(var i=0; i<x.length; i++) {
                    x[i].style.display = "block";
                }
                for(var j=0; j<y.length; j++) {
                    y[j].style.display = "none";
                }
            }
            </script>

    </body>
</html>