<?php 


// Load the rumors from the database and left join the campaign for the campaign names
$query_rsAvRumorCards = sprintf("SELECT * FROM tbrumors LEFT JOIN tbcampaign ON rumor_exp_id = cam_id WHERE rumor_exp_id IN ($selExpansions) AND rumor_exp_id != %s ORDER BY rumor_name ASC", GetSQLValueString($row_rsGroupCampaign['game_camp_id'], "int"));
$rsAvRumorCards = mysql_query($query_rsAvRumorCards, $dbDescent) or die(mysql_error());
$row_rsAvRumorCards = mysql_fetch_assoc($rsAvRumorCards);
$totalRows_rsAvRumorCards = mysql_num_rows($rsAvRumorCards);

$availableRumorCards = array();
$rumorsInPlayData = array();
$rumorsDoneData = array();
$blockedRumors = array();

do {
  // Add them to a general array of all rumors
  $availableRumorCards[] = array(
    "rumor_id" => intval($row_rsAvRumorCards['rumor_id']),
    "rumor_name" => $row_rsAvRumorCards['rumor_name'],
    "rumor_quest_id" => $row_rsAvRumorCards['rumor_quest_id'],
    "rumor_exp_id" => $row_rsAvRumorCards['rumor_exp_id'],
    "rumor_act" => $row_rsAvRumorCards['rumor_act'],
    "rumor_type" => $row_rsAvRumorCards['rumor_type'],
    "rumor_step" => $row_rsAvRumorCards['rumor_step'],
  );

  // if the the rumor is in play, put it in a second array - FIX ME: Why not use a flag in the previous array?
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

  // if the the rumor is done, put it in a third array - FIX ME: Why not use a flag in the previous array?
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

  // Put the id's of rumors that can be blocked by this rumor in a temporary array
  $tempBlocked = explode(",", $row_rsAvRumorCards['rumor_blocks']);

  // if the rumor is in play or has been played, add it to an array to block the other rumors
  if(in_array($row_rsAvRumorCards['rumor_id'], $rumorsInPlay) || in_array($row_rsAvRumorCards['rumor_id'], $rumorsDone)){
    foreach ($tempBlocked as $tb){
      if ($tb != NULL){
        $blockedRumors[] = $tb; 
      }
    }
  }

} while ($row_rsAvRumorCards = mysql_fetch_assoc($rsAvRumorCards));

// Go through all rumor cards
foreach ($availableRumorCards as $avc) {
  // if the rumor id is not in the in play or in the rumors done array
  if(!in_array($avc['rumor_id'], $rumorsInPlay) && !in_array($avc['rumor_id'], $rumorsDone) ){
    // if the id is also not in the blocked by other rumors
    if(!(in_array($avc['rumor_id'], $blockedRumors))){
      // then if the current act is the same as the rumor act, or the current act is act 1 and the rumor act is set to Interlude, or the rumor is available in any act.
      if ($currentAct == $avc['rumor_act'] || ($currentAct == "Act 1" &&  $avc['rumor_act'] == "Interlude") || $avc['rumor_act'] == "All"){

        // If the campaign winner has been set
        if($campaign['quests'][0]['winner'] != NULL){
          // And the items aren't set (so we are before the shopping step) and the rumor targets the shopping step
          if ($campaign['quests'][0]['items_set'] != 1 && $avc['rumor_step'] == "Items"){
            // add it to the options
            $rumorCardOptions[] = '<option value="' . $avc['rumor_id'] . '">' . $avc['rumor_name'] . '</option>';
          }
          // Or if the quest info has just been entered (then we are at the start of the campaign phase) and we are not at the interlude
          if ($avc['rumor_step'] == "Start" && $currentAct != "Interlude"){
            // add it to the options
            $rumorCardOptions[] = '<option value="' . $avc['rumor_id'] . '">' . $avc['rumor_name'] . '</option>';
          }

        } else {
          // if the current quest isn't the introduction
          if($campaign['quests'][0]['act'] != "Introduction"){
            // and the rumor is supposed to be played before a quest (which is when travel has been set and winner is not set)
            if ($avc['rumor_step'] == "Details" && $campaign['quests'][0]['travel_set'] == 1){
              // add it to the options
              $rumorCardOptions[] = '<option value="' . $avc['rumor_id'] . '">' . $avc['rumor_name'] . '</option>';
            }
            // and the rumor is supposed to be played before travel (which is when travel has not been set and winner is not set)
            else if($avc['rumor_step'] == "Travel" && $campaign['quests'][0]['travel_set'] == 0){
              $rumorCardOptions[] = '<option value="' . $avc['rumor_id'] . '">' . $avc['rumor_name'] . '</option>';
            }
          }
        }
      }
    }
  }
}

$rumorQuestsAv = array();
$rumorQuestsBl = array();
// for each rumor card
foreach ($availableRumorCards as $avc){
  // if the rumor is in play
  if(in_array($avc['rumor_id'], $rumorsInPlay)){
    // place the quest attached to it in an array
    $rumorQuestsAv[] = $avc['rumor_quest_id'];
  }
  // if a rumor is blocked
  if(in_array($avc['rumor_id'], $blockedRumors)){
    // place the quest attached to it in an array
    $rumorQuestsBl[] = $avc['rumor_quest_id'];
  }
}

// if the current act is Act 2, then get the advanced quests
if ($currentAct == "Act 2"){
  // get the played rumors from the database
  $query_rsRumorsAdvanced = sprintf("SELECT * FROM tbrumors_played INNER JOIN tbrumors ON played_rumor_id = rumor_id WHERE played_game_id = %s AND played_rumor_quest_id is not null", 
                    GetSQLValueString($gameID, "int"));
  $rsRumorsAdvanced = mysql_query($query_rsRumorsAdvanced, $dbDescent) or die(mysql_error());
  $row_rsRumorsAdvanced = mysql_fetch_assoc($rsRumorsAdvanced);

  do{
    // if a rumor with quest was found, and its not in the rumors completed, then it was auto completed at the start of the interlude
    if(!in_array($row_rsRumorsAdvanced['rumor_quest_id'], $rumorsCompleted)){
      // Add it to the rumors won by the overlord so its available, to Autowon, to change the message, and the rumorscompleted
      // FIX ME: Filter out LotW and Trollfens
      $rumorsWonByOverlordAct1[] = $row_rsRumorsAdvanced['rumor_quest_id'];
      $rumorsAutoWon[] = $row_rsRumorsAdvanced['rumor_quest_id'];
      $rumorsCompleted[] = $row_rsRumorsAdvanced['rumor_quest_id'];
    }

  } while ($row_rsRumorsAdvanced = mysql_fetch_assoc($rsRumorsAdvanced));

}