<?php

// Skills
$query_rsSkillsUndo = sprintf("SELECT * FROM tbskills_aquired INNER JOIN tbskills ON spendxp_skill_id = skill_id WHERE spendxp_progress_id = %s",
                  GetSQLValueString($pID, "int"));
$rsSkillsUndo = mysql_query($query_rsSkillsUndo, $dbDescent) or die(mysql_error());
$row_rsSkillsUndo = mysql_fetch_assoc($rsSkillsUndo);
$totalRows_rsSkillsUndo = mysql_num_rows($rsSkillsUndo);

$unspendxp = array();
$unspendthreat = array();

$skillsOptions = array();
$plotOptions = array();


do{

  if ($totalRows_rsSkillsUndo != 0){
    if ($row_rsSkillsUndo['skill_plot'] == 1){
      $plotOptions[] = '<div><div class="checkbox"><label><input type="checkbox" name="undo_plot[]" value="' . $row_rsSkillsUndo['spendxp_id'] . '"><div>' . $row_rsSkillsUndo['skill_name'] . ' was bought for ' . $row_rsSkillsUndo['skill_cost'] . ' Threat</span></label></div></div></div>';
    } else if ($row_rsSkillsUndo['skill_plot'] == 97 || $row_rsSkillsUndo['skill_plot'] == 4){

    } else {
      $skillsOptions[] = '<div><div class="checkbox"><label><input type="checkbox" name="undo_skills[]" value="' . $row_rsSkillsUndo['spendxp_id'] . '"><div>' . $row_rsSkillsUndo['skill_name'] . ' was bought for ' . $row_rsSkillsUndo['skill_cost'] . 'XP</label></div></div></div>';
    }
  }

} while ($row_rsSkillsUndo = mysql_fetch_assoc($rsSkillsUndo));



// Items
$query_rsItemsUndo = sprintf("SELECT * FROM tbitems_aquired 
                  INNER JOIN tbitems ON aq_item_id = item_id
                  INNER JOIN tbcharacters ON aq_char_id = char_id
                  INNER JOIN tbheroes ON char_hero = hero_id
                  WHERE aq_progress_id = %s OR aq_trade_progress_id = %s OR aq_sold_progress_id = %s ",
                  GetSQLValueString($pID, "int"),
                  GetSQLValueString($pID, "int"),
                  GetSQLValueString($pID, "int"));
$rsItemsUndo = mysql_query($query_rsItemsUndo, $dbDescent) or die(mysql_error());
$row_rsItemsUndo = mysql_fetch_assoc($rsItemsUndo);
$totalRows_rsItemsUndo = mysql_num_rows($rsItemsUndo);

$boughtOptions = array();
$soldOptions = array();
$tradeOptions = array();

do{

  if ($totalRows_rsItemsUndo != 0){

    //echo $row_rsItemsUndo['shop_id'] . ' - ' . $row_rsItemsUndo['item_name'] . '<br />';
    if ($row_rsItemsUndo['aq_item_gottraded'] == 1 && $row_rsItemsUndo['aq_trade_char_id'] == NULL){
      //$tradeOptions[] = '<div><div class="checkbox"><label><input type="checkbox" name="undo_trade[]" value="' . $row_rsItemsUndo['shop_id'] . '"><div>' . $row_rsItemsUndo['item_name'] . ' was traded from ' . $row_rsItemsUndo['aq_char_id'] . ' to ' . $row_rsItemsUndo['aq_trade_char_id'] . '.</label></div></div></div>';
    } else if ($row_rsItemsUndo['aq_item_gottraded'] == 1 && $row_rsItemsUndo['aq_trade_char_id'] != NULL){
      $tradeOptions[] = '<div><div class="checkbox"><label><input type="checkbox" name="undo_trade[]" value="' . $row_rsItemsUndo['shop_id'] . '"><div>' . $row_rsItemsUndo['item_name'] . ' was traded by ' . $row_rsItemsUndo['hero_name'] . '.</label></div></div></div>';
    } else if ($row_rsItemsUndo['aq_trade_progress_id'] != NULL){
      $tradeOptions[] = '<div><div class="checkbox"><label><input type="checkbox" name="undo_trade[]" value="' . $row_rsItemsUndo['shop_id'] . '"><div>' . $row_rsItemsUndo['item_name'] . ' was traded by ' . $row_rsItemsUndo['hero_name'] . '.</label></div></div></div>';
      if ($row_rsItemsUndo['aq_progress_id'] == $row_rsItemsUndo['aq_trade_progress_id']){
        $boughtOptions[] = '<div><div class="checkbox"><label><input type="checkbox" name="undo_bought[]" value="' . $row_rsItemsUndo['shop_id'] . '"><div>' . $row_rsItemsUndo['item_name'] . ' was bought for ' . $row_rsItemsUndo['item_default_price'] . ' gold by ' . $row_rsItemsUndo['hero_name'] . '.</label></div></div></div>';
      }
    } 
    else if ($row_rsItemsUndo['aq_sold_progress_id'] != NULL){
      $soldOptions[] = '<div><div class="checkbox"><label><input type="checkbox" name="undo_sold[]" value="' . $row_rsItemsUndo['shop_id'] . '"><div>' . $row_rsItemsUndo['item_name'] . ' was sold for ' . $row_rsItemsUndo['item_sell_price'] . ' gold by ' . $row_rsItemsUndo['hero_name'] . '.</label></div></div></div>';
    } 
    else {
      $boughtOptions[] = '<div><div class="checkbox"><label><input type="checkbox" name="undo_bought[]" value="' . $row_rsItemsUndo['shop_id'] . '"><div>' . $row_rsItemsUndo['item_name'] . ' was bought for ' . $row_rsItemsUndo['item_default_price'] . ' gold by ' . $row_rsItemsUndo['hero_name'] . '.</label></div></div></div>';
    }
  }
  
} while ($row_rsItemsUndo = mysql_fetch_assoc($rsItemsUndo));

?>