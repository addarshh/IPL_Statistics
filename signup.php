<?php 
  session_start();
  if($_SESSION['loggedin']==FALSE) {
    header('Location: home.php');
  }
  $uname = $_SESSION["usname"];
  include 'func2.php';
?>
<!DOCTYPE HTML>
<html>

<head>
  <title>Register</title>
  <meta name="description" content="website description" />
  <meta name="keywords" content="website keywords, website keywords" />
  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
  <link rel="stylesheet" type="text/css" href="style/style.css" title="style" />
</head>

<body>
  <div id="main">
    <div id="header">
      <div id="logo">
        <div id="logo_text">
          <!-- class="logo_colour", allows you to change the colour of the text -->
          <h1><a href="home.php">IPL</a></h1>
        </div>
      </div>
      <div id="menubar">
        <ul id="menu">
          <!-- put class="selected" in the li tag for the selected page - to highlight which page you're on -->
          <li><a href='login_home.php'>Home</a></li>
          <li><a href='ateam.php'>Add Team</a></li>
          <li><a href='aplayer.php'>Add Player</a></li>
          <li><a href='upload_match.php'>Upload Match</a></li>
          <!--<li><a href='amatch.php'>Add Match</a></li>-->
          <li class = 'selected'><a href='signup.php'>Sign Up</a></li>
          <li><a href='home.php'>Logout</a></li>
        </ul>
      </div>
    </div>
    <div id="site_content">
      <div id="content">
        <!-- insert the page content here -->
        <h1>Register</h1>
        <h4>Enter your username and password : </h4>
        <form action="signup.php" method="post">
          <div class="form_settings">
            <p><input class="contact" type="text" name="usname" placeholder = "Username" value="" required/></p><br/>
            <p><input class="contact" type="password" name="pwd" placeholder = "Password" value="" required/></p><br/>
            <p style="padding-top: 15px"><input type="submit" value="SIGNUP" /></p>
          </div>
        </form>
        
        <?php
        if ( isset($_POST['usname'], $_POST['pwd']) ) {
          $usname=$_POST["usname"];
          $pwd = password_hash($_POST["pwd"],PASSWORD_BCRYPT);
          $query = "SELECT uname FROM login WHERE uname ='$usname'";
          $row = getOutputOfQuery($query);
          if($row != FALSE){
              die( "<p>Username already exist</p>");
          } 
          $query= "INSERT into login values('$usname','$pwd')";
          insertQuery($query);
          echo "Login succesfully created";
        }
        ?>
      </div>
    </div>
    <div id="footer">
      Made by Chinmaya Singh, Vivek Singal and Adarsh Agarwal
    </div>
  </div>
</body>
</html>