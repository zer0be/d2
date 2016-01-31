<?php

// ------------------------- //
// --- LABYRINTH OF RUIN --- //
// ------------------------- //


// If the current act is Act 1
if ($currentAct == "Act 1" && $aqs['quest_act'] == "Act 1"){

  // If the introduction has been completed
  if (count($questsCompleted) == 1){
    // In 'The Labyrinth of Ruin' the players have 2 quests to chose from after the Introduction quest, quests 26 and 27
    if($aqs['quest_id'] == "26" || $aqs['quest_id'] == "27"){
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
    if($aqs['quest_id'] == "26" && !in_array($aqs['quest_id'], $questsCompleted)){
      setQuestMessage("other-quest", $aqs['quest_id'], $aqs['quest_req'], NULL, 27);
    }
    // if Honor Among Thieves was chosen, update message for Gathering Foretold
    if($aqs['quest_id'] == "27" && !in_array($aqs['quest_id'], $questsCompleted)){
      setQuestMessage("other-quest", $aqs['quest_id'], $aqs['quest_req'], NULL, 26);
    }
  }

  // In 'The Labyrinth of Ruin' there are two 'branches' of quests, of which each has 2 unique quests
  if (count($questsCompleted) == 2){
    // If the type is Unique 1
    if($aqs['quest_req_type'] == "Unique1"){
      // if the required previous quest has been played.
      if(!empty($intersection3)){ 
        $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 1;
        setQuestMessage("available-because", $aqs['quest_id'], $aqs['quest_req'], NULL, NULL); 

      // if the required previous quest has not been played
      } else {
        setQuestMessage("unavailable-because", $aqs['quest_id'], $aqs['quest_req'], NULL, NULL);   
      }
    }


    if($aqs['quest_req_type'] == "Unique2"){
      $reqOfReq = array_intersect($AvailableQuests[$aqs['quest_req'][0]]['quest_req'], $questsCompleted);
      if (!empty($reqOfReq)){
        $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 2;
        setQuestMessage("available-after", $aqs['quest_id'], $aqs['quest_req'], NULL, NULL); 
      } else {
        $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "Unavailable because ";
        $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] .= "'<strong>" . $AvailableQuests[$aqs['quest_req'][0]]['quest_name'] . "</strong>'" . " cannot be played.";   
      }
      
    }
  }

  if (count($questsCompleted) == 3){
    // If the type is Unique 1
    if($aqs['quest_req_type'] == "Unique1"){

      if(!empty($intersection3)){ 
        setQuestMessage("other-quest", $aqs['quest_id'], $aqs['quest_req'], NULL, 32);
      } else {
        setQuestMessage("unavailable-because", $aqs['quest_id'], $aqs['quest_req'], NULL, NULL);   
      }

    }


    if($aqs['quest_req_type'] == "Unique2"){
      $reqOfReq = array_intersect($AvailableQuests[$aqs['quest_req'][0]]['quest_req'], $questsCompleted);
      if (!empty($reqOfReq)){
        $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 1;
        setQuestMessage("available-because", $aqs['quest_id'], $aqs['quest_req'], NULL, NULL); 

      // if the required previous quest has not been played
      } else {
        if(in_array(28, $questsCompleted)){
          setQuestMessage("other-quest-not", $aqs['quest_id'], $aqs['quest_req'], NULL, 30);
        }
        // if Honor Among Thieves was chosen, update message for Gathering Foretold
        if(in_array(30, $questsCompleted)){
          setQuestMessage("other-quest-not", $aqs['quest_id'], $aqs['quest_req'], NULL, 28);
        } 
      }
      
    }
  }




  // In 'The Labyrinth of Ruin' there are also 2 shared quests between the two branches
  
  // Fury of the Tempest
  if($aqs['quest_id'] == 32){

    if (count($questsCompleted) == 1){
      setQuestMessage("available-after", $aqs['quest_id'], $aqs['quest_req'], NULL, NULL);
    }

    if (count($questsCompleted) == 2){
      $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 1;
      setQuestMessage("available-after", $aqs['quest_id'], $aqs['quest_req'], NULL, NULL);
    }

    if (count($questsCompleted) == 3){
      if(in_array(28, $questsCompleted)){
        setQuestMessage("other-quest", $aqs['quest_id'], $aqs['quest_req'], NULL, 28);
      }
      // if Honor Among Thieves was chosen, update message for Gathering Foretold
      if(in_array(30, $questsCompleted)){
        setQuestMessage("other-quest", $aqs['quest_id'], $aqs['quest_req'], NULL, 30);
      }
    }
  }

  // Back from the Dead
  if($aqs['quest_id'] == 33){
  
    if (count($questsCompleted) == 1){
      setQuestMessage("available-after", $aqs['quest_id'], $aqs['quest_req'], NULL, NULL);
    }

    if (count($questsCompleted) == 2){
      $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 2;
      setQuestMessage("available-after", $aqs['quest_id'], $aqs['quest_req'], NULL, NULL);
    }

    if (count($questsCompleted) == 3){
      $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 1;
      setQuestMessage("available-because", $aqs['quest_id'], $aqs['quest_req'], NULL, NULL); 
    }

  }

} else if ($currentAct == "Interlude"){

  if($aqs['quest_act'] == "Interlude"){
    // In 'The Labyrinth of Ruin', The winner of the third Act 1 quest selects the interlude quest
    $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 1;
    $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "Available because any Interlude quest can be chosen.";
  }


} else if ($currentAct == "Act 2"){

  if($aqs['quest_act'] == "Act 2"){

    // In 'The Labyrinth of Ruin' all Act 2 quest can be selected freely
    $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 1;
    setQuestMessage("act-available", $aqs['quest_id'], $aqs['quest_req'], "Act II", NULL);

  }

} else if ($currentAct == "Finale"){

  if($aqs['quest_act'] == "Finale"){
    if(!empty($intersection2)){
      if($aqs['quest_req_type'] == "Heroes"){
        $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 1;
        $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "Available because the heroes won " . "'<strong>" . $AvailableQuests[$aqs['quest_req'][0]]['quest_name'] . "</strong>'" . ".";
      } else if($aqs['quest_req_type'] == "Overlord") {
        $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "Unavailable because the Overlord lost " . "'<strong>" . $AvailableQuests[$aqs['quest_req'][0]]['quest_name'] . "</strong>'" . ".";
      }
    } else if(empty($intersection2) && in_array(39, $questsCompleted)){
      if($aqs['quest_req_type'] == "Overlord"){
        $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 1;
        $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "Available because the Overlord won " . "'<strong>" . $AvailableQuests[$aqs['quest_req'][0]]['quest_name'] . "</strong>'" . ".";
      } else if($aqs['quest_req_type'] == "Heroes") {
        $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "Unavailable because the Heroes lost " . "'<strong>" . $AvailableQuests[$aqs['quest_req'][0]]['quest_name'] . "</strong>'" . ".";
      }
    } else if(!in_array(39, $questsCompleted)){
      if($aqs['quest_req_type'] == "Overlord"){
        $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 1;
        $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "Available because the heroes didn't attempt " . "'<strong>" . $AvailableQuests[$aqs['quest_req'][0]]['quest_name'] . "</strong>'" . ".";
      } else if($aqs['quest_req_type'] == "Heroes") {
        $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "Unavailable because the didn't attempt " . "'<strong>" . $AvailableQuests[$aqs['quest_req'][0]]['quest_name'] . "</strong>'" . ".";
      }
    }
  }

}