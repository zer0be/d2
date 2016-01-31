<?php

// --------------------------- //
// --- Shadow of Nerekhall --- //
// --------------------------- //

if ($currentAct == "Act 1"){

  if($aqs['quest_act'] == "Act 1"){
    // In 'Shadow of Nerekhall' all Act 1 quest can be selected freely
    $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 1;
    $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "Available because this quest is an Act I quest.";
  }

} else if ($currentAct == "Act 2"){

  if($aqs['quest_act'] == "Act 2"){

    if (count($questsCompleted) == 5){

      if($aqs['quest_req_type'] == "Unique1"){

        $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 1;
        $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "Available after the Interlude.";

      } else if($aqs['quest_req_type'] == "Unique2" || $aqs['quest_req_type'] == "Unique3"){
        $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "Available after ";
        $i = 1;
        foreach ($aqs['quest_req'] as $reqs){
          $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] .= $AvailableQuests[$reqs]['quest_name'];
          if(count($aqs['quest_req']) > $i){
            $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] .= " or ";
            $i++;
          } else {
            $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] .= ".";
          }   
        }

      }

    }

    if (count($questsCompleted) == 6){

      if($aqs['quest_req_type'] == "Unique1"){
        if(!in_array(60, $questsCompleted)){
          $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "Unavailable because " . $AvailableQuests[61]['quest_name'] ." was played.";
        } else if(!in_array(61, $questsCompleted)){
          $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "Unavailable because " . $AvailableQuests[60]['quest_name'] ." was played.";
        }

      } else if($aqs['quest_req_type'] == "Unique2" || $aqs['quest_req_type'] == "Unique3"){

        $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 1;
        $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "Available after ";
        $i = 1;
        foreach ($aqs['quest_req'] as $reqs){
          $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] .= $AvailableQuests[$reqs]['quest_name'];
          if(count($aqs['quest_req']) > $i){
            $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] .= " or ";
            $i++;
          } else {
            $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] .= ".";
          }   
        }

      } 

    }

    if (count($questsCompleted) == 7){

      if($aqs['quest_req_type'] == "Unique1"){
        if(!in_array(60, $questsCompleted)){
          $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "Unavailable because " . $AvailableQuests[61]['quest_name'] ." was played.";
        } else if(!in_array(61, $questsCompleted)){
          $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "Unavailable because " . $AvailableQuests[60]['quest_name'] ." was played.";
        }

      } else if($aqs['quest_req_type'] == "Unique2"){
        if(!in_array(62, $questsCompleted)){
          $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "Unavailable because " . $AvailableQuests[63]['quest_name'] ." was played.";
        } else if(!in_array(63, $questsCompleted)){
          $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "Unavailable because " . $AvailableQuests[62]['quest_name'] ." was played.";
        }

      } else if($aqs['quest_req_type'] == "Unique3"){

        $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 1;
        $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "Available after ";
        $i = 1;
        foreach ($aqs['quest_req'] as $reqs){
          $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] .= $AvailableQuests[$reqs]['quest_name'];
          if(count($aqs['quest_req']) > $i){
            $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] .= " or ";
            $i++;
          } else {
            $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] .= ".";
          }   
        }

      } 

    }

  }

} else if (($currentAct == "Interlude" && $aqs['quest_act'] == "Interlude") || ($currentAct == "Finale" && $aqs['quest_act'] == "Finale")){

  if ($currentAct == "Interlude"){
    // In 'Shadow of Nerekhall', if the heroes win at least 2 quests, they play 'The True Enemy' (58), otherwise they play 'Traitors Among Us' (59)
    $questCount = $questsWonByHeroesAct1;
  } else if ($currentAct == "Finale"){
    // In 'Shadow of Nerekhall', if the heroes win at least 2 quests, they play 'Gryvorn Unleashed' (18), otherwise they play 'The Man Who Would Be King' (19)
    $questCount = $questsWonByHeroesAct2;
  }
  
  if (count($questCount) >= 2){

    if($aqs['quest_req_type'] == "Heroes"){

      $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 1;
      $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "Available because the Heroes won more than two Act I quest.";

    } else {

      $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 0;
      $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "Unavailable because the Overlord lost more than two Act I quest.";

    }

  } else if (count($questCount) < 2){

    if ($aqs['quest_req_type'] == "Overlord"){

      $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 1;
      $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "Available because the Overlord won more than two Act I quest.";

    } else {

      $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 0;
      $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "Unavailable because the Heroes lost more than two Act I quest.";

    }

  }

}