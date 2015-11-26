<?php

// Make an array with completed quests/rumors for options
$questsCompleted = array();
$rumorsCompleted = array();
$rumorsExpansion = array();

// Which quest was by the heroes in act 1 and act 2
$questsWonByHeroesAct1 = array();
$questsWonByHeroesAct2 = array();
$rumorsWonByHeroesAct1 = array();
$rumorsWonByOverlordAct1 = array();

foreach ($campaign['quests'] as $qos){
  if (($qos['quest_exp_id'] == $selCampaign) && ($qos['quest_type'] == "Quest" || $qos['quest_type'] == "Setup")){
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
    if (($qos['winner'] == 'Overlord Wins') && $qos['act'] == "Act 1"){
      $rumorsWonByOverlordAct1[] = $qos['quest_id'];
    }
  }
}

// Where are we in the Campaign?
$currentAct = "Act 1";
$currentActItems = "Act 1";

if (count($questsCompleted) == 4){
  $currentAct = "Interlude";
}

if (count($questsCompleted) > 4){
  $currentAct = "Act 2";
}

if (count($questsCompleted) > 5){
$currentActItems = "Act 2";
}

if (count($questsCompleted) > 7){
  $currentAct = "Finale";
  $currentActItems = "Act 2";
}


// echo $selCampaign;
// echo '<br/>';
// echo $currentAct;
// echo '<pre>';
// var_dump ($questsCompleted);
// echo '</pre>';
// echo '<pre>';
// var_dump ($campaign['quests']);
// echo '</pre>';



// Available Quests

$query_rsAvQuestList = sprintf("SELECT * FROM tbquests WHERE quest_expansion_id = %s ORDER BY quest_order ASC", GetSQLValueString($selCampaign, "int"));
$rsAvQuestList = mysql_query($query_rsAvQuestList, $dbDescent) or die(mysql_error());
$row_rsAvQuestList = mysql_fetch_assoc($rsAvQuestList);
$totalRows_rsAvQuestList = mysql_num_rows($rsAvQuestList);


$AvailableQuests = array();
do {
  $AvailableQuests[] = array(
    "quest_id" => intval($row_rsAvQuestList['quest_id']),
    "quest_name" => $row_rsAvQuestList['quest_name'],
    "quest_act" => $row_rsAvQuestList['quest_act'],
    "quest_req_type" => $row_rsAvQuestList['quest_req_type'],
    "quest_req" => explode(",", $row_rsAvQuestList['quest_req']),
    );

} while ($row_rsAvQuestList = mysql_fetch_assoc($rsAvQuestList));

$questOptions = array();


