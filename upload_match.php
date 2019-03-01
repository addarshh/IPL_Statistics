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
          <li class = 'selected'><a href='upload_match.php'>Upload Match</a></li>
          <!--<li><a href='amatch.php'>Add Match</a></li>-->
          <li><a href='signup.php'>Sign Up</a></li>
          <li><a href='home.php'>Logout</a></li>
        
        </ul>
      </div>
    </div>
    <div id="site_content">
      <div id="content">
        <!-- insert the page content here -->
        
        <h1>Upload Match</h1>
        <form action="upload_match.php" method="post" enctype="multipart/form-data">
          <h4>Choose a .csv file</h4>
          <input type="file" name="match_details" accept=".csv,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" required>
          <p style="padding-top: 15px"><input type="submit" value="SUBMIT" /></p>
        </form>

        <?php
          if(isset($_FILES['match_details'])){

            $uploaddir = 'uploads/';
            $uploadfile = $uploaddir . basename($_FILES['match_details']['name']);

            if (move_uploaded_file($_FILES['match_details']['tmp_name'], $uploadfile)){
              echo "File is valid, and was successfully uploaded.\n";
            } 
            else {
              echo "File not uploaded. Try Again!!\n";
            }

            $dbconn = pg_connect("host=localhost port=5432 dbname=ipl user=postgres password=col362") or die('Could not connect: ' . pg_last_error());
            pg_query("BEGIN;");
            pg_query("SET DATESTYLE to MDY;");
            $handle = fopen($uploadfile, "r");

            $str='';
            $data = fgetcsv($handle);
            for ($c=0; $c < 13; $c++) {
              if($data[$c]==''){}
              elseif($c==0 or $c==4 or $c==5){
                $str = $str.$data[$c].',';
              }
              elseif($c==12){
                $str = $str.$data[$c];
              }
              else{
                $str = $str."'".$data[$c]."',";
              }
              if($c==0){
                $matchid = $data[$c];
              }
            }
            $query = "INSERT INTO match VALUES($str)";
            //echo $query;
            if(pg_query($query)==FALSE){
              echo "<script>alert('Upload Not successful!! Try Again!');</script>";
              die();
            }
            //echo "<br>";

            $data = fgetcsv($handle);
            for ($i=0; $i < 22; $i++) { 
              $data = fgetcsv($handle);
              $str=$matchid.',';
              for ($c=0; $c < 3; $c++) {
                if($c==0){
                  $str = $str.$data[$c].",";
                }
                elseif($c==1){
                  $str = $str."'".$data[$c]."',";
                }
                else{
                  $str = $str."'".$data[$c]."'";
                }
              }
              $query = "INSERT INTO player_match VALUES($str)";
              //echo $query;
              if(pg_query($query)==FALSE){
              echo "<script>alert('Upload Not successful!! Try Again!');</script>";
              die();
            }
              //echo "<br>";
            }

            $data = fgetcsv($handle);
            for ($i=0; $i < 2; $i++) { 
              $data = fgetcsv($handle);
              $str=$matchid.',';
              for ($c=0; $c < 3; $c++) {
                if($c==2){
                  $str = $str.$data[$c];
                }
                else{
                  $str = $str.$data[$c].',';
                }
              }
              $query = "INSERT INTO team_match VALUES($str)";
              //echo $query;
              if(pg_query($query)==FALSE){
              echo "<script>alert('Upload Not successful!! Try Again!');</script>";
              die();
            }
              //echo "<br>";
            }

            $data = fgetcsv($handle);
            while (($data = fgetcsv($handle)) !== FALSE) {
              $str = $matchid.',';
              for ($c=0; $c < 13; $c++) {
                if($data[$c]==''){
                
                }
                elseif($c==4 or $c==7){
                  $str = $str."'".$data[$c]."',";
                }
                elseif($c==12){
                  $str = $str.$data[$c];
                }
                else{
                  $str = $str.$data[$c].',';
                }
              }
              $str=rtrim($str,", ");
              $query = "INSERT INTO ball VALUES($str)";
              //echo $query;
              if(pg_query($query)==FALSE){
              echo "<script>alert('Upload Not successful!! Try Again!');</script>";
              die();
            }
              //echo "<br>";
            }
            fclose($handle);
          pg_query("COMMIT;");
          pg_close($dbconn);

        
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
