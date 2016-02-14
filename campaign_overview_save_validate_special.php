<?php

//if (isset($_POST['special_heroes'])){
    foreach($_SESSION['rewards_heroes'] as $rh){
      switch ($rh[0]) {
        case "special":
          switch ($qID) {
            // relic taken
            case "9": // Ritual of Shadows
              if($_POST['progress_quest_winner'] == "Heroes Win"){
                mysql_select_db($database_dbDescent, $dbDescent);

                foreach ($_SESSION['campaign']['quests'] as $qsm){
                  // Enduring the Elements
                  if ($qsm['quest_id'] == 9){
                    if ($qsm['winner'] == "Overlord Wins"){
                      $insertSQL5 = sprintf("UPDATE tbitems_aquired SET aq_trade_char_id = %s, aq_trade_progress_id = %s WHERE aq_game_id = %s AND aq_relic_id = %s AND aq_trade_char_id is null",
                                GetSQLValueString($_POST['special_heroes'], "int"),
                                GetSQLValueString($pID, "int"),
                                GetSQLValueString($gameID, "int"),
                                GetSQLValueString($rh[1], "int"));
                      $Result5 = mysql_query($insertSQL5, $dbDescent) or die(mysql_error());

                      $insertSQL6 = sprintf("INSERT INTO tbitems_aquired (aq_game_id, aq_char_id, aq_relic_id, aq_item_gottraded, aq_progress_id) VALUES (%s, %s, %s, %s, %s)",
                                      GetSQLValueString($gameID, "int"),
                                      GetSQLValueString($_POST['special_heroes'], "int"),
                                      GetSQLValueString($rh[1], "int"),
                                      GetSQLValueString(1, "int"),
                                      GetSQLValueString($pID, "int"));

                      $Result6 = mysql_query($insertSQL6, $dbDescent) or die(mysql_error());
                    } else {
                      $insertSQL6 = sprintf("INSERT INTO tbitems_aquired (aq_game_id, aq_char_id, aq_relic_id, aq_progress_id) VALUES (%s, %s, %s, %s)",
                                      GetSQLValueString($gameID, "int"),
                                      GetSQLValueString($_POST['special_heroes'], "int"),
                                      GetSQLValueString($rh[1], "int"),
                                      GetSQLValueString($pID, "int"));

                      $Result6 = mysql_query($insertSQL6, $dbDescent) or die(mysql_error());
                    }
                  }
                }
                
              }
              break;
            // insert gold based on form field
            case "26": // Gathering Foretold
            case "27": // Honor Among Thieves
            case "44": // Ghost Town
            case "45": // Food for Worms
            case "46": // Three Heads, One Mind
            case "49":
            case "55": // The Incident
            case "85": // Three Heads, One Mind
            case "87": // Three Heads, One Mind
            case "88": // Three Heads, One Mind
            case "108":
              $val_questGold += ($_POST['special_heroes'] * $rh[1]);
              break;
            case "95": // Army
              if($_POST['progress_quest_winner'] == "Heroes Win"){
                $val_questGold += ($_POST['special_heroes'] * $rh[1]);
              }
              break;
          }
          break;
        case "blockmonster":
          switch ($qID) {
            case "97": // HoB - Flame
            case "100": // HoB - Shadowfall Mountain
              if ($_POST['progress_quest_winner'] == "Heroes Win"){
                mysql_select_db($database_dbDescent, $dbDescent);

                $insertSQLMonster = sprintf("INSERT INTO tbmonsters_usable (usable_monster_id, usable_progress_id, usable_game_id, usable_status, usable_quest) VALUES (%s, %s, %s, %s, %s)",
                           GetSQLValueString($rh[1], "int"),
                           GetSQLValueString($pID, "int"),
                           GetSQLValueString($gameID, "int"),
                           GetSQLValueString("blocked", "text"),
                           GetSQLValueString("Finale", "text"));
                
                $ResultMonster = mysql_query($insertSQLMonster, $dbDescent) or die(mysql_error());
              }
              break;   
          }
          break;

        case "freegold":
          switch ($qID) {
            case "108":
            case "109":
            case "110":
            case "111":
            case "112":
            case "113":
            case "114":
              $val_questGold += $rh[1];
              break;   
          }
          break;
      }
    }
