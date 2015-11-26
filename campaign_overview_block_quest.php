<div class="col-sm-3">
	<div class="row no-gutters campaign-controls"><?php
		if ($qs['winner'] == NULL && ($qs['travel_set'] == 1 || $qs['act'] == "Introduction") && $owner == 1 ){ ?>
			<a class="btn btn-primary btn-block" role="button" href="campaign_overview_save.php?urlGamingID=<?php echo $gameID_obscured; ?>&part=q"><?php 
				if($qs['quest_type'] == "Quest"){
					echo '<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Add Quest Details'; 
				}	else {
					echo '<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Add Rumor Details'; 
				} ?>			
			</a><?php 
		} 
		else if ($qs['winner'] == NULL && ($qs['travel_set'] == 0 || $qs['act'] != "Introduction") && $owner == 1 ){ ?>
			<a class="btn btn-info btn-block" role="button" href="campaign_overview_save.php?urlGamingID=<?php echo $gameID_obscured; ?>&part=q"><?php 
				if($qs['quest_type'] == "Quest"){
					echo '<span class="glyphicon glyphicon-search" aria-hidden="true"></span> View Quest Info';
				}	else {
					echo '<span class="glyphicon glyphicon-search" aria-hidden="true"></span> View Rumor Info';
				} ?>	
			</a><?php 
		}
		else { ?>
			<div class="btn btn-default btn-block" role="button" disabled="disabled"><?php 
				if($qs['quest_type'] == "Quest"){
					echo 'Quest Details';
				}	else {
					echo 'Rumor Details';
				} ?>	
			</div><?php 
		}	?>
	</div>

	<div class="row no-gutters"><?php 
		$filename = "img/quests/" . $qs['img'];
		if (file_exists($filename)) {
		
		} else {
		  $filename = "img/quests/default.jpg";
		}

		?>
		<div class="col-xs-12 col-quest" style="background: url('<?php print $filename; ?>') no-repeat center;"><?php 
			if ($qs['winner'] != NULL) { 
				if($qs['quest_type'] == "Quest"){ ?>
					<div class="quest-name">
						<?php print $qs['name']; ?>
					</div><?php
				} else { ?>
					<div class="rumor-name">
						<?php print $qs['name']; ?>
					</div>
					<span class="quest-label">(Rumor)</span><?php 
				}	?>

				<div class="quest-winner">
					<?php print $qs['winner']; ?>
				</div>

				<div class="quest-monsters"><?php

					if ($qs['winner'] != "Setup"){ ?>
						<div class="row no-gutters"><?php
							$monstersstring = "";
							foreach($qs['monsters_enc1'] as $mo){
								foreach ($allMonsters as $am){
									if ($am['id'] == $mo){
										$monstersstring = $monstersstring . $am['name'] . ' - ';
									}	
								}
								
							} 
							$monstersstring = rtrim($monstersstring, " - ");
							echo 'I: ' . $monstersstring; ?>
						</div>
						<?php if($qs['monsters_enc2'] != NULL){ ?>
						<div class="row no-gutters"><?php
							$monstersstring2 = "";
							foreach($qs['monsters_enc2'] as $mo){
								foreach ($allMonsters as $am){
									if ($am['id'] == $mo){
										$monstersstring2 = $monstersstring2 . $am['name'] . ' - ';
									}	
								}
								
							} 
							$monstersstring2 = rtrim($monstersstring2, " - ");
							echo 'II: ' . $monstersstring2; ?>
						</div>
						<?php } ?>
						<?php if($qs['monsters_enc3'] != NULL){ ?>
						<div class="row no-gutters"><?php
							$monstersstring3 = "";
							foreach($qs['monsters_enc3'] as $mo){
								foreach ($allMonsters as $am){
									if ($am['id'] == $mo){
										$monstersstring3 = $monstersstring3 . $am['name'] . ' - ';
									}	
								}
								
							} 
							$monstersstring3 = rtrim($monstersstring3, " - ");
							echo 'III: ' . $monstersstring3; ?>
						</div>
						<?php } ?>
					<?php } ?>
				</div>

				<div class="quest-reward">
					<span class="quest-label">Reward</span><?php
					
					if($qs['rewardsHeroes'] != NULL || $qs['rewardsOverlord'] != NULL || $qs['relic_id'] != NULL){

							if ($qs['winner'] == "Heroes Win"){ //FIX ME: this should be a boolean maybe?
								foreach($qs['rewardsHeroes'] as $rh){
									echo '<div class="row">';
										switch ($rh[0]) {
											case "xp":
												echo $rh[1] . '<span class="quest-label">XP</span>';
												break;
											case "goldhero":
												echo ($rh[1] * (count($players) - 1)) . '<span class="quest-label"> GOLD</span>'; // -1 for overlord
												break;
											case "goldall":
												echo $rh[1] . '<span class="quest-label"> GOLD</span>';
												break;
										}
									echo '</div>';
								}

								echo '<div class="row">';
									if (isset($qs['relic_HeroesName'])){
										echo $qs['relic_HeroesName'];
									}
								echo '</div>';

							} else { //FIX ME: this should be a boolean maybe?
								foreach($qs['rewardsOverlord'] as $rh){
									echo '<div class="row">';
										switch ($rh[0]) {
											case "xp":
												echo $rh[1] . '<span class="quest-label">XP</span>';
												break;
											}
									echo '</div>';
								} 
								
								echo '<div class"row">';
									if (isset($qs['relic_OverlordName'])){
										echo $qs['relic_OverlordName'];
									}
								echo '</div>';
							}
						  
					} else {
						echo '<div class"row">';
						echo "None";
						echo '</div>';
					} ?>
				</div><?php 
			} else {

				if($qs['quest_type'] == "Quest"){ ?>
					<div class="quest-name">
						<?php print $qs['name']; ?>
					</div><?php
				} else { ?>
					<div class="rumor-name">
						<?php print $qs['name']; ?>
					</div>
					<span class="quest-label">(Rumor)</span><?php 
				}

			} ?>  
		</div>
	</div>
</div>