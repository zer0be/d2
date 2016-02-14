<h1>Edit Campaign Phase</h1>
<p>Here you can undo any actions performed during the skills and items step, if applicable.</p><p>You can also reopen the skills/items step for further editing of those steps.</p>
<form action="campaign_overview_save_validate.php" method="post" name="quest-edit-form" id="quest-edit-form">
  <div class="row no-gutters"><?php
    if (!empty($skillsOptions)){ ?>
      <h2>Undo Skills/Cards</h2>
      <div class="col-sm-12"><?php
      	foreach ($skillsOptions as $sk){
      		echo $sk;
      	} ?>
      </div><?php
    }

  	if (!empty($plotOptions)){ ?>
	    <h2>Undo Plot Cards</h2>
	    <div class="col-sm-12"><?php
	    	foreach ($plotOptions as $pl){
	    		echo $pl;
	    	} ?>
	    </div><?php
	  }

    if (empty($tradeOptions) && empty($soldOptions) && empty($boughtOptions)){ 

    } else {
    ?>
  		<h2>Undo Items</h2><?php
  		if (isset ($_SESSION["errorcode"])){ ?>
        <div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
          <?php if($_SESSION["errorcode"] == "Gold"){

          } ?>
        </div><?php 
      } ?>

      <div class="col-sm-12"><?php
      	foreach ($tradeOptions as $tr){
      		echo $tr;
      	}
      	foreach ($soldOptions as $sd){
      		echo $sd;
      	}
      	foreach ($boughtOptions as $bt){
      		echo $bt;
      	} ?>
      </div><?php
    } ?>


    <h2>Open steps for editing</h2>
    <div>
      <div class="checkbox"><label><input type="checkbox" name="open_items" value="true"><div>Open Items Step</label></div></div>
      <div class="checkbox"><label><input type="checkbox" name="open_skills" value="true"><div>Open Skills Step</label></div></div>    
    </div>
    <div class="col-sm-4">
      <input type="submit" class="btn btn-block btn-primary" value="Undo / Open" />
    </div>
  </div>

  <input type="hidden" name="MM_insert" value="quest-edit-form" />
  <input type="hidden" name="token" value="<?php echo $token ; ?>" />
</form>