<?php 

//-----------------------//
//remove me after include//
//-----------------------//

//include the db
require_once('Connections/dbDescent.php'); 

mysql_select_db($database_dbDescent, $dbDescent);

//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

//include functions
include 'includes/function_logout.php';
include 'includes/function_getSQLValueString.php';
include 'includes/function_createProgressBar.php';

//$query_rsCampaignList = "SELECT cam_id, cam_name FROM tbcampaign WHERE cam_type = 'full' OR cam_type = 'mini' OR cam_type = 'book' ORDER BY cam_id ASC";
$query_rsCampaignList = "SELECT cam_id, cam_name FROM tbcampaign WHERE cam_type != 'lieutenant' ORDER BY cam_id ASC";
$rsCampaignList = mysql_query($query_rsCampaignList, $dbDescent) or die(mysql_error());
$row_rsCampaignList = mysql_fetch_assoc($rsCampaignList);
$totalRows_rsCampaignList = mysql_num_rows($rsCampaignList);

$campaignsIdArray = array();
$campaignsNameArray = array();

do{
	$campaignsIdArray[] = $row_rsCampaignList['cam_id'];
	$campaignsNameArray[$row_rsCampaignList['cam_id']] = $row_rsCampaignList['cam_name'];
} while ($row_rsCampaignList = mysql_fetch_assoc($rsCampaignList));


if (isset($_GET['campaign']) && in_array($_GET['campaign'], $campaignsIdArray)) {
  $campaignUrlID = $_GET['campaign'];
} else {
	$campaignUrlID = 0;
}





// include 'stats_quests_array.php';



$query_rsCampaignInfo = sprintf("SELECT * FROM tbcampaign WHERE cam_id = %s", GetSQLValueString($campaignUrlID, "int"));
$rsCampaignInfo = mysql_query($query_rsCampaignInfo, $dbDescent) or die(mysql_error());
$row_rsCampaignInfo = mysql_fetch_assoc($rsCampaignInfo);
$totalRows_rsCampaignInfo = mysql_num_rows($rsCampaignInfo);

do{
	$campaignInfoArray = array(
		"id" =>	$row_rsCampaignInfo['cam_id'],
		"name" => $row_rsCampaignInfo['cam_name'],
		"type" => $row_rsCampaignInfo['cam_type'],
		"heroes" => array(),
	);
} while ($row_rsCampaignInfo = mysql_fetch_assoc($rsCampaignInfo));

$query_rsCampaignHeroes = sprintf("SELECT * FROM tbheroes WHERE hero_expansion = %s", GetSQLValueString($campaignUrlID, "int"));
$rsCampaignHeroes = mysql_query($query_rsCampaignHeroes, $dbDescent) or die(mysql_error());
$row_rsCampaignHeroes = mysql_fetch_assoc($rsCampaignHeroes);
$totalRows_rsCampaignHeroes = mysql_num_rows($rsCampaignHeroes);

do{
	if($row_rsCampaignHeroes['hero_type'] != "Overlord"){
		$campaignInfoArray['heroes'][] = array(
			"name" => $row_rsCampaignHeroes['hero_name'],
			"img" => $row_rsCampaignHeroes['hero_img'],
			"archetype" => $row_rsCampaignHeroes['hero_type'],
			"description" => "",
		);
	}
	
} while ($row_rsCampaignHeroes = mysql_fetch_assoc($rsCampaignHeroes));

$query_rsCampaignQuests = sprintf("SELECT * FROM tbquests WHERE quest_expansion_id = %s", GetSQLValueString($campaignUrlID, "int"));
$rsCampaignQuests = mysql_query($query_rsCampaignQuests, $dbDescent) or die(mysql_error());
$row_rsCampaignQuests = mysql_fetch_assoc($rsCampaignQuests);
$totalRows_rsCampaignQuests = mysql_num_rows($rsCampaignQuests);

