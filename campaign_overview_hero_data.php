<?php

$DetailItemsList = array();

$extraSpeed = 0;
$tooltipSpeed = array();
$maxSpeed = 0;
$extraHealth = 0;
$tooltipHealthArray = array();
$extraStamina = 0;
$extraDefense = "";

$extraMight = 0;
$extraKnowledge = 0;
$extraWillpower = 0;
$extraAwareness = 0;

// Get the items
$query_rsDetailItems = sprintf("SELECT * FROM tbitems_aquired INNER JOIN tbitems ON aq_item_id = item_id WHERE aq_char_id = %s AND aq_item_sold = %s AND aq_trade_char_id is null", GetSQLValueString($heroID, "int"), GetSQLValueString(0, "int"));
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
    "default_price" => $row_rsDetailItems['item_default_price'],
    "override_price" => $row_rsDetailItems['aq_item_price_ovrd'],
    "item_type" => $row_rsDetailItems['item_type'],
    "item_special" => $row_rsDetailItems['item_special'],
    "item_img" => $row_rsDetailItems['market_img'],
    "tooltip" => $tooltipItems,
  );

} while ($row_rsDetailItems = mysql_fetch_assoc($rsDetailItems));


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


foreach ($DetailItemsList as $dti){
  if($dti['item_special'] != NULL){
    $itemsSpecial = explode(';', $dti['item_special']);

    $ip = 0;
    foreach ($itemsSpecial as $isp){
      $itemsSpecial[$ip] = explode(',', $isp);

      switch ($itemsSpecial[$ip][0]) {
        case "speed":
          $extraSpeed += intval($itemsSpecial[$ip][1]);
          $tooltipSpeedArray[] = array(
            "item" => $dti['item_name'],
            "skill" => NULL,
            "amount" => intval($itemsSpecial[$ip][1]),
          );
          break;
        case "limitspeed":
          $maxSpeed = intval($itemsSpecial[$ip][1]);
          break;
        case "health":
          $extraHealth += intval($itemsSpecial[$ip][1]);
          $tooltipHealthArray[] = array(
            "item" => $dti['item_name'],
            "skill" => NULL,
            "amount" => intval($itemsSpecial[$ip][1]),
          );
          break;
        case "stamina":
          $extraStamina += intval($itemsSpecial[$ip][1]);
          $tooltipStaminaArray[] = array(
            "item" => $dti['item_name'],
            "skill" => NULL,
            "amount" => intval($itemsSpecial[$ip][1]),
          );
          break;
        case "defense":
          $extraDefense = $extraDefense . $itemsSpecial[$ip][1];
          if ($itemsSpecial[$ip][1] == "B"){
            $die = "Brown";
          } else if ($itemsSpecial[$ip][1] == "G"){
            $die = "Grey";
          } else if ($itemsSpecial[$ip][1] == "BL"){
            $die = "Black";
          }
          $tooltipDefenseArray[] = array(
            "item" => $dti['item_name'],
            "skill" => NULL,
            "amount" => $die,
          );
          break;

        case "might":
          $extraMight += intval($itemsSpecial[$ip][1]);
          $tooltipMightArray[] = array(
            "item" => $dti['item_name'],
            "skill" => NULL,
            "amount" => intval($itemsSpecial[$ip][1]),
          );
          break;
        case "knowledge":
          $extraKnowledge += intval($itemsSpecial[$ip][1]);
          $tooltipKnowledgeArray[] = array(
            "item" => $dti['item_name'],
            "skill" => NULL,
            "amount" => intval($itemsSpecial[$ip][1]),
          );
          break;
        case "willpower":
          $extraWillpower += intval($itemsSpecial[$ip][1]);
          $tooltipWillpowerArray[] = array(
            "item" => $dti['item_name'],
            "skill" => NULL,
            "amount" => intval($itemsSpecial[$ip][1]),
          );
          break;
        case "awareness":
          $extraAwareness += intval($itemsSpecial[$ip][1]);
          $tooltipAwarenessArray[] = array(
            "item" => $dti['item_name'],
            "skill" => NULL,
            "amount" => intval($itemsSpecial[$ip][1]),
          );
          break;
      }

      $ip++;
    }

    //var_dump($skillsSpecial);

  }
}

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
  );
} while ($row_rsDetailRelics = mysql_fetch_assoc($rsDetailRelics));

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
          $extraDefense = $extraDefense . $skillsSpecial[$sp][1];
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

if(!empty($tooltipSpeedArray)){
  $tooltipSpeed = "<div class='text-center tooltip-div'>";
  foreach ($tooltipSpeedArray as $sa){
    if ($sa['item'] != NULL){
      $tooltipSpeed .= "<div>+" . $sa['amount'] . " from '" . $sa['item'] . "' item</div>";
    }
    if ($sa['skill'] != NULL){
      $tooltipSpeed .= "<div>+" . $sa['amount'] . " from '" . $sa['skill'] . "' skill</div>";
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
      $tooltipHealth .= "<div>+" . $ha['amount'] . " from '" . $ha['item'] . "' item</div>";
    }
    if ($ha['skill'] != NULL){
      $tooltipHealth .= "<div>+" . $ha['amount'] . " from '" . $ha['skill'] . "' skill</div>";
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
      $tooltipStamina .= "<div>+" . $sta['amount'] . " from '" . $sta['item'] . "' item</div>";
    }
    if ($sta['skill'] != NULL){
      $tooltipStamina .= "<div>+" . $sta['amount'] . " from '" . $sta['skill'] . "' skill</div>";
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
      $tooltipDefense .= "<div>+" . $da['amount'] . " die from '" . $da['item'] . "' item</div>";
    }
    if ($da['skill'] != NULL){
      $tooltipDefense .= "<div>+" . $da['amount'] . " die from '" . $da['skill'] . "' skill</div>";
    } 
  }
  $tooltipDefense .= "</div>";
} else {
  $tooltipDefense = NULL;
}


if(!empty($tooltipMightArray)){
  $tooltipMight = "<div class='text-center tooltip-div'>";
  foreach ($tooltipMightArray as $ma){
    if ($ma['item'] != NULL){
      $tooltipMight .= "<div>+" . $ma['amount'] . " from '" . $ma['item'] . "' item</div>";
    }
    if ($ma['skill'] != NULL){
      $tooltipMight .= "<div>+" . $ma['amount'] . " from '" . $ma['skill'] . "' skill</div>";
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
      $tooltipKnowledge .= "<div>+" . $ka['amount'] . " from '" . $ka['item'] . "' item</div>";
    }
    if ($ka['skill'] != NULL){
      $tooltipKnowledge .= "<div>+" . $ka['amount'] . " from '" . $ka['skill'] . "' skill</div>";
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
      $tooltipWillpower .= "<div>+" . $wa['amount'] . " from '" . $wa['item'] . "' item</div>";
    }
    if ($wa['skill'] != NULL){
      $tooltipWillpower .= "<div>+" . $wa['amount'] . " from '" . $wa['skill'] . "' skill</div>";
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
      $tooltipAwareness .= "<div>+" . $aa['amount'] . " from '" . $aa['item'] . "' item</div>";
    }
    if ($aa['skill'] != NULL){
      $tooltipAwareness .= "<div>+" . $aa['amount'] . " from '" . $aa['skill'] . "' skill</div>";
    } 
  }
  $tooltipAwareness .= "</div>";
} else {
  $tooltipAwareness = NULL;
}

?>