<?php

require_once('Connections/dbDescent.php');

// Includes
include 'includes/protected_page.php';
include 'includes/function_getSQLValueString.php';

// FIX ME: Load mini campaigns from database?
$miniCampaigns = array(1,3,5);

mysql_select_db($database_dbDescent, $dbDescent);

if (!isset($_SESSION)) {
  session_start();
}

$players = $_SESSION['players'];
$allTravel = $_SESSION['alltravel'];
$gameID = $_SESSION['validate']['gameID'];
$gameID_obscured = $_SESSION['validate']['gameID'] * 43021;
$pID = $_SESSION['validate']['pID'];
$qID = $_SESSION['validate']['qID'];
$oID = $_SESSION['validate']['oID'];

  // Reset any previous errors when form gets submitted
  $_SESSION["errorcode"] = array();
  $_SESSION['old_post'] = $_POST;
  $noError = 1;


// -----------------
 
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "travel-substep-form")) { 

  $currentspecial = NULL;
  $eventoption = NULL;  
  foreach ($allTravel as $atssp){
    if ($atssp['id'] == $_POST['travel_step']){
      
      //return the option for disabled version
      $eventoption = $atssp['option'];

      //return the card
      $travelcard = $atssp['card'];

      // does the travel step have a special action
      if (isset($atssp['special'])){

        $currentspecial = $_SESSION['lastspecialtype'] = $atssp['special'];

        // We save the location in the array of the last special item (+1 because we are making it next step)
        $_SESSION['lastspecial'] = (count($_SESSION['travelevents']) + 1);

      }
    }
  }

  $_SESSION['travelevents'][] = array(
      "event" => $_POST['travel_step'],
      "option" => $eventoption,
      "special" => $currentspecial,
      "card" => $travelcard,
      "gold" => NULL,
      "item" => NULL,
      "player" => NULL,
  );

  $countStep = $_POST['total_step_add'] - 1;

  if($currentspecial == "skip"){
    if ($_POST['current_step_add'] != $countStep){
      $_SESSION['travelevents'][] = array(
        "event" => 999,
        "option" => '<option name="travel" value="NULL">' . "Skipped" . '</option>',
        "special" => $currentspecial,
        "card" => NULL,
        "gold" => NULL,
        "item" => NULL,
        "player" => NULL,
      );
    }
    $_SESSION['lastspecial'] = NULL;
  }

  if($currentspecial == "double"){
    $_SESSION['addedstep'] = $countStep - 2;
    $_SESSION['lastspecial'] = NULL;
  }

  $insertGoTo = "campaign_overview_save.php?urlGamingID=" . $gameID_obscured . "&part=t&data=x";
  header(sprintf("Location: %s", $insertGoTo));
  die("Redirecting to campaign_overview_save.php"); 

}





if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "travel-substep-details-form")) {

  if((isset($_POST["travel_gold"]) && $_POST["travel_gold"] != "") && (isset($_POST["travel_item"]) && $_POST["travel_item"] != "")){
    $_SESSION['errorcode'][] = "The heroes can either gain gold or an item, not both ";
    $insertGoTo = "campaign_overview_save.php?urlGamingID=" . $gameID_obscured . "&part=t&data=x";
    header(sprintf("Location: %s", $insertGoTo));
    die("Redirecting to campaign_overview_save.php"); 
  }

  if(isset($_POST["travel_gold"]) && $_POST["travel_gold"] != ""){
    $_SESSION['travelevents'][count($_SESSION['travelevents']) - 1]['gold'] = $_POST["travel_gold"];
    $_SESSION['lastspecial'] = NULL;
    $_SESSION['lastspecialtype'] = NULL;
  }

  if(isset($_POST["travel_item"]) && $_POST["travel_item"] != ""){
    $_SESSION['travelevents'][count($_SESSION['travelevents']) - 1]['item'] = $_POST["travel_item"];
    $_SESSION['travelevents'][count($_SESSION['travelevents']) - 1]['player'] = $_POST["travel_player"];
    $_SESSION['lastspecial'] = NULL;
    $_SESSION['lastspecialtype'] = NULL;
  }

  if(isset($_POST["travel_item"]) && $_POST["travel_item"] == ""){
    $_SESSION['lastspecial'] = NULL;
    $_SESSION['lastspecialtype'] = NULL;
  }

  if(isset($_POST["travel_goldskp"]) && $_POST["travel_goldskp"] != ""){
    $_SESSION['travelevents'][count($_SESSION['travelevents']) - 1]['gold'] = $_POST["travel_goldskp"]; 
    $_SESSION['lastspecial'] = NULL;
    $_SESSION['lastspecialtype'] = NULL;
  }

  $countStep = $_POST['total_step_update'];

  if(isset($_POST["travel_skipchk"])){
    if($_POST["travel_skipchk"] == 1 && $_POST['current_step_update'] != $countStep){
      $_SESSION['travelevents'][] = array(
        "event" => 999,
        "option" => '<option name="travel" value="NULL">' . "Skipped" . '</option>',
        "special" => NULL,
        "card" => NULL,
        "gold" => NULL,
        "item" => NULL,
        "player" => NULL,
      );
    }
    $_SESSION['lastspecial'] = NULL;
    $_SESSION['lastspecialtype'] = NULL;
  }

  if(isset($_POST["travel_goldskp"])){
    if($_POST["travel_goldskp"] == -25 && $_POST['current_step_update'] != $countStep){
      $_SESSION['travelevents'][] = array(
        "event" => 999,
        "option" => '<option name="travel" value="NULL">' . "Skipped" . '</option>',
        "special" => NULL,
        "card" => NULL,
        "gold" => NULL,
        "item" => NULL,
        "player" => NULL,
      );
    }
    $_SESSION['lastspecial'] = NULL;
    $_SESSION['lastspecialtype'] = NULL;
  }

  $insertGoTo = "campaign_overview_save.php?urlGamingID=" . $gameID_obscured . "&part=t&data=x";
  header(sprintf("Location: %s", $insertGoTo));
  die("Redirecting to campaign_overview_save.php"); 

}

 



if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "travel-step-form")) {
  
  foreach ($_SESSION['travelevents'] as $tes){
    $insertSQL = sprintf("INSERT INTO tbtravel_aquired (travel_aq_event_id, travel_aq_progress_id, travel_aq_game_id, travel_aq_gold, travel_aq_item, travel_aq_player) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($tes['event'], "int"),
                       GetSQLValueString($pID, "int"),
                       GetSQLValueString($gameID, "int"),
                       GetSQLValueString($tes['gold'], "int"),
                       GetSQLValueString($tes['item'], "int"),
                       GetSQLValueString($tes['player'], "int"));

    mysql_select_db($database_dbDescent, $dbDescent);
    $Result1 = mysql_query($insertSQL, $dbDescent) or die(mysql_error());

    if ($tes['gold'] != NULL){
      $insertSQLGold = sprintf("UPDATE tbgames SET game_gold = game_gold + %s WHERE game_id = %s",
                         GetSQLValueString($tes['gold'], "int"),
                         GetSQLValueString($gameID, "int"));

      mysql_select_db($database_dbDescent, $dbDescent);
      $ResultGold = mysql_query($insertSQLGold, $dbDescent) or die(mysql_error());
    }

    if ($tes['item'] != NULL){
      $insertSQLItem = sprintf("INSERT INTO tbitems_aquired (aq_game_id, aq_char_id, aq_item_id, aq_item_price_ovrd, aq_progress_id) VALUES (%s, %s, %s, %s, %s)",
                        GetSQLValueString($gameID, "int"),
                        GetSQLValueString($tes['player'], "int"),
                        GetSQLValueString($tes['item'], "int"),
                        GetSQLValueString(0, "int"),
                        GetSQLValueString($pID, "int"));

      mysql_select_db($database_dbDescent, $dbDescent);
      $ResultItem = mysql_query($insertSQLItem, $dbDescent) or die(mysql_error());
    }
  }
  $insertSQL2 = sprintf("UPDATE tbquests_progress SET progress_set_travel = 1 WHERE progress_id = %s", 
                       GetSQLValueString($pID, "int"));

  mysql_select_db($database_dbDescent, $dbDescent);
  $Result2 = mysql_query($insertSQL2, $dbDescent) or die(mysql_error());

  $_SESSION['travelevents'] = array();
  $_SESSION['lastspecial'] = NULL;
  $_SESSION['lastspecialtype'] = NULL;

  $insertGoTo = "campaign_overview.php?urlGamingID=" . $gameID_obscured;
  header(sprintf("Location: %s", $insertGoTo));
  die("Redirecting to campaign_overview.php"); 

}





