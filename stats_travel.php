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

//select the database
mysql_select_db($database_dbDescent, $dbDescent);

$query_rsTopTravel = sprintf("SELECT travel_aq_event_id, travel_name, travel_id, travel_exp_id, travel_type, cam_id, cam_name, COUNT(*) as count FROM tbtravel_aquired INNER JOIN tbtravel ON travel_aq_event_id = travel_id INNER JOIN tbcampaign ON travel_exp_id = cam_id GROUP BY travel_name ORDER BY count DESC");
$rsTopTravel = mysql_query($query_rsTopTravel, $dbDescent) or die(mysql_error());
$row_rsTopTravel = mysql_fetch_assoc($rsTopTravel);
$totalRows_rsTopTravel = mysql_num_rows($rsTopTravel);

$topTravel = array();
$topTravel2 = array();
$topTravelCount = 0;
do{

  $tempCam = $row_rsTopTravel['cam_name'];
  if ($row_rsTopTravel['travel_name'] == "No Event"){
    $tempCam = "All";
  }

  if ($row_rsTopTravel['travel_name'] != "Skipped" && $row_rsTopTravel['travel_name'] != "No Event"){
    $topTravel[] = array(
      "name" => $row_rsTopTravel['travel_name'],
      "cam_name" => $tempCam,
      "count" => $row_rsTopTravel['count'],
    );
    if($row_rsTopTravel['travel_type'] != "all" && $row_rsTopTravel['travel_type'] != "item"){
      $topTravel2[$row_rsTopTravel['travel_type']][] = array(
        "name" => $row_rsTopTravel['travel_name'],
        "cam_name" => $tempCam,
        "count" => $row_rsTopTravel['count'],
      );
    }
    
  }

  if ($row_rsTopTravel['travel_name'] != "No Event" && $row_rsTopTravel['travel_name'] != "Skipped"){
    $topTravelCount = $topTravelCount + $row_rsTopTravel['count'];
  }

} while ($row_rsTopTravel = mysql_fetch_assoc($rsTopTravel));


?>

<html>
  <head><?php 
    $pagetitle = "Quest Statistics";
    include 'head.php'; ?>
  </head>
  <body>
    <?php 
      include 'navbar.php'; 
      include 'banner.php';
    ?>

    <div class="container grey">
      <h1>Travel Stats</h1>
      <p class="top-lead lead text-muted">Statistics about encountered travel steps.</p><?php

      $i = 0;
      foreach ($topTravel2 as $key => $tt2){ 
        if ($i == 0){
          echo '<div class="row">';
        }
        $i++; ?>
        <div class="col-md-6">
          <div class="row">
            <div class="col-md-12">&nbsp;</div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h2 class="panel-title"><?php echo ucwords($key) . " Travel Steps"; ?></h2>
                </div>

                <div class="panel-body"><?php
                  $topTravelCount2 = 0;
                  foreach ($tt2 as $ttx) {
                    $topTravelCount2 += $ttx['count'];
                  }
                  foreach ($tt2 as $ttx) { ?>
                    <div class="row stats-row">
                      <div class="col-xs-12"><?php
                        $TravelPerc2 = ($ttx['count'] / $topTravelCount2) * 100; ?>

                        <div class="row"> 
                          <div class="col-md-6"> 
                            <p><strong><?php echo $ttx['name']; ?></strong></p>
                          </div>
                          <div class="col-md-6 text-right"> 
                            <?php getCampaignLabel($ttx['cam_name'], "normal"); ?>
                          </div>
                        </div>
                        <div class="row"> 
                          <div class="col-md-12">    
                            <div class="progress">
                              <?php createProgressBar($TravelPerc2, "of " . ucwords($key) . " Travel Steps", 0, ""); ?>  
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
        </div><?php
        if ($i == 2){
          echo '</div>';
          $i = 0;
        }
      } ?>

    </div>
  </body>
</html>