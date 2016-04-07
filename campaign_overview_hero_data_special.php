<?php

$ip = 0;
$valueCalc = "";
$value = ($dti['default_price'] / 1000);
$valueCalc .= $value;


$tempextraSpeed = 0;
$temptooltipSpeedArray = array();
$tempmaxSpeed = 0;
$tempextraHealth = 0;
$tempattrHealth = array();
$temptooltipHealthArray = array();
$temptooltipAttrHealthArray = array();
$tempextraStamina = 0;
$tempextraDefense = "";
$temptooltipDefenseArray = array();
$temptooltipAttackArray = array();
$tempextraMight = 0;
$tempextraKnowledge = 0;
$tempextraWillpower = 0;
$tempextraAwareness = 0;
$tempattackdice = "";

if($dti['attack_dice'] != NULL){
  $attackdiceArray = str_split($dti['attack_dice']);

  if ($dti['item_type'] != "armor" && $attackdiceArray[0] == "B" && isset($attackdiceArray[1])){
    $tempattackdice = "B";
    $temptooltipAttackArray[] = array(
      "item" => $dti['item_name'],
      "skill" => NULL,
      "amount" => "Blue",
    );
    foreach($attackdiceArray as $attackdie){
      if ($attackdie == "B"){

      }
      if ($attackdie == "R"){
        $tempattackdice = $tempattackdice . "," . $attackdie;
        $value += 2;
        $temptooltipAttackArray[] = array(
          "item" => $dti['item_name'],
          "skill" => NULL,
          "amount" => "Red",
        );
      }
      if ($attackdie == "Y"){
        $tempattackdice = $tempattackdice . "," . $attackdie;
        $value += 1.5;
        $temptooltipAttackArray[] = array(
          "item" => $dti['item_name'],
          "skill" => NULL,
          "amount" => "Yellow",
        );
      }
      if ($attackdie == "G"){
        $tempattackdice = $tempattackdice . "," . $attackdie;
        $value += 1;
        $temptooltipAttackArray[] = array(
          "item" => $dti['item_name'],
          "skill" => NULL,
          "amount" => "Green",
        );
      }

    }
  }
}

if(in_array("shield", $dti['item_tags'])){
  $value += 2;
}





