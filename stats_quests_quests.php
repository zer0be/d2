<?php 

$test = array();
foreach ($statsArray as $key => $row) {
  $test[$key]  = $row['count']; 
}
array_multisort($test, SORT_DESC, $statsArray);

function questStats($statsArray, $expansion_id){

  foreach ($statsArray as $sa){

    if ($sa['expansion_id'] == $expansion_id){

      if (($sa['hero_wins'] + $sa['overlord_wins']) > 0){
        $QuestWins = calcQuestWins($sa['overlord_wins'], $sa['hero_wins']);?>

        <div class="row stats-row">
          <div class="col-xs-12">

            <div class="row">
              <div class="col-md-6">
                <p><strong><?php echo $sa['quest_name']; ?></strong></p>
              </div>
              <div class="col-md-6 text-right"> 
                <?php getCampaignLabel($sa['quest_campaign'], "normal"); ?>
              </div>
            </div>
    
            <div class="row">
              <div class="col-md-6">         
                <p class="text-left text-muted"><small><?php echo $QuestWins['HeroText']; ?></small></p>
              </div>
              <div class="col-md-6">         
                <p class="text-right text-muted"><small><?php echo $QuestWins['OverlordText']; ?></small></p>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <?php createProgressBar($QuestWins['HeroPerc'], "Won (Heroes)", $QuestWins['OverlordPerc'], "Won (Overlord)"); ?>         
              </div>
              <div class="col-md-12">
                <div class="row text-muted">
                  <small>
                    <div class="col-md-5 text-left">
                      <?php if(isset($sa['selected_monsters_enc1']['loss'])){
                        echo $sa['selected_monsters_enc1']['loss'];
                      } ?>
                    </div>
                    <div class="col-md-2 text-center">
                      <strong>Enc 1</strong>
                    </div>
                    <div class="col-md-5 text-right">
                      <?php if(isset($sa['selected_monsters_enc1']['win'])){
                        echo $sa['selected_monsters_enc1']['win'];
                      } ?>
                    </div>
                  </small>
                </div>
                <p></p><?php 
                if(isset($sa['selected_monsters_enc2']['win']) || isset($sa['selected_monsters_enc2']['loss'])){ ?>
                  <div class="row text-muted">
                    <small>
                      <div class="col-md-5 text-left">
                        <?php if(isset($sa['selected_monsters_enc2']['loss'])){
                          echo $sa['selected_monsters_enc2']['loss'];
                        } ?>
                      </div>
                      <div class="col-md-2 text-center">
                        <strong>Enc 2</strong>
                      </div>
                      <div class="col-md-5 text-right">
                        <?php if(isset($sa['selected_monsters_enc2']['win'])){
                          echo $sa['selected_monsters_enc2']['win'];
                        } ?>
                      </div>
                    </small>
                  </div><p></p><?php
                } if (isset($sa['selected_monsters_enc3']['win']) || isset($sa['selected_monsters_enc3']['loss'])){ ?>
                  <div class="row text-muted">
                    <small>
                      <div class="col-md-5 text-left">
                        <?php if(isset($sa['selected_monsters_enc3']['loss'])){
                          echo $sa['selected_monsters_enc3']['loss'];
                        } ?>
                      </div>
                      <div class="col-md-2 text-center">
                        <strong>Enc 3</strong>
                      </div>
                      <div class="col-md-5 text-right">
                        <?php if(isset($sa['selected_monsters_enc3']['win'])){
                          echo $sa['selected_monsters_enc3']['win'];
                        } ?>
                      </div>
                    </small>
                  </div>
                  <p></p><?php
                } ?>
              </div>
            </div>

          </div>
        </div><?php
      }
    
    }

  }

} ?>
<div class="row">&nbsp;</div>