foreach ($AvailableQuests as $aqs) {

$intersection1 = array_intersect($aqs['quest_req'], $questsWonByHeroesAct1);
$intersection2 = array_intersect($aqs['quest_req'], $questsWonByHeroesAct2);
$intersection3 = array_intersect($aqs['quest_req'], $questsCompleted);
  // ----------------------- //
  // --- THE SHADOW RUNE --- //
  // ----------------------- //
  if ($selCampaign == 0){
    // filter out completed quests
    if(!(in_array($aqs['quest_id'], $questsCompleted))){ 

      if ($currentAct == "Act 1"){

        if($aqs['quest_act'] == "Act 1"){
          // In 'The Shadow Rune' all Act 1 quest can be selected freely
          $questOptions[] = '<option value="' . $aqs['quest_id'] . '">' . $aqs['quest_name'] . '</option>';
        }

      } else if ($currentAct == "Interlude"){

        if($aqs['quest_act'] == "Interlude"){
          // In 'The Shadow Rune', if the heroes win at least 2 quests, they play 'The Shadow Vault' (16), otherwise they play 'The Overlord Revealed' (17)
          if (count($questsWonByHeroesAct1) >= 2 && $aqs['quest_req_type'] == "Heroes"){
            $questOptions[] = '<option value="' . $aqs['quest_id'] . '">' . $aqs['quest_name'] . '</option>';
          } else if (count($questsWonByHeroesAct1) < 2 && $aqs['quest_req_type'] == "Overlord"){
            $questOptions[] = '<option value="' . $aqs['quest_id'] . '">' . $aqs['quest_name'] . '</option>';
          }
        }

      } else if ($currentAct == "Act 2"){

        if($aqs['quest_act'] == "Act 2"){
          // In 'The Shadow Rune' the Act 2 quests the heroes can select are the 'Hero' follow up ones of the ones they won during Act 1 
          // + the 'Overlord' follow up quest for those they did not attempt
          if(!empty($intersection1) && $aqs['quest_req_type'] == "Heroes"){
            $questOptions[] = '<option value="' . $aqs['quest_id'] . '">' . $aqs['quest_name'] . '</option>';
          } else if(empty($intersection1) && $aqs['quest_req_type'] == "Overlord"){
            $questOptions[] = '<option value="' . $aqs['quest_id'] . '">' . $aqs['quest_name'] . '</option>';
          }
        }

      } else if ($currentAct == "Finale"){

        if($aqs['quest_act'] == "Finale"){
          if (count($questsWonByHeroesAct2) >= 2 && $aqs['quest_req_type'] == "Heroes"){
            $questOptions[] = '<option value="' . $aqs['quest_id'] . '">' . $aqs['quest_name'] . '</option>';
          } else if (count($questsWonByHeroesAct2) < 2 && $aqs['quest_req_type'] == "Overlord"){
            $questOptions[] = '<option value="' . $aqs['quest_id'] . '">' . $aqs['quest_name'] . '</option>';
          }
        }

      }

    }  // in array end

  } // end selCampaign


  // ------------------------- //
  // --- LABYRINTH OF RUIN --- //
  // ------------------------- //

  if ($selCampaign == 2){
    // filter out completed quests
    if(!(in_array($aqs['quest_id'], $questsCompleted))){ 

      if ($currentAct == "Act 1"){

        if (count($questsCompleted) == 1){
          // In 'The Labyrinth of Ruin' the players have 2 quests to chose from after the Introduction quest, quests 26 and 27
          if($aqs['quest_id'] == "26" || $aqs['quest_id'] == "27"){
            $questOptions[] = '<option value="' . $aqs['quest_id'] . '">' . $aqs['quest_name'] . '</option>';
          }
        }

        if(!empty($intersection3)){


        // In 'The Labyrinth of Ruin' there are two 'branches' of quests, of which each has 2 unique quests

          if (count($questsCompleted) == 2){

            if($aqs['quest_req_type'] == "Unique1"){
              $questOptions[] = '<option value="' . $aqs['quest_id'] . '">' . $aqs['quest_name'] . '</option>';
            }

          }

          if (count($questsCompleted) == 3){

            if($aqs['quest_req_type'] == "Unique2"){
              $questOptions[] = '<option value="' . $aqs['quest_id'] . '">' . $aqs['quest_name'] . '</option>';
            }

          }

        }

        // In 'The Labyrinth of Ruin' there are also 2 shared quests between the two branches
        if (count($questsCompleted) == 2){
          
          if($aqs['quest_id'] == 32){
            $questOptions[] = '<option value="' . $aqs['quest_id'] . '">' . $aqs['quest_name'] . '</option>';
          }

        }

        if (count($questsCompleted) == 3){
          if($aqs['quest_id'] == 33){
            $questOptions[] = '<option value="' . $aqs['quest_id'] . '">' . $aqs['quest_name'] . '</option>';
          }

        }

      } else if ($currentAct == "Interlude"){

        if($aqs['quest_act'] == "Interlude"){
          // In 'The Labyrinth of Ruin', The winner of the third Act 1 quest selects the interlude quest
          $questOptions[] = '<option value="' . $aqs['quest_id'] . '">' . $aqs['quest_name'] . '</option>';
        }

      } else if ($currentAct == "Act 2"){

        if($aqs['quest_act'] == "Act 2"){

          // In 'The Labyrinth of Ruin' all Act 2 quest can be selected freely
          $questOptions[] = '<option value="' . $aqs['quest_id'] . '">' . $aqs['quest_name'] . '</option>';

        }

      } else if ($currentAct == "Finale"){

        if($aqs['quest_act'] == "Finale"){
          if(!empty($intersection2) && $aqs['quest_req_type'] == "Heroes"){
            $questOptions[] = '<option value="' . $aqs['quest_id'] . '">' . $aqs['quest_name'] . '</option>';
          } else if(empty($intersection1) && $aqs['quest_req_type'] == "Overlord"){
            $questOptions[] = '<option value="' . $aqs['quest_id'] . '">' . $aqs['quest_name'] . '</option>';
          }
        }

      }

    }  // in array end

  } // end selCampaign

  // --------------------------- //
  // --- Shadow of Nerekhall --- //
  // --------------------------- //

  if ($selCampaign == 4){
    // filter out completed quests
    if(!(in_array($aqs['quest_id'], $questsCompleted))){ 

      if ($currentAct == "Act 1"){

        if($aqs['quest_act'] == "Act 1"){
          // In 'Shadow of Nerekhall' all Act 1 quest can be selected freely
          $questOptions[] = '<option value="' . $aqs['quest_id'] . '">' . $aqs['quest_name'] . '</option>';
        }

      } else if ($currentAct == "Interlude"){

        if($aqs['quest_act'] == "Interlude"){
          // In 'Shadow of Nerekhall', if the heroes win at least 2 quests, they play 'The True Enemy' (16), otherwise they play 'The Overlord Revealed' (17)
          if (count($questsWonByHeroesAct1) >= 2 && $aqs['quest_req_type'] == "Heroes"){
            $questOptions[] = '<option value="' . $aqs['quest_id'] . '">' . $aqs['quest_name'] . '</option>';
          } else if (count($questsWonByHeroesAct1) < 2 && $aqs['quest_req_type'] == "Overlord"){
            $questOptions[] = '<option value="' . $aqs['quest_id'] . '">' . $aqs['quest_name'] . '</option>';
          }
        }

      } else if ($currentAct == "Act 2"){

        if($aqs['quest_act'] == "Act 2"){

          if (count($questsCompleted) == 5){

            if($aqs['quest_req_type'] == "Unique1"){
              $questOptions[] = '<option value="' . $aqs['quest_id'] . '">' . $aqs['quest_name'] . '</option>';
            }

          }

          if (count($questsCompleted) == 6){

            if($aqs['quest_req_type'] == "Unique2"){
              $questOptions[] = '<option value="' . $aqs['quest_id'] . '">' . $aqs['quest_name'] . '</option>';
            }

          }

          if (count($questsCompleted) == 7){

            if($aqs['quest_req_type'] == "Unique3"){
              $questOptions[] = '<option value="' . $aqs['quest_id'] . '">' . $aqs['quest_name'] . '</option>';
            }

          }
        }

      } else if ($currentAct == "Finale"){

        if($aqs['quest_act'] == "Finale"){
          if(!empty($intersection2) && $aqs['quest_req_type'] == "Heroes"){
            $questOptions[] = '<option value="' . $aqs['quest_id'] . '">' . $aqs['quest_name'] . '</option>';
          } else if(empty($intersection1) && $aqs['quest_req_type'] == "Overlord"){
            $questOptions[] = '<option value="' . $aqs['quest_id'] . '">' . $aqs['quest_name'] . '</option>';
          }
        }

      }

    }  // in array end

  } // end selCampaign

  // ----------------------- //
  // --- Lair of the Wyrm --- //
  // ----------------------- //

  if ($selCampaign == 1){
    // filter out completed quests
    if(!(in_array($aqs['quest_id'], $questsCompleted))){ 

      if ($currentAct == "Act 1"){

        if (count($questsCompleted) == 1){
          if($aqs['quest_id'] == "20"){
            $questOptions[] = '<option value="' . $aqs['quest_id'] . '">' . $aqs['quest_name'] . '</option>';
          }
        }

        if (count($questsCompleted) == 2){
          if($aqs['quest_id'] == "21"){
            $questOptions[] = '<option value="' . $aqs['quest_id'] . '">' . $aqs['quest_name'] . '</option>';
          }
        }

        if (count($questsCompleted) == 3){
          if($aqs['quest_id'] == "22"){
            $questOptions[] = '<option value="' . $aqs['quest_id'] . '">' . $aqs['quest_name'] . '</option>';
          }
        }

      } else {

        if($aqs['quest_act'] == "Act 2"){
          if(count($questsWonByHeroesAct1) > 2){
            if($aqs['quest_id'] == "23"){
              $questOptions[] = '<option value="' . $aqs['quest_id'] . '">' . $aqs['quest_name'] . '</option>';
            }
          } else {
            if($aqs['quest_id'] == "24"){
              $questOptions[] = '<option value="' . $aqs['quest_id'] . '">' . $aqs['quest_name'] . '</option>';
            }
          }

        }

      } 

    }  // in array end

  } // end selCampaign

  // ----------------------- //
  // --- Lair of the Wyrm --- //
  // ----------------------- //

  if ($selCampaign == 3){
    // filter out completed quests
    if(!(in_array($aqs['quest_id'], $questsCompleted))){ 

      if ($currentAct == "Act 1"){

        if (count($questsCompleted) == 1){
          if($aqs['quest_id'] == "44"){
            $questOptions[] = '<option value="' . $aqs['quest_id'] . '">' . $aqs['quest_name'] . '</option>';
          }
        }

        if (count($questsCompleted) == 2){
          if($aqs['quest_id'] == "45"){
            $questOptions[] = '<option value="' . $aqs['quest_id'] . '">' . $aqs['quest_name'] . '</option>';
          }
        }

        if (count($questsCompleted) == 3){
          if($aqs['quest_id'] == "46"){
            $questOptions[] = '<option value="' . $aqs['quest_id'] . '">' . $aqs['quest_name'] . '</option>';
          }
        }

      } else {

        if($aqs['quest_act'] == "Act 2"){
          if(count($questsWonByHeroesAct1) > 2){
            if($aqs['quest_id'] == "48"){
              $questOptions[] = '<option value="' . $aqs['quest_id'] . '">' . $aqs['quest_name'] . '</option>';
            }
          } else {
            if($aqs['quest_id'] == "47"){
              $questOptions[] = '<option value="' . $aqs['quest_id'] . '">' . $aqs['quest_name'] . '</option>';
            }
          }

        }

      } 

    }  // in array end

  } // end selCampaign

  // ----------------------- //
  // --- Manor of Ravens --- //
  // ----------------------- //

  if ($selCampaign == 5){
    // filter out completed quests
    if(!(in_array($aqs['quest_id'], $questsCompleted))){ 

      if ($currentAct == "Act 1"){

        if (count($questsCompleted) == 1){
          if($aqs['quest_id'] == "68"){
            $questOptions[] = '<option value="' . $aqs['quest_id'] . '">' . $aqs['quest_name'] . '</option>';
          }
        }

        if (count($questsCompleted) == 2){
          if($aqs['quest_id'] == "69"){
            $questOptions[] = '<option value="' . $aqs['quest_id'] . '">' . $aqs['quest_name'] . '</option>';
          }
        }

        if (count($questsCompleted) == 3){
          if($aqs['quest_id'] == "70"){
            $questOptions[] = '<option value="' . $aqs['quest_id'] . '">' . $aqs['quest_name'] . '</option>';
          }
        }

      } else {

        if($aqs['quest_act'] == "Act 2"){
          if(!empty($intersection1) && count($questsWonByHeroesAct1) > 2){
            $questOptions[] = '<option value="' . $aqs['quest_id'] . '">' . $aqs['quest_name'] . '</option>';
          } else if(empty($intersection1) && count($questsWonByHeroesAct1) < 2 ){
            $questOptions[] = '<option value="' . $aqs['quest_id'] . '">' . $aqs['quest_name'] . '</option>';
          }

        }

      } 

    }  // in array end

  } // end selCampaign

  // ---------------------- //
  // --- HEIRS OF BLOOD --- //
  // ---------------------- //

  if ($selCampaign == 29){
    // filter out completed quests
    if(!(in_array($aqs['quest_id'], $questsCompleted))){ 

      if ($currentAct == "Act 1"){

        if (count($questsCompleted) == 1){
          if($aqs['quest_id'] == "86" || $aqs['quest_id'] == "87"){
            $questOptions[] = '<option value="' . $aqs['quest_id'] . '">' . $aqs['quest_name'] . '</option>';
          }
        }

        if(!empty($intersection3)){


        // In 'The Labyrinth of Ruin' there are two 'branches' of quests, of which each has 2 unique quests

          if (count($questsCompleted) == 2){

            if($aqs['quest_req_type'] == "Unique1"){
              $questOptions[] = '<option value="' . $aqs['quest_id'] . '">' . $aqs['quest_name'] . '</option>';
            }

          }

          if (count($questsCompleted) == 3){

            if(!empty($intersection1) && $aqs['quest_req_type'] == "Heroes"){
              $questOptions[] = '<option value="' . $aqs['quest_id'] . '">' . $aqs['quest_name'] . '</option>';
            } else if(empty($intersection1) && $aqs['quest_req_type'] == "Overlord"){
              $questOptions[] = '<option value="' . $aqs['quest_id'] . '">' . $aqs['quest_name'] . '</option>';
            }

          }

        }

        // In 'The Labyrinth of Ruin' there are also 2 shared quests between the two branches
        if (count($questsCompleted) == 2){
          
          if($aqs['quest_id'] == 89){
            $questOptions[] = '<option value="' . $aqs['quest_id'] . '">' . $aqs['quest_name'] . '</option>';
          }

        }

      } else if ($currentAct == "Interlude"){

        if($aqs['quest_act'] == "Interlude"){
          // In 'The Labyrinth of Ruin', The winner of the third Act 1 quest selects the interlude quest
          $questOptions[] = '<option value="' . $aqs['quest_id'] . '">' . $aqs['quest_name'] . '</option>';
        }

      } else if ($currentAct == "Act 2"){

        if($aqs['quest_act'] == "Act 2"){

          if (count($questsCompleted) == 5){

            if($aqs['quest_id'] == "95" || $aqs['quest_id'] == "96"){
              if(!empty($intersection1) && $aqs['quest_req_type'] == "Heroes"){
                $questOptions[] = '<option value="' . $aqs['quest_id'] . '">' . $aqs['quest_name'] . '</option>';
              } else if(empty($intersection1) && $aqs['quest_req_type'] == "Overlord"){
                $questOptions[] = '<option value="' . $aqs['quest_id'] . '">' . $aqs['quest_name'] . '</option>';
              }
            }

          }

          if (count($questsCompleted) == 6){

            if($aqs['quest_id'] == "97" || $aqs['quest_id'] == "98"){
              if(!empty($intersection2) && $aqs['quest_req_type'] == "Heroes"){
                $questOptions[] = '<option value="' . $aqs['quest_id'] . '">' . $aqs['quest_name'] . '</option>';
              } else if(empty($intersection2) && $aqs['quest_req_type'] == "Overlord"){
                $questOptions[] = '<option value="' . $aqs['quest_id'] . '">' . $aqs['quest_name'] . '</option>';
              }
            }

          }

          if (count($questsCompleted) == 7){
            if($aqs['quest_id'] == "101" || $aqs['quest_id'] == "102"){
              $questOptions[] = '<option value="' . $aqs['quest_id'] . '">' . $aqs['quest_name'] . '</option>';
            }
          }

        }

      } else if ($currentAct == "Finale"){

        if($aqs['quest_act'] == "Finale"){
          $questOptions[] = '<option value="' . $aqs['quest_id'] . '">' . $aqs['quest_name'] . '</option>';
        }

      }

    }  // in array end

  } // end selCampaign


} // foreach end

