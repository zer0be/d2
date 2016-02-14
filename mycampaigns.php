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
include 'includes/function_getCampaignLabel.php';

// get the group id from the url
if (isset($_GET['view'])) {
  $view = $_GET['view'];
} else {
  $view = "mine";
}

if (isset($_GET['urlGgrp'])) {
  $urlGgrp = $_GET['urlGgrp'];
}

if (isset($_GET['letter'])) {
  $currentletter = $_GET['letter'];
} else {
  $currentletter = "A";
}

mysql_select_db($database_dbDescent, $dbDescent);


if ($view == "all"){
  // Get the campaign details from the tbgames table.
  $query_rsGetCampaigns = sprintf("SELECT * FROM tbgames INNER JOIN tbcampaign ON tbgames.game_camp_id = tbcampaign.cam_id INNER JOIN tbgroup ON tbgames.game_grp_id = tbgroup.grp_id  ORDER BY grp_name, game_timestamp DESC");
  $rsGetCampaigns = mysql_query($query_rsGetCampaigns, $dbDescent) or die(mysql_error());
  $row_rsGetCampaigns = mysql_fetch_assoc($rsGetCampaigns);
  $totalRows_rsGetCampaigns = mysql_num_rows($rsGetCampaigns);
} else if ($view == "group"){
  // Get the campaign details from the tbgames table.
  $query_rsGetCampaigns = sprintf("SELECT * FROM tbgames INNER JOIN tbcampaign ON tbgames.game_camp_id = tbcampaign.cam_id INNER JOIN tbgroup ON tbgames.game_grp_id = tbgroup.grp_id  WHERE grp_id = %s ORDER BY grp_name, game_timestamp DESC", GetSQLValueString($urlGgrp, "int"));
  $rsGetCampaigns = mysql_query($query_rsGetCampaigns, $dbDescent) or die(mysql_error());
  $row_rsGetCampaigns = mysql_fetch_assoc($rsGetCampaigns);
  $totalRows_rsGetCampaigns = mysql_num_rows($rsGetCampaigns);
} else {
  include 'includes/protected_page.php';
  // Get the campaign details from the tbgames table.
  $query_rsGetCampaigns = sprintf("SELECT * FROM tbgames INNER JOIN tbcampaign ON tbgames.game_camp_id = tbcampaign.cam_id INNER JOIN tbgroup ON tbgames.game_grp_id = tbgroup.grp_id WHERE game_dm = %s  ORDER BY grp_name, game_timestamp DESC", GetSQLValueString($_SESSION['user']['id'], "int"));
  $rsGetCampaigns = mysql_query($query_rsGetCampaigns, $dbDescent) or die(mysql_error());
  $row_rsGetCampaigns = mysql_fetch_assoc($rsGetCampaigns);
  $totalRows_rsGetCampaigns = mysql_num_rows($rsGetCampaigns);


  if ($totalRows_rsGetCampaigns == 0){
    header("Location: create_campaign.php");
    die("Redirecting to create_campaign.php");
  }
}

