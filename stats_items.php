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


//Select items based on occurence
$query_rsTopItemsAct1 = sprintf("SELECT aq_item_id, item_name, item_id, item_act, aq_item_gottraded, item_exp_id, cam_id, cam_name, COUNT(*) as count FROM tbitems_aquired INNER JOIN tbitems ON aq_item_id = item_id INNER JOIN tbcampaign ON item_exp_id = cam_id WHERE aq_item_gottraded = 0 AND item_act = 'Act 1' GROUP BY item_name ORDER BY count DESC");
$rsTopItemsAct1 = mysql_query($query_rsTopItemsAct1, $dbDescent) or die(mysql_error());
$row_rsTopItemsAct1 = mysql_fetch_assoc($rsTopItemsAct1);
$totalRows_rsTopItemsAct1 = mysql_num_rows($rsTopItemsAct1);

$query_rsTopItemsAct1Details = sprintf("SELECT aq_item_id, item_name, item_id, item_act, aq_item_gottraded, aq_item_price_ovrd, item_exp_id, cam_id, cam_name FROM tbitems_aquired INNER JOIN tbitems ON aq_item_id = item_id INNER JOIN tbcampaign ON item_exp_id = cam_id WHERE aq_item_gottraded = 0 AND item_act = 'Act 1'");
$rsTopItemsAct1Details = mysql_query($query_rsTopItemsAct1Details, $dbDescent) or die(mysql_error());
$row_rsTopItemsAct1Details = mysql_fetch_assoc($rsTopItemsAct1Details);
$totalRows_rsTopItemsAct1Details = mysql_num_rows($rsTopItemsAct1Details);



$topItemsAct1Bought = array();
$topItemsAct1Found = array();

do {  

  // echo '<pre>';
  // var_dump($row_rsTopItemsAct1Details);
  // echo '</pre>';

  //echo $row_rsTopItemsAct1Details['item_name'] . $row_rsTopItemsAct1Details['aq_item_price_ovrd'];

  if (is_null($row_rsTopItemsAct1Details['aq_item_price_ovrd'])){
    $topItemsAct1Bought[] = $row_rsTopItemsAct1Details['item_name'];
  } else {
    if ($row_rsTopItemsAct1Details['aq_item_price_ovrd'] == 0){
      $topItemsAct1Found[] = $row_rsTopItemsAct1Details['item_name'];
    } else {
      $topItemsAct1Bought[] = $row_rsTopItemsAct1Details['item_name'];
    }
  }

  

} while ($row_rsTopItemsAct1Details = mysql_fetch_assoc($rsTopItemsAct1Details));

$topItemsAct1Found = array_count_values($topItemsAct1Found);
$topItemsAct1Bought = array_count_values($topItemsAct1Bought);



$query_rsTopItemsAct2 = sprintf("SELECT aq_item_id, item_name, item_id, item_act, aq_item_gottraded, item_exp_id, cam_id, cam_name, COUNT(*) as count FROM tbitems_aquired INNER JOIN tbitems ON aq_item_id = item_id INNER JOIN tbcampaign ON item_exp_id = cam_id WHERE aq_item_gottraded = 0 AND item_act = 'Act 2' GROUP BY item_name ORDER BY count DESC");
$rsTopItemsAct2 = mysql_query($query_rsTopItemsAct2, $dbDescent) or die(mysql_error());
$row_rsTopItemsAct2 = mysql_fetch_assoc($rsTopItemsAct2);
$totalRows_rsTopItemsAct2 = mysql_num_rows($rsTopItemsAct2);

