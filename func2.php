<?php

  $host = 'localhost';
  $db = 'ipl';
  $user = 'postgres';
  $pwd = 'col362';

  function getOutputOfQuery($query) {
    $host = $GLOBALS['host'];
    $db = $GLOBALS['db'];
    $user = $GLOBALS['user'];
    $pwd = $GLOBALS['pwd'];
    $dbconn2 = pg_connect("host=localhost port=5432 dbname=ipl user=postgres password=col362") or die('Could not connect: ' . pg_last_error());

    $result = pg_query($query) or die('Query failed: ' . pg_last_error());
    $row = pg_fetch_all($result);
    pg_close($dbconn2);
    return $row;
  }
  function insertQuery($query) {
    $host = $GLOBALS['host'];
    $db = $GLOBALS['db'];
    $user = $GLOBALS['user'];
    $pwd = $GLOBALS['pwd'];
   $dbconn3 = pg_connect("host=localhost port=5432 dbname=ipl user=postgres password=col362") or die('Could not connect: ' . pg_last_error());

    pg_query($query) or die('Oops!! Something is wrong - ' . pg_last_error());

    pg_close($dbconn3);
  }
  function getRowFromQuery($query) {
    $host = $GLOBALS['host'];
    $db = $GLOBALS['db'];
    $user = $GLOBALS['user'];
    $pwd = $GLOBALS['pwd'];
    $dbconn4 = pg_connect("host=localhost port=5432 dbname=ipl user=postgres password=col362") or die('Could not connect: ' . pg_last_error());

    $result = pg_query($query) or die('Query failed: ' . pg_last_error());
    $single_row;
    while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
      $single_row=$line;
    }
    pg_close($dbconn4);
    return $single_row;
  }
  function createTable($stringArray) {
    $host = $GLOBALS['host'];
    $db = $GLOBALS['db'];
    $user = $GLOBALS['user'];
    $pwd = $GLOBALS['pwd'];
   $dbconn5 = pg_connect("host=localhost port=5432 dbname=ipl user=postgres password=col362") or die('Could not connect: ' . pg_last_error());

    $allColumns=array();
    echo "<table>
          <tr><th>S.No</th>";

    $numoutput=0;
    foreach ($stringArray as $query ) {
      $result = pg_query($query) or die('Query failed: ' . pg_last_error());
      $lines=pg_fetch_all($result);
      if($lines) {
        $numoutput++;
        foreach($lines[0] as $key=>$value) {
          echo "<th>$key</th>";
        }
        array_push($allColumns,$lines);
      }
    }
    if($numoutput==0) {
      die("No output found");
    }
    echo "</tr>";
    $noRows=count($allColumns[0]);
    for($i=0;$i<$noRows;$i++) {
      $serialNo=$i+1;
      echo "<tr><td>$serialNo</td>";
      foreach($allColumns as $lines) {
        foreach($lines[$i] as $key=>$value) {
          echo "<td>$value</td>";
        }
      }
      echo "</tr>";
    }
    echo "</table>";

    pg_close($dbconn5);
  }
?>