if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "quest-details-form")) {

  // Validate as much data as possible
  if (!isset($_SESSION['validate']['token']) || $_SESSION['validate']['token'] != $_POST['token']){
    $_SESSION["errorcode"][] = "The submitted security token did not match, please try again.";
    $noError = 0; 
  }

  if (isset($_POST['progress_quest_time']) && !in_array($_POST['progress_quest_time'], $_SESSION['verify_values']['time'])){
    $_SESSION["errorcode"][] = "Illegal value submitted for 'Quest time' selection.";
    $noError = 0; 

  }

  if (!isset($_POST['progress_quest_winner']) || !in_array($_POST['progress_quest_winner'], $_SESSION['verify_values']['winner'])){
    $_SESSION["errorcode"][] = "Illegal value submitted for 'Quest Winner' selection.";
    $noError = 0; 
  }

  if (isset($_POST['progress_enc1_winner']) && !in_array($_POST['progress_enc1_winner'], $_SESSION['verify_values']['winner'])){
    $_SESSION["errorcode"][] = "Illegal value submitted for 'Encounter 1 Winner' selection.";
    $noError = 0; 
  }

  if (isset($_POST['progress_enc2_winner']) && !in_array($_POST['progress_enc2_winner'], $_SESSION['verify_values']['winner'])){
    $_SESSION["errorcode"][] = "Illegal value submitted for 'Encounter 2 Winner' selection.";
    $noError = 0; 
  }

  if (isset($_SESSION['verify_values']['monsters_enc1'])){
    if (!isset($_POST['progress_enc1_monsters']) || array_diff($_POST['progress_enc1_monsters'], $_SESSION['verify_values']['monsters_enc1'])){
      $_SESSION["errorcode"][] = "Illegal value(s) submitted for 'Encounter 1 Monsters' selection.";
      $noError = 0; 
    }
  }

  if (isset($_SESSION['verify_values']['monsters_enc2'])){
    if (!isset($_POST['progress_enc2_monsters']) || array_diff($_POST['progress_enc2_monsters'], $_SESSION['verify_values']['monsters_enc2'])){
      $_SESSION["errorcode"][] = "Illegal value(s) submitted for 'Encounter 2 Monsters' selection.";
      $noError = 0; 
    }
  }

  if (isset($_SESSION['verify_values']['monsters_enc3'])){
    if (!isset($_POST['progress_enc3_monsters']) || array_diff($_POST['progress_enc3_monsters'], $_SESSION['verify_values']['monsters_enc3'])){
      $_SESSION["errorcode"][] = "Illegal value(s) submitted for 'Encounter 3 Monsters' selection.";
      $noError = 0; 
    }
  }

  if (isset($_POST['search_id']) && array_diff($_POST['search_id'], $_SESSION['verify_values']['search_cards'])){
    $_SESSION["errorcode"][] = "Illegal value(s) submitted for 'Search Cards' selection.";
    $noError = 0; 
  }

  if (($_POST['search_item'] != "empty") && !in_array($_POST['search_item'], $_SESSION['verify_values']['items_available'])){
    $_SESSION["errorcode"][] = "Illegal value(s) submitted for 'Treasure Found' selection.";
    $noError = 0; 
  }

  if (isset($_POST['search_player']) && !in_array($_POST['search_player'], $_SESSION['verify_values']['heroes'])){
    $_SESSION["errorcode"][] = "Illegal value(s) submitted for 'Hero receiving treasure' selection.";
    $noError = 0; 
  }

  if (($_POST['secretroom_item'] != "empty") && !in_array($_POST['secretroom_item'], $_SESSION['verify_values']['items_available'])){
    $_SESSION["errorcode"][] = "Illegal value(s) submitted for 'Secret Room Reward' selection.";
    $noError = 0; 
  }

  if (isset($_POST['secretroom_player']) && !in_array($_POST['secretroom_player'], $_SESSION['verify_values']['heroes'])){
    $_SESSION["errorcode"][] = "Illegal value(s) submitted for 'Hero receiving reward' selection.";
    $noError = 0; 
  }

  if (isset($_POST['threat_tokens']) && !in_array($_POST['threat_tokens'], $_SESSION['verify_values']['threat'])){
    $_SESSION["errorcode"][] = "Illegal value(s) submitted for 'Treat Tokens' selection.";
    $noError = 0; 
  }

  if (isset($_POST['threat_agent']) && !in_array($_POST['threat_agent'], $_SESSION['verify_values']['yesno'])){
    $_SESSION["errorcode"][] = "Illegal value(s) submitted for 'Agent Defeated' selection.";
    $noError = 0; 
  }

  if (isset($_POST['threat_deal']) && !in_array($_POST['threat_deal'], $_SESSION['verify_values']['yesno'])){
    $_SESSION["errorcode"][] = "Illegal value(s) submitted for 'Cut A Deal' selection.";
    $noError = 0; 
  }
  

  if($noError == 0){
    $insertGoTo = "campaign_overview_save.php?urlGamingID=" . $gameID_obscured . "&part=q";
    header(sprintf("Location: %s", $insertGoTo));
    die("Redirecting to campaign_overview.php"); 
  }

  $val_encounter1Monsters = NULL;
  $val_encounter1Check = array();
  foreach($_POST['progress_enc1_monsters'] as $pm1){
    $val_encounter1Check[] = $pm1;
    $val_encounter1Monsters = $val_encounter1Monsters . $pm1 . ",";
  }
  $val_encounter1Monsters = rtrim($val_encounter1Monsters, ",");

  $val_encounter2Monsters = NULL;
  $val_encounter2Check = array();
  if (isset($_POST['progress_enc2_monsters'])){
    foreach($_POST['progress_enc2_monsters'] as $pm2){
      $val_encounter2Check[] = $pm2;
      $val_encounter2Monsters = $val_encounter2Monsters . $pm2 . ",";
    }
    $val_encounter2Monsters = rtrim($val_encounter2Monsters, ",");
  }

  $val_encounter3Monsters = NULL;
  $val_encounter3Check = array();
  if (isset($_POST['progress_enc3_monsters'])){
    foreach($_POST['progress_enc3_monsters'] as $pm3){
      $val_encounter3Check[] = $pm3;
      $val_encounter3Monsters = $val_encounter3Monsters . $pm3 . ",";
    }
    $val_encounter3Monsters = rtrim($val_encounter3Monsters, ",");
  }

  $UniqueS = 1;
  if (count(array_unique($val_encounter1Check)) !== count($val_encounter1Check)) {
    $UniqueS = 0;
  }
  if (count(array_unique($val_encounter2Check)) !== count($val_encounter2Check)) {
    $UniqueS = 0;
  }
  if (count(array_unique($val_encounter3Check)) !== count($val_encounter3Check)) {
    $UniqueS = 0;
  }


  // Find out who got the relic
  $val_relicRecipiant = NULL;
  if (isset($_POST['progress_relic_recipiant']) && $_POST['progress_quest_winner'] == "Heroes Win"){
    $val_relicRecipiant = $_POST['progress_relic_recipiant'];
  } else if($_POST['progress_quest_winner'] == "Overlord Wins") {
    $val_relicRecipiant = $oID;
  }

  // Which random shop item was received in a mini campaign?
  $RandomItem = 1;
  if ($_POST['progress_quest_winner'] == "Heroes Win"){
    if(isset($_POST['random_item']) && $_POST['random_item'] == "empty"){
      $RandomItem = 0;
    } else {
      $val_randomItemId = $_POST['random_item'];
    }
  }

  // What Treasure chest (search) item was selected?
  $SearchItem = 1;
  if (isset($_POST['search_id']) && in_array(6, $_POST['search_id'])){
    if ($_POST['search_item'] == "empty") {
      $SearchItem = 0;
    } else {
      $val_searchItemId = $_POST['search_item'];
    }
  }

  // What Secret Room?
  $SecretRoomItem = 1;
  if (isset($_POST['search_id']) && (in_array(9, $_POST['search_id']) || in_array(10, $_POST['search_id'])) ){
    if (($_POST['secretroom'] == "3" || $_POST['secretroom'] == "4" || $_POST['secretroom'] == "8") && $_POST['secretRoomCleared'] == 1){
      if ($_POST['secretroom_item'] == "empty") {
        $SecretRoomItem = 0;
      } else {
        $val_SecretRoomItemId = $_POST['secretroom_item'];
      }
    }
  }

  $checkDuplicate = array();
  $duplicateItem = 1;

  if(isset($val_randomItemId)){
    $checkDuplicate[] = $val_randomItemId;
  }
  if (isset($val_SecretRoomItemId)){
    $checkDuplicate[] = $val_SecretRoomItemId;
  }
  if (isset($val_searchItemId)){
    $checkDuplicate[] = $val_searchItemId;
  }

  $checkDuplicateUnique = array_unique($checkDuplicate);
  if(count($checkDuplicate) != count($checkDuplicateUnique)){
    $duplicateItem = 0;
  }


  // if (isset($val_SecretRoomItemId) && isset($val_searchItemId)){
  //   if($val_SecretRoomItemId == $val_searchItemId){
  //     $duplicateItem = 0;
  //   }
  // }
  // 
  
  // default values
  $val_searchGold = 0;
  $val_totalThreat = 1;
  $val_questGold = 0;
  $val_allySkill = NULL;
  if ($_SESSION['quest_type'] == "Quest") {
    $defaultXP = 1;
  } else {
    $defaultXP = 0;
  } 

  $val_xpHeroes = $val_xpOverlord = $defaultXP;


  if ($UniqueS == 1 && $RandomItem == 1 && $SearchItem == 1 && $SecretRoomItem == 1 && $duplicateItem == 1){
      $insertSQL = sprintf("UPDATE tbquests_progress SET progress_quest_winner = %s, progress_enc1_winner = %s, progress_enc2_winner = %s, progress_enc1_monsters = %s, progress_enc2_monsters = %s, progress_enc3_monsters = %s, progress_relic_char = %s, progress_quest_time = %s WHERE progress_quest_id = %s AND progress_game_id = %s",
                           GetSQLValueString($_POST['progress_quest_winner'], "text"),
                           GetSQLValueString($_POST['progress_enc1_winner'], "text"),
                           GetSQLValueString($_POST['progress_enc2_winner'], "text"),
                           GetSQLValueString($val_encounter1Monsters, "text"),
                           GetSQLValueString($val_encounter2Monsters, "text"),
                           GetSQLValueString($val_encounter3Monsters, "text"),
                           GetSQLValueString($val_relicRecipiant, "int"),
                           GetSQLValueString($_POST['progress_quest_time'], "int"),
                           GetSQLValueString($qID, "int"),
                           GetSQLValueString($gameID, "int"));

      // Add a new entry to the items_aquired table, so the relic is added to the inventory of the selected player
      if (isset($_POST['progress_relic_recipiant'])){
        $relicSteal = 0;
        $awardxp = 0;
        if(in_array($_SESSION['validate']['expID'], $miniCampaigns)){
          $heroRelics = array();
          $OLRelics = array();
          // Go through the items each player has
          foreach($_SESSION['players'] as $relPlayer){
            foreach($relPlayer['items'] as $ritems){
              // if its a relic
              if ($ritems['relic_id'] != NULL){
                // and the player is the overlord, add it to the OL array, otherwise add it to the heroes array
                if($relPlayer['id'] == $oID){
                  $OLRelics[] = $ritems['relic_id'];
                } else {
                  $heroRelics[] = $ritems['relic_id'];
                }
              }
            }
          }
          // if the person receiving te relic is the overlord
          if($val_relicRecipiant == $oID){
            //check if he already has the relic, award him 1xp
            if(in_array($_SESSION['relic_id'], $OLRelics)){
              $val_xpOverlord += 1;
              $awardxp = 1;

            // else, if the heroes have the relic, trade it
            } else if(in_array($_SESSION['relic_id'], $heroRelics)) {
              $relicSteal = 1;
              $insertSQLSteal = sprintf("UPDATE tbitems_aquired SET aq_trade_char_id = %s, aq_trade_progress_id = %s WHERE aq_game_id = %s AND aq_relic_id = %s AND aq_trade_char_id is null",
                                GetSQLValueString($oID, "int"),
                                GetSQLValueString($pID, "int"),
                                GetSQLValueString($gameID, "int"),
                                GetSQLValueString($_SESSION['relic_id'], "int"));

              $insertSQLSteal2 = sprintf("INSERT INTO tbitems_aquired (aq_game_id, aq_char_id, aq_relic_id, aq_item_gottraded, aq_progress_id) VALUES (%s, %s, %s, %s, %s)",
                                      GetSQLValueString($gameID, "int"),
                                      GetSQLValueString($oID, "int"),
                                      GetSQLValueString($_SESSION['relic_id'], "int"),
                                      GetSQLValueString(1, "int"),
                                      GetSQLValueString($pID, "int"));

            // else just give the relic
            } else {
              $insertSQL2 = sprintf("INSERT INTO tbitems_aquired (aq_relic_id, aq_progress_id, aq_char_id, aq_game_id) VALUES (%s, %s, %s, %s)",
                             GetSQLValueString($_SESSION['relic_id'], "int"),
                             GetSQLValueString($pID, "int"),
                             GetSQLValueString($val_relicRecipiant, "int"),
                             GetSQLValueString($gameID, "int"));
            }

          // else, if its a hero
          } else {
            // check if the heroes already have the relic, award heroes 1xp
            if(in_array($_SESSION['relic_id'], $heroRelics)){
              $val_xpHeroes += 1;
              $awardxp = 1;

            // else, if the heroes have the relic, trade it
            } else if(in_array($_SESSION['relic_id'], $OLRelics)) {
              $relicSteal = 1;
              $insertSQLSteal = sprintf("UPDATE tbitems_aquired SET aq_trade_char_id = %s, aq_trade_progress_id = %s WHERE aq_game_id = %s AND aq_relic_id = %s AND aq_trade_char_id is null",
                                GetSQLValueString($oID, "int"),
                                GetSQLValueString($pID, "int"),
                                GetSQLValueString($gameID, "int"),
                                GetSQLValueString($_SESSION['relic_id'], "int"));

              $insertSQLSteal2 = sprintf("INSERT INTO tbitems_aquired (aq_game_id, aq_char_id, aq_relic_id, aq_item_gottraded, aq_progress_id) VALUES (%s, %s, %s, %s, %s)",
                                      GetSQLValueString($gameID, "int"),
                                      GetSQLValueString($oID, "int"),
                                      GetSQLValueString($_SESSION['relic_id'], "int"),
                                      GetSQLValueString(1, "int"),
                                      GetSQLValueString($pID, "int"));

            // else just give the relic
            } else {
              $insertSQL2 = sprintf("INSERT INTO tbitems_aquired (aq_relic_id, aq_progress_id, aq_char_id, aq_game_id) VALUES (%s, %s, %s, %s)",
                             GetSQLValueString($_SESSION['relic_id'], "int"),
                             GetSQLValueString($pID, "int"),
                             GetSQLValueString($val_relicRecipiant, "int"),
                             GetSQLValueString($gameID, "int"));
            }
          }

        } else {
          $insertSQL2 = sprintf("INSERT INTO tbitems_aquired (aq_relic_id, aq_progress_id, aq_char_id, aq_game_id) VALUES (%s, %s, %s, %s)",
                             GetSQLValueString($_SESSION['relic_id'], "int"),
                             GetSQLValueString($pID, "int"),
                             GetSQLValueString($val_relicRecipiant, "int"),
                             GetSQLValueString($gameID, "int"));
        } 
      }

      if ($_POST['progress_quest_winner'] == "Heroes Win" && isset($_POST['random_item']) && $_POST['random_item'] != "empty"){
        $insertSQLRandomShop = sprintf("INSERT INTO tbitems_aquired (aq_item_id, aq_progress_id, aq_char_id, aq_game_id, aq_item_price_ovrd) VALUES (%s, %s, %s, %s, %s)",
                             GetSQLValueString($_POST['random_item'], "int"),
                             GetSQLValueString($pID, "int"),
                             GetSQLValueString($_POST['random_player'], "int"),
                             GetSQLValueString($gameID, "int"),
                             GetSQLValueString(0, "int"));
      }

      if (isset($_POST['search_item']) && $_POST['search_item'] != "empty"){
        $insertSQLSearch = sprintf("INSERT INTO tbitems_aquired (aq_item_id, aq_progress_id, aq_char_id, aq_game_id, aq_item_price_ovrd) VALUES (%s, %s, %s, %s, %s)",
                             GetSQLValueString($_POST['search_item'], "int"),
                             GetSQLValueString($pID, "int"),
                             GetSQLValueString($_POST['search_player'], "int"),
                             GetSQLValueString($gameID, "int"),
                             GetSQLValueString(0, "int"));
      }

      if (isset($_POST['secretroom_item']) && $_POST['secretroom_item'] != "empty"){
        $insertSQLSecretRoom = sprintf("INSERT INTO tbitems_aquired (aq_item_id, aq_progress_id, aq_char_id, aq_game_id, aq_item_price_ovrd) VALUES (%s, %s, %s, %s, %s)",
                             GetSQLValueString($_POST['secretroom_item'], "int"),
                             GetSQLValueString($pID, "int"),
                             GetSQLValueString($_POST['secretroom_player'], "int"),
                             GetSQLValueString($gameID, "int"),
                             GetSQLValueString(0, "int"));
      }
      

      // Get the gold value of all searchcards and update the stats for it.
      if(!empty($_POST['search_id'])) {

        foreach($_POST['search_id'] as $check) {

          $query_rsSearchData2 = sprintf("SELECT * FROM tbsearch WHERE search_id = %s",GetSQLValueString($check, "int"));
          $rsSearchData2 = mysql_query($query_rsSearchData2, $dbDescent) or die(mysql_error());
          $row_rsSearchData2 = mysql_fetch_assoc($rsSearchData2);
          $totalRows_rsSearchData2 = mysql_num_rows($rsSearchData2);

          $val_searchGold = $val_searchGold + $row_rsSearchData2['search_value'];

          // update stats
          $insertSQLSrc = sprintf("UPDATE tbsearch SET search_found = search_found + 1 WHERE search_id = %s",
                             GetSQLValueString($check, "int"));

          $ResultSrc = mysql_query($insertSQLSrc, $dbDescent) or die(mysql_error());
        }

      }


      //Update secret room stats
      if ($_POST['secretroom'] != "empty"){
        $insertSQLSec = sprintf("UPDATE tbsecretrooms SET secretroom_found = secretroom_found + 1, secretroom_cleared = secretroom_cleared + %s WHERE secretroom_id = %s",
                              GetSQLValueString($_POST['secretRoomCleared'], "int"),
                              GetSQLValueString($_POST['secretroom'], "int"));

        $ResultSec = mysql_query($insertSQLSec, $dbDescent) or die(mysql_error());
      }

      

      // Add or substract the threat tokens gained/spent during the game
      if(isset($_POST['threat_tokens'])){
        $val_totalThreat += $_POST['threat_tokens'];
      }

      if(isset($_POST['threat_agent'])){
        if ($_POST['threat_agent'] == "yes"){
          $insertSQLAgent = sprintf("UPDATE tbskills_aquired SET spendxp_sold = %s, spendxp_sold_progress_id = %s WHERE spendxp_game_id = %s AND spendxp_char_id = %s AND spendxp_id = %s",
           GetSQLValueString(1, "int"),
           GetSQLValueString($pID, "int"),
           GetSQLValueString($gameID, "int"),
           GetSQLValueString($oID, "int"),
           GetSQLValueString($_POST['threat_agent_id'], "int"));


          $ResultAgent = mysql_query($insertSQLAgent, $dbDescent) or die(mysql_error());
        }
      }

      if(isset($_POST['threat_deal'])){
        if ($_POST['threat_deal'] == "yes"){
          $val_totalThreat += 1;
          $val_questGold += 25;
          if($_POST['progress_quest_winner'] == "Overlord Wins"){
            $val_totalThreat += 1;
          }
        }
      }

      // -- Rewards -- //

      

      if($_POST['progress_quest_winner'] == "Heroes Win"){
        foreach($_SESSION['rewards_heroes'] as $rh){
          switch ($rh[0]) {
            case "xp":
              $val_xpHeroes += $rh[1];
              break;
            case "goldhero":
              $val_questGold = ($rh[1] * (count($players) - 1)); // -1 for overlord
              break;
            case "goldall":
              $val_questGold = $rh[1];
              break;
            case "serena":
              $val_allySkill = $rh[1];
              $val_allyId = "1";
              break;
            case "raythen":
              $val_allySkill = $rh[1];
              $val_allyId = "2";
              break;

          }
        }
      }
    
      if($_POST['progress_quest_winner'] == "Overlord Wins"){
        $val_totalThreat += 1;
        if(in_array($_SESSION['validate']['expID'], $miniCampaigns)){
          $val_xpOverlord += 1;
        }
        foreach($_SESSION['rewards_overlord'] as $ro){
          switch ($ro[0]) {
            case "xp":
              $val_xpOverlord += $ro[1];
              break;
          }
        }
      }

     
      //independent of winner

      include 'campaign_overview_save_validate_special.php';

      $val_totalGold = $val_searchGold + $val_questGold;

      $insertSQL3 = sprintf("UPDATE tbgames SET game_gold = game_gold + %s WHERE game_id = %s",
                             GetSQLValueString($val_totalGold, "int"),
                             GetSQLValueString($gameID, "int"));

      $insertSQL3b = sprintf("UPDATE tbquests_progress SET progress_gold_gained = %s WHERE progress_quest_id = %s AND progress_game_id = %s",
                             GetSQLValueString($val_totalGold, "int"),
                             GetSQLValueString($qID, "int"),
                             GetSQLValueString($gameID, "int"));


      $insertSQLThrt = sprintf("UPDATE tbgames SET game_threat = game_threat + %s WHERE game_id = %s",
                             GetSQLValueString($val_totalThreat, "int"),
                             GetSQLValueString($gameID, "int"));

      $insertSQLThrtb = sprintf("UPDATE tbquests_progress SET progress_threat_gained = %s WHERE progress_quest_id = %s AND progress_game_id = %s",
                             GetSQLValueString($val_totalThreat, "int"),
                             GetSQLValueString($qID, "int"),
                             GetSQLValueString($gameID, "int"));


      $insertSQLH = sprintf("UPDATE tbcharacters SET char_xp = char_xp + %s WHERE char_game_id = %s AND char_id != %s", 
                             GetSQLValueString($val_xpHeroes, "int"),
                             GetSQLValueString($gameID, "int"),
                             GetSQLValueString($oID, "int"));

      $insertSQLOl = sprintf("UPDATE tbcharacters SET char_xp = char_xp + %s WHERE char_game_id = %s AND char_id = %s",
                             GetSQLValueString($val_xpOverlord, "int"),
                             GetSQLValueString($gameID, "int"),
                             GetSQLValueString($oID, "int"));

      if($val_allySkill != NULL){        
        $insertSQLAlly = sprintf("INSERT INTO tbskills_aquired (spendxp_game_id, spendxp_char_id, spendxp_skill_id, spendxp_progress_id) VALUES (%s, %s, %s, %s)",
                   GetSQLValueString($gameID, "int"),
                   GetSQLValueString($val_allyId, "int"),
                   GetSQLValueString($val_allySkill, "int"),
                   GetSQLValueString($pID, "int"));
        
        mysql_select_db($database_dbDescent, $dbDescent);
        $ResultAlly = mysql_query($insertSQLAlly, $dbDescent) or die(mysql_error());
      }

      
      $Result1 = mysql_query($insertSQL, $dbDescent) or die(mysql_error());

      if (isset($_POST['progress_relic_recipiant'])){
        if($awardxp == 0 && $relicSteal == 0){
          $Result2 = mysql_query($insertSQL2, $dbDescent) or die(mysql_error());
        }

        if($relicSteal == 1){
          $ResultSteal = mysql_query($insertSQLSteal, $dbDescent) or die(mysql_error());
          $ResultSteal2 = mysql_query($insertSQLSteal2, $dbDescent) or die(mysql_error());
        }
      }

      if ($_POST['progress_quest_winner'] == "Heroes Win" && isset($_POST['random_item']) && $_POST['random_item'] != "empty"){
        $ResultRandomShop = mysql_query($insertSQLRandomShop, $dbDescent) or die(mysql_error());
      }

      if (isset($_POST['search_item']) && $_POST['search_item'] != "empty"){
        $ResultSearch = mysql_query($insertSQLSearch, $dbDescent) or die(mysql_error());
      }

      if (isset($_POST['secretroom_item']) && $_POST['secretroom_item'] != "empty"){
        $ResultSecretRoom = mysql_query($insertSQLSecretRoom, $dbDescent) or die(mysql_error());
      }

      $Result3 = mysql_query($insertSQL3, $dbDescent) or die(mysql_error());
      $ResultThrt = mysql_query($insertSQLThrt, $dbDescent) or die(mysql_error());

      $Result3b = mysql_query($insertSQL3b, $dbDescent) or die(mysql_error());
      $ResultThrtb = mysql_query($insertSQLThrtb, $dbDescent) or die(mysql_error());

      $ResultH = mysql_query($insertSQLH, $dbDescent) or die(mysql_error());
      $ResultOl = mysql_query($insertSQLOl, $dbDescent) or die(mysql_error());

      $_SESSION['old_post'] = array();
      $insertGoTo = "campaign_overview.php?urlGamingID=" . $gameID_obscured;
      header(sprintf("Location: %s", $insertGoTo));
      die("Redirecting to campaign_overview.php"); 
  } else {
    if ($UniqueS == 0){
      $_SESSION["errorcode"][] = "The selection contains duplicate monsters.";
    }
    if ($RandomItem == 0){
      $_SESSION["errorcode"][] = "No Random Shop item selected.";
    }
    if ($SearchItem == 0){
      $_SESSION["errorcode"][] = "No Treasure Chest item selected.";
    }
    if ($SecretRoomItem == 0){
      $_SESSION["errorcode"][] = "No Secret Room item selected.";
    }
    if ($duplicateItem == 0){
      $_SESSION["errorcode"][] = "One or more item fields (Treasure Chest, Secret Room,..) contained the same item.";
    }

    $insertGoTo = "campaign_overview_save.php?urlGamingID=" . $gameID_obscured . "&part=q";
    header(sprintf("Location: %s", $insertGoTo));
    die("Redirecting to campaign_overview_save.php"); 
    
  }
}






