<?php

$DetailItemsList = array();

$extraSpeed = $AextraSpeed = $O1extraSpeed = $O2extraSpeed = $H1extraSpeed = $H2extraSpeed = 0;
$tooltipSpeed = $AtooltipSpeed = $O1tooltipSpeed = $O2tooltipSpeed = $H1tooltipSpeed = $H2tooltipSpeed = array();
$maxSpeed = $AmaxSpeed = $O1maxSpeed = $O2maxSpeed = $H1maxSpeed = $H2maxSpeed = 0;
$extraHealth = $AextraHealth = $O1extraHealth = $O2extraHealth = $H1extraHealth = $H2extraHealth = 0;
$attrHealth = $AattrHealth = $O1attrHealth = $O2attrHealth = $H1attrHealth = $H2attrHealth = array();
$tooltipHealthArray = $AtooltipHealthArray = $O1tooltipHealthArray = $O2tooltipHealthArray = $H1tooltipHealthArray = $H2tooltipHealthArray = array();
$tooltipAttrHealthArray = $AtooltipAttrHealthArray = $O1tooltipAttrHealthArray = $O2tooltipAttrHealthArray = $H1tooltipAttrHealthArray = $H2tooltipAttrHealthArray = array();
$extraStamina = $AextraStamina = $O1extraStamina = $O2extraStamina = $H1extraStamina = $H2extraStamina = 0;
$extraDefense = $AextraDefense = $O1extraDefense = $O2extraDefense = $H1extraDefense = $H2extraDefense = "";
$tooltipDefenseArray = $AtooltipDefenseArray = $O1tooltipDefenseArray = $O2tooltipDefenseArray = $H1tooltipDefenseArray = $H2tooltipDefenseArray = array();

$tooltipAttackArrayH1 = $tooltipAttackArrayH2 = $AtooltipAttackArray = $O1tooltipAttackArray = $O2tooltipAttackArray = $H1tooltipAttackArray = $H2tooltipAttackArray = array();

$extraMight = $AextraMight = $O1extraMight = $O2extraMight = $H1extraMight = $H2extraMight = 0;
$extraKnowledge = $AextraKnowledge = $O1extraKnowledge = $O2extraKnowledge = $H1extraKnowledge = $H2extraKnowledge = 0;
$extraWillpower = $AextraWillpower = $O1extraWillpower = $O2extraWillpower = $H1extraWillpower = $H2extraWillpower = 0;
$extraAwareness = $AextraAwareness = $O1extraAwareness = $O2extraAwareness = $H1extraAwareness = $H2extraAwareness = 0;

$attackdiceH1 = $attackdiceH2 = $H1attackdice = $H2attackdice = $Aattackdice = $O1attackdice = $O2attackdice = "";

// Get the skills
$query_rsDetailSkills = sprintf("SELECT * FROM tbskills_aquired INNER JOIN tbskills ON spendxp_skill_id = skill_id WHERE spendxp_char_id = %s AND spendxp_sold = %s ORDER BY skill_class, skill_cost, skill_name ASC", 
                        GetSQLValueString($heroID, "int"),
                        GetSQLValueString(0, "int"));
$rsDetailSkills = mysql_query($query_rsDetailSkills, $dbDescent) or die(mysql_error());
$row_rsDetailSkills = mysql_fetch_assoc($rsDetailSkills);
$totalRows_rsDetailSkills = mysql_num_rows($rsDetailSkills);



