<?php 

function calcQuestWins($overlord, $heroes){
  $total = $overlord + $heroes;

  if ($overlord != 0){
    $OverlordPerc = ($overlord / $total) * 100;
    $olText = 'Overlord: ' . $overlord . ' Win(s)';
  } 

  if ($heroes != 0){
    $HeroPerc = ($heroes / $total) * 100;
    $hText = 'Heroes: ' . $heroes . ' Win(s)';
  } 

  if ($heroes == 0){
    $HeroPerc = 0;
    $hText = 'Heroes: ' . $heroes . ' Win(s)';
  }

  if ($overlord == 0){
     $OverlordPerc = 0;
    $olText = 'Overlord: ' . $overlord .' Win(s)';
  }

  $QuestWins = array(
    "OverlordPerc" => $OverlordPerc,
    "OverlordText" => $olText,
    "HeroPerc" => $HeroPerc,
    "HeroText" => $hText,
  );

  return $QuestWins;
}

// Get the quests
//$query_rsAllQuests = sprintf("SELECT * FROM tbquests WHERE quest_expansion_id = %s ORDER BY quest_order ASC", GetSQLValueString(0, "int"));
$query_rsAllQuests = sprintf("SELECT * FROM tbquests LEFT JOIN tbcampaign ON tbquests.quest_expansion_id = tbcampaign.cam_id ORDER BY quest_expansion_id ASC");
$rsAllQuests = mysql_query($query_rsAllQuests, $dbDescent) or die(mysql_error());
$row_rsAllQuests = mysql_fetch_assoc($rsAllQuests);
$totalRows_rsAllQuests = mysql_num_rows($rsAllQuests);

