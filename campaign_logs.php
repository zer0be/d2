<?php

// Make an array with completed quests/rumors for options
$questsCompleted = array();
$rumorsCompleted = array();
$rumorsCompletedAct2 = array();
$rumorsExpansion = array();



// Which quest was by the heroes in act 1 and act 2
$questsWonByHeroesAct1 = array();
$questsWonByHeroesAct2 = array();
$rumorsWonByHeroesAct1 = array();
$rumorsWonByHeroesAct2 = array();
$rumorsWonByOverlordAct1 = array();
$rumorsAutoWon = array();

// loop through the quests played in this game
foreach ($campaign['quests'] as $qos){
  // if the expansion of the quest is in the selected expansion and its a quest, or its a setup (for the minicampaigns)
  if (($qos['quest_exp_id'] == $selCampaign) && ($qos['quest_type'] == "Quest" || $qos['quest_type'] == "Setup")){
    // list it as played
    $questsCompleted[] = intval($qos['quest_id']);

    if (($qos['winner'] == 'Heroes Win') && ($qos['act'] == "Act 1" || $qos['act'] == "Interlude")){
      $questsWonByHeroesAct1[] = $qos['quest_id'];
    } else if(($qos['winner'] == 'Heroes Win') && $qos['act'] == "Act 2"){
      $questsWonByHeroesAct2[] = $qos['quest_id'];
    }
    
  } else {
    $rumorsCompleted[] = $qos['quest_id'];
    $rumorsExpansion[] = $qos['quest_exp_id'];
    if (($qos['winner'] == 'Heroes Win') && $qos['act'] == "Act 1"){
      $rumorsWonByHeroesAct1[] = $qos['quest_id'];
    }
    if (($qos['winner'] == 'Heroes Win') && $qos['act'] == "Act 2"){
      $rumorsWonByHeroesAct2[] = $qos['quest_id'];
    }
    if (($qos['winner'] == 'Overlord Wins') && $qos['act'] == "Act 1"){
      $rumorsWonByOverlordAct1[] = $qos['quest_id'];
    }
    if ((($qos['winner'] == 'Heroes Win') || ($qos['winner'] == 'Overlord Wins')) && $qos['act'] == "Act 2"){
      $rumorsCompletedAct2[] = $qos['quest_id'];
    }
  }
}


// Where are we in the Campaign?
$currentAct = "Act 1";
$currentActItems = "Act 1";
$questAct = "Act I";

if (count($questsCompleted) == 4){
  $currentAct = "Interlude";
  $questAct = "Act I";
}

if (count($questsCompleted) > 4){
  $currentAct = "Act 2";
  $questAct = "Act II";
}

if (count($questsCompleted) > 5 || count($rumorsCompletedAct2) > 0){
  $currentActItems = "Act 2";
  $questAct = "Act II";
}

if (count($questsCompleted) > 7){
  $currentAct = "Finale";
  $currentActItems = "Act 2";
  $questAct = "Act II";
}