// Available Rumor Cards

$query_rsAvRumorCards = sprintf("SELECT * FROM tbrumors LEFT JOIN tbcampaign ON rumor_exp_id = cam_id WHERE rumor_exp_id IN ($selExpansions) AND rumor_exp_id != %s ORDER BY rumor_name ASC", GetSQLValueString($row_rsGroupCampaign['game_camp_id'], "int"));
$rsAvRumorCards = mysql_query($query_rsAvRumorCards, $dbDescent) or die(mysql_error());
$row_rsAvRumorCards = mysql_fetch_assoc($rsAvRumorCards);
$totalRows_rsAvRumorCards = mysql_num_rows($rsAvRumorCards);

$availableRumorCards = array();
$rumorsInPlayData = array();
$rumorsDoneData = array();
$blockedRumors = array();

do {
  $availableRumorCards[] = array(
    "rumor_id" => intval($row_rsAvRumorCards['rumor_id']),
    "rumor_name" => $row_rsAvRumorCards['rumor_name'],
    "rumor_quest_id" => $row_rsAvRumorCards['rumor_quest_id'],
    "rumor_exp_id" => $row_rsAvRumorCards['rumor_exp_id'],
    "rumor_act" => $row_rsAvRumorCards['rumor_act'],
    "rumor_type" => $row_rsAvRumorCards['rumor_type'],
    "rumor_step" => $row_rsAvRumorCards['rumor_step'],
  );

  if(in_array($row_rsAvRumorCards['rumor_id'], $rumorsInPlay)){
    $rumorsInPlayData[] = array(
      "rumor_id" => intval($row_rsAvRumorCards['rumor_id']),
      "rumor_name" => $row_rsAvRumorCards['rumor_name'],
      "rumor_quest_id" => $row_rsAvRumorCards['rumor_quest_id'],
      "rumor_exp_id" => $row_rsAvRumorCards['rumor_exp_id'],
      "rumor_act" => $row_rsAvRumorCards['rumor_act'],
      "rumor_type" => $row_rsAvRumorCards['rumor_type'],
    );
  }

  if(in_array($row_rsAvRumorCards['rumor_id'], $rumorsDone)){
    $rumorsDoneData[] = array(
      "rumor_id" => intval($row_rsAvRumorCards['rumor_id']),
      "rumor_name" => $row_rsAvRumorCards['rumor_name'],
      "rumor_quest_id" => $row_rsAvRumorCards['rumor_quest_id'],
      "rumor_exp_id" => $row_rsAvRumorCards['rumor_exp_id'],
      "rumor_act" => $row_rsAvRumorCards['rumor_act'],
      "rumor_type" => $row_rsAvRumorCards['rumor_type'],
    );
  }

  $tempBlocked = explode(",", $row_rsAvRumorCards['rumor_blocks']);

  if(in_array($row_rsAvRumorCards['rumor_id'], $rumorsInPlay) || in_array($row_rsAvRumorCards['rumor_id'], $rumorsDone)){
    foreach ($tempBlocked as $tb){
      if ($tb != NULL){
        $blockedRumors[] = $tb; 
      }
    }
  }

} while ($row_rsAvRumorCards = mysql_fetch_assoc($rsAvRumorCards));

