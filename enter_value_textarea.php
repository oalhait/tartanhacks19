<?php
  //$con include
  require 'config.php';
  
  if(isset($_SESSION['username'])==0){
    header('Location: index.php');
    exit;
  }

  if(isset($_GET['id']) && isset($_GET['pbn']) && isset($_GET['table']) && isset($_GET['column'])){
    //good to go...
  } else {
    header('Location: explore.php');
    exit;
  }

  $query = "SELECT * FROM " . $_GET['table'] . " WHERE id = '" . $_GET['id'] . "' AND deleted = 'no'";
  $profile_details_query = mysqli_query($con, $query);
  $profile_array = mysqli_fetch_array($profile_details_query);
  $old_value = $profile_array[$_GET['column']];
  
  $txt_value = "";

  if(isset($_POST['update_b'])){
    $txt_value = $_POST['txt_value'];

    $query = "SELECT * FROM " . $_GET['table'] . " WHERE id = '" . $_GET['id'] . "' AND deleted = 'no'";
    $find_attempted_user = mysqli_query($con, $query);
    $attempted_user = mysqli_fetch_array($find_attempted_user);
    $num_rows = mysqli_num_rows($find_attempted_user);

    if($num_rows > 0){
        $user_update_query = mysqli_query($con, "UPDATE " . $_GET['table'] . " SET " . $_GET['column'] . " = '$txt_value' WHERE id = '" . $_GET['id'] . "'");

        header('Location: ' . $_GET['pbn'] . '.php?id=' . $_GET['id']);
        exit;
    } else {
      header('Location: explore.php');
      exit;
    }
  }
?>

<!DOCTYPE HTML>
<html>
    <head>
        <title>Work Well | Edit <?php echo ucfirst($_GET['pbn']); ?></title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
        <link rel="icon" type="image/png" href ="logo.png" />
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
                            <h2 style="color: white">EDIT <?php echo strtoupper($_GET['pbn']); ?></h2>
                            <section>
                                <div id="loginBoxes">
                                    <form method="post">
                                        <div class="row gtr-uniform">
                                            <div class="col-2 col-0-xsmall"></div>
                                            <div class="col-8 col-12-xsmall">
                                                <textarea name="txt_value" id="textbox" rows="4" style="resize: none" placeholder="<?php echo ucfirst($_GET['column']); ?>"><?php echo $old_value; ?></textarea>
                                            </div>
                                            <p></p>
                                            <div class="col-2 col-0-xsmall"></div>
                                        </div>
                                    
                                        <p></p>
                                        <ul class="actions special">
                                            <input name="update_b" class="button fit primary" type="submit" value="UPDATE <?php echo strtoupper($_GET['column']); ?>">
                                            <li><a href="<?php echo $_GET['pbn']; ?>.php?id=<?php echo $_GET['id']; ?>" class="button fit">Back</a></li>
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

    </body>
</html>