if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "spendxp-details-form")) {
  $valid = 1;
  $totalCostPlayer = array();
  $_SESSION["errorcode"] = array();
  $_SESSION["old_post"] = $_POST;

  foreach ($players as $sh){
    $neededXP = 0;
    $neededThreat = 0;
    if (array_key_exists($sh['id'], $_POST)){
      foreach ($_POST[$sh['id']] as $skp){        
        if ($skp == 9999){
          $neededXP += 1;
        } else {
          $query_rsSkillCost = sprintf("SELECT skill_cost FROM tbskills WHERE skill_id = %s", GetSQLValueString($skp, "int"));
          $rsSkillCost = mysql_query($query_rsSkillCost, $dbDescent) or die(mysql_error());
          $row_rsSkillCost = mysql_fetch_assoc($rsSkillCost);
          $neededXP += $row_rsSkillCost['skill_cost'];
        }
      }

      foreach ($_POST[$sh['id']] as $skp){      
        if ($skp == 9999){
          $neededThreat += 1;
        } else {
          $query_rsSkillCost = sprintf("SELECT skill_cost FROM tbskills WHERE skill_id = %s", GetSQLValueString($skp, "int"));
          $rsSkillCost = mysql_query($query_rsSkillCost, $dbDescent) or die(mysql_error());
          $row_rsSkillCost = mysql_fetch_assoc($rsSkillCost);
          $neededThreat += $row_rsSkillCost['skill_cost'];
        }
      }

      $totalCostPlayer[$sh['id']] = $neededXP;
      $totalThreatPlayer[$sh['id']] = $neededThreat;

      if($totalCostPlayer[$sh['id']] > $sh['xp']){    
        $_SESSION["errorcode"][] = ' <strong>' . $sh['player'] . '</strong> tried to spend <strong>' . $totalCostPlayer[$sh['id']] . 'XP</strong>, but <strong>' . $sh['name'] . '</strong> has only <strong>' . $sh['xp'] . 'XP</strong>.';
        $valid = 0;
      }
    }

  } //close foreach
  if($valid == 1){
    foreach ($players as $sh){
      $neededXP = 0;
      if (array_key_exists($sh['id'], $_POST)){
        foreach ($_POST[$sh['id']] as $skp){
          if ($skp == 9999){
            $insertSQLThrt = sprintf("UPDATE tbgames SET game_threat = game_threat + %s WHERE game_id = %s",
                         GetSQLValueString(3, "int"),
                         GetSQLValueString($gameID, "int"));
            
            mysql_select_db($database_dbDescent, $dbDescent);
            $ResultThrt = mysql_query($insertSQLThrt, $dbDescent) or die(mysql_error());

          } else {
            $insertSQL = sprintf("INSERT INTO tbskills_aquired (spendxp_game_id, spendxp_char_id, spendxp_skill_id, spendxp_progress_id) VALUES (%s, %s, %s, %s)",
                          GetSQLValueString($gameID, "int"),
                          GetSQLValueString($sh['id'], "int"),
                          GetSQLValueString($skp, "int"),
                          GetSQLValueString($pID, "int"));

            mysql_select_db($database_dbDescent, $dbDescent);
            $Result1 = mysql_query($insertSQL, $dbDescent) or die(mysql_error());
          }
        }

        $insertSQL2 = sprintf("UPDATE tbcharacters SET char_xp = char_xp - %s WHERE char_id = %s",
                          GetSQLValueString($totalCostPlayer[$sh['id']], "int"),
                          GetSQLValueString($sh['id'], "int"));

        mysql_select_db($database_dbDescent, $dbDescent);
        $Result2 = mysql_query($insertSQL2, $dbDescent) or die(mysql_error());
    
      }

      if (array_key_exists($sh['id'] . "-Plot", $_POST)){
        foreach ($_POST[$sh['id'] . "-Plot"] as $skp){
            $insertSQL = sprintf("INSERT INTO tbskills_aquired (spendxp_game_id, spendxp_char_id, spendxp_skill_id, spendxp_progress_id) VALUES (%s, %s, %s, %s)",
                          GetSQLValueString($gameID, "int"),
                          GetSQLValueString($sh['id'], "int"),
                          GetSQLValueString($skp, "int"),
                          GetSQLValueString($pID, "int"));

            mysql_select_db($database_dbDescent, $dbDescent);
            $Result1 = mysql_query($insertSQL, $dbDescent) or die(mysql_error());
        }

        $insertSQL4 = sprintf("UPDATE tbgames SET game_threat = game_threat - %s WHERE game_id = %s",
                          GetSQLValueString($totalThreatPlayer[$sh['id']], "int"),
                          GetSQLValueString($gameID, "int"));

        mysql_select_db($database_dbDescent, $dbDescent);
        $Result4 = mysql_query($insertSQL4, $dbDescent) or die(mysql_error());
    
      }
      
    } //close foreach

    $insertSQL3 = sprintf("UPDATE tbquests_progress SET progress_set_spendxp = 1 WHERE progress_id = %s", 
                       GetSQLValueString($pID, "int"));
    $Result3 = mysql_query($insertSQL3, $dbDescent) or die(mysql_error());

    if ($_POST['progress_plot'] == 0){
      $_SESSION["errorcode"] = array();
      $insertGoTo = "campaign_overview.php?urlGamingID=" . $gameID_obscured;
      header(sprintf("Location: %s", $insertGoTo));
      die("Redirecting to campaign_overview.php"); 
    } else {
      $_SESSION["errorcode"] = array();
      $insertGoTo = "campaign_overview_save.php?urlGamingID=" . $gameID_obscured . "&part=xp&page=plot";
      header(sprintf("Location: %s", $insertGoTo));
      die("Redirecting to campaign_overview_save.php"); 
    }
  } else {
    $insertGoTo = "campaign_overview_save.php?urlGamingID=" . $gameID_obscured . "&part=xp&page=skills";
    header(sprintf("Location: %s", $insertGoTo));
    die("Redirecting to campaign_overview_save.php"); 
  }

}