// echo '<pre>';
// var_dump($availableRumorCards);
// echo '</pre>';

// echo '<pre>';
// var_dump($blockedRumors);
// echo '</pre>';

foreach ($availableRumorCards as $avc) {
  if(!(in_array($avc['rumor_id'], $rumorsInPlay)) && !(in_array($avc['rumor_id'], $rumorsDone))){
    if(!(in_array($avc['rumor_id'], $blockedRumors))){
      if ($currentAct == $avc['rumor_act'] || ($currentAct == "Act 1" &&  $avc['rumor_act'] == "Interlude") || $avc['rumor_act'] == "All"){
        // if($campaign['quests'][0]['items_set'] == 1 && $campaign['quests'][0]['spendxp_set'] == 1 && $avc['rumor_step'] == "Start"){
        //   $rumorCardOptions[] = '<option value="' . $avc['rumor_id'] . '">' . $avc['rumor_name'] . '</option>';

        // } else 
        if($campaign['quests'][0]['winner'] != NULL){
          //if items arent set and the rumor targets the items step
          if ($campaign['quests'][0]['items_set'] != 1 && $avc['rumor_step'] == "Items"){
            $rumorCardOptions[] = '<option value="' . $avc['rumor_id'] . '">' . $avc['rumor_name'] . '</option>';
          }
          //
          if ($avc['rumor_step'] == "Start" && $currentAct != "Interlude"){
            $rumorCardOptions[] = '<option value="' . $avc['rumor_id'] . '">' . $avc['rumor_name'] . '</option>';
          }
          // if (($campaign['quests'][0]['items_set'] != 1 && $campaign['quests'][0]['spendxp_set'] != 1) && ($avc['rumor_step'] == "Start")){
          //   $rumorCardOptions[] = '<option value="' . $avc['rumor_id'] . '">' . $avc['rumor_name'] . '</option>';
          // }
          // if (($campaign['quests'][0]['items_set'] != 1 || $campaign['quests'][0]['spendxp_set'] != 1) && $avc['rumor_type'] == "Quest"){
          //   $rumorCardOptions[] = '<option value="' . $avc['rumor_id'] . '">' . $avc['rumor_name'] . '</option>';
          // }

        } else {
          if ($avc['rumor_step'] == "Details"){
            if($campaign['quests'][0]['travel_set'] == 1 && $campaign['quests'][0]['act'] != "Introduction"){
              $rumorCardOptions[] = '<option value="' . $avc['rumor_id'] . '">' . $avc['rumor_name'] . '</option>';
            }
          }
          
          else if($campaign['quests'][0]['act'] != "Introduction" && $campaign['quests'][0]['travel_set'] == 0 && $avc['rumor_step'] == "Travel"){
            $rumorCardOptions[] = '<option value="' . $avc['rumor_id'] . '">' . $avc['rumor_name'] . '</option>';
          }
        }
      }
    }
  }
}