$query_rsTopItemsAct2Details = sprintf("SELECT aq_item_id, item_name, item_id, item_act, aq_item_gottraded, aq_item_price_ovrd, item_exp_id, cam_id, cam_name FROM tbitems_aquired INNER JOIN tbitems ON aq_item_id = item_id INNER JOIN tbcampaign ON item_exp_id = cam_id WHERE aq_item_gottraded = 0 AND item_act = 'Act 2'");
$rsTopItemsAct2Details = mysql_query($query_rsTopItemsAct2Details, $dbDescent) or die(mysql_error());
$row_rsTopItemsAct2Details = mysql_fetch_assoc($rsTopItemsAct2Details);
$totalRows_rsTopItemsAct2Details = mysql_num_rows($rsTopItemsAct2Details);


$topItemsAct2Bought = array();
$topItemsAct2Found = array();

do {  
  if (is_null($row_rsTopItemsAct2Details['aq_item_price_ovrd'])){
    $topItemsAct2Bought[] = $row_rsTopItemsAct2Details['item_name'];
  } else {
    if ($row_rsTopItemsAct2Details['aq_item_price_ovrd'] == 0){
      $topItemsAct2Found[] = $row_rsTopItemsAct2Details['item_name'];
    } else {
      $topItemsAct2Found[] = $row_rsTopItemsAct2Details['item_name'];
    }
  }

} while ($row_rsTopItemsAct2Details = mysql_fetch_assoc($rsTopItemsAct2Details));

$topItemsAct2Found = array_count_values($topItemsAct2Found);
$topItemsAct2Bought = array_count_values($topItemsAct2Bought);

?>

