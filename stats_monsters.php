<?php

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

//select the database
mysql_select_db($database_dbDescent, $dbDescent);

// Get Games - FIX ME: Do I need this here?
$query_rsAllGames = sprintf("SELECT * FROM tbgames ORDER BY game_id ASC");
$rsAllGames = mysql_query($query_rsAllGames, $dbDescent) or die(mysql_error());
$row_rsAllGames = mysql_fetch_assoc($rsAllGames);
$totalRows_rsAllGames = mysql_num_rows($rsAllGames);

$totalGames = 0;
do {
  $totalGames++; 
} while ($row_rsAllGames = mysql_fetch_assoc($rsAllGames));

include 'stats_monsters_data.php';

?>

<html>
  <head><?php 
    $pagetitle = "Monster Statistics and Information";
    include 'head.php'; ?>
  </head>
  <body><?php 
    include 'navbar.php'; 
    include 'banner.php'; ?>

    <div class="container grey">
      <h1>Monster Statistics and Info</h1>
      <p class="top-lead lead text-muted">Statistics showing the most popular monsters and information about monster traits, size, conditions, ...</p>

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
                                  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo round($MonsterUsedPercentage[$key]);?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo round($MonsterUsedPercentage[$key]);?>%;">
                                    <span class="sr-only"><?php echo round($MonsterUsedPercentage[$key]);?>%</span>
                                    <?php echo round($MonsterUsedPercentage[$key]);?>% of Encounters
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
                <div class="row stats-row">
                    <div class="col-xs-12">
                      <p><strong>Important: </strong>All Medium, Huge and Massive monsters are considered Large monsters.</p>
                    </div>
                  </div>
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
                  <h2 class="panel-title">Conditions inflictable by monsters</h2>
                </div>

                <div class="panel-body"><?php
                  foreach($conditions as $c){ ?>
                    <div class="row stats-row">
                      <div class="col-xs-12">
                        <div class="row"> 
                          <div class="col-md-6"> 
                            <p><strong><?php echo ucwords($c); ?></strong></p>
                          </div>
                          <div class="col-md-6 text-right"> 
                          </div>
                        </div>
                        <div class="row"> 
                          <div class="col-md-12">
                            <div class="row"><?php
                              foreach ($allMonsters as $am){
                                if (in_array($c, $am['conditions'])){
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