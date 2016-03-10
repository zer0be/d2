<div class="row">
  <div class="col-md-12 text-center">
    <h2>Recently Updated Groups</h2>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="row hidden-xs">
      <div class="col-sm-3">
        <strong>Group Name</strong>
      </div>
      <div class="col-sm-2">
        <strong>User</strong>
      </div>
      <div class="col-sm-3">
        <strong>Location</strong>
      </div>
      <div class="col-sm-2 text-center">
        <strong>Campaigns</strong>
      </div>
      <div class="col-sm-2">
        <strong>Updated</strong>
      </div>
    </div><?php

    $xg = 1;
    foreach ($gamingGroups as $gg) { 
      // Show the x most recently updated groups
      if ($xg <= 20) {
        if ($xg < 10) { 
          echo '<div>';
        } else {
          echo '<div class="hidden-xs">';
        } ?>
          <div class="row campaign-row">
            <div class="col-sm-3">
              <span class="visible-xs-inline"><strong>Group Name: </strong></span><a href="mycampaigns.php?urlGgrp=<?php echo $gg['grp_id']; ?>&view=group"><strong><?php echo htmlentities($gg['grp_name'], ENT_QUOTES, 'UTF-8'); ?></strong></a><br />
            </div>
            <div class="col-sm-2">
              <span class="visible-xs-inline"><strong>User: </strong></span><?php echo htmlentities($gg['dm'], ENT_QUOTES, 'UTF-8');
                if(isset($gg['special'])){
                  echo ' <span title="' . $gg['special'] . '" class="special-' . $gg['special'] . '""><div class="glyphicon glyphicon-star shift-glyphicon" aria-hidden="true"></div></span>';
                }

              ?>
            </div>
            <div class="col-sm-3">
              <span class="visible-xs-inline"><strong>Location: </strong></span><?php 
              if (isset($gg['grp_city'])){
                echo ucfirst(htmlentities($gg['grp_city'], ENT_QUOTES, 'UTF-8'));
              } else {
                echo "<i>Somewhere</i>";
              }
              echo ", ";
              if (isset($gg['grp_state_country'])){
                echo ucfirst(htmlentities($gg['grp_state_country'], ENT_QUOTES, 'UTF-8'));
              } else {
                echo "<i>Someplace</i>";
              } ?>
            </div>
            <div class="col-sm-2 text-center hidden-xs">
              <?php echo $gg['campaigns']; ?>
            </div>
            <div class="col-sm-2 visible-xs-inline">
              <strong>Campaigns: </strong><?php echo $gg['campaigns']; ?>
            </div>

            <div class="col-sm-2">
              <span class="visible-xs-inline"><strong>Updated: </strong></span><?php 
              $grpTimestamp = strtotime($gg['timestamp']); 
              $grpDate = date('d-m-Y', $grpTimestamp);
              $grpTime = date('Gi.s', $grpTimestamp);
              echo $grpDate; ?>
            </div>
          </div>
        </div><?php
      }
      $xg++;
    } ?>
  </div>
</div>