if($dti['item_special'] != NULL){
  $itemsSpecial = explode(';', $dti['item_special']);
  foreach ($itemsSpecial as $isp){
    $itemsSpecial[$ip] = explode(',', $isp);
    //echo $itemsSpecial[$ip][0];
    switch ($itemsSpecial[$ip][0]) {
      case "speed":
        $tempextraSpeed += intval($itemsSpecial[$ip][1]);
        $value += intval($itemsSpecial[$ip][1]);
        $valueCalc .= " + " . intval($itemsSpecial[$ip][1]);
        $temptooltipSpeedArray[] = array(
          "item" => $dti['item_name'],
          "skill" => NULL,
          "amount" => intval($itemsSpecial[$ip][1]),
        );
        break;
      case "limitspeed":
        $tempmaxSpeed = intval($itemsSpecial[$ip][1]);
        $value += ($itemsSpecial[$ip][1] - $h['speed']) * 1.5;
        $valueCalc .= " + " . ($itemsSpecial[$ip][1] - $h['speed']) * 1.5;
        break;
      case "health":
        $tempextraHealth += intval($itemsSpecial[$ip][1]);
        $value += intval($itemsSpecial[$ip][1]) * 0.75;
        $valueCalc .= " + " . intval($itemsSpecial[$ip][1]) * 0.75;
        $temptooltipHealthArray[] = array(
          "item" => $dti['item_name'],
          "skill" => NULL,
          "amount" => intval($itemsSpecial[$ip][1]),
        );
        break;
      case "attribhealth":
        $tempattrHealth[] = array(
          "item" => $dti['item_name'],
          "bonus" => intval($itemsSpecial[$ip][1]),
          "type" => intval($itemsSpecial[$ip][2]),
          "required" => intval($itemsSpecial[$ip][3]),
        );
        break;
      case "stamina":
        $tempextraStamina += intval($itemsSpecial[$ip][1]);
        $value += intval($itemsSpecial[$ip][1]) * 2;
        $valueCalc .= " + " . intval($itemsSpecial[$ip][1]) * 2;
        $temptooltipStaminaArray[] = array(
          "item" => $dti['item_name'],
          "skill" => NULL,
          "amount" => intval($itemsSpecial[$ip][1]),
        );
        break;
      case "attack":
        if ($itemsSpecial[$ip][1] == "R"){
          $die = "Red";
          $value += 2;
          $valueCalc .= " + 2";
        } else if ($itemsSpecial[$ip][1] == "Y"){
          $die = "Yellow";
          $value += 1.5;
          $valueCalc .= " + 1.5";
        } else if ($itemsSpecial[$ip][1] == "G"){
          $die = "Green";
          $value += 1;
          $valueCalc .= " + 1";
        }
        $tempattackdice = $tempattackdice . "," . $itemsSpecial[$ip][1];
        $temptooltipAttackArray[] = array(
          "item" => $dti['item_name'],
          "skill" => NULL,
          "amount" => $die,
        );
        break;
      case "defense":
        if ($itemsSpecial[$ip][1] == "B"){
          $die = "Brown";
          $value += 1;
          $valueCalc .= " + 1";
        } else if ($itemsSpecial[$ip][1] == "G"){
          $die = "Grey";
          $value += 3;
          $valueCalc .= " + 3";
        } else if ($itemsSpecial[$ip][1] == "BL"){
          $die = "Black";
          $value += 5;
          $valueCalc .= " + 5";
        }
        $tempextraDefense = $tempextraDefense . "," . $itemsSpecial[$ip][1];
        $temptooltipDefenseArray[] = array(
          "item" => $dti['item_name'],
          "skill" => NULL,
          "amount" => $die,
        );
        break;
      case "runedefense":
        if(!in_array("rune", $itemTags)){
          $tempextraDefense = $tempextraDefense . "," . $itemsSpecial[$ip][1];
          $tempdie = $itemsSpecial[$ip][1];
        } else {
          $tempextraDefense = $tempextraDefense . "," . $itemsSpecial[$ip][2];
          $tempdie = $itemsSpecial[$ip][2];
        }
        if ($tempdie == "B"){
          $die = "Brown";
          $value += 1;
          $valueCalc .= " + 1";
        } else if ($tempdie == "G"){
          $die = "Grey";
          $value += 3;
          $valueCalc .= " + 3";
        } else if ($tempdie == "BL"){
          $die = "Black";
          $value += 5;
          $valueCalc .= " + 5";
        }
        $temptooltipDefenseArray[] = array(
          "item" => $dti['item_name'],
          "skill" => NULL,
          "amount" => $die,
        );
        break;
      case "might":
        $tempextraMight += intval($itemsSpecial[$ip][1]);
        $value += intval($itemsSpecial[$ip][1]) * 1.5;
        $valueCalc .= " + " . intval($itemsSpecial[$ip][1]) * 1.5;
        $temptooltipMightArray[] = array(
          "item" => $dti['item_name'],
          "skill" => NULL,
          "amount" => intval($itemsSpecial[$ip][1]),
        );
        break;
      case "knowledge":
        $tempextraKnowledge += intval($itemsSpecial[$ip][1]);
        $value += intval($itemsSpecial[$ip][1]) * 1.5;
        $valueCalc .= " + " . intval($itemsSpecial[$ip][1]) * 1.5;
        $temptooltipKnowledgeArray[] = array(
          "item" => $dti['item_name'],
          "skill" => NULL,
          "amount" => intval($itemsSpecial[$ip][1]),
        );
        break;
      case "willpower":
        $tempextraWillpower += intval($itemsSpecial[$ip][1]);
        $value += intval($itemsSpecial[$ip][1]) * 1.5;
        $valueCalc .= " + " . intval($itemsSpecial[$ip][1]) * 1.5;
        $temptooltipWillpowerArray[] = array(
          "item" => $dti['item_name'],
          "skill" => NULL,
          "amount" => intval($itemsSpecial[$ip][1]),
        );
        break;
      case "awareness":
        $tempextraAwareness += intval($itemsSpecial[$ip][1]);
        $value += intval($itemsSpecial[$ip][1]) * 1.5;
        $valueCalc .= " + " . intval($itemsSpecial[$ip][1]) * 1.5;
        $temptooltipAwarenessArray[] = array(
          "item" => $dti['item_name'],
          "skill" => NULL,
          "amount" => intval($itemsSpecial[$ip][1]),
        );
        break;
    }

    $ip++;
  }
}

