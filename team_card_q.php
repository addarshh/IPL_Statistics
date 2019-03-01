<?php
echo "<p align=center style='font-size:120%;'>";
$qt4 = " select  extra_type, sum(extra_runs) as eruns from ball where match_id = $match_id and innings_no = $inn and extra_type <> 'No Extras' group by extra_type";
$result = pg_query($qt4) or die('Query failed: ' . pg_last_error());
echo "EXTRAS";
$ans = 0;

echo "(";
for ($i=0; $i < pg_num_rows($result); $i++) { 
	echo pg_fetch_all($result)[$i]['extra_type'];
	echo pg_fetch_all($result)[$i]['eruns'];
	if($i != pg_num_rows($result) - 1){echo ", ";}
	$ans = $ans + pg_fetch_all($result)[$i]['eruns'];
}
echo ") Total Extras are $ans<br>";
pg_free_result($result);

echo "TOTAL (";

$qt2 = "select count(*) as twickets
from ball
where match_id = $match_id
and innings_no = $inn
and out_type <> 'Not Applicable' 
and out_type <> 'retired hurt'";
$result = pg_query($qt2) or die('Query failed: ' . pg_last_error());
$ans2 = pg_fetch_all($result)[0]['twickets'];
echo "$ans2 wickets; ";
pg_free_result($result);

$qt3 = "select case when balls <> 0 then tover - 1 else tover end as tover , balls from ( select  mod(count(*), 6) as balls, max(over_id) as tover from ball where match_id = $match_id and innings_no = $inn
and ( extra_type = 'No Extras' or extra_type = 'legbyes' or extra_type = 'byes') group by match_id ) as temp";
$result = pg_query($qt3) or die('Query failed: ' . pg_last_error());
$ans3 = pg_fetch_all($result)[0]['tover'];
$ans4 = pg_fetch_all($result)[0]['balls'];
echo "$ans3.$ans4 overs; ";
pg_free_result($result);


$qt1 = "select sum(runs_scored) + sum(extra_runs) as truns
from ball
where match_id = $match_id
and innings_no = $inn";
$result = pg_query($qt1) or die('Query failed: ' . pg_last_error());
$ans1 = pg_fetch_all($result)[0]['truns'];
echo "$ans1 runs)";
pg_free_result($result);








$qt5 = "select player_name , role_desc, player_team from player_match, match, player where player_match.match_id = match.match_id and match.match_id = $match_id and player.player_id = player_match.player_id and ( ( player_match.player_team = toss_winner and toss_name = 'bat' ) or (player_match.player_team <> toss_winner and toss_name = 'field') ) order by role_desc";
$result = pg_query($qt5) or die('Query failed: ' . pg_last_error());
pg_free_result($result);

$qt6 = "select player_name , role_desc, player_team from player_match, match, player where player_match.match_id = match.match_id and match.match_id = $match_id and player.player_id = player_match.player_id and  ( ( player_match.player_team <> toss_winner and toss_name = 'bat' ) or (player_match.player_team = toss_winner and toss_name = 'field') ) order by role_desc";
$result = pg_query($qt6) or die('Query failed: ' . pg_last_error());
pg_free_result($result);

$qt7 = " DROP VIEW IF EXISTS scard_team_inni2_player_played, scard_team_inni1_player_played, scard_team_extra_inni1, scard_team_tover_inni1, scard_team_outs, scard_team_runs";
$result = pg_query($qt7) or die('Query failed: ' . pg_last_error());
pg_free_result($result);
echo "</p>";

?>