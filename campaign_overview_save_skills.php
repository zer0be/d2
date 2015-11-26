<?php 
if ($_GET['page'] == "skills"){ ?>
	<h1>Spend Experience Points</h1>
	<p class="top-lead lead text-muted">Select the skills the Heroes and Overlord bought after this <?php echo $qs['quest_type'] ?>.</p><?php 
		if (isset($_SESSION["errorcode"])){ 
			foreach ($_SESSION["errorcode"] as $ec){ ?>
			  <div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> <?php 
			    echo $ec; ?>
			  </div><?php 
			}
		} ?>
	<form action="campaign_overview_save_validate.php" method="post" name="spendxp-details-form" id="spendxp-details-form">
		<h2>Available Hero Skills</h2>
		<?php
		// loop through heroes
		foreach ($players as $h){
			if ($h['archetype'] != "Overlord"){
				echo '<div class="row">';
				echo '<div class="col-sm-12"><h3>' . $h['name'] . ' - ' . $h['xp'] . ' XP</h3></div>';
				$noSkills = array_filter($availableSkills[$h['id']]);

				if (!empty($noSkills)) {
					foreach ($availableSkills[$h['id']] as $as){
						echo $as;
					}
				} else {
					echo '<div class="col-sm-12"><p>' . $h['name'] . ' does not have enough experience to buy a skill.</p></div>';
				}
				echo '</div>';
			}
			
		} //close foreach

		?><h2>Available Overlord Cards</h2><?php
		foreach ($players as $h){
			if ($h['archetype'] == "Overlord"){
				echo '<div class="row">';
				echo '<div class="col-sm-12"><h3>' . $h['name'] . ' - ' . $h['xp'] . ' XP</h3></div>';

				$noCards = array_filter($availableOverlordCards[$h['id']]);

				if (!empty($noCards)) {
					foreach ($availableOverlordCards[$h['id']] as $as){
						echo $as;
					}
					if ($plotStuff == 1){
						for($i = 0; $i < $h['xp']; $i++){
							echo '<div class="col-sm-3"><div class="checkbox"><label><input type="checkbox" name="' . $h['id'] . '[]" value="' . 9999 . '"><div>' . "3 Threat Tokens" . '<br /><span class="search-gold">' . 1 . 'XP - <span class="text-muted">Exchange</span></span></label></div></div></div>';
						}
					}		
				} else {
					echo '<div class="col-sm-12"><p>' . "The Overlord doesn't have enough experience to buy an Overlord card.</p></div>";
				}
				echo '</div>';
			}
			
		} //close foreach
		?>
		<div class="row"><div class="col-sm-4"><input type="submit" value="Save" class="btn btn-block btn-primary" /></div></div>
		<input type="hidden" name="token" value="<?php echo $token ; ?>" />
		<input type="hidden" name="MM_insert" value="spendxp-details-form" />
		<input type="hidden" name="progress_plot" value="<?php echo $plotStuff; ?>" />
	</form>
<?php

} else if ($_GET['page'] == "plot"){ ?>
<h1>Spend Threat Tokens</h1>
<p class="top-lead lead text-muted">Select the Plot Cards the Overlord bought after this <?php echo $qs['quest_type'] ?>.</p>
<h2>Available Plot Cards</h2>	<?php 
if (isset($_SESSION["errorcode"])){ 
	foreach ($_SESSION["errorcode"] as $ec){ ?>
	  <div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span><?php
	    echo $ec; ?>
	  </div><?php 
	}
} ?>
<form action="campaign_overview_save_validate.php" method="post" name="spendthreat-details-form" id="spendthreat-details-form">
		<?php
		// loop through heroes
		foreach ($players as $h){
			if ($h['archetype'] == "Overlord"){
				echo '<div class="row">';
				echo '<div class="col-sm-12"><h3>' . $h['name'] . ' - ' . $campaign['threat'] . ' Threat tokens</h3></div>';

				$noPlot = array_filter($availablePlot[$h['id']]);

				if (!empty($noPlot)) {
					foreach ($availablePlot[$h['id']] as $as){
						echo $as;
					}
				} else {
					echo '<div class="col-sm-12"><p>' . "The Overlord doesn't have enough threat tokens to buy a Plot card.</p></div>";
				}
				echo '</div>';
			}
			
		} //close foreach ?>
		<div class="row"><div class="col-sm-4"><input type="submit" value="Save" class="btn btn-block btn-primary" /></div>
		<input type="hidden" name="token" value="<?php echo $token ; ?>" />
		<input type="hidden" name="MM_insert" value="spendthreat-details-form" />
	</form><?php 
} ?>