<html>
  <head><?php 
    $pagetitle = "Item Statistics";
    include 'head.php'; ?>
  </head>
  <body>
    <?php 
      include 'navbar.php'; 
      include 'banner.php';
    ?>

    <div class="container grey">

      <h1>Item Statistics</h1>
      <p class="top-lead lead text-muted">Statistics about the most bought and found items.</p>

      <div class="row">
        <div class="col-sm-6">
          <div class="row">
            <div class="col-md-12">
              &nbsp;
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h2 class="panel-title">Popular Act I Items</h2>
                </div>

                <div class="panel-body"><?php
                  do { ?>
                    <div class="row stats-row">
                      <div class="col-xs-12"><?php
                        $ItemAct1Perc = ($row_rsTopItemsAct1['count'] / $totalGames) * 100;

                        if (isset($topItemsAct1Bought[$row_rsTopItemsAct1['item_name']])){
                        $boughtPerc = $topItemsAct1Bought[$row_rsTopItemsAct1['item_name']]; 
                        } else {
                        $boughtPerc = 0;
                        }

                        if ($boughtPerc != 0){
                        $ItemAct1BoughtPerc = ($boughtPerc / $row_rsTopItemsAct1['count']) * 100;
                        } else {
                        $ItemAct1BoughtPerc = 0;
                        }

                        if (isset($topItemsAct1Found[$row_rsTopItemsAct1['item_name']])){
                        $foundPerc = $topItemsAct1Found[$row_rsTopItemsAct1['item_name']]; 
                        } else {
                        $foundPerc = 0;
                        }

                        if ($foundPerc != 0){
                        $ItemAct1FoundPerc = ($foundPerc / $row_rsTopItemsAct1['count']) * 100;
                        } else {
                        $ItemAct1FoundPerc = 0;
                        }

                        $ItemAct1PercCalc = $ItemAct1Perc * $ItemAct1FoundPerc / 100;
                        $ItemAct1PercCalcB = $ItemAct1Perc * $ItemAct1BoughtPerc / 100;?>

                        <div class="row"> 
                          <div class="col-md-6"> 
                            <p><strong><?php echo $row_rsTopItemsAct1['item_name']; ?></strong></p>
                          </div>
                          <div class="col-md-6 text-right"><?php 
                            getCampaignLabel($row_rsTopItemsAct1['cam_name'], "normal"); ?>
                          </div>
                        </div>
                        <div class="row"> 
                          <div class="col-md-12">    
                            <div class="progress">
                              <div class="progress">
                                <div class="progress-bar progress-bar-success " role="progressbar" aria-valuenow="<?php echo round($ItemAct1Perc - $ItemAct1PercCalc);?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo round($ItemAct1Perc - $ItemAct1PercCalc);?>%;">
                                  <span class="sr-only"><?php echo round($ItemAct1Perc - $ItemAct1PercCalc);?>%</span>
                                  Bought in <?php echo round($ItemAct1PercCalcB);?>% of games.
                                </div>
                                <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="<?php echo round($ItemAct1PercCalc);?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo round($ItemAct1PercCalc);?>%;">
                                  <span class="sr-only"><?php echo round($ItemAct1PercCalc);?>%</span>
                                  Found in <?php echo round($ItemAct1PercCalc);?>% of games.
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div><?php
                  } while ($row_rsTopItemsAct1 = mysql_fetch_assoc($rsTopItemsAct1)); ?>
                  
                </div>
              </div> 
            </div>
          </div>                   
        </div>
        <div class="col-sm-6">
          <div class="row">
            <div class="col-md-12">
              &nbsp;
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h2 class="panel-title">Popular Act II Items</h2>
                </div>

                <div class="panel-body"><?php
                  do { ?>
                    <div class="row stats-row">
                      <div class="col-xs-12"><?php
                        $ItemAct2Perc = ($row_rsTopItemsAct2['count'] / $totalGames) * 100;

                        if (isset($topItemsAct2Bought[$row_rsTopItemsAct2['item_name']])){
                        $boughtPerc = $topItemsAct2Bought[$row_rsTopItemsAct2['item_name']]; 
                        } else {
                        $boughtPerc = 0;
                        }

                        if ($boughtPerc != 0){
                        $ItemAct2BoughtPerc = ($boughtPerc / $row_rsTopItemsAct2['count']) * 100;
                        } else {
                        $ItemAct2BoughtPerc = 0;
                        }

                        if (isset($topItemsAct2Found[$row_rsTopItemsAct2['item_name']])){
                        $foundPerc = $topItemsAct2Found[$row_rsTopItemsAct2['item_name']]; 
                        } else {
                        $foundPerc = 0;
                        }

                        if ($foundPerc != 0){
                        $ItemAct2FoundPerc = ($foundPerc / $row_rsTopItemsAct2['count']) * 100;
                        } else {
                        $ItemAct2FoundPerc = 0;
                        }

                        $ItemAct2PercCalc = $ItemAct2Perc * $ItemAct2FoundPerc / 100;
                        $ItemAct2PercCalcB = $ItemAct2Perc * $ItemAct2BoughtPerc / 100;?>

                        <div class="row"> 
                          <div class="col-md-6"> 
                            <p><strong><?php echo $row_rsTopItemsAct2['item_name']; ?></strong></p>
                          </div>
                          <div class="col-md-6 text-right"><?php 
                            getCampaignLabel($row_rsTopItemsAct2['cam_name'], "normal"); ?>
                          </div>
                        </div>
                        <div class="row"> 
                          <div class="col-md-12">    
                            <div class="progress">
                              <div class="progress">
                                <div class="progress-bar progress-bar-success " role="progressbar" aria-valuenow="<?php echo round($ItemAct2Perc - $ItemAct2PercCalc);?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo round($ItemAct2Perc - $ItemAct2PercCalc);?>%;">
                                  <span class="sr-only"><?php echo round($ItemAct2Perc - $ItemAct2PercCalc);?>%</span>
                                  Bought in <?php echo round($ItemAct2PercCalcB);?>% of games.
                                </div>
                                <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="<?php echo round($ItemAct2PercCalc);?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo round($ItemAct2PercCalc);?>%;">
                                  <span class="sr-only"><?php echo round($ItemAct2PercCalc);?>%</span>
                                  Found in <?php echo round($ItemAct2PercCalc);?>% of games.
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div><?php
                  } while ($row_rsTopItemsAct2 = mysql_fetch_assoc($rsTopItemsAct2)); ?>
                  
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