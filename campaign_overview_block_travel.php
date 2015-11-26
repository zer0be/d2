<div class="col-sm-3">
	<div class="row no-gutters campaign-controls"> <?php 
		if ($qs['travel_set'] == 0 && ($qs['act'] != "Introduction" && $qs['act'] != "Setup") && $owner == 1){ ?>
			<a class="btn btn-primary btn-block" role="button" href="campaign_overview_save.php?urlGamingID=<?php echo $gameID_obscured; ?>&part=t">
				<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Add Travel Details
			</a><?php 
		} 
		else if ($qs['travel_set'] == 0 && ($qs['act'] != "Introduction" && $qs['act'] != "Setup") && $owner == 0) { ?>
			<div class="btn btn-default btn-block" role="button" disabled="disabled">
				Travel
			</div><?php 
		}
		else if ($qs['travel_set'] == 1 || $qs['act'] == "Introduction" || $qs['act'] == "Setup") { ?>
			<div class="btn btn-default btn-block" role="button" disabled="disabled">
				Travel
			</div><?php 
		}	?>
	</div>

	<div class="row no-gutters">
		<div class="col-sm-12 col-travel"><?php 
			
			// show the travel steps only if they are set, and only if the quest is not an introduction (you don't travel for that)
			if ($qs['travel_set'] == 1 && $qs['act'] != "Introduction"){ 
				
				// loop through travel steps
				foreach ($qs['travel'] as $ts){	?>
					
					<div class="row no-gutters">
						<div class="col-xs-2">
							<!-- <div class="travel-image" style="background: url('img/<?php print $ts['type']; ?>.png') no-repeat 5px center #f9f9f9;"></div> -->
							<div class="hero-mini"><img src="img/<?php print $ts['type']; ?>.png" style="width: 30px; height: 30px;" /></div>
						</div>
					
						<div class="col-xs-10">

							<div class="travel-event">
								<?php print $ts['event']; ?>
							</div>
							<div class="travel-outcome">
								<?php print $ts['outcome']; ?>
							</div>
										
							<?php 
							if ($ts['item'] != NULL){ ?>
								<div class="travel-outcome"><?php print $ts['player']; ?> found the <?php print $ts['item']; ?>.</div><?php
							}
							?>

						</div>
								
					</div><?php
				} //close travel foreach
			} ?>
		</div>
	</div>
</div>