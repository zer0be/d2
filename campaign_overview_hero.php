<?php

$detailCharID = $_GET['urlCharID'];
$detailGameID = $gameID;
$currentHeroType = NULL;

?>
<div class="container full">
  <div id="heroes-detail" class="clearfix">
    <div class="row no-gutters"><?php 
      // loop through heroes
      foreach ($players as $h){
        if (($h['id'] == $detailCharID)){ 

          $heroID = $h['id'];
          include 'campaign_overview_hero_data.php';

          ?>  
              <div class="hero col-sm-3 col-xs-12 text-center" style="background: url('img/heroes/<?php print $h['img']; ?>') no-repeat center;">
                <div class="name"><?php print $h['name']; ?></div>
                <div class="class"><?php print $h['class']; ?></div>
                <div class="player"><?php print $h['player']; ?></div>
                <div class="xp"><?php print $h['xp']; ?><span class="xp-label">XP</span></div>
                <?php $currentHeroType = $h['archetype']; ?> 
                <a class="btn btn-primary btn-block" href="campaign_overview.php?urlGamingID=<?php echo $gameID_obscured ?>">Back to Overview</a>
              </div> <!-- close hero -->

            <div class="col-sm-9 col-xs-12">
              <div class="row no-gutters row-bg-white"><?php 

                if($currentHeroType != "Overlord"){ ?>
                  <div class="detail-items col-sm-4 col-bg-white">
                    <h2 class="text-center">Stats</h2>
                      <div class="skill row no-gutters">
                        <div class="col-xs-2"><div class="hero-mini" style="background: url('img/speed.png') center;"></div></div>
                        <div class="skill-name col-xs-8">Speed</div>
                        <div class="col-xs-2 text-center"><a href="#" data-toggle="tooltip" title="<?php echo $tooltipSpeed; ?>"><?php
                          if($maxSpeed == 0 || ($h['speed'] + $extraSpeed) < $maxSpeed){ ?>
                            <span class="badge <?php if($extraSpeed > 0){ echo 'green';}else{ echo 'blue'; }?>"><?php print $h['speed'] + $extraSpeed; ?></span><?php
                          } else { ?>
                            <span class="badge red"><?php print $maxSpeed; ?></span><?php
                          } ?></a>
                        </div>
                      </div>
                      <div class="skill row no-gutters">
                        <div class="col-xs-2"><div class="hero-mini" style="background: url('img/health.png') center;"></div></div>
                        <div class="skill-name col-xs-8">Health</div>
                        <div class="col-xs-2 text-center"><a href="#" data-toggle="tooltip" title="<?php echo $tooltipHealth; ?>"><span class="badge <?php if($extraHealth > 0){ echo 'green';}else{ echo 'blue'; }?>"><?php print $h['health']  + $extraHealth; ?></span></a></div>
                      </div>
                      <div class="skill row no-gutters">
                        <div class="col-xs-2"><div class="hero-mini" style="background: url('img/stamina.png') center;"></div></div>
                        <div class="skill-name col-xs-8">Stamina</div>
                        <div class="col-xs-2 text-center"><a href="#" data-toggle="tooltip" title="<?php echo $tooltipStamina; ?>"><span class="badge <?php if($extraStamina > 0){ echo 'green';}else{ echo 'blue'; }?>"><?php print $h['stamina']  + $extraStamina; ?></span></a></div>
                      </div>
                      <div class="skill row no-gutters">
                        <div class="col-xs-2"><div class="hero-mini" style="background: url('img/defense.png') center;"></div></div>
                        <div class="skill-name col-xs-8">Defense</div>
                        <div class="col-xs-2 text-center"><a href="#" data-toggle="tooltip" title="<?php echo $tooltipDefense; ?>"><div class="hero-mini center-block" style="background: url('img/defense<?php print $h['defense']  . $extraDefense; ?>.png') center;"></div></a></div>
                      </div>


                      <div class="skill row no-gutters">
                        <div class="col-xs-2"><div class="hero-mini" style="background: url('img/might.png') center;"></div></div>
                        <div class="skill-name col-xs-8">Might</div>
                        <div class="col-xs-2 text-center"><a href="#" data-toggle="tooltip" title="<?php echo $tooltipMight; ?>"><span class="badge <?php if($extraMight > 0){ echo 'green';}else{ echo 'blue'; }?>"><?php print $h['might']  + $extraMight; ?></span></a></div>
                      </div>
                      <div class="skill row no-gutters">
                        <div class="col-xs-2"><div class="hero-mini" style="background: url('img/knowledge.png') center;"></div></div>
                        <div class="skill-name col-xs-8">Knowledge</div>
                        <div class="col-xs-2 text-center"><a href="#" data-toggle="tooltip" title="<?php echo $tooltipKnowledge; ?>"><span class="badge <?php if($extraKnowledge > 0){ echo 'green';}else{ echo 'blue'; }?>"><?php print $h['knowledge']  + $extraKnowledge; ?></span></a></div>
                      </div>
                      <div class="skill row no-gutters">
                        <div class="col-xs-2"><div class="hero-mini" style="background: url('img/willpower.png') center;"></div></div>
                        <div class="skill-name col-xs-8">Willpower</div>
                        <div class="col-xs-2 text-center"><a href="#" data-toggle="tooltip" title="<?php echo $tooltipWillpower; ?>"><span class="badge <?php if($extraWillpower > 0){ echo 'green';}else{ echo 'blue'; }?>"><?php print $h['willpower']  + $extraWillpower; ?></span></a></div>
                      </div>
                      <div class="skill row no-gutters">
                       <div class="col-xs-2"><div class="hero-mini" style="background: url('img/awareness.png') center;"></div></div>
                        <div class="skill-name col-xs-8">Awareness</div>
                        <div class="col-xs-2 text-center"><a href="#" data-toggle="tooltip" title="<?php echo $tooltipAwareness; ?>"><span class="badge <?php if($extraAwareness > 0){ echo 'green';}else{ echo 'blue'; }?>"><?php print $h['awareness']  + $extraAwareness; ?></span></a></div>
                      </div>
                      <div class="text-center text-muted"><small>The data above might not be accurate, this is still work in progress and more complex items are not yet taken into account. (e.g. 'Rune Plate')</small></div>
                  </div> <!-- close stats -->


                  <div class="detail-items col-sm-4 col-bg-white">
                    <h2 class="text-center">Items and Relics</h2><?php 
                    $faqItems = array();
                    foreach($DetailItemsList as $dil) {
                      $faqItems[] = $dil['item_id'];
                      if (isset($dil['item_name'])){ ?>
                        <div class="item row no-gutters">     
                          <div class="col-xs-2">
                            <div class="hero-mini" style="background: url('img/<?php print $dil['item_type']; ?>.jpg') center;"></div>
                          </div>
                          <div class="item-name col-xs-8"><a href="#" data-toggle="tooltip" title="<?php print $dil['tooltip']; ?>"><?php print $dil['item_name']; ?></a></div><?php 
                          if ($dil['default_price'] == 0){ ?>
                            <div class="col-xs-2 text-center"><span class="badge blue">Free</span></div><?php 
                          } else if ($dil['override_price'] != NULL && $dil['override_price'] == 0) { ?>
                            <div class="col-xs-2 text-center"><span class="badge blue">Free</span></div><?php 
                          } else if ($dil['override_price'] != NULL) { ?>
                            <div class="col-xs-2 text-center"><span class="badge yellow">- <?php print $dil['override_price']; ?></span></div><?php 
                          } else { ?>
                            <div class="col-xs-2 text-center"><span class="badge red">- <?php print $dil['default_price']; ?></span></div><?php 
                          } ?>
                        </div><?php 
                      }
                    }  

                    foreach($DetailRelicsList as $drl) { 
                      if (isset($drl['relic_h_name'])){ ?>
                        <div class="item row no-gutters">    
                          <div class="col-xs-2">  
                            <div class="hero-mini" style="background: url('img/<?php print $drl['relic_type']; ?>.jpg') center;"></div>
                          </div>
                          <div class="item-name col-xs-8"><a href="#" data-toggle="tooltip" title="<?php print $drl['tooltip_h']; ?>"><?php print $drl['relic_h_name']; ?></a></div> <!-- FIX ME: Overlord relics and stuff -->
                          <div class="col-xs-2 text-center"><span class="badge blue">Relic</span></div>
                        </div><?php 
                      }  
                    } ?>
                  </div> <!-- close items -->

                  <div class="detail-skills col-sm-4 col-bg-white">
                    <h2 class="text-center">Skills</h2><?php 

                    $faqSkills = array();
                    foreach($DetailSkillsList as $dsl) { 
                      $faqSkills[] = $dsl['skill_id']; ?>
                      <div class="skill row no-gutters">
                        <div class="col-xs-2">
                          <div class="hero-mini" style="background: url('img/staminacost<?php print $dsl['skill_stamina_cost']; ?>.png') center;"></div>
                        </div> 
                        <div class="skill-name col-xs-8"><a href="#" data-toggle="tooltip" title="<?php print $dsl['tooltip']; ?>"><?php print $dsl['skill_name']; ?></a></div><?php 
                        if ($dsl['skill_cost'] == 0){ ?>
                          <div class="col-xs-2 text-center"><span class="badge green">Free</span></div><?php 
                        } else { ?>
                          <div class="col-xs-2 text-center"><span class="badge red">- <?php echo $dsl['skill_cost']; ?><span class="skill-xp-label">XP</span></span></div><?php 
                        } ?>
                      </div><?php 
                    } ?>

                  </div> <!-- close skills --><?php 

                } else { ?>

                  <div class="detail-items col-sm-4 col-bg-white">
                    <h2 class="text-center">Items and Relics</h2><?php 
                    $faqItems = array();
                    foreach($DetailRelicsList as $drl) {  
                      if (isset($drl['relic_ol_name'])){ ?>
                        <div class="item row no-gutters">
                          <div class="col-xs-2">   
                            <div class="hero-mini" style="background: url('img/<?php print $drl['relic_type']; ?>.jpg') center;"></div>
                          </div> 
                          <div class="item-name col-xs-8"><a href="#" data-toggle="tooltip" title="<?php print $drl['tooltip_ol']; ?>"><?php print $drl['relic_ol_name']; ?></a></div> <!-- FIX ME: Overlord relics and stuff -->
                          <div class="col-xs-2 text-center"><span class="badge blue">Relic</span></div>
                        </div><?php 
                      }
                    } ?>
                  </div> <!-- close items -->


                  <div class="detail-skills col-sm-4 col-bg-white">
                    <h2 class="text-center">Overlord Cards</h2><?php 
                    $olCardTotal = 0;
                    $olClassTotal = array();
                    $faqSkills = array();
                    foreach($DetailSkillsList as $dsl) { 
                      $faqSkills[] = $dsl['skill_id'];
                      if($dsl['skill_plot'] != 1){ ?>
                        <div class="skill row no-gutters">
                          <div class="col-xs-2"><div class="hero-mini" style="background: url('img/overlordcard.jpg') center;"></div></div>
                          <div class="skill-name col-xs-8"><a href="#" data-toggle="tooltip" title="<?php print $dsl['tooltip']; ?>"><?php print $dsl['skill_name']; ?></a></div><?php
                          if ($dsl['skill_cost'] == 0){ ?>
                            <div class="col-xs-2 text-center"><span class="badge green">Free</span></div><?php 
                          } else { ?>
                            <div class="col-xs-2 text-center"><span class="badge red">- <?php echo $dsl['skill_cost']; ?><span class="skill-xp-label">XP</span></span></div><?php 
                          } ?>
                        </div><?php
                        $olCardTotal++; 
                        $olClassTotal[] = $dsl['skill_class'];
                      } 
                    } 

                    $olClassTotalCount = array_count_values($olClassTotal);
                    ?>
                    <p class="text-center text-muted">
                      <strong>Total cards: <?php echo $olCardTotal; ?></strong><br />
                      <?php 
                        echo "(";
                        $xo = 0;
                        foreach($olClassTotalCount as $key => $val){
                          $xo++;
                          echo  $val . "x " . $key;
                          // echo  " Card";
                          // if ($val > 1){
                          //   echo "s";
                          // }
                          if ($xo <= (count($olClassTotalCount) - 1)){
                            echo ", ";
                          }
                        }
                        echo ")";
                      ?>
                    </p>
                  </div> <!-- close skills -->

                  <div class="detail-skills col-sm-4 col-bg-white">
                    <h2 class="text-center">Plot Cards</h2>
                    <?php foreach($DetailSkillsList as $dsl) { ?>
                      <?php if($dsl['skill_plot'] == 1){ ?>
                        <div class="skill row no-gutters">
                        <div class="col-xs-2"><div class="hero-mini" style="background: url('img/plotcard.jpg') center;"></div></div> 
                          <div class="skill-name col-xs-8"><a href="#" data-toggle="tooltip" title="<?php print $dsl['tooltip']; ?>"><?php print $dsl['skill_name']; ?></a></div>
                          <?php if ($dsl['skill_cost'] == 0){ ?>
                            <div class="col-xs-2 text-center"><span class="badge green">Free</span></div>
                          <?php } else { ?>
                            <div class="col-xs-2 text-center"><span class="badge red">- <?php echo $dsl['skill_cost']; ?><span class="skill-xp-label"> Thrt</span></span></div>
                          <?php } ?>
                        </div>
                      <?php } ?>
                    <?php } ?>
                  </div> <!-- close skills -->
                </div>
                <div class="row no-gutters">
                    <?php

                    $topSpeed = $topHealth = $topStamina = $topMight = $topKnowledge = $topWillpower = $topAwareness = 0;
                    $lowSpeed = $lowHealth = $lowStamina = $lowMight = $lowKnowledge = $lowWillpower = $lowAwareness = 999;

                    foreach ($players as $ho){
                      if($ho['archetype'] != "Overlord"){ 

                        $heroID = $ho['id'];
                        include 'campaign_overview_hero_data.php';

                        if (($ho['speed'] + $extraSpeed) > $topSpeed){
                          $topSpeed = $ho['speed'] + $extraSpeed;
                        }

                        if (($ho['speed'] + $extraSpeed) < $lowSpeed){
                          $lowSpeed = $ho['speed'] + $extraSpeed;
                        }


                        if (($ho['health'] + $extraHealth) > $topHealth){
                          $topHealth = $ho['health'] + $extraHealth;
                        }

                        if (($ho['health'] + $extraHealth) < $lowHealth){
                          $lowHealth = $ho['health'] + $extraHealth;
                        }


                        if (($ho['stamina'] + $extraStamina) > $topStamina){
                          $topStamina = $ho['stamina'] + $extraStamina;
                        }

                        if (($ho['stamina'] + $extraStamina) < $lowStamina){
                          $lowStamina = $ho['stamina'] + $extraStamina;
                        }


                        if (($ho['might'] + $extraMight) > $topMight){
                          $topMight = $ho['might'] + $extraMight;
                        }

                        if (($ho['might'] + $extraMight) < $lowMight){
                          $lowMight = $ho['might'] + $extraMight;
                        }


                        if (($ho['knowledge'] + $extraKnowledge) > $topKnowledge){
                          $topKnowledge = $ho['knowledge'] + $extraKnowledge;
                        }

                        if (($ho['knowledge'] + $extraKnowledge) < $lowKnowledge){
                          $lowKnowledge = $ho['knowledge'] + $extraKnowledge;
                        }


                        if (($ho['willpower'] + $extraWillpower) > $topWillpower){
                          $topWillpower = $ho['willpower'] + $extraWillpower;
                        }

                        if (($ho['willpower'] + $extraWillpower) < $lowWillpower){
                          $lowWillpower = $ho['willpower'] + $extraWillpower;
                        }


                        if (($ho['awareness'] + $extraAwareness) > $topAwareness){
                          $topAwareness = $ho['awareness'] + $extraAwareness;
                        }

                        if (($ho['awareness'] + $extraAwareness) < $lowAwareness){
                          $lowAwareness = $ho['awareness'] + $extraAwareness;
                        } 

                      }
                    } ?>

                    <div class="panel-group no-bottom" id="accordion" role="tablist" aria-multiselectable="true">
                      <div class="panel panel-default">

                        <div class="panel-heading" role="tab" id="headingOne">
                          <h4 class="panel-title text-center">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                              <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> Overlord Intelligence <span class="caret"><span>
                            </a>
                          </h4>
                        </div>

                        <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                          <div class="panel-body">
                            <div class="col-sm-12">
                              <div class="row no-gutters">
                                <p>Below is an overview of the current stats of the heroes, which shows their <span class="badge dark-green">strenghts</span> and <span class="badge dark-red">weaknesses</span> 
                                  based on the overall maximum and minimum values of each stat or attribute (of all heroes). Useful for choosing the most effective overlord card to use on a hero.
                                </p>
                              </div>
                              <div class="row no-gutters">
                                <div class="col-sm-3 text-center hidden-xs">
                                  <div class="text-margin-10"><strong>Hero</strong></div>
                                </div>
                                <div class="col-sm-9">
                                  <div class="row no-gutters">

                                    <div class="col-xs-3 text-center ">
                                      <div class="text-margin-10 hidden-xs"><strong>Class</strong></div>
                                    </div>

                                    <div class="col-xs-1">
                                      <div class="hero-mini center-block" style="background: url('img/speed.png') center;"></div>
                                    </div>
                                    <div class="col-xs-1">
                                      <div class="hero-mini center-block" style="background: url('img/health.png') center;"></div>
                                    </div>
                                    <div class="col-xs-1">
                                      <div class="hero-mini center-block" style="background: url('img/stamina.png') center;"></div>
                                    </div>
                                    <div class="col-xs-1">
                                      <div class="hero-mini center-block" style="background: url('img/defense.png') center;"></div>
                                    </div>
                                    <div class="col-xs-1">
                                      <div class="hero-mini center-block" style="background: url('img/might.png') center;"></div>
                                    </div>
                                    <div class="col-xs-1">
                                      <div class="hero-mini center-block" style="background: url('img/knowledge.png') center;"></div>
                                    </div>
                                    <div class="col-xs-1">
                                      <div class="hero-mini center-block" style="background: url('img/willpower.png') center;"></div>
                                    </div>
                                    <div class="col-xs-1">
                                      <div class="hero-mini center-block" style="background: url('img/awareness.png') center;"></div>
                                    </div>

                                    <div class="col-xs-1">
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="row no-gutters"><?php
                                foreach ($players as $ho){
                                  if($ho['archetype'] != "Overlord"){ 

                                    $heroID = $ho['id'];
                                    include 'campaign_overview_hero_data.php'; ?>

                                    <div class="col-sm-3 detail-items text-center">
                                      <div class="text-margin-10"><?php echo $ho['name']; ?></div>
                                    </div>
                                    <div class="col-sm-9">
                                      <div class="skill row no-gutters">
                                        <div class="col-xs-3 text-center">
                                          <div class="text-margin-10"><?php echo $ho['archetype']; ?></div>
                                        </div>

                                        <div class="col-xs-1 text-center">
                                          <span class="badge <?php 
                                            if(($ho['speed']  + $extraSpeed) == $topSpeed){ 
                                              echo 'green-border';
                                            } else if(($ho['speed']  + $extraSpeed) == $lowSpeed){ 
                                              echo 'red-border';
                                            }
                                            echo ' ';
                                            if(($ho['speed']  + $extraSpeed) <= 2){ 
                                              echo 'dark-red';
                                            } else if(($ho['speed']  + $extraSpeed) == 3){ 
                                              echo 'red';
                                            } else if(($ho['speed']  + $extraSpeed) == 4){ 
                                              echo 'yellow';
                                            } else if(($ho['speed']  + $extraSpeed) == 5){ 
                                              echo 'green';
                                            } else if(($ho['speed']  + $extraSpeed) >= 6){ 
                                              echo 'dark-green';
                                            } ?>">
                                            <?php print $ho['speed']  + $extraSpeed; ?>
                                          </span>
                                        </div>
                                        <div class="col-xs-1 text-center">
                                          <span class="badge <?php 
                                            if(($ho['health']  + $extraHealth) == $topHealth){ 
                                              echo 'green-border';
                                            } else if(($ho['health']  + $extraHealth) == $lowHealth){ 
                                              echo 'red-border';
                                            }
                                            echo ' ';
                                            if(($ho['health']  + $extraHealth) <= 8){ 
                                              echo 'dark-red';
                                            } else if(($ho['health']  + $extraHealth) <= 10){ 
                                              echo 'red';
                                            } else if(($ho['health']  + $extraHealth) <= 12){ 
                                              echo 'yellow';
                                            } else if(($ho['health']  + $extraHealth) <= 14){ 
                                              echo 'green';
                                            } else if(($ho['health']  + $extraHealth) >= 15){ 
                                              echo 'dark-green';
                                            } ?>">
                                            <?php print $ho['health']  + $extraHealth; ?>
                                          </span>
                                        </div>
                                        <div class="col-xs-1 text-center">
                                          <span class="badge <?php 
                                            if(($ho['stamina']  + $extraStamina) == $topStamina){ 
                                              echo 'green-border';
                                            } else if(($ho['stamina']  + $extraStamina) == $lowStamina){ 
                                              echo 'red-border';
                                            }
                                            echo ' ';
                                            if(($ho['stamina']  + $extraStamina) <= 2){ 
                                              echo 'dark-red';
                                            } else if(($ho['stamina']  + $extraStamina) == 3){ 
                                              echo 'red';
                                            } else if(($ho['stamina']  + $extraStamina) == 4){ 
                                              echo 'yellow';
                                            } else if(($ho['stamina']  + $extraStamina) == 5){ 
                                              echo 'green';
                                            } else if(($ho['stamina']  + $extraStamina) >= 6){ 
                                              echo 'dark-green';
                                            } ?>">
                                            <?php print $ho['stamina']  + $extraStamina; ?>
                                          </span>
                                        </div>
                                        <div class="col-xs-1">
                                          <div class="hero-mini center-block" style="background: url('img/defense<?php print $ho['defense']  . $extraDefense; ?>.png') center;"></div>
                                        </div>
                                        <div class="col-xs-1 text-center">
                                          <span class="badge <?php 
                                            if(($ho['might']  + $extraMight) == $topMight){ 
                                              echo 'green-border';
                                            } else if(($ho['might']  + $extraMight) == $lowMight){ 
                                              echo 'red-border';
                                            }
                                            echo ' ';
                                            if(($ho['might']  + $extraMight) <= 1){ 
                                              echo 'dark-red';
                                            } else if(($ho['might']  + $extraMight) == 2){ 
                                              echo 'red';
                                            } else if(($ho['might']  + $extraMight) == 3){ 
                                              echo 'yellow';
                                            } else if(($ho['might']  + $extraMight) == 4){ 
                                              echo 'green';
                                            } else if(($ho['might']  + $extraMight) >= 5){ 
                                              echo 'dark-green';
                                            } ?>">
                                            <?php print $ho['might']  + $extraMight; ?>
                                          </span>
                                        </div>
                                        <div class="col-xs-1 text-center">
                                          <span class="badge <?php 
                                            if(($ho['knowledge']  + $extraKnowledge) == $topKnowledge){ 
                                              echo 'green-border';
                                            } else if(($ho['knowledge']  + $extraKnowledge) == $lowKnowledge){ 
                                              echo 'red-border';
                                            }
                                            echo ' ';
                                            if(($ho['knowledge']  + $extraKnowledge) <= 1){ 
                                              echo 'dark-red';
                                            } else if(($ho['knowledge']  + $extraKnowledge) == 2){ 
                                              echo 'red';
                                            } else if(($ho['knowledge']  + $extraKnowledge) == 3){ 
                                              echo 'yellow';
                                            } else if(($ho['knowledge']  + $extraKnowledge) == 4){ 
                                              echo 'green';
                                            } else if(($ho['knowledge']  + $extraKnowledge) >= 5){ 
                                              echo 'dark-green';
                                            } ?>">
                                            <?php print $ho['knowledge']  + $extraKnowledge; ?>
                                          </span>
                                        </div>
                                        <div class="col-xs-1 text-center">
                                          <span class="badge <?php 
                                            if(($ho['willpower']  + $extraWillpower) == $topWillpower){ 
                                              echo 'green-border';
                                            } else if(($ho['willpower']  + $extraWillpower) == $lowWillpower){ 
                                              echo 'red-border';
                                            }
                                            echo ' ';
                                            if(($ho['willpower']  + $extraWillpower) <= 1){ 
                                              echo 'dark-red';
                                            } else if(($ho['willpower']  + $extraWillpower) == 2){ 
                                              echo 'red';
                                            } else if(($ho['willpower']  + $extraWillpower) == 3){ 
                                              echo 'yellow';
                                            } else if(($ho['willpower']  + $extraWillpower) == 4){ 
                                              echo 'green';
                                            } else if(($ho['willpower']  + $extraWillpower) >= 5){ 
                                              echo 'dark-green';
                                            } ?>">
                                            <?php print $ho['willpower']  + $extraWillpower; ?>
                                          </span>
                                        </div>
                                        <div class="col-xs-1 text-center">
                                          <span class="badge <?php 
                                            if(($ho['awareness']  + $extraAwareness) == $topAwareness){ 
                                              echo 'green-border';
                                            } else if(($ho['awareness']  + $extraAwareness) == $lowAwareness){ 
                                              echo 'red-border';
                                            }
                                            echo ' ';
                                            if(($ho['awareness']  + $extraAwareness) <= 1){ 
                                              echo 'dark-red';
                                            } else if(($ho['awareness']  + $extraAwareness) == 2){ 
                                              echo 'red';
                                            } else if(($ho['awareness']  + $extraAwareness) == 3){ 
                                              echo 'yellow';
                                            } else if(($ho['awareness']  + $extraAwareness) == 4){ 
                                              echo 'green';
                                            } else if(($ho['awareness']  + $extraAwareness) >= 5){ 
                                              echo 'dark-green';
                                            } ?>">
                                            <?php print $ho['awareness']  + $extraAwareness; ?>
                                          </span>
                                        </div>
                                        <div class="col-xs-1"></div>
                                      </div>
                                    </div><?php 
                                  }
                                } ?>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
   
                <?php } ?>
                <div class="col-sm-12 col-bg-white">
                  <div class="col-sm-12">
                    <h3 class="text-center">Hero, Class and Skills FAQ and Errata</h3><?php
                    $noErrata = 0;
                    foreach ($faqArray as $faq){
                      if($faq['errata_text'] != NULL){
                        if ( ($faq['subject'] == "hero" && in_array($h['hero_id'], $faq['subject_id'])) || ($faq['subject'] == "class" && in_array($h['class'], $faq['subject_id'])) || ($faq['subject'] == "skill" && array_intersect($faqSkills, $faq['subject_id'])) || ($faq['subject'] == "item" && array_intersect($faqItems, $faq['subject_id'])) ){
                          echo '<p><strong>' . $faq['errata_title'] . ':</strong><br />' . $faq['errata_text'] . '<br />';
                          if($faq['source'] == "official"){
                            echo '<small><span class="text-muted">Source: Official Errata</span></small></p>';
                          }
                          $noErrata = 1;
                        }
                      } 
                    }
                    foreach ($faqArray as $faq){
                      if($faq['question'] != NULL){
                        if ( ($faq['subject'] == "hero" && in_array($h['hero_id'], $faq['subject_id'])) || ($faq['subject'] == "class" && in_array($h['class'], $faq['subject_id'])) || ($faq['subject'] == "skill" && array_intersect($faqSkills, $faq['subject_id'])) || ($faq['subject'] == "item" && array_intersect($faqItems, $faq['subject_id'])) ){
                          echo '<p><strong><i>Q: ' . $faq['question'] . '</i></strong><br />A: ' . $faq['answer'] . '<br />';
                          if($faq['source'] == "official"){
                            echo '<small><span class="text-muted">Source: Official Errata</span></small></p>';
                          }
                          $noErrata = 1;
                        }
                      }
                    } 
                    if($noErrata == 0){
                      echo '<p>No know FAQ or Errata.</p>';
                    }?>
                  </div>
                </div>
              </div>
              

     </div>

              

      <?php
        }
      } //close foreach
      ?>
          
    </div>
  </div> <!-- close heroes -->
</div> <!-- close wrapper -->