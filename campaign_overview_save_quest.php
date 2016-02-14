<?php

// Select the search cards
$query_rsSearchData = sprintf("SELECT * FROM tbsearch WHERE search_exp_id IN ($selExpansions) ORDER BY search_name ASC");
$rsSearchData = mysql_query($query_rsSearchData, $dbDescent) or die(mysql_error());
$row_rsSearchData = mysql_fetch_assoc($rsSearchData);
$totalRows_rsSearchData = mysql_num_rows($rsSearchData);

$selExpansionsArray = explode(",", $selExpansions);

// create array with select options
$searchCards = array();
do {
  if ((in_array(1, $selExpansionsArray) || in_array(3, $selExpansionsArray)) && $row_rsSearchData['search_id'] == 7){
    // don't show the nothing card
  } else if (in_array(1, $selExpansionsArray) && $row_rsSearchData['search_id'] == 10){
    // don't show the second secret passage card
  } else {
    // convert name to short string, to use as an id for the checkbox so we can target the treasurechest one with jquery
    $short = $row_rsSearchData['search_name'];
    $short = strtolower($short);
    $short = str_replace(" ","-",$short);
    $short = preg_replace("/[^A-Za-z0-9_]/","",$short);
    for($ia = 0; $ia < $row_rsSearchData['search_amount']; $ia++ ){
      $searchCards[] = array(
        "short" => $short,
        "search_id" => $row_rsSearchData['search_id'],
        "search_name" => $row_rsSearchData['search_name'],
        "search_value" => $row_rsSearchData['search_value'],
      );
    }
    $_SESSION['verify_values']['search_cards'][] = $row_rsSearchData['search_id'];
  }
} while ($row_rsSearchData = mysql_fetch_assoc($rsSearchData));


$query_rsSecretRooms = sprintf("SELECT * FROM tbsecretrooms WHERE secretroom_exp_id IN ($selExpansions) ORDER BY secretroom_name ASC");
$rsSecretRooms = mysql_query($query_rsSecretRooms, $dbDescent) or die(mysql_error());
$row_rsSecretRooms = mysql_fetch_assoc($rsSecretRooms);
$totalRows_rsSecretRooms = mysql_num_rows($rsSecretRooms);

// create array with select options
$secretRooms = array();
do {
  $short = $row_rsSecretRooms['secretroom_name'];
  $short = strtolower($short);
  $short = str_replace(" ","-",$short);
  $short = preg_replace("/[^A-Za-z0-9_]/","",$short);

  $secretRooms[] = '<option id="' . $short . '" value="' . $row_rsSecretRooms['secretroom_id'] . '">' . $row_rsSecretRooms['secretroom_name'] . '</option>';
} while ($row_rsSecretRooms = mysql_fetch_assoc($rsSecretRooms));


$agent = 0;
$deal = 0;
$citizen = 0;
$citizens = array();
// Search all acquired overlord cards for a 'Summon Agent' card, and/or Cut a Deal card.
foreach ($players as $h){
	if ($h['archetype'] == "Overlord"){ 
		foreach($h['skills'] as $hsk){
			if ($hsk['sold'] == 0){
				$pos = strpos($hsk['name'], 'Summon');
				if ($pos === false) {
				} else {
					$agent = 1;
					$agent_id = $hsk['id'];
				}
			}

			if($hsk['plot'] == 4){
				$citizen = 1;
				$citizens[] = array(
					"id" => $hsk['id'],
					"name" => $hsk['name']
				);
			}

			if ($hsk['skill_id'] == 203){
				$deal = 1;
			}
		}
	} 
} 

?>