//}


foreach($_SESSION['rewards_overlord'] as $rh){
  switch ($rh[0]) {
    case "special":
      switch ($qID) {
        case "9": // Ritual of Shadows
          if($_POST['progress_quest_winner'] == "Overlord Wins"){
            if (isset($_POST[$oID])){
              foreach ($_POST[$oID] as $skr){
                mysql_select_db($database_dbDescent, $dbDescent);
                $insertSQLSpecial9 = sprintf("UPDATE tbskills_aquired SET spendxp_sold = %s, spendxp_sold_progress_id = %s WHERE spendxp_game_id = %s AND spendxp_char_id = %s AND spendxp_skill_id = %s",
                     GetSQLValueString(1, "int"),
                     GetSQLValueString($pID, "int"),
                     GetSQLValueString($gameID, "int"),
                     GetSQLValueString($oID, "int"),
                     GetSQLValueString($skr, "int"));

                $query_rsSkillCost9 = sprintf("SELECT skill_cost FROM tbskills WHERE skill_id = %s", GetSQLValueString($skr, "int"));
                $rsSkillCost9 = mysql_query($query_rsSkillCost9, $dbDescent) or die(mysql_error());
                $row_rsSkillCost9 = mysql_fetch_assoc($rsSkillCost9);

                $insertSQL9 = sprintf("UPDATE tbcharacters SET char_xp = char_xp + %s WHERE char_id = %s",
                          GetSQLValueString($row_rsSkillCost9['skill_cost'], "int"),
                          GetSQLValueString($oID, "int"));
          
                
                $ResultSpecial9 = mysql_query($insertSQLSpecial9, $dbDescent) or die(mysql_error());
                $Result9 = mysql_query($insertSQL9, $dbDescent) or die(mysql_error());
              }
            }
          }
        case "26": // LoR - Gathering Foretold
          if (isset($_POST['special_overlord'])){
            if($_POST['special_overlord'] == "reward"){        
              $insertSQLSpecial = sprintf("INSERT INTO  tbskills_aquired (spendxp_game_id, spendxp_char_id, spendxp_skill_id, spendxp_progress_id) VALUES (%s, %s, %s, %s)",
                         GetSQLValueString($gameID, "int"),
                         GetSQLValueString($oID, "int"),
                         GetSQLValueString($rh[1], "int"),
                         GetSQLValueString($pID, "int"));
              
              mysql_select_db($database_dbDescent, $dbDescent);
              $ResultSpecial = mysql_query($insertSQLSpecial, $dbDescent) or die(mysql_error());


            } else {
              $val_questGold += 50;
            }
          }
          break;
        case "44": // Ghost Town
        case "45": // Food for Worms
        case "46": // Three Heads, One Mind
        case "49": // SoN - A Demonstration
        case "57": // SoN - Respected Citizen
        case "68": // MoR - Spread Your Wings
        case "69": // MoR - Finders and Keepers
        case "70": // MoR - My House, My Rules
          if (isset($_POST['special_overlord'])){
            if($_POST['special_overlord'] == "reward"){        
              $insertSQLSpecial = sprintf("INSERT INTO  tbskills_aquired (spendxp_game_id, spendxp_char_id, spendxp_skill_id, spendxp_progress_id) VALUES (%s, %s, %s, %s)",
                         GetSQLValueString($gameID, "int"),
                         GetSQLValueString($oID, "int"),
                         GetSQLValueString($rh[1], "int"),
                         GetSQLValueString($pID, "int"));
              
              mysql_select_db($database_dbDescent, $dbDescent);
              $ResultSpecial = mysql_query($insertSQLSpecial, $dbDescent) or die(mysql_error());
            }
          }
          break;
        case "92": // HoB
          if (isset($_POST['special_overlord'])){
            if($_POST['special_overlord'] == "reward"){        
              $insertSQLSpecial = sprintf("UPDATE tbcharacters SET char_xp = char_xp + %s WHERE char_game_id = %s AND char_id = %s",
                         GetSQLValueString($rh[1], "int"),
                         GetSQLValueString($gameID, "int"),
                         GetSQLValueString($oID, "int"));
              
              mysql_select_db($database_dbDescent, $dbDescent);
              $ResultSpecial = mysql_query($insertSQLSpecial, $dbDescent) or die(mysql_error());
            }
          }
          break;

        // If overlord wins give cards straight away
        case "40": // LoR - Fire and Brimstone  
        case "50": // SoN - Civil War
        case "51": // SoN - Without Mercy
        case "52": // SoN - Local Politics
        case "54": // SoN - Price of Power
        case "55": // SoN - The Incident
        case "63": // SoN - Widespread Panic
        case "64": // SoN - Nightmares
        case "65": // SoN - Respected Citizen
        case "74": // CotF - Crusade of the Forgotten
        case "76": // OotO - Oath of the Outcast
        case "78": // CoD - Burning Harvest
        case "80": // GoD - Guardians of Deephall
        case "104": // VoD - Trucebreaker
        case "106": // BotW - One Man's Trash

          if($_POST['progress_quest_winner'] == "Overlord Wins"){
            $insertSQLSpecial = sprintf("INSERT INTO  tbskills_aquired (spendxp_game_id, spendxp_char_id, spendxp_skill_id, spendxp_progress_id) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($gameID, "int"),
                       GetSQLValueString($oID, "int"),
                       GetSQLValueString($rh[1], "int"),
                       GetSQLValueString($pID, "int"));
            
            mysql_select_db($database_dbDescent, $dbDescent);
            $ResultSpecial = mysql_query($insertSQLSpecial, $dbDescent) or die(mysql_error());
          }
          break;
          
      }
      break;
    case "specialrelic":
      switch ($qID) {
        case "86": // HoB - Rellegar's Rest
        case "87": // HoB - Siege of Skytower
        case "89": // HoB - The Baron Returns
        case "95": // HoB - Army of Dal'Zunm
        case "96": // HoB - Prison of Khinn
          if (isset($_POST['special_relic']) && $_POST['special_relic'] != "none"){     
            $insertSQLRelic = sprintf("INSERT INTO  tbitems_aquired (aq_game_id, aq_char_id, aq_relic_id, aq_progress_id) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($gameID, "int"),
                       GetSQLValueString($_POST['special_relic'], "int"),
                       GetSQLValueString($rh[1], "int"),
                       GetSQLValueString($pID, "int"));
            
            mysql_select_db($database_dbDescent, $dbDescent);
            $ResultRelic = mysql_query($insertSQLRelic, $dbDescent) or die(mysql_error());
          }
          break;
          
      }
      break;
    case "specialmonster":
      switch ($qID) {
        case "92": // HoB - Caladen's Crossing
          if (isset($_POST['special_monster'])){    

            $insertSQLMonster = sprintf("INSERT INTO tbmonsters_usable (usable_monster_id, usable_progress_id, usable_game_id, usable_status, usable_quest) VALUES (%s, %s, %s, %s, %s)",
                           GetSQLValueString($_POST['special_monster'], "int"),
                           GetSQLValueString($pID, "int"),
                           GetSQLValueString($gameID, "int"),
                           GetSQLValueString("blocked", "text"),
                           GetSQLValueString("All", "text"));
            
            mysql_select_db($database_dbDescent, $dbDescent);
            $ResultMonster = mysql_query($insertSQLMonster, $dbDescent) or die(mysql_error());
          }
          break;
          
      }
      break;
    case "addmonster":
      switch ($qID) {
        case "6": // Desecrated tomb
        case "8": // Enduring the elements
        case "47": // Source of Sickness
          if ($_POST['progress_quest_winner'] == "Overlord Wins"){
            mysql_select_db($database_dbDescent, $dbDescent);

            $insertSQLMonster = sprintf("INSERT INTO tbmonsters_usable (usable_monster_id, usable_progress_id, usable_game_id, usable_status, usable_quest) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($rh[1], "int"),
                       GetSQLValueString($pID, "int"),
                       GetSQLValueString($gameID, "int"),
                       GetSQLValueString("added", "text"),
                       GetSQLValueString("All", "text"));
            
            $ResultMonster = mysql_query($insertSQLMonster, $dbDescent) or die(mysql_error());
          }
          break;   
      }
      break;

    case "relic":
      switch ($qID) {
        case "86": // HoB - Rellegar's Rest
        case "87": // HoB - Siege of Skytower
          $insertSQLRelic = sprintf("INSERT INTO  tbitems_aquired (aq_game_id, aq_char_id, aq_relic_id, aq_progress_id) VALUES (%s, %s, %s, %s)",
                     GetSQLValueString($gameID, "int"),
                     GetSQLValueString($oID, "int"),
                     GetSQLValueString($rh[1], "int"),
                     GetSQLValueString($pID, "int"));
          
          mysql_select_db($database_dbDescent, $dbDescent);
          $ResultRelic = mysql_query($insertSQLRelic, $dbDescent) or die(mysql_error());
        break;    
      }
      break;
  }
}

