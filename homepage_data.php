<?php

$currentPage = $_SERVER["PHP_SELF"];

// FIX ME: Check if these db queries shouldn't be rewritten to something more simple
mysql_select_db($database_dbDescent, $dbDescent);
// Select all campaigns
$query_rsCampaignList = "SELECT cam_id, cam_name, expansion, cam_logo, cam_icon FROM tbcampaign WHERE cam_type = 'full' OR cam_type = 'mini' OR cam_type = 'book' OR cam_type = 'act-one' ORDER BY cam_id ASC";
$rsCampaignList = mysql_query($query_rsCampaignList, $dbDescent) or die(mysql_error());
$row_rsCampaignList = mysql_fetch_assoc($rsCampaignList);
$totalRows_rsCampaignList = mysql_num_rows($rsCampaignList);

// Select all progress/games
$query_rsSelectGroup = sprintf("SELECT * FROM tbquests_progress INNER JOIN tbgames ON progress_game_id = game_id INNER JOIN tbgroup ON game_grp_id = grp_id INNER JOIN users ON grp_startedby = id ORDER BY progress_timestamp DESC");
$rsSelectGroup = mysql_query($query_rsSelectGroup, $dbDescent) or die(mysql_error());
$row_rsSelectGroup = mysql_fetch_assoc($rsSelectGroup);
$totalRows_rsSelectGroup = mysql_num_rows($rsSelectGroup);

$gamingGroups = array();
$uniqueGroups = array();
$lastcampaign = "";
do {

	if(isset($_SESSION['user']['username']) && $row_rsSelectGroup['username'] == $_SESSION['user']['username']){
  	if($lastcampaign == ""){
    	$lastcampaign = $row_rsSelectGroup['game_id'];
    }
  }

  $query_rsSelectGroupGames = sprintf("SELECT * FROM tbgames WHERE game_grp_id = %s", GetSQLValueString($row_rsSelectGroup['grp_id'], "int"));
  $rsSelectGroupGames = mysql_query($query_rsSelectGroupGames, $dbDescent) or die(mysql_error());
  $row_rsSelectGroupGames = mysql_fetch_assoc($rsSelectGroupGames);
  $totalRows_rsSelectGroupGames = mysql_num_rows($rsSelectGroupGames);

  $countGames = 0;
  
  do{
    $countGames += 1;
  } while ($row_rsSelectGroupGames = mysql_fetch_assoc($rsSelectGroupGames));

  
  if (!in_array($row_rsSelectGroup['grp_id'], $uniqueGroups)){
    $uniqueGroups[] = $row_rsSelectGroup['grp_id'];

    $gamingGroups[] = array(
      "grp_id" => $row_rsSelectGroup['grp_id'],
      "grp_name" => $row_rsSelectGroup['grp_name'],
      "grp_city" => $row_rsSelectGroup['grp_city'],
      "grp_state_country" => $row_rsSelectGroup['grp_state_country'],
      "dm" => $row_rsSelectGroup['username'],
      "special" => $row_rsSelectGroup['special'],
      "timestamp" => $row_rsSelectGroup['progress_timestamp'],
      "campaigns" => $countGames,
    );
  }
  
} while ($row_rsSelectGroup = mysql_fetch_assoc($rsSelectGroup));

// Select all games
$query_rsGamesStats = sprintf("SELECT * FROM tbgames");
$rsGamesStats = mysql_query($query_rsGamesStats, $dbDescent) or die(mysql_error());
$row_rsGamesStats = mysql_fetch_assoc($rsGamesStats);
$totalRows_rsGamesStats = mysql_num_rows($rsGamesStats);

// Select all characters/heroes
$query_rsCharStats = sprintf("SELECT * FROM tbcharacters INNER JOIN tbheroes ON char_hero = hero_id INNER JOIN tbplayerlist ON tbcharacters.char_player = tbplayerlist.player_id");
$rsCharStats = mysql_query($query_rsCharStats, $dbDescent) or die(mysql_error());
$row_rsCharStats = mysql_fetch_assoc($rsCharStats);
$totalRows_rsCharStats = mysql_num_rows($rsCharStats);

$OverlordTotal = 0;
$HeroesTotal = 0;

// Count how many heroes and overlords there are
do{
  if($row_rsCharStats['hero_type'] == "Overlord"){
    $OverlordTotal += 1;
  } else {
    $HeroesTotal += 1;
  }
} while ($row_rsCharStats = mysql_fetch_assoc($rsCharStats));

$OverlordQuests = 0;
$HeroesQuests = 0;
$OverlordQuestsFB = 0;
$HeroesQuestsFB = 0;
$undecidedQuests = 0;
$noFB = 0;

// Select all questprogress
$query_rsQuestStats = sprintf("SELECT * FROM tbquests_progress");
$rsQuestStats = mysql_query($query_rsQuestStats, $dbDescent) or die(mysql_error());
$row_rsQuestStats = mysql_fetch_assoc($rsQuestStats);
$totalRows_rsQuestStats = mysql_num_rows($rsQuestStats);

// Count the quests that are won by Heroes/Overlord/Undecided
do{
  if($row_rsQuestStats['progress_quest_winner'] == "Overlord Wins"){
    $OverlordQuests += 1;
    if($row_rsQuestStats['progress_quest_id'] != 0){
    	$OverlordQuestsFB += 1;
    } else {
    	$noFB += 1;
    }
  } else if($row_rsQuestStats['progress_quest_winner'] == "Heroes Win"){
    $HeroesQuests += 1;
    if($row_rsQuestStats['progress_quest_id'] != 0){
    	$HeroesQuestsFB += 1;
    } else {
    	$noFB += 1;
    }
  } else {
    $undecidedQuests += 1;
  }
} while ($row_rsQuestStats = mysql_fetch_assoc($rsQuestStats));

// Calculate the percentage for progressbar
$OverlordQuestsPerc = ($OverlordQuests /($totalRows_rsQuestStats - $undecidedQuests)) * 100;
$HeroesQuestsPerc = ($HeroesQuests / ($totalRows_rsQuestStats - $undecidedQuests)) * 100;

$OverlordQuestsPercFB = ($OverlordQuestsFB /($totalRows_rsQuestStats - $undecidedQuests - $noFB)) * 100;
$HeroesQuestsPercFB = ($HeroesQuestsFB / ($totalRows_rsQuestStats - $undecidedQuests - $noFB)) * 100;
