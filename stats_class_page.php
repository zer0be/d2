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
include 'includes/function_getCampaignLabel.php';

$query_rsClassList = "SELECT class_id, class_name FROM tbclasses ORDER BY class_archetype ASC";
$rsClassList = mysql_query($query_rsClassList, $dbDescent) or die(mysql_error());
$row_rsClassList = mysql_fetch_assoc($rsClassList);
$totalRows_rsClassList = mysql_num_rows($rsClassList);

$classIdArray = array();
$classNameArray = array();

do{
	$classIdArray[] = $row_rsClassList['class_id'];
	$classNameArray[$row_rsClassList['class_id']] = $row_rsClassList['class_name'];
} while ($row_rsClassList = mysql_fetch_assoc($rsClassList));


if (isset($_GET['class']) && in_array($_GET['class'], $classIdArray)) {
  $classUrlID = $_GET['class'];
} else {
	$classUrlID = 1;
}





$query_rsClassInfo = sprintf("SELECT * FROM tbclasses WHERE class_id = %s", GetSQLValueString($classUrlID, "int"));
$rsClassInfo = mysql_query($query_rsClassInfo, $dbDescent) or die(mysql_error());
$row_rsClassInfo = mysql_fetch_assoc($rsClassInfo);
$totalRows_rsClassInfo = mysql_num_rows($rsClassInfo);

do{
	$className = $row_rsClassInfo['class_name'];
	$classType = $row_rsClassInfo['class_archetype'];

	$classInfoArray = array(
		"id" =>	$row_rsClassInfo['class_id'],
		"name" => $row_rsClassInfo['class_name'],
		"type" => $row_rsClassInfo['class_archetype'],
    "exp_id" => $row_rsClassInfo['class_exp_id'],
		"skills" => array(),
	);
} while ($row_rsClassInfo = mysql_fetch_assoc($rsClassInfo));

$query_rsSkills = sprintf("SELECT * FROM tbskills WHERE skill_class = %s", GetSQLValueString($className, "text"));
$rsSkills = mysql_query($query_rsSkills, $dbDescent) or die(mysql_error());
$row_rsSkills = mysql_fetch_assoc($rsSkills);
$totalRows_rsSkills = mysql_num_rows($rsSkills);

do{
	$campaignInfoArray['heroes'][] = array(
		"name" => $row_rsSkills['skill_name'],
		"cost" => $row_rsSkills['skill_cost'],
		"stamina" => $row_rsSkills['skill_stamina_cost'],
	);
} while ($row_rsSkills = mysql_fetch_assoc($rsSkills));

$query_rsClassHeroes = sprintf("SELECT * FROM tbcharacters INNER JOIN tbheroes ON char_hero = hero_id INNER JOIN tbcampaign ON hero_expansion = cam_id WHERE char_class = %s", GetSQLValueString($className, "text"));
$rsClassHeroes = mysql_query($query_rsClassHeroes, $dbDescent) or die(mysql_error());
$row_rsClassHeroes = mysql_fetch_assoc($rsClassHeroes);

$classHeroes = array();
$classCharSkills = array();
$classCharSkillsInfo = array();
$classCharItems = array();
$classCharItemsNotKept = array();
$classCharItemsBought = array();
$classCharItemsFound = array();
$classCharItemsInfo = array();
$chars = 0;

