<?php

$returnGold = 0;
$returnThreat = 0;

$query_rsTravelReset = sprintf("SELECT * FROM tbtravel_aquired WHERE travel_aq_progress_id = %s",
                  GetSQLValueString($pID, "int"));
$rsTravelReset = mysql_query($query_rsTravelReset, $dbDescent) or die(mysql_error());
$row_rsTravelReset = mysql_fetch_assoc($rsTravelReset);
$totalRows_rsTravelReset = mysql_num_rows($rsTravelReset);

$untravel = array();

do{
  if ($row_rsTravelReset['travel_aq_gold'] != NULL){
    $returnGold += $row_rsTravelReset['travel_aq_gold'];
  }
  
  $untravel[] = $row_rsTravelReset['travel_aq_id'];

} while ($row_rsTravelReset = mysql_fetch_assoc($rsTravelReset));


// Skills
$query_rsSkillsReset = sprintf("SELECT * FROM tbskills_aquired INNER JOIN tbskills ON spendxp_skill_id = skill_id WHERE spendxp_progress_id = %s",
                  GetSQLValueString($pID, "int"));
$rsSkillsReset = mysql_query($query_rsSkillsReset, $dbDescent) or die(mysql_error());
$row_rsSkillsReset = mysql_fetch_assoc($rsSkillsReset);
$totalRows_rsSkillsReset = mysql_num_rows($rsSkillsReset);

$unspendxp = array();
$unspendthreat = array();

do{
  if ($row_rsSkillsReset['skill_plot'] != 1){
    $unspendxp[] = array(
      "id" => $row_rsSkillsReset['spendxp_id'],
      "char" => $row_rsSkillsReset['spendxp_char_id'],
      "xp" => $row_rsSkillsReset['skill_cost'],
    );
  } else {
    $unspendthreat[] = array(
      "id" => $row_rsSkillsReset['spendxp_id'],
      "char" => $row_rsSkillsReset['spendxp_char_id'],
      "xp" => $row_rsSkillsReset['skill_cost'],
    );
  }
  

} while ($row_rsSkillsReset = mysql_fetch_assoc($rsSkillsReset));

// Skills
$query_rsSkillsSold = sprintf("SELECT * FROM tbskills_aquired INNER JOIN tbskills ON spendxp_skill_id = skill_id WHERE spendxp_sold_progress_id = %s",
                  GetSQLValueString($pID, "int"));
$rsSkillsSold = mysql_query($query_rsSkillsSold, $dbDescent) or die(mysql_error());
$row_rsSkillsSold = mysql_fetch_assoc($rsSkillsSold);
$totalRows_rsSkillsSold = mysql_num_rows($rsSkillsSold);

$unspendxpSold = array();
$unspendthreatSold = array();

do{
  if ($row_rsSkillsSold['skill_plot'] != 1){
    $unspendxpSold[] = array(
      "id" => $row_rsSkillsSold['spendxp_id'],
      "char" => $row_rsSkillsSold['spendxp_char_id'],
      "xp" => $row_rsSkillsSold['skill_cost'],
    );
  } else {
    $unspendthreatSold[] = array(
      "id" => $row_rsSkillsSold['spendxp_id'],
      "char" => $row_rsSkillsSold['spendxp_char_id'],
      "xp" => $row_rsSkillsSold['skill_cost'],
    );
  }
  

} while ($row_rsSkillsSold = mysql_fetch_assoc($rsSkillsSold));

// Items
$query_rsItemsReset = sprintf("SELECT * FROM tbitems_aquired INNER JOIN tbitems ON aq_item_id = item_id WHERE aq_progress_id = %s OR aq_trade_progress_id = %s OR aq_sold_progress_id = %s ",
                  GetSQLValueString($pID, "int"),
                  GetSQLValueString($pID, "int"),
                  GetSQLValueString($pID, "int"));
$rsItemsReset = mysql_query($query_rsItemsReset, $dbDescent) or die(mysql_error());
$row_rsItemsReset = mysql_fetch_assoc($rsItemsReset);
$totalRows_rsItemsReset = mysql_num_rows($rsItemsReset);