$statsArray = array();
do {

  $query_rsEachQuest = sprintf("SELECT * FROM tbquests_progress WHERE progress_quest_id = %s", GetSQLValueString($row_rsAllQuests['quest_id'], "int"));
  $rsEachQuest = mysql_query($query_rsEachQuest, $dbDescent) or die(mysql_error());
  $row_rsEachQuest = mysql_fetch_assoc($rsEachQuest);
  $totalRows_rsEachQuest = mysql_num_rows($rsEachQuest);

  $statsArray[$row_rsAllQuests['quest_id']] = array(
    "quest_name" => $row_rsAllQuests['quest_name'],
    "quest_campaign" => $row_rsAllQuests['cam_name'],
    "expansion_id" => $row_rsAllQuests['quest_expansion_id'],
    "act" => $row_rsAllQuests['quest_act'],
    "hero_wins" => 0,
    "overlord_wins" => 0,
    "count" => 0,
    "time" => array(
      "0" => 0,
      "30" => 0,
      "60" => 0,
      "90" => 0,
      "120" => 0,
      "150" => 0,
      "180" => 0,
      "240" => 0,
      "300" => 0,
      "360" => 0,
      "999" => 0,
    ),
    "selected_monsters_enc1" => array(
      'loss' => NULL,
      'win' => NULL,
    ),
    "selected_monsters_enc2" => array(
      'loss' => NULL,
      'win' => NULL,
    ),
    "selected_monsters_enc3" => array(
      'loss' => NULL,
      'win' => NULL,
    ),
  );

  do {

    if (isset($row_rsEachQuest['progress_quest_winner'])){
      if (isset($row_rsEachQuest['progress_quest_time'])){
        $statsArray[$row_rsAllQuests['quest_id']]['time'][$row_rsEachQuest['progress_quest_time']] += 1;
      }


      if ($row_rsEachQuest['progress_quest_winner'] == "Heroes Win"){
        $statsArray[$row_rsAllQuests['quest_id']]['hero_wins'] += 1;
        $statsArray[$row_rsAllQuests['quest_id']]['count'] += 1;
      }
      if ($row_rsEachQuest['progress_quest_winner'] == "Overlord Wins"){
        $statsArray[$row_rsAllQuests['quest_id']]['overlord_wins'] += 1;
        $statsArray[$row_rsAllQuests['quest_id']]['count'] += 1;
      }

      if (isset($row_rsEachQuest['progress_quest_winner'])){
        if ($row_rsEachQuest['progress_enc1_monsters'] != NULL){
          if ($row_rsEachQuest['progress_quest_winner'] == "Heroes Win"){
            $statsArray[$row_rsAllQuests['quest_id']]['selected_monsters_enc1']['loss'] = $statsArray[$row_rsAllQuests['quest_id']]['selected_monsters_enc1']['loss'] . $row_rsEachQuest['progress_enc1_monsters'] . ',';
          } else if ($row_rsEachQuest['progress_quest_winner'] == "Overlord Wins"){
            $statsArray[$row_rsAllQuests['quest_id']]['selected_monsters_enc1']['win'] = $statsArray[$row_rsAllQuests['quest_id']]['selected_monsters_enc1']['win'] . $row_rsEachQuest['progress_enc1_monsters'] . ',';
          }
        }
      }

      if (isset($row_rsEachQuest['progress_enc2_winner']) || (isset($row_rsEachQuest['progress_enc1_winner']) && isset($row_rsEachQuest['progress_quest_winner']))){
        if ($row_rsEachQuest['progress_enc2_monsters'] != NULL){
          if ($row_rsEachQuest['progress_quest_winner'] == "Heroes Win"){
            $statsArray[$row_rsAllQuests['quest_id']]['selected_monsters_enc2']['loss'] = $statsArray[$row_rsAllQuests['quest_id']]['selected_monsters_enc2']['loss'] . $row_rsEachQuest['progress_enc2_monsters'] . ',';
          } else if ($row_rsEachQuest['progress_quest_winner'] == "Overlord Wins"){
            $statsArray[$row_rsAllQuests['quest_id']]['selected_monsters_enc2']['win'] = $statsArray[$row_rsAllQuests['quest_id']]['selected_monsters_enc2']['win'] . $row_rsEachQuest['progress_enc2_monsters'] . ',';
          }
        }
      }

      if (isset($row_rsEachQuest['progress_enc2_winner']) && isset($row_rsEachQuest['progress_quest_winner'])){
        if ($row_rsEachQuest['progress_enc3_monsters'] != NULL){
          if ($row_rsEachQuest['progress_quest_winner'] == "Heroes Win"){
            $statsArray[$row_rsAllQuests['quest_id']]['selected_monsters_enc3']['loss'] = $statsArray[$row_rsAllQuests['quest_id']]['selected_monsters_enc3']['loss'] . $row_rsEachQuest['progress_enc3_monsters'] . ',';
          } else if ($row_rsEachQuest['progress_quest_winner'] == "Overlord Wins"){
            $statsArray[$row_rsAllQuests['quest_id']]['selected_monsters_enc3']['win'] = $statsArray[$row_rsAllQuests['quest_id']]['selected_monsters_enc3']['win'] . $row_rsEachQuest['progress_enc3_monsters'] . ',';
          }
        }
      }
    }
    
  } while ($row_rsEachQuest = mysql_fetch_assoc($rsEachQuest));

  // create an array that lists each encounter.
  $encountersArray = array();
  $encountersArray[] = array(
    "encounter_variable" => "selected_monsters_enc1",
    "encounter_outcome" => "loss",
    "encounter_monsters" => "quest_enc1_monsters",
    "encounter_italic" => "hero_wins",
  );
  $encountersArray[] = array(
    "encounter_variable" => "selected_monsters_enc1",
    "encounter_outcome" => "win",
    "encounter_monsters" => "quest_enc1_monsters",
    "encounter_italic" => "overlord_wins",
  );
  $encountersArray[] = array(
    "encounter_variable" => "selected_monsters_enc2",
    "encounter_outcome" => "loss",
    "encounter_monsters" => "quest_enc2_monsters",
    "encounter_italic" => "hero_wins",
  );
  $encountersArray[] = array(
    "encounter_variable" => "selected_monsters_enc2",
    "encounter_outcome" => "win",
    "encounter_monsters" => "quest_enc2_monsters",
    "encounter_italic" => "overlord_wins",
  );
  $encountersArray[] = array(
    "encounter_variable" => "selected_monsters_enc3",
    "encounter_outcome" => "loss",
    "encounter_monsters" => "quest_enc3_monsters",
    "encounter_italic" => "hero_wins",
  );
  $encountersArray[] = array(
    "encounter_variable" => "selected_monsters_enc3",
    "encounter_outcome" => "win",
    "encounter_monsters" => "quest_enc3_monsters",
    "encounter_italic" => "overlord_wins",
  );

  foreach ($encountersArray as $ea){
    // If there are saved monsters found for this encounter
    if ($statsArray[$row_rsAllQuests['quest_id']][$ea['encounter_variable']][$ea['encounter_outcome']] != NULL){

      // Trim the last extra comma off
      $topMonsters_trim = rtrim($statsArray[$row_rsAllQuests['quest_id']][$ea['encounter_variable']][$ea['encounter_outcome']], ",");
      // Explode the string to array
      $topMonsters_expl = explode(',',$topMonsters_trim);
      // Count the number of times each monster appears
      $topMonsters_count = array_count_values($topMonsters_expl);
      // Sort the array by values
      arsort($topMonsters_count);
      // Count how many monsters the quest has (Predefined + Open groups)
      $countMonsters = explode(',',$row_rsAllQuests[$ea['encounter_monsters']]);
      $count = count($countMonsters);
      // Cut of the array at that amount
      $topMonsters_countSlice = array_slice($topMonsters_count, 0, $count, true);
      // Because we sorted the values, the keys are now messed up, and using multisort doesn't seem to keep the keys because they are numerical.
      // So we copy them to a new array and sort that one by keys
      $topMonsters_countSliceResort = $topMonsters_countSlice;
      ksort($topMonsters_countSliceResort);
      // Then we remove the duplicates from our original array
      $topMonsters_countSlice = array_unique($topMonsters_countSlice);
      // We then loop through the shortened array with values, and for each through the original array that is now sorted by keys.
      // Each key that returns for a certain value goes into a new array, which is then correctly sorted. Yay!
      $topmonsters_resorted = array();
      foreach($topMonsters_countSlice as $csl){
        foreach ($topMonsters_countSliceResort as $rkey => $rvalue){
          if ($csl == $rvalue){
            $topmonsters_resorted[$rkey] = $rvalue;
          }
        }
      }

      $topMonsters = "";

      // Loop through the sorted array, and get the monsters (also by looping through them, maybe not the best way?)
      foreach ($topmonsters_resorted as $mrskey => $mrsval){
        foreach ($allMonsters as $am){
          if ($mrskey == $am['id'])
            if (in_array($mrskey, $countMonsters)){
              $topMonsters = $topMonsters . "<i>" . $am['name'] . "</i>, ";
            } else {
              $topMonsters = $topMonsters . $am['name'] . "<sup>(" . $mrsval . ")</sup>" . ", ";
            }
            
        }
      }

      // Trim the last extra comma off
      $topMonsters = rtrim($topMonsters, ", ");
      // Update the stats array with the new list of monsters
      $statsArray[$row_rsAllQuests['quest_id']][$ea['encounter_variable']][$ea['encounter_outcome']] = $topMonsters;
    }
  }

} while ($row_rsAllQuests = mysql_fetch_assoc($rsAllQuests));


?>

