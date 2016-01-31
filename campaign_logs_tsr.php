<?php 

// ----------------------- //
// --- THE SHADOW RUNE --- //
// ----------------------- //
  
// In 'The Shadow Rune', during act 1 all Act 1 quests can be selected freely, so we activate all Act I quests, and their message gets set to the 'act1-available' message
if ($currentAct == "Act 1" && $aqs['quest_act'] == "Act 1"){

    $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 1;
    setQuestMessage("act-available", $aqs['quest_id'], $aqs['quest_req'], "Act I", NULL);

} 



// In 'The Shadow Rune' the Act 2 quests the heroes can select are the 'Hero' follow up ones of the ones they won during Act 1 
// + the 'Overlord' follow up quest for those they did not attempt
else if ($currentAct == "Act 2" && $aqs['quest_act'] == "Act 2"){
  
  // if the heroes won the required act 1 quest..
  if(!empty($intersection1)){

    // ..and the quest has heroes as required winner, make it active
    if($aqs['quest_req_type'] == "Heroes"){

      $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 1;
      setQuestMessage("heroes-won", $aqs['quest_id'], $aqs['quest_req'], NULL, NULL);

    // ..and the quest has overlord as required winner
    } else if($aqs['quest_req_type'] == "Overlord"){
      setQuestMessage("overlord-lost", $aqs['quest_id'], $aqs['quest_req'], NULL, NULL);
    }

  // if the overlord won the required act 1 quest, and the quest has overlord as required winner
  } else if(empty($intersection1) && in_array($aqs['quest_req'][0], $questsCompleted)){
    // ..and the quest has heroes as required winner, make it active
    if($aqs['quest_req_type'] == "Heroes"){

      setQuestMessage("heroes-lost", $aqs['quest_id'], $aqs['quest_req'], NULL, NULL);

    // ..and the quest has overlord as required winner
    } else if($aqs['quest_req_type'] == "Overlord"){
      $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 1;
      setQuestMessage("overlord-won", $aqs['quest_id'], $aqs['quest_req'], NULL, NULL);
    }

  // if the quest was not played
  } else if(empty($intersection1) && !in_array($aqs['quest_req'][0], $questsCompleted)){
    // ..and the quest has heroes as required winner
    if($aqs['quest_req_type'] == "Heroes"){

      setQuestMessage("heroes-attempt", $aqs['quest_id'], $aqs['quest_req'], NULL, NULL);

    // ..and the quest has overlord as required winner
    } else if($aqs['quest_req_type'] == "Overlord"){
      $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 1;
      setQuestMessage("overlord-attempt", $aqs['quest_id'], $aqs['quest_req'], NULL, NULL);
    }


  }

} 

// If the current step is the interlude or the Finale
else if (($currentAct == "Interlude" && $aqs['quest_act'] == "Interlude") || ($currentAct == "Finale" && $aqs['quest_act'] == "Finale")){

  if ($currentAct == "Interlude"){
    // In 'The Shadow Rune', if the heroes win at least 2 quests, they play 'The Shadow Vault' (16), otherwise they play 'The Overlord Revealed' (17)
    $questCount = $questsWonByHeroesAct1;
  } else if ($currentAct == "Finale"){
    // In 'The Shadow Rune', if the heroes win at least 2 quests, they play 'Gryvorn Unleashed' (18), otherwise they play 'The Man Who Would Be King' (19)
    $questCount = $questsWonByHeroesAct2;
  }
  
  // If there are 2 or more quests won by the Heroes
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