function setQuestMessage($type, $questid, $required, $act, $diffquest){
  global $AvailableQuests;
  global $questsCompleted;
  $questMessage = "";

  if($type == "act-available"){
    $questMessage .= "Available because this quest is an " . $act . " quest.";
  }

  if($type == "introduction-available"){
    $questMessage .= "Available after the Introduction quest.";
  }

  if($type == "more-hero-wins-heroes"){
    $questMessage .= "Available because the Heroes won more than two " . $act . " quests.";
  }
  if($type == "more-hero-wins-overlord"){
     $questMessage .= "Unavailable because the Overlord lost more than two " . $act . " quests.";
  }
  if($type == "more-overlord-wins-heroes"){
    $questMessage .= "Available because the Overlord won more than two " . $act . " quests.";
  }
  if($type == "more-overlord-wins-overlord"){
    $questMessage .= "Unavailable because the Heroes lost more than two " . $act . " quests.";
  }

  if($type == "heroes-won"){
    $questMessage .= "Available because the Heroes won ";
  }
  if($type == "heroes-lost"){
    $questMessage .= "Unavailable because the Heroes lost ";
  }
  
  if($type == "overlord-won"){
    $questMessage .= "Available because the Overlord won ";
  }
  if($type == "overlord-lost"){
    $questMessage .= "Unavailable because the Overlord lost ";
  }

  if($type == "heroes-won" || $type == "heroes-lost" || $type == "overlord-won" || $type == "overlord-lost"){
    foreach ($required as $reqs){
      if (in_array($reqs, $questsCompleted)){
        $questMessage .= "'<strong>" . $AvailableQuests[$reqs]['quest_name'] . "</strong>'" . ".";
      }
    }
  }

  if($type == "heroes-attempt"){
    $questMessage .= "Unavailable because the Heroes didn't attempt ";
  }
  if($type == "overlord-attempt"){
    $questMessage .= "Available because the Heroes didn't attempt ";
  }

  if($type == "heroes-attempt" || $type == "overlord-attempt"){
    foreach ($required as $reqs){
      if (!in_array($reqs, $questsCompleted)){
        $questMessage .= "'<strong>" . $AvailableQuests[$reqs]['quest_name'] . "</strong>'" . ".";
      }
    }
  }

  if($type == "available-after"){
    $questMessage .= "Available after ";
    $i = count($required);
    foreach ($required as $reqs){
      $questMessage .= "'<strong>" . $AvailableQuests[$reqs]['quest_name'] . "</strong>'";
      if($i > 2){
        $questMessage .= ", ";
      } else if($i == 2){
        $questMessage .= " or ";
      } else {
        $questMessage .= ".";
      }   
      $i--;
    }

  }

  if($type == "available-because"){
    $questMessage .= "Available because ";
    $i = count($required);
    foreach ($required as $reqs){
      $questMessage .= "'<strong>" . $AvailableQuests[$reqs]['quest_name'] . "</strong>'";
      if($i > 2){
        $questMessage .= ", ";
      } else if($i == 2){
        $questMessage .= " or ";
      } else {
        $questMessage .= " was played.";
      }   
      $i--;
    }
  }

  if($type == "unavailable-because"){
    $questMessage .= "Unavailable because ";
    $i = count($required);
    foreach ($required as $reqs){
      $questMessage .= "'<strong>" . $AvailableQuests[$reqs]['quest_name'] . "</strong>'";
      if($i > 2){
        $questMessage .= ", ";
      } else if($i == 2){
        $questMessage .= " or ";
      } else {
        $questMessage .= " was not played.";
      }   
      $i--;
    }
  }

  if($type == "other-quest"){
    $questMessage .= "Unavailable because " . "'<strong>" . $AvailableQuests[$diffquest]['quest_name'] . "</strong>'" ." was played.";
  }
  if($type == "other-quest-not"){
    $questMessage .= "Unavailable because " . "'<strong>" . $AvailableQuests[$diffquest]['quest_name'] . "</strong>'" ." was not played.";
  }

  $AvailableQuests[$questid]['quest_status']['message'] = $questMessage;

}


include 'campaign_logs_rumors.php';

// Get the available quests, available as in 'these are in the story the game has been started with'

$query_rsAvQuestList = sprintf("SELECT * FROM tbquests WHERE quest_expansion_id = %s ORDER BY quest_order ASC", GetSQLValueString($selCampaign, "int"));
$rsAvQuestList = mysql_query($query_rsAvQuestList, $dbDescent) or die(mysql_error());
$row_rsAvQuestList = mysql_fetch_assoc($rsAvQuestList);
$totalRows_rsAvQuestList = mysql_num_rows($rsAvQuestList);

// Store these in an array, as type quest
$AvailableQuests = array();
do {

  $shortl = $row_rsAvQuestList['quest_name'];
  $shortl = strtolower($shortl);
  $shortl = str_replace(" ","_",$shortl);
  $shortl = preg_replace("/[^A-Za-z0-9_]/","",$shortl);

  $AvailableQuests[intval($row_rsAvQuestList['quest_id'])] = array(
    "quest_id" => intval($row_rsAvQuestList['quest_id']),
    "quest_type" => "quest",
    "quest_name" => $row_rsAvQuestList['quest_name'],
    "quest_act" => $row_rsAvQuestList['quest_act'],
    "quest_req_type" => $row_rsAvQuestList['quest_req_type'],
    "quest_req" => explode(",", $row_rsAvQuestList['quest_req']),
    "quest_img" => $shortl . ".jpg",
    "quest_description" => $row_rsAvQuestList['quest_description'],
    "quest_status" => array(
      "available" => 0,
      "message" => '',
    ),
  );

} while ($row_rsAvQuestList = mysql_fetch_assoc($rsAvQuestList));


