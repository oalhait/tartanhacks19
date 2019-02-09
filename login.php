<?php
  //$con include
  require 'config.php';

  function getRandomString($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $string = '';

    for ($i = 0; $i < $length; $i++) {
        $string .= $characters[mt_rand(0, strlen($characters) - 1)];
    }

    return $string;
  }
  
  if(isset($_SESSION['username'])){
    header('Location: index.php?tit=Home');
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

<!DOCTYPE html>
<html>
<head>
  <title>
    theResearchApp | Login
  </title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    @font-face {
      font-family: optimal;
      src: url(avnext.otf); /* Safari, Android, iOS */
    }

    @font-face {
      font-family: orkney;
      src: url(orkney.ttf); /* Safari, Android, iOS */
    }

    body, html {
        height: 100%;
        margin: 0;
        max-width: 100%;
        overflow-x: hidden;
    }

    .bg { 
        position: fixed;
        left: 0;
        top: 0;
        z-index: 1;
        /* The image used */
        background-image: url("research-background.jpg");

        /* Full height */
        height: 100%; 
        width: 100%;

        /* Center and scale the image nicely */
        background-position: center;
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-size: cover;
    }

    .bg2 { 
        position: absolute;
        left: 0;
        top: 0;
        z-index: 1;
        width: 100%;
    }

    .logo_tag {
      width: 80%;
      max-width: 500px;
      background-color: #008ce2;
      margin: 0 auto;
      margin-top: 10%;
      margin-bottom: 10px;
      z-index: 2;
      color: white;
      font-size: 35px;
      font-family: optimal;
      text-align: center;
      box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.4), 0 6px 20px 0 rgba(0, 0, 0, 0.8);
    }

    input {
      width: calc(100% - 10px);
      border: none;
      height: 50px;
      padding-left: 10px;
      margin-top: 10px;
      z-index: 2;
      font-size: 20px;
      font-family: orkney;
      box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.4);
    }

    form {
      margin: 0 auto;
      margin-bottom: 10%;
      width: 80%;
      max-width: 500px;
      display: block;
    }

    span {
      display: block;
      width: 100%;
      color: darkred;
      text-align: center;
      font-weight: bold;
      margin-top: 5px;
    }

    hidden {
      display: none;
    }

    showy {
      display: block;
    }

    @media screen and (max-width: 1080px) {
      input {
        -webkit-appearance: none;
        border-radius: 0;
        padding-right: 0;
      }

      input[type="submit"] {
        margin-bottom: 50px;
      }
    }
  </style>
  <link rel = "icon" type = "image/png" href = "preston.png" />
</head>

<body style="margin: 0; font-family: orkney;">
  <script type="text/javascript">
    <?php
      if(isset($_GET['alert'])){
        echo "alert('" . $_GET['alert'] . "');";
      }
    ?>
  </script>

  <div class="bg">

  </div>

  <div class="bg2">
  <div class="logo_tag">
    <img src="preston.png" height=50px width=40px style="display: inline-block; margin-right: 30px; margin-top: 4px; vertical-align: text-bottom; border-radius: 10px;"><b>theResearchApp</b>
  </div>

  <form method="POST">
    <hidden>
    <input type="text" name="f_name" placeholder="First Name" value="<?php
        if(isset($_SESSION['f_name'])){
          echo $_SESSION['f_name'];
        }
      ?>"><br><span><?php echo $fn_errors; ?></span>      
    <input type="text" name="l_name" placeholder="Last Name" value="<?php
        if(isset($_SESSION['l_name'])){
          echo $_SESSION['l_name'];
        }
      ?>"><br><span><?php echo $ln_errors; ?></span>  
    <input type="text" name="uname" placeholder="Display Name" value="<?php
        if(isset($_SESSION['r_username'])){
          echo $_SESSION['r_username'];
        }
      ?>"><br><span><?php echo $u_errors; ?></span>
      
      </hidden>
    <input type="email" name="em" placeholder="E-mail Address" value="<?php
        if(isset($_SESSION['email'])){
          echo $_SESSION['email'];
        }
      ?>"><br>
    <hidden><span><?php echo $e_errors; ?></span></hidden>
    <showy><span><?php echo $e_errors_log; ?></span></showy>
    <input type="password" name="pword" placeholder="Password"><br>
    <showy><span><?php echo $p_errors_log; ?></span></showy>
    <hidden><span><?php echo $p_errors; ?></span>
    <input type="password" name="pword2" placeholder="Confirm Password"><br><span><?php echo $cp_errors; ?></span></hidden>
    <showy>
      <a href="change_password.php" style="text-decoration: none;"><div style="margin: 0 auto; min-height: 20px; background-color: black; width: 100%; max-width: 500px; padding-top: 10px; padding-bottom: 5px; text-align: center; color: white; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.4), 0 6px 20px 0 rgba(0, 0, 0, 0.8); overflow: hidden; margin-bottom: 2px; margin-top: 18px;">
        <font size=4>Help! I Forgot my Password!</font>
      </div></a>
    <input type="submit" name="login_b" value="Log In" style="background-color: #008ce2; color: white; width: 49%; float: left;">
    <input type="button" name="new_user" onclick="Registering()" value="I'm New" style="background-color: #008ce2; color: white; width: 49%; float: right;"><br></showy>
    <hidden><input type="submit" name="register_b" value="Register" style="background-color: #008ce2; color: white; width: 65%; float: left;">
      <input type="button" name="back" onclick="LoggingIn()" value="Back" style="background-color: black; color: white; width: 33%; float: right;">
    </hidden>
  </form>
  </div>
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