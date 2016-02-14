<?php

?>
<h1>Save Travel Details</h1>
<p class="top-lead lead text-muted">Add the travel steps the heroes encountered for this <?php echo $qs['quest_type'] ?>.</p>
<p class="text-muted"><strong>Important:</strong> It may be tempting to just select 'No Event' for each one, but since some events reward items or require gold it is important that you don't. 
	If you fail to resolve those here, there is no other way to add them later and your campaign will be broken.<br /> The statistics on this website regarding travel also become more relevant when this is completed accurately.</p>
<p class="text-muted">Hint: If you are unable to update this step during play, put the drawn travel cards aside or at the top of the deck (in the order they were drawn) when storing the game. 
	This way you can easily find them again when updating your campaign.</p>

<?php
$tempAmount = count($_SESSION['travelevents']);
$travelSteps = explode(',', $qs['travel_steps']);

if(isset($_SESSION['addedstep'])){
	$tempSteps = array();
	$ti = 1;
	foreach ($travelSteps as $add){
		$tempSteps[] = $add;
		if($ti == $_SESSION['addedstep']){
			$tempSteps[] = $add;
		} 
		$ti++;
	}

	$travelSteps = $tempSteps;
}
$travelAmount = count(explode(',', $qs['travel_steps']));

$tmpi = 0;