if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "spendthreat-details-form")) {
  $valid = 1;
  $totalCostPlayer = array();
  $_SESSION["errorcode"] = array();
  $_SESSION["old_post"] = $_POST;

  foreach ($players as $sh){
    $neededThreat = 0;
    if (array_key_exists($sh['id'], $_POST)){
      foreach ($_POST[$sh['id']] as $skp){
          $query_rsSkillCost = sprintf("SELECT skill_cost FROM tbskills WHERE skill_id = %s", GetSQLValueString($skp, "int"));
          $rsSkillCost = mysql_query($query_rsSkillCost, $dbDescent) or die(mysql_error());
          $row_rsSkillCost = mysql_fetch_assoc($rsSkillCost);
          $neededThreat += $row_rsSkillCost['skill_cost'];
      }

      $totalThreatPlayer[$sh['id']] = $neededThreat;

      if($totalThreatPlayer[$sh['id']] > $_SESSION['campaign']['threat']){
        $_SESSION["errorcode"][] = ' <strong>' . $sh['player'] . '</strong> tried to spend <strong>' . $totalThreatPlayer[$sh['id']] . ' Threat tokens</strong>, but the <strong>' . $sh['name'] . '</strong> has only <strong>' . $_SESSION['campaign']['threat'] . ' Threat tokens</strong>.';
        $valid = 0;
      }
    }

  } //close foreach
  if($valid == 1){
    foreach ($players as $sh){
      if (array_key_exists($sh['id'], $_POST)){
        foreach ($_POST[$sh['id']] as $skp){
          $insertSQL = sprintf("INSERT INTO tbskills_aquired (spendxp_game_id, spendxp_char_id, spendxp_skill_id, spendxp_progress_id) VALUES (%s, %s, %s, %s)",
                        GetSQLValueString($gameID, "int"),
                        GetSQLValueString($sh['id'], "int"),
                        GetSQLValueString($skp, "int"),
                        GetSQLValueString($pID, "int"));

          mysql_select_db($database_dbDescent, $dbDescent);
          $Result1 = mysql_query($insertSQL, $dbDescent) or die(mysql_error());
        }

        $insertSQL4 = sprintf("UPDATE tbgames SET game_threat = game_threat - %s WHERE game_id = %s",
                          GetSQLValueString($totalThreatPlayer[$sh['id']], "int"),
                          GetSQLValueString($gameID, "int"));

        mysql_select_db($database_dbDescent, $dbDescent);
        $Result4 = mysql_query($insertSQL4, $dbDescent) or die(mysql_error());
    
      }
      

    } //close foreach

    $_SESSION["errorcode"] = array();
    $insertGoTo = "campaign_overview.php?urlGamingID=" . $gameID_obscured;
    header(sprintf("Location: %s", $insertGoTo));
    die("Redirecting to campaign_overview.php"); 
  } else {
    $insertGoTo = "campaign_overview_save.php?urlGamingID=" . $gameID_obscured . "&part=xp&page=plot";
    header(sprintf("Location: %s", $insertGoTo));
    die("Redirecting to campaign_overview_save.php"); 
  }

}








