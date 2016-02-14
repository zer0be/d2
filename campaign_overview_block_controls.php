<?php

// If the person watching this page is the owner of the campaign, show this stuff
if ($owner == 1){

  // Display a message about winning the campaign if the last quest is the finale or an act 2 quest when its a mini campaign, and the winner is set
  if (($campaign['quests'][0]['act'] == "Finale" || ($campaign['quests'][0]['act'] == "Act 2" && $campaign['type'] == "mini") ) && $campaign['quests'][0]['winner'] != NULL){ ?>
    <div class="row no-gutters">
      <div class="col-sm-12 text-center"><?php
        if($campaign['quests'][0]['winner'] == "Heroes Win"){ ?>
          <p class="lead">The Heroes won the Finale! They are the winners of this campaign!</p><?php
        } else if($campaign['quests'][0]['winner'] == "Overlord Wins"){ ?>
          <p class="lead">The Overlord won the Finale! He is the winner of this campaign!</p><?php
        } ?>
      </div>
    </div><?php 

  }

  // Enable or Disable the buttons
  if($campaign['quests'][0]['items_set'] == 1 && $campaign['quests'][0]['spendxp_set'] == 1 && $campaign['quests'][0]['act'] != "Finale"){ 
    $showControls = 1;
    $enableQuests = 1;
    $questBtnClass = "btn-primary";
    $enableRumors = 0;
    $rumorBtnClass = "btn-default";
  } else if (($campaign['quests'][0]['act'] == "Finale" || ($campaign['quests'][0]['act'] == "Act 2" && $campaign['type'] == "mini")) && $campaign['quests'][0]['winner'] != NULL){ 
    $showControls = 0;
  } else { 
    $showControls = 1;
    $enableQuests = 0;
    $questBtnClass = "btn-default";
    $enableRumors = 1;
    $rumorBtnClass = "btn-primary";
  } 

  // If the controls should be shown, show them
  if($showControls == 1){ ?>
    <div id="start-block-reverse">
      <div class="row no-gutters">
        <div class="col-sm-4"></div>
        <div class="col-sm-4"><?php
          if ($enableQuests == 1){ ?>
            <div class="col-sm-2 hidden-xs"></div>
            <div class="col-sm-8">
              <div id="quest-button" class="btn btn-block <?php echo $questBtnClass; ?> form-control">Start New ...</div>
            </div><?php 
          } 
          else if (!empty($rumorCardOptions)){
            if($campaign['type'] != "mini"){ ?>
              <div class="col-sm-2 hidden-xs"></div>
              <div class="col-sm-8">
                <div id="rumor-button" class="btn btn-block <?php echo $rumorBtnClass; ?> form-control">Play Rumor Cards</div>     
              </div><?php 
            } 
          } ?>
        </div>
      </div>
    </div>

    <div id="start-block">
      <div class="row no-gutters">


        <div class="col-sm-3 hidden-xs"></div>
        <div class="col-sm-12">
          <form action="<?php echo $editFormAction; ?>" method="post" name="start-quest-form" id="start-quest-form">
            <input type="hidden" name="progress_timestamp" value="" />
            <input type="hidden" name="progress_game_id" value="<?php echo $gameID; ?>" />
            <input type="hidden" name="MM_insert" value="start-quest-form" />
            
            <div class="row no-gutters">
              <div class="col-sm-4 hidden-xs"></div>
              <div class="col-sm-4">
                <input type="submit" value="Start selected quest" class="btn btn-block btn-info form-control" />
              </div>
            </div><?php

            $minicampaigns = array(1,3,5);
            $actonecampaigns = array(30);

            $ir = 0;
            foreach ($AvailableQuests as $aqs){ 
              // Show only the quest for this act
              
              if ($aqs['quest_act'] == $currentAct || (in_array($selCampaign,$minicampaigns) && ($aqs['quest_act'] == "Act 2" && $currentAct == "Interlude" )) || (in_array($selCampaign,$actonecampaigns) && ($aqs['quest_act'] == "Finale" && $currentAct == "Interlude" )) || ($aqs['quest_type'] == "rumor" && $aqs['quest_status']['available'] == 1 && $currentAct == "Interlude")  ){

                // Check if the image for the quest exists, if not, use default
                $filename = "img/quests/" . $aqs['quest_img'];
                if (!file_exists($filename)) {
                  $filename = "img/quests/default.jpg";
                }

                $opacity = "";
                if($aqs['quest_status']['available'] != 1){ 
                  $opacity = 'style="opacity: 0.5;"'; 
                }
                $pClass = "";
                if($aqs['quest_status']['available'] == 0){
                  $pClass = "text-danger";
                } else if ($aqs['quest_status']['available'] == 1){
                  $pClass = "text-success";
                } else if ($aqs['quest_status']['available'] == 2){
                  $pClass = "text-warning";
                } 

                if($ir == 0){ ?>
                  <div class="row no-gutters" style="margin-bottom: 30px;">
                    <div class="col-xs-12"><?php
                } 
                $ir++; ?>

                      <div class="col-md-4" <?php echo $opacity; ?> >

                        <div class="row no-gutters" style="background: #f9f9f9; border: 1px solid #ddd;">
                          <div class="col-sm-4 hidden-xs">
                            <div style="background: url('<?php print $filename; ?>') no-repeat center; background-size: 160% auto; height: 320px;"></div>
                          </div>
                          <div class="col-sm-8" style="padding: 0 15px;">
                            <h2 class="h4"><?php print $aqs['quest_name']; ?></h2>
                            <small>
                              <p class=" <?php echo $pClass; ?>">
                                <small><?php echo $aqs['quest_status']['message']; ?></small>
                              </p>
                            </small>
                            <small class="text-muted">
                              <span class="glyphicon glyphicon-time" aria-hidden="true"></span><?php
                              echo " ";
                              foreach ($statsArray as $sta){
                                if($sta['quest_name'] == $aqs['quest_name']){
                                  $timeNames = array(
                                    "0" => "Unknown",
                                    "30" => "30 minutes",
                                    "60" => "1 hour",
                                    "90" => "1 hour and 30 min",
                                    "120" => "2 hours",
                                    "150" => "2 hours and 30 min",
                                    "180" => "3 hours",
                                    "210" => "3 hours and 30 min",
                                    "240" => "4 hours",
                                    "270" => "4 hours and 30 min",
                                    "300" => "5 hours",
                                    "330" => "5 hours and 30 min",
                                    "360" => "6 hours",
                                    "999" => "more than 6 hours",
                                  );

                                  unset($sta['time']['0']);
                                  arsort($sta['time']);

                                  // Other option for showing time indication
                                  // 
                                  // foreach ($sta['time'] as $key => $time){
                                  //   echo $timeNames[$key] . " - ";
                                  // }

                                  $avgAll = 0;
                                  $timeCount = 0;
                                  foreach ($sta['time'] as $key => $time){
                                    if($time != 0){
                                      $avgAll += $key * $time;
                                      $timeCount += $time;
                                    }
                                  }
                                  echo "Avg. playtime: ";
                                  if($timeCount != 0){
                                    $average = $avgAll / $timeCount; 
                                    $average = $average / 30;
                                    $avgNr = round($average) * 30;
                                    if ($avgNr > 360){
                                      echo $timeNames[999];
                                    } else {
                                      echo $timeNames[$avgNr];
                                    }
                                  } else {
                                    echo "Unknown";
                                  }

                                  // Other option for showing time indication
                                  // 
                                  // $timeSlice = array_slice($sta['time'], 0, 2, true);
                                  // $timeSlice = array_filter($timeSlice);
                                  // if (!empty($timeSlice)){
                                  //   $t = 0;
                                  //   foreach ($timeNames as $tnkey => $tn){
                                  //     if (array_key_exists($tnkey, $timeSlice)){
                                  //       echo $tn;
                                  //       if($t == 0){
                                  //         echo " to ";
                                  //         $t++;
                                  //       }
                                  //     }
                                  //   }
                                  // } else {
                                  //   echo "Unknown";
                                  // }
                                  
                                }
                              } ?>
                            </small>
                            <p style="height: 120px;"><small><?php 
                              if($aqs['quest_description'] != NULL){
                                print $aqs['quest_description']; 
                              } else {
                                echo 'No description available yet.';
                              }
                              
                              ?></small></p>
                            <div class="row"><?php 
                              foreach ($statsArray as $sta){
                                if($sta['quest_name'] == $aqs['quest_name']){
                                  $QuestWins = calcQuestWins($sta['overlord_wins'], $sta['hero_wins']);
                                }
                              } ?>
                              
                              <div class="col-md-12">
                                <?php createProgressBar($QuestWins['HeroPerc'], "Won (Heroes)", $QuestWins['OverlordPerc'], "Won (Overlord)"); ?>         
                              </div>
                            </div><?php 
                                        
                            if($aqs['quest_status']['available'] != 1){ ?>
                              <div class="radio disabled btn btn-default">
                                <label> 
                                  <input type="radio" name="selectquest" id="selectquest" value="" disabled><?php
                                  echo "Select this " . $aqs['quest_type']; ?>
                                </label>
                              </div><?php
                            } else { ?>
                              <div class="radio btn btn-default">
                                <label><?php
                                  if($aqs['quest_type'] == "quest"){
                                    echo '<input type="radio" name="selectquest" id="selectquest' . $aqs['quest_id'] . '" value="quest' . $aqs['quest_id'] . '">';
                                  } else {
                                    echo '<input type="radio" name="selectquest" id="selectquest' . $aqs['quest_id'] . '" value="rumor' . $aqs['quest_id'] . '">';
                                  }
                                  echo "Select this " . $aqs['quest_type']; ?>
                                </label>
                              </div><?php
                            } ?>
                            <p></p>
                          </div>
                          
                        </div>

                      </div><?php

                if($ir == 3){
                  echo '</div>';
                  echo '</div>';
                  $ir = 0;
                }

              } // if current act

            } // foreach

            if($ir != 0){
              echo '</div>';
              echo '</div>';
            } ?>
                
          </form>
        </div>

      </div>
    </div>

    <div id="rumors-block" class="col-sm-6 col-md-offset-3">
      <div class="well"><?php 
        if ($enableRumors == 1){ ?>
          <strong>Put a rumor in play</strong>
          <div class="row">
            <form action="<?php echo $editFormAction; ?>" method="post" name="add-rumor-form" id="add-rumor-form">
              <div class="col-sm-8">
                <select name="progress_rumor_card_id" class="form-control">
                  <option value="">Select Rumor card</option><?php 
                  foreach ($rumorCardOptions as $rco){
                    echo $rco;
                  } ?>
                </select>
              </div>

              <div class="col-sm-4">
                <input type="submit" value="Select" class=" btn btn-block btn-info form-control" />
                <input type="hidden" name="progress_timestamp" value="" />
                <input type="hidden" name="progress_game_id" value="<?php echo $gameID; ?>" />
                <input type="hidden" name="MM_insert" value="add-rumor-form" />
              </div>
            </form>
          </div><?php 
        } else { ?>
          <p class="text-muted">Please start a new quest or rumor quest first. 
            Rumors can only be played at the start of a campaign phase (which is right after saving the quest details) or before the specific step as stated on the rumor card.
          </p><?php 
        } ?>
      </div>
    </div><?php 

  } // controls

} // owner