<form action="campaign_overview_save_validate.php" method="post" name="quest-details-form" id="quest-details-form">
	<div class="row"><?php

		// If the travel step has been set, the user can save its details, otherwise he can look at the quest info, so we print the relevant info here
		if ($qs['travel_set'] != 0 || $qs['act'] == "Introduction"){
				echo '<div class="col-sm-12"><h1>Save ' . $qs['quest_type'] . ' Details</h1>';
				echo '<p class="top-lead lead text-muted">Save the winner, rewards, search cards and more for this ' . $qs['quest_type'] . '.</p>';
				echo '<h2>' . $qs['quest_type'] . ' Details</h2></div>';
		} else {
			echo '<div class="col-sm-12"><h1>' . $qs['quest_type'] . ' Info</h1>';
				echo '<p class="top-lead lead text-muted">View selectable monsters, frequently asked questions and errata for this ' . $qs['quest_type'] . '.</p>';

				echo '<h2>FAQ and Errata</h2>';
				echo '<h3>Errata</h3>';
				$noErrata = 0;
				foreach ($faqArray as $faq){
	        if($faq['errata_text'] != NULL){
	          if ($faq['subject'] == "quest" && in_array($qs['quest_id'], $faq['subject_id'])){
	            echo '<p><strong>' . $faq['errata_title'] . ':</strong><br />' . $faq['errata_text'] . '</p>';
	            $noErrata = 1;
	          }
	        }
	      }
	      if($noErrata == 0){
	      	echo '<p>No know Errata for this quest.</p>';
	      }

	      echo '<h3>FAQ</h3>';
	      $noFaq = 0;
	      foreach ($faqArray as $faq){
	      	if($faq['question'] != NULL){
		        if ($faq['subject'] == "quest" && in_array($qs['quest_id'], $faq['subject_id'])){
		          echo '<p><i>Q: ' . $faq['question'] . '</i><br />A: ' . $faq['answer'] . '</p>';
		          $noFaq = 1;
		        }
		      }  
	      }
	      if($noFaq == 0){
	      	echo '<p>No know FAQ for this quest.</p>';
	      }
	      
				echo '<p class="text-muted">If you believe that the above information is incomplete or incorrect, feel free to contact me at support@d2etracker.com.</p>';

				echo '<h2>Selectable Monsters</h2>'; ?>
				<p>If you need some help selecting monsters, <a href="stats_monsters.php">this page</a> offers details and statistics about all of Descent's monsters.</p><?php
				include ('campaign_overview_save_quest_monsters.php');

				echo '<h3>Monster Errata</h3>';
				$noErrata = 0;
				foreach ($faqArray as $faq){
	        if($faq['errata_text'] != NULL){
	          if ($faq['subject'] == "monster" && ($faq['subject_id'] == NULL || $faq['subject_id']) ){
	            echo '<p><strong>' . $faq['errata_title'] . ':</strong><br />' . $faq['errata_text'] . '</p>';
	            $errata = 1;
	          }
	        }
	      }
	      echo '<h3>Monster FAQ</h3>';
	      $noFaq = 0;
	      foreach ($faqArray as $faq){
	      	if($faq['question'] != NULL){
	          if ($faq['subject'] == "monster"){
	            echo '<p><i>Q: ' . $faq['question'] . '</i><br />A: ' . $faq['answer'] . '</p>';
	            $noFaq = 1;
	          }
	        }
	      }

			echo '</div>';
		}


		// Output any errors
		if (!empty($_SESSION["errorcode"])){ ?>
			<div class="col-sm-12"><?php
				foreach($_SESSION["errorcode"] as $ec){ ?>
					<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> <?php
						echo $ec; ?>
					</div><?php 
				} ?>
			</div><?php 
		} ?>


		<div class="col-sm-6"><?php

			// If the travel step has been set, the user can save the winner, otherwise this is skipped
			if ($qs['travel_set'] != 0 || $qs['act'] == "Introduction"){ ?>
				<h3>Quest Duration</h3>
				<p class="text-muted">An estimate of how long you spent playing this quest.</p>
				<select name="progress_quest_time" class="form-control">
					<option value="0">Unknown</option>
					<option value="30" <?php if(isset($_SESSION['old_post']['progress_quest_time']) && $_SESSION['old_post']['progress_quest_time'] == "30"){ echo 'selected="selected"';} ?> >About 30 minutes</option>
					<option value="60" <?php if(isset($_SESSION['old_post']['progress_quest_time']) && $_SESSION['old_post']['progress_quest_time'] == "60"){ echo 'selected="selected"';} ?> >About 1 hour</option>
					<option value="90" <?php if(isset($_SESSION['old_post']['progress_quest_time']) && $_SESSION['old_post']['progress_quest_time'] == "90"){ echo 'selected="selected"';} ?> >About 1 hour and 30 minutes</option>
					<option value="120" <?php if(isset($_SESSION['old_post']['progress_quest_time']) && $_SESSION['old_post']['progress_quest_time'] == "120"){ echo 'selected="selected"';} ?> >About 2 hours</option>
					<option value="150" <?php if(isset($_SESSION['old_post']['progress_quest_time']) && $_SESSION['old_post']['progress_quest_time'] == "150"){ echo 'selected="selected"';} ?> >About 2 hours and 30 minutes</option>
					<option value="180" <?php if(isset($_SESSION['old_post']['progress_quest_time']) && $_SESSION['old_post']['progress_quest_time'] == "180"){ echo 'selected="selected"';} ?> >About 3 hours</option>
					<option value="240" <?php if(isset($_SESSION['old_post']['progress_quest_time']) && $_SESSION['old_post']['progress_quest_time'] == "240"){ echo 'selected="selected"';} ?> >About 4 hours</option>
					<option value="300" <?php if(isset($_SESSION['old_post']['progress_quest_time']) && $_SESSION['old_post']['progress_quest_time'] == "300"){ echo 'selected="selected"';} ?> >About 5 hours</option>
					<option value="360" <?php if(isset($_SESSION['old_post']['progress_quest_time']) && $_SESSION['old_post']['progress_quest_time'] == "360"){ echo 'selected="selected"';} ?> >About 6 hours</option>
					<option value="999" <?php if(isset($_SESSION['old_post']['progress_quest_time']) && $_SESSION['old_post']['progress_quest_time'] == "999"){ echo 'selected="selected"';} ?> >More than 6 Hours</option>

				</select><?php

				echo '<h3>Winner</h3>'; ?>
				<h4>Quest Winner</h4>
				<select name="progress_quest_winner" class="form-control">
					<option value="Heroes Win" <?php if(isset($_SESSION['old_post']['progress_quest_winner']) && $_SESSION['old_post']['progress_quest_winner'] == "Heroes Win"){ echo 'selected="selected"';} ?> >Heroes Win</option>
					<option value="Overlord Wins" <?php if(isset($_SESSION['old_post']['progress_quest_winner']) && $_SESSION['old_post']['progress_quest_winner'] == "Overlord Wins"){ echo 'selected="selected"';} ?> >Overlord Wins</option>
				</select><?php

				if ($qs['monsters_enc3'] != NULL) { ?>
					<h4>Encounter 2 Winner</h4>
					<select name="progress_enc2_winner" class="form-control">
					  <option value="Heroes Win" <?php if(isset($_SESSION['old_post']['progress_enc2_winner']) && $_SESSION['old_post']['progress_enc2_winner'] == "Heroes Win"){ echo 'selected="selected"';} ?> >Heroes Win</option>
						<option value="Overlord Wins" <?php if(isset($_SESSION['old_post']['progress_enc2_winner']) && $_SESSION['old_post']['progress_enc2_winner'] == "Overlord Wins"){ echo 'selected="selected"';} ?> >Overlord Wins</option>
					</select><?php
				}

				if ($qs['monsters_enc2'] != NULL) { ?>
					<h4>Encounter 1 Winner</h4>
					<select name="progress_enc1_winner" class="form-control">
					  <option value="Heroes Win" <?php if(isset($_SESSION['old_post']['progress_enc1_winner']) && $_SESSION['old_post']['progress_enc1_winner'] == "Heroes Win"){ echo 'selected="selected"';} ?> >Heroes Win</option>
						<option value="Overlord Wins" <?php if(isset($_SESSION['old_post']['progress_enc1_winner']) && $_SESSION['old_post']['progress_enc1_winner'] == "Overlord Wins"){ echo 'selected="selected"';} ?> >Overlord Wins</option>
					</select><?php
				}

				// If the quest has a default relic as reward
				if($qs['relic_id'] != NULL){ ?>
					<h3>Hero Relic Recipiant</h3>
					<p class="text-muted">Ignored if the overlord wins</p> 
				 	<select name="progress_relic_recipiant" class="form-control"><?php 
						// loop through heroes
						foreach ($players as $h){
							$selSel = "";
							if (isset($_SESSION['old_post']['progress_relic_recipiant']) && $_SESSION['old_post']['progress_relic_recipiant'] == $h['id']){
								$selSel = "selected='selected'";
							}
							if ($h['archetype'] != "Overlord"){  ?>
								<option value="<?php print $h['id']; ?>" <?php echo $selSel; ?> ><?php print $h['name']; ?></option><?php
							}
						} //close foreach ?>
					</select><?php
				}

				if(in_array($selCampaign, $miniCampaigns)){ ?>
					<h3>Free Shop Card</h3>
					<p class="text-muted">Ignored if the overlord wins</p> 

					<h4>Item received</h4>
					<select name="random_item" class="form-control">
						<option value="empty">None</option><?php 
						foreach($availableItems as $ai) {
							echo $ai;
						} ?>
					</select>

					<h4>Hero receiving item</h4>
					<select name="random_player" class="form-control"><?php 
						foreach ($players as $h){
							if (isset($_SESSION['old_post']['random_player']) && $_SESSION['old_post']['random_player'] == $h['id']){
								$selSel = "selected='selected'";
							} else {
								$selSel = "";
							}

							if ($h['archetype'] != "Overlord"){ ?>
								<option value="<?php print $h['id']; ?>" <?php echo $selSel; ?> ><?php print $h['name']; ?></option><?php
							}
						} //close foreach ?>
					</select><?php


				}

			} ?>

		</div><?php
			if ($qs['travel_set'] != 0 || $qs['act'] == "Introduction"){

				echo '<div class="col-sm-12">';
					include 'campaign_overview_save_quest_special.php';
				echo '</div>';
			} ?>
		
	</div><?php 

	// If travel has been set, we can also show the Monsters and any Search cards that may have been found
	if ($qs['travel_set'] != 0 || $qs['act'] == "Introduction"){ 
		echo '<h3>Selected Monsters</h3>';
		include ('campaign_overview_save_quest_monsters.php'); ?>
		<div class="row no-gutters">
			<h3>Found Search Cards</h3><?php 
			foreach($searchCards as $sc) {
				$scSel = "";
				if(isset($_SESSION['old_post']['search_id']) && in_array($sc['search_id'], $_SESSION['old_post']['search_id'])){
					$scSel = "checked";
				}
				echo '<div class="col-sm-3"><div class="checkbox"><label><input type="checkbox" id="' . $sc['short'] . '" name="search_id[]" value="' . $sc['search_id'] . '"' . $scSel . '><div>' . $sc['search_name'] . '<br /><span class="search-gold">' . $sc['search_value'] . ' Gold</span></label></div></div></div>';
			} ?>
		</div>

		<div class="row"><?php 
			// If a treasure chest was found, make the treasure select block re-appear
			if (isset($_SESSION['old_post']['search_id']) && in_array(6, $_SESSION['old_post']['search_id'])){
				$style = 'style="display: block"';
			} else {
				$style = '';
			} ?>


			<div id="select-item" class="col-sm-4" <?php echo $style; ?> >

				<h4>Treasure found</h4>
				<select name="search_item" class="form-control">
					<option value="empty">None</option><?php 
					foreach($availableItems as $ai) {
						echo $ai;
					} ?>
				</select>

				<h4>Hero receiving treasure</h4>
				<select name="search_player" class="form-control"><?php 
					foreach ($players as $h){
						if (isset($_SESSION['old_post']['search_player']) && $_SESSION['old_post']['search_player'] == $h['id']){
							$selSel = "selected='selected'";
						} else {
							$selSel = "";
						}

						if ($h['archetype'] != "Overlord"){ ?>
							<option value="<?php print $h['id']; ?>" <?php echo $selSel; ?> ><?php print $h['name']; ?></option><?php
						}
					} //close foreach ?>
				</select>
			</div><?php 

			// If a secret passage was found, make the secret secret room select block re-appear
			if (isset($_SESSION['old_post']['search_id']) && (in_array(9, $_SESSION['old_post']['search_id']) || in_array(10, $_SESSION['old_post']['search_id'])) ){
				$style = 'style="display: block"';
			} else {
				$style = '';
			} ?>

			<div id="select-passage" class="col-sm-4" <?php echo $style; ?> >
				<h4>Secret Room discovered</h4>
				<select id="secretroom" name="secretroom" class="form-control">
					<option value="empty">Not explored</option><?php 
					foreach($secretRooms as $sr) {
						echo $sr;
					} ?>
				</select><?php
				echo '<div class="checkbox"><label><input type="checkbox" id="secretroom-cleared" name="secretRoomCleared" value="1"' . $scSel . '><div>' . 'The Secret Room was cleared</label></div></div>'; ?>

				<div id="select-passage-item">
					<div id="select-passage-clear">

						<h4>Reward</h4>
						<select name="secretroom_item" class="form-control">
							<option value="empty">None</option><?php 
							foreach($availableItems as $ai) {
								echo $ai;
							} ?>
						</select>

						<h4>Hero receiving reward</h4>
						<select name="secretroom_player" class="form-control"><?php 
							foreach ($players as $h){
								if (isset($_SESSION['old_post']['secretroom_player']) && $_SESSION['old_post']['secretroom_player'] == $h['id']){
									$selSel = "selected='selected'";
								} else {
									$selSel = "";
								}
								if ($h['archetype'] != "Overlord"){ ?>
									<option value="<?php print $h['id']; ?>" <?php echo $selSel; ?> ><?php print $h['name']; ?></option><?php
								}
							} //close foreach ?>
				    </select>
					</div>
				</div>
			</div>
		</div><?php 


		// If plot decks are being used, we ask for more info about them
		if ($plotStuff != 0){ ?>
			<h2>Plot Deck</h2>
			<div class="row no-gutters">
				<h3>Threat Token Balance</h3>
				<div class="col-sm-12">
					<p>The sum of all tokens gained and lost during this quest (Ex: 2 gained for defeating heroes, 5 spent using plot cards = -3 tokens). <strong>Do not count the threat tokens the Overlord would gain from this quest.</strong><br />
						<span class="text-muted">Memory boost: At the start of this quest the Overlord had <?php echo $campaign['threat']; ?> threat token<?php if($campaign['threat'] != 1){ echo "s";}?>.</span>
					</p>
				</div>
				<div id="select-threat" class="col-sm-4">
					<select name="threat_tokens" class="form-control"><?php
						$avThreat = $campaign['threat'];
						for ($i=0; $i < ($campaign['threat']); $i++) { 
							$echoThreat = ($avThreat * -1) + $i;
							$TrthSel = "";
							if(isset($_SESSION['old_post']['threat_tokens']) && $_SESSION['old_post']['threat_tokens'] == $echoThreat){ 
								$TrthSel = 'selected="selected"';
							}
							echo '<option value="' . $echoThreat . '"' . $TrthSel . ' >' . $echoThreat . ' Tokens</option>';
							$_SESSION['verify_values']["threat"][] = $echoThreat; 
						}

						if((isset($_SESSION['old_post']['threat_tokens']) && $_SESSION['old_post']['threat_tokens'] == 0) || !isset($_SESSION['old_post']['threat_tokens']) ){ 
							echo '<option value="0" selected>0 Tokens</option>';
							$_SESSION['verify_values']["threat"][] = 0; 
						}

						for ($i=1; $i < 16; $i++) { 
							$echoThreat = $i;
							$TrthSel = "";
							if(isset($_SESSION['old_post']['threat_tokens']) && $_SESSION['old_post']['threat_tokens'] == $echoThreat){ 
								$TrthSel = 'selected="selected"';
							}
							echo '<option value="' . $echoThreat . '"' . $TrthSel . ' >' . $echoThreat . ' Tokens</option>';
							$_SESSION['verify_values']["threat"][] = $echoThreat; 
						} ?>
					</select>
				</div>
			</div>

			<div class="row no-gutters"><?php

				if ($agent == 1 || $deal == 1){ ?>
					<h3>Special Actions</h3><?php
				}

				// Output select box based on them.
				if ($agent == 1){ ?>

					<p>Was the Agent defeated during this quest?</p>
					<div id="select-agent" class="col-sm-4">
						<select name="threat_agent" class="form-control">
							<option value="no">No</option>
							<option value="yes">Yes</option>
						</select>
						<input type="hidden" name="threat_agent_id" value="<?php echo $agent_id; ?>" />
					</div><?php 
				} 
				if ($deal == 1){ ?>
					<p>Did the Overlord 'Cut a Deal'?</p>
					<div id="cut-a-deal" class="col-sm-4">
						<select name="threat_deal" class="form-control">
							<option value="no">No</option>
							<option value="yes">Yes</option>
						</select>
					</div><?php 
				} 
				?>


			</div><?php 
		} ?>

		<div class="row no-gutters">
			<div class="col-sm-4">
				<input type="submit" class="btn btn-block btn-primary" value="Save" />
			</div>
		</div>
		<input type="hidden" name="token" value="<?php echo $token ; ?>" /><?php
		$_SESSION['quest_type'] = $qs['quest_type'];
		$_SESSION['relic_id'] = $qs['relic_id'];
		$_SESSION['rewards_heroes'] = $qs['rewardsHeroes'];
		$_SESSION['rewards_overlord'] = $qs['rewardsOverlord']; ?>
		<input type="hidden" name="MM_insert" value="quest-details-form" /><?php 
	} ?>
</form>