if($dti['item_type'] == "2h"){
  $value = $value * 1.75;
}

//echo $dti['item_name'] . " = " . $value . "<br />";
if($dti['item_type'] == "1h" && !in_array("shield", $dti['item_tags']) && ($bestHand1 == NULL || ($bestHand1Value < $value && $bestHand2Value >= $bestHand1Value && $bestHand2 != NULL))){

  $bestHand1 = $dti['item_id'];
  $bestHand1Value = $value;

  $H1extraSpeed = $tempextraSpeed;
  $H1tooltipSpeed = $temptooltipSpeedArray;
  $H1maxSpeed = $tempmaxSpeed;
  $H1extraHealth = $tempextraHealth;
  $H1attrHealth = $tempattrHealth;
  $H1tooltipHealthArray = $temptooltipHealthArray;
  $H1tooltipAttrHealthArray = $temptooltipAttrHealthArray;
  $H1extraStamina = $tempextraStamina;
  $H1extraDefense = $tempextraDefense;
  $H1tooltipDefenseArray = $temptooltipDefenseArray;
  $H1tooltipAttackArray = $temptooltipAttackArray;

  $H1attackdice = $tempattackdice;

  $H1extraMight = $tempextraMight;
  $H1extraKnowledge = $tempextraKnowledge;
  $H1extraWillpower = $tempextraWillpower;
  $H1extraAwareness = $tempextraAwareness;
} else if($dti['item_type'] == "1h" && ($bestHand2 == NULL || $bestHand2Value < $value)){
  $bestHand2 = $dti['item_id'];
  $bestHand2Value = $value;

  $H2extraSpeed = $tempextraSpeed;
  $H2tooltipSpeed = $temptooltipSpeedArray;
  $H2maxSpeed = $tempmaxSpeed;
  $H2extraHealth = $tempextraHealth;
  $H2attrHealth = $tempattrHealth;
  $H2tooltipHealthArray = $temptooltipHealthArray;
  $H2tooltipAttrHealthArray = $temptooltipAttrHealthArray;
  $H2extraStamina = $tempextraStamina;
  $H2extraDefense = $tempextraDefense;
  $H2tooltipDefenseArray = $temptooltipDefenseArray;
  $H2tooltipAttackArray = $temptooltipAttackArray;

  $H2attackdice = $tempattackdice;

  $H2extraMight = $tempextraMight;
  $H2extraKnowledge = $tempextraKnowledge;
  $H2extraWillpower = $tempextraWillpower;
  $H2extraAwareness = $tempextraAwareness;
}
if($dti['item_type'] == "2h" && ($bestHand1 == NULL || ($bestHand1Value + $bestHand2Value) < $value)){
  $bestHand1 = $dti['item_id'];
  $bestHand2 = NULL;
  $bestHand1Value = $value;
  $bestHand2Value = 0;

  $H1extraSpeed = $tempextraSpeed;
  $H1tooltipSpeed = $temptooltipSpeedArray;
  $H1maxSpeed = $tempmaxSpeed;
  $H1extraHealth = $tempextraHealth;
  $H1attrHealth = $tempattrHealth;
  $H1tooltipHealthArray = $temptooltipHealthArray;
  $H1tooltipAttrHealthArray = $temptooltipAttrHealthArray;
  $H1extraStamina = $tempextraStamina;
  $H1extraDefense = $tempextraDefense;
  $H1tooltipDefenseArray = $temptooltipDefenseArray;
  $H1tooltipAttackArray = $temptooltipAttackArray;

  $H1attackdice = $tempattackdice;
  $H1extraMight = $tempextraMight;
  $H1extraKnowledge = $tempextraKnowledge;
  $H1extraWillpower = $tempextraWillpower;
  $H1extraAwareness = $tempextraAwareness;

  $H2extraSpeed = 0;
  $H2tooltipSpeed = array();
  $H2maxSpeed = 0;
  $H2extraHealth = 0;
  $H2attrHealth = array();
  $H2tooltipHealthArray = array();
  $H2tooltipAttrHealthArray = array();
  $H2extraStamina = 0;
  $H2extraDefense = "";
  $H2tooltipDefenseArray = array();
  $H2tooltipAttackArray = array();
  $H2attackdice = "";
  $H2extraMight = 0;
  $H2extraKnowledge = 0;
  $H2extraWillpower = 0;
  $H2extraAwareness = 0;
}
if($dti['item_type'] == "armor" && ($bestArmor == NULL || $bestArmorValue < $value)){
  $bestArmor = $dti['item_id'];
  $bestArmorValue = $value;

  $AextraSpeed = $tempextraSpeed;
  $AtooltipSpeed = $temptooltipSpeedArray;
  $AmaxSpeed = $tempmaxSpeed;
  $AextraHealth = $tempextraHealth;
  $AattrHealth = $tempattrHealth;
  $AtooltipHealthArray = $temptooltipHealthArray;
  $AtooltipAttrHealthArray = $temptooltipAttrHealthArray;
  $AextraStamina = $tempextraStamina;
  $AextraDefense = $tempextraDefense;
  $AtooltipDefenseArray = $temptooltipDefenseArray;
  $AtooltipAttackArray = $temptooltipAttackArray;

  $Aattackdice = $tempattackdice;

  $AextraMight = $tempextraMight;
  $AextraKnowledge = $tempextraKnowledge;
  $AextraWillpower = $tempextraWillpower;
  $AextraAwareness = $tempextraAwareness;
}

