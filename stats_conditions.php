<?php

//-----------------------//
//remove me after include//
//-----------------------//

//include the db
require_once('Connections/dbDescent.php'); 

//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

//include functions
include 'includes/function_logout.php';
include 'includes/function_createProgressBar.php';
include 'includes/function_getSQLValueString.php';
include 'includes/function_getCampaignLabel.php';

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

//select the database
mysql_select_db($database_dbDescent, $dbDescent);

// Get Games

$query_rsAllGames = sprintf("SELECT * FROM tbgames ORDER BY game_id ASC");
$rsAllGames = mysql_query($query_rsAllGames, $dbDescent) or die(mysql_error());
$row_rsAllGames = mysql_fetch_assoc($rsAllGames);
$totalRows_rsAllGames = mysql_num_rows($rsAllGames);

$totalGames = 0;
do {
  $totalGames++; 
} while ($row_rsAllGames = mysql_fetch_assoc($rsAllGames));


// Select Monsters
$query_rsMonsters = sprintf("SELECT * FROM tbmonsters INNER JOIN tbcampaign ON tbmonsters.monster_exp_id = tbcampaign.cam_id ORDER BY monster_name");
$rsMonsters = mysql_query($query_rsMonsters, $dbDescent) or die(mysql_error());
$row_rsMonsters = mysql_fetch_assoc($rsMonsters);
$totalRows_rsMonsters = mysql_num_rows($rsMonsters);

$allMonsters = array();
do {

  $conditions = explode(",", $row_rsMonsters['monster_conditions']);
  $allMonsters[] = array(
    "id" => $row_rsMonsters['monster_id'],
    "name" => $row_rsMonsters['monster_name'],
    "expansion" => $row_rsMonsters['cam_name'],
    "traits" => explode(",", $row_rsMonsters['monster_traits']),
    "size" => $row_rsMonsters['monster_type'],
    "conditions" => $conditions,
  );

} while ($row_rsMonsters = mysql_fetch_assoc($rsMonsters));

$query_rsEachQuest = sprintf("SELECT * FROM tbquests_progress WHERE progress_quest_winner is not NULL");
$rsEachQuest = mysql_query($query_rsEachQuest, $dbDescent) or die(mysql_error());
$row_rsEachQuest = mysql_fetch_assoc($rsEachQuest);
$totalRows_rsEachQuest = mysql_num_rows($rsEachQuest);

$MonstersUsed = array();
$totalEncounters = 0;

do {
    if ($row_rsEachQuest['progress_enc1_monsters'] != NULL){
      $MonsEncounter1 = explode(",", $row_rsEachQuest['progress_enc1_monsters']);
      $totalEncounters++;
    }

    if ($row_rsEachQuest['progress_enc2_monsters'] != NULL){
      $MonsEncounter2 = explode(",", $row_rsEachQuest['progress_enc2_monsters']);
      $totalEncounters++;
    }

    foreach ($MonsEncounter1 as $m1){
      $MonstersUsed[] = $m1;
    }
    if(isset($MonsEncounter2)){
      foreach ($MonsEncounter2 as $m2){
        $MonstersUsed[] = $m2;
      }
    }
    
} while ($row_rsEachQuest = mysql_fetch_assoc($rsEachQuest));

$MonstersUsedCounted = array_count_values($MonstersUsed);

// echo '<pre>';
// var_dump($MonstersUsedCounted);
// echo '</pre>';


arsort($MonstersUsedCounted);

// echo '<pre>';
// var_dump($MonstersUsedCountedSort);
// echo '</pre>';

$traits = array("civilized","cold","dark","hot","building","water","mountain","wilderness","cursed","cave",);
$size = array("small","medium","huge","massive",);



?>

<html>
  <head>
    <link rel="stylesheet" type="text/css" href="content.css">
    <title>Descent 2nd Edition Campaign Tracker</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
  </head>
  <body>
    <?php 
      include 'navbar.php'; 
      include 'banner.php';
    ?>

    <div class="container grey">

      <div class="row">
        <div id="" class="col-md-6">

          <div class="row">
            <div class="col-md-12">&nbsp;</div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h2 class="panel-title">Most popular monsters</h2>
                </div>

                <div class="panel-body"><?php 
                  foreach ($MonstersUsedCounted as $key => $muc){
                    $monsterPerc = ($muc / $totalEncounters) * 100;
                    foreach($allMonsters as $am){
                      if ($am['id'] == $key && $am['size'] != "lieutenant"){ ?>

                        <div class="row stats-row">
                          <div class="col-xs-12">
                            <div class="row"> 
                              <div class="col-md-6"> 
                                <p><strong><?php echo $am['name']; ?></strong></p>
                              </div>
                              <div class="col-md-6 text-right"><?php
                                getCampaignLabel($am['expansion'], "normal"); ?>
                              </div>
                            </div>
                            <div class="row"> 
                              <div class="col-md-12">
                                <div class="progress">
                                  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo round($monsterPerc);?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo round($monsterPerc);?>%;">
                                    <span class="sr-only"><?php echo round($monsterPerc);?>%</span>
                                    <?php echo round($monsterPerc);?>% of Encounters
                                  </div>
                                </div>
                              </div>
                            </div>
                            <p></p>
                          </div>
                        </div><?php
                      }
                    }
                  } ?>
                </div>
              </div> 
            </div>
          </div>
        </div>

        <div id="" class="col-md-6">

          <div class="row">
            <div class="col-md-12">&nbsp;</div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h2 class="panel-title">Monster Traits</h2>
                </div>

                <div class="panel-body"><?php 
                  foreach($traits as $t){ ?>
                    <div class="row stats-row">
                      <div class="col-xs-12">
                        <div class="row"> 
                          <div class="col-md-6"> 
                            <p><strong><?php echo ucwords($t); ?></strong></p>
                          </div>
                          <div class="col-md-6 text-right"> 
                          </div>
                        </div>
                        <div class="row"> 
                          <div class="col-md-12">
                            <div class="row"><?php
                              foreach ($allMonsters as $am){
                                if (in_array($t, $am['traits'])){
                                  echo '<div class="col-sm-4 col-xs-6">';
                                  getCampaignLabel($am['expansion'], "mini");
                                  echo " " . $am['name'];
                                  echo '</div>';
                                }
                              } ?>
                            </div>
                          </div>
                        </div>
                        <p></p>
                      </div>
                    </div><?php
                  } ?>
                </div>
              </div> 
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">&nbsp;</div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h2 class="panel-title">Monster Size</h2>
                </div>

                <div class="panel-body"><?php 
                  foreach($size as $s){ ?>
                    <div class="row stats-row">
                      <div class="col-xs-12">
                        <div class="row"> 
                          <div class="col-md-6"> 
                            <p><strong><?php echo ucwords($s); ?></strong></p>
                          </div>
                          <div class="col-md-6 text-right"> 
                          </div>
                        </div>
                        <div class="row"> 
                          <div class="col-md-12">
                            <div class="row"><?php
                              foreach ($allMonsters as $am){
                                if ($s == $am['size']){
                                  echo '<div class="col-sm-4 col-xs-6">';
                                  getCampaignLabel($am['expansion'], "mini");
                                  echo " " . $am['name'];
                                  echo '</div>';
                                }
                              } ?>
                            </div>
                          </div>
                        </div>
                        <p></p>
                      </div>
                    </div><?php
                  } ?>
                </div>
              </div> 
            </div>
          </div>



        </div>


         
















      </div>
    </div>
  </body>
</html>
<?php
/*
echo '<pre>';
var_dump($statsArray);
echo '</pre>';
*/

?>