$getCampaigns = array();
$groupName = array();
do {
  $groupName[] = $row_rsGetCampaigns['grp_name'];

  $query_rsGetQuestAmount = sprintf("SELECT * FROM tbquests_progress WHERE progress_game_id = %s ORDER BY progress_timestamp DESC", GetSQLValueString($row_rsGetCampaigns['game_id'], "int"));
  $rsGetQuestAmount = mysql_query($query_rsGetQuestAmount, $dbDescent) or die(mysql_error());
  $row_rsGetQuestAmount = mysql_fetch_assoc($rsGetQuestAmount);
  $totalRows_rsGetQuestAmount = mysql_num_rows($rsGetQuestAmount);

  $query_rsCharData = sprintf("SELECT * FROM tbcharacters INNER JOIN tbheroes ON tbcharacters.char_hero = tbheroes.hero_id INNER JOIN tbplayerlist ON tbcharacters.char_player = tbplayerlist.player_id WHERE char_game_id = %s ORDER BY hero_id", GetSQLValueString($row_rsGetCampaigns['game_id'], "int"));
  $rsCharData = mysql_query($query_rsCharData, $dbDescent) or die(mysql_error());
  $row_rsCharData = mysql_fetch_assoc($rsCharData);
  $totalRows_rsCharData = mysql_num_rows($rsCharData);

  $campaign_players = array();
  $expansionsSelectedNames = array();
  $expansionsSelected = explode(",", $row_rsGetCampaigns['game_expansions']);

  foreach ($expansionsSelected as $exp){
    $query_rsGetExpansions = sprintf("SELECT * FROM tbcampaign WHERE cam_id = %s", GetSQLValueString($exp, "int"));
    $rsGetExpansions = mysql_query($query_rsGetExpansions, $dbDescent) or die(mysql_error());
    $row_rsGetExpansions = mysql_fetch_assoc($rsGetExpansions);

    do{
      if ($row_rsGetExpansions['cam_type'] != "lieutenant"){
        $expansionsSelectedNames[] = $row_rsGetExpansions['cam_name'];
      }
    } while ($row_rsGetExpansions = mysql_fetch_assoc($rsGetExpansions));
  }

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
    "last_date" => $row_rsGetQuestAmount['progress_timestamp'],
    "players" =>  $campaign_players,
    "quest_amount" => $totalRows_rsGetQuestAmount,
    "grp_name" => $row_rsGetCampaigns['grp_name'],
    "expansions" => $expansionsSelectedNames,
  );


} while ($row_rsGetCampaigns = mysql_fetch_assoc($rsGetCampaigns));

$groupName = array_unique($groupName);

$usedLetters = array();
foreach ($groupName as $gn){ 
  $firstletter = substr($gn, 0, 1);
  $firstletter = strtoupper($firstletter);
  $usedLetters[] = $firstletter;
}
$usedLetters = array_unique($usedLetters);

$abc = array("0-9-?","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");

?>