$unbuy = array();
$unsell = array();
$untrade = array();

do{
  // echo '<pre>';
  // var_dump($row_rsItemsReset);
  // echo '</pre>';
  if ($totalRows_rsItemsReset != 0){
    if (($row_rsItemsReset['aq_item_gottraded'] == 1 || $row_rsItemsReset['aq_item_gottraded'] == 2) && $row_rsItemsReset['aq_progress_id'] == $pID){
      $unbuy[] =  $row_rsItemsReset['shop_id'];
    } 
    
    if ($row_rsItemsReset['aq_trade_progress_id'] != NULL && $row_rsItemsReset['aq_progress_id'] != $row_rsItemsReset['aq_trade_progress_id']){
      $untrade[] =  $row_rsItemsReset['shop_id'];

    } 
    else if ($row_rsItemsReset['aq_sold_progress_id'] != NULL && $row_rsItemsReset['aq_progress_id'] != $row_rsItemsReset['aq_sold_progress_id']){
      $returnGold -= $row_rsItemsReset['item_sell_price'];
      $unsell[] =  $row_rsItemsReset['shop_id'];
    } 
    else {
      if ($row_rsItemsReset['aq_item_price_ovrd'] == NULL){
        $returnGold += $row_rsItemsReset['item_default_price'];
      } else {
        $returnGold += $row_rsItemsReset['aq_item_price_ovrd'];
      }
      $unbuy[] =  $row_rsItemsReset['shop_id'];
    }
  }
  
  

} while ($row_rsItemsReset = mysql_fetch_assoc($rsItemsReset));

$query_rsRelicsReset = sprintf("SELECT * FROM tbitems_aquired INNER JOIN tbitems_relics ON aq_relic_id = relic_id WHERE aq_progress_id = %s OR aq_trade_progress_id = %s OR aq_sold_progress_id = %s ",
                  GetSQLValueString($pID, "int"),
                  GetSQLValueString($pID, "int"),
                  GetSQLValueString($pID, "int"));
$rsRelicsReset = mysql_query($query_rsRelicsReset, $dbDescent) or die(mysql_error());
$row_rsRelicsReset = mysql_fetch_assoc($rsRelicsReset);
$totalRows_rsRelicsReset = mysql_num_rows($rsRelicsReset);

do{
  // echo '<pre>';
  // var_dump($row_rsRelicsReset);
  // echo '</pre>';
  if ($totalRows_rsRelicsReset != 0){
    if (($row_rsRelicsReset['aq_item_gottraded'] == 1 || $row_rsRelicsReset['aq_item_gottraded'] == 2) && $row_rsRelicsReset['aq_progress_id'] == $pID){
      $unbuy[] =  $row_rsRelicsReset['shop_id'];
    } 

    if ($row_rsRelicsReset['aq_trade_progress_id'] != NULL && $row_rsRelicsReset['aq_progress_id'] != $row_rsRelicsReset['aq_trade_progress_id']){
      $untrade[] =  $row_rsRelicsReset['shop_id'];
    } 
    else if ($row_rsRelicsReset['aq_sold_progress_id'] != NULL && $row_rsRelicsReset['aq_progress_id'] != $row_rsRelicsReset['aq_sold_progress_id']){
      $unsell[] =  $row_rsRelicsReset['shop_id'];
    } 
    else {
      $unbuy[] =  $row_rsRelicsReset['shop_id'];
    }
  }

} while ($row_rsRelicsReset = mysql_fetch_assoc($rsRelicsReset));


$returnXPOL = 0;
$returnXPH = 0;

// Progress
$query_rsProgressReset = sprintf("SELECT * FROM tbquests_progress INNER JOIN tbquests ON progress_quest_id = quest_id WHERE progress_id = %s",
                  GetSQLValueString($pID, "int"));
$rsProgressReset = mysql_query($query_rsProgressReset, $dbDescent) or die(mysql_error());
$row_rsProgressReset = mysql_fetch_assoc($rsProgressReset);
$totalRows_rsProgressReset = mysql_num_rows($rsProgressReset);