do{
	$chars +=1;
	$classHeroes[] = $row_rsClassHeroes['hero_name'];

	$classHeroesInfo[$row_rsClassHeroes['hero_name']] = array(
		"name" => $row_rsClassHeroes['hero_name'],
		"img" => $row_rsClassHeroes['hero_img'],
		"cam_id" => $row_rsClassHeroes['cam_id'],
		"cam_name" => $row_rsClassHeroes['cam_name'],
		); 

	$query_rsClassCharSkills = sprintf("SELECT * FROM tbskills_aquired INNER JOIN tbskills ON spendxp_skill_id = skill_id INNER JOIN tbcampaign ON skill_expansion = cam_id WHERE spendxp_char_id = %s AND skill_class = %s", GetSQLValueString($row_rsClassHeroes['char_id'], "int"), GetSQLValueString($className, "text"));
	$rsClassCharSkills = mysql_query($query_rsClassCharSkills, $dbDescent) or die(mysql_error());
	$row_rsClassCharSkills = mysql_fetch_assoc($rsClassCharSkills);

	do{
		$classCharSkills[] = $row_rsClassCharSkills['skill_name'];

		$classCharSkillsInfo[$row_rsClassCharSkills['skill_name']] = array(
			"name" => $row_rsClassCharSkills['skill_name'],
			"cost" => $row_rsClassCharSkills['skill_cost'],
			"cam_id" => $row_rsClassCharSkills['cam_id'],
			"cam_name" => $row_rsClassCharSkills['cam_name'],
			); 
	} while ($row_rsClassCharSkills = mysql_fetch_assoc($rsClassCharSkills));


	$query_rsClassCharItems = sprintf("SELECT * FROM tbitems_aquired INNER JOIN tbitems ON aq_item_id = item_id INNER JOIN tbcampaign ON item_exp_id = cam_id WHERE aq_char_id = %s", GetSQLValueString($row_rsClassHeroes['char_id'], "int"));
	$rsClassCharItems = mysql_query($query_rsClassCharItems, $dbDescent) or die(mysql_error());
	$row_rsClassCharItems = mysql_fetch_assoc($rsClassCharItems);

	// echo $row_rsClassCharItems['item_name'];

	// if($row_rsClassCharItems['aq_item_sold']){
	// 	echo $row_rsClassCharItems['aq_progress_id'] . ' - ' . $row_rsClassCharItems['aq_sold_progress_id'] . '<br/>';
	// }

	do{
		if(($row_rsClassCharItems['aq_item_sold'] != 0 && $row_rsClassCharItems['aq_progress_id'] == $row_rsClassCharItems['aq_sold_progress_id']) || ($row_rsClassCharItems['aq_trade_char_id'] != NULL && $row_rsClassCharItems['aq_progress_id'] == $row_rsClassCharItems['aq_trade_progress_id'])){
			//echo 'lol';
			$classCharItemsNotKept[] = $row_rsClassCharItems['item_name'];
		} else {
			if($row_rsClassCharItems['aq_item_price_ovrd'] == '0'){
				$classCharItemsFound[] = $row_rsClassCharItems['item_name'];
			} else {
				$classCharItemsBought[] = $row_rsClassCharItems['item_name'];
			}
			$classCharItems[] = $row_rsClassCharItems['item_name'];
		}

		$classCharItemsInfo[$row_rsClassCharItems['item_name']] = array(
			"name" => $row_rsClassCharItems['item_name'],
			"act" => $row_rsClassCharItems['item_act'],
			"cam_id" => $row_rsClassCharItems['cam_id'],
			"cam_name" => $row_rsClassCharItems['cam_name'],
		); 
	} while ($row_rsClassCharItems = mysql_fetch_assoc($rsClassCharItems));

} while ($row_rsClassHeroes = mysql_fetch_assoc($rsClassHeroes));

// echo '<pre>';
// var_dump($classHeroes);
// echo '</pre>';

if (isset($classHeroes[0])){

	$classHeroesCount = count($classHeroes);
	$classHeroes = array_count_values($classHeroes);
	arsort($classHeroes);

	$classCharSkillsCount = count($classCharSkills);
	$classCharSkills = array_count_values($classCharSkills);
	arsort($classCharSkills);

	if($classType != "Overlord"){
		$classCharItemsCount = count($classCharItems);
		$classCharItems = array_count_values($classCharItems);
		$classCharItemsNotKept = array_count_values($classCharItemsNotKept);
		$classCharItemsBought = array_count_values($classCharItemsBought);
		$classCharItemsFound = array_count_values($classCharItemsFound);
		arsort($classCharItems);
	}
}

// echo '<pre>';
// var_dump($classCharItemsNotKept);
// echo '</pre>';



?>

