<!DOCTYPE HTML>
<html>

<head>
  <title>IPL-Scorecard</title>
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
          <li class = "selected"><a href="matches.php">Matches</a></li>
          <li><a href="stats.php">Interesting Stats</a></li>
          <li><a href="player_stats.php">Player Stats</a></li>
          <li><a href="login.php">Login</a></li>
        </ul>
      </div>
    </div>
    <div id="site_content">
      <div id="content">
        <!-- insert the page content here -->
        <h1>Score Card</h1>
        <?php
        // Connecting, selecting database
      $dbconn = pg_connect("host=localhost port=5432 dbname=ipl user=postgres password=col362")
        or die('Could not connect: ' . pg_last_error());

        $match_id = $_GET["match_id"];
        //echo "Match ID is ".$match_id;
        $inn = $_GET["inn"];


        $query = "select outcome_type from match where match_id = $match_id";
        
        $result = pg_query($query) or die('Query failed: ' . pg_last_error());
        $ans = pg_fetch_all($result)[0]['outcome_type'];
        if($ans == 'Abandoned'){
            echo "<h2>The match was Abandoned due to rain and not even a ball was played</h2>";
            die();
        }
        elseif($ans == 'Superover' OR $ans == 'Tied'){
            if($inn == 1){
                echo "<h6 align='right'><a href='match_card.php?match_id=$match_id&inn=4'>Click Here to go to Superover 2</a></h6>";
            }
            else{
                echo "<h6 align = 'right'><a href='match_card.php?match_id=$match_id&inn=3'>Click Here to go to Superover 1</a></h6>";
            }
        }



        echo "<h1><center>Innings $inn</center></h1>";
        if($inn == 1){
          echo "<h6 align='right'><a href='match_card.php?match_id=$match_id&inn=2'>Click Here to go to Innings 2</a></h6>";
        }
        else{
          echo "<h6 align = 'right'><a href='match_card.php?match_id=$match_id&inn=1'>Click Here to go to Innings 1</a></h6>";
        }
        $query = "select team_name from team where team_id = (SELECT team_batting from team_match where match_id = $match_id and innings_no =$inn)";
        
        $result = pg_query($query) or die('Query failed: ' . pg_last_error());
        $ans = pg_fetch_all($result)[0]['team_name'];
        echo "<center><h3>Batting: $ans</h3></center>";
        pg_free_result($result);


        $query = "create view scard_inni_1_allbat as select distinct player.player_name as Bats_name from ball, player where match_id = $match_id and (ball.striker = player.player_id or ball.non_striker = player.player_id) and innings_no = $inn";

        $result = pg_query($query) or die('Query failed: ' . pg_last_error());
        pg_free_result($result);

        $query = "create view scard_inni_1_bat_p1 as select player.player_name as Bats_name, sum(runs_scored) as runs, count(runs_scored) as ball,(round(round(sum(runs_scored),2)/round(count(runs_scored),2)*100,2)) as srate,striker_batting_position from ball, player where match_id = $match_id and ball.striker = player.player_id and ( extra_type = 'No Extras' or extra_type = 'legbyes' or extra_type = 'byes' or extra_type = 'noballs' ) and innings_no = $inn group by player.player_name, striker_batting_position order by striker_batting_position";

        $result = pg_query($query) or die('Query failed: ' . pg_last_error());
        pg_free_result($result);

        $query = "create view scard_inni_1_bat_p2 as select scard_inni_1_allbat.Bats_name ,n4, n6 from scard_inni_1_allbat left join ( select distinct player_name as pname, count(*) as n4 from ball, player where match_id = $match_id and player_id = striker and innings_no = $inn and runs_scored = 4 group by pname) as temp2 on temp2.pname = scard_inni_1_allbat.Bats_name left join ( select distinct player_name as pname, count(*) as n6 from ball, player where match_id = $match_id and player_id = striker and innings_no = $inn and runs_scored = 6 group by pname) as temp3 on temp3.pname = scard_inni_1_allbat.Bats_name;";

        $result = pg_query($query) or die('Query failed: ' . pg_last_error());
        pg_free_result($result);

        $query = "create view scard_inni_1_bat_p3 as select player_name, out_type from ball, player where match_id = $match_id and innings_no = $inn and ((ball.striker = player.player_id and out_type <> 'Not Applicable' and out_type <> 'run out') or (ball.non_striker = player.player_id and out_type = 'run out'));";

        $result = pg_query($query) or die('Query failed: ' . pg_last_error());
        pg_free_result($result);

        
        $query = "select scard_inni_1_allbat.Bats_name, CASE WHEN out_type IS NULL THEN 'Not Out' ELSE out_type END AS out_type,CASE WHEN runs IS NULL THEN 0 ELSE runs END AS runs,CASE WHEN ball IS NULL THEN 0 ELSE ball END AS ball, CASE WHEN n4 IS NULL THEN 0 ELSE n4 END AS n4,CASE WHEN n6 IS NULL THEN 0 ELSE n6 END AS n6, CASE WHEN srate IS NULL THEN 0 ELSE srate END AS srate from scard_inni_1_allbat left join scard_inni_1_bat_p3 on scard_inni_1_bat_p3.player_name = scard_inni_1_allbat.Bats_name left join scard_inni_1_bat_p2 on scard_inni_1_bat_p2.Bats_name = scard_inni_1_allbat.Bats_name left join scard_inni_1_bat_p1 on scard_inni_1_bat_p1.Bats_name = scard_inni_1_allbat.Bats_name order by scard_inni_1_bat_p1.striker_batting_position";

        //echo $query;
        $result = pg_query($query) or die('Query failed: ' . pg_last_error());

        echo "<table>\n";
        echo "<tr><th>Batsman</th><th>Out</th><th>Runs Scored</th><th>Balls Played</th><th>Fours Hit</th><th>Sixes Hit</th><th>Strike Rate</th></tr>";
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
          echo "\t<tr>\n";
          foreach ($line as $col_value) {
            echo "\t\t<td>$col_value</td>\n";
            }
          echo "\t</tr>\n";
        }
        echo "</table>\n";
        pg_free_result($result);

        $query = "DROP VIEW IF EXISTS scard_inni_1_bat_p1, scard_inni_1_allbat, scard_inni_1_bat_p2, scard_inni_1_bat_p3 CASCADE;";

        //echo $query;
        $result = pg_query($query) or die('Query failed: ' . pg_last_error());
        pg_free_result($result);

        include 'team_card_q.php';



        $query = "select team_name from team where team_id = (SELECT team_bowling from team_match where match_id = $match_id and innings_no = $inn)";

        //echo $query;
        $result = pg_query($query) or die('Query failed: ' . pg_last_error());
        $ans = pg_fetch_all($result)[0]['team_name'];
        echo "<center><h1>Bowling: $ans</h1></center>";
        pg_free_result($result);

        $query = "create view scard_bowl_inni1_bowlorder as select distinct bowler, min(over_id) as bowl_order from ball where match_id = $match_id and innings_no = $inn group by bowler order by bowl_order";

        //echo $query;
        $result = pg_query($query) or die('Query failed: ' . pg_last_error());
        pg_free_result($result);

        $query = "create view scard_bowl_nball_inni1_p1 as select bowler, mod(count(*), 6) as balls from ball where match_id = $match_id and innings_no = $inn and ( extra_type = 'No Extras' or extra_type = 'legbyes' or extra_type = 'byes') group by bowler, over_id order by over_id";

        //echo $query;
        $result = pg_query($query) or die('Query failed: ' . pg_last_error());
        pg_free_result($result);

        $query = "create view scard_bowl_nball_inni1_nover as select bowler, count(*) as nover from scard_bowl_nball_inni1_p1 where balls = 0 group by bowler";

        //echo $query;
        $result = pg_query($query) or die('Query failed: ' . pg_last_error());
        pg_free_result($result);

        $query = "create view scard_bowl_nball_inni1_nball as select distinct bowler , max(balls) as nballs from scard_bowl_nball_inni1_p1 group by bowler";

        //echo $query;
        $result = pg_query($query) or die('Query failed: ' . pg_last_error());
        pg_free_result($result);

        $query = "create view scard_bowl_inni1_runs_p1 as select bowler, sum(extra_runs) as nerun from ball where match_id = $match_id and innings_no = $inn and (extra_type = 'wides' or extra_type = 'noballs') group by bowler";

        //echo $query;
        $result = pg_query($query) or die('Query failed: ' . pg_last_error());
        pg_free_result($result);

        $query = "create view scard_bowl_inni1_runs_p2 as select bowler, sum(runs_scored) as nrun from ball where match_id = $match_id and innings_no = $inn group by bowler";

        //echo $query;
        $result = pg_query($query) or die('Query failed: ' . pg_last_error());
        pg_free_result($result);

        $query = "create view scard_bowl_inni1_nwickets as  select distinct bowler, count(out_type) as nwickets from ball where match_id = $match_id and innings_no = $inn and (out_type = 'caught' or out_type = 'Keeper Catch' or out_type = 'caught and bowled'or out_type = 'stumped' or out_type = 'lbw' or out_type = 'bowled') group by bowler";

        //echo $query;
        $result = pg_query($query) or die('Query failed: ' . pg_last_error());
        pg_free_result($result);

        $query = "create view scard_bowl_inni1_n4 as select distinct bowler as pname, count(*) as n4 from ball where match_id = $match_id and innings_no = $inn and runs_scored = 4 group by pname";

        //echo $query;
        $result = pg_query($query) or die('Query failed: ' . pg_last_error());
        pg_free_result($result);

        $query = "create view scard_bowl_inni1_n6 as select distinct bowler as pname, count(*) as n6 from ball where match_id = $match_id and innings_no = $inn and runs_scored = 6 group by pname";

        //echo $query;
        $result = pg_query($query) or die('Query failed: ' . pg_last_error());
        pg_free_result($result);

        $query = "create view scard_bowl_inni1_n0 as select distinct bowler as pname, count(*) as n0 from ball where match_id = $match_id and innings_no = $inn and runs_scored = 0 and ( extra_type = 'No Extras' or extra_type = 'legbyes' or extra_type = 'byes') group by pname;";

        //echo $query;
        $result = pg_query($query) or die('Query failed: ' . pg_last_error());
        pg_free_result($result);

        $query = "create view scard_bowl_inni1_nwide as select distinct bowler as pname, sum(extra_runs) as nwide from ball where match_id = $match_id and innings_no = $inn and extra_type = 'wides' group by pname;";

        //echo $query;
        $result = pg_query($query) or die('Query failed: ' . pg_last_error());
        pg_free_result($result);

        $query = "create view scard_bowl_inni1_nnoball as select distinct bowler as pname, sum(extra_runs) as nnoball from ball where match_id = $match_id and innings_no = $inn and extra_type = 'noballs' group by pname";

        //echo $query;
        $result = pg_query($query) or die('Query failed: ' . pg_last_error());
        pg_free_result($result);

        $query = "create view scard_bowl_inni1_nmaiden as  select bowler, count(*) as nmaiden from ( select distinct bowler, count(*), over_id from ball where match_id = $match_id and innings_no = $inn and runs_scored = 0 and ( ( extra_runs = 0 and (extra_type = 'wides' or extra_type = 'noballs' or extra_type = 'No Extras') ) or ( extra_type = 'legbyes' or extra_type = 'byes' or extra_type = 'penalty') ) group by bowler, over_id) as nmaiden1 where count = 6 group by bowler";

        //echo $query;
        $result = pg_query($query) or die('Query failed: ' . pg_last_error());
        pg_free_result($result);

        $query = "create view scard_bowl_inni1_nevery as select scard_bowl_inni1_bowlorder.bowler,CASE WHEN nover IS NULL THEN 0 ELSE nover END AS nover,CASE WHEN nballs IS NULL THEN 0 ELSE nballs END AS nballs,CASE WHEN nrun IS NULL THEN 0 ELSE nrun END AS nrun,CASE WHEN nerun IS NULL THEN 0 ELSE nerun END AS nerun,CASE WHEN nmaiden IS NULL THEN 0 ELSE nmaiden END AS nmaiden,CASE WHEN nwickets IS NULL THEN 0 ELSE nwickets END AS nwickets, CASE WHEN n0 IS NULL THEN 0 ELSE n0 END AS n0, CASE WHEN n4 IS NULL THEN 0 ELSE n4 END AS n4,CASE WHEN n6 IS NULL THEN 0 ELSE n6 END AS n6, CASE WHEN nwide IS NULL THEN 0 ELSE nwide END AS nwide,CASE WHEN nnoball IS NULL THEN 0 ELSE nnoball END AS nnoball from scard_bowl_inni1_bowlorder left join scard_bowl_inni1_nwickets on scard_bowl_inni1_nwickets.bowler = scard_bowl_inni1_bowlorder.bowler left join scard_bowl_inni1_n4 on scard_bowl_inni1_n4.pname = scard_bowl_inni1_bowlorder.bowler left join scard_bowl_inni1_n6 on scard_bowl_inni1_n6.pname = scard_bowl_inni1_bowlorder.bowler left join scard_bowl_inni1_n0 on scard_bowl_inni1_n0.pname = scard_bowl_inni1_bowlorder.bowler left join scard_bowl_inni1_nwide on scard_bowl_inni1_nwide.pname = scard_bowl_inni1_bowlorder.bowler left join scard_bowl_inni1_nnoball on scard_bowl_inni1_nnoball.pname = scard_bowl_inni1_bowlorder.bowler left join scard_bowl_inni1_nmaiden on scard_bowl_inni1_nmaiden.bowler = scard_bowl_inni1_bowlorder.bowler left join scard_bowl_inni1_runs_p1 on scard_bowl_inni1_runs_p1.bowler = scard_bowl_inni1_bowlorder.bowler left join scard_bowl_inni1_runs_p2 on scard_bowl_inni1_runs_p2.bowler = scard_bowl_inni1_bowlorder.bowler left join scard_bowl_nball_inni1_nover on scard_bowl_nball_inni1_nover.bowler = scard_bowl_inni1_bowlorder.bowler left join scard_bowl_nball_inni1_nball on scard_bowl_nball_inni1_nball.bowler = scard_bowl_inni1_bowlorder.bowler";

        //echo $query;
        $result = pg_query($query) or die('Query failed: ' . pg_last_error());
        pg_free_result($result);

        $query = "create view scard_bowl_inni1_nevery1 as select scard_bowl_inni1_nevery.bowler,nover, nballs, nrun + nerun as runs_given, nmaiden, nwickets, n0, n4, n6, nwide, nnoball from scard_bowl_inni1_nevery;";

        //echo $query;
        $result = pg_query($query) or die('Query failed: ' . pg_last_error());
        pg_free_result($result);

        $query = "select player.player_name as player_name, nover , nballs, nmaiden,runs_given,nwickets, round(round(runs_given,2)/round(nover*6+nballs,2)*6,2) as econ, n0, n4,n6,nwide,nnoball from scard_bowl_inni1_bowlorder, scard_bowl_inni1_nevery1, player where player.player_id = scard_bowl_inni1_bowlorder.bowler and scard_bowl_inni1_bowlorder.bowler = scard_bowl_inni1_nevery1.bowler order by bowl_order;";

        //echo $query;
        $result = pg_query($query) or die('Query failed: ' . pg_last_error());

        echo "<table>\n";
        echo "<tr><th>Bowler</th><th>Overs</th><th>Balls</th><th>Maidens</th><th>Runs Given</th><th>Wickets Taken</th><th>Economy Rate</th><th>Dot Balls</th><th>Fours Given</th><th>Six Givens</th><th>Wides</th><th>No Balls</th></tr>";
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
          echo "\t<tr>\n";
          foreach ($line as $col_value) {
            echo "\t\t<td>$col_value</td>\n";
            }
          echo "\t</tr>\n";
        }
        echo "</table>\n";
        pg_free_result($result);

        $query = "DROP VIEW IF EXISTS scard_bowl_inni1_bowlorder,scard_bowl_nball_inni1_p1,scard_bowl_nball_inni1_nover,scard_bowl_nball_inni1_nball,scard_bowl_inni1_runs_p1,scard_bowl_inni1_runs_p2,scard_bowl_inni1_nwickets,scard_bowl_inni1_n4,scard_bowl_inni1_n6,scard_bowl_inni1_n0,scard_bowl_inni1_nwide,scard_bowl_inni1_nnoball,scard_bowl_inni1_nmaiden,scard_bowl_inni1_nevery,scard_bowl_inni1_nevery1 CASCADE;";

        //echo $query;
        $result = pg_query($query) or die('Query failed: ' . pg_last_error());
        pg_free_result($result);

        ?>


      </div>
    </div>
    <div id="footer">
      Made by Chinmaya Singh, Vivek Singal and Adarsh Agarwal
    </div>
  </div>
</body>
</html>
