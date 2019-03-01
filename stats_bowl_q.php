<?php
$qb0 = "create view stats_bowl_nmatches as 
select player_name, count(distinct match_id) as nmatches
from ball, player
where bowler = player_id
group by player_name";


$qb1 =  "create view stats_bowl_mwickets as 
select player_name, count(*) as nwicket
from ball, player
where bowler = player_id
and ( out_type <> 'Not Applicable' and out_type <> 'retired hurt'  and out_type <> 'run out'  and out_type <> 'obstructing the field' )
group by player_name
order by nwicket desc";


$qb2 =  "create view stats_bowl_nmaidens as
select player_name, count(*) as nmaiden
from (
select distinct bowler, over_id, count(*)
from ball
where runs_scored = 0
and ( ( extra_runs = 0 and (extra_type = 'wides' or extra_type = 'noballs' or extra_type = 'No Extras') ) or ( extra_type = 'legbyes' or extra_type = 'byes' or extra_type = 'penalty') )
group by match_id, bowler, over_id) as nmaiden1, player
where nmaiden1.count = 6
and bowler = player_id
group by player_name
order by nmaiden desc";

$qb3 =  "create view stats_bowl_ndots as
select distinct player_name as pname, count(*) as n0
from ball, player
where player_id = bowler
and runs_scored = 0
and ( ( extra_runs = 0 and (extra_type = 'wides' or extra_type = 'noballs' or extra_type = 'No Extras') ) or ( extra_type = 'legbyes' or extra_type = 'byes' or extra_type = 'penalty') )
group by pname
order by n0 desc";

$qb4 = "create view stats_bowl_runs_p1 as
select bowler, sum(extra_runs) as nerun
from ball
where (extra_type = 'wides' or extra_type = 'noballs')
group by bowler";

$qb5 =  "create view stats_bowl_runs_p2 as
select bowler, sum(runs_scored) as nrun
from ball
group by bowler";

$qb6 = "create view stats_bowl_runs as
select player.player_name, (nrun + nerun ) as runs
from stats_bowl_runs_p1, stats_bowl_runs_p2, player
where stats_bowl_runs_p1.bowler = stats_bowl_runs_p2.bowler
and stats_bowl_runs_p1.bowler = player_id
order by runs desc";

$qb7 =  "create view stats_bowl_avg as select stats_bowl_mwickets.player_name, round(round(runs,2)/ round(nwicket,2) , 2) as avg from stats_bowl_runs, stats_bowl_mwickets where stats_bowl_runs.player_name = stats_bowl_mwickets.player_name and nwicket > 10 order by avg";

$qb8 = "create view stats_bowl_econ as select stats_bowl_runs.player_name , round(round(runs,2)/round(balls,2),2)*6 as econ, balls from ( select bowler, count(*) as balls from ball where ( extra_type = 'No Extras' or extra_type = 'legbyes' or extra_type = 'byes' or extra_type = 'penalty') group by bowler) as stats_econ, stats_bowl_runs, player where stats_bowl_runs.player_name = player.player_name and stats_econ.bowler = player.player_id and balls > 120 order by econ";



$qb9 = "select stats_bowl_mwickets.player_name,nmatches as no_innings, nwicket, nmaiden, n0, runs, avg, econ
from stats_bowl_mwickets
left join stats_bowl_nmaidens on stats_bowl_mwickets.player_name = stats_bowl_nmaidens.player_name
left join stats_bowl_ndots on stats_bowl_mwickets.player_name = stats_bowl_ndots.pname
left join stats_bowl_runs on stats_bowl_mwickets.player_name = stats_bowl_runs.player_name
left join stats_bowl_avg on stats_bowl_mwickets.player_name = stats_bowl_avg.player_name
left join stats_bowl_econ on stats_bowl_mwickets.player_name = stats_bowl_econ.player_name, stats_bowl_nmatches
where stats_bowl_nmatches.player_name = stats_bowl_mwickets.player_name";


$qb10 = "DROP VIEW IF EXISTS stats_bowl_econ, stats_bowl_avg, stats_bowl_runs, stats_bowl_runs_p2, stats_bowl_runs_p1, stats_bowl_ndots, stats_bowl_nmaidens, stats_bowl_mwickets, stats_bowl_nmatches";

?>
