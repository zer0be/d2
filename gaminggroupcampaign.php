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
include 'includes/function_getSQLValueString.php';

// get the group id from the url
if (isset($_GET['urlGgrp'])) {
  $colname_rsGetCampaigns = $_GET['urlGgrp'];
}

mysql_select_db($database_dbDescent, $dbDescent);

// Get the campaign details from the tbgames table.
$query_rsGetCampaigns = sprintf("SELECT * FROM tbgames INNER JOIN tbcampaign ON tbgames.game_camp_id = tbcampaign.cam_id INNER JOIN tbgroup ON tbgames.game_grp_id = tbgroup.grp_id WHERE game_grp_id = %s  ORDER BY game_timestamp DESC", GetSQLValueString($colname_rsGetCampaigns, "int"));
$rsGetCampaigns = mysql_query($query_rsGetCampaigns, $dbDescent) or die(mysql_error());
$row_rsGetCampaigns = mysql_fetch_assoc($rsGetCampaigns);
$totalRows_rsGetCampaigns = mysql_num_rows($rsGetCampaigns);

$getCampaigns = array();

do {
  $groupName = $row_rsGetCampaigns['grp_name'];
  $groupCity = $row_rsGetCampaigns['grp_city'];
  $groupCountry = $row_rsGetCampaigns['grp_state_country'];

  $query_rsGetQuestAmount = sprintf("SELECT * FROM tbquests_progress WHERE progress_game_id = %s", GetSQLValueString($row_rsGetCampaigns['game_id'], "int"));
  $rsGetQuestAmount = mysql_query($query_rsGetQuestAmount, $dbDescent) or die(mysql_error());
  $row_rsGetQuestAmount = mysql_fetch_assoc($rsGetQuestAmount);
  $totalRows_rsGetQuestAmount = mysql_num_rows($rsGetQuestAmount);

  $query_rsCharData = sprintf("SELECT * FROM tbcharacters INNER JOIN tbheroes ON tbcharacters.char_hero = tbheroes.hero_id INNER JOIN tbplayerlist ON tbcharacters.char_player = tbplayerlist.player_id WHERE char_game_id = %s ORDER BY hero_id", GetSQLValueString($row_rsGetCampaigns['game_id'], "int"));
  $rsCharData = mysql_query($query_rsCharData, $dbDescent) or die(mysql_error());
  $row_rsCharData = mysql_fetch_assoc($rsCharData);
  $totalRows_rsCharData = mysql_num_rows($rsCharData);

  $campaign_players = array();

  do {
    $campaign_players[] = array(
      "player" => $row_rsCharData['player_handle'],
      "name" => $row_rsCharData['hero_name'],
      "img" => $row_rsCharData['hero_img'],
      "class" => $row_rsCharData['char_class'],
      "xp" => 0,
    );
  } while ($row_rsCharData = mysql_fetch_assoc($rsCharData));

  $getCampaigns[] = array(
    "game_id" => $row_rsGetCampaigns['game_id'],
    "campaign" => $row_rsGetCampaigns['cam_name'],
    "date" => $row_rsGetCampaigns['game_timestamp'],
    "players" =>  $campaign_players,
    "quest_amount" => $totalRows_rsGetQuestAmount,
  );


} while ($row_rsGetCampaigns = mysql_fetch_assoc($rsGetCampaigns));




?>

<html>
  <head><?php 
    $pagetitle = $groupName . "'s Campaigns";
    include 'head.php'; ?>
  </head>
  <body>

    <?php
      include 'navbar.php'; 
      include 'banner.php';
    ?>

    <div class="container grey campaigns-overview">
      <div class="text-center">
        <h1 class="gaming-group"><?php echo $groupName . "'s Campaigns";?></h1>
        <p><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span><?php echo ' ' . $groupCity . ", " . $groupCountry;?></p>
      </div>


      <div class="campaigns-overview row"><?php
        foreach ($getCampaigns as $gc){
          ?>
          <a href="campaign_overview.php?urlGamingID=<?php echo $gc['game_id']; ?>">
            <div class="col-sm-6 campaign-col">
              <div class="row">
                <div class="col-sm-1 hidden-xs"></div>
                <div class="col-sm-10">
                  <h2><?php echo $gc['campaign'];?></h2>
                </div>
                <div class="col-sm-1 hidden-xs"></div>
              </div>
              <div id="heroes">
                <div class="col-sm-1 hidden-xs hero"></div><?php 
                // loop through heroes  
                foreach ($gc['players'] as $h){ ?>
                  <div class="col-sm-2 hero" style="background: url('img/heroes/<?php print$h['img'];?> ') center; background-size: cover;">
                    <div class="name text-center"><?php print $h['name']; ?></div>
                    <div class="class text-center"><?php print $h['class']; ?></div>
                    <div class="row">
                      <div class="player text-center"><?php print $h['player']; ?></div>
                    </div>
                  </div> <!-- close hero --> <?php
                } //close foreach

                for ($x = count($gc['players']); $x < 5; $x++){ ?>
                  <div class="col-sm-2 hero" style="background: url('img/heroes/nohero.jpg') center; background-size: cover;"></div><?php 
                } ?>
                <div class="col-sm-1 hidden-xs hero"></div>
                  
              </div> <!-- close heroes-campaign -->
              <div class="row">
                <div class="col-sm-1 hidden-xs"></div>
                <div class="col-sm-10 text-muted">
                  <div class="row">
                    <div class="col-sm-1"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></div><div class="col-sm-11"><?php echo '  Started: ' . $gc['date'];?></div>
                    <div class="col-sm-1"><span class="glyphicon glyphicon-book" aria-hidden="true"></span></div><div class="col-sm-11"><?php echo '  Quests/rumors finished: ' . $gc['quest_amount']; ?></div>
                  </div>
                </div>
                <div class="col-sm-1 hidden-xs"></div>
              </div>
            </div>
          </a><?php
        } ?>
      </div>  
    </div>
  </body>
</html>
