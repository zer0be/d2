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
include 'includes/function_getSQLValueString.php';
include 'includes/function_createProgressBar.php';

//include campaign data
include 'campaign_data.php';

include 'stats_quests_array.php';
	
?>

<html>
	<head>
		<?php 
  	$pagetitle = "Campaign Overview";
  	include 'head.php'; ?>
		<script>
		$(document).ready(function(){
		  $("#quest-button").click(function(){
		    $("#start-block").toggle();
		    $("#start-block-reverse").hide();
		    $("#rumors-block").hide();
		  });

		  $("#rumor-button").click(function(){
		    $("#rumors-block").toggle();
		    $("#start-block").hide();
		  });

		  $("#heroes-button").click(function(){
      	$("#heroes-div").show();
      	$("#overlord-div").hide();
    	});

    	$("#overlord-button").click(function(){
      	$("#heroes-div").hide();
      	$("#overlord-div").show();
    	});

    	$('[data-toggle="tooltip"]').tooltip({'placement': 'bottom', html: true});

		});
		</script>
	</head>
	<body><?php 
		
		include 'navbar.php';

		if (!(isset($_GET['urlCharID']))) { // normal page or detail page? ?> 
			<div class="container grey full">
				<div class="row no-gutters" id="heroes-div">

					<div id="heroes" class="clearfix"><?php
						if(count($players) == 3){ ?>
							<div class="col-sm-3 hero hidden-xs" style="background: url('img/heroes/nohero.jpg'); background-position: center;">
							</div> <!-- close hero --> <?php
						}	

						// loop through heroes
						$ih = 0;
						foreach ($players as $h){
							if (($players[$ih]['archetype'] != "Overlord")){ ?>
								<a href="campaign_overview.php?urlGamingID=<?php echo $gameID * 43021; ?>&urlCharID=<?php echo $players[$ih]['id']; ?>">
									<div class="col-sm-3 col-xs-6 hero text-center" style="background-image: url('img/heroes/<?php print $players[$ih]['img']; ?>');">
										<div class="name"><?php echo htmlentities($players[$ih]['name'], ENT_QUOTES, 'UTF-8'); ?></div>
										<div class="class"><?php print $players[$ih]['class']; ?></div>
										<div class="player"><?php echo htmlentities($players[$ih]['player'], ENT_QUOTES, 'UTF-8'); ?></div>
										<div class="xp"><?php print $players[$ih]['xp']; ?><span class="xp-label">XP</span></div>
									</div> <!-- close hero -->
								</a><?php
							}
							$ih++;
						} //close foreach

						if(count($players) == 3 || count($players) == 4){ ?>
							<div class="col-sm-3 hero hidden-xs" style="background: url('img/heroes/nohero.jpg'); background-position: center;">
							</div> <!-- close hero --><?php
						}	?>
					</div> <!-- close heroes -->

					<div class="gold">
						<div class="gold-amount"><?php print $campaign['gold']; ?></div>
						<div class="gold-label">GOLD</div>
					</div> <!-- close gold -->

				</div>

				<div id="overlord-div" class="row no-gutters" style="display: none;">

					<div id="overlord" class="clearfix"><?php 
						// loop through heroes
						$ih = 0;
						foreach ($players as $h){
							if (($players[$ih]['archetype'] == "Overlord")){ ?>
								<a href="campaign_overview.php?urlGamingID=<?php echo $gameID * 43021; ?>&urlCharID=<?php echo $players[$ih]['id']; ?>">
									<div class="overlord col-xs-12 text-center" style="background: url('img/heroes/large_<?php print $players[$ih]['img']; ?>') center;">
										<div class="name"><?php print $players[$ih]['name']; ?></div>
										<div class="class"><?php 
											if ($plotStuff != 0){
												print $players[$ih]['class'];
											} ?>
										</div>
										<div class="player"><?php print $players[$ih]['player']; ?></div>
										<div class="xp"><?php print $players[$ih]['xp']; ?><span class="xp-label">XP</span></div>
									</div> <!-- close hero -->
								</a><?php
							}
							$ih++;
						} //close foreach ?>
					</div> <!-- close overlord -->

					<div class="gold"><?php 
						if ($plotStuff != 0){ ?>
							<div class="gold-amount"><?php print $campaign['threat']; ?></div>
							<div class="gold-label">THREAT</div><?php 
						} ?>
					</div> <!-- close gold -->

				</div>

				<div class="row no-gutters switchers">

					<div class="col-xs-4 hidden-xs">
					</div>
	 
					<div class="col-sm-2 col-xs-6 heroes-button text-center" id="heroes-button">
						<div class="label label-default">Heroes</div>
					</div>

					<div class="col-sm-2 col-xs-6 overlord-button text-center " id="overlord-button">
						<div class="label label-default">Overlord</div>
					</div>

					<div class="col-xs-4 hidden-xs">
					</div>

				</div>

				<div id="campaign"><?php 
		
					include 'campaign_overview_block_controls.php';
					
					// loop through quests
					$del = 1;
					foreach ($campaign['quests'] as $qs){ ?>
						<div class="row no-gutters campaign-phase phase-<?php echo $qs['id']; ?>"><?php 

							if ($owner == 1 && $del == 1){ ?>
								<div class="col-sm-12 text-center">
									<small><?php
										// <a href="#"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> FAQ & Errata</a>
										// echo ' - ';
										// <a href="#"><span class="glyphicon glyphicon-book" aria-hidden="true"></span> History</a><?php
										// echo ' - ';
										if($campaign['quests'][0]['items_set'] == 1 && $campaign['quests'][0]['spendxp_set'] == 1){ ?>																		
											<a href="campaign_overview_save.php?urlGamingID=<?php echo $gameID * 43021; ?>&part=edit"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span> Edit</a><?php
										} else { ?>
											<span class="text-muted"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span> Edit</span><?php
										}	
										if($campaign['quests'][0]['act'] != "Introduction"){ 
											echo ' - '; ?>
											<a href="campaign_overview_save.php?urlGamingID=<?php echo $gameID * 43021; ?>&part=del"><span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span> Delete</a><?php
										} ?>
										</small>
									&nbsp;
								</div><?php
								$del = 0;
							}

							include 'campaign_overview_block_travel.php';
							include 'campaign_overview_block_quest.php';
							include 'campaign_overview_block_market.php';
							include 'campaign_overview_block_spendxp.php'; ?>
						</div><?php	
					} //close quests foreach ?>
				</div> <!-- close campaign -->
			</div> <!-- close wrapper -->



			<?php
			} else {
				include 'campaign_overview_hero.php';
			} ?>
		

	</body>
</html>