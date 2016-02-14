<?php 

// ------------------------- //
// --- LABYRINTH OF RUIN --- //
// ------------------------- //


// If the current act is Act 1
if ($currentAct == "Act 1" && $aqs['quest_act'] == "Act 1"){

  // If the introduction has been completed
  if (count($questsCompleted) == 1){
    // In 'The Labyrinth of Ruin' the players have 2 quests to chose from after the Introduction quest, quests 26 and 27
    if($aqs['quest_id'] == "109" || $aqs['quest_id'] == "110"){
      $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 1;
      setQuestMessage("available-after", $aqs['quest_id'], $aqs['quest_req'], NULL, NULL);

    // other quests
    } else {
      $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 2;
      setQuestMessage("available-after", $aqs['quest_id'], $aqs['quest_req'], NULL, NULL);
    }
  }

  // If the introduction has been completed
  if (count($questsCompleted) == 2){
    // In 'The Labyrinth of Ruin' the players have 2 quests to chose from after the Introduction quest, quests 26 and 27
    if($aqs['quest_id'] == "111" || $aqs['quest_id'] == "112"){
      $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 1;
      setQuestMessage("available-after", $aqs['quest_id'], $aqs['quest_req'], NULL, NULL);

    // other quests
    } else {
      $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 2;
      setQuestMessage("available-after", $aqs['quest_id'], $aqs['quest_req'], NULL, NULL);
    }
  }

  if (count($questsCompleted) == 3){
    // In 'The Labyrinth of Ruin' the players have 2 quests to chose from after the Introduction quest, quests 26 and 27
    if($aqs['quest_id'] == "113" || $aqs['quest_id'] == "114"){
      $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 1;
      setQuestMessage("available-after", $aqs['quest_id'], $aqs['quest_req'], NULL, NULL);

    // other quests
    } else {
      $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 2;
      setQuestMessage("available-after", $aqs['quest_id'], $aqs['quest_req'], NULL, NULL);
    }
  }

    // If the first Act 1 quest has been completed
  if(count($questsCompleted) > 1){
    // if Gathering Foretold was chosen, update message for Honor Among Thieves
    if($aqs['quest_id'] == "109" && !in_array($aqs['quest_id'], $questsCompleted)){
      $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 0;
      setQuestMessage("other-quest", $aqs['quest_id'], $aqs['quest_req'], NULL, 110);
    }
    // if Honor Among Thieves was chosen, update message for Gathering Foretold
    if($aqs['quest_id'] == "110" && !in_array($aqs['quest_id'], $questsCompleted)){
      $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 0;
      setQuestMessage("other-quest", $aqs['quest_id'], $aqs['quest_req'], NULL, 109);
    }
  }

  if(count($questsCompleted) > 2){
    // if Gathering Foretold was chosen, update message for Honor Among Thieves
    if($aqs['quest_id'] == "111" && !in_array($aqs['quest_id'], $questsCompleted)){
      $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 0;
      setQuestMessage("other-quest", $aqs['quest_id'], $aqs['quest_req'], NULL, 112);
    }
    // if Honor Among Thieves was chosen, update message for Gathering Foretold
    if($aqs['quest_id'] == "112" && !in_array($aqs['quest_id'], $questsCompleted)){
      $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 0;
      setQuestMessage("other-quest", $aqs['quest_id'], $aqs['quest_req'], NULL, 111);
    }
  }



} else if ($currentAct == "Interlude"){
  if($aqs['quest_act'] == "Finale"){
    if(!empty($intersection1)){
      if($aqs['quest_req_type'] == "Heroes"){
        $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 1;
        setQuestMessage("heroes-won", $aqs['quest_id'], $aqs['quest_req'], NULL, NULL); 
      } else if($aqs['quest_req_type'] == "Overlord") {
        setQuestMessage("overlord-lost", $aqs['quest_id'], $aqs['quest_req'], NULL, NULL);       }
    } else if(empty($intersection1)){
      if($aqs['quest_req_type'] == "Overlord"){
        $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 1;
        setQuestMessage("overlord-won", $aqs['quest_id'], $aqs['quest_req'], NULL, NULL);   
      } else if($aqs['quest_req_type'] == "Heroes") {
        setQuestMessage("heroes-lost", $aqs['quest_id'], $aqs['quest_req'], NULL, NULL);   
      }
    }
  }
}