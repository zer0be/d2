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


// Select Search Stats
$query_rsAllSearch = sprintf("SELECT * FROM tbsearch ORDER BY search_found DESC");
$rsAllSearch = mysql_query($query_rsAllSearch, $dbDescent) or die(mysql_error());
$row_rsAllSearch = mysql_fetch_assoc($rsAllSearch);
$totalRows_rsAllSearch = mysql_num_rows($rsAllSearch);

$SearchStats = array();
$SearchTotal = 0;
$totalSecretPassages = 0;
do{

  $SearchStats[] = array(
    "name" => $row_rsAllSearch['search_name'],
    "value" => $row_rsAllSearch['search_value'],
    "amount" => $row_rsAllSearch['search_found'],
  );

  $SearchTotal = $SearchTotal + $row_rsAllSearch['search_found'];

  if ($row_rsAllSearch['search_name'] == "Secret Passage"){
    $totalSecretPassages += $row_rsAllSearch['search_found'];
  }

} while ($row_rsAllSearch = mysql_fetch_assoc($rsAllSearch));


// Select Secret Room Stats
$query_rsAllSecretRooms = sprintf("SELECT * FROM tbsecretrooms INNER JOIN tbcampaign ON secretroom_exp_id = cam_id ORDER BY secretroom_found DESC");
$rsAllSecretRooms = mysql_query($query_rsAllSecretRooms, $dbDescent) or die(mysql_error());
$row_rsAllSecretRooms = mysql_fetch_assoc($rsAllSecretRooms);
$totalRows_rsAllSecretRooms = mysql_num_rows($rsAllSecretRooms);

$SecretRoomsStats = array();
$SecretRoomsTotal = 0;
$SecretRoomsCleared = 0;
do{

  $SecretRoomsStats[] = array(
    "name" => $row_rsAllSecretRooms['secretroom_name'],
    "amount" => $row_rsAllSecretRooms['secretroom_found'],
    "cleared" => $row_rsAllSecretRooms['secretroom_cleared'],
    "cam_name" => $row_rsAllSecretRooms['cam_name'],
  );

  $SecretRoomsTotal = $SecretRoomsTotal + $row_rsAllSecretRooms['secretroom_found'];
  $SecretRoomsCleared = $SecretRoomsCleared + $row_rsAllSecretRooms['secretroom_cleared'];

} while ($row_rsAllSecretRooms = mysql_fetch_assoc($rsAllSecretRooms));

?>

<html>
  <head><?php 
    $pagetitle = "Search Statistics";
    include 'head.php'; ?>
  </head>
  <body>
    <?php 
      include 'navbar.php'; 
      include 'banner.php';
    ?>

    <div class="container grey">

      <h1>Search Statistics</h1>
      <p class="top-lead lead text-muted">Statistics about the Search Cards and Secret Rooms.</p>

      <div class="row">
        <div id="search" class="col-md-6"> 


          <div class="row">
            <div class="col-md-12">&nbsp;</div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h2 class="panel-title">Most Found Search Cards</h2>
                </div>

                <div class="panel-body"><?php
                  foreach ($SearchStats as $ss) { ?>
                    <div class="row stats-row">
                      <div class="col-xs-12"><?php
                        $SearchPerc = ($ss['amount'] / $SearchTotal) * 100; ?>

                        <div class="row"> 
                          <div class="col-md-6"> 
                            <p><strong><?php echo $ss['name'] . ' (' . $ss['value'] . ' gold)'; ?></strong></p>
                          </div>
                          <div class="col-md-6 text-right"> 
                          </div>
                        </div>
                        <div class="row"> 
                          <div class="col-md-12">    
                            <div class="progress">
                              <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo round($SearchPerc);?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo round($SearchPerc);?>%;">
                                <span class="sr-only"><?php echo round($SearchPerc);?>%</span>
                                <?php echo round($SearchPerc);?>% of Searches
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div><?php
                  } ?>
                </div>
              </div> 
            </div>
          </div>
        </div>

        <div class="col-md-6"> 
           <div class="row">
            <div class="col-md-12">&nbsp;</div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h2 class="panel-title">Most Explored Secret Rooms</h2>
                </div>

                <div class="panel-body"><?php
                  foreach ($SecretRoomsStats as $srs) { ?>
                    <div class="row stats-row">
                      <div class="col-xs-12"><?php
                        $SecretRoomPerc = ($srs['amount'] / $SecretRoomsTotal) * 100;
                        if ($srs['cleared'] != 0){
                          $SecretRoomClearPerc = ($srs['cleared'] / $srs['amount']) * 100;
                        } else {
                          $SecretRoomClearPerc = 0;
                        }
                        $SecretRoomPercCalc = $SecretRoomPerc * $SecretRoomClearPerc / 100;
                        ?>

                        <div class="row"> 
                          <div class="col-md-6"> 
                            <p><strong><strong><?php echo $srs['name']; ?></strong></strong></p>
                          </div>
                          <div class="col-md-6 text-right"> 
                            <?php getCampaignLabel($srs['cam_name'], "normal"); ?>
                          </div>
                        </div>
                        <div class="row"> 
                          <div class="col-md-12">    
                            <div class="progress">
                              <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo round($SecretRoomPercCalc);?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo round($SecretRoomPercCalc);?>%;">
                                <span class="sr-only"><?php echo round($SecretRoomPercCalc);?>%</span>
                                <?php echo round($SecretRoomPerc);?>% of Explorations
                              </div>
                              <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="<?php echo round($SecretRoomPerc - $SecretRoomPercCalc);?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo round($SecretRoomPerc - $SecretRoomPercCalc);?>%;">
                                <span class="sr-only"><?php echo round($SecretRoomPerc - $SecretRoomPercCalc);?>%</span>
                                <?php echo round(100 - $SecretRoomClearPerc);?>% Not Cleared
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div><?php
                  } ?>
                  <div class="row stats-row">
                    <div class="col-xs-12"> <?php
                      echo "<p>Of the " . $totalSecretPassages . " Secret Passages found, " . $SecretRoomsTotal ." were explored to reveal a Secret Room. Of those,  " . $SecretRoomsCleared . " were fully cleared.</p>"; ?>
                    </div>
                  </div>
                  
                </div>
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