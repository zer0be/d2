<?php

//-----------------------//
//remove me after include//
//-----------------------//

//include the db
require_once('Connections/dbDescent.php'); 

//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

//include functions
include 'includes/function_logout.php';
include 'includes/function_createProgressBar.php';
include 'includes/function_getSQLValueString.php';
include 'includes/function_getCampaignLabel.php';

//select the database
mysql_select_db($database_dbDescent, $dbDescent);

// Get all games
$query_rsAllGames = sprintf("SELECT * FROM tbgames ORDER BY game_id ASC");
$rsAllGames = mysql_query($query_rsAllGames, $dbDescent) or die(mysql_error());
$row_rsAllGames = mysql_fetch_assoc($rsAllGames);
$totalRows_rsAllGames = mysql_num_rows($rsAllGames);

$totalGames = 0;
do {
  $totalGames++; 
} while ($row_rsAllGames = mysql_fetch_assoc($rsAllGames));


// Get Monsters
include 'stats_monsters_data.php';

// Include quest stats
include 'stats_quests_array.php';

?>

<html>

  <head><?php 
    $pagetitle = "Quest Statistics";
    include 'head.php'; ?>
  </head>

  <body><?php

    include 'navbar.php'; 
    include 'banner.php'; ?>

    <div class="container grey">
      <h1>Campaign & Quest Stats</h1>
      <p class="top-lead lead text-muted">Statistics about played quests and encountered travel steps.</p>
      <div class="row">
        <div id="quests" class="col-md-6"><?php 
          include 'stats_quests_campaigns.php'; ?>
        </div>

        <div id="quests" class="col-md-6"><?php 
          include 'stats_quests_quests.php'; ?>
        </div> 
      </div>
    </div>

  </body>

</html>