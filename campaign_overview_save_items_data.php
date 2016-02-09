<?php 

if (!isset($_GET['data'])){
	$_SESSION["shopItems"] = array();
	$_SESSION["tempSold"] = array();
	//$_SESSION["errorcode"] = array();
}

$bought = array();
$sold = array();
$traded = array();
$selectionPrice = 0;

$aquiredItems = array();
$aquiredItemsDetails = array();
$aquiredItemsList = array();

if(isset($_GET['data']) && $_GET['data'] == "y"){
	// add the items already in the session
	foreach ($_SESSION["shopItems"] as $ses){
		
		if ($ses['action'] == "buy"){
			$aquiredItems[] = $ses['id'];
			if ($ses['override'] == NULL){
				$selectionPrice += $ses['price'];
			} else {
				$selectionPrice += $ses['override'];
			}
			
		}

		if ($ses['action'] == "sell"){
			$sold[] = $ses['id'];
			$selectionPrice -= $ses['price'];
		}

		if ($ses['action'] == "trade"){
			$traded[] = $ses['shop_id'];
			//make traded items not sellable
			$sold[] = $ses['id'];
		}
		
	}

}

// echo '<pre>';
// var_dump($_SESSION["shopItems"]);
// //var_dump($_SESSION["tempItems"]);
// // var_dump($traded);
// echo '</pre>';
// 

// Get the gold!
$query_rsTotalGold = sprintf("SELECT game_gold FROM tbgames WHERE game_id = %s", GetSQLValueString($gameID, "int"));
$rsTotalGold = mysql_query($query_rsTotalGold, $dbDescent) or die(mysql_error());
$row_rsTotalGold = mysql_fetch_assoc($rsTotalGold);

$availableGold = $row_rsTotalGold['game_gold'];

$minicampaigns = array(1,3,5);
// if Mini campaign show all items
if (in_array($selCampaign,$minicampaigns)){
	// Select all items
	$query_rsAllItems = sprintf("SELECT * FROM tbitems WHERE item_exp_id IN ($selExpansions) AND owner = %s AND item_act != %s ORDER BY item_name", GetSQLValueString("hero", "text"), GetSQLValueString("Start", "text"));
	$rsAllItems = mysql_query($query_rsAllItems, $dbDescent) or die(mysql_error());
	$row_rsAllItems = mysql_fetch_assoc($rsAllItems);
} else {
	// Select all items
	$query_rsAllItems = sprintf("SELECT * FROM tbitems WHERE item_exp_id IN ($selExpansions) AND owner = %s AND item_act = %s ORDER BY item_name", GetSQLValueString("hero", "text"), GetSQLValueString($currentActItems, "text"));
	$rsAllItems = mysql_query($query_rsAllItems, $dbDescent) or die(mysql_error());
	$row_rsAllItems = mysql_fetch_assoc($rsAllItems);
}


// Select aquired items
$query_rsAqItems = sprintf("SELECT * FROM tbitems_aquired INNER JOIN tbitems ON aq_item_id = item_id INNER JOIN tbcharacters ON aq_char_id = char_id INNER JOIN tbheroes ON char_hero = hero_id WHERE aq_game_id = %s AND aq_trade_char_id is null ORDER BY item_name", GetSQLValueString($gameID, "int"));
$rsAqItems = mysql_query($query_rsAqItems, $dbDescent) or die(mysql_error());
$row_rsAqItems = mysql_fetch_assoc($rsAqItems);

do { 
	// if the item hasn't been sold before or has not been sold in the current window
	if($row_rsAqItems['aq_item_sold'] == 0  && (!(in_array($row_rsAqItems['aq_item_id'], $sold)))){
		// then add it to the list of items the heroes have, and create a select option for it
		$aquiredItems[] = $row_rsAqItems['aq_item_id'];
		$_SESSION['verify_values']['items_sellable'][] = $row_rsAqItems['aq_item_id'];
		$aquiredItemsDetails[$row_rsAqItems['aq_item_id']] = array(	
			"char_id" => $row_rsAqItems['aq_char_id'],
			"shop_id" => $row_rsAqItems['shop_id'],
			);
		$aquiredItemsList[] = '<option value="' . $row_rsAqItems['aq_item_id'] . '">' . $row_rsAqItems['item_name'] . ' - ' . $row_rsAqItems['item_sell_price'] . ' Gold - ' . $row_rsAqItems['hero_name'] . '</option>';
		// If the item is not a starting item, make a select option for the trade dropdown
		if ($row_rsAqItems['item_act'] != "Start"){
			if (!(in_array($row_rsAqItems['shop_id'], $traded))){
				$aquiredItemsTradeList[] = '<option value="' . $row_rsAqItems['shop_id'] . '">' . $row_rsAqItems['item_name'] . ' - ' . $row_rsAqItems['hero_name'] . '</option>';
				$_SESSION['verify_values']['items_tradable'][] = $row_rsAqItems['shop_id'];
			}
		}
	}
} while ($row_rsAqItems = mysql_fetch_assoc($rsAqItems));


// Select aquired relics
$query_rsAqRelics = sprintf("SELECT * FROM tbitems_aquired INNER JOIN tbitems_relics ON aq_relic_id = relic_id INNER JOIN tbcharacters ON aq_char_id = char_id INNER JOIN tbheroes ON char_hero = hero_id WHERE aq_game_id = %s AND aq_trade_char_id is null ORDER BY relic_h_name", GetSQLValueString($gameID, "int"));
$rsAqRelics = mysql_query($query_rsAqRelics, $dbDescent) or die(mysql_error());
$row_rsAqRelics = mysql_fetch_assoc($rsAqRelics);

do{
	if (isset($row_rsAqRelics['shop_id']) && $row_rsAqRelics['aq_char_id'] != $overlordID && $row_rsAqRelics['aq_item_sold'] == 0){
		$aquiredItemsTradeList[] = '<option value="' . $row_rsAqRelics['shop_id'] . '">' . $row_rsAqRelics['relic_h_name'] . ' - ' . $row_rsAqRelics['hero_name'] . '</option>';
		$_SESSION['verify_values']['items_tradable'][] = $row_rsAqRelics['shop_id'];
	}
} while ($row_rsAqRelics = mysql_fetch_assoc($rsAqRelics)); 

// create array with select options for the items available for purchase
$availableItems = array();
do {
	if(!(in_array($row_rsAllItems['item_id'], $aquiredItems))){
		$availableItems[] = '<option value="' . $row_rsAllItems['item_id'] . '">' . $row_rsAllItems['item_name'] . ' - ' . $row_rsAllItems['item_default_price'] . ' Gold</option>';
		$_SESSION['verify_values']['items_available'][] = $row_rsAllItems['item_id'];
	}
} while ($row_rsAllItems = mysql_fetch_assoc($rsAllItems));