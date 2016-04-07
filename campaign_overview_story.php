<?php

// echo '<pre>';
// var_dump($campaign);
// echo '</pre>';


$reverseQuests = array();
foreach($campaign['quests'] as $quest){
  $reverseQuests[] = array(
    "quest_id" => $quest['quest_id'],
    "quest_name" => $quest['name'],
    "quest_type" => $quest['quest_type'],
    "quest_exp_id" => $quest['quest_exp_id'],
    "winner" => $quest['winner'],
    "act" => $quest['act'],
  );
}

$reverseQuests = array_reverse($reverseQuests);

$questText = array();

foreach ($reverseQuests as $quest){
  $query_rsQuestText = sprintf("SELECT * FROM tbquests_text WHERE quest_text_quest_id = %s", GetSQLValueString($quest['quest_id'], "int"));
  $rsQuestText = mysql_query($query_rsQuestText, $dbDescent) or die(mysql_error());
  $row_rsQuestText = mysql_fetch_assoc($rsQuestText);
  $totalRows_rsQuestText = mysql_num_rows($rsQuestText);

  $questText[$row_rsQuestText['quest_text_quest_id']] = array(
    "played" => $row_rsQuestText['quest_text_played'],
    "heroes_won" => $row_rsQuestText['quest_text_heroes'],
    "overlord_won" => $row_rsQuestText['quest_text_overlord'],
    "not_played" => $row_rsQuestText['quest_text_skipped'],
  );
}

$towns = array(
  "0" => "a tavern in Arhynn",
  "2" => "Pylia Camp",
  "4" => "Ironbrick Inn",
  "29" => "the Kethiri Ruins"
);

$places = array(
  "1" => "Vigil Mines in the barony of Rhynn",
  "3" => "Valdari Marsh",
  "5" => "Manor of Ravens location",
);

function romanic_number($integer, $upcase = true)
{
  $table = array('M'=>1000, 'CM'=>900, 'D'=>500, 'CD'=>400, 'C'=>100, 'XC'=>90, 'L'=>50, 'XL'=>40, 'X'=>10, 'IX'=>9, 'V'=>5, 'IV'=>4, 'I'=>1);
  $return = '';
  while($integer > 0)
  {
      foreach($table as $rom=>$arb)
      {
          if($integer >= $arb)
          {
              $integer -= $arb;
              $return .= $rom;
              break;
          }
      }
  }

  return $return;
}

$short = $campaign['name'];
$short = strtolower($short);
$short = str_replace(" ","_",$short);
$short = preg_replace("/[^A-Za-z0-9_]/","",$short);


?>
<div class="container full">
  <div id="heroes-detail" class="clearfix">
    <div class="row no-gutters">
      <div class="hero col-sm-3 col-xs-12 text-center" style="height: 294px; background: url('img/campaigns/logos/<?php echo $short; ?>.jpg') no-repeat center; background-size: 100%;">
        <div style="padding-top: 292px"><a class="btn btn-primary btn-block" href="campaign_overview.php?urlGamingID=<?php echo $gameID_obscured ?>">Back to Overview</a></div>
      </div> <!-- close hero -->

      <div class="col-sm-9 col-xs-12">
        <div class="row no-gutters row-bg-white" style="padding: 0 15px;">
          <div class="col-sm-12">
            <h1>The Road So Far..<a href="https://www.youtube.com/watch?v=2X_2IdybTV0" target="_blank">.</a></h1>
            <div><?php
              foreach ($reverseQuests as $key => $quest){
                echo '<h3>Chapter ' . romanic_number($key + 1) . ': ' . $quest['quest_name'] . '</h3>';
                if(isset($quest['winner'])){

                  if(isset($questText[$quest['quest_id']]['played'])){
                    if($quest['quest_type'] == "Rumor"){
                      if($quest['act'] == "Act 1"){
                        echo '<p>Sidetracked by stories they overheard in ' . $towns[$campaign['camp_id']] . ', the heroes set out to the ' . $places[$quest['quest_exp_id']] . '. ' . $questText[$quest['quest_id']]['played'] . '</p>';
                      } else {
                        echo '<p>Following up on their earlier inquiry, the heroes ventured back to the ' . $places[$quest['quest_exp_id']] . '. ' . $questText[$quest['quest_id']]['played'] . '</p>';
                      }

                    } else {
                      echo '<p>' . $questText[$quest['quest_id']]['played'] . '</p>';
                    }
                  } else {
                    echo '<p>No Story text for this quest yet.</p>';
                  }

                  if($quest['winner'] == "Heroes Win"){
                    if(isset($questText[$quest['quest_id']]['heroes_won'])){
                        echo '<p>' . $questText[$quest['quest_id']]['heroes_won'] . '</p>';
                    } else {
                      echo '<p>No victory story text for this quest yet.</p>';
                    }
                  } else if($quest['winner'] == "Overlord Wins"){
                    if(isset($questText[$quest['quest_id']]['overlord_won'])){
                      echo '<p>' . $questText[$quest['quest_id']]['overlord_won'] . '</p>';
                    } else {
                      echo '<p>No victory story text for this quest yet.</p>';
                    }
                  }

                }

              } ?>
            </div>
            <!-- <p class="text-muted text-center"><small>Thanks to Volkren and Rhinor8 for some of the texts.</small></p> -->
          </div>
        </div>

      </div>

    </div>
  </div> <!-- close heroes -->
</div> <!-- close wrapper -->