<html>
  <head><?php
    $pagetitle = $className . " Class";
    include 'head.php'; ?>
  </head>
  <body>

    <?php
      include 'navbar.php';
      include 'banner.php';
    ?>



    <div class="container grey campaigns-overview">
      <nav>
        <ul class="pagination pagination-sm"><?php
          foreach ($classIdArray as $cid){
            echo '<li ';
            if ($cid == $classUrlID){
              echo 'class="active"';
            }
            echo '><a href="stats_class_page.php?class='. $cid . '">' . $classNameArray[$cid] . '</a></li>';
          } ?>
        </ul>
      </nav>
      <div>
        <h1 class="gaming-group"><?php echo $className . " Class"; ?></h1>
        <p class="top-lead lead text-muted">Most popular Heroes, Skills and Items for this class</p>
        <!-- <em class="campaign-intro"><p><?php echo $campaignInfoArray['story']; ?></p></em> -->
      </div>

      <?php if (!isset($classHeroesCount)){
      	echo '<p>Nobody has used this class yet!</p>';
      	die();
      }
      ?>


      <div class="row" style="margin-bottom: 30px;">
        <div class="col-xs-12">

        	<div class="col-md-4">
        		<div class="row">
            	<div class="col-sm-12">
              	<div class="panel panel-default">
                	<div class="panel-heading">
                  	<h2 class="panel-title">Heroes</h2>
                	</div>

                	<div class="panel-body"><?php
                  	foreach($classHeroes as $hero => $heroValue){ ?>
                    	<div class="row stats-row">
                      	<div class="col-xs-12"><?php
                        	$heroPerc = ($heroValue / $classHeroesCount) * 100; ?>

                        	<div class="row"> 
                          	<div class="col-md-8"> 
                            	<p><strong><?php echo $hero; ?></strong></p>
                          	</div>
	                          <div class="col-md-4 text-right"><?php 
	                            getCampaignLabel($classHeroesInfo[$hero]['cam_name'], "mini"); ?>
	                          </div>
                        	</div>
													<div class="row"> 
	                          <div class="col-md-12"><?php 
	                          	if($classType != "Overlord"){
	                            	createProgressBar($heroPerc, "of " . $className . "s", 0, ""); 
	                            } else {
	                            	createProgressBar($heroPerc, "of " . $className . " Overlords", 0, ""); 
	                            } ?>
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

        	<div class="col-md-4">
        		<div class="row">
            	<div class="col-sm-12">
              	<div class="panel panel-default">
                	<div class="panel-heading">
                  	<h2 class="panel-title">Skills</h2>
                	</div>

                	<div class="panel-body"><?php
                  	foreach($classCharSkills as $skill => $skillValue){ ?>
                    	<div class="row stats-row">
                      	<div class="col-xs-12"><?php
                        	$skillPerc = ($skillValue / $chars) * 100; ?>

                        	<div class="row"> 
                          	<div class="col-md-8"> 
                            	<p><strong><?php echo $skill . ' <small>(' . $classCharSkillsInfo[$skill]['cost'] . 'XP)</small>'; ?></strong></p>
                          	</div>
	                          <div class="col-md-4 text-right"><?php 
	                            getCampaignLabel($classCharSkillsInfo[$skill]['cam_name'], "mini"); ?>
	                          </div>
                        	</div>
													<div class="row"> 
	                          <div class="col-md-12"><?php 
	                          	if($classType != "Overlord"){
	                            	createProgressBar($skillPerc, "of " . $className . "s", 0, ""); 
	                            } else {
	                            	createProgressBar($skillPerc, "of " . $className . " Overlords", 0, ""); 
	                            } ?>
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

        	<?php if($classType != "Overlord"){ ?>
        	<div class="col-md-4">
        		<div class="row">
            	<div class="col-sm-12">
              	<div class="panel panel-default">
                	<div class="panel-heading">
                  	<h2 class="panel-title">Items</h2>
                	</div>

                	<div class="panel-body">
                		<div class="tab-panel">
	                		<ul class="nav nav-tabs" role="tablist">
						            <li role="presentation" class="active"><a href="#act1" aria-controls="home" role="tab" data-toggle="tab">Act I</a></li>
						            <li role="presentation"><a href="#act2" aria-controls="profile" role="tab" data-toggle="tab">Act II</a></li>
						          </ul>
						          <div class="tab-content">
            						<div role="tabpanel" class="tab-pane active fade in" id="act1"><?php
            							$x = 0;
			                  	foreach($classCharItems as $item => $itemValue){ 
			                  		if(($classCharItemsInfo[$item]['act'] == "Act 1" || $classCharItemsInfo[$item]['act'] == "Start") && $x < 15){ 
			                  			$x++; ?>
				                    	<div class="row stats-row">
				                      	<div class="col-xs-12"><?php
				                      		$itemPerc = ($itemValue / $chars) * 100;
				                      		if(isset($classCharItemsNotKept[$item])){
				                        	$itemPercNotKept = ($classCharItemsNotKept[$item] / $itemValue) * 100;
				                        	} else {
				                        		$itemPercNotKept = 0;
				                        	}
				                        	if(isset($classCharItemsBought[$item])){
				                        	$itemPercBought = ($classCharItemsBought[$item] / $itemValue) * 100;
				                        	} else {
				                        		$itemPercBought = 0;
				                        	}
				                        	if(isset($classCharItemsFound[$item])){
				                        	$itemPercFound = ($classCharItemsFound[$item] / $itemValue) * 100;
				                        	} else {
				                        		$itemPercFound = 0;
				                        	} ?>

				                        	<div class="row"> 
				                          	<div class="col-md-9"> 
				                            	<p><strong><?php echo $item . ' <small>(' . $classCharItemsInfo[$item]['act'] . ')</small>'; ?></strong></p>
				                          	</div>
					                          <div class="col-md-3 text-right"><?php 
					                            getCampaignLabel($classCharItemsInfo[$item]['cam_name'], "mini"); ?>
					                          </div>
				                        	</div>
				                        	<div class="row"> 
					                          <div class="col-md-12"><?php 
					                          	createProgressBar($itemPerc, "of " . $className . "s", 0, "");
					                          	// createProgressBar($itemPercBought, "bought",$itemPercFound, "found/traded");
					                          	// createProgressBar(0, "",$itemPercNotKept, "sold/traded immediately"); 
					                          	?>
					                          </div>
				                        	</div>
				                      	</div>
				                    	</div><?php
				                    }
			                  	} ?>
			                 	</div>

			                 	<div role="tabpanel" class="tab-pane fade in" id="act2"><?php
			                 		$x = 0;
			                  	foreach($classCharItems as $item => $itemValue){ 
			                  		if($classCharItemsInfo[$item]['act'] == "Act 2" && $x < 15){ 
			               					$x++; ?>
				                    	<div class="row stats-row">
				                      	<div class="col-xs-12"><?php
				                      		$itemPerc = ($itemValue / $chars) * 100;
				                      		if(isset($classCharItemsNotKept[$item])){
				                        	$itemPercNotKept = ($classCharItemsNotKept[$item] / $itemValue) * 100;
				                        	} else {
				                        		$itemPercNotKept = 0;
				                        	}
				                        	if(isset($classCharItemsBought[$item])){
				                        	$itemPercBought = ($classCharItemsBought[$item] / $itemValue) * 100;
				                        	} else {
				                        		$itemPercBought = 0;
				                        	}
				                        	if(isset($classCharItemsFound[$item])){
				                        	$itemPercFound = ($classCharItemsFound[$item] / $itemValue) * 100;
				                        	} else {
				                        		$itemPercFound = 0;
				                        	} ?>

				                        	<div class="row"> 
				                          	<div class="col-md-9"> 
				                            	<p><strong><?php echo $item . ' <small>(' . $classCharItemsInfo[$item]['act'] . ')</small>'; ?></strong></p>
				                          	</div>
					                          <div class="col-md-3 text-right"><?php 
					                            getCampaignLabel($classCharItemsInfo[$item]['cam_name'], "mini"); ?>
					                          </div>
				                        	</div>
				                        	<div class="row"> 
					                          <div class="col-md-12"><?php 
					                          	createProgressBar($itemPerc, "of " . $className . "s", 0, "");
					                          	// createProgressBar($itemPercBought, "bought",$itemPercFound, "found/traded");
					                          	// createProgressBar(0, "",$itemPercNotKept, "sold/traded immediately"); 
					                          	?>
					                          </div>
				                        	</div>
				                      	</div>
				                    	</div><?php
				                    }
			                  	} ?>
			                 	</div>

			                </div>
	                  </div>
                  
                	</div>
              	</div> 
            	</div>
          	</div>
        	</div>
        	<?php } ?>


        </div>
      </div>

    </div>
  </body>
</html>