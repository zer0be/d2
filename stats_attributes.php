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



if ((isset($_GET["show"])) && ($_GET["show"] == "hide-ck")) {
  $hideCk = 1;
} else {
  $hideCk = 0;
}

if (!isset($_GET["by"])) {
  $sorting = "DESC";
  $byurl = "";
} else {
  $sorting = $_GET["by"];
  $byurl = "by=" . $_GET["by"] . "&";
}

if (isset($_GET["sort"])) {
  $sort = $_GET["sort"];
  $sorturl = "sort=" . $_GET["sort"] . "&";
  if ($_GET["sort"] == "speed"){
    $sortBy = "hero_speed " . $sorting . ", hero_name";
  } else if ($_GET["sort"] == "health"){
    $sortBy = "hero_health " . $sorting . ", hero_name";
  } else if ($_GET["sort"] == "stamina"){
    $sortBy = "hero_stamina " . $sorting . ", hero_name";
  } else if ($_GET["sort"] == "defense"){
    $sortBy = "hero_defense " . $sorting . ", hero_name";
  } else if ($_GET["sort"] == "might"){
    $sortBy = "hero_might " . $sorting . ", hero_name";
  } else if ($_GET["sort"] == "knowledge"){
    $sortBy = "hero_knowledge " . $sorting . ", hero_name";
  } else if ($_GET["sort"] == "willpower"){
    $sortBy = "hero_willpower " . $sorting . ", hero_name";
  } else if ($_GET["sort"] == "awareness"){
    $sortBy = "hero_awareness " . $sorting . ", hero_name";
  } else {
    $sortBy = "hero_name";
  }
  
} else {
  $sortBy = "hero_name";
  $sort = "";
  $sorturl = "";
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


?>

<html>
  <head><?php 
    $pagetitle = "Hero Information";
    include 'head.php'; ?>
    <script>
      $(function() { 
          // for bootstrap 3 use 'shown.bs.tab', for bootstrap 2 use 'shown' in the next line
          $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
              // save the latest tab; use cookies if you like 'em better:
              localStorage.setItem('lastTab', $(this).attr('href'));
          });

          // go to the latest tab, if it exists:
          var lastTab = localStorage.getItem('lastTab');
          if (lastTab) {
              $('[href="' + lastTab + '"]').tab('show');
          }
      });
    </script>
  </head>
  <body>
    <?php 
      include 'navbar.php'; 
      include 'banner.php';
    ?>

    <div class="container grey">

      <h1>Hero Information</h1>
      <p class="top-lead lead text-muted">Attribute and other information about all available heroes.</p>

      <div class="row">
        <div class="col-md-12">
          &nbsp;
        </div>
        <div class="col-sm-12">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h2 class="panel-title">Hero Attributes</h2>
            </div>

            <div class="panel-body">
              This table displays the attributes of all heroes. Values are indicated as follows: 
              <span class="badge dark-red">Very low</span>, 
              <span class="badge red">Low</span>,
              <span class="badge yellow">Medium</span>, 
              <span class="badge green">High</span>, 
              <span class="badge dark-green">Very High</span>. 
              <p>It also displays the average of each attribute (the sum of all values of that attribute divided by the number of heroes). 
              A value higher than average will be indicated by a <small><span class="glyphicon glyphicon-triangle-top text-green" aria-hidden="true"></span></small>, 
              a lower one with a <small><span class="glyphicon glyphicon-triangle-bottom text-red" aria-hidden="true"></span></small>. </p>
              <p>For example: When I select the Warrior tab to view only the Warrior heroes, a certain Warrior might have a Might of <span class="badge yellow">3</span><small><span class="glyphicon glyphicon-triangle-bottom text-red" aria-hidden="true"></span></small>, 
              which means that while the value is ok, it is still lower than the average of <span class="badge green">4</span> Might for Warriors.</p>
              <div class="text-right"><?php 
                if ($hideCk == 0){ ?>
                  <a class="btn btn-default" href="stats_attributes.php?<?php echo $sorturl . $byurl; ?>show=hide-ck">Remove Conversion Kit</a/><?php
                } else { ?>
                  <a class="btn btn-default" href="stats_attributes.php?<?php echo $sorturl . $byurl; ?>">Add Conversion Kit</a/><?php
                } ?>
              </div>
                


              <div role="tabpanel">

                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                  <li role="presentation" class="active"><a href="#all-attr" aria-controls="home" role="tab" data-toggle="tab">All</a></li>
                  <li role="presentation"><a href="#mages-attr" aria-controls="profile" role="tab" data-toggle="tab">Mages</a></li>
                  <li role="presentation"><a href="#warriors-attr" aria-controls="messages" role="tab" data-toggle="tab">Warriors</a></li>
                  <li role="presentation"><a href="#scouts-attr" aria-controls="settings" role="tab" data-toggle="tab">Scouts</a></li>
                  <li role="presentation"><a href="#healers-attr" aria-controls="settings" role="tab" data-toggle="tab">Healers</a></li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                  <div role="tabpanel" class="tab-pane active fade in" id="all-attr"><?php 
                    $attrType = "All";
                    include 'stats_hero.php'; ?>
                  </div>

                  <div role="tabpanel" class="tab-pane fade" id="mages-attr"><?php
                    $attrType = "Mage";
                    include 'stats_hero.php'; ?>
                  </div>

                  <div role="tabpanel" class="tab-pane fade" id="warriors-attr"><?php
                    $attrType = "Warrior";
                    include 'stats_hero.php'; ?>
                  </div>

                  <div role="tabpanel" class="tab-pane fade" id="scouts-attr"><?php
                    $attrType = "Scout";
                    include 'stats_hero.php'; ?>
                  </div>

                  <div role="tabpanel" class="tab-pane fade" id="healers-attr"><?php
                    $attrType = "Healer";
                    include 'stats_hero.php'; ?>
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