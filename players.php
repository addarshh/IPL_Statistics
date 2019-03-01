<?php include 'func.php'; ?>
<!DOCTYPE HTML>
<html>

<head>
  <title>IPL-Players</title>
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
        <li><a href="home.php">Home</a></li>
        <li><a href="teams.php">Teams</a></li>
        <li class = "selected"><a href="players.php">Players</a></li>
        <li><a href="matches.php">Matches</a></li>
        <li><a href="stats.php">Interesting Stats</a></li>
        <li><a href="player_stats.php">Player Stats</a></li>
        <li><a href="login.php">Login</a></li>
      </ul>
    </div>
    <div id="site_content">
      <div id="content">
        <!-- insert the page content here -->
        <h2>Filters</h2>
        <form action="players.php" method="get">
          <h4>By Country</h4>
          <?php create_dropdown('country[]','SELECT distinct(country_name) from player order by country_name',true,'Select country'); ?>
          <h4>By Batting Hand</h4>
          <select name="bat" size="2">
            <option value="Right">Right</option>
            <option value="Left">Left</option>
          </select><br>
          <h4>By Bowling Hand</h4>
          <select name="bowl" size="2">
            <option value="Right">Right</option>
            <option value="Left">Left</option>
          </select><br>
          <input type="submit" , value="SUBMIT">
        </form>

        <?php
        include 'func2.php';
        $check=0;
        $query1='';
        $query2='';
        $query3='';
        if(isset($_GET["country"])){
          $check=1;
          $country = $_GET["country"];
          $country_list = "(";
          foreach($country as $c){
            $country_list = $country_list."'".$c."'".',';
          }
          $country_list = $country_list."'".$c."'".')';
          $query1 = "SELECT player_id,player_name,dob,batting_hand,bowling_skill,country_name FROM player WHERE country_name IN $country_list";
        }

        if(isset($_GET["bowl"])){
          $check=1;
          $bowl = $_GET["bowl"];
          if($bowl=='Right'){
            $query2 = "SELECT player_id,player_name,dob,batting_hand,bowling_skill,country_name FROM player WHERE lower(bowling_skill) LIKE '%right%' OR lower(bowling_skill) LIKE '%leg%'";
          }
          elseif($bowl=='Left'){
            $query2 = "SELECT player_id,player_name,dob,batting_hand,bowling_skill,country_name FROM player WHERE lower(bowling_skill) LIKE '%left%'";
          }
          
        }
        if(isset($_GET["bat"])){
          $check=1;
          $bat = $_GET["bat"];
          if($bat=='Right'){
            $query3 = "SELECT player_id,player_name,dob,batting_hand,bowling_skill,country_name FROM player WHERE lower(batting_hand) LIKE '%right%'";
          }
          elseif($bat=='Left'){
            $query3 = "SELECT player_id,player_name,dob,batting_hand,bowling_skill,country_name FROM player WHERE lower(batting_hand) LIKE '%left%'";
          }
        }
        $query='';
        if($query1 != '')
        {
          $query = $query1;
        }
        if($query2 != '')
        {
          if($query==''){
            $query=$query2;
          }
          else{
            $query = $query.' INTERSECT '.$query2;
          }
        }
        if($query3 != '')
        {
          if($query==''){
            $query=$query3;
          }
          else{
            $query = $query.' INTERSECT '.$query3;
          }
        }
        if($query==''){
          $query = 'SELECT player_id,player_name,dob,batting_hand,bowling_skill,country_name FROM player ORDER BY player_id';  
        }
        
        echo '<br>';
        //echo $query;
        $queryArray=array($query);
        createTable($queryArray);
        ?>

      </div>
    </div>
    <div id="footer">
      Made by Adarsh Agrawal, Chinmaya Singh, Vivek Singal
    </div>
  </div>
</body>
</html>
