<?php 
  session_start(); 
  $uname = $_SESSION["usname"];
  include 'func.php';
  ?>
<!DOCTYPE HTML>
<html>

<head>
  <title>Add Balls in Match</title>
  <meta name="description" content="website description" />
  <meta name="keywords" content="website keywords, website keywords" />
  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
  <link rel="stylesheet" type="text/css" href="style/style2.css" title="style2" />
</head>

<body>
  <div id="main">
    <div id="header">
      <div id="logo">
        <div id="logo_text"><h1>IPL</h1></div>
      </div>
      <div id="menubar">
        <ul id="menu">
          <li><a href='login_home.php?delfile=true'>Back to Home</a></li>
        </ul>
      </div>
    </div>
    <div id="site_content">
      <div id="content">
        <!-- insert the page content here -->
        
        <h2>Add Balls in the Match</h2>
        <form action="start_match.php" method="get">
            <h3>Select Players of Team1</h3>
            <?php
            for ($x = 1; $x <= 11; $x++) {
              create_dropdown_player('team1_player'.$x,'SELECT player_name from player order by player_name','Select Player'.$x);
            }
            ?>
            <h3>Select Players of Team2</h3>
            <?php
            for ($x = 1; $x <= 11; $x++) {
              create_dropdown_player('team2_player'.$x,'SELECT player_name from player order by player_name','Select Player'.$x);
            }
            ?>
            <br><br>
            <p style="padding-top: 15px"><input type="submit" value="SUBMIT" /></p>
        </form>

        <?php
        function idtoname($id) {
          $dbconn = pg_connect("host=localhost port=5432 dbname=ipl user=postgres password=col362") or die('Could not connect: ' . pg_last_error());
          $query = "SELECT player_name FROM player WHERE match_id = $id";
          $res = pg_fetch_all(pg_query($query));
          pg_close($dbconn);
          return $res[0]['player_name'];
        }
        function nametoid($name) {
         $dbconn = pg_connect("host=localhost port=5432 dbname=ipl user=postgres password=col362") or die('Could not connect: ' . pg_last_error());
          $query = "SELECT player_id FROM player WHERE player_name = '".$name."'";
          $res = pg_fetch_all(pg_query($query));
          pg_close($dbconn);
          return $res[0]['player_id'];
        }
        if(isset($_GET["team1_player1"])){
          $handle = fopen('file.txt', 'a') or die('Cannot open file:  '.$my_file);
          $query = "INSERT INTO player_match VALUES(".$_SESSION["match_id"].",".nametoid($_GET["team1_player1"]).",'".$_GET["team1_player1_role"]."','".$_SESSION["team1"]."');\n";
          fwrite($handle, $query);
          $query = "INSERT INTO player_match VALUES(".$_SESSION["match_id"].",".nametoid($_GET["team1_player2"]).",'".$_GET["team1_player2_role"]."','".$_SESSION["team1"]."');\n";
          fwrite($handle, $query);
          $query = "INSERT INTO player_match VALUES(".$_SESSION["match_id"].",".nametoid($_GET["team1_player3"]).",'".$_GET["team1_player3_role"]."','".$_SESSION["team1"]."');\n";
          fwrite($handle, $query);
          $query = "INSERT INTO player_match VALUES(".$_SESSION["match_id"].",".nametoid($_GET["team1_player4"]).",'".$_GET["team1_player4_role"]."','".$_SESSION["team1"]."');\n";
          fwrite($handle, $query);
          $query = "INSERT INTO player_match VALUES(".$_SESSION["match_id"].",".nametoid($_GET["team1_player5"]).",'".$_GET["team1_player5_role"]."','".$_SESSION["team1"]."');\n";
          fwrite($handle, $query);
          $query = "INSERT INTO player_match VALUES(".$_SESSION["match_id"].",".nametoid($_GET["team1_player6"]).",'".$_GET["team1_player6_role"]."','".$_SESSION["team1"]."');\n";
          fwrite($handle, $query);
          $query = "INSERT INTO player_match VALUES(".$_SESSION["match_id"].",".nametoid($_GET["team1_player7"]).",'".$_GET["team1_player7_role"]."','".$_SESSION["team1"]."');\n";
          fwrite($handle, $query);
          $query = "INSERT INTO player_match VALUES(".$_SESSION["match_id"].",".nametoid($_GET["team1_player8"]).",'".$_GET["team1_player8_role"]."','".$_SESSION["team1"]."');\n";
          fwrite($handle, $query);
          $query = "INSERT INTO player_match VALUES(".$_SESSION["match_id"].",".nametoid($_GET["team1_player9"]).",'".$_GET["team1_player9_role"]."','".$_SESSION["team1"]."');\n";
          fwrite($handle, $query);
          $query = "INSERT INTO player_match VALUES(".$_SESSION["match_id"].",".nametoid($_GET["team1_player10"]).",'".$_GET["team1_player10_role"]."','".$_SESSION["team1"]."');\n";
          fwrite($handle, $query);
          $query = "INSERT INTO player_match VALUES(".$_SESSION["match_id"].",".nametoid($_GET["team1_player11"]).",'".$_GET["team1_player11_role"]."','".$_SESSION["team1"]."');\n";
          fwrite($handle, $query);
          $query = "INSERT INTO player_match VALUES(".$_SESSION["match_id"].",".nametoid($_GET["team2_player1"]).",'".$_GET["team2_player1_role"]."','".$_SESSION["team2"]."');\n";
          fwrite($handle, $query);
          $query = "INSERT INTO player_match VALUES(".$_SESSION["match_id"].",".nametoid($_GET["team2_player2"]).",'".$_GET["team2_player2_role"]."','".$_SESSION["team2"]."');\n";
          fwrite($handle, $query);
          $query = "INSERT INTO player_match VALUES(".$_SESSION["match_id"].",".nametoid($_GET["team2_player3"]).",'".$_GET["team2_player3_role"]."','".$_SESSION["team2"]."');\n";
          fwrite($handle, $query);
          $query = "INSERT INTO player_match VALUES(".$_SESSION["match_id"].",".nametoid($_GET["team2_player4"]).",'".$_GET["team2_player4_role"]."','".$_SESSION["team2"]."');\n";
          fwrite($handle, $query);
          $query = "INSERT INTO player_match VALUES(".$_SESSION["match_id"].",".nametoid($_GET["team2_player5"]).",'".$_GET["team2_player5_role"]."','".$_SESSION["team2"]."');\n";
          fwrite($handle, $query);
          $query = "INSERT INTO player_match VALUES(".$_SESSION["match_id"].",".nametoid($_GET["team2_player6"]).",'".$_GET["team2_player6_role"]."','".$_SESSION["team2"]."');\n";
          fwrite($handle, $query);
          $query = "INSERT INTO player_match VALUES(".$_SESSION["match_id"].",".nametoid($_GET["team2_player7"]).",'".$_GET["team2_player7_role"]."','".$_SESSION["team2"]."');\n";
          fwrite($handle, $query);
          $query = "INSERT INTO player_match VALUES(".$_SESSION["match_id"].",".nametoid($_GET["team2_player8"]).",'".$_GET["team2_player8_role"]."','".$_SESSION["team2"]."');\n";
          fwrite($handle, $query);
          $query = "INSERT INTO player_match VALUES(".$_SESSION["match_id"].",".nametoid($_GET["team2_player9"]).",'".$_GET["team2_player9_role"]."','".$_SESSION["team2"]."');\n";
          fwrite($handle, $query);
          $query = "INSERT INTO player_match VALUES(".$_SESSION["match_id"].",".nametoid($_GET["team2_player10"]).",'".$_GET["team2_player10_role"]."','".$_SESSION["team2"]."');\n";
          fwrite($handle, $query);
          $query = "INSERT INTO player_match VALUES(".$_SESSION["match_id"].",".nametoid($_GET["team2_player11"]).",'".$_GET["team2_player11_role"]."','".$_SESSION["team2"]."');\n";
          fwrite($handle, $query);
          fclose($handle);
          echo "<script>window.location.href='http://localhost/ipl/record_match.php';</script>";
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
