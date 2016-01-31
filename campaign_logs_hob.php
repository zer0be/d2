<?php

// ---------------------- //
// --- HEIRS OF BLOOD --- //
// ---------------------- //

if ($currentAct == "Act 1" && $aqs['quest_act'] == "Act 1"){

  // If the introduction has been completed
  if (count($questsCompleted) == 1){
    // In 'The Labyrinth of Ruin' the players have 2 quests to chose from after the Introduction quest, quests 26 and 27
    if($aqs['quest_id'] == "86" || $aqs['quest_id'] == "87"){
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
    // if Rellegar's Rest was chosen, update message for Siege of Skytower
    if($aqs['quest_id'] == "86" && !in_array($aqs['quest_id'], $questsCompleted)){
      setQuestMessage("other-quest", $aqs['quest_id'], $aqs['quest_req'], NULL, 87);
    }
    // if Siege of Skytower was chosen, update message for Rellegar's Rest
    if($aqs['quest_id'] == "87" && !in_array($aqs['quest_id'], $questsCompleted)){
      setQuestMessage("other-quest", $aqs['quest_id'], $aqs['quest_req'], NULL, 86);
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
    } else if($aqs['quest_id'] == "91" || $aqs['quest_id'] == "92"){
      $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 2;
      setQuestMessage("available-after", $aqs['quest_id'], $aqs['quest_req'], NULL, NULL);

    // other quests
    }

  }

  if (count($questsCompleted) == 3){
    if($aqs['quest_id'] == "91" || $aqs['quest_id'] == "92"){
      $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 1;
      setQuestMessage("available-after", $aqs['quest_id'], $aqs['quest_req'], NULL, NULL);
    }

    if($aqs['quest_req_type'] == "Unique1"){

      if(!empty($intersection3)){ 
        setQuestMessage("other-quest", $aqs['quest_id'], $aqs['quest_req'], NULL, 89); // fix me: doublecheck quest id
      } else {
        setQuestMessage("unavailable-because", $aqs['quest_id'], $aqs['quest_req'], NULL, NULL);   
      }

    }
  }

  // In 'Heirs of Blood' there are also 1 shared quests between the two Act 1 branches
  
  // The Baron Returns
  if($aqs['quest_id'] == 89){

    if (count($questsCompleted) == 1){
      setQuestMessage("available-after", $aqs['quest_id'], $aqs['quest_req'], NULL, NULL);
    }

    if (count($questsCompleted) == 2){
      $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 1;
      setQuestMessage("available-after", $aqs['quest_id'], $aqs['quest_req'], NULL, NULL);
    }

    if (count($questsCompleted) == 3){
      if(in_array(88, $questsCompleted)){
        setQuestMessage("other-quest", $aqs['quest_id'], $aqs['quest_req'], NULL, 88);
      }
      
      if(in_array(90, $questsCompleted)){
        setQuestMessage("other-quest", $aqs['quest_id'], $aqs['quest_req'], NULL, 90);
      }
    }
  }

} else if ($currentAct == "Interlude"){

  if($aqs['quest_act'] == "Interlude"){
    // In 'Heirs of Blood', The winner of the third Act 1 quest selects the interlude quest
    $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 1;
    $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "Available because any Interlude quest can be chosen.";
  }

} else if ($currentAct == "Act 2" && $aqs['quest_act'] == "Act 2"){

  if (count($questsCompleted) == 5){

    if($aqs['quest_id'] == "95" || $aqs['quest_id'] == "96"){
    $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 1;
    setQuestMessage("available-after", $aqs['quest_id'], $aqs['quest_req'], NULL, NULL);

    // other quests
    } else {
      $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 2;
      setQuestMessage("available-after", $aqs['quest_id'], $aqs['quest_req'], NULL, NULL);
    }

  }

  if(count($questsCompleted) > 5){
    // if  was chosen, update message for 
    if($aqs['quest_id'] == "95" && !in_array($aqs['quest_id'], $questsCompleted)){
      setQuestMessage("other-quest", $aqs['quest_id'], $aqs['quest_req'], NULL, 96);
    }
    // if  was chosen, update message for 
    if($aqs['quest_id'] == "96" && !in_array($aqs['quest_id'], $questsCompleted)){
      setQuestMessage("other-quest", $aqs['quest_id'], $aqs['quest_req'], NULL, 95);
    }
  }


  if (count($questsCompleted) == 6){

    if($aqs['quest_id'] == "97" || $aqs['quest_id'] == "98" || $aqs['quest_id'] == "99" || $aqs['quest_id'] == "100"){

      if(!empty($intersection2)){
        if($aqs['quest_req_type'] == "Heroes"){
          $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 1;
          setQuestMessage("heroes-won", $aqs['quest_id'], $aqs['quest_req'], NULL, NULL);
        }
        if($aqs['quest_req_type'] == "Overlord"){
          setQuestMessage("overlord-lost", $aqs['quest_id'], $aqs['quest_req'], NULL, NULL);
        }
      } else {
        setQuestMessage("unavailable-because", $aqs['quest_id'], $aqs['quest_req'], NULL, NULL);   
      }
          
    } else {
      if($aqs['quest_id'] == "101" || $aqs['quest_id'] == "102"){
        $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 2;
        setQuestMessage("available-after", $aqs['quest_id'], $aqs['quest_req'], NULL, NULL);
      }
    }

  }

  if (count($questsCompleted) == 7){
    if($aqs['quest_id'] == "101" || $aqs['quest_id'] == "102"){
      $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 1;
      setQuestMessage("available-after", $aqs['quest_id'], $aqs['quest_req'], NULL, NULL);
    }

    if($aqs['quest_id'] == "97" || $aqs['quest_id'] == "98" || $aqs['quest_id'] == "99" || $aqs['quest_id'] == "100"){

      if(!empty($intersection3)){
        if($aqs['quest_req_type'] == "Heroes"){
          $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 1;
          setQuestMessage("heroes-won", $aqs['quest_id'], $aqs['quest_req'], NULL, NULL);
        }
        if($aqs['quest_req_type'] == "Overlord"){
          setQuestMessage("overlord-lost", $aqs['quest_id'], $aqs['quest_req'], NULL, NULL);
        }
      } else {
        setQuestMessage("unavailable-because", $aqs['quest_id'], $aqs['quest_req'], NULL, NULL);   
      }
          
    }
  } 


} else if ($currentAct == "Finale"){

  if($aqs['quest_act'] == "Finale"){
    $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 1;
    setQuestMessage("heroes-won", $aqs['quest_id'], $aqs['quest_req'], NULL, NULL);
  }

}
