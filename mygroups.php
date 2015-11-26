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
include 'includes/protected_page.php';
include 'includes/function_logout.php';
include 'includes/function_getSQLValueString.php';


mysql_select_db($database_dbDescent, $dbDescent);

$query_rsGroupList = sprintf("SELECT * FROM tbgroup WHERE grp_startedby = %s", GetSQLValueString($_SESSION['user']['id'], "int"));
$rsGroupList = mysql_query($query_rsGroupList, $dbDescent) or die(mysql_error());
$row_rsGroupList = mysql_fetch_assoc($rsGroupList);
$totalRows_rsGroupList = mysql_num_rows($rsGroupList);

$groupInfo = array();

if ($row_rsGroupList != FALSE){

	do {

		$members = array();
		$query_rsMemberList = sprintf("SELECT * FROM tbplayerlist WHERE player_grp_id = %s", GetSQLValueString($row_rsGroupList['grp_id'], "int"));
		$rsMemberList = mysql_query($query_rsMemberList, $dbDescent) or die(mysql_error());
		$row_rsMemberList = mysql_fetch_assoc($rsMemberList);
		$totalRows_rsMemberList = mysql_num_rows($rsMemberList);

		do {

			$query_rsMemberCampaigns = sprintf("SELECT * FROM tbcharacters INNER JOIN tbheroes ON char_hero = hero_id WHERE char_player = %s", GetSQLValueString($row_rsMemberList['player_id'], "int"));
			$rsMemberCampaigns = mysql_query($query_rsMemberCampaigns, $dbDescent) or die(mysql_error());
			$row_rsMemberCampaigns = mysql_fetch_assoc($rsMemberCampaigns);
			$totalRows_rsMemberCampaigns = mysql_num_rows($rsMemberCampaigns);

			$playedHeroesImg = array();

			if ($row_rsMemberList != FALSE){
				if ($row_rsMemberCampaigns != FALSE){
					do {
						$playedHeroesImg[] =  $row_rsMemberCampaigns["hero_img"];
					} while ($row_rsMemberCampaigns = mysql_fetch_assoc($rsMemberCampaigns));

					$playedHeroesImg = array_unique($playedHeroesImg);
				}

				$members[] = array(
					"id" => $row_rsMemberList['player_id'],
					"name" => $row_rsMemberList['player_handle'],
					"added" => $row_rsMemberList['player_timestamp'],
					"campaigns" => $totalRows_rsMemberCampaigns,
					"heroes" => $playedHeroesImg,
				);		
			}	

		} while ($row_rsMemberList = mysql_fetch_assoc($rsMemberList));

	  $groupInfo[] = array(
	  	"id" => $row_rsGroupList['grp_id'],
	  	"name" => $row_rsGroupList['grp_name'],
	  	"members" => $members,
	  );
	} while ($row_rsGroupList = mysql_fetch_assoc($rsGroupList));

} else {
	$noGroups = 1;
}

?>

<html>
  <head><?php 
  	$pagetitle = "My Groups";
  	include 'head.php'; ?>
  </head>
  <body>

    <?php 
      include 'navbar.php';
      include 'banner.php'; 
    ?>

    <div class="container grey">
    	<h1>My Groups</h1>
    	<p class="top-lead lead text-muted">An overview of all your gaming groups and their members.</p><?php
    	foreach ($groupInfo as $gi){ ?>
    		<div class="row campaigns-overview">
          <div class="col-xs-12">

		    		<div class="panel panel-default">
	            <div class="panel-heading">
	               <h2 class="panel-title"><?php echo htmlentities($gi['name'], ENT_QUOTES, 'UTF-8'); ?></h2>
	            </div>
	            
	            <div class="panel-body">
	              <div class="row hidden-xs">
	                <div class="col-sm-3">
	                  <strong>Member Name</strong>
	                </div>
	                <div class="col-sm-2">
	                  <strong>Added</strong>
	                </div>
	                <div class="col-sm-2 text-center">
	                    <strong>Campaigns</strong>
	                </div>
	                <div class="col-sm-5">
	                	<strong>Played Heroes</strong>
	                </div>
	              </div><?php

                foreach ($gi["members"] as $gim){ ?>
                  <div class="row campaign-row">
                    <div class="col-sm-3 text-margin"><?php
                     	echo $gim['name']; ?>
                    </div>

                    <div class="col-sm-2 text-margin"><?php
                    	$grpTimestamp = strtotime($gim['added']); 
                      $grpDate = date('d-m-Y', $grpTimestamp);
                      $grpTime = date('h:m:s', $grpTimestamp);
                      echo $grpDate; ?>
                    </div>

                    <div class="col-sm-2 text-center text-margin"><?php
                     	echo $gim['campaigns']; ?>
                    </div>

                    <?php
                    	if (!empty($gim["heroes"])){ ?>
                    		<div class="col-sm-5"><?php
	                    		foreach ($gim["heroes"] as $gimh){ ?>
	                    			<img src="img/heroes/mini_<?php echo $gimh; ?>" /><?php
	                    		} 
	                    } else { ?>
	                    	<div class="col-sm-5 text-margin"><?php
	                    	echo "None";
	                    } ?>
                    </div>

                  </div><?php

                } ?>

	            </div>

	            <div class="panel-footer">
				        <a href="mygroups_edit.php?grpID=<?php echo $gi['id']; ?>" class="btn btn-primary ">Edit Group / Add Members</a>
				      </div>

	          </div>
	        </div>
   			</div><?php 
		      } ?>

	    	</div>
   		</div>

   </body>
</html>