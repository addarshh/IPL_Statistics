<!DOCTYPE HTML>
<html>

<head>
  <title>IPL-Matches</title>
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
      <div id = "menubar">
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
      <div id = "menubar">
        <ul id="menu">
          <!-- put class="selected" in the li tag for the selected page - to highlight which page you're on -->
          <li><a href="matches.php?year=2008">2008</a></li>
          <li><a href="matches.php?year=2009">2009</a></li>
          <li><a href="matches.php?year=2010">2010</a></li>
          <li><a href="matches.php?year=2011">2011</a></li>
          <li><a href="matches.php?year=2012">2012</a></li>
          <li><a href="matches.php?year=2013">2013</a></li>
          <li><a href="matches.php?year=2014">2014</a></li>
          <li><a href="matches.php?year=2015">2015</a></li>
          <li><a href="matches.php?year=2016">2016</a></li>
          <li><a href="matches.php?year=2017">2017</a></li>
        </ul>
      </div>
    </div>
    <div id="site_content">
      <div id="content">
        <!-- insert the page content here -->
        <br>
        <h4>Search a Match</h4>
        <form method="get" action="matches.php" id="search_form">
          <!-- <select name="searchtype">
          <option value="match-date" selected="selected">Match Date (yyyy-mm-dd)</option>
          <option value="team"> Team</option>
          <option value="city"> City</option>
          </select> -->
          <input class="search" type="text" name="match_date" placeholder="Match date(yyyy-mm-dd)" />
          <input class="search" type="text" name="team" placeholder="Team" />
          <input class="search" type="text" name="city" placeholder="City(Venue)" />
          <input name="search" type="image" value="Submit" style="border: 0; margin: 0 0 -9px 5px;" src="style/search.png" alt="Search" title="Search" />
        </form>
        <br>
        <?php
        include 'func.php';
        include 'func2.php';
        // Connecting, selecting database
       $dbconn = pg_connect("host=localhost port=5432 dbname=ipl user=postgres password=col362")
        or die('Could not connect: ' . pg_last_error());

        // Performing SQL query
        $query = 'SELECT * FROM match_venue ORDER BY match_date';
        $extra_constraint="";
        if(isset($_GET["match_date"]) && strlen($_GET["match_date"])>0) {
          $var=$_GET["match_date"];
          $extra_constraint.=" match_date='$var' ";
        }
        if(isset($_GET["team"]) && strlen($_GET["team"])>0) {
          $var=$_GET["team"];
          if($extra_constraint!="") {
            $extra_constraint.=" and ";
          }
          $extra_constraint.=" (team1 ilike '%$var%' or team2 ilike '%$var%') ";
        }
        if(isset($_GET["city"]) && strlen($_GET["city"])>0) {
          $var=$_GET["city"];
          if($extra_constraint!="") {
            $extra_constraint.=" and ";
          }
          $extra_constraint.=" city_name ilike '%$var%' ";
        }

        if(isset($_GET["match_date"])) {
          $query = "SELECT * FROM match_venue where $extra_constraint ORDER BY match_date";
        }
        
        if(isset($_GET["year"])){


          $year = $_GET["year"];


          $q1 = "create view ptable_allteams_mcount as select distinct team1, count(*) from ( select team1 from match where season_year = $year union all select team2 from match where season_year = $year ) as allmatch group by team1";

          $result = pg_query($q1) or die('Query failed: ' . pg_last_error());
          pg_free_result($result);

          $q2 = "create view ptable_nmatches as select min(count) as nmatches, count(distinct team1) as nteams, min(count) * count(distinct team1) /2 as allmatch from ptable_allteams_mcount";

          $result = pg_query($q2) or die('Query failed: ' . pg_last_error());
          pg_free_result($result);

          $q3 = "create view matches as select * from (select row_number() OVER(order by match_date) AS rownum , * from ptable_nmatches,   match where season_year = $year order by rownum) as tmatch where rownum <= allmatch";

          $result = pg_query($q3) or die('Query failed: ' . pg_last_error());
          pg_free_result($result);

          $q4 = "create view ptable_wins as select match_winner as team, count(*) as wins, count(*) * 2 as points from matches where match_winner is not null group by match_winner order by points desc";

          $result = pg_query($q4) or die('Query failed: ' . pg_last_error());
          pg_free_result($result);

          $q5 = "create view ptable_draws as select distinct team , count(*) as epoints from ptable_wins, matches where matches.outcome_type <> 'Result' and matches.outcome_type <> 'Superover' and (ptable_wins.team = matches.team1 or ptable_wins.team = matches.team2) group by team, matches.nmatches";

          $result = pg_query($q5) or die('Query failed: ' . pg_last_error());
          pg_free_result($result);

          $q6 = "create view ptable1 as select ptable.team, nmatches, wins, nmatches - wins - epoints as loss , points + epoints as points from ( select ptable_wins.team, nmatches, wins, CASE WHEN epoints IS NULL THEN 0 ELSE epoints END AS epoints, points from ptable_nmatches, ptable_wins left join ptable_draws on ptable_wins.team = ptable_draws.team) as ptable order by points desc";

          $result = pg_query($q6) or die('Query failed: ' . pg_last_error());
          pg_free_result($result);

          $q7 = "create view ptable_nrr_inni as select team_match.match_id as matches_id, innings_no as inni , team_name as team_batting from team_match, team, matches where team_batting = team.team_id and team_match.match_id = matches.match_id";

          $result = pg_query($q7) or die('Query failed: ' . pg_last_error());
          pg_free_result($result);

          $q8 = " create view ptable_nrr_balls as select * from ptable_nrr_inni, ball where ptable_nrr_inni.matches_id = ball.match_id and ptable_nrr_inni.inni = ball.innings_no";

          $result = pg_query($q8) or die('Query failed: ' . pg_last_error());
          pg_free_result($result);

          $q9 = "create view ptable_nrr1 as select team_batting, round((round(sum(runs_scored),2)+ round(sum(extra_runs),2))/ round(count(ball_id),2)*6 ,2) as nrr1, round(sum(runs_scored),2) as runs, round(count(ball_id)/6,2) as overs from ptable_nrr_balls group by team_batting";

          $result = pg_query($q9) or die('Query failed: ' . pg_last_error());
          pg_free_result($result);

          $q10 = "create view ptable_nrr_inni1 as select team_match.match_id as matches_id, innings_no as inni , team_name as team_bowling from team_match, team, matches where team_bowling = team.team_id and team_match.match_id = matches.match_id";

          $result = pg_query($q10) or die('Query failed: ' . pg_last_error());
          pg_free_result($result);

          $q11 = " create view ptable_nrr_balls1 as select * from ptable_nrr_inni1, ball where ptable_nrr_inni1.matches_id = ball.match_id and ptable_nrr_inni1.inni = ball.innings_no";

          $result = pg_query($q11) or die('Query failed: ' . pg_last_error());
          pg_free_result($result);

          $q12 = " create view ptable_nrr2 as select team_bowling, round((round(sum(runs_scored),2)+ round(sum(extra_runs),2))/ round(count(ball_id),2)*6 ,2) as nrr2, round(sum(runs_scored),2) as runs, round(count(ball_id)/6,2) as overs from ptable_nrr_balls1 group by team_bowling";

          $result = pg_query($q12) or die('Query failed: ' . pg_last_error());
          pg_free_result($result);

          $q13 = "create view ptable_nrr as select ptable_nrr1.team_batting, nrr1-nrr2 as nrr from ptable_nrr1, ptable_nrr2 where ptable_nrr1.team_batting = ptable_nrr2.team_bowling order by nrr desc";

          $result = pg_query($q13) or die('Query failed: ' . pg_last_error());
          pg_free_result($result);

          $q14 = "select team, nmatches, wins, loss, nrr, points from ptable1, ptable_nrr where team = team_batting order by points desc, nrr desc";

          $queryArray=array($q14);
          echo "<h1 align = center>Points Table</h1>";
          createTable($queryArray);


          $q15 = " drop view if exists ptable_nrr, ptable_nrr2, ptable_nrr_balls1, ptable_nrr_inni1, ptable_nrr1, ptable_nrr_balls, ptable_nrr_inni, ptable1, ptable_draws, ptable_wins, matches, ptable_nmatches, ptable_allteams_mcount";

         $dbconn = pg_connect("host=localhost port=5432 dbname=ipl user=postgres password=col362")
          or die('Could not connect: ' . pg_last_error());

          $result = pg_query($q15) or die('Query failed: ' . pg_last_error());
          pg_free_result($result);


            

            $query = "SELECT * FROM match_venue WHERE EXTRACT(year FROM match_date) = '$year' ORDER BY match_date";
        }

        //adding search bar to search a match

        $result = pg_query($query) or die('Query failed: ' . pg_last_error());

        echo "<h1 align = center>Match Details</h1>";
        echo "<ul>\n";
        while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
          echo "<li><a href = './match_card.php?match_id={$line['match_id']}&inn=1' ><h4>{$line['team1']} vs {$line['team2']}</h4></a><br>";
          echo "Played on {$line['match_date']} at {$line['venue_name']}, {$line['city_name']}, {$line['country_name']}<br>";
          if($line['outcome_type'] == 'Abandoned'){
            echo "The match was Abandoned<br>";
          }
          elseif($line['outcome_type'] == 'Rain'){
            echo "{$line['toss_winner']} won the toss and chose to {$line['toss_name']}<br>";  
            echo "The match was washed out from rain<br>";
          }
          elseif($line['outcome_type'] == 'Superover'){
            echo "{$line['toss_winner']} won the toss and chose to {$line['toss_name']}<br>";  
            echo "{$line['match_winner']} won the match in the superover<br>";
          }
          else{
            echo "{$line['toss_winner']} won the toss and chose to {$line['toss_name']}<br>";
            echo "{$line['match_winner']} won the match by {$line['win_margin']} {$line['win_type']}<br>";
            echo "{$line['manofmatch']} was declared Man of the Match<br>";
          }
          echo "</li>\n";
        }
        echo "</ul>\n";
        // Free resultset
        pg_free_result($result);

        // Closing connection
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
