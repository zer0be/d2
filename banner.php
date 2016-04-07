<?php

$isfront = "";
if(isset($front)){
	$isfront = "front";
}

?>


<div class="container-fluid descent-header <?php echo $isfront; ?> hidden-xs text-center" style="background: url('img/descentbanner2.jpg') no-repeat center; background-size: cover; background-attachment: fixed;">
	<a href='index.php'>
		<img class="banner-logo" src="img/logo_only.png" />
	</a><?php

	if(isset($front)){ ?>
  	<div class="container"><?php
			// if the user is still flagged as new or there is no user logged in then show the 'Get Started' block
			if ((isset($_SESSION['user']) && $_SESSION['user']['new'] == 1) || !isset($_SESSION['user'])) { ?>
			  <div class="row banner-buttons">
			    <p class="lead">Welcome to the Unofficial Campaign Tracker for the boardgame Descent: Journeys in the Dark - 2nd Edition. <br />
			    Here you can record your progress through it's various campaigns, as well as view stats about the game based on the campaigns that have been played.</p>
			    <p><a href="new_campaign_tutorial.php" class="btn btn-primary btn-lg">Get Started!</a></p>
			    <p><a href="login.php">or Log In</a></p>
			  </div><?php
			} else { ?>
		  	<div class="row banner-buttons">
		  		<div class="col col-sm-4 text-center">
		  			<a href="campaign_overview.php?urlGamingID=<?php echo $lastcampaign * 43021; ?>" >
			  			<span class="glyphicon pad glyphicon-play" aria-hidden="true"></span><br />
			  			<h2>CONTINUE CAMPAIGN</h2>
			  			<p>Continue your most recently updated campaign.</p>
			  		</a>
		  		</div>

		  		<div class="col col-sm-4 text-center">
		  			<a href="create_campaign.php">
			  			<span class="glyphicon pad glyphicon-plus" aria-hidden="true"></span><br />
			  			<h2>NEW CAMPAIGN</h2>
			  			<p>Start a new adventure in the lands of Terrinoth!</p>
			  		</a>
		  		</div>

		  		<div class="col col-sm-4 text-center">
		  			<a href="mycampaigns.php">
			  			<span class="glyphicon glyphicon-list" aria-hidden="true"></span><br />
			  			<h2>MY CAMPAIGNS</h2>
			  			<p>View all of your campaigns.</p>
		  			</a>
		  		</div>
		  	</div><?php
		  } ?>
	  </div><?php
	} ?>
</div>

<?php
if(isset($front)){ ?>
	<div class="visible-xs-block descent-header-mobile <?php echo $isfront; ?> text-center" style="background: url('img/descentbanner_mobile2.jpg') no-repeat center;"><?php
} else { ?>
	<div class="visible-xs-block descent-header-mobile not-front text-center" style="background: url('img/descentbanner_mobile2.jpg') no-repeat top center;"><?php
} ?>
	<a href='index.php'>
		<img class="banner-logo" style="width: 90%" src="img/logo_only_m.png" />
	</a><?php
  if(isset($front)){ ?>
  	<div class="container"><?php
			// if the user is still flagged as new or there is no user logged in then show the 'Get Started' block
			if ((isset($_SESSION['user']) && $_SESSION['user']['new'] == 1) || !isset($_SESSION['user'])) { ?>
			  <div class="row banner-buttons">
			    <p class="lead">Welcome to the Unofficial Campaign Tracker for the boardgame Descent: Journeys in the Dark - 2nd Edition.</p>
			    <p><a href="new_campaign_tutorial.php" class="btn btn-primary">Get Started!</a><br />
			    <a href="login.php">or Log In</a></p>
			  </div><?php
			} else { ?>
		  	<div class="row banner-buttons">
		  		<div class="col col-xs-12 text-center">
		  			<a class="btn btn-primary" href="campaign_overview.php?urlGamingID=<?php echo $lastcampaign * 43021; ?>">
		  				<span class="glyphicon pad glyphicon-play" aria-hidden="true"></span> CONTINUE CAMPAIGN
		  			</a>
		  		</div>
		  		<div class="col col-xs-12 text-center">
		  			<a class="btn btn-primary" href="create_campaign.php">
		  				<span class="glyphicon pad glyphicon-plus" aria-hidden="true"></span> NEW CAMPAIGN
			  		</a>
			  	</div>
			  	<div class="col col-xs-12 text-center">
		  			<a class="btn btn-primary" href="mycampaigns.php">
			  			<span class="glyphicon glyphicon-list" aria-hidden="true"></span> MY CAMPAIGNS
		  			</a>
		  		</div>
		  	</div><?php
		  } ?>
	  </div><?php
	} ?>
</div>

