<?php

if (isset($_GET['urlGamingID']) && is_numeric($_GET['urlGamingID'])) {
  $gameID = ($_GET['urlGamingID'] / 43021);
  $gameID_obscured = $_GET['urlGamingID'];
  // 43021 obfuscates the game id, to make it harder to guess
} else {
  $insertGoTo = "404.php?info=campaignid";
  header(sprintf("Location: %s", $insertGoTo));
  die("Redirecting to 404.php"); 
}

if (isset($_GET['urlCharID'])) {
  $charID = $_GET['urlCharID'];
}

if (isset($_SESSION['user']['username'])){
  $currentUser = $_SESSION['user']['username'];
} else {
  $currentUser = "";
}

//select the database
mysql_select_db($database_dbDescent, $dbDescent);

//Select the game based on the url
$query_rsGroupCampaign = sprintf("SELECT * FROM tbgames INNER JOIN tbcampaign ON game_camp_id = cam_id WHERE game_id = %s", GetSQLValueString($gameID, "int"));
$rsGroupCampaign = mysql_query($query_rsGroupCampaign, $dbDescent) or die(mysql_error());
$row_rsGroupCampaign = mysql_fetch_assoc($rsGroupCampaign);
$totalRows_rsGroupCampaign = mysql_num_rows($rsGroupCampaign);


// Does this campaign exist?
if ($totalRows_rsGroupCampaign == 0){
  $insertGoTo = "404.php?info=campaignid";
  header(sprintf("Location: %s", $insertGoTo));
  die("Redirecting to 404.php"); 
}

$selCampaign = $row_rsGroupCampaign['game_camp_id'];
$selCampaignName = $row_rsGroupCampaign['cam_name'];
$selCampaignType = $row_rsGroupCampaign['cam_type'];
$selExpansions = $row_rsGroupCampaign['game_expansions'];

$query_rsRumorPlayed = sprintf("SELECT * FROM tbrumors_played WHERE played_game_id = %s", GetSQLValueString($gameID, "int"));
$rsRumorPlayed = mysql_query($query_rsRumorPlayed, $dbDescent) or die(mysql_error());
$row_rsRumorPlayed = mysql_fetch_assoc($rsRumorPlayed);
$totalRows_rsRumorPlayed = mysql_num_rows($rsRumorPlayed);

$rumorsInPlay = array();
$rumorsDone = array();

if ($totalRows_rsRumorPlayed > 0){
  do {  
    if ($row_rsRumorPlayed['played_resolved'] == 0){
      $rumorsInPlay[] = $row_rsRumorPlayed['played_rumor_id'];
    } else {
      $rumorsDone[] = $row_rsRumorPlayed['played_rumor_id'];
    }
    
  } while ($row_rsRumorPlayed = mysql_fetch_assoc($rsRumorPlayed));
}

// ------------- //
// -- PLAYERS -- //
// ------------- //

// Select the players (heroes and overlord)
$query_rsCharData = sprintf("SELECT * FROM tbcharacters INNER JOIN tbheroes ON tbcharacters.char_hero = tbheroes.hero_id INNER JOIN tbplayerlist ON tbcharacters.char_player = tbplayerlist.player_id WHERE char_game_id = %s", GetSQLValueString($gameID, "int"));
$rsCharData = mysql_query($query_rsCharData, $dbDescent) or die(mysql_error());
$row_rsCharData = mysql_fetch_assoc($rsCharData);
$totalRows_rsCharData = mysql_num_rows($rsCharData);

$plotStuff = 0;
// Create the player data array
$players = array();