if (isset($_POST['jinns_lamp_item']) && $_POST['jinns_lamp_item'] != "empty"){
  $insertSQLLamp = sprintf("INSERT INTO tbitems_aquired (aq_item_id, aq_progress_id, aq_char_id, aq_game_id, aq_item_price_ovrd) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['jinns_lamp_item'], "int"),
                       GetSQLValueString($pID, "int"),
                       GetSQLValueString($_POST['jinns_lamp_player'], "int"),
                       GetSQLValueString($gameID, "int"),
                       GetSQLValueString(0, "int"));

  $insertSQLLampRemove = sprintf("UPDATE tbitems_aquired SET aq_item_sold = %s, aq_sold_progress_id = %s WHERE aq_game_id = %s AND shop_id = %s",
                       GetSQLValueString(2, "int"),
                       GetSQLValueString($pID, "int"),
                       GetSQLValueString($gameID, "int"),
                       GetSQLValueString($_POST['jinns_lamp_id'], "int"));

  mysql_select_db($database_dbDescent, $dbDescent);
  
  $ResultLamp = mysql_query($insertSQLLamp, $dbDescent) or die(mysql_error());
  $ResultLampRemove = mysql_query($insertSQLLampRemove, $dbDescent) or die(mysql_error());
}

if (isset($_POST[$_POST['archaic_scroll_player']])){
  foreach ($_POST[$_POST['archaic_scroll_player']] as $hskr){
    mysql_select_db($database_dbDescent, $dbDescent);
    $insertSQLSpecialR = sprintf("UPDATE tbskills_aquired SET spendxp_sold = %s, spendxp_sold_progress_id = %s WHERE spendxp_game_id = %s AND spendxp_char_id = %s AND spendxp_skill_id = %s",
         GetSQLValueString(1, "int"),
         GetSQLValueString($pID, "int"),
         GetSQLValueString($gameID, "int"),
         GetSQLValueString($_POST['archaic_scroll_player'], "int"),
         GetSQLValueString($hskr, "int"));

    $query_rsSkillCostR = sprintf("SELECT skill_cost FROM tbskills WHERE skill_id = %s", GetSQLValueString($hskr, "int"));
    $rsSkillCostR = mysql_query($query_rsSkillCostR, $dbDescent) or die(mysql_error());
    $row_rsSkillCostR = mysql_fetch_assoc($rsSkillCostR);

    $insertSQLR = sprintf("UPDATE tbcharacters SET char_xp = char_xp + %s WHERE char_id = %s",
              GetSQLValueString($row_rsSkillCostR['skill_cost'], "int"),
              GetSQLValueString($_POST['archaic_scroll_player'], "int"));

    $insertSQLScrollRemove = sprintf("UPDATE tbitems_aquired SET aq_item_sold = %s, aq_sold_progress_id = %s WHERE aq_game_id = %s AND shop_id = %s",
                       GetSQLValueString(2, "int"),
                       GetSQLValueString($pID, "int"),
                       GetSQLValueString($gameID, "int"),
                       GetSQLValueString($_POST['archaic_scroll_id'], "int"));

    
    $ResultSpecialR = mysql_query($insertSQLSpecialR, $dbDescent) or die(mysql_error());
    $ResultR = mysql_query($insertSQLR, $dbDescent) or die(mysql_error());
    $ResultScrollRemove = mysql_query($insertSQLScrollRemove, $dbDescent) or die(mysql_error());
  }
}


