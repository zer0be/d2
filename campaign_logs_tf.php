<?php

// ----------------- //
// --- Trollfens --- //
// ----------------- //

// filter out completed quests
if(!(in_array($aqs['quest_id'], $questsCompleted))){ 

  if ($currentAct == "Act 1"){

    if (count($questsCompleted) == 1){
      if($aqs['quest_id'] == "44"){
        $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 1;
        $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "Available after setup.";
      } else if ($aqs['quest_id'] == "45"){
        $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 2;
        $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "Available after " . $AvailableQuests[44]['quest_name'] . ".";
      } else if ($aqs['quest_id'] == "46"){
        $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 2;
        $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "Available after " . $AvailableQuests[45]['quest_name'] .".";
      }
    }

    if (count($questsCompleted) == 2){
      if ($aqs['quest_id'] == "45"){
        $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 1;
        $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "Available after " . $AvailableQuests[44]['quest_name'] . ".";
      } else if ($aqs['quest_id'] == "46"){
        $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 2;
        $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "Available after " . $AvailableQuests[45]['quest_name'] .".";
      }
    }

    if (count($questsCompleted) == 3){
      if ($aqs['quest_id'] == "46"){
        $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 1;
        $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "Available after " . $AvailableQuests[45]['quest_name'] .".";
      }
    }

  } else if ($currentAct == "Interlude"){

    $questCount = $questsWonByHeroesAct1;

    if (count($questCount) >= 2){

      // Check which quest is linked to the heroes and set that to available
      if($aqs['quest_req_type'] == "Heroes"){

        $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 1;
        setQuestMessage("more-hero-wins-heroes", $aqs['quest_id'], $aqs['quest_req'], $questAct, NULL);

      // Set the message of the other quest
      } else {

        setQuestMessage("more-hero-wins-overlord", $aqs['quest_id'], $aqs['quest_req'], $questAct, NULL);

      }


    // If there are 2 or more quests won by the Overlord
    } else if (count($questCount) < 2){

      // Check which quest is linked to the overlord and set that to available
      if ($aqs['quest_req_type'] == "Overlord"){

        $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 1;
        setQuestMessage("more-overlord-wins-heroes", $aqs['quest_id'], $aqs['quest_req'], $questAct, NULL);

      // Set the message of the other quest
      } else {

        setQuestMessage("more-overlord-wins-overlord", $aqs['quest_id'], $aqs['quest_req'], $questAct, NULL);

      }

    }

  }

}  // in array end