if (isset($row_rsProgressReset['progress_gold_gained'])){
  $returnGold -= $row_rsProgressReset['progress_gold_gained']; 
}

if (isset($row_rsProgressReset['progress_threat_gained'])){
  $returnThreat -= $row_rsProgressReset['progress_threat_gained']; 
}

if ($row_rsProgressReset['progress_quest_type'] == "Quest" && isset($row_rsProgressReset['progress_quest_winner'])) {
  $returnXPOL += 1;
  $returnXPH += 1;



  if(in_array($campaign['camp_id'], $miniCampaigns)){

    $query_rsRelicsXP = sprintf("SELECT * FROM tbitems_aquired INNER JOIN tbitems_relics ON aq_relic_id = relic_id WHERE aq_game_id = %s",
                      GetSQLValueString($gameID, "int"));
    $rsRelicsXP = mysql_query($query_rsRelicsXP, $dbDescent) or die(mysql_error());
    $row_rsRelicsXP = mysql_fetch_assoc($rsRelicsXP);
    $totalRows_rsRelicsXP = mysql_num_rows($rsRelicsXP);

    do{
      if($row_rsProgressReset['quest_rew_relic_id'] == $row_rsRelicsXP['aq_relic_id']){
        if($pID != $row_rsRelicsXP['aq_progress_id'] && $row_rsRelicsXP['aq_trade_progress_id'] == NULL){
          if($row_rsRelicsXP['aq_char_id'] == $overlordID){
            $returnXPOL += 1;
          } else {
            $returnXPH += 1;
          }
          
        }

      }
      

    } while ($row_rsRelicsXP = mysql_fetch_assoc($rsRelicsXP));

  }






}

if (isset($row_rsProgressReset['progress_quest_winner']) && $row_rsProgressReset['progress_quest_winner'] == "Overlord Wins"){
  if(in_array($campaign['camp_id'], $miniCampaigns)){
    $returnXPOL += 1;
  }
  $rewardsOL = explode(";", $row_rsProgressReset['quest_rew_ol']);
  $rewardsOLexp = array();
  foreach ($rewardsOL as $ol){
    $rewardsOLexp[] = explode(",", $ol);
  } 
  
  foreach ($rewardsOLexp as $olxp){
    if ($olxp[0] == "xp"){
      $returnXPOL += $olxp[1];
    }
  }
  
}

if (isset($row_rsProgressReset['progress_quest_winner']) && $row_rsProgressReset['progress_quest_winner'] == "Heroes Win"){
  $rewardsH = explode(";", $row_rsProgressReset['quest_rew_h']);
  $rewardsHexp = array();
  foreach ($rewardsH as $h){
    $rewardsHexp[] = explode(",", $h);
  } 
  
  foreach ($rewardsHexp as $hxp){
    if ($hxp[0] == "xp"){
      $returnXPH += $hxp[1];
    }
  }
}


$query_rsRumorsReset = sprintf("SELECT * FROM tbrumors_played INNER JOIN tbrumors ON played_rumor_id = rumor_id WHERE played_game_id = %s AND played_resolved_progress_id = %s or played_updated_progress_id = %s", 
                GetSQLValueString($gameID, "int"),
                GetSQLValueString($pID, "int"),
                GetSQLValueString($pID, "int"));
$rsRumorsReset = mysql_query($query_rsRumorsReset, $dbDescent) or die(mysql_error());
$row_rsRumorsReset = mysql_fetch_assoc($rsRumorsReset);

$rumorsUpdate = array();

do{
  if ($row_rsRumorsReset['played_id'] != NULL){
    $rumorsUpdate[] = array(
      'id' => $row_rsRumorsReset['played_id'],
      'rumor_id' => $row_rsRumorsReset['played_rumor_id'],
      'rumor_quest' => $row_rsRumorsReset['played_rumor_quest_id'],
      'rumor_resolved' => $row_rsRumorsReset['played_resolved'],
    );
  }
  

} while ($row_rsRumorsReset = mysql_fetch_assoc($rsRumorsReset));