if ((isset($_POST['sunstone_check']) && $_POST['sunstone_check'] == "lost") || (isset($_POST['sunstone_return']) && $_POST['sunstone_return'] == "lost") || (isset($_POST['sunstone_pilgrimage']) && $_POST['sunstone_pilgrimage'] == "lost")){

  $insertSQLStoneRemove = sprintf("UPDATE tbitems_aquired SET aq_item_sold = %s, aq_sold_progress_id = %s WHERE aq_game_id = %s AND shop_id = %s",
                       GetSQLValueString(2, "int"),
                       GetSQLValueString($pID, "int"),
                       GetSQLValueString($gameID, "int"),
                       GetSQLValueString($_POST['sunstone_id'], "int"));

  mysql_select_db($database_dbDescent, $dbDescent);
  
  $ResultStoneRemove = mysql_query($insertSQLStoneRemove, $dbDescent) or die(mysql_error());

  if (isset($_POST['sunstone_return']) && $_POST['sunstone_return'] == "lost"){
    $insertSQLFuryXP = sprintf("UPDATE tbcharacters SET char_xp = char_xp + %s WHERE char_game_id = %s AND char_id = %s",
                             GetSQLValueString(1, "int"),
                             GetSQLValueString($gameID, "int"),
                             GetSQLValueString($oID, "int"));

    $ResultFuryXP = mysql_query($insertSQLFuryXP, $dbDescent) or die(mysql_error());
  }
}

