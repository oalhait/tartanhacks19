<?php
    include("header.php");

    $prof_id = $user_array['id'];
    if(isset($_GET['id'])){
        $prof_id = $_GET['id'];
    }

    $profile_details_query = mysqli_query($con, "SELECT * FROM users WHERE id = '$prof_id'");
    $profile_array = mysqli_fetch_array($profile_details_query);
?>

        <!-- Page Wrapper -->
            <div id="page-wrapper">

                <!-- Main -->
                    <article id="main">
                        <section class="wrapper style5">
                            <div class="inner">
                                <h3>Profile</h3>
                                <div class="row gtr-uniform">
                                    <!-- user info -->
                                    <div class="col-8 col-12-xsmall">
                                        <div class="row gtr-uniform">
                                            <div class="col-2">
                                                <img src="<?php echo $profile_array['profile_picture']; ?>" alt="profile" width="100%">
                                            </div>
                                            <div class="col-10">
                                                <h2 vertical-align="middle" style="margin-bottom: 0"><?php echo $profile_array['first_name'] . " " . $profile_array['last_name']; ?></h2>
                                                @<?php echo $profile_array['username']; ?>
                                            </div>
                                        </div>
                                        <p>
                                        <div id="userinfo">
                                            <p> <b><h2 style="margin-bottom: 0; padding-bottom: 0">BIO</h2></b><?php echo $profile_array['bio']; ?>
                                            <?php if($prof_id == $user_array['id']){
                                                echo "<br><b><a href='enter_value_textarea.php?id=" . $user_array['id'] . "&pbn=profile&table=users&column=bio' style='text-decoration: none'>Edit Bio</a></b>";
                                            } ?>
                                        </p>
                                            <p> <b>Major</b> &bull; <?php echo $profile_array['major']; ?> 
                                            <?php if($prof_id == $user_array['id']){
                                                echo "<b><a href='' style='text-decoration: none'>Edit Major</a></b>";
                                            } ?>
                                        </p>
                                            <p> <b>Year</b> &bull; <?php echo $profile_array['year']; ?> 
                                            <?php if($prof_id == $user_array['id']){
                                                echo "<b><a href='enter_value_textbox.php?id=" . $user_array['id'] . "&pbn=profile&table=users&column=year' style='text-decoration: none'>Edit Year</a></b>";
                                            } ?>
                                        </p>
                                            <p> <b>Residence</b> &bull; <?php echo $profile_array['residence']; ?> 
                                            <?php if($prof_id == $user_array['id']){
                                                echo "<b><a href='' style='text-decoration: none'>Edit Residence</a></b>";
                                            } ?>
                                        </p>
                                            <p> <b>Skills</b> &bull; <?php echo $profile_array['skills']; ?> 
                                            <?php if($prof_id == $user_array['id']){
                                                echo "<b><a href='enter_value_textarea.php?id=" . $user_array['id'] . "&pbn=profile&table=users&column=skills' style='text-decoration: none'>Edit Skills</a></b>";
                                            } ?>
                                        </p>
                                            <p> <b>Interests</b> &bull; <?php echo $profile_array['interests']; ?> 
                                            <?php if($prof_id == $user_array['id']){
                                                echo "<b><a href='enter_value_textbox.php?id=" . $user_array['id'] . "&pbn=profile&table=users&column=interests'  style='text-decoration: none'>Edit Interests</a></b>";
                                            } ?>
                                        </p>
                                            <p> <b>Student Organizations</b> &bull; <?php echo $profile_array['orgs']; ?> 
                                            <?php if($prof_id == $user_array['id']){
                                                echo "<b><a href='enter_value_textbox.php?id=" . $user_array['id'] . "&pbn=profile&table=users&column=orgs'  style='text-decoration: none'>Edit Student Organizations</a></b>";
                                            } ?>
                                        </p>
                                            <p> <b>Weekly Free Times</b> &bull; <?php echo $profile_array['free_times']; ?> 
                                            <?php if($prof_id == $user_array['id']){
                                                echo "<b><a href='enter_value_textbox.php?id=" . $user_array['id'] . "&pbn=profile&table=users&column=free_times'  style='text-decoration: none'>Edit Weekly Free Times</a></b>";
                                            } ?>
                                        </p>
                                        </div>                                     
                                    </div>
                                    <!-- Calendar -->
                                    <div class="col-4 col-12-xsmall">
                                    </div>
                                </div>
                            </div>
                        </section>
                    </article>

                <!-- Footer -->
                    <footer id="footer">
                        <ul class="icons">
                            <li><a href="#" class="icon fa-twitter"><span class="label">Twitter</span></a></li>
                            <li><a href="#" class="icon fa-facebook"><span class="label">Facebook</span></a></li>
                            <li><a href="#" class="icon fa-instagram"><span class="label">Instagram</span></a></li>
                            <li><a href="#" class="icon fa-dribbble"><span class="label">Dribbble</span></a></li>
                            <li><a href="#" class="icon fa-envelope-o"><span class="label">Email</span></a></li>
                        </ul>
                        <ul class="copyright">
                            <li>&copy; WORK WELL</li><li>Design: EM DEGRANDPRE, OMAR ALHAIT, IFEANYI ENE AND MAYA PANDURANGAN</li>
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