$query_rsRumorsDelete = sprintf("SELECT * FROM tbrumors_played INNER JOIN tbrumors ON played_rumor_id = rumor_id WHERE played_game_id = %s AND played_progress_id = %s", 
                GetSQLValueString($gameID, "int"),
                GetSQLValueString($pID, "int"));
$rsRumorsDelete = mysql_query($query_rsRumorsDelete, $dbDescent) or die(mysql_error());
$row_rsRumorsDelete = mysql_fetch_assoc($rsRumorsDelete);

$rumorsDelete = array();

do{

  if ($row_rsRumorsDelete['played_id'] != NULL){
    $rumorsDelete[] = array(
      'id' => $row_rsRumorsReset['played_id'],
      'rumor_id' => $row_rsRumorsReset['played_rumor_id'],
      'rumor_quest' => $row_rsRumorsReset['played_rumor_quest_id'],
    );

    if ($row_rsRumorsDelete['played_rumor_quest_id'] != NULL){
      $returnThreat -= 1;
    }
  }

} while ($row_rsRumorsDelete = mysql_fetch_assoc($rsRumorsDelete));


$query_rsUsableDelete = sprintf("SELECT * FROM tbmonsters_usable WHERE usable_game_id = %s AND usable_progress_id = %s", 
                GetSQLValueString($gameID, "int"),
                GetSQLValueString($pID, "int"));
$rsUsableDelete = mysql_query($query_rsUsableDelete, $dbDescent) or die(mysql_error());
$row_rsUsableDelete = mysql_fetch_assoc($rsUsableDelete);


$query_rsAdvRewGame = sprintf("SELECT game_rumor_rew_used FROM tbgames WHERE game_id = %s", GetSQLValueString($gameID, "int"));
$rsAdvRewGame = mysql_query($query_rsAdvRewGame, $dbDescent) or die(mysql_error());
$row_rsAdvRewGame = mysql_fetch_assoc($rsAdvRewGame);

$AdvRewGame = $row_rsAdvRewGame['game_rumor_rew_used'];
$AdvRewGame = explode(',', $AdvRewGame);


$query_rsAdvRewProgress = sprintf("SELECT progress_rumor_rew_used FROM tbquests_progress WHERE progress_game_id = %s AND progress_id = %s", GetSQLValueString($gameID, "int"), GetSQLValueString($pID, "int"));
$rsAdvRewProgress = mysql_query($query_rsAdvRewProgress, $dbDescent) or die(mysql_error());
$row_rsAdvRewProgress = mysql_fetch_assoc($rsAdvRewProgress);

$AdvRewProgress = $row_rsAdvRewProgress['progress_rumor_rew_used'];
$AdvRewProgress = explode(',', $AdvRewProgress);

foreach ($AdvRewProgress as $arv){
  if(($key = array_search($arv, $AdvRewGame)) !== false) {
    unset($AdvRewGame[$key]);
  }  
}

$AdvRewGame = implode(",", $AdvRewGame);

$_SESSION['delete_phase'] = array(
  "travel" => $untravel,
  "spendxp" => $unspendxp,
  "spendxpSold" => $unspendxpSold,
  "threat" => $unspendthreat,
  "threatSold" => $unspendthreatSold,
  "buy" => $unbuy,
  "sell" => $unsell,
  "trade" => $untrade,
  "returnGold" => $returnGold,
  "returnThreat" => $returnThreat,
  "returnXPH" => $returnXPH,
  "returnXPOL" => $returnXPOL,
  "rumorsUpdate" => $rumorsUpdate,
  "rumorsDelete" => $rumorsDelete,
  "rumorRewardsUsed" => $AdvRewGame,
);

// echo '<pre>';
// var_dump($_SESSION['delete_phase']);
// echo '</pre>';

// echo $returnGold;
// echo '<br />';
// var_dump($unbuy);
// echo '<br />';
// var_dump($unsell);
// echo '<br />';
// var_dump($untrade);
// echo '<br />';
// var_dump($unspendxp);
// echo '<br />';
// var_dump($unspendthreat);


 ?>