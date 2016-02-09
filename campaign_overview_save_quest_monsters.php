<?php
$currentlyBlocked = array();
$currentlyAdded = array();
$currentlyReplaced = array();

if(isset($monsters_usable)){
  foreach ($monsters_usable as $mu){
    if ($mu['monster_time'] == $currentAct || $mu['monster_time'] == "All"){
      if ($mu['monster_status'] == "blocked"){
        $currentlyBlocked[] = $mu['monster_id'];
      }

      if ($mu['monster_status'] == "added"){
        $currentlyAdded[] = $mu['monster_id'];
      }

      if ($mu['monster_status'] == "replace"){
        $currentlyReplaced[] = $mu['monster_id'];
      }
    }
  }
}

$encounters = array();
$encounters[] = array(
  "traits" => $qs['traits_enc1'],
  "monsters" => $qs['monsters_enc1'],
  "special" => $qs['monsters_special_enc1'],
);

if(isset($qs['traits_enc2'])){
  $encounters[] = array(
    "traits" => $qs['traits_enc2'],
    "monsters" => $qs['monsters_enc2'],
    "special" => $qs['monsters_special_enc2'],
  );
}

if(isset($qs['traits_enc3'])){
  $encounters[] = array(
    "traits" => $qs['traits_enc3'],
    "monsters" => $qs['monsters_enc3'],
    "special" => $qs['monsters_special_enc3'],
  );
}