if (isset($_POST['sunstone_check']) && $_POST['sunstone_check'] == "stolen"){

  mysql_select_db($database_dbDescent, $dbDescent);

  $insertSQLStone = sprintf("UPDATE tbitems_aquired SET aq_trade_char_id = %s, aq_trade_progress_id = %s WHERE aq_game_id = %s AND shop_id = %s AND aq_trade_char_id is null",
                        GetSQLValueString($oID, "int"),
                        GetSQLValueString($pID, "int"),
                        GetSQLValueString($gameID, "int"),
                        GetSQLValueString($_POST['sunstone_id'], "int"));
  $ResultStone = mysql_query($insertSQLStone, $dbDescent) or die(mysql_error());

  $insertSQLFury = sprintf("INSERT INTO tbitems_aquired (aq_game_id, aq_char_id, aq_relic_id, aq_item_gottraded, aq_progress_id) VALUES (%s, %s, %s, %s, %s)",
                  GetSQLValueString($gameID, "int"),
                  GetSQLValueString($oID, "int"),
                  GetSQLValueString(11, "int"),
                  GetSQLValueString(1, "int"),
                  GetSQLValueString($pID, "int"));

  $ResultFury = mysql_query($insertSQLFury, $dbDescent) or die(mysql_error());
}

if(isset($_POST['citizen'])){
  if ($_POST['citizen'] != "no"){
    $insertSQLAgent = sprintf("UPDATE tbskills_aquired SET spendxp_sold = %s, spendxp_sold_progress_id = %s WHERE spendxp_game_id = %s AND spendxp_char_id = %s AND spendxp_id = %s",
     GetSQLValueString(1, "int"),
     GetSQLValueString($pID, "int"),
     GetSQLValueString($gameID, "int"),
     GetSQLValueString($oID, "int"),
     GetSQLValueString($_POST['citizen'], "int"));


    $ResultAgent = mysql_query($insertSQLAgent, $dbDescent) or die(mysql_error());
  }
}

$query_rsAdvRewGame = sprintf("SELECT game_rumor_rew_used FROM tbgames WHERE game_id = %s", GetSQLValueString($gameID, "int"));
$rsAdvRewGame = mysql_query($query_rsAdvRewGame, $dbDescent) or die(mysql_error());
$row_rsAdvRewGame = mysql_fetch_assoc($rsAdvRewGame);