if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "buy-details-form")) {

  if (!isset($_POST["bought_item"]) || !in_array($_POST['bought_item'], $_SESSION['verify_values']['items_available'])){
    $_SESSION["errorcode"][] = "Illegal value(s) submitted for 'Bought Item' selection.";
    $noError = 0; 
  }

  if (!isset($_POST['bought_player']) || !in_array($_POST['bought_player'], $_SESSION['verify_values']['heroes'])){
    $_SESSION["errorcode"][] = "Illegal value(s) submitted for 'Bought Item Player' selection.";
    $noError = 0; 
  }

  if($noError == 0){
    $insertGoTo = "campaign_overview_save.php?urlGamingID=" . $gameID_obscured . "&part=it";
    header(sprintf("Location: %s", $insertGoTo));
    die("Redirecting to campaign_overview.php"); 
  }


  $query_rsGetItem = sprintf("SELECT * FROM tbitems WHERE item_id = %s", GetSQLValueString($_POST["bought_item"], "int"));
  $rsGetItem = mysql_query($query_rsGetItem, $dbDescent) or die(mysql_error());
  $row_rsGetItem = mysql_fetch_assoc($rsGetItem);

  $overridePrice = NULL;
  if(isset($_POST["bought_override"]) && $_POST["bought_override"] != 999){
    $overridePrice = $_POST["bought_override"];
  }

  $discount = 0;
  if(isset($_POST["bought_discount"])){
    $discount = 25;
  }

  if ($overridePrice == NULL && $discount != 0){
    $overridePrice = $row_rsGetItem['item_default_price'] - $discount;
  } else if ($overridePrice != NULL && $discount != 0){
    $overridePrice = $overridePrice - $discount;
  }


  $temp = $_SESSION["shopItems"];

  // FIX ME: this could be done in a different way probably (without the foreach player)
  foreach ($players as $pi){
    if ($pi['id'] == $_POST["bought_player"]){
      $temp[] = array(
        "action" => "buy",
        "id" => $_POST["bought_item"],
        "name" => $row_rsGetItem['item_name'],
        "player" => $_POST["bought_player"],
        "hero" => $pi['name'],
        "player2" => "",
        "hero2" => "",
        "price" => $row_rsGetItem['item_default_price'],  
        "override" => $overridePrice,
      );
    }
  }
}







