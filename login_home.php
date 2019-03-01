<?php 
  session_start();
  if($_SESSION['loggedin']==FALSE) {
    header('Location: home.php');
  }
?>
<!DOCTYPE HTML>
<html>

<head>
  <title>Login-Home</title>
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
          <h1><a href="login_home.php">IPL</a></h1>
        </div>
      </div>
      <div id="menubar">
        <ul id="menu">
          <!-- put class="selected" in the li tag for the selected page - to highlight which page you're on -->
          <li class = 'selected'><a href='login_home.php'>Home</a></li>
          <li><a href='ateam.php'>Add Team</a></li>
          <li><a href='aplayer.php'>Add Player</a></li>
          <li><a href='upload_match.php'>Upload Match</a></li>
          <!--<li><a href='amatch.php'>Add Match</a></li>-->
          <li><a href='signup.php'>Sign Up</a></li>
          <li><a href='home.php'>Logout</a></li>
        </ul>
      </div>
    </div>
    <div id="site_content">
      <div id="content">
        <?php
        
        $uname = $_SESSION['usname'];
        echo "<h1> Welcome $uname </h1>";
        if(isset($_GET['delfile'])){
          if($_GET["delfile"]=='true'){
            unlink('file.txt');
          }
        }
        ?>

      </div>
    </div>
    <div id="footer">
      Made by Adarsh Agrawal, Chinmaya Singh, Vivek Singal
    </div>
  </div>
</body>
</html>
