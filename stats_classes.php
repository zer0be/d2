<?php
function statsClasses($array, $limit){

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
                  Top Heroes: 
                  <?php 
                    $ci = 1;
                    foreach ($th['topClassHeroes'] as $thc){
                      if ($ci <= 3){
                        echo $thc;
                        if ($ci < 3 && $ci != count($th['topClassHeroes'])){
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

function statsClassesArchetype($array, $count, $class){

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
                Top Heroes: 
                <?php 
                  $ci = 1;
                  foreach ($tm['topClassHeroes'] as $thc){
                    if ($ci <= 3){
                      echo $thc;
                      if ($ci < 3 && $ci != count($tm['topClassHeroes'])){
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
        <h2 class="panel-title">Most Selected Classes</h2>
      </div>

      <div class="panel-body">
        <div role="tabpanel">

          <!-- Nav tabs -->
          <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#Classesall" aria-controls="home" role="tab" data-toggle="tab">Top 20</a></li>
            <li role="presentation"><a href="#Classesmages" aria-controls="profile" role="tab" data-toggle="tab">Mages</a></li>
            <li role="presentation"><a href="#Classeswarriors" aria-controls="messages" role="tab" data-toggle="tab">Warriors</a></li>
            <li role="presentation"><a href="#Classesscouts" aria-controls="settings" role="tab" data-toggle="tab">Scouts</a></li>
            <li role="presentation"><a href="#Classeshealers" aria-controls="settings" role="tab" data-toggle="tab">Healers</a></li>
          </ul>

          <!-- Tab panes -->
          <div class="tab-content">
            <div role="tabpanel" class="tab-pane active fade in" id="Classesall">
              <?php
               statsClasses($topClasses, 20);
              ?>
            </div>

            <div role="tabpanel" class="tab-pane fade" id="Classesmages">
              <?php
                statsClassesArchetype($topClassesMages, $topClassesMagesCount, "Mages"); 
              ?>
            </div>

            <div role="tabpanel" class="tab-pane fade" id="Classeswarriors"><?php
              statsClassesArchetype($topClassesWarriors, $topClassesWarriorsCount, "Warriors"); ?>  
            </div>

            <div role="tabpanel" class="tab-pane fade" id="Classesscouts"><?php
              statsClassesArchetype($topClassesScouts, $topClassesScoutsCount, "Scouts"); ?>
            </div>

            <div role="tabpanel" class="tab-pane fade" id="Classeshealers"><?php
              statsClassesArchetype($topClassesHealers, $topClassesHealersCount, "Healers"); ?>
            </div>

          </div>
        </div>
      </div>
    </div> 
  </div>
</div>