if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "sell-details-form")) {

  if (!isset($_POST["sold_item"]) || !in_array($_POST['sold_item'], $_SESSION['verify_values']['items_sellable'])){
    $_SESSION["errorcode"][] = "Illegal value(s) submitted for 'Traded Item' selection.";
    $noError = 0; 
  }

  if($noError == 0){
    $insertGoTo = "campaign_overview_save.php?urlGamingID=" . $gameID_obscured . "&part=it";
    header(sprintf("Location: %s", $insertGoTo));
    die("Redirecting to campaign_overview.php"); 
  }


  $query_rsGetItem = sprintf("SELECT * FROM tbitems_aquired 
    INNER JOIN tbitems ON aq_item_id = item_id 
    INNER JOIN tbcharacters ON aq_char_id = char_id 
    INNER JOIN tbheroes ON char_hero = hero_id 
    WHERE aq_item_id = %s AND aq_game_id = %s AND aq_trade_char_id is null", GetSQLValueString($_POST["sold_item"], "int"), GetSQLValueString($gameID, "int"));
  $rsGetItem = mysql_query($query_rsGetItem, $dbDescent) or die(mysql_error());
  $row_rsGetItem = mysql_fetch_assoc($rsGetItem);

  $temp = $_SESSION["shopItems"];

  $temp[] = array(
    "action" => "sell",
    "id" => $_POST["sold_item"],
    "name" => $row_rsGetItem['item_name'],
    "player" => $row_rsGetItem['char_id'],
    "hero" => $row_rsGetItem['hero_name'],
    "player2" => "",
    "hero2" => "",
    "price" => $row_rsGetItem['item_sell_price'], 
    "override" => NULL,
  );
}











if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "trade-details-form")) {

  if (!isset($_POST["traded_item"]) || !in_array($_POST['traded_item'], $_SESSION['verify_values']['items_tradable'])){
    $_SESSION["errorcode"][] = "Illegal value(s) submitted for 'Traded Item' selection.";
    $noError = 0; 
  }

  if (!isset($_POST['traded_player']) || !in_array($_POST['traded_player'], $_SESSION['verify_values']['heroes'])){
    $_SESSION["errorcode"][] = "Illegal value(s) submitted for 'Bought Item Player' selection.";
    $noError = 0; 
  }

  if($noError == 0){
    $insertGoTo = "campaign_overview_save.php?urlGamingID=" . $gameID_obscured . "&part=it";
    header(sprintf("Location: %s", $insertGoTo));
    die("Redirecting to campaign_overview.php"); 
  }


  $query_rsGetTrade = sprintf("SELECT * FROM tbitems_aquired  
    INNER JOIN tbcharacters ON aq_char_id = char_id 
    INNER JOIN tbheroes ON char_hero = hero_id 
    WHERE shop_id = %s AND aq_game_id = %s AND aq_trade_char_id is null", GetSQLValueString($_POST["traded_item"], "int"), GetSQLValueString($gameID, "int"));
  $rsGetTrade = mysql_query($query_rsGetTrade, $dbDescent) or die(mysql_error());
  $row_rsGetTrade = mysql_fetch_assoc($rsGetTrade);

  if ($row_rsGetTrade['aq_item_id'] != NULL){
    $query_rsGetItem = sprintf("SELECT * FROM tbitems 
      WHERE item_id = %s", GetSQLValueString($row_rsGetTrade['aq_item_id'], "int"), GetSQLValueString($gameID, "int"));
    $rsGetItem = mysql_query($query_rsGetItem, $dbDescent) or die(mysql_error());
    $row_rsGetItem = mysql_fetch_assoc($rsGetItem);
  } else {
    $query_rsGetRelic = sprintf("SELECT * FROM tbitems_relics 
      WHERE relic_id = %s", GetSQLValueString($row_rsGetTrade['aq_relic_id'], "int"), GetSQLValueString($gameID, "int"));
    $rsGetRelic = mysql_query($query_rsGetRelic, $dbDescent) or die(mysql_error());
    $row_rsGetRelic = mysql_fetch_assoc($rsGetRelic);
  }

  
  $query_rsGetHero = sprintf("SELECT * FROM tbcharacters INNER JOIN tbheroes ON char_hero = hero_id WHERE char_id = %s", GetSQLValueString($_POST["traded_player"], "int"));
  $rsGetHero = mysql_query($query_rsGetHero, $dbDescent) or die(mysql_error());
  $row_rsGetHero = mysql_fetch_assoc($rsGetHero);

  $temp = $_SESSION["shopItems"];


  if ($row_rsGetTrade['aq_item_id'] != NULL){
    $temp[] = array(
      "action" => "trade",
      "id" => $row_rsGetItem['item_id'],
      "shop_id" => $_POST["traded_item"],
      "type" => "item",
      "name" => $row_rsGetItem['item_name'],
      "player" => $row_rsGetTrade['char_id'],
      "hero" => $row_rsGetTrade['hero_name'],
      "player2" => $_POST["traded_player"],
      "hero2" => $row_rsGetHero['hero_name'],
      "price" => 0, 
      "override" => $row_rsGetTrade['aq_item_price_ovrd'],
    );
  } else {
    $temp[] = array(
      "action" => "trade",
      "id" => $row_rsGetRelic['relic_id'],
      "shop_id" => $_POST["traded_item"],
      "type" => "relic",
      "name" => $row_rsGetRelic['relic_h_name'],
      "player" => $row_rsGetTrade['char_id'],
      "hero" => $row_rsGetTrade['hero_name'],
      "player2" => $_POST["traded_player"],
      "hero2" => $row_rsGetHero['hero_name'],
      "price" => 0, 
      "override" => NULL,
    );
  }
}




if (isset($_POST["MM_insert"]) && ($_POST["MM_insert"] == "trade-details-form" || ($_POST["MM_insert"] == "buy-details-form") || ($_POST["MM_insert"] == "sell-details-form") || $_POST["MM_insert"] == "item-details-form")){
  $samePlayer = 0;
  $selectionPrice = 0;
  $availableGold = $_SESSION['validate']['gold'];

  if ($_POST["MM_insert"] == "item-details-form"){
    $temp = $_SESSION["shopItems"];
  }

  foreach ($temp as $tmp){
    if ($tmp['action'] == "buy"){
      if ($tmp["override"] != NULL){
        $selectionPrice += $tmp["override"];
        $lastprice = $tmp["price"];
      } else {
        $selectionPrice += $tmp["price"];
        $lastprice = $tmp["price"];
      }   
    } else if ($tmp['action'] == "sell"){
      $selectionPrice -= $tmp["price"];
    }

    if ($tmp['action'] == "trade"){
      if ($tmp["player"] == $tmp["player2"]){
        $samePlayer = 1;
      }
    }
  }

  //$_SESSION["tempItems"][] = $selectionPrice . " > " . $availableGold;
  if ($selectionPrice > $availableGold){
    $_SESSION["errorcode"][] = 'The selected item costed <strong>' . (($availableGold - $selectionPrice) * -1) . ' more gold</strong> than is available.';
    $selectionPrice -= $lastprice;
  } else {
    if ($samePlayer == 0){
      $_SESSION["shopItems"] = $temp;
      $_SESSION["errorcode"] = array();
      $validItems = 1;
    } else {
      $_SESSION["errorcode"][] = "You can't trade an item with the hero the item already belongs to.";
    }
  }

  if ($_POST["MM_insert"] != "item-details-form"){
    $insertGoTo = "campaign_overview_save.php?urlGamingID=" . $gameID_obscured . "&part=it&data=y";
    header(sprintf("Location: %s", $insertGoTo));
    die("Redirecting to campaign_overview_save.php"); 
  }

}



// Save items to the database
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "item-details-form")) {
  if ($validItems == 1){
    foreach ($_SESSION["shopItems"] as $siv){
      if ($siv["action"] == "buy"){
        $insertSQL = sprintf("INSERT INTO tbitems_aquired (aq_game_id, aq_char_id, aq_item_id, aq_item_price_ovrd, aq_progress_id) VALUES (%s, %s, %s, %s, %s)",
                        GetSQLValueString($gameID, "int"),
                        GetSQLValueString($siv['player'], "int"),
                        GetSQLValueString($siv['id'], "int"),
                        GetSQLValueString($siv['override'], "int"),
                        GetSQLValueString($pID, "int"));

        mysql_select_db($database_dbDescent, $dbDescent);
        $Result1 = mysql_query($insertSQL, $dbDescent) or die(mysql_error());

      } else if ($siv["action"] == "sell"){
        mysql_select_db($database_dbDescent, $dbDescent);
        $insertSQL2 = sprintf("UPDATE tbitems_aquired SET aq_item_sold = 1, aq_sold_progress_id = %s WHERE aq_game_id = %s AND aq_item_id = %s AND aq_trade_char_id is null",
                      GetSQLValueString($pID, "int"),
                      GetSQLValueString($gameID, "int"),
                      GetSQLValueString($siv['id'], "int"));
        $Result2 = mysql_query($insertSQL2, $dbDescent) or die(mysql_error());

      } else if ($siv["action"] == "trade"){
        mysql_select_db($database_dbDescent, $dbDescent);

        $insertSQL5 = sprintf("UPDATE tbitems_aquired SET aq_trade_char_id = %s, aq_trade_progress_id = %s WHERE aq_game_id = %s AND shop_id = %s AND aq_trade_char_id is null",
                        GetSQLValueString($siv['player2'], "int"),
                        GetSQLValueString($pID, "int"),
                        GetSQLValueString($gameID, "int"),
                        GetSQLValueString($siv['shop_id'], "int"));
        $Result5 = mysql_query($insertSQL5, $dbDescent) or die(mysql_error());

        if ($siv["type"] == "item"){
          $insertSQL6 = sprintf("INSERT INTO tbitems_aquired (aq_game_id, aq_char_id, aq_item_id, aq_item_price_ovrd, aq_item_gottraded, aq_progress_id) VALUES (%s, %s, %s, %s, %s, %s)",
                          GetSQLValueString($gameID, "int"),
                          GetSQLValueString($siv['player2'], "int"),
                          GetSQLValueString($siv['id'], "int"),
                          GetSQLValueString($siv['override'], "int"),
                          GetSQLValueString(1, "int"),
                          GetSQLValueString($pID, "int"));
          $Result6 = mysql_query($insertSQL6, $dbDescent) or die(mysql_error());
        } else {
          $insertSQL6 = sprintf("INSERT INTO tbitems_aquired (aq_game_id, aq_char_id, aq_relic_id, aq_item_gottraded, aq_progress_id) VALUES (%s, %s, %s, %s, %s)",
                          GetSQLValueString($gameID, "int"),
                          GetSQLValueString($siv['player2'], "int"),
                          GetSQLValueString($siv['id'], "int"),
                          GetSQLValueString(1, "int"),
                          GetSQLValueString($pID, "int"));

          $Result6 = mysql_query($insertSQL6, $dbDescent) or die(mysql_error());
        }
        

      }
    }
    mysql_select_db($database_dbDescent, $dbDescent);
    $insertSQL3 = sprintf("UPDATE tbgames SET game_gold = game_gold - %s WHERE game_id = %s",
                      GetSQLValueString($selectionPrice, "int"),
                      GetSQLValueString($gameID, "int"));
    $Result3 = mysql_query($insertSQL3, $dbDescent) or die(mysql_error());

    $insertSQL4 = sprintf("UPDATE tbquests_progress SET progress_set_items = 1, progress_gold_spent = progress_gold_spent + %s WHERE progress_id = %s", 
                      GetSQLValueString($selectionPrice, "int"),
                      GetSQLValueString($pID, "int"));
    $Result4 = mysql_query($insertSQL4, $dbDescent) or die(mysql_error());

    $insertGoTo = "campaign_overview.php?urlGamingID=" . $gameID_obscured;
    header(sprintf("Location: %s", $insertGoTo));
    die("Redirecting to campaign_overview.php"); 
  }
}





// --------------------- //
// -- QUEST EDIT FORM -- //
// --------------------- //

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "quest-edit-form")) {

  mysql_select_db($database_dbDescent, $dbDescent);

  if (isset($_POST['undo_skills'])){
    foreach ($_POST['undo_skills'] as $usk){

      $query_rsSkillsUndoDB = sprintf("SELECT * FROM tbskills_aquired INNER JOIN tbskills ON spendxp_skill_id = skill_id WHERE spendxp_progress_id = %s AND spendxp_id = %s",
                    GetSQLValueString($pID, "int"),
                    GetSQLValueString($usk, "int"));
      $rsSkillsUndoDB = mysql_query($query_rsSkillsUndoDB, $dbDescent) or die(mysql_error());
      $row_rsSkillsUndoDB = mysql_fetch_assoc($rsSkillsUndoDB);
      $totalRows_rsSkillsUndoDB = mysql_num_rows($rsSkillsUndoDB);

      $insertSQLUXP = sprintf("DELETE FROM tbskills_aquired WHERE spendxp_game_id = %s AND spendxp_id = %s",
                          GetSQLValueString($gameID, "int"),
                          GetSQLValueString($usk, "int"));
    
      $ResultUXP = mysql_query($insertSQLUXP, $dbDescent) or die(mysql_error());

      $insertSQLCHXP = sprintf("UPDATE tbcharacters SET char_xp = char_xp + %s WHERE char_game_id = %s AND char_id = %s",
                          GetSQLValueString($row_rsSkillsUndoDB['skill_cost'], "int"),
                          GetSQLValueString($gameID, "int"),
                          GetSQLValueString($row_rsSkillsUndoDB['spendxp_char_id'], "int"));
    
      $ResultCHXP = mysql_query($insertSQLCHXP, $dbDescent) or die(mysql_error());

    } 

  }

  if (isset($_POST['undo_plot'])){
    foreach ($_POST['undo_plot'] as $usk){

      $query_rsPlotUndoDB = sprintf("SELECT * FROM tbskills_aquired INNER JOIN tbskills ON spendxp_skill_id = skill_id WHERE spendxp_progress_id = %s AND spendxp_id = %s",
                    GetSQLValueString($pID, "int"),
                    GetSQLValueString($usk, "int"));
      $rsPlotUndoDB = mysql_query($query_rsPlotUndoDB, $dbDescent) or die(mysql_error());
      $row_rsPlotUndoDB = mysql_fetch_assoc($rsPlotUndoDB);

      $insertSQLUThrt = sprintf("DELETE FROM tbskills_aquired WHERE spendxp_game_id = %s AND spendxp_id = %s",
                          GetSQLValueString($gameID, "int"),
                          GetSQLValueString($usk, "int"));
    
      $ResultUThrt = mysql_query($insertSQLUThrt, $dbDescent) or die(mysql_error());

      $insertSQLCHThrt = sprintf("UPDATE tbgames SET game_threat = game_threat + %s WHERE game_id = %s",
                          GetSQLValueString($row_rsPlotUndoDB['skill_cost'], "int"),
                          GetSQLValueString($gameID, "int"),
                          GetSQLValueString($row_rsPlotUndoDB['spendxp_char_id'], "int"));
    
      $ResultCHThrt = mysql_query($insertSQLCHThrt, $dbDescent) or die(mysql_error());

    } 

  }

  $returnGold = 0;

  if (isset($_POST['undo_sold'])){
    foreach ($_POST['undo_sold'] as $us){

      $query_rsSoldUndoDB = sprintf("SELECT * FROM tbitems_aquired INNER JOIN tbitems ON aq_item_id = item_id WHERE aq_sold_progress_id = %s AND shop_id = %s",
                    GetSQLValueString($pID, "int"),
                    GetSQLValueString($us, "int"));
      $rsSoldUndoDB = mysql_query($query_rsSoldUndoDB, $dbDescent) or die(mysql_error());
      $row_rsSoldUndoDB = mysql_fetch_assoc($rsSoldUndoDB);
      $totalRows_rsSoldUndoDB = mysql_num_rows($rsSoldUndoDB);

      $returnGold -= $row_rsSoldUndoDB['item_sell_price'];

    }
  }

  if (isset($_POST['undo_bought'])){
    foreach ($_POST['undo_bought'] as $ub){

      $query_rsBoughtUndoDB = sprintf("SELECT * FROM tbitems_aquired INNER JOIN tbitems ON aq_item_id = item_id WHERE aq_progress_id = %s AND shop_id = %s",
                    GetSQLValueString($pID, "int"),
                    GetSQLValueString($ub, "int"));
      $rsBoughtUndoDB = mysql_query($query_rsBoughtUndoDB, $dbDescent) or die(mysql_error());
      $row_rsBoughtUndoDB = mysql_fetch_assoc($rsBoughtUndoDB);
      $totalRows_rsBoughtUndoDB = mysql_num_rows($rsBoughtUndoDB);

      if ($row_rsBoughtUndoDB['aq_item_price_ovrd'] != NULL){
        $returnGold += $row_rsBoughtUndoDB['aq_item_price_ovrd'];
      } else {
        $returnGold += $row_rsBoughtUndoDB['item_default_price'];
      }

    }
  }

  $query_rsGold = sprintf("SELECT * FROM tbgames WHERE game_id = %s", GetSQLValueString($gameID, "int"));
  $rsGold = mysql_query($query_rsGold, $dbDescent) or die(mysql_error());
  $row_rsGold = mysql_fetch_assoc($rsGold);

  $checkGold = 1;

  if (($row_rsGold['game_gold'] + $returnGold) < 0){
    $checkGold = 0;
    $_SESSION["errorcode"][] = "An undo of the selected items is impossible, it would cause a negative amount of gold for the heroes.";  
  }

  if (isset($_POST['undo_trade'])){
    foreach ($_POST['undo_trade'] as $ut){

      $query_rsTradeUndoDB = sprintf("SELECT * FROM tbitems_aquired INNER JOIN tbitems ON aq_item_id = item_id WHERE aq_trade_progress_id = %s AND shop_id = %s",
                    GetSQLValueString($pID, "int"),
                    GetSQLValueString($ut, "int"));
      $rsTradeUndoDB = mysql_query($query_rsTradeUndoDB, $dbDescent) or die(mysql_error());
      $row_rsTradeUndoDB = mysql_fetch_assoc($rsTradeUndoDB);
      $totalRows_rsTradeUndoDB = mysql_num_rows($rsTradeUndoDB);

      $insertSQLut = sprintf("UPDATE tbitems_aquired SET aq_trade_char_id = null, aq_trade_progress_id = null WHERE aq_game_id = %s AND shop_id = %s",
                           GetSQLValueString($gameID, "int"),
                           GetSQLValueString($ut, "int"));
    
      $Resultut = mysql_query($insertSQLut, $dbDescent) or die(mysql_error());

      $insertSQLutt = sprintf("DELETE FROM tbitems_aquired WHERE aq_game_id = %s AND aq_item_gottraded = %s AND aq_progress_id = %s AND aq_item_id = %s",
                          GetSQLValueString($gameID, "int"),
                          GetSQLValueString(1, "int"),
                          GetSQLValueString($pID, "int"),
                          GetSQLValueString($row_rsTradeUndoDB['item_id'], "int"));
    
      $Resultutt = mysql_query($insertSQLutt, $dbDescent) or die(mysql_error());

    }
  }

  if (isset($_POST['undo_sold']) && $checkGold == 1){
    foreach ($_POST['undo_sold'] as $us){

      $query_rsSoldUndoDB = sprintf("SELECT * FROM tbitems_aquired INNER JOIN tbitems ON aq_item_id = item_id WHERE aq_sold_progress_id = %s AND shop_id = %s",
                    GetSQLValueString($pID, "int"),
                    GetSQLValueString($us, "int"));
      $rsSoldUndoDB = mysql_query($query_rsSoldUndoDB, $dbDescent) or die(mysql_error());
      $row_rsSoldUndoDB = mysql_fetch_assoc($rsSoldUndoDB);
      $totalRows_rsSoldUndoDB = mysql_num_rows($rsSoldUndoDB);

      $insertSQLus = sprintf("UPDATE tbitems_aquired SET aq_item_sold = 0, aq_sold_progress_id = null WHERE aq_game_id = %s AND shop_id = %s",
                           GetSQLValueString($gameID, "int"),
                           GetSQLValueString($us, "int"));
    
      $Resultus = mysql_query($insertSQLus, $dbDescent) or die(mysql_error());

      $insertSQLusg = sprintf("UPDATE tbgames SET game_gold = game_gold - %s WHERE game_id = %s", 
        GetSQLValueString($row_rsSoldUndoDB['item_sell_price'], "int"),
        GetSQLValueString($gameID, "int"));
    
      $Resultusg = mysql_query($insertSQLusg, $dbDescent) or die(mysql_error());

    }

  }

  if (isset($_POST['undo_bought']) && $checkGold == 1){
    foreach ($_POST['undo_bought'] as $ub){

      $query_rsBoughtUndoDB = sprintf("SELECT * FROM tbitems_aquired INNER JOIN tbitems ON aq_item_id = item_id WHERE aq_progress_id = %s AND shop_id = %s",
                    GetSQLValueString($pID, "int"),
                    GetSQLValueString($ub, "int"));
      $rsBoughtUndoDB = mysql_query($query_rsBoughtUndoDB, $dbDescent) or die(mysql_error());
      $row_rsBoughtUndoDB = mysql_fetch_assoc($rsBoughtUndoDB);
      $totalRows_rsBoughtUndoDB = mysql_num_rows($rsBoughtUndoDB);

      $insertSQLub = sprintf("DELETE FROM tbitems_aquired WHERE aq_game_id = %s AND aq_progress_id = %s AND shop_id = %s",
                          GetSQLValueString($gameID, "int"),
                          GetSQLValueString($pID, "int"),
                          GetSQLValueString($ub, "int"));
    
      $Resultub = mysql_query($insertSQLub, $dbDescent) or die(mysql_error());

      $insertSQLubT = sprintf("DELETE FROM tbitems_aquired WHERE aq_game_id = %s AND aq_progress_id = %s AND aq_item_id = %s AND aq_item_gottraded = %s",
                          GetSQLValueString($gameID, "int"),
                          GetSQLValueString($pID, "int"),
                          GetSQLValueString($row_rsBoughtUndoDB['item_id'], "int"),
                          GetSQLValueString(1, "int"));
    
      $ResultubT = mysql_query($insertSQLubT, $dbDescent) or die(mysql_error());

      if ($row_rsBoughtUndoDB['aq_item_price_ovrd'] != NULL){
        $insertSQLubg = sprintf("UPDATE tbgames SET game_gold = game_gold + %s WHERE game_id = %s", 
          GetSQLValueString($row_rsBoughtUndoDB['aq_item_price_ovrd'], "int"),
          GetSQLValueString($gameID, "int"));
      } else {
        $insertSQLubg = sprintf("UPDATE tbgames SET game_gold = game_gold + %s WHERE game_id = %s",  
          GetSQLValueString($row_rsBoughtUndoDB['item_default_price'], "int"),
          GetSQLValueString($gameID, "int"));
      }

      $Resultubg = mysql_query($insertSQLubg, $dbDescent) or die(mysql_error());

    }
    
  }

  if (isset($_POST['undo_sold']) || isset($_POST['undo_bought']) || isset($_POST['undo_trade']) || isset($_POST['undo_skills']) || isset($_POST['undo_plot']) || isset($_POST['open_skills']) || isset($_POST['open_items'])){

    if ( (isset($_POST['undo_sold']) || isset($_POST['undo_bought'])) && $checkGold == 0){

    } else {

      if (isset($_POST['open_items'])){
        $insertSQLset2 = sprintf("UPDATE tbquests_progress SET progress_set_items = 2 WHERE progress_id = %s", 
        GetSQLValueString($pID, "int"));
      
        $Resultset2 = mysql_query($insertSQLset2, $dbDescent) or die(mysql_error());
      }
      if (isset($_POST['open_skills'])){
        $insertSQLset2 = sprintf("UPDATE tbquests_progress SET progress_set_spendxp = 2 WHERE progress_id = %s", 
        GetSQLValueString($pID, "int"));
      
        $Resultset2 = mysql_query($insertSQLset2, $dbDescent) or die(mysql_error());
      }
      
      $_SESSION["errorcode"] = array();

      $insertGoTo = "campaign_overview.php?urlGamingID=" . $gameID_obscured;
      header(sprintf("Location: %s", $insertGoTo));
      die("Redirecting to campaign_overview.php");

    }

  } 

}






