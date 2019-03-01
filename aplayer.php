<?php 
  session_start(); 
  if($_SESSION['loggedin']==FALSE) {
    header('Location: home.php');
  }
  $uname = $_SESSION["usname"];
  include 'func.php';
  ?>
<!DOCTYPE HTML>
<html>

<head>
  <title>Add-Player</title>
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

          <li><a href='login_home.php'>Home</a></li>
          <li><a href='ateam.php'>Add Team</a></li>
          <li class = 'selected'><a href='aplayer.php'>Add Player</a></li>
          <li><a href='upload_match.php'>Upload Match</a></li>
          <!--<li><a href='amatch.php'>Add Match</a></li>-->
          <li><a href='signup.php'>Sign Up</a></li>
          <li><a href='home.php'>Logout</a></li>
        
        </ul>
      </div>
    </div>
    <div id="site_content">
      <div id="content">
        <!-- insert the page content here -->
        
        <h1>Add Player</h1>
        <form action="aplayer.php" method="get">
          <div class="form_settings">
            <input type="text" name="pname" placeholder = "Name of the Player" value="" required /><br>
            <input name = "pdob" placeholder="Date of Birth" type="text" onfocus="(this.type='date')" onblur="(this.type='text')" /><br>
            <select name="pbat" required>
              <option selected="selected" disabled="disabled" value="">Select Batting Hand</option>
              <option value="Right-hand bat">Right-Hand Bat</option>
              <option value="Left-hand bat">Left-Hand Bat</option>
            </select><br>
            <?php create_dropdown('pbowl','SELECT distinct(bowling_skill) from player where bowling_skill is not null order by bowling_skill',false,'Select Bowling Style'); ?>
            <?php create_dropdown('pcountry','SELECT distinct(country_name) from player order by country_name',false,'Select Country'); ?>
      

            <p style="padding-top: 15px"><input type="submit" value="SUBMIT" /></p>
          </div>
        </form>

        <?php
        // Connecting, selecting database
        $dbconn = pg_connect("host=localhost port=5432 dbname=ipl user=postgres password=col362")
        or die('Could not connect: ' . pg_last_error());
        if(isset($_GET["pname"])){
          $pname = $_GET["pname"];
          $pdob = $_GET["pdob"];
          $pbat = $_GET["pbat"];
          $pbowl = $_GET["pbowl"];
          $pcountry = $_GET["pcountry"];
          $id = 1 + pg_fetch_all(pg_query('SELECT MAX(player_id) FROM player'))[0]['max'];
          $query = "INSERT INTO player VALUES ($id,'$pname','$pdob','$pbat','$pbowl','$pcountry')";
          echo $query;
          $result = pg_query($query) or die('Oops!! Something is wrong - ' . pg_last_error());
          pg_free_result($result);
        }
        // Closing connection
        pg_close($dbconn);
        ?>

      </div>
    </div>
    <div id="footer">
      Made by Adarsh Agrawal, Chinmaya Singh, Vivek Singal
    </div>
  </div>
</body>
</html>
