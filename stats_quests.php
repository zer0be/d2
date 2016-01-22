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

function getCampaignLabel($var, $view){
  $camClass = "";
  $camName = $var;

  if ($view == "mini"){
    $words = explode(" ", $camName);
    $acronym = "";

    foreach ($words as $w) {
      $acronym .= $w[0];
    }
  } else {
    $acronym = $camName;
  }

  switch($var){
    case "The Shadow Rune":
      $camClass = "label-info";
      break;
    case "Lair of the Wyrm":
      $camClass = "label-danger";
      break;
    case "Labyrinth of Ruin":
      $camClass = "label-warning";
      break;
    case "The Trollfens":
      $camClass = "label-success";
      break;
    case "Shadow of Nerekhall":
      $camClass = "label-primary purple";
      break;
    case "Manor of Ravens":
      $camClass = "label-primary";
      break;
    default:
      $camClass = "label-default";
      break;
  }

  echo '<span class="label ' . $camClass . '">' . $acronym . '</span>';
  // $return = '<span class="label ' . $camClass . '">' . $acronym . '</span>';
  // return $return;

}

function calcQuestWins($overlord, $heroes){
  $total = $overlord + $heroes;

  if ($overlord != 0){
    $OverlordPerc = ($overlord / $total) * 100;
    $olText = 'Overlord: ' . $overlord . ' Win(s)';
  } 

  if ($heroes != 0){
    $HeroPerc = ($heroes / $total) * 100;
    $hText = 'Heroes: ' . $heroes . ' Win(s)';
  } 

  if ($heroes == 0){
    $HeroPerc = 0;
    $hText = 'Heroes: ' . $heroes . ' Win(s)';
  }

  if ($overlord == 0){
     $OverlordPerc = 0;
    $olText = 'Overlord: ' . $overlord .' Win(s)';
  }

  $QuestWins = array(
    "OverlordPerc" => $OverlordPerc,
    "OverlordText" => $olText,
    "HeroPerc" => $HeroPerc,
    "HeroText" => $hText,
  );

  return $QuestWins;
}

//select the database
mysql_select_db($database_dbDescent, $dbDescent);

// Get Games

$query_rsAllGames = sprintf("SELECT * FROM tbgames ORDER BY game_id ASC");
$rsAllGames = mysql_query($query_rsAllGames, $dbDescent) or die(mysql_error());
$row_rsAllGames = mysql_fetch_assoc($rsAllGames);
$totalRows_rsAllGames = mysql_num_rows($rsAllGames);

$totalGames = 0;
do {
  $totalGames++; 
} while ($row_rsAllGames = mysql_fetch_assoc($rsAllGames));


// Select Monsters
$query_rsMonsters = sprintf("SELECT * FROM tbmonsters INNER JOIN tbcampaign ON tbmonsters.monster_exp_id = tbcampaign.cam_id");
$rsMonsters = mysql_query($query_rsMonsters, $dbDescent) or die(mysql_error());
$row_rsMonsters = mysql_fetch_assoc($rsMonsters);
$totalRows_rsMonsters = mysql_num_rows($rsMonsters);

$allMonsters = array();
do {

  $allMonsters[] = array(
    "id" => $row_rsMonsters['monster_id'],
    "name" => $row_rsMonsters['monster_name'],
    "expansion" => $row_rsMonsters['cam_name'],
  );

} while ($row_rsMonsters = mysql_fetch_assoc($rsMonsters));


include 'stats_quests_array.php';


$query_rsTopTravel = sprintf("SELECT travel_aq_event_id, travel_name, travel_id, travel_exp_id, travel_type, cam_id, cam_name, COUNT(*) as count FROM tbtravel_aquired INNER JOIN tbtravel ON travel_aq_event_id = travel_id INNER JOIN tbcampaign ON travel_exp_id = cam_id GROUP BY travel_name ORDER BY count DESC");
$rsTopTravel = mysql_query($query_rsTopTravel, $dbDescent) or die(mysql_error());
$row_rsTopTravel = mysql_fetch_assoc($rsTopTravel);
$totalRows_rsTopTravel = mysql_num_rows($rsTopTravel);

