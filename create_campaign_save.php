<?php

  $minicampaigns = array(1,3,5);
  $oneactcampaigns = array(30);

  // Insert the values into the ongoing games table
  $insertSQL = sprintf("INSERT INTO tbgames (game_grp_id, game_dm, game_camp_id, game_expansions) VALUES (%s, %s, %s, %s)",
                          GetSQLValueString($_SESSION["campaigndata"]['group_id'], "int"),
                          GetSQLValueString($_SESSION['user']['id'], "int"),
                          GetSQLValueString($_SESSION["campaigndata"]['campaign_id'], "int"),
                          GetSQLValueString($_SESSION["campaigndata"]['expansions'], "text"));
  $Result = mysql_query($insertSQL, $dbDescent) or die(mysql_error());

  // Get the id of the row that was inserted, so we can use it in the url for the next page
  $ResultID = mysql_insert_id();

  // Immediately create the first quest of the campaign 
  
  if (!in_array($_SESSION["campaigndata"]['campaign_id'], $minicampaigns)){
    $query_rsIntroduction = sprintf("SELECT * FROM tbquests WHERE quest_act = %s AND quest_expansion_id = %s",
                          GetSQLValueString("Introduction", "text"),
                          GetSQLValueString($_SESSION["campaigndata"]['campaign_id'], "int")); 
    $rsIntroduction = mysql_query($query_rsIntroduction, $dbDescent) or die(mysql_error());
    $row_rsIntroduction = mysql_fetch_assoc($rsIntroduction);
    $totalRows_rsIntroduction = mysql_num_rows($rsIntroduction);

    $insertSQL2 = sprintf("INSERT INTO tbquests_progress (progress_game_id, progress_quest_id, progress_quest_type) VALUES (%s, %s, %s)",
                            GetSQLValueString($ResultID, "int"),
                            GetSQLValueString($row_rsIntroduction['quest_id'], "int"),
                            GetSQLValueString("Quest", "text"));


    $Result2 = mysql_query($insertSQL2, $dbDescent) or die(mysql_error());
  } else {
    $query_rsIntroduction = sprintf("SELECT * FROM tbquests WHERE quest_act = %s AND quest_expansion_id = %s",
                          GetSQLValueString("Setup", "text"),
                          GetSQLValueString($_SESSION["campaigndata"]['campaign_id'], "int"));
    $rsIntroduction = mysql_query($query_rsIntroduction, $dbDescent) or die(mysql_error());
    $row_rsIntroduction = mysql_fetch_assoc($rsIntroduction);
    $totalRows_rsIntroduction = mysql_num_rows($rsIntroduction);

    $insertSQL2 = sprintf("INSERT INTO tbquests_progress (progress_game_id, progress_quest_id, progress_quest_type, progress_quest_winner) VALUES (%s, %s, %s, %s)",
                            GetSQLValueString($ResultID, "int"),
                            GetSQLValueString($row_rsIntroduction['quest_id'], "int"),
                            GetSQLValueString("Quest", "text"),
                            GetSQLValueString("Setup", "text"));


    $Result2 = mysql_query($insertSQL2, $dbDescent) or die(mysql_error());
  }
  
  


$noGoldOverlord = 1;

