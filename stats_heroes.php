<?php
function statsHeroes($array, $limit){

  $i = 0;
  foreach ($array as $th) { 
    if ($i < $limit){ ?>
      <div class="row stats-row">
        <div class="col-xs-12">
          <div class="row"> 
            <div class="col-md-6"> 
              <p><strong><?php echo $th['hero_name']; ?></strong></p>
            </div>
            <div class="col-md-6 text-right"> 
              <?php getCampaignLabel($th['cam_name'], "normal"); ?>
            </div>
          </div>
          <div class="row"> 
            <div class="col-md-12">
              <?php createProgressBar($th['percent'], "of games", 0, ""); ?>
              <p class="text-left text-muted">
                <small>
                  Top Classes: 
                  <?php 
                    $ci = 1;
                    foreach ($th['topHeroClasses'] as $thc){
                      if ($ci <= 3){
                        echo $thc;
                        if ($ci < 3 && $ci != count($th['topHeroClasses'])){
                          echo ", ";
                        }
                        $ci++;
                      }
                    }
                  ?>
                </small>
              </p>
            </div>
          </div>
        </div>
      </div><?php
    }
    $i++;
  }

}

function statsArchetype($array, $count, $class){

  foreach($array as $tm) {
    $TopPerc = ($tm['count'] / $count) * 100; ?>
    <div class="row stats-row">
      <div class="col-xs-12">
        <div class="row"> 
          <div class="col-md-6"> 
            <p><strong><?php echo $tm['hero_name']; ?></strong></p>
          </div>
          <div class="col-md-6 text-right"> 
            <?php getCampaignLabel($tm['cam_name'], "normal"); ?>
          </div>
        </div>
        <div class="row"> 
          <div class="col-md-12">
            <?php createProgressBar($TopPerc, "of " . $class, 0, ""); ?>
            <p class="text-left text-muted">
              <small>
                Top Classes: 
                <?php 
                  $ci = 1;
                  foreach ($tm['topHeroClasses'] as $thc){
                    if ($ci <= 3){
                      echo $thc;
                      if ($ci < 3 && $ci != count($tm['topHeroClasses'])){
                        echo ", ";
                      }
                      $ci++;
                    }
                  }
                ?>
              </small>
            </p>
          </div>
        </div>
      </div>
    </div><?php
  }

}

?>

<div class="row">
    <div class="col-md-12">&nbsp;</div>
</div>
<div class="row">
  <div class="col-sm-12">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h2 class="panel-title">Most Selected Characters</h2>
      </div>

      <div class="panel-body">
        <div role="tabpanel">

          <!-- Nav tabs -->
          <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#all" aria-controls="home" role="tab" data-toggle="tab">Top 20</a></li>
            <li role="presentation"><a href="#mages" aria-controls="profile" role="tab" data-toggle="tab">Mages</a></li>
            <li role="presentation"><a href="#warriors" aria-controls="messages" role="tab" data-toggle="tab">Warriors</a></li>
            <li role="presentation"><a href="#scouts" aria-controls="settings" role="tab" data-toggle="tab">Scouts</a></li>
            <li role="presentation"><a href="#healers" aria-controls="settings" role="tab" data-toggle="tab">Healers</a></li>
          </ul>

          <!-- Tab panes -->
          <div class="tab-content">
            <div role="tabpanel" class="tab-pane active fade in" id="all">
              <?php
               statsHeroes($topheroes, 20);
              ?>
            </div>

            <div role="tabpanel" class="tab-pane fade" id="mages">
              <?php
                statsArchetype($topMages, $topMagesCount, "Mages"); 
              ?>
            </div>

            <div role="tabpanel" class="tab-pane fade" id="warriors"><?php
              statsArchetype($topWarriors, $topWarriorsCount, "Warriors"); ?>  
            </div>

            <div role="tabpanel" class="tab-pane fade" id="scouts"><?php
              statsArchetype($topScouts, $topScoutsCount, "Scouts"); ?>
            </div>

            <div role="tabpanel" class="tab-pane fade" id="healers"><?php
              statsArchetype($topHealers, $topHealersCount, "Healers"); ?>
            </div>

          </div>
        </div>
      </div>
    </div> 
  </div>
</div>