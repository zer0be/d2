<div class="container grey">
	<div class="row text-center">
		<h2>Some Stats</h2>
	</div>
	<div class="row stats-area text-center">
	  <div class="col col-sm-3 col-xs-6">
	    <span class="glyphicon glyphicon-th" aria-hidden="true"></span><br />
	    <div class="stat-amount"><?php echo '<strong>' . count($uniqueGroups) . '</strong><br />'; ?></div>
	      GROUPS
	  </div>
	  <div class="col col-sm-3 col-xs-6">
	    <span class="glyphicon glyphicon-globe" aria-hidden="true"></span><br />
	    <div class="stat-amount"><?php echo '<strong>' . $totalRows_rsGamesStats . '</strong><br />'; ?></div>
	      CAMPAIGNS
	  </div>
	  <div class="col col-sm-3 col-xs-6">
	    <span class="glyphicon glyphicon-book" aria-hidden="true"></span><br />
	    <div class="stat-amount"><?php echo '<strong>' . ($totalRows_rsQuestStats - $undecidedQuests) . '</strong><br />'; ?></div>
	      QUESTS PLAYED
	  </div>
	  <div class="col col-sm-3 col-xs-6">
	    <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span><br />
	    <div class="stat-amount"><?php echo '<strong>' . $undecidedQuests . '</strong><br />'; ?></div>
	      QUESTS IN PROGRESS
	  </div>

	  <div class="col col-sm-3 col-xs-6">
	    <span class="glyphicon glyphicon-user" aria-hidden="true"></span><br />
	    <div class="stat-amount"><?php echo '<strong>' . $HeroesTotal . '</strong><br />'; ?></div>
	      HEROES
	  </div>
	  <div class="col col-sm-3 col-xs-6 visible-xs-block">
	    <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span><br />
	    <div class="stat-amount"><?php echo '<strong>' . $OverlordTotal . '</strong><br />'; ?></div>
	      OVERLORDS
	  </div>
	  <div class="col col-sm-3 col-xs-6">
	    <span class="glyphicon glyphicon-star" aria-hidden="true"></span><br />
	    <div class="stat-amount"><?php echo '<strong>' . round($HeroesQuestsPerc) . '%</strong><br />'; ?></div>
	    <!-- <div class="stat-amount"><?php echo '<strong>' . round($HeroesQuestsPercFB) . '%</strong><br />'; ?></div> -->
	      WON BY HEROES
	  </div>
	  <div class="col col-sm-3 col-xs-6">
	    <span class="glyphicon glyphicon-fire" aria-hidden="true"></span><br />
	    <div class="stat-amount"><?php echo '<strong>' . round($OverlordQuestsPerc) . '%</strong><br />'; ?></div>
	    <!-- <div class="stat-amount"><?php echo '<strong>' . round($OverlordQuestsPercFB) . '%</strong><br />'; ?></div> -->
	      WON BY OVERLORD
	  </div>
	  <div class="col col-sm-3 col-xs-6 hidden-xs">
	    <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span><br />
	    <div class="stat-amount"><?php echo '<strong>' . $OverlordTotal . '</strong><br />'; ?></div>
	      OVERLORDS
	  </div>
	</div>
</div>

	<hr />

<div class="container grey">
	<div class="row text-center">
		<h2>Info and Stats per Campaign</h2>
	</div>
	<div class="row">
    <?php do { 
      // Show a list of images that link to a page with details about every campaign
      $short = $row_rsCampaignList['cam_name'];
      $short = strtolower($short);
      $short = str_replace(" ","_",$short);
      $short = preg_replace("/[^A-Za-z0-9_]/","",$short);
      ?>
      <div class="col-md-2 col-sm-4 col-xs-6">
        <a class="thumbnail" href="campaign_page.php?campaign=<?php echo $row_rsCampaignList['cam_id']; ?>"><img src="img/campaigns/logos/<?php echo $short; ?>.jpg" /></a>
      </div>
    <?php } while ($row_rsCampaignList = mysql_fetch_assoc($rsCampaignList)); ?>
	</div>
	<div class="row">
		<div class="col-md-12 text-center">
	    <p><a href="stats_quests.php" class="btn btn-primary btn-lg">View More Stats</a></p>
	  </div>
	</div>
</div>