// Available Rumor Quests

$query_rsAvRumorList = sprintf("SELECT * FROM tbquests LEFT JOIN tbcampaign ON quest_expansion_id = cam_id WHERE quest_expansion_id != %s AND cam_type != %s ORDER BY quest_order ASC", GetSQLValueString($row_rsGroupCampaign['game_camp_id'], "int"), GetSQLValueString("full", "text"));
$rsAvRumorList = mysql_query($query_rsAvRumorList, $dbDescent) or die(mysql_error());
$row_rsAvRumorList = mysql_fetch_assoc($rsAvRumorList);
$totalRows_rsAvRumorList = mysql_num_rows($rsAvRumorList);

$availableRumors = array();

do {
  $availableRumors[] = array(
    "quest_id" => intval($row_rsAvRumorList['quest_id']),
    "quest_name" => $row_rsAvRumorList['quest_name'],
    "quest_expansion_id" => $row_rsAvRumorList['quest_expansion_id'],
    "quest_act" => $row_rsAvRumorList['quest_act'],
    "quest_req_type" => $row_rsAvRumorList['quest_req_type'],
    "quest_req" => explode(",", $row_rsAvRumorList['quest_req']),
    );
} while ($row_rsAvRumorList = mysql_fetch_assoc($rsAvRumorList));

