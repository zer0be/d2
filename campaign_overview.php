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

	//include campaign data
	include 'campaign_data.php';

	
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
	<body>
		<?php 
		 // echo '<pre>';
		 // var_dump($players);
		 // echo '</pre>';
		
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
									<div class="col-sm-3 hero text-center" style="background-image: url('img/heroes/<?php print $players[$ih]['img']; ?>');">
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
					// echo '<pre>';
					// var_dump($campaign);
					// echo '</pre>';


					if ($owner == 1){

						if (($campaign['quests'][0]['act'] == "Finale" || ($campaign['quests'][0]['act'] == "Act 2" && $campaign['type'] == "mini") ) && $campaign['quests'][0]['winner'] != NULL){ ?>

							<div class="row no-gutters">
								<div class="col-sm-12 text-center"><?php
									if($campaign['quests'][0]['winner'] == "Heroes Win"){ ?>
										<p class="lead">The Heroes won the Finale! They are the winners of this campaign!</p><?php
									} else if($campaign['quests'][0]['winner'] == "Overlord Wins"){ ?>
										<p class="lead">The Overlord won the Finale! He is the winner of this campaign!</p><?php
									} ?>
								</div>
				      </div><?php 

						}

						if($campaign['quests'][0]['items_set'] == 1 && $campaign['quests'][0]['spendxp_set'] == 1 && $campaign['quests'][0]['act'] != "Finale"){ 
							$showControls = 1;
							$enableQuests = 1;
							$questBtnClass = "btn-primary";
							$enableRumors = 0;
							$rumorBtnClass = "btn-default";
						} else if (($campaign['quests'][0]['act'] == "Finale" || ($campaign['quests'][0]['act'] == "Act 2" && $campaign['type'] == "mini")) && $campaign['quests'][0]['winner'] != NULL){ 
							$showControls = 0;
						} else { 
							$showControls = 1;
							$enableQuests = 0;
							$questBtnClass = "btn-default";
							$enableRumors = 1;
							$rumorBtnClass = "btn-primary";
						} 

						if($showControls == 1){ ?>

							<div class="row no-gutters">
								<div class="col-sm-3"></div>
								<div class="col-sm-3">
									<div class="col-sm-12">
										<div id="quest-button" class="btn btn-block <?php echo $questBtnClass; ?> form-control">Start New ...</div>
						      </div>
								</div><?php 

								if (!empty($rumorCardOptions)){
									if($campaign['type'] != "mini"){ ?>
										<div class="col-sm-3">
											<div class="col-sm-12">
												<div id="rumor-button" class="btn btn-block <?php echo $rumorBtnClass; ?> form-control">Play Rumor Cards</div>     
									    </div> 
										</div><?php 
									} 
								}?>

								
								

				      </div>

				      <div class="col-sm-3 hidden-xs"></div>
				      <div id="start-block" class="col-sm-6">
				      	<div class="well"><?php
					      	if ($enableQuests == 1){ ?>
						      	<strong>Start new quest</strong>
										<div class="row">

												<form action="<?php echo $editFormAction; ?>" method="post" name="start-quest-form" id="start-quest-form">
													<div class="col-sm-8"><?php
									        	if(!empty($rumorOptions) && $currentAct == "Interlude" && $campaign['quests'][0]['quest_type'] == "Quest"){ ?>
															<select name="progress_quest_id" class="form-control" disabled="disabled">
										            <option value="">Select Quest</option><?php 
											            foreach ($questOptions as $xqo){
											            	echo $xqo;
											            } ?>
										          </select><?php
														} else { ?>
									          	<select name="progress_quest_id" class="form-control">
										            <option value="">Select Quest</option><?php 
											            foreach ($questOptions as $xqo){
											            	echo $xqo;
											            } ?>
										          </select><?php
							      				} ?>
														
									        </div>
									        <div class="col-sm-4"><?php
									        	if(!empty($rumorOptions) && $currentAct == "Interlude" && $campaign['quests'][0]['quest_type'] == "Quest"){ ?>
															<input type="submit" value="Select" disabled="disabled" class="btn btn-block btn-info form-control" /><?php
														} else { ?>
									          	<input type="submit" value="Select" class="btn btn-block btn-info form-control" /><?php
							      				} ?>



									          <input type="hidden" name="progress_timestamp" value="" />
									          <input type="hidden" name="progress_game_id" value="<?php echo $gameID; ?>" />
									          <input type="hidden" name="MM_insert" value="start-quest-form" />
									        </div>
								        </form><?php
								        if(!empty($rumorOptions) && $currentAct == "Interlude" && $campaign['quests'][0]['quest_type'] == "Quest"){ ?>
								        	<div class="col-xs-12">
							      				<p>If one or more Act I Quest cards are still in play immediately before playing the Interlude, the heroes must choose one of them to attempt (before proceeding to the Interlude).</p>
							      			</div><?php
												} else { 

					      				} ?>
							      </div>

							      <?php if($campaign['type'] != "mini"){ 
							      	if ($currentAct == "Act 2"){ ?>
								      	<strong>Start advanced quest</strong><?php
								      } else { ?>
								      	<strong>Start new rumor quest</strong><?php
								      } ?>

								      <div class="row">
								        <form action="<?php echo $editFormAction; ?>" method="post" name="start-rumor-form" id="start-rumor-form">
								        	<div class="col-sm-8"><?php
									        	if($currentAct == "Interlude" && $campaign['quests'][0]['quest_type'] == "Rumor"){ ?>
															<select name="progress_quest_id" class="form-control" disabled="disabled"><?php 
																if ($currentAct == "Act 2"){ ?>
													      	<option value="">Select Advanced Quest</option><?php 
													      } else { ?>
													      	<option value="">Select Rumor Quest</option><?php 
													      } ?>
										          </select><?php
							      				} else { ?>
							      					<select name="progress_quest_id" class="form-control"><?php 
																if ($currentAct == "Act 2"){ ?>
													      	<option value="">Select Advanced Quest</option><?php 
													      } else { ?>
													      	<option value="">Select Rumor Quest</option><?php 
													      }  
									            	foreach ($rumorOptions as $ro){
									            		echo $ro;
									            	} ?>
										          </select><?php
							      				} ?>
									        </div>

									        <div class="col-sm-4"><?php
									        	if($currentAct == "Interlude" && $campaign['quests'][0]['quest_type'] == "Rumor"){ ?>
															<input type="submit" value="Select" disabled="disabled" class=" btn btn-block btn-info form-control" /><?php
														} else { ?>
									          	<input type="submit" value="Select" class=" btn btn-block btn-info form-control" /><?php
							      				} ?>
									          
									          <input type="hidden" name="progress_timestamp" value="" />
									          <input type="hidden" name="progress_game_id" value="<?php echo $gameID; ?>" />
									          <input type="hidden" name="MM_insert" value="start-rumor-form" />
									        </div>
								        </form>
							        </div><?php
							      }
							    } else { ?>
							    	<p class="text-muted">All steps of the previous quest or rumor need to be saved before starting a new quest or rumor.</p><?php 
							    } ?>
							  </div>

					    </div>

				      <div id="rumors-block" class="col-sm-6">
				      	<div class="well"><?php 
				      		if ($enableRumors == 1){ ?>
						      	<strong>Put a rumor in play</strong>
						      	<div class="row">
											<form action="<?php echo $editFormAction; ?>" method="post" name="add-rumor-form" id="add-rumor-form">
												<div class="col-sm-8">
													<select name="progress_rumor_card_id" class="form-control">
								            <option value="">Select Rumor card</option><?php 
								            	foreach ($rumorCardOptions as $rco){
								            		echo $rco;
								            	} ?>
								          </select>
							          </div>

							          <div class="col-sm-4">
								          <input type="submit" value="Select" class=" btn btn-block btn-info form-control" />
								          <input type="hidden" name="progress_timestamp" value="" />
								          <input type="hidden" name="progress_game_id" value="<?php echo $gameID; ?>" />
								          <input type="hidden" name="MM_insert" value="add-rumor-form" />
								        </div>
							        </form>
						        </div><?php 
							    } else { ?>
							    	<p class="text-muted">Please start a new quest or rumor quest first. 
							    		Rumors can only be played at the start of a campaign phase (which is right after saving the quest details) or before the specific step as stated on the rumor card.</p><?php 
							    } ?>
				        </div>

				      </div><?php 

						}

					}

					




					// loop through quests
					$del = 1;
					foreach ($campaign['quests'] as $qs){ ?>
						<div class="row no-gutters campaign-phase phase-<?php echo $qs['id']; ?>"><?php 

							if ($owner == 1 && $del == 1){ ?>
								<div class="col-sm-12 text-right">
									<small><?php
										if($campaign['quests'][0]['items_set'] == 1 && $campaign['quests'][0]['spendxp_set'] == 1){ ?>																		
											<a href="campaign_overview_save.php?urlGamingID=<?php echo $gameID * 43021; ?>&part=edit"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span> Edit</a><?php
										} else { ?>
											<span class="text-muted"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span> Edit</span><?php
										}
										echo ' - ';
										if($campaign['quests'][0]['act'] != "Introduction"){ ?>	  
											<a href="campaign_overview_save.php?urlGamingID=<?php echo $gameID * 43021; ?>&part=del"><span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span> Delete</a><?php
										} ?>
										</small>
									&nbsp;
								</div><?php
								$del = 0;
							}

							include 'campaign_overview_block_travel.php';
							include 'campaign_overview_block_quest.php';
							include 'campaign_overview_block_spendxp.php';
							include 'campaign_overview_block_market.php'; ?>
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