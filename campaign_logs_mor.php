<?php

// ----------------------- //
// --- Manor of Ravens --- //
// ----------------------- //

// filter out completed quests
if(!(in_array($aqs['quest_id'], $questsCompleted))){ 

  if ($currentAct == "Act 1"){

    if (count($questsCompleted) == 1){
      if($aqs['quest_id'] == "68"){
        $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 1;
        $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "Available after setup.";
      } else if ($aqs['quest_id'] == "69"){
        $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 2;
        $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "Available after " . $AvailableQuests[68]['quest_name'] . ".";
      } else if ($aqs['quest_id'] == "70"){
        $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 2;
        $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "Available after " . $AvailableQuests[69]['quest_name'] .".";
      }
    }

    if (count($questsCompleted) == 2){
      if ($aqs['quest_id'] == "69"){
        $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 1;
        $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "Available after " . $AvailableQuests[68]['quest_name'] . ".";
      } else if ($aqs['quest_id'] == "70"){
        $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 2;
        $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "Available after " . $AvailableQuests[69]['quest_name'] .".";
      }
    }

    if (count($questsCompleted) == 3){
      if ($aqs['quest_id'] == "70"){
        $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 1;
        $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "Available after " . $AvailableQuests[69]['quest_name'] .".";
      }
    }

  } else if ($currentAct == "Interlude"){

    if($aqs['quest_act'] == "Act 2"){
      if(count($questsWonByHeroesAct1) >= 2){
        if (!empty($intersection1)){
          $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 1;
          setQuestMessage("heroes-won", $aqs['quest_id'], $aqs['quest_req'], NULL, NULL);
        } else {
          setQuestMessage("heroes-lost", $aqs['quest_id'], $aqs['quest_req'], NULL, NULL);
        }
        
      } else if(count($questsWonByHeroesAct1) < 2 ){
        if (empty($intersection1)){
          $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 1;
          setQuestMessage("overlord-won", $aqs['quest_id'], $aqs['quest_req'], NULL, NULL);
        } else {
          setQuestMessage("overlord-lost", $aqs['quest_id'], $aqs['quest_req'], NULL, NULL);
        }
      }

    }

  } 

}  // in array end
