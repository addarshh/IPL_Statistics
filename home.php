<?php 
  session_start(); 
  session_unset(); 
  session_destroy();
?>
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
      <div id="logo">
        <div id="logo_text">
          <h1><a href="home.php">IPL</a></h1>
        </div>
      </div>
      <div id="menubar">
        <ul id="menu">
          <!-- put class="selected" in the li tag for the selected page - to highlight which page you're on -->
          <li class="selected"><a href="home.php">Home</a></li>
          <li><a href="teams.php">Teams</a></li>
          <li><a href="players.php">Players</a></li>
          <li><a href="matches.php">Matches</a></li>
          <li><a href="stats.php">Interesting Stats</a></li>
          <li><a href="player_stats.php">Player Stats</a></li>
          <li><a href="login.php">Login</a></li>
        </ul>
      </div>
    <div id="site_content">
      <div id="content">
        <!-- insert the page content here -->
        <h1>About</h1>
        <p>The Indian Premier League (IPL), officially Vivo Indian Premier League for sponsorship reasons, is a professional Twenty20 cricket league in India contested during April and May of every year by teams representing 8 Indian cities and some states. The league was founded by the Board of Control for Cricket in India (BCCI) in 2008, and is regarded as the brainchild of Lalit Modi, the founder and former commissioner of the league. IPL has an exclusive window in ICC Future Tours Programme.
        <br>
        The IPL is the most-attended cricket league in the world and in 2014 ranked sixth by average attendance among all sports leagues. In 2010, the IPL became the first sporting event in the world to be broadcast live on YouTube. The brand value of IPL in 2018 was US$6.3 billion, according to Duff & Phelps. According to BCCI, the 2015 IPL season contributed ₹11.5 billion (US$182 million) to the GDP of the Indian economy.
        <br>
        There have been eleven seasons of the IPL tournament. The current IPL title holders are the Chennai Super Kings, who won the 2018 season. The most successful franchises in the tournament are the Chennai Super Kings and Mumbai Indians with 3 tournament wins each.
    	</p>
        <h1>History</h1>
        <h2>Background</h2>
        <p>The Indian Cricket League (ICL) was founded in 2007, with funding provided by Zee Entertainment Enterprises. The ICL was not recognised by the Board of Control for Cricket in India (BCCI) or the International Cricket Council (ICC) and the BCCI were not pleased with its committee members joining the ICL executive board. To prevent players from joining the ICL, the BCCI increased the prize money in their own domestic tournaments and also imposed lifetime bans on players joining the ICL, which was considered a rebel league by the board.
        </p>
        <h2>Foundation</h2>
        <p>On 13 September 2007, the BCCI announced the launch of a franchise-based Twenty20 cricket competition called Indian Premier League whose first season was slated to start in April 2008, in a "high-profile ceremony" in New Delhi. BCCI vice-president Lalit Modi, said to be the mastermind behind the idea of IPL, spelled out the details of the tournament including its format, the prize money, franchise revenue system and squad composition rules. It was also revealed that the IPL would be run by a seven-man governing council composed of former India players and BCCI officials, and that the top two teams of the IPL would qualify for that year's Champions League Twenty20. Modi also clarified that they had been working on the idea for two years and that IPL was not started as a "knee-jerk reaction" to the ICL. The league's format was similar to that of the Premier League of England and the NBA in the United States.
        <br>
		In order to decide the owners for the new league, an auction was held on 24 January 2008 with the total base prices of the franchises costing around $400 million. At the end of the auction, the winning bidders were announced, as well as the cities the teams would be based in: Bangalore, Chennai, Delhi, Hyderabad, Jaipur, Kolkata, Mohali, and Mumbai. In the end, the franchises were all sold for a total of $723.59 million. The Indian Cricket League soon folded in 2008.
		</p>
        <h2>Expansions and Terminations</h2>
        <p>On 21 March 2010, it was announced that two new franchises – Pune Warriors India and Kochi Tuskers Kerala – would join the league before the fourth season in 2011. Sahara Adventure Sports Group bought the Pune franchise for $370 million while Rendezvous Sports World bought the Kochi franchise for $333.3 million. However, one year later, on 11 November 2011, it was announced that the Kochi Tuskers Kerala side would be terminated following the side breaching the BCCI's terms of conditions.
        <br>
		Then, on 14 September 2012, following the team not being able to find new owners, the BCCI announced that the 2009 champions, the Deccan Chargers, would be terminated. The next month, on 25 October, an auction was held to see who would be the owner of the replacement franchise, with Sun TV Network winning the bid for the Hyderabad franchise. The team would be named Sunrisers Hyderabad.
		<br>
		Pune Warriors India withdrew from the IPL on 21 May 2013 over financial differences with the BCCI. The franchise was officially terminated by the BCCI, on 26 October 2013, on account of the franchise failing to provide the necessary bank guarantee.
		<br>
		On 14 June 2015, it was announced that two-time champions, Chennai Super Kings, and the inaugural season champions, Rajasthan Royals, would be suspended for two seasons following their role in a match-fixing and betting scandal. Then, on 8 December 2015, following an auction, it was revealed that Pune and Rajkot would replace Chennai and Rajasthan for two seasons. The two teams were the Rising Pune Supergiant and the Gujarat Lions.
		</p>
		<h1>Organization</h1>
		<h2>Tournament format</h2>
		<p>Currently, with eight teams, each team plays each other twice in a home-and-away round-robin format in the league phase. At the conclusion of the league stage, the top four teams will qualify for the playoffs. The top two teams from the league phase will play against each other in the first Qualifying match, with the winner going straight to the IPL final and the loser getting another chance to qualify for the IPL final by playing the second Qualifying match. Meanwhile, the third and fourth place teams from league phase play against each other in an eliminator match and the winner from that match will play the loser from the first Qualifying match. The winner of the second Qualifying match will move onto the final to play the winner of the first Qualifying match in the IPL Final match, where the winner will be crowned the Indian Premier League champions.
		</p>
		<h2>Player acquisition, squad composition and salaries</h2>
		<p>A team can acquire players through any of the three ways: the annual player auction, trading players with other teams during the trading windows, and signing replacements for unavailable players. Players sign up for the auction and also set their base price, and are bought by the franchise that bids the highest for them. Unsold players at the auction are eligible to be signed up as replacement signings. In the trading windows, a player can only be traded with his consent, with the franchise paying the difference if any between the old and new contract. If the new contract is worth more than the older one, the difference is shared between the player and the franchise selling the player. There are generally three trading windows–two before the auction, and one after the auction but before the start of the tournament. Players can not be traded outside the trading windows or during the tournament, whereas replacements can be signed before or during the tournament.
		</p>
		Some of the team composition rules (as of 2018 season) are as follows:
		<br>
		<br>
		<ul>
		<li>The squad strength must be between 18 and 25 players, with a maximum of 8 overseas players.</li>
		<li>Salary cap of the entire squad must not exceed ₹80 crore.</li>
		<li>Under-19 players can not be picked unless they have previously played first-class or List A cricket.</li>
		<li>A team can play a maximum of 4 overseas players in their playing eleven.</li>
		</ul>
		<p>
		The term of a player contract is one year, with the franchise having the option to extend the contract by one or two years. Since the 2014 season, the player contracts are denominated in the Indian rupee, before which the contracts were in U.S. dollars. Overseas players can be remunerated in the currency of the player's choice at the exchange rate on either the contract due date or the actual date of payment. Prior to the 2014 season, Indian domestic players were not included in the player auction pool and could be signed up by the franchises at a discrete amount while a fixed sum of ₹10 to 30 lakh would get deducted per signing from the franchise's salary purse. This received significant opposition from franchise owners who complained that richer franchises were "luring players with under-the-table deals" following which the IPL decided to include domestic players in the player auction.
		<br>
		According to a 2015 survey by Sporting Intelligence and ESPN The Magazine, the average IPL salary when pro-rated is US$4.33 million per year, the second highest among all sport leagues in the world. Since the players in IPL are only contracted for the duration of the tournament (less than two months), the weekly IPL salaries are extrapolated pro rata to obtain average annual salary, unlike other sport leagues in which players are contracted by a single team for the entire year.
		</p>
		<h2>Match rules</h2>
		<p>IPL games utilise television timeouts and hence there is no time limit in which teams must complete their innings. However, a penalty may be imposed if the umpires find teams misusing this privilege. Each team is given a two-and-a-half-minute "strategic timeout" during each innings; one must be taken by the bowling team between the ends of the 6th and 9th overs, and one by the batting team between the ends of the 13th and 16th overs.
		Since the 2018 season, the Umpire Decision Review System is being used in all IPL matches, allowing each team one chance to review an on-field umpire's decision per innings.
		</p>
		<h2>Prize money</h2>
		<p>The 2015 season of the IPL offered a total prize money of ₹40 crore (US$5.6 million), with the winning team netting ₹15 crore (US$2.1 million). The first and second runners up received 10 and 7.5 crores, respectively, with the fourth placed team also winning 7.5 crores. The others teams are not awarded any prize money. The IPL rules mandate that half of the prize money must be distributed among the players.
		</p>

      </div>
    </div>
    <div id="footer">
    Made by Adarsh Agrawal, Chinmaya Singh, Vivek Singal
    </div>
  </div>
</body>
</html>