do {

  // Get the skills
  $query_rsSkillsData = sprintf("SELECT * 
    FROM tbcharacters 
    INNER JOIN tbskills_aquired ON tbcharacters.char_id = tbskills_aquired.spendxp_char_id 
    INNER JOIN tbskills ON tbskills_aquired.spendxp_skill_id = tbskills.skill_id 
    WHERE char_game_id = %s AND char_id = %s", GetSQLValueString($gameID, "int"), GetSQLValueString($row_rsCharData['char_id'], "int"));
  $rsSkillsData = mysql_query($query_rsSkillsData, $dbDescent) or die(mysql_error());
  $row_rsSkillsData = mysql_fetch_assoc($rsSkillsData);
  $totalRows_rsSkillsData = mysql_num_rows($rsSkillsData);


  $skills = array();
  do {  
        $skills[] = array(
          "id" => $row_rsSkillsData['spendxp_id'],
          "skill_id" => $row_rsSkillsData['skill_id'],
          "name" => $row_rsSkillsData['skill_name'],
          "xp" => $row_rsSkillsData['skill_cost'],
          "sold" => $row_rsSkillsData['spendxp_sold'],
          "plot" => $row_rsSkillsData['skill_plot'],
        );
  } while ($row_rsSkillsData = mysql_fetch_assoc($rsSkillsData));


  // Get the items
  $query_rsItemsData = sprintf("SELECT *
    FROM tbitems_aquired 
    LEFT JOIN tbitems ON tbitems_aquired.aq_item_id = tbitems.item_id
    LEFT JOIN tbitems_relics ON tbitems_aquired.aq_relic_id = tbitems_relics.relic_id 
    INNER JOIN tbcharacters ON tbitems_aquired.aq_char_id = tbcharacters.char_id 
    INNER JOIN tbheroes ON tbcharacters.char_hero = tbheroes.hero_id 
    WHERE aq_game_id = %s AND char_id = %s AND aq_sold_progress_id is null AND aq_trade_progress_id is null", GetSQLValueString($gameID, "int"), GetSQLValueString($row_rsCharData['char_id'], "int"));
  $rsItemsData = mysql_query($query_rsItemsData, $dbDescent) or die(mysql_error());
  $row_rsItemsData = mysql_fetch_assoc($rsItemsData);
  $totalRows_rsItemsData = mysql_num_rows($rsItemsData);

  $itemsX = array();
  do {  

      if(isset($row_rsItemsData['item_name'])){
        $itemNameTemp = $row_rsItemsData['item_name'];
      } else {
        $itemNameTemp = $row_rsItemsData['relic_h_name'];
      }
        
      if (isset($row_rsItemsData['item_id']) || isset($row_rsItemsData['relic_id'])){
        $itemsX[] = array(
          "id" => $row_rsItemsData['shop_id'],
          "item_id" => $row_rsItemsData['item_id'],
          "relic_id" => $row_rsItemsData['relic_id'],
          "name" => $itemNameTemp,
        );
      }

  } while ($row_rsItemsData = mysql_fetch_assoc($rsItemsData));

  $players[] = array(
    "id" => $row_rsCharData['char_id'],
    "player" => $row_rsCharData['player_handle'],
    "name" => $row_rsCharData['hero_name'],
    "hero_id" => $row_rsCharData['hero_id'],
    "archetype" => $row_rsCharData['hero_type'],
    "speed" => $row_rsCharData['hero_speed'],
    "health" => $row_rsCharData['hero_health'],
    "stamina" => $row_rsCharData['hero_stamina'],
    "defense" => $row_rsCharData['hero_defense'],
    "might" => $row_rsCharData['hero_might'],
    "knowledge" => $row_rsCharData['hero_knowledge'],
    "willpower" => $row_rsCharData['hero_willpower'],
    "awareness" => $row_rsCharData['hero_awareness'],
    "img" => $row_rsCharData['hero_img'],
    "class" => $row_rsCharData['char_class'],
    //"class_id" => $row_rsCharData['char_class'],
    "xp" => $row_rsCharData['char_xp'],
    "skills" => $skills,
    "items" => $itemsX,
  );

  if ($row_rsCharData['hero_type'] == 'Overlord'){

    if ($row_rsCharData['char_class'] != NULL){
      $plotStuff = 1;
    }

  }

} while ($row_rsCharData = mysql_fetch_assoc($rsCharData));

// Select Monsters
$query_rsMonsters = sprintf("SELECT * FROM tbmonsters WHERE monster_exp_id IN ($selExpansions) ORDER BY monster_name ASC");
$rsMonsters = mysql_query($query_rsMonsters, $dbDescent) or die(mysql_error());
$row_rsMonsters = mysql_fetch_assoc($rsMonsters);
$totalRows_rsMonsters = mysql_num_rows($rsMonsters);

$monsters = array();
$D2Monsters = array();
$monsterLimits = array();

do {
  
  if ($row_rsMonsters['monster_limits'] != "1"){
    $monsterLimitsexp = explode(";", $row_rsMonsters['monster_limits']);
    foreach ($monsterLimitsexp as $mle){
      $monsterLimitsTemp = explode(",", $mle);
      $monsterLimits[$monsterLimitsTemp[0]] = array(
        "minions" => $monsterLimitsTemp[1],
        "masters" => $monsterLimitsTemp[2]
      );
    }
  } else {

  }
  

  $monsters[] = array(
    "id" => $row_rsMonsters['monster_id'],
    "name" => $row_rsMonsters['monster_name'],
    "type" => $row_rsMonsters['monster_type'],
    "traits" => explode(',', $row_rsMonsters['monster_traits']),
    "expansion" => $row_rsMonsters['monster_exp_id'],
    "monster_limits" => $monsterLimits,
  );

  if ($row_rsMonsters['monster_exp_id'] != 99){
    $D2Monsters[] = $row_rsMonsters['monster_name'];
  }

} while ($row_rsMonsters = mysql_fetch_assoc($rsMonsters));


$allMonsters = array();
foreach ($monsters as $mo){

  if ($mo['expansion'] != 99){
    $allMonsters[] = array(
      "id" => $mo['id'],
      "name" => $mo['name'],
      "type" => $mo['type'],
      "traits" => $mo['traits'],
      "expansion" => $mo['expansion'],
      "monster_limits" => $mo['monster_limits'],
      "option" => '<option name="monster" value="' . $mo['id'] . '">' . $mo['name'] . '</option>',
    );
  } else if ($mo['expansion'] == 99 && (!in_array($mo['name'], $D2Monsters))){
    $allMonsters[] = array(
      "id" => $mo['id'],
      "name" => $mo['name'],
      "type" => $mo['type'],
      "traits" => $mo['traits'],
      "expansion" => $mo['expansion'],
      "monster_limits" => $mo['monster_limits'],
      "option" => '<option name="monster" value="' . $mo['id'] . '">' . $mo['name'] . ' (Conversion Kit)</option>',
    );
  }

} 


$query_rsMonstersUsable = sprintf("SELECT * FROM tbmonsters_usable WHERE usable_game_id = %s", GetSQLValueString($gameID, "int"));
$rsMonstersUsable = mysql_query($query_rsMonstersUsable, $dbDescent) or die(mysql_error());
$row_rsMonstersUsable = mysql_fetch_assoc($rsMonstersUsable);
$totalRows_rsMonstersUsable = mysql_num_rows($rsMonstersUsable);

$monsters_usable = array();
do {
  $monsters_usable[] = array(
    "monster_id" => $row_rsMonstersUsable['usable_monster_id'],
    "monster_status" => $row_rsMonstersUsable['usable_status'],
    "replace_id" => $row_rsMonstersUsable['usable_status'],
    "monster_time" => $row_rsMonstersUsable['usable_quest'],
  );

} while ($row_rsMonstersUsable = mysql_fetch_assoc($rsMonstersUsable));



// -------------- //
// -- CAMPAIGN -- //
// -------------- //
 
$campaign = array(
    "name" => $selCampaignName,
    "type" => $selCampaignType,
    "dm" => $row_rsGroupCampaign['game_dm'],
    "camp_id" => $selCampaign,
    "gold" => $row_rsGroupCampaign['game_gold'],
    "threat" => $row_rsGroupCampaign['game_threat'],
    "adv_rew" => $row_rsGroupCampaign['game_rumor_rew_used'],
    "quests" => array(),
);



// -- DATABASE QUERIES -- //

// Get the quests
$query_rsQuestData = sprintf("SELECT * 
  FROM tbquests_progress 
  INNER JOIN tbquests ON tbquests_progress.progress_quest_id = tbquests.quest_id 
  LEFT JOIN tbitems_relics ON tbquests.quest_rew_relic_id = tbitems_relics.relic_id 
  WHERE progress_game_id = %s ORDER BY progress_id DESC", GetSQLValueString($gameID, "int"));
$rsQuestData = mysql_query($query_rsQuestData, $dbDescent) or die(mysql_error());
$row_rsQuestData = mysql_fetch_assoc($rsQuestData);
$totalRows_rsQuestData = mysql_num_rows($rsQuestData);



if (isset($_SESSION['user']) && $_SESSION['user']['id'] == $campaign['dm']){
  $owner = 1;
} else {
  $owner = 0;
}




// Set some variables
$iq = 0;

// Create the campaign data array
do {
  $qrewardsHeroes = array();
  $qrewardsOverlord = array();

  if ($row_rsQuestData['quest_rew_h'] != NULL){
    $qrewardsHeroes = explode(';', $row_rsQuestData['quest_rew_h']);

    $rhi = 0;
    foreach ($qrewardsHeroes as $rh){
      $qrewardsHeroes[$rhi] = explode(',', $rh);
      $rhi++;
    }
  }

  if ($row_rsQuestData['quest_rew_ol'] != NULL){
    $qrewardsOverlord = explode(';', $row_rsQuestData['quest_rew_ol']);

    $roi = 0;
    foreach ($qrewardsOverlord as $ro){
      $qrewardsOverlord[$roi] = explode(',', $ro);
      $roi++;
    }
  }


  // Monsters
  if ($row_rsQuestData['progress_enc1_monsters'] == NULL){
    $monstersEnc1Expl = explode(';', $row_rsQuestData['quest_enc1_monsters']);

    if (count($monstersEnc1Expl) == 1){
       $monstersSpecialEnc1 = NULL;
    } else {
      for ($i=1; $i < count($monstersEnc1Expl); $i++) { 
        $monstersSpecialEnc1[] = $monstersEnc1Expl[$i];
      }
    }

    
    $m1exi = 0;
    foreach ($monstersEnc1Expl as $m1ex){
      $monstersEnc1Expl[$m1exi] = explode(',', $m1ex);
      $m1exi++;
    }
    $monstersEnc1 = $monstersEnc1Expl[0];
  } else {
    $monstersEnc1 = explode(',', $row_rsQuestData['progress_enc1_monsters']);
    $monstersSpecialEnc1 = NULL;
  }

  if ($row_rsQuestData['progress_enc2_monsters'] == NULL && $row_rsQuestData['quest_enc2_monsters'] != NULL){
    $monstersEnc2Expl = explode(';', $row_rsQuestData['quest_enc2_monsters']);

    if (count($monstersEnc2Expl) == 1){
       $monstersSpecialEnc2 = NULL;
    } else {
      for ($i=1; $i < count($monstersEnc2Expl); $i++) { 
        $monstersSpecialEnc2[] = $monstersEnc2Expl[$i];
      }
    }

    $m2exi = 0;
    foreach ($monstersEnc2Expl as $m2ex){
      $monstersEnc2Expl[$m2exi] = explode(',', $m2ex);
      $m2exi++;
    }
    $monstersEnc2 = $monstersEnc2Expl[0];
  } else {
    if ($row_rsQuestData['quest_enc2_monsters'] != NULL){
      $monstersEnc2 = explode(',', $row_rsQuestData['progress_enc2_monsters']);
      $monstersSpecialEnc2 = NULL;
    } else {
      $monstersEnc2 = NULL;
      $monstersSpecialEnc2 = NULL;
    }
  }

  if ($row_rsQuestData['progress_enc3_monsters'] == NULL && $row_rsQuestData['quest_enc3_monsters'] != NULL){
    $monstersEnc3Expl = explode(';', $row_rsQuestData['quest_enc3_monsters']);

    if (count($monstersEnc3Expl) == 1){
       $monstersSpecialEnc3 = NULL;
    } else {
      for ($i=1; $i < count($monstersEnc3Expl); $i++) { 
        $monstersSpecialEnc3[] = $monstersEnc3Expl[$i];
      }
    }

    $m3exi = 0;
    foreach ($monstersEnc3Expl as $m3ex){
      $monstersEnc3Expl[$m3exi] = explode(',', $m3ex);
      $m3exi++;
    }

    $monstersEnc3 = $monstersEnc3Expl[0];
  } else {
    if ($row_rsQuestData['quest_enc3_monsters'] != NULL){
      $monstersEnc3 = explode(',', $row_rsQuestData['progress_enc3_monsters']);
      $monstersSpecialEnc3 = NULL;
    } else {
      $monstersEnc3 = NULL;
      $monstersSpecialEnc3 = NULL;
    }
  }

  $short = $row_rsQuestData['quest_name'];
  $short = strtolower($short);
  $short = str_replace(" ","_",$short);
  $short = preg_replace("/[^A-Za-z0-9_]/","",$short);

  $traits_enc1exp = explode(',', $row_rsQuestData['quest_enc1_traits']);
  if(isset($row_rsQuestData['quest_enc2_traits'])){
    $traits_enc2exp = explode(',', $row_rsQuestData['quest_enc2_traits']);
  } else {
    $traits_enc2exp = NULL;
  }
  if(isset($row_rsQuestData['quest_enc3_traits'])){
    $traits_enc3exp = explode(',', $row_rsQuestData['quest_enc3_traits']);
  } else {
    $traits_enc3exp = NULL;
  }
  

  $campaign['quests'][$iq] = array(
    "id" => $row_rsQuestData['progress_id'],
    "timestamp" => $row_rsQuestData['progress_timestamp'],
    "quest_id" => $row_rsQuestData['quest_id'],
    "quest_exp_id" => $row_rsQuestData['quest_expansion_id'],
    "quest_type" => $row_rsQuestData['progress_quest_type'],
    "name" => $row_rsQuestData['quest_name'],
    "img" => $short . ".jpg",
    "act" => $row_rsQuestData['quest_act'],
    "errata" => $row_rsQuestData['quest_errata'],
    "winner" => $row_rsQuestData['progress_quest_winner'],
    "winner_enc1" => $row_rsQuestData['progress_enc1_winner'],
    "monsters_enc1" => $monstersEnc1,
    "monsters_enc2" => $monstersEnc2,
    "monsters_enc3" => $monstersEnc3,
    "monsters_special_enc1" => $monstersSpecialEnc1,
    "monsters_special_enc2" => $monstersSpecialEnc2,
    "monsters_special_enc3" => $monstersSpecialEnc3,
    "traits_enc1" => $traits_enc1exp,
    "traits_enc2" => $traits_enc2exp,
    "traits_enc3" => $traits_enc3exp,
    "rewardsHeroes" => $qrewardsHeroes,
    "rewardsOverlord" => $qrewardsOverlord,
    "relic_HeroesName" => $row_rsQuestData['relic_h_name'],
    "relic_OverlordName" => $row_rsQuestData['relic_ol_name'],
    "relic_id" => $row_rsQuestData['relic_id'],
    //"rumors_played" => $row_rsQuestData['progress_rumors_played'],
    "travel_set" => $row_rsQuestData['progress_set_travel'],
    "travel_steps" => $row_rsQuestData['quest_travel'],
    "travel" => array(),
    "spendxp_set" => $row_rsQuestData['progress_set_spendxp'],
    "spendxp" => array(),
    "items_set" => $row_rsQuestData['progress_set_items'],
    "items" => array(),

  );


  // Get the Travel Steps

  $query_rsQuestTravelData = sprintf("SELECT * 
    FROM tbtravel_aquired 
    INNER JOIN tbtravel ON tbtravel_aquired.travel_aq_event_id = tbtravel.travel_id
    LEFT JOIN tbitems ON tbtravel_aquired.travel_aq_item = tbitems.item_id
    LEFT JOIN tbcharacters ON tbtravel_aquired.travel_aq_player = tbcharacters.char_id 
    LEFT JOIN tbheroes ON tbcharacters.char_hero = tbheroes.hero_id 
    WHERE travel_aq_progress_id = %s", GetSQLValueString($row_rsQuestData['progress_id'], "int"));
  $rsQuestTravelData = mysql_query($query_rsQuestTravelData, $dbDescent) or die(mysql_error());
  $row_rsQuestTravelData = mysql_fetch_assoc($rsQuestTravelData);
  $totalRows_rsQuestTravelData = mysql_num_rows($rsQuestTravelData);

  $questTravelSteps = explode(',', $row_rsQuestData['quest_travel']);
  $qts = 0;
  do {
    if ($totalRows_rsQuestTravelData > 0){
      if ($questTravelSteps[$qts] == $row_rsQuestTravelData['travel_type'] || $row_rsQuestTravelData['travel_type'] == "all" || $row_rsQuestTravelData['travel_type'] == "None"){
        
      } else {
        $qts--;
      }
      $campaign['quests'][$iq]['travel'][] = array(
            "type" => $questTravelSteps[$qts],
            //"type" => $travelType,
            "event" => $row_rsQuestTravelData['travel_event'],
            "outcome" => $row_rsQuestTravelData['travel_result'],
            "goldlost" =>  $row_rsQuestTravelData['travel_aq_gold'],
            "item" => $row_rsQuestTravelData['item_name'],
            "player" => $row_rsQuestTravelData['hero_name'],
        );
      $qts++;
    }

  } while ($row_rsQuestTravelData = mysql_fetch_assoc($rsQuestTravelData));

 
  

  // Get the skills
  $query_rsQuestSkillsData = sprintf("SELECT * 
    FROM tbskills_aquired 
    INNER JOIN tbskills ON tbskills_aquired.spendxp_skill_id = tbskills.skill_id 
    INNER JOIN tbcharacters ON tbskills_aquired.spendxp_char_id = tbcharacters.char_id 
    INNER JOIN tbheroes ON tbcharacters.char_hero = tbheroes.hero_id 
    WHERE spendxp_progress_id = %s OR spendxp_sold_progress_id = %s", GetSQLValueString($row_rsQuestData['progress_id'], "int"),GetSQLValueString($row_rsQuestData['progress_id'], "int"));
  $rsQuestSkillsData = mysql_query($query_rsQuestSkillsData, $dbDescent) or die(mysql_error());
  $row_rsQuestSkillsData = mysql_fetch_assoc($rsQuestSkillsData);
  $totalRows_rsQuestSkillsData = mysql_num_rows($rsQuestSkillsData);

  $ips = 0;
   do {
    if($row_rsQuestSkillsData['skill_name'] != NULL){

      $campaign['quests'][$iq]['spendxp'][$ips] = array(
        "hero_img" => $row_rsQuestSkillsData['hero_img'],
        "name" => $row_rsQuestSkillsData['skill_name'],
        "xpcost" => $row_rsQuestSkillsData['skill_cost'],
        "plot" => $row_rsQuestSkillsData['skill_plot'],
        "action" => "buy",
      );

      if ($row_rsQuestSkillsData['spendxp_sold_progress_id'] != NULL && $row_rsQuestSkillsData['spendxp_sold_progress_id'] == $row_rsQuestData['progress_id']){
        $campaign['quests'][$iq]['spendxp'][$ips] = array(
          "hero_img" => $row_rsQuestSkillsData['hero_img'],
          "name" => $row_rsQuestSkillsData['skill_name'],
          "xpcost" => $row_rsQuestSkillsData['skill_cost'],
          "plot" => $row_rsQuestSkillsData['skill_plot'],
          "action" => "sell",
        );
      }

      if ($row_rsQuestSkillsData['spendxp_sold_progress_id'] != NULL && $row_rsQuestSkillsData['spendxp_sold_progress_id'] == $row_rsQuestData['progress_id'] && $row_rsQuestSkillsData['skill_plot'] == 1){
        $campaign['quests'][$iq]['spendxp'][$ips] = array(
          "hero_img" => $row_rsQuestSkillsData['hero_img'],
          "name" => $row_rsQuestSkillsData['skill_name'],
          "xpcost" => $row_rsQuestSkillsData['skill_cost'],
          "plot" => $row_rsQuestSkillsData['skill_plot'],
          "action" => "return",
        );
      }

      if ($row_rsQuestSkillsData['spendxp_sold_progress_id'] != NULL && $row_rsQuestSkillsData['spendxp_sold_progress_id'] == $row_rsQuestData['progress_id'] && $row_rsQuestSkillsData['skill_plot'] == 4){
        $campaign['quests'][$iq]['spendxp'][$ips] = array(
          "hero_img" => $row_rsQuestSkillsData['hero_img'],
          "name" => $row_rsQuestSkillsData['skill_name'],
          "xpcost" => $row_rsQuestSkillsData['skill_cost'],
          "plot" => $row_rsQuestSkillsData['skill_plot'],
          "action" => "return",
        );
      }
      
    }

    $ips++;

  } while ($row_rsQuestSkillsData = mysql_fetch_assoc($rsQuestSkillsData));

  // Get the items
  $query_rsQuestItemsData = sprintf("SELECT * 
    FROM tbitems_aquired 
    LEFT JOIN tbitems ON tbitems_aquired.aq_item_id = tbitems.item_id
    LEFT JOIN tbitems_relics ON tbitems_aquired.aq_relic_id = tbitems_relics.relic_id 
    INNER JOIN tbcharacters ON tbitems_aquired.aq_char_id = tbcharacters.char_id 
    INNER JOIN tbheroes ON tbcharacters.char_hero = tbheroes.hero_id 
    WHERE aq_progress_id = %s OR aq_sold_progress_id = %s OR aq_trade_progress_id = %s ORDER BY aq_char_id ASC", 
      GetSQLValueString($row_rsQuestData['progress_id'], "int"), 
      GetSQLValueString($row_rsQuestData['progress_id'], "int"), 
      GetSQLValueString($row_rsQuestData['progress_id'], "int"));

  $rsQuestItemsData = mysql_query($query_rsQuestItemsData, $dbDescent) or die(mysql_error());
  $row_rsQuestItemsData = mysql_fetch_assoc($rsQuestItemsData);
  $totalRows_rsQuestItemsData = mysql_num_rows($rsQuestItemsData);

  $ips = 0;
   do {

    if($row_rsQuestItemsData['aq_item_id'] != NULL || $row_rsQuestItemsData['aq_relic_id'] != NULL){

      // If an item got bought but traded in the same progress step, this catches that
      if($row_rsQuestItemsData['aq_trade_progress_id'] != NULL && $row_rsQuestItemsData['aq_trade_progress_id'] == $row_rsQuestItemsData['aq_progress_id']){

        if($row_rsQuestItemsData['aq_item_id'] != NULL){
          $itemName = $row_rsQuestItemsData['item_name'];
          $itemType = "Item";
        } 
        else {
          $itemType = "Relic";
          if($row_rsQuestData['progress_quest_winner'] == "Heroes Win"){
            $itemName = $row_rsQuestItemsData['relic_h_name'];

          } else {
            $itemName = $row_rsQuestItemsData['relic_ol_name'];
          }
        }

        $campaign['quests'][$iq]['items'][$ips] = array(
          "hero_img" => $row_rsQuestItemsData['hero_img'],
          "type" => $itemType,
          "name" => $itemName,
          "action" => "buy",
          "price" => $row_rsQuestItemsData['item_default_price'],
          "override" => $row_rsQuestItemsData['aq_item_price_ovrd'],
          "item_img" => $row_rsQuestItemsData['market_img'],
        );

        $ips++;

      } 

      // if an item got traded, a duplicate is created. This code is to make sure that it doesn't get displayed in the step it is created in
      if($row_rsQuestItemsData['aq_item_gottraded'] != 0 && $row_rsQuestItemsData['aq_progress_id'] == $row_rsQuestData['progress_id']){

      } else {

        if($row_rsQuestItemsData['aq_item_id'] != NULL){
          $itemName = $row_rsQuestItemsData['item_name'];
          $itemType = "Item";
        } 
        else {
          $itemType = "Relic";
          if($row_rsQuestData['progress_quest_winner'] == "Heroes Win"){
            $itemName = $row_rsQuestItemsData['relic_h_name'];

          } else {
            $itemName = $row_rsQuestItemsData['relic_ol_name'];
          }
        }

        $campaign['quests'][$iq]['items'][$ips] = array(
          "hero_img" => $row_rsQuestItemsData['hero_img'],
          "type" => $itemType,
          "name" => $itemName,
          "action" => "buy",
          "price" => $row_rsQuestItemsData['item_default_price'],
          "override" => $row_rsQuestItemsData['aq_item_price_ovrd'],
          "item_img" => $row_rsQuestItemsData['market_img'],
        );

        if ($row_rsQuestItemsData['aq_item_sold'] == 1 && $row_rsQuestItemsData['aq_sold_progress_id'] == $row_rsQuestData['progress_id']){
          $campaign['quests'][$iq]['items'][$ips] = array(
            "hero_img" => $row_rsQuestItemsData['hero_img'],
            "type" => $itemType,
            "name" => $itemName,
            "action" => "sell",
            "price" => $row_rsQuestItemsData['item_sell_price'],
            "override" => NULL,
            "item_img" => $row_rsQuestItemsData['market_img'],
          );
        }

        if ($row_rsQuestItemsData['aq_item_sold'] == 2 && $row_rsQuestItemsData['aq_sold_progress_id'] == $row_rsQuestData['progress_id']){
          $campaign['quests'][$iq]['items'][$ips] = array(
            "hero_img" => $row_rsQuestItemsData['hero_img'],
            "type" => $itemType,
            "name" => $itemName,
            "action" => "box",
            "price" => $row_rsQuestItemsData['item_sell_price'],
            "override" => NULL,
            "item_img" => $row_rsQuestItemsData['market_img'],
          );
        }

        if ($row_rsQuestItemsData['aq_trade_char_id'] != NULL && $row_rsQuestItemsData['aq_trade_progress_id'] == $row_rsQuestData['progress_id']){

          $query_rsTradedPlayer = sprintf("SELECT * 
            FROM tbcharacters INNER JOIN tbheroes ON tbcharacters.char_hero = tbheroes.hero_id WHERE char_id = %s", GetSQLValueString($row_rsQuestItemsData['aq_trade_char_id'], "int"));
          $rsTradedPlayer = mysql_query($query_rsTradedPlayer, $dbDescent) or die(mysql_error());
          $row_rsTradedPlayer = mysql_fetch_assoc($rsTradedPlayer);
          $totalRows_rsTradedPlayer = mysql_num_rows($rsTradedPlayer);

          $campaign['quests'][$iq]['items'][$ips] = array(
            "hero_img" => $row_rsQuestItemsData['hero_img'],
            "type" => $itemType,
            "name" => $itemName,
            "action" => "trade",
            "price" => $row_rsTradedPlayer['hero_img'], // small cheat here ;)
            "override" => NULL,
            "item_img" => $row_rsQuestItemsData['market_img'],
          );
        }

        $ips++;
      }
    }

  } while ($row_rsQuestItemsData = mysql_fetch_assoc($rsQuestItemsData));

  

$iq++;

} while ($row_rsQuestData = mysql_fetch_assoc($rsQuestData));

// FAQ
$query_rsFAQ = sprintf("SELECT * FROM tbfaq WHERE faq_exp_id IN ($selExpansions) ORDER BY faq_subject");
$rsFAQ = mysql_query($query_rsFAQ, $dbDescent) or die(mysql_error());
$row_rsFAQ = mysql_fetch_assoc($rsFAQ);
$totalRows_rsFAQ = mysql_num_rows($rsFAQ);

$faqArray = array();

do{
  $faqArray[] = array(
    "source" => $row_rsFAQ['faq_source'],
    "subject" => $row_rsFAQ['faq_subject'],
    "subject_id" => explode(",", $row_rsFAQ['faq_subject_id']),
    "errata_title" => $row_rsFAQ['faq_errata_title'],
    "errata_text" => $row_rsFAQ['faq_errata_text'],
    "question" => $row_rsFAQ['faq_question'],
    "answer" => $row_rsFAQ['faq_answer'],
  );
} while ($row_rsFAQ = mysql_fetch_assoc($rsFAQ));


$wonForInterlude = 0;
$wonForFinale = 0;
$canChoose = array();
$cantChoose = array();
$olquests = array();

include 'campaign_logs.php';

foreach ($players as $h){
  if ($h['archetype'] == 'Overlord'){
    $overlordID = $h['id'];
  }
}

// Save Quests

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "start-quest-form")) {
  $postQuestID = "";
  if (isset($_POST['selectquest'])){
    if (strpos($_POST['selectquest'],'quest') !== false) {
      $type = 'Quest';
      $postQuestID = str_replace("quest","",$_POST['selectquest']);
    } else {
      $type = 'Rumor';
      $postQuestID = str_replace("rumor","",$_POST['selectquest']);
    }
  } 

  if($postQuestID != "" && $type == 'Quest'){
    // Insert timestamp, gameid, quest id, and the type into the db
    $insertSQL = sprintf("INSERT INTO tbquests_progress (progress_timestamp, progress_game_id,progress_quest_id, progress_quest_type) VALUES (%s, %s, %s, %s)",
                         GetSQLValueString($_POST['progress_timestamp'], "date"),
                         GetSQLValueString($_POST['progress_game_id'], "int"),
                         GetSQLValueString($postQuestID, "int"),
                         GetSQLValueString($type, "text"));

    mysql_select_db($database_dbDescent, $dbDescent);
    $Result1 = mysql_query($insertSQL, $dbDescent) or die(mysql_error());
    $ResultID = mysql_insert_id();

    // Set all played rumor cards that don't have a quest (so stuff that targets travel steps and stuff) to completed
    $insertSQLrc = sprintf("UPDATE tbrumors_played SET played_resolved = %s, played_resolved_progress_id = %s WHERE played_game_id = %s AND played_rumor_quest_id is null",
                        GetSQLValueString(1, "int"),
                        GetSQLValueString($ResultID, "int"),
                        GetSQLValueString($gameID, "int"));

    mysql_select_db($database_dbDescent, $dbDescent);
    $Resultrc = mysql_query($insertSQLrc, $dbDescent) or die(mysql_error());

    // if the current act is the interlude
    if ($currentAct == "Interlude"){
      
      // select from rumors played
      $query_rsRumorsUnplayed = sprintf("SELECT * FROM tbrumors_played INNER JOIN tbrumors ON played_rumor_id = rumor_id WHERE played_game_id = %s AND played_resolved = %s AND played_rumor_quest_id is not null", 
                        GetSQLValueString($gameID, "int"),
                        GetSQLValueString(0, "int"));
      $rsRumorsUnplayed = mysql_query($query_rsRumorsUnplayed, $dbDescent) or die(mysql_error());
      $row_rsRumorsUnplayed = mysql_fetch_assoc($rsRumorsUnplayed);

      do{
        // Set these rumors to 3 (autocompleted) and save the progress id of when it happened
        $insertSQLrcq = sprintf("UPDATE tbrumors_played SET played_resolved = %s, played_resolved_progress_id = %s WHERE played_id = %s",
                        GetSQLValueString(3, "int"),
                        GetSQLValueString($ResultID, "int"),
                        GetSQLValueString($row_rsRumorsUnplayed['played_id'], "int"));

        // If the rumor has a card attached to it.
        if ($row_rsRumorsUnplayed['rumor_unplayed_card'] != NULL){
          //echo "test - " . $row_rsRumorsUnplayed['rumor_unplayed_card'];
          $insertSQLSpecial = sprintf("INSERT INTO  tbskills_aquired (spendxp_game_id, spendxp_char_id, spendxp_skill_id, spendxp_progress_id) VALUES (%s, %s, %s, %s)",
                             GetSQLValueString($gameID, "int"),
                             GetSQLValueString($overlordID, "int"),
                             GetSQLValueString($row_rsRumorsUnplayed['rumor_unplayed_card'], "int"),
                             GetSQLValueString($ResultID, "int"));
                  
          mysql_select_db($database_dbDescent, $dbDescent);
          $ResultSpecial = mysql_query($insertSQLSpecial, $dbDescent) or die(mysql_error());
        }

        if ($row_rsRumorsUnplayed['rumor_unplayed_relic'] != NULL){     
          $insertSQLRelic = sprintf("INSERT INTO  tbitems_aquired (aq_game_id, aq_char_id, aq_relic_id, aq_progress_id) VALUES (%s, %s, %s, %s)",
                     GetSQLValueString($gameID, "int"),
                     GetSQLValueString($overlordID, "int"),
                     GetSQLValueString($row_rsRumorsUnplayed['rumor_unplayed_relic'], "int"),
                     GetSQLValueString($ResultID, "int"));
          
          mysql_select_db($database_dbDescent, $dbDescent);
          $ResultRelic = mysql_query($insertSQLRelic, $dbDescent) or die(mysql_error());
        }

        mysql_select_db($database_dbDescent, $dbDescent);
        $Resultrcq = mysql_query($insertSQLrcq, $dbDescent) or die(mysql_error());

      } while ($row_rsRumorsUnplayed = mysql_fetch_assoc($rsRumorsUnplayed));
   
    }

    $insertGoTo = "campaign_overview.php?urlGamingID=" . $row_rsSelectedCampaign['ggrp_id'] . "";
    if (isset($_SERVER['QUERY_STRING'])) {
      $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
      $insertGoTo .= $_SERVER['QUERY_STRING'];
    }
    header(sprintf("Location: %s", $insertGoTo));
    die("Redirecting to self"); 

  } else if($postQuestID != "" && $type == 'Rumor'){
    $insertSQLr = sprintf("INSERT INTO tbquests_progress (progress_timestamp, progress_game_id, progress_quest_id, progress_quest_type) VALUES (%s, %s, %s, %s)",
                         GetSQLValueString($_POST['progress_timestamp'], "date"),
                         GetSQLValueString($_POST['progress_game_id'], "int"),
                         GetSQLValueString($postQuestID, "int"),
                         GetSQLValueString($type, "text"));

    mysql_select_db($database_dbDescent, $dbDescent);
    $Resultr = mysql_query($insertSQLr, $dbDescent) or die(mysql_error());
    $ResultID = mysql_insert_id();

    $insertSQLrrc = sprintf("UPDATE tbrumors_played SET played_resolved = %s, played_resolved_progress_id= %s WHERE played_game_id = %s AND played_rumor_quest_id = %s",
                        GetSQLValueString(1, "int"),
                        GetSQLValueString($ResultID, "int"),
                        GetSQLValueString($gameID, "int"),
                        GetSQLValueString($postQuestID, "int"));

    mysql_select_db($database_dbDescent, $dbDescent);
    $Resultrrc = mysql_query($insertSQLrrc, $dbDescent) or die(mysql_error());

    $insertSQLrc = sprintf("UPDATE tbrumors_played SET played_resolved = %s WHERE played_game_id = %s AND played_rumor_quest_id is NULL",
                        GetSQLValueString(1, "int"),
                        GetSQLValueString($gameID, "int"));
              
    mysql_select_db($database_dbDescent, $dbDescent);
    $Resultrc = mysql_query($insertSQLrc, $dbDescent) or die(mysql_error());

    $insertGoTo = "campaign_overview.php?urlGamingID=" . $row_rsSelectedCampaign['ggrp_id'] . "";
    if (isset($_SERVER['QUERY_STRING'])) {
      $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
      $insertGoTo .= $_SERVER['QUERY_STRING'];
    }
    header(sprintf("Location: %s", $insertGoTo));
    die("Redirecting to self"); 
  }
}


// Add rumor cards, if its a quest increase threat with 1
if (isset($_POST["MM_insert"]) && ($_POST["MM_insert"] == "add-rumor-form")) {
  if($_POST['progress_rumor_card_id'] != ""){
    foreach ($availableRumorCards as $avc){
      if($_POST['progress_rumor_card_id'] == $avc['rumor_id']){
        if ($avc['rumor_quest_id'] != NULL){
          $thrtRumor = 1;
          $rumor_quest = $avc['rumor_quest_id'];
        } else {
          $thrtRumor = 0;
          $rumor_quest = NULL;
        }

      }
    }

    $insertSQLrc = sprintf("INSERT INTO tbrumors_played (played_rumor_id, played_game_id, played_rumor_quest_id, played_progress_id) VALUES (%s, %s, %s, %s)",
                         GetSQLValueString($_POST['progress_rumor_card_id'], "int"),
                         GetSQLValueString($_POST['progress_game_id'], "int"),
                         GetSQLValueString($rumor_quest, "int"),
                         GetSQLValueString($campaign['quests'][0]['id'], "int"));

    $insertSQLrc2 = sprintf("UPDATE tbgames SET game_threat = game_threat + %s WHERE game_id = %s",
                        GetSQLValueString($thrtRumor, "int"),
                        GetSQLValueString($gameID, "int"));
              
    mysql_select_db($database_dbDescent, $dbDescent);
    $Resultrc = mysql_query($insertSQLrc, $dbDescent) or die(mysql_error());
    $Resultrc2 = mysql_query($insertSQLrc2, $dbDescent) or die(mysql_error());

    $insertGoTo = "";
    if (isset($_SERVER['QUERY_STRING'])) {
      $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
      $insertGoTo .= $_SERVER['QUERY_STRING'];
    }
    header(sprintf("Location: %s", $insertGoTo));
    die("Redirecting to self"); 
  }
}

?>
