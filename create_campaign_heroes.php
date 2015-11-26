<?php

if (isset($_SESSION["campaigndata"]['expansions'])){
  $selExpansions = $_SESSION["campaigndata"]['expansions'];
} else {
  $insertGoTo = "create_campaign.php";
  header(sprintf("Location: %s", $insertGoTo));
  die("Redirecting to create_campaign.php"); 
}


// select the heroes from the db
$query_rsHeroes = sprintf("SELECT * FROM tbheroes WHERE hero_expansion IN ($selExpansions) AND hero_type != 'Overlord' ORDER BY hero_name ASC");
$rsHeroes = mysql_query($query_rsHeroes, $dbDescent) or die(mysql_error());
$row_rsHeroes = mysql_fetch_assoc($rsHeroes);
$totalRows_rsHeroes = mysql_num_rows($rsHeroes);

$query_rsAllHeroes = sprintf("SELECT * FROM tbheroes WHERE hero_type != 'Overlord'");
$rsAllHeroes = mysql_query($query_rsAllHeroes, $dbDescent) or die(mysql_error());
$row_rsAllHeroes = mysql_fetch_assoc($rsAllHeroes);
$totalRows_rsAllHeroes = mysql_num_rows($rsAllHeroes);

$query_rsOverlords = sprintf("SELECT * FROM tbheroes WHERE hero_expansion IN ($selExpansions) AND hero_type = 'Overlord' ORDER BY hero_name ASC");
$rsOverlords = mysql_query($query_rsOverlords, $dbDescent) or die(mysql_error());
$row_rsOverlords = mysql_fetch_assoc($rsOverlords);
$totalRows_rsOverlords = mysql_num_rows($rsOverlords);

$query_rsAllOverlords = sprintf("SELECT * FROM tbheroes WHERE hero_type = 'Overlord'");
$rsAllOverlords = mysql_query($query_rsAllOverlords, $dbDescent) or die(mysql_error());
$row_rsAllOverlords = mysql_fetch_assoc($rsAllOverlords);
$totalRows_rsAllOverlords = mysql_num_rows($rsAllOverlords);

// create an array for the hero images, and the options for the hero select menu
$heroImages = array();
$overlordImages = array();
$overlordOptions = array();

// create an array for each archetype where we save the hero id.
$isWarrior = array();
$isMage = array();
$isScout = array();
$isHealer = array();

$heroImages[0] = "img/heroes/nohero.jpg";
$heroImages[1] = "img/heroes/nohero.jpg";
$heroImages[2] = "img/heroes/nohero.jpg";

$heroOptionsData = array();
$heroOptionsData[0] = array(
    "hero_id" => 0,
    "hero_name" => "No Hero",
    "hero_short" => "nohero",
    "hero_img_short" => "no_hero",
  );

// Create a list of all hero images so the jquery image switch has the correct id's
do{

  $heroImages[] = "img/heroes/" . $row_rsAllHeroes['hero_img'];

} while ($row_rsAllHeroes = mysql_fetch_assoc($rsAllHeroes));

$allHeroesList = array();
$D2HeroesList = array();
// Create a list of all heroes
do{
  $allHeroesList[] = array(
    "hero_id" => $row_rsHeroes['hero_id'],
    "hero_name" => $row_rsHeroes['hero_name'],
    "hero_type" => $row_rsHeroes['hero_type'],
    "hero_expansion" => $row_rsHeroes['hero_expansion'],
  );

  if ($row_rsHeroes['hero_expansion'] != 99){
    $D2HeroesList[] = $row_rsHeroes['hero_name'];
  }
  

} while ($row_rsHeroes = mysql_fetch_assoc($rsHeroes));