do{

  $tooltipSkills = 
  "<div class='text-center tooltip-div row'>" .
    "<div class='col-sm-12'>" . 
      "<div><strong>" . $row_rsDetailSkills['skill_name'] . "</strong></div>" . 
      "<div class='col-sm-12 item-text'>" . $row_rsDetailSkills['skill_text'] . "</div>" . 
      "<div class='col-sm-3 text-margin-5'>" . $row_rsDetailSkills['skill_cost'] . "XP</div>" . "<div class='col-sm-2'></div>" . "<div class='col-sm-7 text-margin-5'>" . $row_rsDetailSkills['skill_stamina_cost'] . " Stamina</div>" . 
    "</div>" .
  "</div>";

  $DetailSkillsList[] = array(
    "skill_id" => $row_rsDetailSkills['skill_id'],
    "skill_name" => $row_rsDetailSkills['skill_name'],
    "skill_cost" => $row_rsDetailSkills['skill_cost'],
    "skill_class" => $row_rsDetailSkills['skill_class'],
    "skill_stamina_cost" => $row_rsDetailSkills['skill_stamina_cost'],
    "skill_plot" => $row_rsDetailSkills['skill_plot'],
    "tooltip" => $tooltipSkills,
  );


  if($row_rsDetailSkills['skill_special'] != NULL){
    $skillsSpecial = explode(';', $row_rsDetailSkills['skill_special']);

    $sp = 0;
    foreach ($skillsSpecial as $ssp){
      $skillsSpecial[$sp] = explode(',', $ssp);

      switch ($skillsSpecial[$sp][0]) {
        case "speed":
          $extraSpeed += intval($skillsSpecial[$sp][1]);
          $tooltipSpeedArray[] = array(
            "item" => NULL,
            "skill" => $row_rsDetailSkills['skill_name'],
            "amount" => intval($skillsSpecial[$sp][1]),
          );
          break;
        case "health":
          $extraHealth += intval($skillsSpecial[$sp][1]);
          $tooltipHealthArray[] = array(
            "item" => NULL,
            "skill" => $row_rsDetailSkills['skill_name'],
            "amount" => intval($skillsSpecial[$sp][1]),
          );
          break;
        case "stamina":
          $extraStamina += intval($skillsSpecial[$sp][1]);
          $tooltipStaminaArray[] = array(
            "item" => NULL,
            "skill" => $row_rsDetailSkills['skill_name'],
            "amount" => intval($skillsSpecial[$sp][1]),
          );
          break;
        case "defense":
          $extraDefense = $extraDefense . "," . $skillsSpecial[$sp][1];
          if ($skillsSpecial[$sp][1] == "B"){
            $die = "Brown";
          } else if ($skillsSpecial[$sp][1] == "G"){
            $die = "Grey";
          } else if ($skillsSpecial[$sp][1] == "BL"){
            $die = "Black";
          }
          $tooltipDefenseArray[] = array(
            "item" => NULL,
            "skill" => $row_rsDetailSkills['skill_name'],
            "amount" => $die,
          );
          break;
        case "might":
          $extraMight += intval($skillsSpecial[$sp][1]);
          $tooltipMightArray[] = array(
            "item" => NULL,
            "skill" => $row_rsDetailSkills['skill_name'],
            "amount" => intval($skillsSpecial[$sp][1]),
          );
          break;
        case "knowledge":
          $extraKnowledge += intval($skillsSpecial[$sp][1]);
          $tooltipKnowledgeArray[] = array(
            "item" => NULL,
            "skill" => $row_rsDetailSkills['skill_name'],
            "amount" => intval($skillsSpecial[$sp][1]),
          );
          break;
        case "willpower":
          $extraWillpower += intval($skillsSpecial[$sp][1]);
          $tooltipWillpowerArray[] = array(
            "item" => NULL,
            "skill" => $row_rsDetailSkills['skill_name'],
            "amount" => intval($skillsSpecial[$sp][1]),
          );
          break;
        case "awareness":
          $extraAwareness += intval($skillsSpecial[$sp][1]);
          $tooltipAwarenessArray[] = array(
            "item" => NULL,
            "skill" => $row_rsDetailSkills['skill_name'],
            "amount" => intval($skillsSpecial[$sp][1]),
          );
          break;
      }

      $sp++;
    }

    //var_dump($skillsSpecial);

  }

} while ($row_rsDetailSkills = mysql_fetch_assoc($rsDetailSkills)); 


// Get the items
$query_rsDetailItems = sprintf("SELECT * FROM tbitems_aquired INNER JOIN tbitems ON aq_item_id = item_id WHERE aq_char_id = %s AND aq_item_sold = %s AND aq_trade_char_id is null ORDER BY item_type", GetSQLValueString($heroID, "int"), GetSQLValueString(0, "int"));
$rsDetailItems = mysql_query($query_rsDetailItems, $dbDescent) or die(mysql_error());
$row_rsDetailItems = mysql_fetch_assoc($rsDetailItems);
$totalRows_rsDetailItems = mysql_num_rows($rsDetailItems);