$AdvRewGame = $row_rsAdvRewGame['game_rumor_rew_used'];
$CurrentAdvRew = "";


// Armed to the Teeth

if (isset($_POST['armed_teeth_item']) && $_POST['armed_teeth_item'] != "empty" && $_POST['progress_quest_winner'] == "Heroes Win" && !in_array($_POST['armed_teeth_item'], $checkDuplicate)){
  $insertSQLArmed = sprintf("INSERT INTO tbitems_aquired (aq_item_id, aq_progress_id, aq_char_id, aq_game_id, aq_item_price_ovrd) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['armed_teeth_item'], "int"),
                       GetSQLValueString($pID, "int"),
                       GetSQLValueString($_POST['armed_teeth_player'], "int"),
                       GetSQLValueString($gameID, "int"),
                       GetSQLValueString(0, "int"));

  mysql_select_db($database_dbDescent, $dbDescent);
  
  $ResultArmed = mysql_query($insertSQLArmed, $dbDescent) or die(mysql_error());

  $CurrentAdvRew = "24" . ",";
}

// Crown of Destiny Reward
if (($qID != 9) || ($qID == 9 && $_POST['progress_quest_winner'] != "Overlord Wins")){
  $destinyUsed = 0;
  foreach($_SESSION['verify_values']['players'] as $plyID){
    if (isset($_POST[$plyID])){
      foreach ($_POST[$plyID] as $skr){
        mysql_select_db($database_dbDescent, $dbDescent);
        $insertSQLSpecialRet = sprintf("UPDATE tbskills_aquired SET spendxp_sold = %s, spendxp_sold_progress_id = %s WHERE spendxp_game_id = %s AND spendxp_char_id = %s AND spendxp_skill_id = %s",
             GetSQLValueString(1, "int"),
             GetSQLValueString($pID, "int"),
             GetSQLValueString($gameID, "int"),
             GetSQLValueString($plyID, "int"),
             GetSQLValueString($skr, "int"));
  
        $query_rsSkillCostRet = sprintf("SELECT skill_cost FROM tbskills WHERE skill_id = %s", GetSQLValueString($skr, "int"));
        $rsSkillCostRet = mysql_query($query_rsSkillCostRet, $dbDescent) or die(mysql_error());
        $row_rsSkillCostRet = mysql_fetch_assoc($rsSkillCostRet);
  
        $insertSQLRet = sprintf("UPDATE tbcharacters SET char_xp = char_xp + %s WHERE char_id = %s",
                  GetSQLValueString($row_rsSkillCostRet['skill_cost'], "int"),
                  GetSQLValueString($plyID, "int"));
  
        
        $ResultSpecialRet = mysql_query($insertSQLSpecialRet, $dbDescent) or die(mysql_error());
        $ResultRet = mysql_query($insertSQLRet, $dbDescent) or die(mysql_error());

        $destinyUsed = 1;
      }
    }
  }

  if ($destinyUsed == 1){
    $CurrentAdvRew = "79" . ",";
  }
}

if ($CurrentAdvRew != ""){

  $CurrentAdvRew = rtrim($CurrentAdvRew, ",");
  $insertSQLAdvRew = sprintf("UPDATE tbgames SET game_rumor_rew_used = %s WHERE game_id = %s",
     GetSQLValueString($CurrentAdvRew, "text"),
     GetSQLValueString($gameID, "int"));

  $ResultAdvRew = mysql_query($insertSQLAdvRew, $dbDescent) or die(mysql_error());

  $insertSQLAdvRew2 = sprintf("UPDATE tbquests_progress SET progress_rumor_rew_used = %s WHERE progress_game_id = %s AND progress_id = %s",
     GetSQLValueString($CurrentAdvRew, "text"),
     GetSQLValueString($gameID, "int"),
     GetSQLValueString($pID, "int"));

  $ResultAdvRew2 = mysql_query($insertSQLAdvRew2, $dbDescent) or die(mysql_error());
}



?>