<?php

// Make an array with completed quests/rumors for options
$questsCompleted = array();
$rumorsCompleted = array();
$rumorsCompletedAct2 = array();
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
    if ((($qos['winner'] == 'Heroes Win') || ($qos['winner'] == 'Overlord Wins')) && $qos['act'] == "Act 2"){
      $rumorsCompletedAct2[] = $qos['quest_id'];
    }
  }
}

// Where are we in the Campaign?
$currentAct = "Act 1";
$currentActItems = "Act 1";
$questAct = "Act I";

if (count($questsCompleted) == 4){
  $currentAct = "Interlude";
  $questAct = "Act I";
}

if (count($questsCompleted) > 4){
  $currentAct = "Act 2";
  $questAct = "Act II";
}

if (count($questsCompleted) > 5 || count($rumorsCompletedAct2) > 0){
  $currentActItems = "Act 2";
  $questAct = "Act II";
}

if (count($questsCompleted) > 7){
  $currentAct = "Finale";
  $currentActItems = "Act 2";
  $questAct = "Act II";
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
// 

function setQuestMessage($type, $questid, $required, $act, $diffquest){
  global $AvailableQuests;
  global $questsCompleted;
  $questMessage = "";

  if($type == "act-available"){
    $questMessage .= "Available because this quest is an " . $act . " quest.";
  }

  if($type == "introduction-available"){
    $questMessage .= "Available after the Introduction quest.";
  }

  if($type == "more-hero-wins-heroes"){
    $questMessage .= "Available because the Heroes won more than two " . $act . " quests.";
  }
  if($type == "more-hero-wins-overlord"){
     $questMessage .= "Unavailable because the Overlord lost more than two " . $act . " quests.";
  }
  if($type == "more-overlord-wins-heroes"){
    $questMessage .= "Available because the Overlord won more than two " . $act . " quests.";
  }
  if($type == "more-overlord-wins-overlord"){
    $questMessage .= "Unavailable because the Heroes lost more than two " . $act . " quests.";
  }

  if($type == "heroes-won"){
    $questMessage .= "Available because the Heroes won ";
  }
  if($type == "heroes-lost"){
    $questMessage .= "Unavailable because the Heroes lost ";
  }
  
  if($type == "overlord-won"){
    $questMessage .= "Available because the Overlord won ";
  }
  if($type == "overlord-lost"){
    $questMessage .= "Unavailable because the Overlord lost ";
  }

  if($type == "heroes-won" || $type == "heroes-lost" || $type == "overlord-won" || $type == "overlord-lost"){
    foreach ($required as $reqs){
      if (in_array($reqs, $questsCompleted)){
        $questMessage .= "'<strong>" . $AvailableQuests[$reqs]['quest_name'] . "</strong>'" . ".";
      }
    }
  }

  if($type == "heroes-attempt"){
    $questMessage .= "Unavailable because the Heroes didn't attempt ";
  }
  if($type == "overlord-attempt"){
    $questMessage .= "Available because the Heroes didn't attempt ";
  }

  if($type == "heroes-attempt" || $type == "overlord-attempt"){
    foreach ($required as $reqs){
      if (!in_array($reqs, $questsCompleted)){
        $questMessage .= "'<strong>" . $AvailableQuests[$reqs]['quest_name'] . "</strong>'" . ".";
      }
    }
  }

  if($type == "available-after"){
    $questMessage .= "Available after ";
    $i = count($required);
    foreach ($required as $reqs){
      $questMessage .= "'<strong>" . $AvailableQuests[$reqs]['quest_name'] . "</strong>'";
      if($i > 2){
        $questMessage .= ", ";
      } else if($i == 2){
        $questMessage .= " or ";
      } else {
        $questMessage .= ".";
      }   
      $i--;
    }

  }

  if($type == "available-because"){
    $questMessage .= "Available because ";
    $i = count($required);
    foreach ($required as $reqs){
      $questMessage .= "'<strong>" . $AvailableQuests[$reqs]['quest_name'] . "</strong>'";
      if($i > 2){
        $questMessage .= ", ";
      } else if($i == 2){
        $questMessage .= " or ";
      } else {
        $questMessage .= " was played.";
      }   
      $i--;
    }
  }

  if($type == "unavailable-because"){
    $questMessage .= "Unavailable because ";
    $i = count($required);
    foreach ($required as $reqs){
      $questMessage .= "'<strong>" . $AvailableQuests[$reqs]['quest_name'] . "</strong>'";
      if($i > 2){
        $questMessage .= ", ";
      } else if($i == 2){
        $questMessage .= " or ";
      } else {
        $questMessage .= " was not played.";
      }   
      $i--;
    }
  }

  if($type == "other-quest"){
    $questMessage .= "Unavailable because " . "'<strong>" . $AvailableQuests[$diffquest]['quest_name'] . "</strong>'" ." was played.";
  }
  if($type == "other-quest-not"){
    $questMessage .= "Unavailable because " . "'<strong>" . $AvailableQuests[$diffquest]['quest_name'] . "</strong>'" ." was not played.";
  }

  $AvailableQuests[$questid]['quest_status']['message'] = $questMessage;

}

include 'campaign_rumors.php';



// Available Quests

$query_rsAvQuestList = sprintf("SELECT * FROM tbquests WHERE quest_expansion_id = %s ORDER BY quest_order ASC", GetSQLValueString($selCampaign, "int"));
$rsAvQuestList = mysql_query($query_rsAvQuestList, $dbDescent) or die(mysql_error());
$row_rsAvQuestList = mysql_fetch_assoc($rsAvQuestList);
$totalRows_rsAvQuestList = mysql_num_rows($rsAvQuestList);


$AvailableQuests = array();
do {

  $shortl = $row_rsAvQuestList['quest_name'];
  $shortl = strtolower($shortl);
  $shortl = str_replace(" ","_",$shortl);
  $shortl = preg_replace("/[^A-Za-z0-9_]/","",$shortl);

  $AvailableQuests[intval($row_rsAvQuestList['quest_id'])] = array(
    "quest_id" => intval($row_rsAvQuestList['quest_id']),
    "quest_type" => "quest",
    "quest_name" => $row_rsAvQuestList['quest_name'],
    "quest_act" => $row_rsAvQuestList['quest_act'],
    "quest_req_type" => $row_rsAvQuestList['quest_req_type'],
    "quest_req" => explode(",", $row_rsAvQuestList['quest_req']),
    "quest_img" => $shortl . ".jpg",
    "quest_description" => $row_rsAvQuestList['quest_description'],
    "quest_status" => array(
      "available" => 0,
      "message" => '',
    ),
  );

} while ($row_rsAvQuestList = mysql_fetch_assoc($rsAvQuestList));


// Available Rumor Quests

$query_rsAvRumorList = sprintf("SELECT * FROM tbquests LEFT JOIN tbcampaign ON quest_expansion_id = cam_id WHERE quest_expansion_id IN ($selExpansions) AND quest_expansion_id != %s AND cam_type != %s AND cam_type != %s ORDER BY quest_order ASC", GetSQLValueString($row_rsGroupCampaign['game_camp_id'], "int"), GetSQLValueString("full", "text"), GetSQLValueString("book", "text"));
$rsAvRumorList = mysql_query($query_rsAvRumorList, $dbDescent) or die(mysql_error());
$row_rsAvRumorList = mysql_fetch_assoc($rsAvRumorList);
$totalRows_rsAvRumorList = mysql_num_rows($rsAvRumorList);

//$availableRumors = array();

do {

  $shortl = $row_rsAvRumorList['quest_name'];
  $shortl = strtolower($shortl);
  $shortl = str_replace(" ","_",$shortl);
  $shortl = preg_replace("/[^A-Za-z0-9_]/","",$shortl);

  $AvailableQuests[intval($row_rsAvRumorList['quest_id'])] = array(
    "quest_id" => intval($row_rsAvRumorList['quest_id']),
    "quest_type" => "rumor",
    "quest_name" => $row_rsAvRumorList['quest_name'],
    "quest_act" => $row_rsAvRumorList['quest_act'],
    "quest_req_type" => $row_rsAvRumorList['quest_req_type'],
    "quest_req" => explode(",", $row_rsAvRumorList['quest_req']),
    "quest_img" => $shortl . ".jpg",
    "quest_description" => $row_rsAvRumorList['quest_description'],
    "quest_status" => array(
      "available" => 0,
      "message" => '',
    ),
  );

} while ($row_rsAvRumorList = mysql_fetch_assoc($rsAvRumorList));


$questOptions = array();

$questSelect = array();
$questSelectWhy = array();


foreach ($AvailableQuests as $aqs) {

$intersection1 = array_intersect($aqs['quest_req'], $questsWonByHeroesAct1);
$intersection2 = array_intersect($aqs['quest_req'], $questsWonByHeroesAct2);
$intersection3 = array_intersect($aqs['quest_req'], $questsCompleted);

$intersectionH = array_intersect($aqs['quest_req'], $rumorsWonByHeroesAct1);
$intersectionO = array_intersect($aqs['quest_req'], $rumorsWonByOverlordAct1);
$intersectionAll = array_intersect($aqs['quest_req'], $rumorsCompleted);



  // New System //

  if(!(in_array($aqs['quest_id'], $questsCompleted)) && !(in_array($aqs['quest_id'], $rumorsCompleted))){

    if($aqs['quest_type'] == "quest"){


      // --- THE SHADOW RUNE --- //
      include 'campaign_logs_tsr.php';

      // --- LABYRINTH OF RUIN --- //
      include 'campaign_logs_lor.php';

      // --- SHADOW OF NEREKHALL --- //
      include 'campaign_logs_son.php';

      // --- HEIRS OF BLOOD --- //
      include 'campaign_logs_hob.php';

      // --- LAIR OF THE WYRM --- //
      include 'campaign_logs_lotw.php';

      // --- THE TROLLFENS --- //
      include 'campaign_logs_tf.php';

      // --- THE TROLLFENS --- //
      include 'campaign_logs_mor.php';

    } else {

      // -------------------- //
      // --- RUMOR QUESTS --- //
      // -------------------- //

      if ($currentAct == "Act 1" || $currentAct == "Interlude"){
        if(in_array($aqs['quest_id'], $rumorQuestsAv)){

          $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 1;
          $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "Available because the card for this Rumor Quest is in play.";

        } else if(in_array($aqs['quest_id'], $rumorQuestsBl)){

          $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 0;
          $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "Unavailable because a different card from this expansion has been played.";

        } else {

          $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 0;
          $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "Unavailable because the card for this Rumor Quest is not in play.";

        }

      } else if ($currentAct == "Act 2") {
        
          if($aqs['quest_act'] == "Act 2"){

            //$rumorOptions[] = '<option value="' . $avr['quest_id'] . '">' . $avr['quest_name'] . '</option>';
            if(!empty($intersectionH) && $aqs['quest_req_type'] == "Heroes"){
              $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 1;
              $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "Available because the Heroes won ";
              foreach ($aqs['quest_req'] as $reqs){
                if (in_array($reqs, $rumorsCompleted)){
                  $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] .= $AvailableQuests[$reqs]['quest_name'] . ".";
                }     
              }
            } else if(empty($intersectionH) && $aqs['quest_req_type'] == "Heroes"){
              $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 0;
              $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "Unavailable because the Heroes lost ";
              foreach ($aqs['quest_req'] as $reqs){
                if (in_array($reqs, $rumorsCompleted)){
                  $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] .= $AvailableQuests[$reqs]['quest_name'] . ".";
                }     
              }
            } else if(!empty($intersectionO) && $aqs['quest_req_type'] == "Overlord"){
              $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 1;
              $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "Available because the Overlord won ";
              foreach ($aqs['quest_req'] as $reqs){
                if (in_array($reqs, $rumorsCompleted)){
                  $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] .= $AvailableQuests[$reqs]['quest_name'] . ".";
                }     
              }
            } else if(empty($intersectionO) && $aqs['quest_req_type'] == "Overlord"){
              $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 0;
              $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "Unavailable because the Overlord lost ";
              foreach ($aqs['quest_req'] as $reqs){
                if (in_array($reqs, $rumorsCompleted)){
                  $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] .= $AvailableQuests[$reqs]['quest_name'] . ".";
                }     
              }
            } else if(!empty($intersectionAll) && $aqs['quest_req_type'] == "All"){
              $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 1;
              $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "Available because it is the follow up to ";
              foreach ($aqs['quest_req'] as $reqs){
                if (in_array($reqs, $rumorsCompleted)){
                  $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] .= $AvailableQuests[$reqs]['quest_name'] . ".";
                }     
              }
            } 
          }

      }
      
    }

  } else {
    if($aqs['quest_type'] == "quest"){
      $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 0;
      $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "This quest has already been completed.";
    } else {
      $AvailableQuests[$aqs['quest_id']]['quest_status']['available'] = 0;
      $AvailableQuests[$aqs['quest_id']]['quest_status']['message'] = "This rumor has already been completed.";
    }
    

  }

} // foreach end



// echo '<pre>';
// var_dump($AvailableQuests);
// echo '</pre>';

// echo '<pre>';
// var_dump($campaign['quests']);
// echo '</pre>';

// var_dump($rumorsCompleted);

?>