<div class="panel panel-default">
  <div class="panel-heading">
    <h2 class="panel-title">Wins Per Quest</h2>
  </div>
  <div class="panel-body">

    <div role="tabpanel">
      <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#allquests" aria-controls="home" role="tab" data-toggle="tab">Top 10</a></li>
        <li role="presentation"><a href="#tsr" aria-controls="profile" role="tab" data-toggle="tab">TSR</a></li>
        <li role="presentation"><a href="#lor" aria-controls="messages" role="tab" data-toggle="tab">LoR</a></li>
        <li role="presentation"><a href="#son" aria-controls="settings" role="tab" data-toggle="tab">SoN</a></li>
        <li role="presentation"><a href="#hob" aria-controls="settings" role="tab" data-toggle="tab">HoB</a></li>
        <li role="presentation"><a href="#mob" aria-controls="settings" role="tab" data-toggle="tab">MoB</a></li>
        <li role="presentation"><a href="#other" aria-controls="settings" role="tab" data-toggle="tab">Other</a></li>
      </ul>

      <div class="tab-content">
        <div role="tabpanel" class="tab-pane active fade in" id="allquests"><?php
          $i = 0;
          foreach ($statsArray as $sa){
            if ($i < 10){
              if (($sa['hero_wins'] + $sa['overlord_wins']) > 0){
                $QuestWins = calcQuestWins($sa['overlord_wins'], $sa['hero_wins']);?>

                <div class="row stats-row">
                  <div class="col-xs-12">

                    <div class="row">
                      <div class="col-md-6">
                        <p><strong><?php echo $sa['quest_name']; ?></strong></p>
                      </div>
                      <div class="col-md-6 text-right"> 
                        <?php getCampaignLabel($sa['quest_campaign'], "normal"); ?>
                      </div>
                    </div>
            
                    <div class="row">
                      <div class="col-md-6">         
                        <p class="text-left text-muted"><small><?php echo $QuestWins['HeroText']; ?></small></p>
                      </div>
                      <div class="col-md-6">         
                        <p class="text-right text-muted"><small><?php echo $QuestWins['OverlordText']; ?></small></p>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-12">
                        <?php createProgressBar($QuestWins['HeroPerc'], "Won (Heroes)", $QuestWins['OverlordPerc'], "Won (Overlord)"); ?>         
                      </div>
                      <div class="col-md-12">
                        <div class="row text-muted">
                          <small>
                            <div class="col-md-5 text-left">
                              <?php if(isset($sa['selected_monsters_enc1']['loss'])){
                                echo $sa['selected_monsters_enc1']['loss'];
                              } ?>
                            </div>
                            <div class="col-md-2 text-center">
                              <strong>Enc 1</strong>
                            </div>
                            <div class="col-md-5 text-right">
                              <?php if(isset($sa['selected_monsters_enc1']['win'])){
                                echo $sa['selected_monsters_enc1']['win'];
                              } ?>
                            </div>
                          </small>
                        </div>
                        <p></p><?php 
                        if(isset($sa['selected_monsters_enc2']['win']) || isset($sa['selected_monsters_enc2']['loss'])){ ?>
                          <div class="row text-muted">
                            <small>
                              <div class="col-md-5 text-left">
                                <?php if(isset($sa['selected_monsters_enc2']['loss'])){
                                  echo $sa['selected_monsters_enc2']['loss'];
                                } ?>
                              </div>
                              <div class="col-md-2 text-center">
                                <strong>Enc 2</strong>
                              </div>
                              <div class="col-md-5 text-right">
                                <?php if(isset($sa['selected_monsters_enc2']['win'])){
                                  echo $sa['selected_monsters_enc2']['win'];
                                } ?>
                              </div>
                            </small>
                          </div>
                          <p></p><?php
                        } if (isset($sa['selected_monsters_enc3']['win']) || isset($sa['selected_monsters_enc3']['loss'])){ ?>
                          <div class="row text-muted">
                            <small>
                              <div class="col-md-5 text-left">
                                <?php if(isset($sa['selected_monsters_enc3']['loss'])){
                                  echo $sa['selected_monsters_enc3']['loss'];
                                } ?>
                              </div>
                              <div class="col-md-2 text-center">
                                <strong>Enc 3</strong>
                              </div>
                              <div class="col-md-5 text-right">
                                <?php if(isset($sa['selected_monsters_enc3']['win'])){
                                  echo $sa['selected_monsters_enc3']['win'];
                                } ?>
                              </div>
                            </small>
                          </div>
                          <p></p><?php
                        } ?>
                      </div>
                    </div>

                  </div>
                </div><?php
              }
            }
            $i++;
          } ?>
        </div>
                
        <div role="tabpanel" class="tab-pane fade" id="tsr"><?php
          questStats($statsArray, 0); ?>
        </div>

        <div role="tabpanel" class="tab-pane fade" id="lor"><?php
          questStats($statsArray, 2); ?>
        </div>
                
        <div role="tabpanel" class="tab-pane fade" id="son"><?php
          questStats($statsArray, 4); ?>
        </div>

        <div role="tabpanel" class="tab-pane fade" id="hob"><?php
          questStats($statsArray, 29); ?>
        </div>

        <div role="tabpanel" class="tab-pane fade" id="mob"><?php
          questStats($statsArray, 30); ?>
        </div>
                
        <div role="tabpanel" class="tab-pane fade" id="other"><?php
          foreach ($statsArray as $sa){
            if ($sa['expansion_id'] != 0 && $sa['expansion_id'] != 2 && $sa['expansion_id'] != 4 && $sa['expansion_id'] != 29){

              if (($sa['hero_wins'] + $sa['overlord_wins']) > 0){
                $QuestWins = calcQuestWins($sa['overlord_wins'], $sa['hero_wins']);?>

                <div class="row stats-row">
                  <div class="col-xs-12">

                    <div class="row"> 
                      <div class="col-md-6"> 
                        <p><strong><?php echo $sa['quest_name']; ?></strong></p>
                      </div>
                      <div class="col-md-6 text-right"> 
                        <?php getCampaignLabel($sa['quest_campaign'], "normal"); ?>
                      </div>
                    </div>

                    <div class="row"> 
                      <div class="col-md-6">         
                        <p class="text-left text-muted"><small><?php echo $QuestWins['HeroText']; ?></small></p>
                      </div>
                      <div class="col-md-6">         
                        <p class="text-right text-muted"><small><?php echo $QuestWins['OverlordText']; ?></small></p>
                      </div>
                    </div>

                    <div class="row"> 
                      <div class="col-md-12">
                        <?php createProgressBar($QuestWins['HeroPerc'], "Won (Heroes)", $QuestWins['OverlordPerc'], "Won (Overlord)"); ?>         
                      </div>
                      <div class="col-md-12">
                        <div class="row text-muted">
                          <small>
                            <div class="col-md-5 text-left">
                              <?php if(isset($sa['selected_monsters_enc1']['loss'])){
                                echo $sa['selected_monsters_enc1']['loss'];
                              } ?>
                            </div>
                            <div class="col-md-2 text-center">
                              <strong>Enc 1</strong>
                            </div>
                            <div class="col-md-5 text-right">
                              <?php if(isset($sa['selected_monsters_enc1']['win'])){
                                echo $sa['selected_monsters_enc1']['win'];
                              } ?>
                            </div>
                          </small>
                        </div>
                        <p></p><?php 
                        if(isset($sa['selected_monsters_enc2']['win']) || isset($sa['selected_monsters_enc2']['loss'])){ ?>
                          <div class="row text-muted">
                            <small>
                              <div class="col-md-5 text-left">
                                <?php if(isset($sa['selected_monsters_enc2']['loss'])){
                                  echo $sa['selected_monsters_enc2']['loss'];
                                } ?>
                              </div>
                              <div class="col-md-2 text-center">
                                <strong>Enc 2</strong>
                              </div>
                              <div class="col-md-5 text-right">
                                <?php if(isset($sa['selected_monsters_enc2']['win'])){
                                  echo $sa['selected_monsters_enc2']['win'];
                                } ?>
                              </div>
                            </small>
                          </div>
                          <p></p><?php
                        } if (isset($sa['selected_monsters_enc3']['win']) || isset($sa['selected_monsters_enc3']['loss'])){ ?>
                          <div class="row text-muted">
                            <small>
                              <div class="col-md-5 text-left">
                                <?php if(isset($sa['selected_monsters_enc3']['loss'])){
                                  echo $sa['selected_monsters_enc3']['loss'];
                                } ?>
                              </div>
                              <div class="col-md-2 text-center">
                                <strong>Enc 3</strong>
                              </div>
                              <div class="col-md-5 text-right">
                                <?php if(isset($sa['selected_monsters_enc3']['win'])){
                                  echo $sa['selected_monsters_enc3']['win'];
                                } ?>
                              </div>
                            </small>
                          </div>
                          <p></p><?php
                        } ?>
                      </div>
                    </div>
                  </div>
                </div><?php
              }
            }  
          } ?>
        </div>
      </div>
    </div>
  </div>
</div> 