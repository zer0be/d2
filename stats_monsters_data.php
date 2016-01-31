<?php

// -------------------------------------------------------- //
// This file can be include where monster stats are needed. //
// -------------------------------------------------------- //

// Select all monsters from the database
$query_rsMonsters = sprintf("SELECT * FROM tbmonsters INNER JOIN tbcampaign ON tbmonsters.monster_exp_id = tbcampaign.cam_id ORDER BY monster_name");
$rsMonsters = mysql_query($query_rsMonsters, $dbDescent) or die(mysql_error());
$row_rsMonsters = mysql_fetch_assoc($rsMonsters);
$totalRows_rsMonsters = mysql_num_rows($rsMonsters);

$allMonsters = array();
do {
  // explode the string of conditions to an array
  $conditionsExp = explode(",", $row_rsMonsters['monster_conditions']);
  // store all data in an array
  $allMonsters[] = array(
    "id" => $row_rsMonsters['monster_id'],
    "name" => $row_rsMonsters['monster_name'],
    "expansion" => $row_rsMonsters['cam_name'],
    "expansion_id" => $row_rsMonsters['cam_id'],
    "traits" => explode(",", $row_rsMonsters['monster_traits']),
    "size" => $row_rsMonsters['monster_type'],
    "conditions" => $conditionsExp,
    "description" => "",
  );

} while ($row_rsMonsters = mysql_fetch_assoc($rsMonsters));


// Get all the quests that have been finished
$query_rsEachQuest = sprintf("SELECT * FROM tbquests_progress WHERE progress_quest_winner is not NULL");
$rsEachQuest = mysql_query($query_rsEachQuest, $dbDescent) or die(mysql_error());
$row_rsEachQuest = mysql_fetch_assoc($rsEachQuest);
$totalRows_rsEachQuest = mysql_num_rows($rsEachQuest);

$MonstersUsed = array();
$totalEncounters = 0;

do {
    // if there are monsters set for encounter 1, explode them to an array, and increase then encounter count
    if ($row_rsEachQuest['progress_enc1_monsters'] != NULL){
      $MonsEncounter1 = explode(",", $row_rsEachQuest['progress_enc1_monsters']);
      $totalEncounters++;

      // add each monster to the used array
      foreach ($MonsEncounter1 as $m1){
        $MonstersUsed[] = $m1;
      }
    }


    // if there are monsters set for encounter 2, explode them to an array, and increase then encounter count
    if ($row_rsEachQuest['progress_enc2_monsters'] != NULL){
      $MonsEncounter2 = explode(",", $row_rsEachQuest['progress_enc2_monsters']);
      $totalEncounters++;

      // add each monster to the used array
      foreach ($MonsEncounter2 as $m2){
        $MonstersUsed[] = $m2;
      }
    }

    // if there are monsters set for encounter 3, explode them to an array, and increase then encounter count
    if ($row_rsEachQuest['progress_enc3_monsters'] != NULL){
      $MonsEncounter3 = explode(",", $row_rsEachQuest['progress_enc3_monsters']);
      $totalEncounters++;

      // add each monster to the used array
      foreach ($MonsEncounter3 as $m3){
        $MonstersUsed[] = $m3;
      }
    }
    
} while ($row_rsEachQuest = mysql_fetch_assoc($rsEachQuest));

// count each value to get the most uses monsters
$MonstersUsedCounted = array_count_values($MonstersUsed);
// sort them
arsort($MonstersUsedCounted);

// count their percentage by encounter
$MonsterUsedPercentage = array();
foreach ($MonstersUsedCounted as $key => $muc){
  $MonsterUsedPercentage[$key] = ($muc / $totalEncounters) * 100;
}

// predefine some terms related to the monsters
$traits = array("civilized","cold","dark","hot","building","water","mountain","wilderness","cursed","cave",);
$size = array("small","medium","huge","massive",);
$conditions = array("bleeding","burning","cursed","diseased","doomed","immobilized","poisoned","stunned","weakened",);