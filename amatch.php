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
  <title>Add-Match</title>
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
          <li><a href='aplayer.php'>Add Player</a></li>
          <li><a href='upload_match.php'>Upload Match</a></li>
          <li class = 'selected'><a href='amatch.php'>Add Match</a></li>
          <li><a href='signup.php'>Sign Up</a></li>
          <li><a href='home.php'>Logout</a></li>
        
        </ul>
      </div>
    </div>
    <div id="site_content">
      <div id="content">
        <!-- insert the page content here -->
        
        <h2>Add Match</h2>
        <form action="amatch.php" method="get">
          <div class="form_settings">
            <?php create_dropdown('t_name1','SELECT team_name from team order by team_name',false,'Select Team1'); ?>
            <?php create_dropdown('t_name2','SELECT team_name from team order by team_name',false,'Select Team2'); ?>
            <input name = "tdate" placeholder="Date" type="text" onfocus="(this.type='date')" onblur="(this.type='text')" required /><br>
            <input type="text" name="tyear" placeholder="Season" value="" required /><br>
            <?php create_dropdown('tvenue','SELECT venue_name from venue order by venue_name',false,'Select Venue'); ?>
            <?php create_dropdown('tcity','SELECT DIStiNCT city_name from venue order by city_name',false,'Select City'); ?>
            <?php create_dropdown('tcountry','SELECT DISTINCT country_name from venue order by country_name',false,'Country'); ?>
            <?php create_dropdown('toss','SELECT team_name from team order by team_name',false,'Toss won by:'); ?>
            <select name="t_toss_choose" required>
              <option selected="selected" disabled="disabled" value="">Toss winner chose to</option>
              <option value="bat">Bat</option>
              <option value="field">Field</option>
            </select><br>
            <p style="padding-top: 15px"><input type="submit" value="SUBMIT" /></p>
          </div>
        </form>

        <?php
        // Connecting, selecting database
        if(isset($_GET["t_name1"])){
          $t_name1 = $_GET["t_name1"];
          $t_name2 = $_GET["t_name2"];
          $tdate = $_GET["tdate"];
          $tyear = $_GET["tyear"];
          $tvenue = $_GET["tvenue"];
          $tcity = $_GET["tcity"];
          $tcountry = $_GET["tcountry"];
          $toss = $_GET["toss"];
          $t_toss_choose = $_GET["t_toss_choose"];
          //
          $tvenue = pg_fetch_all(pg_query("SELECT venue_id FROM venue WHERE venue_name = '$tvenue'"))[0]['venue_id'];
          $id = 1 + pg_fetch_all(pg_query('SELECT MAX(match_id) FROM match'))[0]['max'];
          $query = "INSERT INTO match(match_id, team1, team2, match_date, season_year, venue_id, toss_winner, toss_name, outcome_type) VALUES ($id,'$t_name1','$t_name2','$tdate',$tyear,$tvenue,'$toss','$t_toss_choose','Live');\n";
            //  outcome_type is set to 'Live'
          $my_file = 'file.txt';
          $handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
          fwrite($handle, $query);
          fclose($handle);
          $_SESSION["match_id"] = $id;
          $_SESSION["team1"] = $t_name1;
          $_SESSION["team2"] = $t_name2;
          echo "<script>window.location.href='http://localhost/ipl/start_match.php';</script>";
        }
        // Closing connection
        ?>

      </div>
    </div>
    <div id="footer">
      Made by Adarsh Agrawal, Chinmaya Singh, Vivek Singal
    </div>
  </div>
</body>
</html>