do{
	if($row_rsCampaignQuests['quest_act'] != "Setup"){
	  $shortl = $row_rsCampaignQuests['quest_name'];
	  $shortl = strtolower($shortl);
	  $shortl = str_replace(" ","_",$shortl);
	  $shortl = preg_replace("/[^A-Za-z0-9_]/","",$shortl);
	
	  $campaignInfoArray['quests'][] = array(
	    "id" => intval($row_rsCampaignQuests['quest_id']),
	    "name" => $row_rsCampaignQuests['quest_name'],
	    "act" => $row_rsCampaignQuests['quest_act'],
	    "req_type" => $row_rsCampaignQuests['quest_req_type'],
	    "req" => explode(",", $row_rsCampaignQuests['quest_req']),
	    "img" => $shortl . ".jpg",
	    "description" => "",
	  );
	}
} while ($row_rsCampaignQuests = mysql_fetch_assoc($rsCampaignQuests));


// Select Monsters
$query_rsMonsters = sprintf("SELECT * FROM tbmonsters WHERE monster_exp_id = %s ORDER BY monster_name ASC", GetSQLValueString($campaignUrlID, "int"));
$rsMonsters = mysql_query($query_rsMonsters, $dbDescent) or die(mysql_error());
$row_rsMonsters = mysql_fetch_assoc($rsMonsters);
$totalRows_rsMonsters = mysql_num_rows($rsMonsters);

do {

  $campaignInfoArray['monsters'][] = array(
    "id" => $row_rsMonsters['monster_id'],
    "name" => $row_rsMonsters['monster_name'],
    "type" => $row_rsMonsters['monster_type'],
    "traits" => explode(',', $row_rsMonsters['monster_traits']),
    "description" => "",
  );

} while ($row_rsMonsters = mysql_fetch_assoc($rsMonsters));



?>