foreach ($_SESSION["playerdata"] as $xshdb){
  // Save Character
  if (in_array($_SESSION["campaigndata"]['campaign_id'], $minicampaigns)){
    $insertSQL = sprintf("INSERT INTO tbcharacters (char_ggrp_id, char_game_id, char_player, char_hero, char_class, char_xp) VALUES (%s, %s, %s, %s, %s, %s)",
                            GetSQLValueString($_SESSION["campaigndata"]['group_id'], "int"),
                            GetSQLValueString($ResultID, "int"),
                            GetSQLValueString($xshdb['player'], "text"),
                            GetSQLValueString($xshdb['id'], "int"),
                            GetSQLValueString($xshdb['class'], "text"),
                            GetSQLValueString(4, "int"));

    $Result1 = mysql_query($insertSQL, $dbDescent) or die(mysql_error());
    $Result1ID = mysql_insert_id();

    if ($noGoldOverlord == 0){
      $insertSQLG = sprintf("UPDATE tbgames SET game_gold = game_gold + %s WHERE game_id = %s",
                          GetSQLValueString(100, "int"),
                          GetSQLValueString($ResultID, "int"));
      $ResultG = mysql_query($insertSQLG, $dbDescent) or die(mysql_error());
    }
    $noGoldOverlord = 0;
  } else if (in_array($_SESSION["campaigndata"]['campaign_id'], $oneactcampaigns)){
    $insertSQL = sprintf("INSERT INTO tbcharacters (char_ggrp_id, char_game_id, char_player, char_hero, char_class, char_xp) VALUES (%s, %s, %s, %s, %s, %s)",
                            GetSQLValueString($_SESSION["campaigndata"]['group_id'], "int"),
                            GetSQLValueString($ResultID, "int"),
                            GetSQLValueString($xshdb['player'], "text"),
                            GetSQLValueString($xshdb['id'], "int"),
                            GetSQLValueString($xshdb['class'], "text"),
                            GetSQLValueString(1, "int"));

    $Result1 = mysql_query($insertSQL, $dbDescent) or die(mysql_error());
    $Result1ID = mysql_insert_id();
    
  } else {
    $insertSQL = sprintf("INSERT INTO tbcharacters (char_ggrp_id, char_game_id, char_player, char_hero, char_class) VALUES (%s, %s, %s, %s, %s)",
                            GetSQLValueString($_SESSION["campaigndata"]['group_id'], "int"),
                            GetSQLValueString($ResultID, "int"),
                            GetSQLValueString($xshdb['player'], "text"),
                            GetSQLValueString($xshdb['id'], "int"),
                            GetSQLValueString($xshdb['class'], "text"));

    $Result1 = mysql_query($insertSQL, $dbDescent) or die(mysql_error());
    $Result1ID = mysql_insert_id();
  }

  

  $query_rsStarting = sprintf("SELECT * FROM tbclasses WHERE class_name = %s", GetSQLValueString($xshdb['class'], "text"));
  $rsStarting = mysql_query($query_rsStarting, $dbDescent) or die(mysql_error());
  $row_rsStarting = mysql_fetch_assoc($rsStarting);
  $totalRows_rsStarting = mysql_num_rows($rsStarting);

  // Save Starting item 1
  if ($row_rsStarting['class_item_id1'] != NULL) {
    $insertSQL2 = sprintf("INSERT INTO tbitems_aquired (aq_item_id, aq_char_id, aq_game_id) VALUES (%s, %s, %s)",
                          GetSQLValueString($row_rsStarting['class_item_id1'], "int"),
                          GetSQLValueString($Result1ID, "int"),
                          GetSQLValueString($ResultID, "int"));

    $Result2 = mysql_query($insertSQL2, $dbDescent) or die(mysql_error());
  }

  // Save Starting item 2
  if ($row_rsStarting['class_item_id2'] != NULL) {
    $insertSQL2 = sprintf("INSERT INTO tbitems_aquired (aq_item_id, aq_char_id, aq_game_id) VALUES (%s, %s, %s)",
                          GetSQLValueString($row_rsStarting['class_item_id2'], "int"),
                          GetSQLValueString($Result1ID, "int"),
                          GetSQLValueString($ResultID, "int"));

    $Result2 = mysql_query($insertSQL2, $dbDescent) or die(mysql_error());
  }

  // Save Starting Skill
  if ($row_rsStarting['class_skill_id'] != NULL) {
    $insertSQL3 = sprintf("INSERT INTO tbskills_aquired (spendxp_skill_id, spendxp_char_id, spendxp_game_id) VALUES (%s, %s, %s)",
                          GetSQLValueString($row_rsStarting['class_skill_id'], "int"),
                          GetSQLValueString($Result1ID, "int"),
                          GetSQLValueString($ResultID, "int"));

    $Result3 = mysql_query($insertSQL3, $dbDescent) or die(mysql_error());
  }

  if ($row_rsStarting['class_skill_id2'] != NULL) {
    $insertSQL3b = sprintf("INSERT INTO tbskills_aquired (spendxp_skill_id, spendxp_char_id, spendxp_game_id) VALUES (%s, %s, %s)",
                          GetSQLValueString($row_rsStarting['class_skill_id2'], "int"),
                          GetSQLValueString($Result1ID, "int"),
                          GetSQLValueString($ResultID, "int"));

    $Result3b = mysql_query($insertSQL3b, $dbDescent) or die(mysql_error());
  }

  if ($xshdb['name'] == "overlord"){
    if ($xshdb['id'] == 0){
      $deck = "Basic";
    } else if ($xshdb['id'] == 1){
      $deck = "Basic II";
    } else if ($xshdb['id'] == 2){
      $deck = "Basic III";
    }

    $query_rsOverlordDeck = sprintf("SELECT * FROM tbskills WHERE skill_class = %s", GetSQLValueString($deck, "text"));
    $rsOverlordDeck = mysql_query($query_rsOverlordDeck, $dbDescent) or die(mysql_error());
    $row_rsOverlordDeck = mysql_fetch_assoc($rsOverlordDeck);
    $totalRows_rsOverlordDeck = mysql_num_rows($rsOverlordDeck);

    do{
      $insertSQL4 = sprintf("INSERT INTO tbskills_aquired (spendxp_skill_id, spendxp_char_id, spendxp_game_id) VALUES (%s, %s, %s)",
                            GetSQLValueString($row_rsOverlordDeck['skill_id'], "int"),
                            GetSQLValueString($Result1ID, "int"),
                            GetSQLValueString($ResultID, "int"));

      $Result4 = mysql_query($insertSQL4, $dbDescent) or die(mysql_error());
    } while ($row_rsOverlordDeck = mysql_fetch_assoc($rsOverlordDeck));

    

  }

}

$_SESSION["campaigndata"] = array();
$_SESSION["playerdata"] = array();

$insertGoTo = "campaign_overview.php?urlGamingID=" . ($ResultID * 43021);
header(sprintf("Location: %s", $insertGoTo));
die("Redirecting to: campaign_overview.php");
