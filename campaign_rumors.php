<?php 

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

$rumorQuestsAv = array();
$rumorQuestsBl = array();
foreach ($availableRumorCards as $avc){
  if(in_array($avc['rumor_id'], $rumorsInPlay)){
    $rumorQuestsAv[] = $avc['rumor_quest_id'];
  }
  if(in_array($avc['rumor_id'], $blockedRumors)){
    $rumorQuestsBl[] = $avc['rumor_quest_id'];
  }
}

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