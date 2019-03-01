<?php
function create_dropdown($dropdown_name,$query,$multiple,$text) {
    //  $dropdown_name --> name of the dropdown menu or the name of the variable in which the selected values get POST or GET
    //  $query --> this query should return the list which you want to show in the dropdown menu. Note - This query must return only one column.
    //  $multiple --> Set TRUE if you want to select multiplt items in the dropdown menu otherwise FALSE
    //  $text --> this is the text which is to be displayed on the dropdown menu
	 $dbconn = pg_connect("host=localhost port=5432 dbname=ipl user=postgres password=col362")
        or die('Could not connect: ' . pg_last_error());
    $result = pg_query($query) or die('Query failed: ' . pg_last_error());
    $line = pg_fetch_all($result);
    if($multiple == FALSE)
    {
    	echo "<select name='$dropdown_name' required>";
    }
    else{
    	echo "<select name='$dropdown_name' multiple>";
    }
    echo "<option selected='selected' disabled='disabled' value=''>$text</option>";
    foreach ($line as $key => $value) {
      foreach ($value as $key1 => $value1) {
        echo "<option value='$value1'>$value1</option>";
      }     
    }
    echo "</select><br>";
}




function create_dropdown_player($dropdown_name,$query,$text) {
    $dbconn = pg_connect("host=localhost port=5432 dbname=ipl user=postgres password=col362")
        or die('Could not connect: ' . pg_last_error());
    $result = pg_query($query) or die('Query failed: ' . pg_last_error());
    $line = pg_fetch_all($result);

    echo "<select name='$dropdown_name' required>";
 
    echo "<option selected='selected' disabled='disabled' value=''>$text</option>";
    foreach ($line as $key => $value) {
      foreach ($value as $key1 => $value1) {
        echo "<option value='$value1'>$value1</option>";
      }     
    }
    echo "</select>";
    
    $name = $dropdown_name.'_role';
    echo "<select name='$name' required>";
    echo "<option selected='selected' disabled='disabled' value=''>Select Role</option>";

    
    echo "<option value='Player'>Player</option>";  
    echo "<option value='Captain'>Captain</option>";  
    echo "<option value='Keeper'>Keeper</option>";  
    echo "<option value='CaptainKeeper'>CaptainKeeper</option>";  
    
    echo "</select><br><br><br>";


}

?>

