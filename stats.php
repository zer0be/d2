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


// Get the quests
//$query_rsAllQuests = sprintf("SELECT * FROM tbquests WHERE quest_expansion_id = %s ORDER BY quest_order ASC", GetSQLValueString(0, "int"));
$query_rsAllQuests = sprintf("SELECT * FROM tbquests LEFT JOIN tbcampaign ON tbquests.quest_expansion_id = tbcampaign.cam_id ORDER BY quest_expansion_id ASC");
$rsAllQuests = mysql_query($query_rsAllQuests, $dbDescent) or die(mysql_error());
$row_rsAllQuests = mysql_fetch_assoc($rsAllQuests);
$totalRows_rsAllQuests = mysql_num_rows($rsAllQuests);

$statsArray = array();
do {

  // Get the quests
  // $query_rsEachQuest = sprintf("SELECT *, COUNT(*) FROM tbquests_progress WHERE progress_quest_id = %s ORDER BY COUNT(*)", GetSQLValueString($row_rsAllQuests['quest_id'], "int"));
  // $rsEachQuest = mysql_query($query_rsEachQuest, $dbDescent) or die(mysql_error());
  // $row_rsEachQuest = mysql_fetch_assoc($rsEachQuest);
  // $totalRows_rsEachQuest = mysql_num_rows($rsEachQuest);

  $query_rsEachQuest = sprintf("SELECT * FROM tbquests_progress WHERE progress_quest_id = %s", GetSQLValueString($row_rsAllQuests['quest_id'], "int"));
  $rsEachQuest = mysql_query($query_rsEachQuest, $dbDescent) or die(mysql_error());
  $row_rsEachQuest = mysql_fetch_assoc($rsEachQuest);
  $totalRows_rsEachQuest = mysql_num_rows($rsEachQuest);

  if (isset($row_rsEachQuest['progress_quest_winner'])){

    $statsArray[$row_rsAllQuests['quest_id']] = array(
      "quest_name" => $row_rsAllQuests['quest_name'],
      "quest_campaign" => $row_rsAllQuests['cam_name'],
      "expansion_id" => $row_rsAllQuests['quest_expansion_id'],
      "hero_wins" => 0,
      "overlord_wins" => 0,
      "count" => 0,
      "selected_monsters_enc1" => NULL,
      "selected_monsters_enc2" => NULL,
    );

    do {
      if ($row_rsEachQuest['progress_quest_winner'] == "Heroes Win"){
        $statsArray[$row_rsAllQuests['quest_id']]['hero_wins'] += 1;
        $statsArray[$row_rsAllQuests['quest_id']]['count'] += 1;
      }
      if ($row_rsEachQuest['progress_quest_winner'] == "Overlord Wins"){
        $statsArray[$row_rsAllQuests['quest_id']]['overlord_wins'] += 1;
        $statsArray[$row_rsAllQuests['quest_id']]['count'] += 1;
      }

      if ($row_rsEachQuest['progress_enc1_monsters'] != NULL){
        $statsArray[$row_rsAllQuests['quest_id']]['selected_monsters_enc1'] = $statsArray[$row_rsAllQuests['quest_id']]['selected_monsters_enc1'] . $row_rsEachQuest['progress_enc1_monsters'] . ',';
      }

      if ($row_rsEachQuest['progress_enc2_monsters'] != NULL){
        $statsArray[$row_rsAllQuests['quest_id']]['selected_monsters_enc2'] = $statsArray[$row_rsAllQuests['quest_id']]['selected_monsters_enc2'] . $row_rsEachQuest['progress_enc2_monsters'] . ',';
      }
      
    } while ($row_rsEachQuest = mysql_fetch_assoc($rsEachQuest));

    if ($statsArray[$row_rsAllQuests['quest_id']]['selected_monsters_enc1'] != NULL){
      $topMonsters_enc1_trim = rtrim($statsArray[$row_rsAllQuests['quest_id']]['selected_monsters_enc1'], ",");
      $topMonsters_enc1_expl = explode(',',$topMonsters_enc1_trim);
      $topMonsters_enc1_count = array_count_values($topMonsters_enc1_expl);
      $topMonsters_enc1 = "";

      $countEnc1 = count(explode(',',$row_rsAllQuests['quest_enc1_monsters']));
      $i = 0;    
      foreach ($topMonsters_enc1_count as $key => $value){
        if ($i < $countEnc1){
          foreach ($allMonsters as $am){
            if ($key == $am['id'])
              $topMonsters_enc1 = $topMonsters_enc1 . $am['name'] . ", ";
          }
        }
        $i++;
      }

      $topMonsters_enc1 = rtrim($topMonsters_enc1, ", ");
      $statsArray[$row_rsAllQuests['quest_id']]['selected_monsters_enc1'] = $topMonsters_enc1;
    }

    if ($statsArray[$row_rsAllQuests['quest_id']]['selected_monsters_enc2'] != NULL){
      $topMonsters_enc2_trim = rtrim($statsArray[$row_rsAllQuests['quest_id']]['selected_monsters_enc2'], ",");
      $topMonsters_enc2_expl = explode(',',$topMonsters_enc2_trim);
      $topMonsters_enc2_count = array_count_values($topMonsters_enc2_expl);
      $topMonsters_enc2 = "";

      $countEnc2 = count(explode(',',$row_rsAllQuests['quest_enc2_monsters']));
      $i = 0;    
      foreach ($topMonsters_enc2_count as $key => $value){
        if ($i < $countEnc2){
          foreach ($allMonsters as $am){
            if ($key == $am['id'])
              $topMonsters_enc2 = $topMonsters_enc2 . $am['name'] . ", ";
          }
        }
        $i++;
      }

      $topMonsters_enc2 = rtrim($topMonsters_enc2, ", ");
      $statsArray[$row_rsAllQuests['quest_id']]['selected_monsters_enc2'] = $topMonsters_enc2;
    }

   

  }

} while ($row_rsAllQuests = mysql_fetch_assoc($rsAllQuests));