// Get the available rumors, available as in 'these are in the expansions the game has been started with'

$query_rsAvRumorList = sprintf("SELECT * FROM tbquests LEFT JOIN tbcampaign ON quest_expansion_id = cam_id WHERE quest_expansion_id IN ($selExpansions) AND quest_expansion_id != %s AND cam_type != %s AND cam_type != %s ORDER BY quest_order ASC", GetSQLValueString($row_rsGroupCampaign['game_camp_id'], "int"), GetSQLValueString("full", "text"), GetSQLValueString("book", "text"));
$rsAvRumorList = mysql_query($query_rsAvRumorList, $dbDescent) or die(mysql_error());
$row_rsAvRumorList = mysql_fetch_assoc($rsAvRumorList);
$totalRows_rsAvRumorList = mysql_num_rows($rsAvRumorList);

// Store these in the same array, as type rumor
do {

  $shortl = $row_rsAvRumorList['quest_name'];
  $shortl = strtolower($shortl);
  $shortl = str_replace(" ","_",$shortl);
  $shortl = preg_replace("/[^A-Za-z0-9_]/","",$shortl);

  $AvailableQuests[intval($row_rsAvRumorList['quest_id'])] = array(
    "quest_id" => intval($row_rsAvRumorList['quest_id']),
    "quest_type" => "rumor",
    "quest_name" => $row_rsAvRumorList['quest_name'],
    "quest_act" => $row_rsAvRumorList['quest_act'],
    "quest_req_type" => $row_rsAvRumorList['quest_req_type'],
    "quest_req" => explode(",", $row_rsAvRumorList['quest_req']),
    "quest_img" => $shortl . ".jpg",
    "quest_description" => $row_rsAvRumorList['quest_description'],
    "quest_status" => array(
      "available" => 0,
      "message" => '',
    ),
  );

} while ($row_rsAvRumorList = mysql_fetch_assoc($rsAvRumorList));


$questSelect = array();
$questSelectWhy = array();


