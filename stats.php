<?php include 'func2.php'; ?>
<!DOCTYPE HTML>
<html>

<head>
  <title>Stats</title>
  <meta name="description" content="website description" />
  <meta name="keywords" content="website keywords, website keywords" />
  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
  <link rel="stylesheet" type="text/css" href="style/style.css" title="style" />
</head>

<body>
  <div id="main">
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
          <li class = "selected"><a href="stats.php">Interesting Stats</a></li>
          <li><a href="player_stats.php">Player Stats</a></li>
          <li><a href="login.php">Login</a></li>
        </ul>
      </div>
      <div id="menubar">
        <ul id="menu">
          <li><a href="stats.php?type2=bat">Batting</a></li>
          <li><a href="stats.php?type2=bowl">Bowling</a></li>
        </ul>
      </div>
      <?php
      if($_GET["type2"]=='bat')
      {
      echo "<div id='menubar2'>";
        echo "<ul id='menu'>";
          echo "<li><a href='stats.php?type2=bat&type=Runs'>Most Runs</a></li>";
          echo "<li><a href='stats.php?type2=bat&type=HS'>Highest Score</a></li>";
          echo "<li><a href='stats.php?type2=bat&type=SR'>Highest Strike Rates</a></li>";
          echo "<li><a href='stats.php?type2=bat&type=AVG'>Highest Average</a></li>";
          echo "<li><a href='stats.php?type2=bat&type=Fours'>Most Fours</a></li>";
          echo "<li><a href='stats.php?type2=bat&type=Sixes'>Most Sixes</a></li>";
          echo "<li><a href='stats.php?type2=bat&type=Fifties'>Most Fifties</a></li>";
          echo "<li><a href='stats.php?type2=bat&type=Centuries'>Most Centuries</a></li>";
          echo "<li><a href='stats.php?type2=bat&type=FastestFifties'>Fastest Fifties</a></li>";
          echo "<li><a href='stats.php?type2=bat&type=FastestCenturies'>Fastest Centuries</a></li>";
        echo "</ul>";
      echo "</div>";
      }
      elseif($_GET["type2"]=='bowl')
      {
      echo "<div id='menubar2'>";
        echo "<ul id='menu'>";
          echo "<li><a href='stats.php?type2=bowl&type=MostWickets'>Most Wickets</a></li>";
          echo "<li><a href='stats.php?type2=bowl&type=MostMaidens'>Most Maidens</a></li>";
          echo "<li><a href='stats.php?type2=bowl&type=MostDotBalls'>Most DotBalls</a></li>";
          //echo "<li><a href='stats.php?type2=bowl&type=LeastRuns'>Least RunsGiven</a></li>";
          echo "<li><a href='stats.php?type2=bowl&type=Average'>Average</a></li>";
          echo "<li><a href='stats.php?type2=bowl&type=EconomyRate'>Economy Rate</a></li>";
          
        echo "</ul>";
      echo "</div>";
      }
      ?>
      
    <div id="site_content2">
    <div id="content">
      <?php

        $common_query="select player_name as Player,total_matches as Matches,total_runs as Total_Runs,highest_score as Highest_Score,
                  Round(average_runs,2) as AVG_Runs,total_balls_faced as Balls_Faced,Round(total_runs*100.0/total_balls_faced,2) as strike_rate, num_100 as Centuries,num_50 as Fifties,total_num4 as Fours,total_num6 as Sixes
                  from (
                  select player_name,striker,sum(runs_per_match) as total_runs,sum(balls_faced_permatch) as total_balls_faced,sum(num4_permatch) as total_num4,
                  sum(num6_permatch) as total_num6, max(runs_per_match) as highest_score, AVG(runs_per_match) as average_runs,count(runs_per_match) 
                  filter(where runs_per_match>=50 and runs_per_match<100) as num_50, count(runs_per_match) filter(where runs_per_match>=100) as num_100
                  from (select player_name,ball.striker,sum(runs_scored) as runs_per_match,count(ball_id) as balls_faced_permatch,
                  count(ball_id) filter(where runs_scored=4) as num4_permatch,
                  count(ball_id) filter(where runs_scored=6) as num6_permatch from ball join player on (ball.striker=player.player_id) 
                  group by match_id,ball.striker,player.player_name) as x
                  group by striker,player_name ) as table1
                  join 
                  (select player_id,count(distinct match_id) as total_matches from player_match group by player_id) as table2
                  on striker=player_id ";
        function mostRuns($extra_constraint) {
          $query1= $GLOBALS['common_query'] . " $extra_constraint order by total_runs DESC";
          $queryArray=array($query1);
          createTable($queryArray);
        }
        function mostHS($extra_constraint) {
          $query1= $GLOBALS['common_query'] . " $extra_constraint order by Highest_Score DESC";
          $queryArray=array($query1);
          createTable($queryArray); 
        }
        function mostSR($extra_constraint) {
          $query1= $GLOBALS['common_query'] . " $extra_constraint and total_runs>=200 order by Strike_Rate DESC ";
          $queryArray=array($query1);
          createTable($queryArray);
        }
        function mostAVG($extra_constraint){
          $query1= $GLOBALS['common_query'] . " $extra_constraint order by average_runs DESC";
          $queryArray=array($query1);
          createTable($queryArray); 
        }
        function mostFours($extra_constraint) {
          $query1=$GLOBALS['common_query'] . " $extra_constraint order by Fours DESC ";
          $queryArray=array($query1);
          createTable($queryArray);
        }
        function mostSixes($extra_constraint) {
          $query1=$GLOBALS['common_query'] . " $extra_constraint order by Sixes DESC ";
          $queryArray=array($query1);
          createTable($queryArray);
        }
        function mostFifties($extra_constraint ) {
          $query1=$GLOBALS['common_query'] . " $extra_constraint order by Fifties DESC ";
          $queryArray=array($query1);
          createTable($queryArray); 
        }
        function mostCenturies($extra_constraint) {
          $query1=$GLOBALS['common_query'] . " $extra_constraint order by Centuries DESC ";
          $queryArray=array($query1);
          createTable($queryArray); 
        }


        function fastestFifties($extra_constraint ) {
           $q_dropView="drop view if exists ball_cumm_runs2;
            drop view if exists ball_cumm_runs1;
            drop view if exists ball_cumm_runs;";
            insertQuery($q_dropView);
          $q1="create view ball_cumm_runs as 
                  select match_id,innings_no,striker,over_id,ball_id,count(ball_id) filter(where extra_type not in ('wides')) over w
                  as num_balls,count(ball_id) filter(where runs_scored=6) over w as num_6s,
                  count(ball_id) filter(where runs_scored=4) over w as num_4s,
                  sum(runs_scored) over w as cumm_runs 
                  from ball where innings_no<=2
                  window w as (partition by match_id,striker order by over_id,ball_id)";
          $q2=  "create view ball_cumm_runs1 as
                  select match_id,innings_no,player.player_name,min(num_balls) as balls_faced,max(num_6s) as num_6s,max(num_4s) as num_4s,max(cumm_runs) as runs
                  from ball_cumm_runs join player on ball_cumm_runs.striker=player.player_id
                  where cumm_runs>=50
                  group by match_id,striker,player.player_name,innings_no
                  order by Balls_Faced
                  limit 100";
          $q3=  "create view ball_cumm_runs2 as
                  select ball_cumm_runs1.match_id,player_name,team_bowling as team_id,venue_id,match_date,balls_faced,num_6s,num_4s,runs
                  from ball_cumm_runs1 join team_match on ball_cumm_runs1.match_id=team_match.match_id and ball_cumm_runs1.innings_no=team_match.innings_no
                  join match on ball_cumm_runs1.match_id=match.match_id";
            insertQuery($q1);
            insertQuery($q2);
            insertQuery($q3);
            $query="select player_name,team_name as Against,venue_name as Venue,match_date,balls_faced,num_6s,num_4s,runs
                  from ball_cumm_runs2 join team on ball_cumm_runs2.team_id=team.team_id
                  join venue on ball_cumm_runs2.venue_id=venue.venue_id
                  $extra_constraint 
                  order by balls_faced,runs desc,num_6s desc,num_4s desc";
            $queryArray=array($query);
            createTable($queryArray);  
            insertQuery($q_dropView);
          }

          function fastestCentury($extra_constraint) {
           $q_dropView="drop view if exists ball_cumm_runs2;
            drop view if exists ball_cumm_runs1;
            drop view if exists ball_cumm_runs;";
            insertQuery($q_dropView);
          $q1="create view ball_cumm_runs as 
                  select match_id,innings_no,striker,over_id,ball_id,count(ball_id) filter(where extra_type not in ('wides')) over w
                  as num_balls,count(ball_id) filter(where runs_scored=6) over w as num_6s,
                  count(ball_id) filter(where runs_scored=4) over w as num_4s,
                  sum(runs_scored) over w as cumm_runs 
                  from ball where innings_no<=2
                  window w as (partition by match_id,striker order by over_id,ball_id)";
          $q2=  "create view ball_cumm_runs1 as
                  select match_id,innings_no,player.player_name,min(num_balls) as balls_faced,max(num_6s) as num_6s,max(num_4s) as num_4s,max(cumm_runs) as runs
                  from ball_cumm_runs join player on ball_cumm_runs.striker=player.player_id
                  where cumm_runs>=100
                  group by match_id,striker,player.player_name,innings_no
                  order by Balls_Faced
                  limit 100";
          $q3=  "create view ball_cumm_runs2 as
                  select ball_cumm_runs1.match_id,player_name,team_bowling as team_id,venue_id,match_date,balls_faced,num_6s,num_4s,runs
                  from ball_cumm_runs1 join team_match on ball_cumm_runs1.match_id=team_match.match_id and ball_cumm_runs1.innings_no=team_match.innings_no
                  join match on ball_cumm_runs1.match_id=match.match_id";
            insertQuery($q1);
            insertQuery($q2);
            insertQuery($q3);
            $query="select player_name,team_name as Against,venue_name as Venue,match_date,balls_faced,num_6s,num_4s,runs
                  from ball_cumm_runs2 join team on ball_cumm_runs2.team_id=team.team_id
                  join venue on ball_cumm_runs2.venue_id=venue.venue_id
                  $extra_constraint
                  order by balls_faced,runs desc,num_6s desc,num_4s desc";
            $queryArray=array($query);
            createTable($queryArray); 
            insertQuery($q_dropView);   
          }


         
        if(isset($_GET["type2"])) {
          if(isset($_GET["type"]) and $_GET["type2"]=='bat'){
            if($_GET["type"]!='FastestFifties' && $_GET["type"]!='FastestCenturies') {
              $var=$_GET["type"];
              echo '<br><h4>'.$var.' more than </h4>';
            }
            else {
              $var=$_GET["type"];
              echo '<br><h4>'.$var.' in balls below </h4>'; 
            }
            $extra_constraint="";
            echo '<form method="get" action="stats.php">
              <p>
                <input type="hidden" name="type2" value="bat" />
                <input type="hidden" name="type" value="'.$_GET["type"].'" />
                <input class="search" type="text" name="extra_constraint" placeholder="Enter number" />
                <input name="search" type="image" style="border: 0; margin: 0 0 -9px 5px;" src="style/search.png" alt="Search" title="Search" />
              </p>
            </form>';
            if(isset($_GET["extra_constraint"])) {
              $extra_constraint=$_GET["extra_constraint"];
            }
            switch($_GET["type"]) {
              case 'Runs':
                if(strlen($extra_constraint)>0) {
                  $par= " where total_runs >= $extra_constraint ";
                }
                else {
                  $par="";
                }
                mostRuns($par);
                break;
              case 'HS':
                if(strlen($extra_constraint)>0) {
                  $par= " where highest_score >= $extra_constraint ";
                }
                else {
                  $par="";
                }
                mostHS($par);
                break;
              case 'SR':
                if(strlen($extra_constraint)>0) {
                  $par= " where total_runs*100.0/total_balls_faced >= $extra_constraint ";
                }
                else {
                  $par="";
                }
                mostSR($par);
                break;
              case 'AVG':
                if(strlen($extra_constraint)>0) {
                  $par= " where average_runs >= $extra_constraint ";
                }
                else {
                  $par="";
                }
                mostAVG($par);
                break;
              case 'Fours':
                if(strlen($extra_constraint)>0) {
                  $par= " where total_num4 >= $extra_constraint ";
                }
                else {
                  $par="";
                }
                mostFours($par);
                break; 
              case 'Sixes':
                if(strlen($extra_constraint)>0) {
                  $par= " where total_num6 >= $extra_constraint ";
                }
                else {
                  $par="";
                }
                mostSixes($par);
                break;
              case 'Fifties':
                if(strlen($extra_constraint)>0) {
                  $par= " where num_50 >= $extra_constraint ";
                }
                else {
                  $par="";
                }
                mostFifties($par);
                break;
              case 'Centuries':
                if(strlen($extra_constraint)>0) {
                  $par= " where num_100 >= $extra_constraint ";
                }
                else {
                  $par="";
                }
                mostCenturies($par);
                break;
              case 'FastestFifties':
                if(strlen($extra_constraint)>0) {
                  $par= " where balls_faced <= $extra_constraint ";
                }
                else {
                  $par="";
                }
                fastestFifties($par);
                break;
              case 'FastestCenturies':
                if(strlen($extra_constraint)>0) {
                  $par= " where balls_faced <= $extra_constraint ";
                }
                else {
                  $par="";
                }
                fastestCentury($par);
                break;
            }
          } 
          elseif(isset($_GET["type"]) and $_GET["type2"]=='bowl'){
            include 'stats_bowl_q.php';

            $dbconn = pg_connect("host=localhost port=5432 dbname=ipl user=postgres password=col362") or die('Could not connect: ' . pg_last_error());
            $result = pg_query($qb10) or die('Query failed: ' . pg_last_error());
            $result = pg_query($qb0) or die('Query failed: ' . pg_last_error());
            $result = pg_query($qb1) or die('Query failed: ' . pg_last_error());
            $result = pg_query($qb2) or die('Query failed: ' . pg_last_error());
            $result = pg_query($qb3) or die('Query failed: ' . pg_last_error());
            $result = pg_query($qb4) or die('Query failed: ' . pg_last_error());
            $result = pg_query($qb5) or die('Query failed: ' . pg_last_error());
            $result = pg_query($qb6) or die('Query failed: ' . pg_last_error());
            $result = pg_query($qb7) or die('Query failed: ' . pg_last_error());
            $result = pg_query($qb8) or die('Query failed: ' . pg_last_error());
            pg_close($dbconn);

            switch($_GET["type"]) {
              case 'MostWickets':
                $qf = $qb9 . ' order by nwicket desc nulls last limit 50';
                $queryArray=array($qf);
                createTable($queryArray);
                break;
              case 'MostMaidens':
                $qf = $qb9 . ' order by nmaiden desc nulls last limit 50';
                $queryArray=array($qf);
                createTable($queryArray);
                break;
              case 'MostDotBalls':
                $qf = $qb9 . ' order by n0 desc nulls last limit 50';
                $queryArray=array($qf);
                createTable($queryArray);
                break;

              /*case 'LeastRuns':
                $qf = $q10 . ' order by nruns limit 50';
                $queryArray=array($qf);
                createTable($queryArray);*/
              case 'Average':
                $qf = $qb9 . ' order by avg limit 50';
                $queryArray=array($qf);
                createTable($queryArray);
                break;

              case 'EconomyRate':
                $qf = $qb9 . ' order by econ desc  nulls last limit 50';
                $queryArray=array($qf);
                createTable($queryArray);
                break;

            } 
            $dbconn = pg_connect("host=localhost port=5432 dbname=ipl user=postgres password=col362") or die('Could not connect: ' . pg_last_error());
            $result = pg_query($qb10) or die('Query failed: ' . pg_last_error());
            pg_close($dbconn);
          }
        }
        
      ?>
    </div>
    <div id="footer">
      Made by Chinmaya Singh, Vivek Singal and Adarsh Agarwal
    </div>
  </div>
  </div>

</body>
</html>
