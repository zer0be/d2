<div class="row">
	<div class="col-sm-12">
		<h1>Shopping</h1>
		<p class="top-lead lead text-muted">Add the items the Heroes bought, sold or traded after this <?php echo $qs['quest_type'] ?>.</p>
		<h2>Available Items</h2>
	</div>
	<div class="col-sm-4">
		<form action="campaign_overview_save_validate.php" method="post" name="buy-details-form" id="buy-details-form">
			<h3 class="center">Buy Item</h3>
				<select name="bought_item" class="form-control">
					<?php 
						foreach($availableItems as $ai){
							echo $ai;
						}
					?>
				</select>
				<select name="bought_player" class="form-control">
					<?php 
						foreach($players as $pl){
							if ($pl['archetype'] != "Overlord"){
								echo '<option value="' . $pl['id'] . '">' . $pl['name'] . '</option>';
							}
						}
					?>
				</select><?php

				if (in_array(5, $rumorsInPlay)){ ?>
					<select name="bought_override" class="form-control">
						<option value="999">'Unknown Treasures' Price</option><?php 							 
							if ($currentAct == "Introduction" || $currentAct == "Act 1" || $currentAct == "Interlude"){
								echo '<option value="' . 100 . '">' . 100 . ' Gold</option>';
							} else {
								echo '<option value="' . 175 . '">' . 175 . ' Gold</option>';
							} ?>
					</select><?php
				} 

				$discounted = 0;
				if (in_array(23, $rumorsWonByHeroesAct2) && empty($_SESSION["shopItems"])){ ?>
					<p class="text-center">First item has a 25 Gold discount from 'A Friend at the Forge'.</p>
					<input type="hidden" name="bought_discount" value="25" /><?php
					$discounted = 25;
				} ?>

			<div>
				<input type="submit" value="Add" class="btn btn-block btn-primary" />
				<input type="hidden" name="token" value="<?php echo $token ; ?>" />
				<input type="hidden" name="MM_insert" value="buy-details-form" />
				<input type="hidden" name="progress_gold" value="<?php echo $availableGold - $selectionPrice; ?>" />
			</div>
			
		</form>
	</div><?php


	if (!empty($aquiredItemsList)){ ?>
		<div class="col-sm-4">
			<form action="campaign_overview_save_validate.php" method="post" name="sell-details-form" id="sell-details-form">
				<h3 class="center">Sell Item</h3>
					<select name="sold_item" class="form-control">
						<?php 
							foreach($aquiredItemsList as $ail){
								echo $ail;
							}
						?>
					</select>
				<div><input type="submit" value="Add" class="btn btn-block btn-primary" /></div>
				<input type="hidden" name="token" value="<?php echo $token ; ?>" />
				<input type="hidden" name="MM_insert" value="sell-details-form" />
				<input type="hidden" name="progress_gold" value="<?php echo $availableGold - $selectionPrice; ?>" />
			</form>
		</div><?php
	} else { ?>
		<div class="col-sm-4">
			<h3 class="center">Sell Item</h3>
			<p class="text-muted">No items eligible to be sold.</p>
		</div><?php
	}



	if (isset($aquiredItemsTradeList)){ ?>
		<div class="col-sm-4">
			<form action="campaign_overview_save_validate.php" method="post" name="trade-details-form" id="trade-details-form">
				<h3 class="center">Trade Item</h3>
					<select name="traded_item" class="form-control">
						<?php 
							foreach($aquiredItemsTradeList as $ail){
								echo $ail;
							}
						?>
					</select>
					<select name="traded_player" class="form-control">
						<?php 
							foreach($players as $pl){
								if ($pl['archetype'] != "Overlord"){
									echo '<option value="' . $pl['id'] . '">' . $pl['name'] . '</option>';
								}
							}
						?>
					</select>
				<div><input type="submit" value="Add" class="btn btn-block btn-primary" /></div>
				<input type="hidden" name="token" value="<?php echo $token ; ?>" />
				<input type="hidden" name="MM_insert" value="trade-details-form" />
				<input type="hidden" name="progress_gold" value="<?php echo $availableGold - $selectionPrice; ?>" />
			</form>
		</div><?php 
	} else { ?>
		<div class="col-sm-4">
			<h3 class="center">Trade Item</h3>
			<p class="text-muted">No items eligible for trade.</p>
		</div><?php
	} ?>
</div>

<div class="row">
	<div class="col-sm-12">
		<h2>Current Transaction</h2><?php 
		if (isset($_SESSION["errorcode"])){
		  foreach($_SESSION["errorcode"] as $ec){ ?>
		  	<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> <?php
		  		echo $ec; ?>
		  	</div><?php
			} 
		} ?>
	</div>

	<div class="col-xs-12">
		<div class="panel panel-default">
		  <div class="panel-heading">
		    <p class="panel-title"><?php
					if (($availableGold - $selectionPrice) > $availableGold){
						echo 'Available Gold: ' . '<span class="text-green">' . ($availableGold - $selectionPrice) . ' (+' . ($selectionPrice * -1) . ')</span>';
					} else {
						echo 'Available Gold: ' . ($availableGold - $selectionPrice);
					} ?>
		   	</hp>
		  </div>
		  <div class="panel-body"><?php
				foreach ($_SESSION["shopItems"] as $si){
					if ($si['action'] == "buy"){
						if($si['override'] != NULL){
							echo '<p><span class="glyphicon glyphicon-arrow-down text-red" aria-hidden="true"></span><span class="shift-glyphicon">  ' . $si['hero'] . ' will buy the <strong>' . $si['name'] . '</strong> for ' . $si['override'] . ' gold.</span></p>';
						} else {
							echo '<p><span class="glyphicon glyphicon-arrow-down text-red" aria-hidden="true"></span><span class="shift-glyphicon">  ' . $si['hero'] . ' will buy the <strong>' . $si['name'] . '</strong> for ' . $si['price'] . ' gold.</span></p>';
						}
					} else if ($si['action'] == "sell"){
						echo '<p><span class="glyphicon glyphicon-arrow-up text-green" aria-hidden="true"></span><span class="shift-glyphicon">  ' . $si['hero'] . ' will sell <strong>' . $si['name'] . '</strong> for ' . $si['price'] . ' gold.</span></p>';
					}	else if ($si['action'] == "trade"){
						echo '<p><span class="glyphicon glyphicon-transfer text-yellow" aria-hidden="true"></span><span class="shift-glyphicon">  ' . $si['hero'] . ' will trade his/her <strong>' . $si['name'] . '</strong> with <strong>' . $si['hero2'] . '</strong>.</span></p>';
					}						
				} ?>
		  </div>
		</div>
	</div>

</div>

<div class="row">
	<div class="col-sm-4">
		<form action="campaign_overview_save_validate.php" method="post" name="item-details-form" id="item-details-form">
			<div>
				<input type="submit" value="Save" class="btn btn-block btn-primary" />
				<input type="hidden" name="token" value="<?php echo $token ; ?>" />
				<input type="hidden" name="progress_gold" value="<?php echo $availableGold; ?>" />
			</div>
			<input type="hidden" name="MM_insert" value="item-details-form" />
		</form>
	</div>

</div>