foreach ($AvailableQuests as $aqs) {

  // Create some intersections - FIX ME: I should rename this to something clearer
  $intersection1 = array_intersect($aqs['quest_req'], $questsWonByHeroesAct1);
  $intersection2 = array_intersect($aqs['quest_req'], $questsWonByHeroesAct2);
  $intersection3 = array_intersect($aqs['quest_req'], $questsCompleted);

  $intersectionH = array_intersect($aqs['quest_req'], $rumorsWonByHeroesAct1);
  $intersectionO = array_intersect($aqs['quest_req'], $rumorsWonByOverlordAct1);
  $intersectionAll = array_intersect($aqs['quest_req'], $rumorsCompleted);



  // if the quest or rumor has not been completed
  if(!(in_array($aqs['quest_id'], $questsCompleted)) && !(in_array($aqs['quest_id'], $rumorsCompleted))){

    // If its a quest, go through the relevant campaign log
    if($aqs['quest_type'] == "quest"){

      switch($selCampaign){
        case 0:
          // --- THE SHADOW RUNE --- //
          include 'campaign_logs_tsr.php';
          break;
        case 1:
          // --- LAIR OF THE WYRM --- //
          include 'campaign_logs_lotw.php';
          break;
        case 2:
          // --- LABYRINTH OF RUIN --- //
          include 'campaign_logs_lor.php';
          break;
        case 3:
          // --- THE TROLLFENS --- //
          include 'campaign_logs_tf.php';
          break;
        case 4:
          // --- SHADOW OF NEREKHALL --- //
          include 'campaign_logs_son.php';
          break;
        case 5:
          // --- MANOR OF RAVENS --- //
          include 'campaign_logs_mor.php';
          break;
        case 29:
          // --- HEIRS OF BLOOD --- //
          include 'campaign_logs_hob.php';
          break; 
        case 30:
          // --- MISTS OF BILEHALL --- //
          include 'campaign_logs_mob.php';
          break;   
      }
      
    } else {

      // -------------------- //
      // --- RUMOR QUESTS --- //
      // -------------------- //

      // if the current act is act 1 or the interlude
      if ($currentAct == "Act 1" || $currentAct == "Interlude"){
        
        // if the rumor is available
        if(in_array($aqs['quest_id'], $rumorQuestsAv)){

          $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 1;
          $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "Available because the card for this Rumor Quest is in play.";

        // else if the rumor is blocked
        } else if(in_array($aqs['quest_id'], $rumorQuestsBl)){

          $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "Unavailable because a different card from this expansion has been played.";

        // else they need to put it in play
        } else {

          $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "Unavailable because the card for this Rumor Quest is not in play.";

        }

      // if act 2
      } else if ($currentAct == "Act 2") {
        
          // if the rumor is an advanced (act 2) rumor
          if($aqs['quest_act'] == "Act 2"){

            // if the heroes won and the rumor requires the previous quest to be won by heroes
            if(!empty($intersectionH) && $aqs['quest_req_type'] == "Heroes"){
              $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 1;
              $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "Available because the Heroes won ";
              foreach ($aqs['quest_req'] as $reqs){
                if (in_array($reqs, $rumorsCompleted)){
                  $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] .= $AvailableQuests[$reqs]['quest_name'] . ".";
                }     
              }
            // if the overlord won and the rumor requires the previous quest to be won by heroes
            } else if(empty($intersectionH) && $aqs['quest_req_type'] == "Heroes"){
              $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 0;
              $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "Unavailable because the Heroes lost ";
              foreach ($aqs['quest_req'] as $reqs){
                if (in_array($reqs, $rumorsCompleted)){
                  $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] .= $AvailableQuests[$reqs]['quest_name'] . ".";
                }     
              }
            // if the overlord won and the rumor requires the previous quest to be won by the overlord
            } else if(!empty($intersectionO) && $aqs['quest_req_type'] == "Overlord"){

              if(array_intersect($aqs['quest_req'], $rumorsAutoWon)){
                $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 1;
                $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "Available because the rumor quest ";
                foreach ($aqs['quest_req'] as $reqs){
                  if (in_array($reqs, $rumorsCompleted)){
                    $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] .= $AvailableQuests[$reqs]['quest_name'] . " was not played.";
                  }     
                }
              } else {
                $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 1;
                $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "Available because the Overlord won ";
                foreach ($aqs['quest_req'] as $reqs){
                  if (in_array($reqs, $rumorsCompleted)){
                    $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] .= $AvailableQuests[$reqs]['quest_name'] . ".";
                  }     
                }
              }

            // if the heroes won and the rumor requires the previous quest to be won by the overlord
            } else if(empty($intersectionO) && $aqs['quest_req_type'] == "Overlord"){
              $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 0;
              $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "Unavailable because the Overlord lost ";
              foreach ($aqs['quest_req'] as $reqs){
                if (in_array($reqs, $rumorsCompleted)){
                  $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] .= $AvailableQuests[$reqs]['quest_name'] . ".";
                }     
              }
            // if the rumor requires no specific winner
            } else if(!empty($intersectionAll) && $aqs['quest_req_type'] == "All"){

              if(array_intersect($aqs['quest_req'], $rumorsAutoWon)){
                $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 1;
                $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "Available because the rumor quest ";
                foreach ($aqs['quest_req'] as $reqs){
                  if (in_array($reqs, $rumorsCompleted)){
                    $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] .= $AvailableQuests[$reqs]['quest_name'] . " was not played.";
                  }     
                }
              } else {
                $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 1;
                $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "Available because it is the follow up to ";
                foreach ($aqs['quest_req'] as $reqs){
                  if (in_array($reqs, $rumorsCompleted)){
                    $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] .= $AvailableQuests[$reqs]['quest_name'] . ".";
                  }     
                }
              }
            } 
          }

      }
      
    }

  // if the quest or rumor has been completed
  } else {
    if($aqs['quest_type'] == "quest"){
      $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 0;
      $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "This quest has already been completed.";
    } else {
      $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 0;
      $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "This rumor has already been completed.";
    }
    

  }

} // end foreach ($AvailableQuests as $aqs)

?>