do {

  $diceimg = "";
  if ($row_rsDetailItems['item_attack_dice'] != NULL){
    $diceimg = "<img src='img/" . $row_rsDetailItems['item_attack_dice'] . ".png' />";
  }

  $tooltipItems = 
  "<div class='text-center tooltip-div row'>" .
    "<div class='col-sm-12'>" . 
      "<div><strong>" . $row_rsDetailItems['item_name'] . "</strong></div>" . 
      "<div><small>" . $row_rsDetailItems['item_tags'] . "</small></div>" . 
      "<div class='col-sm-5 text-margin-5'>" . $row_rsDetailItems['item_attack'] . "</div>" . "<div class='col-sm-2'></div>" . "<div class='col-sm-5'>" . $diceimg . "</div>" . 
      "<div class='col-sm-12 item-text'>" . $row_rsDetailItems['item_text'] . "</div>" . 
      "<div class='col-sm-5 text-margin-5'>" . $row_rsDetailItems['item_act'] . "</div>" . "<div class='col-sm-2'></div>" . "<div class='col-sm-5 text-margin-5'>" . $row_rsDetailItems['item_default_price'] . " G</div>" . 
    "</div>" .
  "</div>";

  $DetailItemsList[] = array(
    "item_name" => $row_rsDetailItems['item_name'],
    "item_id" => $row_rsDetailItems['item_id'],
    "item_type" => $row_rsDetailItems['item_type'],
    "item_tags" => explode(",",$row_rsDetailItems['item_tags']),
    "default_price" => $row_rsDetailItems['item_default_price'],
    "override_price" => $row_rsDetailItems['aq_item_price_ovrd'],
    "item_special" => $row_rsDetailItems['item_special'],
    "item_img" => $row_rsDetailItems['market_img'],
    "attack_dice" => $row_rsDetailItems['item_attack_dice'],
    "tooltip" => $tooltipItems,
  );

} while ($row_rsDetailItems = mysql_fetch_assoc($rsDetailItems));

// Get the relics
$query_rsDetailRelics = sprintf("SELECT * FROM tbitems_aquired INNER JOIN tbitems_relics ON aq_relic_id = relic_id WHERE aq_char_id = %s AND aq_trade_char_id is null", GetSQLValueString($heroID, "int"));
$rsDetailRelics = mysql_query($query_rsDetailRelics, $dbDescent) or die(mysql_error());
$row_rsDetailRelics = mysql_fetch_assoc($rsDetailRelics);
$totalRows_rsDetailRelics = mysql_num_rows($rsDetailRelics);

do{

  $diceimg = "";
  if ($row_rsDetailRelics['relic_dice'] != NULL){
    $diceimg = "<img src='img/" . $row_rsDetailRelics['relic_dice'] . ".png' />";
  }

  $tooltip_h_Relics = 
  "<div class='text-center tooltip-div row'>" .
    "<div class='col-sm-12'>" . 
      "<div><strong>" . $row_rsDetailRelics['relic_h_name'] . "</strong></div>" . 
      //"<div><small>" . $row_rsDetailRelics['item_tags'] . "</small></div>" . 
      "<div class='col-sm-5 text-margin-5'>" . $row_rsDetailRelics['relic_attack'] . "</div>" . "<div class='col-sm-2'></div>" . "<div class='col-sm-5'>" . $diceimg . "</div>" . 
      "<div class='col-sm-12 item-text'>" . $row_rsDetailRelics['relic_h_text'] . "</div>" . 
    "</div>" .
  "</div>";

  $tooltip_ol_Relics = 
  "<div class='text-center tooltip-div row'>" .
    "<div class='col-sm-12'>" . 
      "<div><strong>" . $row_rsDetailRelics['relic_ol_name'] . "</strong></div>" . 
      "<div class='col-sm-12 item-text'>" . $row_rsDetailRelics['relic_ol_text'] . "</div>" . 
    "</div>" .
  "</div>";


  $DetailRelicsList[] = array(
    "relic_h_name" => $row_rsDetailRelics['relic_h_name'],
    "relic_ol_name" => $row_rsDetailRelics['relic_ol_name'],
    "relic_type" => $row_rsDetailRelics['relic_type'],
    "tooltip_h" => $tooltip_h_Relics,
    "tooltip_ol" => $tooltip_ol_Relics,
    "item_id" => $row_rsDetailRelics['relic_id'],
    'item_name' => $row_rsDetailRelics['relic_h_name'],
    "item_type" => $row_rsDetailRelics['relic_type'],
    "item_tags" => explode(",",$row_rsDetailRelics['relic_tags']),
    "default_price" => 500,
    "item_special" => $row_rsDetailRelics['relic_special'],
    "attack_dice" => $row_rsDetailRelics['relic_dice'],
  );
} while ($row_rsDetailRelics = mysql_fetch_assoc($rsDetailRelics));