$topTravel = array();
$topTravel2 = array();
$topTravelCount = 0;
do{

  $tempCam = $row_rsTopTravel['cam_name'];
  if ($row_rsTopTravel['travel_name'] == "No Event"){
    $tempCam = "All";
  }

  if ($row_rsTopTravel['travel_name'] != "Skipped" && $row_rsTopTravel['travel_name'] != "No Event"){
    $topTravel[] = array(
      "name" => $row_rsTopTravel['travel_name'],
      "cam_name" => $tempCam,
      "count" => $row_rsTopTravel['count'],
    );
    if($row_rsTopTravel['travel_type'] != "all" && $row_rsTopTravel['travel_type'] != "item"){
      $topTravel2[$row_rsTopTravel['travel_type']][] = array(
        "name" => $row_rsTopTravel['travel_name'],
        "cam_name" => $tempCam,
        "count" => $row_rsTopTravel['count'],
      );
    }
    
  }

  if ($row_rsTopTravel['travel_name'] != "No Event" && $row_rsTopTravel['travel_name'] != "Skipped"){
    $topTravelCount = $topTravelCount + $row_rsTopTravel['count'];
  }

} while ($row_rsTopTravel = mysql_fetch_assoc($rsTopTravel));


// echo '<pre>';
// var_dump($topTravel2);
// echo '</pre>';

?>

<html>
  <head><?php 
    $pagetitle = "Quest Statistics";
    include 'head.php'; ?>
  </head>
  <body>
    <?php 
      include 'navbar.php'; 
      include 'banner.php';
    ?>

    <div class="container grey">
      <h1>Quest Stats</h1>
      <p class="top-lead lead text-muted">Statistics about played quests and encountered travel steps.</p>

      <div class="row">
        <div id="quests" class="col-md-6">
          <?php include 'stats_quests_data_cam.php'; ?>
        </div>

        <div id="quests" class="col-md-6">
          <?php include 'stats_quests_data.php'; ?>
        </div> 

      </div><?php

      $i = 0;
      foreach ($topTravel2 as $key => $tt2){ 
        if ($i == 0){
          echo '<div class="row">';
        }
        $i++; ?>
        <div class="col-md-6">
          <div class="row">
            <div class="col-md-12">&nbsp;</div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h2 class="panel-title"><?php echo ucwords($key) . " Travel Steps"; ?></h2>
                </div>

                <div class="panel-body"><?php
                  $topTravelCount2 = 0;
                  foreach ($tt2 as $ttx) {
                    $topTravelCount2 += $ttx['count'];
                  }
                  foreach ($tt2 as $ttx) { ?>
                    <div class="row stats-row">
                      <div class="col-xs-12"><?php
                        $TravelPerc2 = ($ttx['count'] / $topTravelCount2) * 100; ?>

                        <div class="row"> 
                          <div class="col-md-6"> 
                            <p><strong><?php echo $ttx['name']; ?></strong></p>
                          </div>
                          <div class="col-md-6 text-right"> 
                            <?php getCampaignLabel($ttx['cam_name'], "normal"); ?>
                          </div>
                        </div>
                        <div class="row"> 
                          <div class="col-md-12">    
                            <div class="progress">
                              <?php createProgressBar($TravelPerc2, "of " . ucwords($key) . " Travel Steps", 0, ""); ?>  
                            </div>
                          </div>
                        </div>
                      </div>
                    </div><?php
                  } ?>
                </div>
              </div> 
            </div>
          </div>
        </div><?php
        if ($i == 2){
          echo '</div>';
          $i = 0;
        }
      } ?>

    </div>
  </body>
</html>