foreach ($allHeroesList as $ahl){
// Create a list of all heroes and subdivide them by archetype
  $short = $ahl['hero_name'];
  $short = strtolower($short);
  $short = str_replace(" ","-",$short);
  $short = preg_replace("/[^A-Za-z0-9_]/","",$short);

  $shortImg = $ahl['hero_name'];
  $shortImg = strtolower($shortImg);
  $shortImg = str_replace(" ","_",$shortImg);

  if ($ahl['hero_expansion'] != 99){
    $heroOptionsData[] = array(
      "hero_id" => $ahl['hero_id'],
      "hero_name" => $ahl['hero_name'],
      "hero_short" => $short,
      "hero_img_short" => $shortImg,
    );
  } else if ($ahl['hero_expansion'] == 99 && (!in_array($ahl['hero_name'],$D2HeroesList))){
    $heroOptionsData[] = array(
      "hero_id" => $ahl['hero_id'],
      "hero_name" => $ahl['hero_name'] . ' (Conversion Kit)',
      "hero_short" => $short,
      "hero_img_short" => $shortImg,
    );
  }

  // Put the hero id into the array for its archetype
  if ($ahl['hero_type'] == "Warrior"){
    $isWarrior[] = $ahl['hero_id'];
  } else if ($ahl['hero_type'] == "Mage"){
    $isMage[] = $ahl['hero_id'];
  } else if ($ahl['hero_type'] == "Scout"){
    $isScout[] = $ahl['hero_id'];
  } else if ($ahl['hero_type'] == "Healer"){
    $isHealer[] = $ahl['hero_id'];
  }

}

// Do the same for the overlord
do{
  
  $overlordImages[] = "img/heroes/large_" . $row_rsAllOverlords['hero_img'];

} while ($row_rsAllOverlords = mysql_fetch_assoc($rsAllOverlords));

do{

  $short = $row_rsOverlords['hero_name'];
  $short = strtolower($short);
  $short = str_replace(" ","-",$short);
  $short = preg_replace("/[^A-Za-z0-9_]/","",$short);
  
  $overlordOptions[] = '<option value="' . $row_rsOverlords['hero_id'] . '" id="' . $short . '">' . $row_rsOverlords['hero_name'] . '</option>';

} while ($row_rsOverlords = mysql_fetch_assoc($rsOverlords));


if ($_GET['page'] == "Classes" || $_GET['page'] == "Overlord"){

	if (!empty($_SESSION["playerdata"])){
    $pData = $_SESSION["playerdata"];
  } 
  else {
    $pData = $_SESSION["old_post"]['heroes'];
  }

	//get the players
	$query_rsGroupMembers = sprintf("SELECT * FROM tbplayerlist WHERE created_by = %s OR created_by = '0' ORDER BY player_handle ASC", GetSQLValueString($_SESSION['user']['id'], "int"));
	$rsGroupMembers = mysql_query($query_rsGroupMembers, $dbDescent) or die(mysql_error());
	$row_rsGroupMembers = mysql_fetch_assoc($rsGroupMembers);
	$totalRows_rsGroupMembers = mysql_num_rows($rsGroupMembers);

	// create an array of options for the players dropdown
	$playerOptions = array();
	$playersAvailable = array();
	do{
	  if ($row_rsGroupMembers['player_grp_id'] == $_SESSION["campaigndata"]['group_id'] || $row_rsGroupMembers['player_grp_id'] == 0){


	    $playerOptions[] = array(
	      "player_id" => $row_rsGroupMembers['player_id'],
	      "player_handle" => $row_rsGroupMembers['player_handle'],
	    );

	    // Don't include Shared option
	    if ($row_rsGroupMembers['player_id'] != 1){
	      $playersAvailable[] = $row_rsGroupMembers['player_id'];
	    }
	  }
	} while ($row_rsGroupMembers = mysql_fetch_assoc($rsGroupMembers));

	$playersAvailableImp = implode(",", $playersAvailable);


	// get the classes and its starting items/skills
	$query_rsClasses = sprintf("SELECT * FROM tbclasses INNER JOIN tbcampaign ON tbclasses.class_exp_id = tbcampaign.cam_id WHERE class_exp_id IN ($selExpansions)");
	$rsClasses = mysql_query($query_rsClasses, $dbDescent) or die(mysql_error());
	$row_rsClasses = mysql_fetch_assoc($rsClasses);
	$totalRows_rsClasses = mysql_num_rows($rsClasses);

	// Create arrays for the classes that belong to each archetype
	$classesWarrior = array();
	$classesMage = array();
	$classesScout = array();
	$classesHealer = array();
	$classesOverlord = array();
	do{
	  // FIX ME: Make value an id $row_rsClasses['class_id'] instead of the name (change in database)
	  if ($row_rsClasses['class_archetype'] == "Warrior"){
	    $classesWarrior[] = array(
	      "class_id" => $row_rsClasses['class_id'],
	      "class_name" => $row_rsClasses['class_name'],
	    ); 
	  } else if ($row_rsClasses['class_archetype'] == "Mage"){
	    $classesMage[] = array(
	      "class_id" => $row_rsClasses['class_id'],
	      "class_name" => $row_rsClasses['class_name'],
	    ); 
	  } else if ($row_rsClasses['class_archetype'] == "Scout"){
	    $classesScout[] = array(
	      "class_id" => $row_rsClasses['class_id'],
	      "class_name" => $row_rsClasses['class_name'],
	    ); 
	  } else if ($row_rsClasses['class_archetype'] == "Healer"){
	    $classesHealer[] = array(
	      "class_id" => $row_rsClasses['class_id'],
	      "class_name" => $row_rsClasses['class_name'],
	    ); 
	  } else if ($row_rsClasses['class_archetype'] == "Overlord"){
	    $classesOverlord[] = '<option value="' . $row_rsClasses['class_name'] . '">' . $row_rsClasses['class_name'] . ' (' . str_replace(" Lieutenant Pack", "", $row_rsClasses['cam_name']) . ')</option>';
	  }

	} while ($row_rsClasses = mysql_fetch_assoc($rsClasses));
}