// echo '<pre>';
// var_dump($DetailRelicsList);
// echo '</pre>';

// Get the traded items
// $query_rsDetailTradedItems = sprintf("SELECT * FROM tbitems_aquired INNER JOIN tbitems ON aq_item_id = item_id WHERE aq_trade_char_id = %s AND aq_item_sold = %s", GetSQLValueString($heroID, "int"), GetSQLValueString(0, "int"));
// $rsDetailTradedItems = mysql_query($query_rsDetailTradedItems, $dbDescent) or die(mysql_error());
// $row_rsDetailTradedItems = mysql_fetch_assoc($rsDetailTradedItems);
// $totalRows_rsDetailTradedItems = mysql_num_rows($rsDetailTradedItems);

// do {
//   if($row_rsDetailTradedItems['item_name'] != NULL){
//     $DetailItemsList[] = array(
//       "item_name" => $row_rsDetailTradedItems['item_name'],
//       "default_price" => $row_rsDetailTradedItems['item_default_price'],
//       "override_price" => $row_rsDetailTradedItems['aq_item_price_ovrd'],
//       "item_type" => $row_rsDetailTradedItems['item_type'],
//       "item_special" => $row_rsDetailTradedItems['item_special'],
//     );
//   }

// } while ($row_rsDetailTradedItems = mysql_fetch_assoc($rsDetailTradedItems));

$itemTags = array();
foreach ($DetailItemsList as $dti){
  foreach($dti['item_tags'] as $tag){
    $itemTags[] = $tag;
  }
}

$equipment = array(
  "hand_one" => NULL,
  "hand_two" => NULL,
  "armor" => NULL,
  "other_one" => NULL,
  "other_two" => NULL,
);

$bestHand1 = NULL;
$bestHand1Value = 0;

$bestHand2 = NULL;
$bestHand2Value = 0;

$bestArmor = NULL;
$bestArmorValue = 0;

$bestOther1 = NULL;
$bestOther1Value = 0;

$bestOther2 = NULL;
$bestOther2Value = 0;

foreach ($DetailItemsList as $dti){
  include 'campaign_overview_hero_data_special.php';
}
foreach ($DetailRelicsList as $dti){
  include 'campaign_overview_hero_data_special.php';
}

$extraSpeed = $extraSpeed + $AextraSpeed + $O1extraSpeed + $O2extraSpeed + $H1extraSpeed + $H2extraSpeed;
$tooltipSpeed = array_merge($tooltipSpeed, $AtooltipSpeed,$O1tooltipSpeed,$O2tooltipSpeed,$H1tooltipSpeed,$H2tooltipSpeed);
$maxSpeed = $maxSpeed + $AmaxSpeed + $O1maxSpeed + $O2maxSpeed + $H1maxSpeed + $H2maxSpeed;
$extraHealth = $extraHealth + $AextraHealth + $O1extraHealth + $O2extraHealth + $H1extraHealth + $H2extraHealth;
$attrHealth = array_merge($attrHealth, $AattrHealth,$O1attrHealth,$O2attrHealth,$H1attrHealth,$H2attrHealth);
$tooltipHealthArray = array_merge($tooltipHealthArray, $AtooltipHealthArray,$O1tooltipHealthArray,$O2tooltipHealthArray,$H1tooltipHealthArray,$H2tooltipHealthArray);
$tooltipAttrHealthArray = array_merge($tooltipAttrHealthArray, $AtooltipAttrHealthArray,$O1tooltipAttrHealthArray,$O2tooltipAttrHealthArray,$H1tooltipAttrHealthArray,$H2tooltipAttrHealthArray);
$extraStamina = $extraStamina + $AextraStamina + $O1extraStamina + $O2extraStamina + $H1extraStamina + $H2extraStamina;
$extraDefense = $AextraDefense . $O1extraDefense . $O2extraDefense . $H1extraDefense . $H2extraDefense;
$tooltipDefenseArray = array_merge($tooltipDefenseArray, $H1tooltipDefenseArray,$H2tooltipDefenseArray,$AtooltipDefenseArray,$O1tooltipDefenseArray,$O2tooltipDefenseArray);