// ----------------------- //
// -- QUEST DELETE FORM -- //
// ----------------------- //

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "quest-delete-form")) {

  $_SESSION["errorcode"] = array();

  mysql_select_db($database_dbDescent, $dbDescent);

  foreach ($_SESSION['delete_phase']['travel'] as $utr){
    $insertSQLUTR = sprintf("DELETE FROM tbtravel_aquired WHERE travel_aq_game_id = %s AND travel_aq_id = %s",
                        GetSQLValueString($gameID, "int"),
                        GetSQLValueString($utr, "int"));
  
    $ResultUTR = mysql_query($insertSQLUTR, $dbDescent) or die(mysql_error());
  } 

  foreach ($_SESSION['delete_phase']['spendxp'] as $uxp){
    $insertSQLUXP = sprintf("DELETE FROM tbskills_aquired WHERE spendxp_game_id = %s AND spendxp_id = %s",
                        GetSQLValueString($gameID, "int"),
                        GetSQLValueString($uxp['id'], "int"));
  
    $ResultUXP = mysql_query($insertSQLUXP, $dbDescent) or die(mysql_error());

    $insertSQLCHXP = sprintf("UPDATE tbcharacters SET char_xp = char_xp + %s WHERE char_game_id = %s AND char_id = %s",
                        GetSQLValueString($uxp['xp'], "int"),
                        GetSQLValueString($gameID, "int"),
                        GetSQLValueString($uxp['char'], "int"));
  
    $ResultCHXP = mysql_query($insertSQLCHXP, $dbDescent) or die(mysql_error());

  }

  foreach ($_SESSION['delete_phase']['spendxpSold'] as $uxps){
    $insertSQLUXPs = sprintf("UPDATE tbskills_aquired SET spendxp_sold_progress_id = %s, spendxp_sold = %s WHERE spendxp_game_id = %s AND spendxp_id = %s",
                        GetSQLValueString(NULL, "int"),
                        GetSQLValueString(0, "int"),
                        GetSQLValueString($gameID, "int"),
                        GetSQLValueString($uxps['id'], "int"));
  
    $ResultUXPs = mysql_query($insertSQLUXPs, $dbDescent) or die(mysql_error());

    $insertSQLCHXPs = sprintf("UPDATE tbcharacters SET char_xp = char_xp - %s WHERE char_game_id = %s AND char_id = %s",
                        GetSQLValueString($uxps['xp'], "int"),
                        GetSQLValueString($gameID, "int"),
                        GetSQLValueString($uxps['char'], "int"));
  
    $ResultCHXPs = mysql_query($insertSQLCHXPs, $dbDescent) or die(mysql_error());

  } 

  foreach ($_SESSION['delete_phase']['threat'] as $uth){
    $insertSQLUTH = sprintf("DELETE FROM tbskills_aquired WHERE spendxp_game_id = %s AND spendxp_id = %s",
                        GetSQLValueString($gameID, "int"),
                        GetSQLValueString($uth['id'], "int"));
  
    $ResultUTH = mysql_query($insertSQLUTH, $dbDescent) or die(mysql_error());

    $insertSQLGTH = sprintf("UPDATE tbgames SET game_threat = game_threat + %s WHERE game_id = %s",
                        GetSQLValueString($uth['xp'], "int"),
                        GetSQLValueString($gameID, "int"));
  
    $ResultGTH = mysql_query($insertSQLGTH, $dbDescent) or die(mysql_error());

  } 

  foreach ($_SESSION['delete_phase']['threatSold'] as $uths){
    $insertSQLUTHs = sprintf("UPDATE tbskills_aquired SET spendxp_sold = %s, spendxp_sold_progress_id = %s WHERE spendxp_game_id = %s AND spendxp_id = %s",
                        GetSQLValueString(0, "int"),
                        GetSQLValueString(NULL, "int"),
                        GetSQLValueString($gameID, "int"),
                        GetSQLValueString($uths['id'], "int"));
  
    $ResultUTHs = mysql_query($insertSQLUTHs, $dbDescent) or die(mysql_error());

  } 

  foreach ($_SESSION['delete_phase']['buy'] as $ub){
    $insertSQLUB = sprintf("DELETE FROM tbitems_aquired WHERE aq_game_id = %s AND shop_id = %s",
                        GetSQLValueString($gameID, "int"),
                        GetSQLValueString($ub, "int"));
  
    $ResultUB = mysql_query($insertSQLUB, $dbDescent) or die(mysql_error());
  } 

  foreach ($_SESSION['delete_phase']['sell'] as $us){
    $insertSQLUS = sprintf("UPDATE tbitems_aquired SET aq_item_sold = 0, aq_sold_progress_id = null WHERE aq_game_id = %s AND shop_id = %s",
                        GetSQLValueString($gameID, "int"),
                        GetSQLValueString($us, "int"));
  
    $ResultUS = mysql_query($insertSQLUS, $dbDescent) or die(mysql_error());
  } 

  foreach ($_SESSION['delete_phase']['trade'] as $ut){
    $insertSQLUT = sprintf("UPDATE tbitems_aquired SET aq_trade_char_id = null, aq_trade_progress_id = null WHERE aq_game_id = %s AND shop_id = %s",
                        GetSQLValueString($gameID, "int"),
                        GetSQLValueString($ut, "int"));
  
    $ResultUT = mysql_query($insertSQLUT, $dbDescent) or die(mysql_error());
  }

  $insertSQLRelics = sprintf("DELETE FROM tbitems_aquired WHERE aq_game_id = %s AND aq_progress_id = %s AND aq_relic_id is not null",
                        GetSQLValueString($gameID, "int"),
                        GetSQLValueString($pID, "int"));
  
  $ResultRelics = mysql_query($insertSQLRelics, $dbDescent) or die(mysql_error());


  // add reduction of gold from quest
  $insertSQLG = sprintf("UPDATE tbgames SET game_gold = game_gold + %s WHERE game_id = %s",
                        GetSQLValueString($_SESSION['delete_phase']['returnGold'], "int"),
                        GetSQLValueString($gameID, "int"));
  
  $ResultG = mysql_query($insertSQLG, $dbDescent) or die(mysql_error());

  $insertSQLTH = sprintf("UPDATE tbgames SET game_threat = game_threat + %s WHERE game_id = %s",
                        GetSQLValueString($_SESSION['delete_phase']['returnThreat'], "int"),
                        GetSQLValueString($gameID, "int"));
  
  $ResultTH = mysql_query($insertSQLTH, $dbDescent) or die(mysql_error());

  foreach ($_SESSION['delete_phase']['rumorsUpdate'] as $ruu){
    if ($ruu['rumor_resolved'] == 1){
      $insertSQLRum = sprintf("UPDATE tbrumors_played SET played_resolved = %s, played_resolved_progress_id = NULL WHERE played_id = %s AND played_game_id = %s",
                        GetSQLValueString(0, "int"),
                        GetSQLValueString($ruu['id'], "int"),
                        GetSQLValueString($gameID, "int"));
  
      $ResultTRum = mysql_query($insertSQLRum, $dbDescent) or die(mysql_error());
    } else 
    if ($ruu['rumor_resolved'] == 2){
      $insertSQLRum = sprintf("UPDATE tbrumors_played SET played_resolved = %s, played_updated_progress_id = NULL WHERE played_id = %s AND played_game_id = %s",
                        GetSQLValueString(0, "int"),
                        GetSQLValueString($ruu['id'], "int"),
                        GetSQLValueString($gameID, "int"));
  
      $ResultTRum = mysql_query($insertSQLRum, $dbDescent) or die(mysql_error());
    }
     else if ($ruu['rumor_resolved'] == 3){
      $insertSQLRum = sprintf("UPDATE tbrumors_played SET played_resolved = %s, played_resolved_progress_id = NULL WHERE played_id = %s AND played_game_id = %s",
                        GetSQLValueString(0, "int"),
                        GetSQLValueString($ruu['id'], "int"),
                        GetSQLValueString($gameID, "int"));
  
      $ResultTRum = mysql_query($insertSQLRum, $dbDescent) or die(mysql_error());
    }
    
  }


  foreach ($_SESSION['delete_phase']['rumorsDelete'] as $rud){
    $insertSQLRum = sprintf("DELETE FROM tbrumors_played WHERE played_id = %s AND played_game_id = %s",
                        GetSQLValueString($rud['id'], "int"),
                        GetSQLValueString($gameID, "int"));
  
    $ResultTRum = mysql_query($insertSQLRum, $dbDescent) or die(mysql_error());
  }

  foreach ($players as $h){
    if ($h['archetype'] == 'Overlord'){
      $insertSQLOLXP = sprintf("UPDATE tbcharacters SET char_xp = char_xp - %s WHERE char_game_id = %s AND char_id = %s",
                        GetSQLValueString($_SESSION['delete_phase']['returnXPOL'], "int"),
                        GetSQLValueString($gameID, "int"),
                        GetSQLValueString($h['id'], "int"));
  
      $ResultOLXP = mysql_query($insertSQLOLXP, $dbDescent) or die(mysql_error());
    } else {
      $insertSQLHXP = sprintf("UPDATE tbcharacters SET char_xp = char_xp - %s WHERE char_game_id = %s AND char_id = %s",
                        GetSQLValueString($_SESSION['delete_phase']['returnXPH'], "int"),
                        GetSQLValueString($gameID, "int"),
                        GetSQLValueString($h['id'], "int"));
  
      $ResultHXP = mysql_query($insertSQLHXP, $dbDescent) or die(mysql_error());
    }
  }

  $insertSQLUsable = sprintf("DELETE FROM tbmonsters_usable WHERE usable_game_id = %s AND usable_progress_id = %s",
                        GetSQLValueString($gameID, "int"),
                        GetSQLValueString($pID, "int"));
  
  $ResultSQLUsable = mysql_query($insertSQLUsable, $dbDescent) or die(mysql_error());


  $insertSQLremove = sprintf("DELETE FROM tbquests_progress WHERE progress_game_id = %s AND progress_id = %s",
                        GetSQLValueString($gameID, "int"),
                        GetSQLValueString($pID, "int"));
  
  $ResultSQLremove = mysql_query($insertSQLremove, $dbDescent) or die(mysql_error());


  $insertSQLAdvRew = sprintf("UPDATE tbgames SET game_rumor_rew_used = %s WHERE game_id = %s",
     GetSQLValueString($_SESSION['delete_phase']['rumorRewardsUsed'], "text"),
     GetSQLValueString($gameID, "int"));

  $ResultAdvRew = mysql_query($insertSQLAdvRew, $dbDescent) or die(mysql_error());





  $insertGoTo = "campaign_overview.php?urlGamingID=" . $gameID_obscured;
  header(sprintf("Location: %s", $insertGoTo));
  die("Redirecting to campaign_overview.php"); 

}

?>






