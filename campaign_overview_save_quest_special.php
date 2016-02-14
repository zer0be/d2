<?php

echo '<div class="row">';
  echo '<div class="col-sm-6">';
    echo '<h3>Additional Rewards</h3>';
    echo "<p class='text-muted'>Any special rewards will be listed here.</p>";

    foreach ($qs['rewardsHeroes'] as $rh){
      if ($rh[0] == "special"){

        switch ($qs['quest_id']){
          case "9": // Ritual of Shadows ?>
            <p>Who claims the relic from the overlord? <br /><span class="text-muted">(Ignored if the Overlord wins.)</span></p>
            <select name="special_heroes" class="form-control"><?php 
              foreach ($players as $h){
                if ($h['archetype'] != "Overlord"){  ?>
                  <option value="<?php print $h['id']; ?>"><?php print $h['name']; ?></option><?php
                }
              } ?>
            </select><?php
            break;

          case "27": // Honor Among Thieves ?>
            <p>Gold revealed in excess of goal?</p>
            <select name="special_heroes" class="form-control">
              <option value="0">None</option>
              <option value="25">25 Gold</option>
              <option value="50">50 Gold</option>
              <option value="75">75 Gold</option>
              <option value="100">100 Gold</option>
              <option value="125">125 Gold</option>
              <option value="150">150 Gold</option>
              <option value="175">175 Gold</option>
              <option value="200">200 Gold</option>
            </select><?php
            break;

          case "44": // Ghost town ?>
            <p>How many objective tokens do the heroes have in their area?</p>
            <select name="special_heroes" class="form-control">
              <option value="0">None</option>
              <option value="0">One</option>
              <option value="1">Two</option>
              <option value="1">Three</option>
              <option value="2">Four</option>
              <option value="2">Five</option>
              <option value="3">Six</option>
              <option value="3">Seven</option>
              <option value="4">Eight</option>
            </select><?php
            break;

          case "45": // Food for Worms ?>
            <p>How many villagers did not get discarded?</p>
            <select name="special_heroes" class="form-control">
              <option value="0">None</option>
              <option value="1">One</option>
              <option value="2">Two</option>
              <option value="3">Three</option>
              <option value="4">Four</option>
            </select><?php
            break;

          case "46": // Three Heads, One Mind ?>
            <p>How many named monsters were defeated?</p>
            <select name="special_heroes" class="form-control">
              <option value="0">None</option>
              <option value="1">One</option>
              <option value="2">Two</option>
            </select><?php
            break;

          case "49": // Ghost town ?>
            <p>How many objective tokens do the heroes have in their area?</p>
            <select name="special_heroes" class="form-control">
              <option value="0">None</option>
              <option value="1">One</option>
              <option value="2">Two</option>
              <option value="3">Three</option>
              <option value="4">Four</option>
              <option value="5">Five</option>
              <option value="6">Six</option>
              <option value="7">Seven</option>
              <option value="8">Eight</option>
            </select><?php
            break;

          case "55": // The Incident ?>
            <p>How many objective tokens do the heroes have in their area?</p>
            <select name="special_heroes" class="form-control">
              <option value="0">None</option>
              <option value="1">One</option>
              <option value="2">Two</option>
              <option value="3">Three</option>
              <option value="4">Four</option>
            </select><?php
            break;

          case "85": // Acolyte of Saradyn ?>
            <p>How many villager tokens do the heroes have in their play area?</p>
            <select name="special_heroes" class="form-control">
              <option value="0">None</option><?php 
              $xh = 1;
              foreach ($players as $h){
                if ($h['archetype'] != "Overlord"){  ?>
                  <option value="<?php print $xh; ?>"><?php print $xh; ?> Token(s)</option><?php
                  $xh++;
                }
              } ?>
            </select><?php
            break;
          case "87": // Skytower ?>
            <p>How many fatigue tokens are in the heroes' play area?</p>
            <select name="special_heroes" class="form-control">
              <option value="0">More than 2</option>
              <option value="1">Exactly 2</option>
              <option value="2">Less than 2</option>
            </select><?php
            break;

          case "88": // Blood Will Tell ?>
            <p>How many wards were discarded?</p>
            <select name="special_heroes" class="form-control">
              <option value="0">None</option>
              <option value="1">One</option>
              <option value="2">Two</option>
              <option value="3">Three</option>
            </select><?php
            break;

          case "95": //  ?>
            <p>Was Tyrus defeated? <br /><span class="text-muted">(Ignored if the Overlord wins.)</span></p>
            <select name="special_heroes" class="form-control">
              <option value="0">Yes</option>
              <option value="1">No</option>
            </select><?php
            break;

          case "108": // Strange Awakening ?>
            <p>How many heroes moved off the map?</p>
            <select name="special_heroes" class="form-control">
              <option value="0">None</option><?php 
              $xh = 1;
              foreach ($players as $h){
                if ($h['archetype'] != "Overlord"){  ?>
                  <option value="<?php print $xh; ?>"><?php print $xh; ?> Heroes</option><?php
                  $xh++;
                }
              } ?>
            </select><?php
            break;
        }
      }
    }

    foreach ($qs['rewardsOverlord'] as $rh){
      if ($rh[0] == "special"){
        switch ($qs['quest_id']){
          case "9": // Ritual of Shadows ?>  
            <p>Select Overlord cards to return. <br /><span class="text-muted">(Ignored if the Heroes win.)</span></p>
            <div class="well">
              <div class="row"><?php
                if(isset($acquiredOptions[$overlordID])){
                  foreach ($acquiredOptions[$overlordID] as $aqo){
                    echo $aqo;
                  }
                } else {
                  echo 'The overlord has no cards to return.';
                }
                 ?>
              </div>
            </div><?php
            break;

          case "26": // Gathering Foretold ?>
            <p>Did Splig survive?</p>
            <select name="special_overlord" class="form-control">
              <option value="reward">Yes</option>
              <option value="noreward">No</option>
            </select><?php
            break;

          case "44": // Ghost Town ?>
            <p>Does the overlord have at least 4 objective tokens in his area?</p>
            <select name="special_overlord" class="form-control">
              <option value="reward">Yes</option>
              <option value="noreward">No</option>
            </select><?php
            break;

          case "45": // Food for Worms ?>
            <p>Was the master Plague Worm defeated?</p>
            <select name="special_overlord" class="form-control">
              <option value="reward">No</option>
              <option value="noreward">Yes</option>
            </select><?php
            break;

          case "46": // Three Heads, One Mind ?>
            <p>Were Chi'kree and Grug'nik both defeated?</p>
            <select name="special_overlord" class="form-control">
              <option value="reward">No</option>
              <option value="noreward">Yes</option>
            </select><?php
            break;

          case "49": // Three Heads, One Mind ?>
            <p>Does the overlord have at least 1 villager token in his play area?</p>
            <select name="special_overlord" class="form-control">
              <option value="reward">Yes</option>
              <option value="noreward">No</option>
            </select><?php
            break;

          case "57": // Respected Citizen ?>
            <p>Did the overlord win the first encounter, or was Bertram defeated?</p>
            <select name="special_overlord" class="form-control">
              <option value="reward">Yes</option>
              <option value="noreward">No</option>
            </select><?php
            break;

          case "68": // Spread Your Wings ?>
            <p>Was Skarn removed from the Rookery?</p>
            <select name="special_overlord" class="form-control">
              <option value="reward">Yes</option>
              <option value="noreward">No</option>
            </select><?php
            break;

          case "69": // Finders and Keepers ?>
            <p>Was a hero knocked out while on the Spike Pit?</p>
            <select name="special_overlord" class="form-control">
              <option value="reward">Yes</option>
              <option value="noreward">No</option>
            </select><?php
            break;

          case "70": // My House, My Rules ?>
            <p>Was Skarn Defeated?</p>
            <select name="special_overlord" class="form-control">
              <option value="reward">No</option>
              <option value="noreward">Yes</option>
            </select><?php
            break;

          case "92": // My House, My Rules ?>
            <p>Did Baron Zachareth move off the map?</p>
            <select name="special_overlord" class="form-control">
              <option value="reward">Yes</option>
              <option value="noreward">No</option>
            </select><?php
            break;
        }
      }

      if ($rh[0] == "specialrelic"){
        switch ($qs['quest_id']){
          case "86": // ?>
            <p>Who receives the Staff of Light/Staff of Shadows?</p>
            <select name="special_relic" class="form-control"><?php 
              foreach ($players as $h){ ?>
                <option value="<?php echo $h['id'] ?>"><?php echo $h['name'] ?></option><?php
              } ?>
            </select><?php
            break;

          case "87": // ?>
              <p>Who receives the Shield of the Dark God/Shield of Zorek's Favor?</p>
              <select name="special_relic" class="form-control"><?php 
                foreach ($players as $h){ ?>
                  <option value="<?php echo $h['id'] ?>"><?php echo $h['name'] ?></option><?php
                } ?>
              </select>
            <?php
            break;

          case "89": // The Baron Returns ?>
            <p>Was the Shadow Rune set aside?</p>
            <select name="special_relic" class="form-control">
              <option value="none">Yes</option><?php 
              foreach ($players as $h){ 
                if ($h['archetype'] == 'Overlord'){ ?>
                  <option value="<?php echo $h['id'] ?>">No</option><?php
                }
              } ?>
            </select><?php
            break;

          case "95": // ?>
            <p>Who receives the Fortuna's Dice/Bones of Woe?</p>
            <select name="special_relic" class="form-control"><?php 
              foreach ($players as $h){ ?>
                <option value="<?php echo $h['id'] ?>"><?php echo $h['name'] ?></option><?php
              } ?>
            </select><?php
            break;

          case "96": // ?>
            <p>Who receives the The Shadow Rune?</p>
            <select name="special_relic" class="form-control"><?php 
              foreach ($players as $h){ ?>
                <option value="<?php echo $h['id'] ?>"><?php echo $h['name'] ?></option><?php
              } ?>
            </select><?php
            break;
        }
      }

      if ($rh[0] == "specialmonster"){
        switch ($qs['quest_id']){
          case "92": // ?>
            <p>Which lieutenant was chosen?</p>
            <select name="special_monster" class="form-control">
              <option value="1">Baron Zachareth</option>
              <option value="2">Splig</option>
              <option value="6">Belthir</option>
            </select><?php
            break;
        }
      }
    }

  echo '</div>';
  echo '<div class="col-sm-6">';
    echo '<h3>Special Items & Relic actions</h3>';
    echo "<p class='text-muted'>Any special item abilities are listed here. (E.g. Jinn's Lamp)</p>";
    if(in_array(79,$aquiredItems)){  ?>
      <p>Was an item was found using Jinn's Lamp?</p>
      <select name="jinns_lamp_item" class="form-control">
        <option value="empty">No</option>
        <?php foreach($availableItems as $ai) {
          echo $ai;
        } ?>
      </select>
      <input type="hidden" name="jinns_lamp_player" value="<?php echo $aquiredItemsDetails[79]['char_id']; ?>" />
      <input type="hidden" name="jinns_lamp_id" value="<?php echo $aquiredItemsDetails[79]['shop_id']; ?>" /><?php
    }

    if(in_array(174,$aquiredItems)){ ?>
      <p>Were any class cards returned with the Archaic Scroll?</p>
      <div class="well">
        <div class="row"><?php
          foreach ($acquiredOptions[$aquiredItemsDetails[174]['char_id']] as $aqo){
            echo $aqo;
          } ?>
        </div>
      </div>
      <input type="hidden" name="archaic_scroll_player" value="<?php echo $aquiredItemsDetails[174]['char_id']; ?>" />
      <input type="hidden" name="archaic_scroll_id" value="<?php echo $aquiredItemsDetails[174]['shop_id']; ?>" /><?php
    }


    $SunStoneReturn = 0;
    $SunStoneCheck = 0;
    $SunStonePilgrimage = 0;
    foreach ($players as $h){
      
      if ($h['archetype'] == "Overlord"){ 
        
        foreach($h['items'] as $hi){
          if (isset($hi['relic_id']) && $hi['relic_id'] == 11){
            $SunStoneReturn = 1;
            $SunStone_id = $hi['id'];
          }
          
        }
      } else {
        foreach($h['items'] as $hi){
          if (isset($hi['relic_id']) && $hi['relic_id'] == 11){
            $SunStoneCheck = 1;
            $SunStone_id = $hi['id'];
            if ($qs['quest_id'] == 34){
              $SunStonePilgrimage = 1;
            }
          }
          
        }
      }

    } 

    if ($SunStoneCheck == 1){ ?>
      <p>What happened to the Sun Stone?</p>
      <select name="sunstone_check" class="form-control">
        <option value="keep">Still held by heroes.</option>
        <option value="stolen">Taken by the Overlord</option>
        <option value="lost">Lost during this quest</option>
      </select><?php 
    }

    if ($SunStonePilgrimage == 1){ ?>
      <p>Did the heroes use the Sun Stone to heal one of the sentinels?</p>
      <select name="sunstone_pilgrimage" class="form-control">
        <option value="keep">No</option>
        <option value="lost">Yes</option>
      </select><?php 
    }

    if ($SunStoneReturn == 1){ ?>
      <p>Did the Overlord exchange the Sun's Fury relic for 1 XP?</p>
      <select name="sunstone_return" class="form-control">
        <option value="keep">No</option>
        <option value="lost">Yes</option>
      </select><?php 
    }

    if ($SunStoneCheck == 1 || $SunStonePilgrimage == 1 || $SunStoneReturn == 1){ ?>
    <input type="hidden" name="sunstone_id" value="<?php echo $SunStone_id; ?>" /><?php 
    }

    if ($citizen == 1){ ?>
      <p>Was a Master Changeling with a corrupted citizen card defeated?</p>
      <select name="citizen" class="form-control">
        <option value="no">No</option><?php 
        foreach ($citizens as $ct){
          echo '<option value="' . $ct['id'] . '">' . $ct['name'] . '</option>';
        } ?>
      </select><?php 
    }

    // Special Rewards Advanced Quests
    
    $adv_rew_used = explode(',', $campaign['adv_rew']);

    // armed to the teeth (double, because it needs to be available for the quest itself too)
    if ($qs['quest_id'] == 24){ ?>
      <p>Select a free item for 'Raiding the Armory'. (Armed to the Teeth reward) (Ignored if the Overlord wins)</p>
      <select name="armed_teeth_item" class="form-control">
        <option value="empty">Use Reward Later</option>
        <?php foreach($availableItems as $ai) {
          echo $ai;
        } ?>
      </select>
      <select name="armed_teeth_player" class="form-control"><?php 
        foreach ($players as $h){
          if ($h['archetype'] != "Overlord"){  ?>
            <option value="<?php echo $h['id'] ?>"><?php echo $h['name'] ?></option><?php
          }
        } ?>
      </select><?php
    }

    
    foreach ($campaign['quests'] as $AdvQuestRew){
      if(isset($AdvQuestRew["winner"])){

        // Armed to the teeth
        if($AdvQuestRew['quest_id'] == 24 && !in_array(24, $adv_rew_used)){ 
          if ($AdvQuestRew["winner"] == "Heroes Win"){ ?>
            <p>Select a free item for 'Raiding the Armory'. (Armed to the Teeth reward) (Ignored if the Overlord wins)</p>
            <select name="armed_teeth_item" class="form-control">
              <option value="empty">Use Reward Later</option>
              <?php foreach($availableItems as $ai) {
                echo $ai;
              } ?>
            </select>
            <select name="armed_teeth_player" class="form-control"><?php 
              foreach ($players as $h){
                if ($h['archetype'] != "Overlord"){  ?>
                  <option value="<?php echo $h['id'] ?>"><?php echo $h['name'] ?></option><?php
                }
              } ?>
            </select><?php
          } else { 
            if ($AdvQuestRew["winner"] == "Overlord Wins"){ ?>
              <p>Select a free item for 'Raiding the Armory'. (Armed to the Teeth reward)</p>
              <select name="armed_teeth_item" class="form-control">
                <?php foreach($availableItemsRelics as $air) {
                  echo $air;
                } ?>
              </select><?php
            }
          }
        }

        // Crown of Destiny
        if($AdvQuestRew['quest_id'] == 79 && !in_array(79, $adv_rew_used)){ 
          if ($AdvQuestRew["winner"] == "Heroes Win"){ ?>
            <p>Select cards to return for 'The Path not Taken'. (Crown of Destiny reward)</p>
            <div class="well"><?php
              foreach($players as $ply){ 
                if($ply['id'] != $overlordID){ 
                  echo '<strong>' . $ply['name'] . '</strong>'; ?>
                  <div class="row"><?php
                    if(isset($acquiredOptions[$ply['id']])){
                      foreach ($acquiredOptions[$ply['id']] as $aqo){
                        echo $aqo;
                      }
                    } else {
                      echo $ply['name'] . ' has no cards to return.';
                    } ?>
                  </div><?php
                }
              } ?>
            </div><?php
          } else { ?>
            <p>Select cards to return for the 'Reforged'. (Crown of Destiny reward)</p><?php 
            if ($qs['quest_id'] == 9){ ?>
              <p>(If the current quest is 'Ritual of Shadows' and the overlord wins this is ignored)</p><?php
            } ?> 
            <div class="well">
              <div class="row"><?php
                if(isset($acquiredOptions[$overlordID])){
                  foreach ($acquiredOptions[$overlordID] as $aqo){
                    echo $aqo;
                  }
                } else {
                  echo 'The overlord has no cards to return.';
                }
                 ?>
              </div>
            </div><?php
          }
        }

      } 
    }

  echo '</div>';
echo '</div>';


?>