if ($currentAct == "Act 1" || $currentAct == "Interlude"){
  foreach ($availableRumorCards as $avc){
    if(in_array($avc['rumor_id'], $rumorsInPlay)){
      foreach ($availableRumors as $avr) {
        if ($avr['quest_id'] == $avc['rumor_quest_id']){
          $rumorOptions[] = '<option value="' . $avr['quest_id'] . '">' . $avr['quest_name'] . '</option>';
        }
      }
    }
  }
} else if ($currentAct == "Act 2") {
  $query_rsRumorsAdvanced = sprintf("SELECT * FROM tbrumors_played INNER JOIN tbrumors ON played_rumor_id = rumor_id WHERE played_game_id = %s AND played_rumor_quest_id is not null", 
                    GetSQLValueString($gameID, "int"));
  $rsRumorsAdvanced = mysql_query($query_rsRumorsAdvanced, $dbDescent) or die(mysql_error());
  $row_rsRumorsAdvanced = mysql_fetch_assoc($rsRumorsAdvanced);

  do{
    if(!in_array($row_rsRumorsAdvanced['rumor_quest_id'], $rumorsCompleted)){
      $rumorsWonByOverlordAct1[] = $row_rsRumorsAdvanced['rumor_quest_id'];
      $rumorsCompleted[] = $row_rsRumorsAdvanced['rumor_quest_id'];
    }

  } while ($row_rsRumorsAdvanced = mysql_fetch_assoc($rsRumorsAdvanced));

  foreach ($availableRumors as $avr) {
    $intersection1 = array_intersect($avr['quest_req'], $rumorsWonByHeroesAct1);
    $intersectionO = array_intersect($avr['quest_req'], $rumorsWonByOverlordAct1);
    $intersectionAll = array_intersect($avr['quest_req'], $rumorsCompleted);


    if($avr['quest_act'] == "Act 2"){

      //$rumorOptions[] = '<option value="' . $avr['quest_id'] . '">' . $avr['quest_name'] . '</option>';
      
      if(!empty($intersection1) && $avr['quest_req_type'] == "Heroes"){
        $rumorOptions[] = '<option value="' . $avr['quest_id'] . '">' . $avr['quest_name'] . '</option>';
      } else if(!empty($intersectionO) && $avr['quest_req_type'] == "Overlord"){
        $rumorOptions[] = '<option value="' . $avr['quest_id'] . '">' . $avr['quest_name'] . '</option>';
      } else if(!empty($intersectionAll) && $avr['quest_req_type'] == "All"){
        $rumorOptions[] = '<option value="' . $avr['quest_id'] . '">' . $avr['quest_name'] . '</option>';
      } 
    }

  }

  
}






// foreach ($availableRumors as $avr) {
//   if(in_array($avr['quest_id'], $rumorsInPlay)){
//     if ($currentAct == "Act 1"){
//       if($avr['quest_act'] == "Act 1"){
//         if(!(in_array($avr['quest_expansion_id'], $rumorsExpansion))){
//           $rumorOptions[] = '<option value="' . $avr['quest_id'] . '">' . $avr['quest_name'] . '</option>';
//         }
//       } 
//     } 
//     else if ($currentAct == "Act 1"){
//       if($avr['quest_act'] == "Finale"){
//         $rumorOptions[] = '<option value="' . $avr['quest_id'] . '">' . $avr['quest_name'] . '</option>';
//       } 
//     }
//   }
// }
                  
?>