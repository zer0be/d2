<?php

function createProgressBar($perc1, $perc1text, $perc2, $perc2text){
	?>
		<div class="progress">
      <div class="progress-bar progress-bar-success" style="width: <?php echo $perc1; ?>%">
        <span class="sr-only"><?php echo round($perc1) . '% ' . $perc1text; ?></span>
        <?php echo round($perc1) . '% ' . $perc1text; ?>
      </div>
      <div class="progress-bar progress-bar-danger" style="width: <?php echo $perc2; ?>%">
        <span class="sr-only"><?php echo round($perc2) . '% ' . $perc2text; ?></span>
        <?php echo round($perc2) . '% ' . $perc2text; ?>
      </div>
    </div>
  <?php
}

?>