if($dti['item_type'] == "other" && ($bestOther1 == NULL || ($bestOther1Value < $value && $bestOther2 != NULL))){
  $bestOther1 = $dti['item_id'];
  $bestOther1Value = $value;

  $O1extraSpeed = $tempextraSpeed;
  $O1ooltipSpeed = $temptooltipSpeedArray;
  $O1maxSpeed = $tempmaxSpeed;
  $O1extraHealth = $tempextraHealth;
  $O1attrHealth = $tempattrHealth;
  $O1tooltipHealthArray = $temptooltipHealthArray;
  $O1tooltipAttrHealthArray = $temptooltipAttrHealthArray;
  $O1extraStamina = $tempextraStamina;
  $O1extraDefense = $tempextraDefense;
  $O1tooltipDefenseArray = $temptooltipDefenseArray;
  $O1tooltipAttackArray = $temptooltipAttackArray;

  $O1attackdice = $tempattackdice;

  $O1extraMight = $tempextraMight;
  $O1extraKnowledge = $tempextraKnowledge;
  $O1extraWillpower = $tempextraWillpower;
  $O1extraAwareness = $tempextraAwareness;
} else if($dti['item_type'] == "other" && ($bestOther2 == NULL || $bestOther2Value < $value)){
  $bestOther2 = $dti['item_id'];
  $bestOther2Value = $value;

  $O2extraSpeed = $tempextraSpeed;
  $O2ooltipSpeed = $temptooltipSpeedArray;
  $O2maxSpeed = $tempmaxSpeed;
  $O2extraHealth = $tempextraHealth;
  $O2attrHealth = $tempattrHealth;
  $O2tooltipHealthArray = $temptooltipHealthArray;
  $O2tooltipAttrHealthArray = $temptooltipAttrHealthArray;
  $O2extraStamina = $tempextraStamina;
  $O2extraDefense = $tempextraDefense;
  $O2tooltipDefenseArray = $temptooltipDefenseArray;
  $O2tooltipAttackArray = $temptooltipAttackArray;

  $O2attackdice = $tempattackdice;

  $O2extraMight = $tempextraMight;
  $O2extraKnowledge = $tempextraKnowledge;
  $O2extraWillpower = $tempextraWillpower;
  $O2extraAwareness = $tempextraAwareness;
}
