<div class="col-sm-3">
	<div class="row no-gutters campaign-controls"><?php
		if ($qs['act'] == "Finale" || ($qs['act'] == "Act 2" && $campaign['type'] == "mini")) { ?>
			<div class="btn btn-default btn-block" role="button" disabled="disabled">
				Skills
			</div><?php 
		} else if ($qs['winner'] != NULL && $qs['items_set'] == 1 && $qs['spendxp_set'] == 0 && $owner == 1){ ?>
			<a class="btn btn-primary btn-block" role="button" href="campaign_overview_save.php?urlGamingID=<?php echo $gameID_obscured; ?>&part=xp&page=skills">
				<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Add Skills
			</a><?php 
		} 
		else if($qs['spendxp_set'] == 2  && $owner == 1) { ?>
			<a class="btn btn-info btn-block" role="button" href="campaign_overview_save.php?urlGamingID=<?php echo $gameID_obscured; ?>&part=xp&page=skills">
				<span class="glyphicon glyphicon-wrench" aria-hidden="true"></span> Skills
			</a><?php 
		}
		else { ?>
			<div class="btn btn-default btn-block" role="button" disabled="disabled">
				Skills
			</div><?php 
		}	?>
	</div>

	<div class="row no-gutters col-skills"><?php 
		// if ($qs['winner'] != NULL) {
			// loop through skills per hero
			foreach ($qs['spendxp'] as $xsk){	?>
				<div class="row no-gutters">
					<div class="col-xs-2"><?php
						if($xsk['plot'] == 97){ ?>
							<div class="hero-mini" style="background: url('img/heroes/mini_ally_<?php print $xsk['hero_img']; ?>') center;"></div><?php
						} else { ?>
							<div class="hero-mini" style="background: url('img/heroes/mini_<?php print $xsk['hero_img']; ?>') center;"></div><?php
						} ?>
						
					</div>
					<div class="col-xs-8 skill-name">
						<?php print $xsk['name']; ?>
					</div>
					<div class="col-xs-2 text-center"><?php
						if ($xsk['action'] == "sell") { ?>
							<span class="badge green">
								<?php print $xsk['xpcost']; ?>
								<span class="skill-xp-label">XP</span>
							</span><?php
						}
						else if($xsk['plot'] == 1){
							if ($xsk['action'] == "return") { ?>
								<span class="badge yellow">Deck</span><?php
							} else { ?>
								<span class="badge red"><?php print $xsk['xpcost']; ?>
									<span class="skill-xp-label"> Thrt</span>
								</span><?php
							}
						} 
						else if($xsk['plot'] == 4){
							if ($xsk['action'] == "return") { ?>
								<span class="badge yellow">Box</span><?php
							} else { ?>
								<span class="badge blue">Free</span><?php
							}
						} 
						else if($xsk['plot'] == 98 || $xsk['plot'] == 97){ ?>
							<span class="badge blue">Free</span><?php
						} 
						else { ?>
							<span class="badge red">
								<?php print $xsk['xpcost']; ?>
								<span class="skill-xp-label">XP</span>
							</span><?php
						} ?>
					</div>
				</div><?php
			} //close foreach
 		// } ?>
	</div>
</div>