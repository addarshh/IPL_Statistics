<?php
session_start();
?>
<!DOCTYPE HTML>
<html>

<head>
  <title>Login</title>
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
          <li><a href="home.php">Home</a></li>
          <li><a href="teams.php">Teams</a></li>
          <li><a href="players.php">Players</a></li>
          <li><a href="matches.php">Matches</a></li>
          <li><a href="stats.php">Interesting Stats</a></li>
          <li><a href="player_stats.php">Player Stats</a></li>
          <li class = "selected"><a href="login.php">Login</a></li>
        </ul>
      </div>
    </div>
    <div id="site_content">
      <div id="content">
        <!-- insert the page content here -->
        <h1>Login Page</h1>
        <h4>Enter your username and password to login: </h4>
        <form action="login.php" method="post">
          <div class="form_settings">
            <p><input class="contact" type="text" name="usname" placeholder = "Username" value="" required/></p><br/>
            <p><input class="contact" type="password" name="pwd" placeholder = "Password" value="" required/></p><br/>
            <p style="padding-top: 15px"><input type="submit" value="SUBMIT" /></p>
          </div>
        </form>

        <?php
        

        $dbconn = pg_connect("host=localhost port=5432 dbname=ipl user=postgres password=col362")
        or die('Could not connect: ' . pg_last_error());
        // used to set a password $hashed = password_hash ( 'col362', PASSWORD_BCRYPT  );

        if(isset($_POST["usname"])){
          $usname = $_POST["usname"]; 
          $pwd = $_POST["pwd"];
          $query = "SELECT password FROM login WHERE uname = '$usname'";
          $result = pg_query($query) or die('Query failed: ' . pg_last_error());
          $row = pg_fetch_all($result);

          if($row == FALSE){
            echo "<p>Username does not exist!! <br/> Try Again";
            }
          else{
            if(password_verify($pwd, $row[0]['password'])){
               $_SESSION['loggedin'] = TRUE;
               $_SESSION['usname'] = $_POST['usname'];
              echo "<script> window.location.assign('login_home.php'); </script>";
            }
            else{
              echo "<p>Your password is wrong! <br/> Try Again";
            }
          }
          pg_free_result($result);
          }
        else{
          $usname = "default";
        }
        pg_close($dbconn);
        ?>
      </div>
    </div>
    <div id="footer">
      Made by Chinmaya Singh, Vivek Singal and Adarsh Agarwal
    </div>
  </div>
</body>
</html>