<html>
  <head><?php 
    if ($view == "all"){
      $pagetitle = "All Campaigns";
    } else if ($view == "group"){ 
      $pagetitle = $groupName[0] . "'s Campaigns";
    } else { 
      $pagetitle = "My Campaigns";
    } 
    include 'head.php'; ?>
  </head>
  <body>

    <?php 
      include 'navbar.php'; 
      include 'banner.php';
    ?>

    

    <div class="container grey campaigns-overview">
      <div><?php
        if ($view == "all"){ ?>
          <h1 class="gaming-group">All Campaigns</h1>
          <p class="top-lead lead text-muted">An overview of all gaming groups and their campaigns.</p>
          <nav>
            <ul class="pagination pagination-sm"><?php
              foreach ($abc as $letter){
                echo '<li ';
                if ($letter == $currentletter){
                  echo 'class="active"';
                }
                if (!in_array($letter, $usedLetters) && $letter != "0-9-?"){
                  echo 'class="disabled"';
                }
                echo '><a href="mycampaigns.php?view=all&letter='. $letter . '">' . $letter . '</a></li>';
              } ?>
            </ul>
          </nav><?php
        } else if ($view == "group"){ ?>
          <h1 class="gaming-group"><?php echo $groupName[0] ?>'s Campaigns</h1>
          <p class="top-lead lead text-muted">An overview of the campaigns played by <?php echo $groupName[0] ?>.</p><?php
        } else { ?>
          <h1 class="gaming-group">My Campaigns</h1>
          <p class="top-lead lead text-muted">An overview of your gaming groups and current campaigns.</p><?php
        } ?>

      </div>
      <div class="row">&nbsp;</div>

      <?php
      foreach ($groupName as $gn){ 
        $firstletter = substr($gn, 0, 1);
        $firstletter = strtoupper($firstletter);
        if($view != "all" || $firstletter == $currentletter || ($currentletter == "0-9-?" && !in_array($firstletter, $abc))){ ?>
          <div class="row campaigns-overview">
            <div class="col-xs-12">
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h2 class="panel-title"><?php echo $gn; ?></h2>
                </div>
                <div class="panel-body">
                  <div class="row hidden-xs">
                    <div class="col-sm-5">
                      <div class="row">
                        <div class="col-sm-6">
                          <strong>Heroes</strong>
                        </div>
                        <div class="col-sm-6">
                          <strong>Campaign</strong>
                        </div>
                      </div>
                    </div>

                    <div class="col-sm-2">
                      <div class="row">
                        <div class="col-sm-6">
                          <strong>Started</strong>
                        </div>
                        <div class="col-sm-6">
                          <strong>Updated</strong>
                        </div>

                      </div>
                    </div>
                    
                    <div class="col-sm-5">
                      <div class="row">
                        <div class="col-sm-2 text-center">
                            <strong>Quests</strong>
                          </div>
                        <div class="col-sm-10">
                          <strong>Expansions</strong>
                        </div>
                      </div>
                    </div>
                  </div>



                  <?php

                    foreach ($getCampaigns as $gc){
                      if ($gc['grp_name'] == $gn){?>

                        <div class="row campaign-row">
                          <div class="col-sm-5">
                            <div class="row">
                              <div class="col-sm-6"><?php
                                foreach ($gc['players'] as $h){ ?>
                                  <img src="img/heroes/mini_<?php print $h['img'];?>" /><?php
                                } //close foreach

                                for ($x = count($gc['players']); $x < 5; $x++){ ?>
                                  <img src="img/heroes/mini_nohero.jpg" /><?php 
                                } ?>
                              </div>

                              <div class="col-sm-6 text-margin">
                                <a href="campaign_overview.php?urlGamingID=<?php echo ($gc['game_id'] * 43021); ?>"><strong><?php echo $gc['campaign']; ?></strong></a>
                              </div>
                            </div>
                          </div>

                          <div class="col-sm-2 text-margin">
                            <div class="row hidden-xs">

                              <div class="col-sm-6"><?php 
                                  $grpTimestamp = strtotime($gc['date']); 
                                  $grpDate = date('d/m/Y', $grpTimestamp);
                                  $grpTime = date('h:m:s', $grpTimestamp);
                                  echo $grpDate; ?>
                              </div>

                              <div class="col-sm-6"><?php 
                                $grpTimestamp = strtotime($gc['last_date']); 
                                $grpDate2 = date('d/m/Y', $grpTimestamp);
                                $grpTime2 = date('h:m:s', $grpTimestamp);
                                echo $grpDate2; ?>
                              </div>

                            </div>
                            <span class="visible-xs-inline">
                              <strong>Started: </strong><?php echo $grpDate; ?><br />
                              <strong>Updated: </strong><?php echo $grpDate2; ?>
                            </span>

                          </div>

                          <div class="col-sm-5">
                            <span class="visible-xs-inline"><strong>Quests: </strong><?php echo $gc['quest_amount']; ?></span>
                            <div class="row">
                              <div class="col-sm-2 text-margin text-center hidden-xs">
                                <?php echo $gc['quest_amount']; ?>
                              </div>
                              <div class="col-sm-8">
                                <small>
                                  <?php foreach ($gc['expansions'] as $exp){
                                    getCampaignLabel($exp, "mini");
                                    echo ' ';
                                  } ?>
                                </small>
                              </div>

                              <div class="col-sm-2 text-margin text-center"><?php
                                if ($view == "mine"){ ?>
                                  <a title="Delete Campaign" href="mycampaigns_delete.php?urlGamingID=<?php echo $gc['game_id']; ?>"><span class="glyphicon glyphicon-remove-sign text-muted" aria-hidden="true"></span></a><?php
                                } ?>
                              </div>
                            </div>
                          </div>


                        </div><?php

                      }
                    } ?>

                </div>
              </div>
            </div>
          </div><?php
        }
      } ?>
      </div>  
    </div>
  </body>
</html>


<?php
mysql_free_result($rsGetCampaigns);
?>
