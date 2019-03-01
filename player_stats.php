<?php include 'func2.php'; ?>
<!DOCTYPE HTML>
<html>

<head>
  <title>IPL-Home</title>
  <meta name="description" content="website description" />
  <meta name="keywords" content="website keywords, website keywords" />
  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
  <link rel="stylesheet" type="text/css" href="./style/style.css" title="style" />
</head>

<body>
  <div id="main">
    <div id="header">
      <div id="logo">
        <div id="logo_text">
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
          <li class="selected"><a href="player_stats.php">Player Stats</a>
          <li><a href="login.php">Login</a></li>
          </li>
        </ul>
      </div>
    </div>
    <div id="site_content">
      <div id="content">
        <h3>Search a player by their name</h3>
        <h4>Search</h4>
        <form method="get" action="player_stats.php" id="search_form">
          <p>
            <input class="search" type="text" name="search_field" placeholder="Enter keywords....." />
            <input name="search" type="image" style="border: 0; margin: 0 0 -9px 5px;" src="style/search.png" alt="Search" title="Search" />
          </p>
        </form>

      <?php
       // Connecting, selecting database
        $dbconn = pg_connect("host=localhost port=5432 dbname=ipl user=postgres password=col362")
        or die('Could not connect: ' . pg_last_error());
        $query='';
        if(isset($_GET["search_field"])) {
           $query="select Player_Name,Player_Id from player where Player_Name ILIKE '%".$_GET['search_field']."%'";
        }
        if($query!='') {
          $result = pg_query($query) or die('Query failed : ' . pg_last_error());
          echo '<form action="player_stats.php" method="get">';
          echo '<select name="playerId" size='.pg_num_rows($result).' >';
          while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            echo "<option value='".$line['player_id'].$line['player_name']."'>".$line['player_name']."</option>"; 
          }
          echo "</select>";
            echo "<br>";
            echo '<input type="submit" name="player">';
            echo "</form>";
        }
        
        if(isset($_GET['playerId'])) {
          $str=$_GET['playerId'];
          $i=0;
          for (; $i < strlen($str); $i++){
              if(!is_numeric($str[$i])) { 
                break;
              }
          }
          $player_id=substr($str,0,$i);
          $player_name=substr($str,$i);

          //finding matches played by the player
          $total_matches=0;
          $result = pg_query("select count(distinct match_id) as total_matches from player_match where player_id=".$player_id);
          while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            $total_matches=$line['total_matches'];
          }

          $total_runs=0;
          $balls_played=0;
          $num_6=0;
          $num_4=0;
          $result = pg_query("select sum(runs_scored) as total_runs,count(ball_id) as balls_played,count(ball_id) filter(where runs_scored=4) as num_4,count(ball_id) filter(where runs_scored=6) as num_6,count(ball_id) filter(where out_type not in ('Not Applicable','retired hurt')) as num_outs from ball where striker=".$player_id);
          while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            $total_runs=floatval($line['total_runs']);
            $balls_played=floatval($line['balls_played']);
            $num_outs=floatval($line['num_outs']);
            $num_4=$line['num_4'];
            $num_6=$line['num_6'];
          }

          $highest_score=0;
          $average_runs=0;
          $num_50=0;
          $num_100=0;
          $result=pg_query("select max(runs_per_match) as highest_score, Round(AVG(runs_per_match),2) as average_runs,count(runs_per_match) filter(where runs_per_match>=50 and runs_per_match<100) as num_50, count(runs_per_match) filter(where runs_per_match>=100) as num_100 from (select sum(runs_scored) as runs_per_match from ball where ball.striker=$player_id group by match_id ) as x");
          while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
            $highest_score=$line['highest_score'];
            $average_runs=$line['average_runs'];
            $num_50=$line['num_50'];
            $num_100=$line['num_100'];
          }
          $strike_rate=0;
          if($balls_played>0) {
            $strike_rate=round(($total_runs*100.0)/$balls_played,2);
          }
          $average_runs=NAN;
          if($num_outs>0) {
            $average_runs=round($total_runs/$num_outs,2);
          }
          
          echo "<table>
            <caption>Batting Profile</caption>
            <tr><th>Name</th><th>Innings</th><th>Runs</th><th>HS</th><th>Avg</th><th>BF</th><th>SR</th><th>100</th><th>50</th><th>4s</th><th>6s</th></tr>
            <tr><td>$player_name</td><td>$total_matches</td><td>$total_runs</td><td>$highest_score</td><td>$average_runs</td><td>$balls_played</td><td>$strike_rate</td><td>$num_100</td><td>$num_50</td><td>$num_4</td><td>$num_6</td></tr>";
          
          //number of overs
          $query="select count(*) as num_over,count(*) filter(where numWicketsPerOver=4) as num_4w,count(*) filter(where numWicketsPerOver=3) as num_3w from (select match_id,over_id,count(ball_id) 
            filter(where out_type Not in ('Not Applicable','retired hurt','obstructing the field','run out')) as numWicketsPerOver
            from ball where bowler=$player_id group by match_id,over_id) as x";
          $row=getRowFromQuery($query);
          $num_over=(float)$row['num_over'];
          $num_3w=$row['num_3w'];
          $num_4w=$row['num_4w'];

          //number of runs and num of wickets
          $query="select sum(runs_scored) + sum(extra_runs) filter(where extra_type NOT In ('penalty')) as total_runs,count(out_type) filter(where out_type Not in ('Not Applicable','retired hurt','obstructing the field','hit wicket','run out')) as num_wickets,count(ball_id) as num_balls,count(distinct match_id) as num_matches from ball where bowler=$player_id";
          $row=getRowFromQuery($query);
          $total_runs=(float)$row['total_runs'];
          $num_wickets=(float)$row['num_wickets'];
          $num_balls=(float)$row['num_balls'];
          $num_matches=$row['num_matches'];
          
          $avg=0;
          if($num_wickets>0) {
            $avg=round($total_runs/$num_wickets,2);
          }
          $econ=0;
          if($num_over>0) {
            $econ=round($total_runs/$num_over,2);
          }
          $sr=0;
          if($num_wickets>0) {
            $sr=round($num_balls/$num_wickets,2);
          }
          echo "<table>
          <caption>Bowling Profile</caption>
          <tr><th>Name</th><th>Innings</th><th>Overs</th><th>Runs</th><th>Wckts</th><th>AVG</th><th>Econ</th><th>SR</th><th>3w</th><th>4w</th></tr>
            <tr><td>$player_name</td><td>$num_matches</td><td>$num_over</td><td>$total_runs</td><td>$num_wickets</td><td>$avg</td><td>$econ</td><td>$sr</td><td>$num_3w</td><td>$num_4w</td></tr>
          ";
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