<html>
  <head><?php 
    $pagetitle = $campaignInfoArray['name'] . " Campaign";
    include 'head.php'; ?>
  </head>
  <body>

    <?php 
      include 'navbar.php'; 
      include 'banner.php';
    ?>

    

    <div class="container grey campaigns-overview">
      <div>
        <h1 class="gaming-group"><?php echo $pagetitle = $campaignInfoArray['name'] . " Campaign"; ?></h1>
        <p class="top-lead lead text-muted">An overview of all components related to this campaign.</p>
      </div>
      <nav>
        <ul class="pagination pagination-sm"><?php
          foreach ($campaignsIdArray as $cid){
            echo '<li ';
            if ($cid == $campaignUrlID){
              echo 'class="active"';
            }
            echo '><a href="campaign_page.php?campaign='. $cid . '">' . $campaignsNameArray[$cid] . '</a></li>';
          } ?>
        </ul>
      </nav>
      <div class="row">&nbsp;</div>


      <h2>Heroes</h2><?php
      $ir = 0;
      foreach ($campaignInfoArray['heroes'] as $cia){ 

        // Check if the image for the quest exists, if not, use default
        $filename = "img/heroes/" . $cia['img'];
        if (!file_exists($filename)) {
          $filename = "img/quests/default.jpg";
        }

        if($ir == 0){ ?>
          <div class="row no-gutters" style="margin-bottom: 30px;">
            <div class="col-xs-12"><?php
        } 
        $ir++; ?>

            

              <div class="col-md-3">

                <div class="row no-gutters" style="background: #f9f9f9; border: 1px solid #ddd;">
                  <div class="col-sm-4">
                    <div style="background: url('<?php print $filename; ?>') no-repeat center; background-size: 160% auto; height: 224px;"></div>
                  </div>
                  <div class="col-sm-8" style="padding: 0 15px;">
                    <h2 class="h4"><?php print $cia['name']; ?></h2>
                    <small>
                      <p>
                        <small><?php echo $cia['archetype']; ?></small>
                      </p>
                    </small>
                    <p><small><?php print $cia['description']; ?></small></p>
                    <div class="row"><?php 
                      // foreach ($statsArray as $sta){
                      //   if($sta['quest_name'] == $cia['name']){
                      //     $QuestWins = calcQuestWins($sta['overlord_wins'], $sta['hero_wins']);
                      //   }
                      // } ?>
                      
                      <div class="col-md-12">
                        <?php //createProgressBar($QuestWins['HeroPerc'], "Won (Heroes)", $QuestWins['OverlordPerc'], "Won (Overlord)"); ?>         
                      </div>
                    </div>

                  </div>
                  
                </div>


                
              
              </div><?php

        if($ir == 4){
          echo '</div>';
          echo '</div>';
          $ir = 0;
        }

	    } // foreach 

	    if($ir != 0){
        echo '</div>';
        echo '</div>';
      } ?>


      <h2>Quests</h2><?php
      $ir = 0;
      foreach ($campaignInfoArray['quests'] as $qia){ 

        // Check if the image for the quest exists, if not, use default
        $filename = "img/quests/" . $qia['img'];
        if (!file_exists($filename)) {
          $filename = "img/quests/default.jpg";
        }

        if($ir == 0){ ?>
          <div class="row no-gutters" style="margin-bottom: 30px;">
            <div class="col-xs-12"><?php
        } 
        $ir++; ?>

            

              <div class="col-md-3">

                <div class="row no-gutters" style="background: #f9f9f9; border: 1px solid #ddd;">
                  <div class="col-sm-4">
                    <div style="background: url('<?php print $filename; ?>') no-repeat center; background-size: 160% auto; height: 224px;"></div>
                  </div>
                  <div class="col-sm-8" style="padding: 0 15px;">
                    <h2 class="h4"><?php print $qia['name']; ?></h2>
                    <small>
                      <p>
                        <small><?php echo $qia['act']; ?></small>
                      </p>
                    </small>
                    <p><small><?php print $qia['description']; ?></small></p>
                    <div class="row"><?php 
                      // foreach ($statsArray as $sta){
                      //   if($sta['quest_name'] == $qia['name']){
                      //     $QuestWins = calcQuestWins($sta['overlord_wins'], $sta['hero_wins']);
                      //   }
                      // } ?>
                      
                      <div class="col-md-12">
                        <?php //createProgressBar($QuestWins['HeroPerc'], "<small>Won (Heroes)</small>", $QuestWins['OverlordPerc'], "<small>Won (Overlord)</small>"); ?>      
                      </div>
                    </div>

                  </div>
                  
                </div>


                
              
              </div><?php

        if($ir == 4){
          echo '</div>';
          echo '</div>';
          $ir = 0;
        }

	    } // foreach 

	    if($ir != 0){
        echo '</div>';
        echo '</div>';
      } ?>

      <h2>Monsters</h2><?php


      $ir = 0;
      foreach ($campaignInfoArray['monsters'] as $monster){ 

        // Check if the image for the quest exists, if not, use default
        // $filename = "img/quests/" . $monster['img'];
        // if (!file_exists($filename)) {
          $filename = "img/quests/default.jpg";
        //}

        if($ir == 0){ ?>
          <div class="row no-gutters" style="margin-bottom: 30px;">
            <div class="col-xs-12"><?php
        } 
        $ir++; ?>

            

              <div class="col-md-3">

                <div class="row no-gutters" style="background: #f9f9f9; border: 1px solid #ddd;">
                  <div class="col-sm-4">
                    <div style="background: url('<?php print $filename; ?>') no-repeat center; background-size: 160% auto; height: 224px;"></div>
                  </div>
                  <div class="col-sm-8" style="padding: 0 15px;">
                    <h2 class="h4"><?php print $monster['name']; ?></h2>
                    <small>
                      <p>
                        <small><?php 
                        foreach ($monster['traits'] as $trait){
                        	if ($trait != 'all'){
                        		echo $trait . ' ';
                        	}
                        } ?>
                      	</small>
                      </p>
                    </small>
                    <p><small><?php print $monster['description']; ?></small></p>
                    <div class="row"><?php 
                      // foreach ($statsArray as $sta){
                      //   if($sta['quest_name'] == $qia['name']){
                      //     $QuestWins = calcQuestWins($sta['overlord_wins'], $sta['hero_wins']);
                      //   }
                      // } ?>
                      
                      <div class="col-md-12">
                        <?php //createProgressBar($QuestWins['HeroPerc'], "<small>Won (Heroes)</small>", $QuestWins['OverlordPerc'], "<small>Won (Overlord)</small>"); ?>      
                      </div>
                    </div>

                  </div>
                  
                </div>


                
              
              </div><?php

        if($ir == 4){
          echo '</div>';
          echo '</div>';
          $ir = 0;
        }

	    } // foreach 

	    if($ir != 0){
        echo '</div>';
        echo '</div>';
      } ?>


    </div>
  </body>
</html>