?>


<html>
  <head><?php 
    $pagetitle = "Create Campaign";
    include 'head.php'; ?>
    <script>
	    $(document).ready(function(){
	      var pictureList = [ 
	        <?php foreach ($heroImages as $hi) { echo '"' . $hi . '", '; } ?>
	      ];

	      var pictureList2 = [ 
	        <?php foreach ($overlordImages as $oi) { echo '"' . $oi . '", '; } ?>
	      ];

	      
	      $('#overlord-select').change(function () {
	          var val = parseInt($('#overlord-select').val());
	          $('#overlord-img').attr("src",pictureList2[val]);                
	      });
	      
	      $('#hero1').change(function () {
	          var val = parseInt($('#hero1').val());
	          $('#heroimg1').attr("src",pictureList[val]);                
	      });
	      $('#hero2').change(function () {
	          var val = parseInt($('#hero2').val());
	          $('#heroimg2').attr("src",pictureList[val]);
	      });
	      $('#hero3').change(function () {
	          var val = parseInt($('#hero3').val());
	          $('#heroimg3').attr("src",pictureList[val]);

	      });
	      $('#hero4').change(function () {
	          var val = parseInt($('#hero4').val());
	          $('#heroimg4').attr("src",pictureList[val]);
	      });
	    });
    </script>
  </head>
  
  <body><?php 
    include 'navbar.php';
    include 'banner.php'; ?>

    <div class="container grey full">
    	<!-- FIX ME: Errors as array -->
      <div class="col-sm-12"><?php 
      	if ($_GET["page"] == "Heroes"){ ?>
      		<h1>The Heroes</h1>
          <p class="top-lead lead text-muted">Select the heroes embarking on this Journey in the Dark.</p><?php
      	} else if ($_GET["page"] == "Classes"){ ?>
          <h1>Classes and Group Members</h1>
          <p class="top-lead lead text-muted">Assign classes and players from your group to the chosen heroes.</p><?php
        } else if ($_GET["page"] == "Overlord"){ ?>
          <h1>The Overlord</h1>
          <p class="top-lead lead text-muted">Assign a basic deck, optional plot deck and player to the Overlord.</p><?php
        }

      	if (!empty($_SESSION["errorcode"])){ ?>
	        <div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span><?php 

		        foreach ($_SESSION["errorcode"] as $ec){ 
	            if ($ec == "Total Heroes"){ ?>
	              Select at least <strong>2 heroes</strong>. <?php 
	            }
	            if ($ec == "Duplicate Heroes"){ ?>
	              Selection contains <strong>duplicate heroes</strong>. <?php 
	            }
	            if($ec == "Duplicate Classes"){ ?>
	                Selection contains <strong>duplicate classes</strong>. <?php 
	            } 
	            if($ec == "Used Players"){ ?>
	              Selection leaves <strong>no group member available to assign to the Overlord role</strong> in the next step. (The Overlord cannot use the 'Shared' option).<br />
	              Either assign members to multiple heroes, use the shared role, add more members to your group or select less heroes.<?php 
	            } 
		        } ?>
		      </div><?php 
		    }

        if ($_GET["page"] == "Heroes"){ ?>
          <ul class="nav nav-tabs" role="tablist">
            <li role="presentation"><a href="create_campaign.php" >Campaign</a></li>
            <li role="presentation" class="active"><a href="create_campaign.php?page=Heroes">Heroes</a></li>
            <li role="presentation"><a href="#"><span class="text-muted">Classes and Players</span></a></li>
            <li role="presentation"><a href="#"><span class="text-muted">Overlord and Plot Deck</span></a></li>
          </ul><?php

        } else if ($_GET["page"] == "Classes"){ ?>
          <ul class="nav nav-tabs" role="tablist">
            <li role="presentation"><a href="create_campaign.php">Campaign</a></li>
            <li role="presentation"><a href="create_campaign.php?page=Heroes">Heroes</a></li>
            <li role="presentation" class="active"><a href="create_campaign.php?page=Classes">Classes and Players</a></li>
            <li role="presentation"><a href="#"><span class="text-muted">Overlord and Plot Deck</span></a></li>
          </ul><?php
        } else if ($_GET["page"] == "Overlord"){ ?>
          <ul class="nav nav-tabs" role="tablist">
            <li role="presentation"><a href="create_campaign.php">Campaign</a></li>
            <li role="presentation"><a href="create_campaign.php?page=Heroes">Heroes</a></li>
            <li role="presentation"><a href="create_campaign.php?page=Classes">Classes and Players</a></li>
            <li role="presentation" class="active"><a href="create_campaign.php?page=Overlord">Overlord and Plot Deck</a></li>
          </ul><?php  
        } ?>

      </div>

      <div class="col-sm-12"><p>&nbsp;</p></div>

      <?php 



      if ($_GET["page"] == "Heroes"){ ?>

        <form action="create_campaign_validate.php" method="post" name="set-heroes-form" id="set-heroes-form">

          <div class="row no-gutters">
            <div class="col-sm-3 hide-x"><?php
              $heroImage = "img/heroes/nohero.jpg";
              foreach ($heroOptionsData as $ho) { 
                if (isset($_SESSION['old_post']['heroes']) && $_SESSION['old_post']['heroes']['select_hero1'] == $ho['hero_id']){
                  $heroImage = "img/heroes/" . $ho['hero_img_short'] . ".jpg";
                 }
              } ?>
              <img class="bottom-10" id="heroimg1" src="<?php echo $heroImage; ?>" />
              <select id="hero1" name="select_hero1" class="form-control"><?php 
                foreach ($heroOptionsData as $ho) { 
                  $selSel = "";
                  if (isset($_SESSION['old_post']['heroes']) && $_SESSION['old_post']['heroes']['select_hero1'] == $ho['hero_id']){
                    $selSel = "selected='selected'";
                  }
                  echo '<option value="' . $ho['hero_id'] . '" id="' . $ho['hero_short'] . '"' . $selSel . '>' . $ho['hero_name'] . '</option>';
                } ?>
              </select>
            </div>

            <div class="col-sm-3 hide-x"><?php
              $heroImage = "img/heroes/nohero.jpg";
              foreach ($heroOptionsData as $ho) { 
                if (isset($_SESSION['old_post']['heroes']) && $_SESSION['old_post']['heroes']['select_hero2'] == $ho['hero_id']){
                  $heroImage = "img/heroes/" . $ho['hero_img_short'] . ".jpg";
                }
              } ?>
              <img class="bottom-10" id="heroimg2" src="<?php echo $heroImage; ?>" />
              <select id="hero2" name="select_hero2" class="form-control"><?php 
                foreach ($heroOptionsData as $ho) { 
                  $selSel = "";
                  if (isset($_SESSION['old_post']['heroes']) && $_SESSION['old_post']['heroes']['select_hero2'] == $ho['hero_id']){
                    $selSel = "selected='selected'";
                  }
                  echo '<option value="' . $ho['hero_id'] . '" id="' . $ho['hero_short'] . '"' . $selSel . '>' . $ho['hero_name'] . '</option>';
                } ?>
              </select>
            </div>
                    
            <div class="col-sm-3 hide-x"><?php
              $heroImage = "img/heroes/nohero.jpg";
              foreach ($heroOptionsData as $ho) { 
                if (isset($_SESSION['old_post']['heroes']) && $_SESSION['old_post']['heroes']['select_hero3'] == $ho['hero_id']){
                  $heroImage = "img/heroes/" . $ho['hero_img_short'] . ".jpg";
                }
              } ?>
              <img class="bottom-10" id="heroimg3" src="<?php echo $heroImage; ?>" />
              <select id="hero3" name="select_hero3" class="form-control"><?php 
                foreach ($heroOptionsData as $ho) { 
                  $selSel = "";
                  if (isset($_SESSION['old_post']['heroes']) && $_SESSION['old_post']['heroes']['select_hero3'] == $ho['hero_id']){
                    $selSel = "selected='selected'";
                  }
                  echo '<option value="' . $ho['hero_id'] . '" id="' . $ho['hero_short'] . '"' . $selSel . '>' . $ho['hero_name'] . '</option>';
                } ?>
              </select>
            </div>
                              
            <div class="col-sm-3 hide-x"><?php
              $heroImage = "img/heroes/nohero.jpg";
              foreach ($heroOptionsData as $ho) { 
                if (isset($_SESSION['old_post']['heroes']) && $_SESSION['old_post']['heroes']['select_hero4'] == $ho['hero_id']){
                  $heroImage = "img/heroes/" . $ho['hero_img_short'] . ".jpg";
                }
              } ?>
              <img class="bottom-10" id="heroimg4" src="<?php echo $heroImage; ?>" />
              <select id="hero4" name="select_hero4" class="form-control"><?php 
                foreach ($heroOptionsData as $ho) { 
                  $selSel = "";
                  if (isset($_SESSION['old_post']['heroes']) && $_SESSION['old_post']['heroes']['select_hero4'] == $ho['hero_id']){
                    $selSel = "selected='selected'";
                  }
                  echo '<option value="' . $ho['hero_id'] . '" id="' . $ho['hero_short'] . '"' . $selSel . '>' . $ho['hero_name'] . '</option>';
                } ?>
              </select>
            </div>
          
          </div>

          <div class="row no-gutters">
            <div class="col-sm-3"></div>
            <div class="col-sm-6">
              <input name="buttonSaveHeroes" type="submit" id="buttonSaveHeroes" class="btn btn-primary btn-block" value="Continue to Classes and Members Selection" />
              <input type="hidden" name="MM_insert" value="set-heroes-form" />
            </div>
            <div class="col-sm-3"></div>
          </div>
                      
        </form><?php 




      } else if($_GET["page"] == "Classes"){ ?>
        
        <form action="create_campaign_validate.php" method="post" name="save-heroes-form" id="save-heroes-form">

          <div class="row no-gutters"><?php 
            $ip = 1; 
            if(count($pData) < 3){ ?>
              <div class="col-sm-3 hide-x">
                <img class="bottom-10" src="img/heroes/nohero.jpg" />
              </div><?php 
            } 

            foreach ($pData as $xsh){ ?>
            	<div class="col-sm-3 hide-x">
            		<img class="bottom-10" src="img/heroes/<?php echo $xsh['img'] ?>" />

                <select id="hero<?php echo $ip; ?>-disabled" name="select_hero1" class="form-control" disabled>
                  <option id="hero-disabled"><?php echo $xsh['name']; ?></option>
                </select><?php 
                $oldClass = "class" . $ip;
                $oldPlayer = "player" . $ip;

                if (in_array($xsh['id'], $isWarrior)){ ?>
                  <select id="class_hero1" name="class<?php echo $ip; ?>" class="form-control"><?php 
                    foreach ($classesWarrior as $cw) {
                      $selSel = "";
                      if ($listClasses[$ip-1] == $cw['class_name'] || $_SESSION['old_post']['classes'][$oldClass] == $cw['class_name']){
                        $selSel = "selected='selected'";
                      }
                      echo '<option value="' . $cw['class_name'] . '"' . $selSel . '>' . $cw['class_name'] . '</option>';
                    } ?>
                  </select><?php 
                } else if (in_array($xsh['id'], $isMage)){ ?>
                  <select id="class_hero1" name="class<?php echo $ip; ?>" class="form-control"><?php 
                    foreach ($classesMage as $cm) { 
                      $selSel = "";
                      if ($listClasses[$ip-1] == $cm['class_name'] || $_SESSION['old_post']['classes'][$oldClass] == $cm['class_name']){
                        $selSel = "selected='selected'";
                      }
                      echo '<option value="' . $cm['class_name'] . '"' . $selSel . '>' . $cm['class_name'] . '</option>';
                    } ?>
                	</select><?php 
                } else if (in_array($xsh['id'], $isScout)){ ?>
                	<select id="class_hero1" name="class<?php echo $ip; ?>" class="form-control"><?php 
                    foreach ($classesScout as $cs) { 
                      $selSel = "";
                      if ($listClasses[$ip-1] == $cs['class_name'] || $_SESSION['old_post']['classes'][$oldClass] == $cs['class_name']){
                        $selSel = "selected='selected'";
                      }
                      echo '<option value="' . $cs['class_name'] . '"' . $selSel . '>' . $cs['class_name'] . '</option>';
                    } ?>
                	</select><?php 
                } else if (in_array($xsh['id'], $isHealer)){ ?>
                	<select id="class_hero1" name="class<?php echo $ip; ?>" class="form-control"><?php 
                    foreach ($classesHealer as $ch) { 
                      $selSel = "";
                      if ($listClasses[$ip-1] == $ch['class_name'] || $_SESSION['old_post']['classes'][$oldClass] == $ch['class_name']){
                        $selSel = "selected='selected'";
                      }
                      echo '<option value="' . $ch['class_name'] . '"' . $selSel . '>' . $ch['class_name'] . '</option>';
                    } ?>
                	</select><?php 
                } ?>

                <input type="hidden" name="heroId<?php echo $ip; ?>" value="<?php echo $xsh['id']; ?>" />

                <select id="class_hero1" name="player<?php echo $ip; ?>" value="player<?php echo $ip; ?>" class="form-control"><?php 
                  foreach ($playerOptions as $po) {
                    $selSel = "";
                    if ($listPlayers[$ip-1] == $po['player_id'] || $_SESSION['old_post']['classes'][$oldPlayer] == $po['player_id']){
                      $selSel = "selected='selected'";
                    }
                    echo '<option value="' . $po['player_id'] . '"' . $selSel . '>' . $po['player_handle'] . '</option>';
                  } ?>
                </select>
                        		
              </div><?php 
              $ip++; 
            } 

            if(count($pData) < 4){ ?>
              <div class="col-sm-3 hide-x">
                <img class="bottom-10" src="img/heroes/nohero.jpg" />
              </div><?php 
            } ?>   

          </div>

          <div class="row no-gutters">
            <div class="col-sm-3"></div>
            <div class="col-sm-6">
              <input name="buttonSaveClasses" type="submit" id="buttonSaveClasses" class="btn btn-primary btn-block" value="Continue to Overlord Setup" />
              <input type="hidden" name="MM_insert" value="save-heroes-form" /> 
              <input type="hidden" name="playersAvailable" value="<?php echo $playersAvailableImp; ?>" />
              	
            </div>
            <div class="col-sm-3"></div>
          </div>
     
        </form><?php 



      } else if($_GET['page'] == "Overlord"){ ?>

        <form action="create_campaign_validate.php" method="post" name="save-heroes-form" id="save-heroes-form">
          <div class="row no-gutters bottom-10">
            <div class="col-sm-12 hide-x">
              <img id="overlord-img center-block" src="img/heroes/large_overlord.jpg" />
            </div>        
          </div>
          <div class="row no-gutters">
            <div class="col-sm-3 hidden-xs"></div>
            <div class="col-sm-6">
              <select id="overlord-select" name="selectoverlord" class="form-control"><?php 
                foreach ($overlordOptions as $oo) { 
                  echo $oo; 
                } ?>
              </select><?php 

              if(count($classesOverlord) != 0){ ?>
                <select id="class_overlord" name="classoverlord" class="form-control">
                  <option value="" id="overlord">No Plot Deck</option><?php 
                  foreach ($classesOverlord as $co) { 
                    echo $co; 
                  } ?>
                </select><?php
              } 
              else { ?>
                <input type="hidden" name="classoverlord" value="" /><?php
              } 

              foreach($_SESSION["playerdata"] as $pd){
                $listPlayers[] = $pd['player'];
              } ?>
                 
              <select id="player_overlord" name="playeroverlord" value="playeroverlord" class="form-control"><?php
                foreach ($playerOptions as $po) { 
                  if (!in_array($po['player_id'],$listPlayers)){
                    if ($po['player_id'] != "1"){
                      echo '<option value="' . $po['player_id'] . '"' . $selSel . '>' . $po['player_handle'] . '</option>';
                    }
                  }
                } ?>
              </select>           
            </div>        
          </div>

          <div class="row no-gutters">
            <div class="col-sm-3 hidden-xs"></div>
            <div class="col-sm-6">
              <input name="buttonSaveOverlord" type="submit" id="buttonSaveOverlord" class="btn btn-primary btn-block" value="Save" />
              <input type="hidden" name="MM_insert" value="save-overlord-form" /> 
            </div>
            <div class="col-sm-3"></div>
          </div>
     
        </form><?php 

      } ?>
        
    </div>
  </body>
</html>