$attackdiceH1 = $H1attackdice . $Aattackdice . $O1attackdice . $O2attackdice;
$attackdiceH2 = $H2attackdice . $Aattackdice . $O1attackdice . $O2attackdice;
$tooltipAttackArrayH1 = array_merge($tooltipAttackArrayH1, $H1tooltipAttackArray,$AtooltipAttackArray,$O1tooltipAttackArray,$O2tooltipAttackArray);
$tooltipAttackArrayH2 = array_merge($tooltipAttackArrayH2, $H2tooltipAttackArray,$AtooltipAttackArray,$O1tooltipAttackArray,$O2tooltipAttackArray);


$extraMight = $extraMight + $AextraMight + $O1extraMight + $O2extraMight + $H1extraMight + $H2extraMight;
$extraKnowledge = $extraKnowledge + $AextraKnowledge + $O1extraKnowledge + $O2extraKnowledge + $H1extraKnowledge + $H2extraKnowledge;
$extraWillpower = $extraWillpower + $AextraWillpower + $O1extraWillpower + $O2extraWillpower + $H1extraWillpower + $H2extraWillpower;
$extraAwareness = $extraAwareness + $AextraAwareness + $O1extraAwareness + $O2extraAwareness + $H1extraAwareness + $H2extraAwareness;

$equiped['H1'] = $bestHand1;
$equiped['H2'] = $bestHand2;
$equiped['A'] = $bestArmor;
$equiped['O1'] = $bestOther1;
$equiped['O2'] = $bestOther2;

    // echo '<pre>';
    // var_dump($equiped);
    // echo '</pre>';

//var_dump($equiped);


foreach($attrHealth as $atrH){
  if ($atrH['type'] == "know" && ($h['knowledge'] + $extraKnowledge) >=  $atrH['required']){
    $extraHealth += $atrH['bonus'];
    $tooltipHealthArray[] = array(
      "item" => $atrH['item'],
      "skill" => NULL,
      "amount" => $atrH['bonus'],
    );
  }
}



if(!empty($tooltipSpeedArray)){
  $tooltipSpeed = "<div class='text-center tooltip-div'>";
  foreach ($tooltipSpeedArray as $sa){
    if ($sa['item'] != NULL){
      $tooltipSpeed .= "<div>" . $sa['amount'] . " from '" . str_replace(' ', '&nbsp;', $sa['item']) . "'</div>";
    }
    if ($sa['skill'] != NULL){
      $tooltipSpeed .= "<div>" . $sa['amount'] . " from '" . $sa['skill'] . "' skill</div>";
    } 
  }
  $tooltipSpeed .= "</div>";
} else {
  $tooltipSpeed = NULL;
}

if(!empty($tooltipHealthArray)){
  $tooltipHealth = "<div class='text-center tooltip-div'>";
  foreach ($tooltipHealthArray as $ha){
    if ($ha['item'] != NULL){
      $tooltipHealth .= "<div>" . $ha['amount'] . " from '" . str_replace(' ', '&nbsp;', $ha['item']) . "'</div>";
    }
    if ($ha['skill'] != NULL){
      $tooltipHealth .= "<div>" . $ha['amount'] . " from '" . $ha['skill'] . "' skill</div>";
    } 
  }
  $tooltipHealth .= "</div>";
} else {
  $tooltipHealth = NULL;
}

if(!empty($tooltipStaminaArray)){
  $tooltipStamina = "<div class='text-center tooltip-div'>";
  foreach ($tooltipStaminaArray as $sta){
    if ($sta['item'] != NULL){
      $tooltipStamina .= "<div>" . $sta['amount'] . " from '" . str_replace(' ', '&nbsp;', $sta['item']) . "'</div>";
    }
    if ($sta['skill'] != NULL){
      $tooltipStamina .= "<div>" . $sta['amount'] . " from '" . $sta['skill'] . "' skill</div>";
    } 
  }
  $tooltipStamina .= "</div>";
} else {
  $tooltipStamina = NULL;
}

if(!empty($tooltipDefenseArray)){
  $tooltipDefense = "<div class='text-center tooltip-div'>";
  foreach ($tooltipDefenseArray as $da){
    if ($da['item'] != NULL){
      $tooltipDefense .= "<div>" . $da['amount'] . " die from '" . str_replace(' ', '&nbsp;', $da['item']) . "'</div>";
    }
    if ($da['skill'] != NULL){
      $tooltipDefense .= "<div>" . $da['amount'] . " die from '" . $da['skill'] . "' skill</div>";
    } 
  }
  $tooltipDefense .= "</div>";
} else {
  $tooltipDefense = NULL;
}

