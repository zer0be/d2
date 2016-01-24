<div class="col-sm-3">

	<div class="row no-gutters campaign-controls"><?php
		if ($qs['act'] == "Finale" || ($qs['act'] == "Act 2" && $campaign['type'] == "mini")) { ?>
			<div class="btn btn-default btn-block" role="button" disabled="disabled">
				Items
			</div><?php 
		} else if ($qs['winner'] != NULL && $qs['items_set'] == 0  && $owner == 1){ ?>
			<a class="btn btn-primary btn-block" role="button" href="campaign_overview_save.php?urlGamingID=<?php echo $gameID_obscured; ?>&part=it">
				<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Add Items
			</a><?php 
		} 
		else if($qs['items_set'] == 2  && $owner == 1) { ?>
			<a class="btn btn-info btn-block" role="button" href="campaign_overview_save.php?urlGamingID=<?php echo $gameID_obscured; ?>&part=it">
				<span class="glyphicon glyphicon-wrench" aria-hidden="true"></span> Items
			</a><?php 
		} 
		else { ?>
			<div class="btn btn-default btn-block" role="button" disabled="disabled">
				Items
			</div><?php 
		}	?>

	</div>

	<div class="row no-gutters">

		<div class="col-sm-12 col-items"><?php  

			//if ($qs['winner'] != NULL){
				// loop through items
				foreach ($qs['items'] as $xit){
					//if it's an item
					if($xit['type'] == "Item"){

						if($xit['action'] != "trade"){ ?>

							<div class="row no-gutters">
								<div class="col-xs-2">
									<div class="hero-mini" style="background: url('img/heroes/mini_<?php print $xit['hero_img']; ?>') center;"></div>
								</div>

								<div class="col-xs-8 item-name">
									<?php print $xit['name']; ?>
								</div>

								<div class="col-xs-2 text-center"> 
									<?php 
									// if the item was bought or sold echo the cost, but with different classes
									if($xit['action'] == "buy"){
										// if an override price is set echo the override price, else echo the default one
										if($xit['override'] == NULL){
											echo '<span class="badge red">- ' . $xit['price'] . '</span>';
										} else if($xit['override'] == 0){
											echo '<span class="badge blue">Free</span>';
										} else {
											echo '<span class="badge yellow">' . $xit['override'] . '</span>';
										}
									} 
									else if ($xit['action'] == "sell"){
										echo '<span class="badge green">+ ' . $xit['price'] . '</span>';
									} else if ($xit['action'] == "box"){
										echo '<span class="badge yellow">Box</span>';
									} ?>
								</div>

							</div><?php
							
						} 
						else { ?>
							
							<div class="row no-gutters">

								<div class="col-xs-2">
									<div class="hero-mini" style="background: url('img/heroes/mini_<?php print $xit['price']; ?>') center;"></div>
								</div>

								<div class="col-xs-8 item-name">
									<?php print $xit['name']; ?>
								</div>

								<div class="col-xs-2">
									<div class="hero-mini center-block" style="background: url('img/heroes/mini_<?php print $xit['hero_img']; ?>') center;"></div>
								</div>

							</div> <?php

						}
					} // close if item
				} //close foreach items


				foreach ($qs['items'] as $xit){
					if($xit['type'] == "Relic"){
						if($xit['action'] != "trade"){ ?>

							<div class="row no-gutters">
						
								<div class="col-xs-2">
									<div class="hero-mini" style="background: url('img/heroes/mini_<?php print $xit['hero_img']; ?>') center;"></div>
								</div>
								
								<div class="col-xs-8 item-name">
									<?php print $xit['name']; ?>
								</div>

								<div class="col-xs-2 text-center">
									<?php 
									// if the item was bought or sold echo the cost, but with different classes
									if($xit['action'] == "buy"){
										echo '<span class="badge blue">Relic</span>';
									} 
									else if ($xit['action'] == "sell"){
										echo '<span class="badge green">+ ' . $xit['price'] . '</span>';
									} else if ($xit['action'] == "box"){
										echo '<span class="badge yellow">Box</span>';
									} ?>
								</div>
							</div> <?php

						} 
						else { ?>

							<div class="row no-gutters">

								<div class="col-xs-2">
									<div class="hero-mini" style="background: url('img/heroes/mini_<?php print $xit['price']; ?>') center;"></div>
								</div>

								<div class="col-xs-8 item-name">
									<?php print $xit['name']; ?>
								</div>

								<div class="col-xs-2">		
									<div class="hero-mini center-block" style="background: url('img/heroes/mini_<?php print $xit['hero_img']; ?>') center;"></div>
								</div>

							</div> <?php
						} 
					} // close if relic
				} //close foreach relics
			//} ?>
		</div>
	</div>
</div>