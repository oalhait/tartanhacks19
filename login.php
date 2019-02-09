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
        <title>Work Well | Login</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
        <link rel="stylesheet" href="assets/css/main.css" />
        <noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
        <script src="assets/js/main.js"></script>
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
                                            <li><a href="index.php" class="button fit">Back</a></li>
                                         </ul>
                                    </form>
                                </div>
                            </section>
                            
                        </div>
                        <a class="more scrolly">Learn More</a>
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

            <script type="text/javascript">
                <?php
                  if(isset($_GET['alert'])){
                    echo "alert('" . $_GET['alert'] . "');";
                  }
                ?>
            </script>

    </body>
</html>