if(!empty($tooltipAttackArrayH1)){
  $tooltipAttackH1 = "<div class='text-center tooltip-div'>";
  foreach ($tooltipAttackArrayH1 as $da){
    if ($da['item'] != NULL){
      $tooltipAttackH1 .= "<div>" . $da['amount'] . " die from '" . str_replace(' ', '&nbsp;', $da['item']) . "'</div>";
    }
    if ($da['skill'] != NULL){
      $tooltipAttackH1 .= "<div>" . $da['amount'] . " die from '" . $da['skill'] . "' skill</div>";
    } 
  }
  $tooltipAttackH1 .= "</div>";
} else {
  $tooltipAttackH1 = NULL;
}

if(!empty($tooltipAttackArrayH2)){
  $tooltipAttackH2 = "<div class='text-center tooltip-div'>";
  foreach ($tooltipAttackArrayH2 as $da){
    if ($da['item'] != NULL){
      $tooltipAttackH2 .= "<div>" . $da['amount'] . " die from '" . str_replace(' ', '&nbsp;', $da['item']) . "'</div>";
    }
    if ($da['skill'] != NULL){
      $tooltipAttackH2 .= "<div>" . $da['amount'] . " die from '" . $da['skill'] . "' skill</div>";
    } 
  }
  $tooltipAttackH2 .= "</div>";
} else {
  $tooltipAttackH2 = NULL;
}


if(!empty($tooltipMightArray)){
  $tooltipMight = "<div class='text-center tooltip-div'>";
  foreach ($tooltipMightArray as $ma){
    if ($ma['item'] != NULL){
      $tooltipMight .= "<div>" . $ma['amount'] . " from '" . str_replace(' ', '&nbsp;', $ma['item']) . "'</div>";
    }
    if ($ma['skill'] != NULL){
      $tooltipMight .= "<div>" . $ma['amount'] . " from '" . $ma['skill'] . "' skill</div>";
    } 
  }
  $tooltipMight .= "</div>";
} else {
  $tooltipMight = NULL;
}

if(!empty($tooltipKnowledgeArray)){
  $tooltipKnowledge = "<div class='text-center tooltip-div'>";
  foreach ($tooltipKnowledgeArray as $ka){
    if ($ka['item'] != NULL){
      $tooltipKnowledge .= "<div>" . $ka['amount'] . " from '" . str_replace(' ', '&nbsp;', $ka['item']) . "'</div>";
    }
    if ($ka['skill'] != NULL){
      $tooltipKnowledge .= "<div>" . $ka['amount'] . " from '" . $ka['skill'] . "' skill</div>";
    } 
  }
  $tooltipKnowledge .= "</div>";
} else {
  $tooltipKnowledge = NULL;
}

if(!empty($tooltipWillpowerArray)){
  $tooltipWillpower = "<div class='text-center tooltip-div'>";
  foreach ($tooltipWillpowerArray as $wa){
    if ($wa['item'] != NULL){
      $tooltipWillpower .= "<div>" . $wa['amount'] . " from '" . str_replace(' ', '&nbsp;', $wa['item']) . "'</div>";
    }
    if ($wa['skill'] != NULL){
      $tooltipWillpower .= "<div>" . $wa['amount'] . " from '" . $wa['skill'] . "' skill</div>";
    } 
  }
  $tooltipWillpower .= "</div>";
} else {
  $tooltipWillpower = NULL;
}

if(!empty($tooltipAwarenessArray)){
  $tooltipAwareness = "<div class='text-center tooltip-div'>";
  foreach ($tooltipAwarenessArray as $aa){
    if ($aa['item'] != NULL){
      $tooltipAwareness .= "<div>" . $aa['amount'] . " from '" . str_replace(' ', '&nbsp;', $aa['item']) . "'</div>";
    }
    if ($aa['skill'] != NULL){
      $tooltipAwareness .= "<div>" . $aa['amount'] . " from '" . $aa['skill'] . "' skill</div>";
    } 
  }
  $tooltipAwareness .= "</div>";
} else {
  $tooltipAwareness = NULL;
}

?>