$mi = 1;
foreach ($encounters as $enc){
  if($mi == 1 || $mi == 3){
    echo '<div class="row">';
  }
  
  echo '<div class="col-sm-6">';

  $specialCheck = $enc['monsters'];
  $specialMessage = array();

  foreach ($specialCheck as $mekey => $me){

    if ($enc['special'] != NULL){
      // go through each setting
      foreach ($enc['special'] as $mod){
        $mods = explode(',', $mod);

        if ($mods[0] == "replace"){
          $mods[2] = explode('-', $mods[2]);
          $replace = 0;
          $monstername = "";
          $monsternamereplace = "";
          foreach ($campaign['quests'] as $qsm){
            if (in_array($qsm['quest_id'], $mods[2]) && $qsm['winner'] == "Heroes Win"){
              $replace = 1;
              $questname = $qsm['name'];
            }
          }
          foreach ($allMonsters as $am){
            if ($mods[1] == $am['id']){
              $monstername = $am['name'];
            }
            if ($mods[3] == $am['id']){
              $monsterreplace = $am['name'];
            } else if ($mods[3] == "open"){
              $monsterreplace = "an open group";
            } else if ($mods[3] == 999){
              $monsterreplace = "not present";
            }
          }

          if ($replace == 1){
            if ($me == $mods[1]){
              $specialCheck[$mekey] = $mods[3];
              if ($monsterreplace == "not present"){
                $specialMessage[] = '<p>* ' . $monstername . ' is not present because the heroes won ' . $questname . '.</p>';
              } else {
                $specialMessage[] = '<p>* ' . $monstername . ' has been replaced with ' . $monsterreplace . ' because the heroes won ' . $questname . '.</p>';
              }
            }

          }

        }

      }

    }

  }

  echo '<h4>Encounter ' . $mi . '</h4>';
  $echoTraits = "";
  foreach($enc['traits'] as $et){
    $echoTraits .= $et . ', ';
  }
  $echoTraits = rtrim($echoTraits, ", ");
  echo '<p class="text-muted"><strong>Traits:</strong> ' . ucwords($echoTraits) . '</p>';
    			
  foreach ($specialCheck as $menc){	
    // Create an array to store all available monsters
    $checkDouble = array();
    // If the group is a simple open group
  	if ($menc == "open"){ 
      ?>
  		<select name="progress_enc<?php echo $mi; ?>_monsters[]" class="form-control"><?php
        // Loop through all monsters
  			foreach ($allMonsters as $am){
  			 	$intersection = array_intersect($am['traits'], $enc['traits']);
  			 	if ( (!empty($intersection) && !in_array($am['id'], $enc['monsters'])) || in_array($am['id'], $currentlyAdded)){
            echo $am['option'];
            $_SESSION['verify_values']['monsters_enc' . $mi][] = $am['id'];
            $checkDouble[] = $am['id'];
  				}
  			} ?>
  		</select><?php

    // else, if it is a group with only small monsters
  	} else if ($menc == "opensmall"){ ?>
  		<select name="progress_enc<?php echo $mi; ?>_monsters[]" class="form-control"><?php
        // Loop through all monsters
  		 	foreach ($allMonsters as $am){
  		 		$intersection = array_intersect($am['traits'], $enc['traits']);
  		 		if ((!empty($intersection) && (!in_array($am['id'], $enc['monsters']))) || in_array($am['id'], $currentlyAdded)){
  		 			if ($am['type'] == "small"){
  		 				echo $am['option'];
              $_SESSION['verify_values']['monsters_enc' . $mi][] = $am['id'];
  		 			}
  			 	}
  		 	} ?>
  		</select><?php

    // else, if no massive monsters are allowed
  	} else if ($menc == "opennomassive"){ ?>
      <select name="progress_enc<?php echo $mi; ?>_monsters[]" class="form-control"><?php
        // Loop through all monsters
        foreach ($allMonsters as $am){
          $intersection = array_intersect($am['traits'], $enc['traits']);
          if ((!empty($intersection) && (!in_array($am['id'], $enc['monsters']))) || in_array($am['id'], $currentlyAdded)){
            if ($am['type'] != "massive"){
              echo $am['option'];
              $_SESSION['verify_values']['monsters_enc' . $mi][] = $am['id'];
            }
          }
        }
      ?>
      </select> <?php

    // else, if there is a open monster group per hero
    } else if ($menc == "openhero"){ 
      // Loop through the players
      foreach ($players as $h){ 
        // Ignore the overlord
        if ($h['archetype'] != "Overlord"){?>
          <select name="progress_enc<?php echo $mi; ?>_monsters[]" class="form-control"><?php
            // Loop through all monsters
            foreach ($allMonsters as $am){
              $intersection = array_intersect($am['traits'], $enc['traits']);
              if ((!empty($intersection) && (!in_array($am['id'], $enc['monsters']))) || in_array($am['id'], $currentlyAdded)){
                echo $am['option'];
                $checkDouble[] = $am['id'];
                $_SESSION['verify_values']['monsters_enc' . $mi][] = $am['id'];
              }
            } ?>
          </select><?php
        }
      } 

    // else, if the open group needs to include a master monster
    } else if ($menc == "openneedmaster"){ ?>
      <select name="progress_enc<?php echo $mi; ?>_monsters[]" class="form-control"><?php
        foreach ($allMonsters as $am){
          $intersection = array_intersect($am['traits'], $enc['traits']);
          if ((!empty($intersection) && (!in_array($am['id'], $enc['monsters']))) || in_array($am['id'], $currentlyAdded)){
            if ($am['monster_limits'][count($players) - 1]['masters'] > 0){
              echo $am['option'];
              $_SESSION['verify_values']['monsters_enc' . $mi][] = $am['id'];
            }
          }
        } ?>
      </select><?php

    // else, if its an ally
    } else if ($menc == "ally"){ ?>
        <select name="progress_enc<?php echo $mi; ?>_monsters[]" class="form-control">
        <?php
          echo '<option name="monster" value="21">Serena</option>';
          $_SESSION['verify_values']['monsters_enc' . $mi][] = 21;
          echo '<option name="monster" value="22">Raythen</option>';
          $_SESSION['verify_values']['monsters_enc' . $mi][] = 22;
        ?>
        </select> <?php

    // else, show the preselected monster
    } else { 
			foreach ($allMonsters as $am){
				if ($am['id'] == $menc && !in_array($am['id'], $currentlyBlocked)){ ?>
					<select name="progress_enc<?php echo $mi; ?>_monsters[]" class="form-control" readonly><?php	
            echo $am['option'];	
            $_SESSION['verify_values']['monsters_enc' . $mi][] = $am['id']; ?>
					</select><?php
				}
			}
		}
  }

  foreach ($specialMessage as $sm){
    echo $sm;
  }   

  echo '</div>';
  if(count($encounters) == 1 && $mi == 1){
    echo '</div>';
  } else if(count($encounters) == 3 && $mi == 3){
    echo '</div>';
  } else if($mi == 2 || $mi == 4){
    echo '</div>';
  }
  $mi++;

} ?>