// echo '<pre>';
// var_dump($statsArray);
// echo '</pre>';

//Select heroes based on occurence
$query_rsTopHeroes = sprintf("SELECT hero_name, hero_type, char_hero, hero_expansion, cam_name, COUNT(*) as count FROM tbcharacters INNER JOIN tbheroes ON char_hero = hero_id INNER JOIN tbcampaign ON tbheroes.hero_expansion = tbcampaign.cam_id GROUP BY char_hero ORDER BY count DESC");
$rsTopHeroes = mysql_query($query_rsTopHeroes, $dbDescent) or die(mysql_error());
$row_rsTopHeroes = mysql_fetch_assoc($rsTopHeroes);
$totalRows_rsTopHeroes = mysql_num_rows($rsTopHeroes);

$topHeroes = array();
$topMages = array();
$topMagesCount = 0;
$topWarriors = array();
$topWarriorsCount = 0;
$topScouts = array();
$topScoutsCount = 0;
$topHealers = array();
$topHealersCount = 0;

do {
  $HeroSelectedPerc = ($row_rsTopHeroes['count'] / $totalGames) * 100;

  $topheroes[] = array(
    "hero_name" => $row_rsTopHeroes['hero_name'],
    "cam_name" => $row_rsTopHeroes['cam_name'],
    "percent" => $HeroSelectedPerc,
  );

  switch ($row_rsTopHeroes['hero_type']){
    case 'Mage':
      $topMages[] = array(
        "hero_name" => $row_rsTopHeroes['hero_name'],
        "cam_name" => $row_rsTopHeroes['cam_name'],
        "count" => $row_rsTopHeroes['count'],
      );
      $topMagesCount = $topMagesCount + $row_rsTopHeroes['count'];
      break;
    case 'Warrior':
      $topWarriors[] = array(
        "hero_name" => $row_rsTopHeroes['hero_name'],
        "cam_name" => $row_rsTopHeroes['cam_name'],
        "count" => $row_rsTopHeroes['count'],
      );
      $topWarriorsCount = $topWarriorsCount + $row_rsTopHeroes['count'];
      break;
    case 'Scout':
      $topScouts[] = array(
        "hero_name" => $row_rsTopHeroes['hero_name'],
        "cam_name" => $row_rsTopHeroes['cam_name'],
        "count" => $row_rsTopHeroes['count'],
      );
      $topScoutsCount = $topScoutsCount + $row_rsTopHeroes['count'];
      break;
    case 'Healer':
      $topHealers[] = array(
        "hero_name" => $row_rsTopHeroes['hero_name'],
        "cam_name" => $row_rsTopHeroes['cam_name'],
        "count" => $row_rsTopHeroes['count'],
      );
      $topHealersCount = $topHealersCount + $row_rsTopHeroes['count'];
      break;
  }

} while ($row_rsTopHeroes = mysql_fetch_assoc($rsTopHeroes));


$query_rsTopTravel = sprintf("SELECT travel_aq_event_id, travel_name, travel_id, travel_exp_id, cam_id, cam_name, COUNT(*) as count FROM tbtravel_aquired INNER JOIN tbtravel ON travel_aq_event_id = travel_id INNER JOIN tbcampaign ON travel_exp_id = cam_id GROUP BY travel_name ORDER BY count DESC");
$rsTopTravel = mysql_query($query_rsTopTravel, $dbDescent) or die(mysql_error());
$row_rsTopTravel = mysql_fetch_assoc($rsTopTravel);
$totalRows_rsTopTravel = mysql_num_rows($rsTopTravel);

$topTravel = array();
$topTravelCount = 0;
do{

  $tempCam = $row_rsTopTravel['cam_name'];
  if ($row_rsTopTravel['travel_name'] == "No Event"){
    $tempCam = "All";
  }

  if ($row_rsTopTravel['travel_name'] != "Skipped"){
    $topTravel[] = array(
      "name" => $row_rsTopTravel['travel_name'],
      "cam_name" => $tempCam,
      "count" => $row_rsTopTravel['count'],
    );
  }

  // if ($row_rsTopTravel['travel_name'] != "No Event" && $row_rsTopTravel['travel_name'] != "Skipped"){
    $topTravelCount = $topTravelCount + $row_rsTopTravel['count'];
  // }

} while ($row_rsTopTravel = mysql_fetch_assoc($rsTopTravel));

?>

<html>
  <head><?php 
    $pagetitle = "Statistics";
    include 'head.php'; ?>
  </head>
  <body>
    <?php 
      include 'navbar.php'; 
      include 'banner.php';
    ?>

    <div class="container grey">

      <div class="row">
        <div id="quests" class="col-md-6">
          <?php include 'stats_quests_data.php'; ?>
        </div>

        <div id="heroes" class="col-md-6"> 
          <?php include 'stats_heroes.php'; ?>
        </div>

      </div>
    </div>
  </body>
</html>
<?php
/*
echo '<pre>';
var_dump($statsArray);
echo '</pre>';
*/

?>