foreach ($travelSteps as $ts){ ?>

	<div class="row no-gutters">
		<span class="glyphicon glyphicon-option-vertical travel-glyphicon" aria-hidden="true"></span>
	</div>

	<div class="row no-gutters">
		<div class="col-xs-2">
			<div class="save-travel-step" style="background: url('img/<?php echo $ts ?>.png') no-repeat center;"></div>
		</div><?php 


		if ($_SESSION['lastspecial'] == NULL){
			if ($tmpi <= $tempAmount) { ?>

				<form action="campaign_overview_save_validate.php" method="post" name="travel-substep-form" id="travel-substep-form">
					<div class="col-sm-5 col-xs-10"><?php 

						if ($tmpi == ($tempAmount)){ ?>

							<select name="travel_step" class="form-control"><?php 
								foreach($allTravel as $ats){
									if (($ats['type'] == $ts || $ats['type'] == "all") && !(in_array($ats['card'],$usedCards))){																
										echo $ats['option'];
									}
									if ($ats['type'] == "item" && !(in_array($ats['card'],$usedCards))){	
										if (in_array($ats['card'],$aquiredItems))	{
											echo $ats['option'];
										}													
									}
								} // close ats foreach ?>
							</select><?php 

						} else { // close if tmpi == ?>

							<select class="form-control" disabled="disabled">
								<?php echo $_SESSION['travelevents'][$tmpi]['option']; ?>
							</select><?php 

						} // close else tmpi ?>

					</div>

					<div class="col-sm-4 col-xs-10 col-xs-offset-2 col-sm-offset-0"><?php 
						if ($tmpi == ($tempAmount)){ ?>
							<input type="submit" value="Add" class="btn btn-block btn-primary" />
							<input type="hidden" name="token" value="<?php echo $token ; ?>" />
							<input type="hidden" name="total_step_add" value="<?php echo $travelAmount; ?>" />
							<input type="hidden" name="current_step_add" value="<?php echo $tempAmount; ?>" />
							<input type="hidden" name="MM_insert" value="travel-substep-form" /><?php 
						} // close if tmpi ?>
					</div>

					<div class="col-sm-1 hidden-xs">
					</div>
				</form><?php 

			} // close if tmpi <= 

		} else { // close lastspecial == NULL

			if ($tmpi < $tempAmount) { ?>

				<form action="campaign_overview_save_validate.php" method="post" name="travel-substep-details-form" id="travel-substep-details-form">
					<div class="col-sm-5 col-xs-10">

						<select class="form-control" disabled="disabled"><?php 
							echo $_SESSION['travelevents'][$tmpi]['option']; ?>
						</select><?php 

						if ($tmpi == ($tempAmount - 1)){ 

							if ($_SESSION['lastspecialtype'] == "gold"){ 
								$query_rsGold = sprintf("SELECT * FROM tbgames WHERE game_id = %s", GetSQLValueString($gameID, "int"));
								$rsGold = mysql_query($query_rsGold, $dbDescent) or die(mysql_error());
								$row_rsGold = mysql_fetch_assoc($rsGold); ?>
								
								<select name="travel_gold" class="form-control"><?php 
									if ($row_rsGold['game_gold'] >= 25){ ?>
										<option value="">No Gold Spent</option>
										<option value="-25">25 Gold Spent</option><?php
									} else { ?>
										<option value="">No Gold Spent (Not enough available)</option><?php 
									} ?>
								</select><?php 
							} // close == gold 

							if ($_SESSION['lastspecialtype'] == "goldskp"){ 
								$query_rsGold = sprintf("SELECT * FROM tbgames WHERE game_id = %s", GetSQLValueString($gameID, "int"));
								$rsGold = mysql_query($query_rsGold, $dbDescent) or die(mysql_error());
								$row_rsGold = mysql_fetch_assoc($rsGold); ?>
								
								<select name="travel_goldskp" class="form-control"><?php 
									if ($row_rsGold['game_gold'] >= 25){ ?>
										<option value="">No Gold Spent</option>
										<option value="-25">25 Gold Spent</option><?php
									} else { ?>
										<option value="">No Gold Spent (Not enough available)</option><?php 
									} ?>
								</select><?php 
							} // close == gold 

							if ($_SESSION['lastspecialtype'] == "golditem"){ ?>	
								<select name="travel_gold" class="form-control">
									<option value="">No Gold Gained</option><?php
									$ig = 25;
									foreach ($players as $h){ 
										if ($h['archetype'] != "Overlord"){ ?>
											<option value="<?php echo $ig; ?>"><?php echo $ig; ?> Gold Gained</option><?php
											$ig += 25; 
										}
									} ?>
								</select>

								<select name="travel_item" class="form-control">
									<option value="">No Item Acquired</option><?php
									foreach($availableItems as $ai){
										echo $ai;
									}	?>
								</select>

								<select name="travel_player" class="form-control"><?php 
									foreach ($players as $h){ 
										if ($h['archetype'] != "Overlord"){ ?>
											<option value="<?php print $h['id']; ?>"><?php print $h['name']; ?></option><?php 
										}
									} ?>
								</select><?php 
							} // close == golditem

							if ($_SESSION['lastspecialtype'] == "goldchk"){ ?>	
								<select name="travel_gold" class="form-control">
									<option value="">No Gold Gained</option>
									<option value="25">25 Gold Gained</option>
								</select><?php 
							} // close == goldchk

							if ($_SESSION['lastspecialtype'] == "skipchk"){ ?>
								<select name="travel_skipchk" class="form-control">
									<option value="0">Failed</option>
									<option value="1">Succeeded</option>
								</select><?php 
							} // close == skipchk

							if ($_SESSION['lastspecialtype'] == "item"){ ?>
								<select name="travel_item" class="form-control">
									<option value="">No Item Acquired</option><?php
									foreach($availableItems as $ai){
										echo $ai;
									}	?>
								</select>

								<select name="travel_player" class="form-control"><?php 
									foreach ($players as $h){ 
										if ($h['archetype'] != "Overlord"){ ?>
											<option value="<?php print $h['id']; ?>"><?php print $h['name']; ?></option><?php 
										}
									} ?>
								</select><?php 
							} // close == item

						} // close $tmpi == ($tempAmount - 1) ?>

					</div>

					<div class="col-sm-4 col-xs-10 col-xs-offset-2 col-sm-offset-0"><?php 
						if ($tmpi == ($tempAmount - 1)){ ?>
							<input type="submit" value="Update" class="btn btn-block btn-primary" />
							<input type="hidden" name="token" value="<?php echo $token ; ?>" />
							<input type="hidden" name="total_step_update" value="<?php echo $travelAmount; ?>" />
							<input type="hidden" name="current_step_update" value="<?php echo $tempAmount; ?>" />
							<input type="hidden" name="MM_insert" value="travel-substep-details-form" /><?php 
						} ?>
					</div>

					<div class="col-sm-1 hidden-xs"></div>

				</form><?php 
			} // close $tmpi < $tempAmount

		} // close lastspecial == NULL

		$tmpi++; ?>	

	</div><?php 

} // close travel foreach
?>

<div class="row no-gutters">&nbsp;</div>

<?php if ($tempAmount >= $travelAmount && $_SESSION['lastspecial'] == NULL){ ?>
	<div class="row no-gutters">
		<div class="col-sm-4">
			<form action="campaign_overview_save_validate.php" method="post" name="travel-step-form" id="travel-step-form">
				<div><input type="submit" value="Save" class="btn btn-block btn-primary" /></div>
				<input type="hidden" name="token" value="<?php echo $token ; ?>" />
				<input type="hidden" name="MM_insert" value="travel-step-form" />
			</form>
		</div>
	</div><?php 

} else { ?>
		<div class="row no-gutters">
		<div class="col-sm-4">
			<div><a class="btn btn-block btn-default" disabled="disabled" />Save</a></div>
			<p></p>
		</div>
	</div><?php 
}

// var_dump($usedCards);

// echo '<pre>';
// var_dump($_SESSION['travelevents']);
// echo '</pre>';
?>