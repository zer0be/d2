<form action="campaign_overview_save_validate.php" method="post" name="quest-delete-form" id="quest-delete-form">
  <div class="row no-gutters">
    <div class="col-sm-7 confirm-deco-text">
    	<div class="row no-gutters">
    		<div class="col-sm-1">
    		</div>
    		<div class="col-sm-10">
		      <h1>Delete Campaign Phase</h1>
		      <p class="lead">Are you sure you want to delete this campaign phase? This cannot be undone.</p>
		      <input type="submit" class="btn btn-primary" value="Delete" />
	      </div>
	    </div>
    </div>
    <div class="col-sm-4 confirm-deco">
    	<img src="img/GoblinWitcher.png" />
    </div>
  </div>

  <input type="hidden" name="MM_insert" value="quest-delete-form" />
  <input type="hidden" name="token" value="<?php echo $token ; ?>" />
</form>