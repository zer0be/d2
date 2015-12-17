<?php

// loop through heroes
$acquiredSkills = array();
foreach ($players as $sh){
	// Select aquired skills
	$query_rsAqSkills = sprintf("SELECT * FROM tbskills_aquired INNER JOIN tbcharacters ON tbskills_aquired.spendxp_char_id = tbcharacters.char_id INNER JOIN tbskills ON tbskills_aquired.spendxp_skill_id = tbskills.skill_id WHERE spendxp_game_id = %s AND spendxp_char_id = %s AND spendxp_sold = %s", 
											GetSQLValueString($gameID, "int"), 
											GetSQLValueString($sh['id'], "int"),
											GetSQLValueString(0, "int"));
	$rsAqSkills = mysql_query($query_rsAqSkills, $dbDescent) or die(mysql_error());
	$row_rsAqSkills = mysql_fetch_assoc($rsAqSkills);
	$totalRows_rsAqSkills = mysql_num_rows($rsAqSkills);

	$acquiredSkills[$sh['id']] = array();
	do { 
		$acquiredSkills[$sh['id']][] = $row_rsAqSkills['spendxp_skill_id'];

		// this is used to return overlord cards (Ritual of Shadows)
		if ($row_rsAqSkills['skill_cost'] > 0 && $row_rsAqSkills['skill_plot'] != 1){
			$acquiredOptions[$sh['id']][] = '<div class="col-sm-4"><div class="checkbox"><label><input type="checkbox" name="' . $sh['id'] . '[]" value="' . $row_rsAqSkills['skill_id'] . '"><div>' . $row_rsAqSkills['skill_name'] . '<br /><span class="search-gold">' . $row_rsAqSkills['skill_cost'] . 'XP</span></label></div></div></div>';
		}
	} while ($row_rsAqSkills = mysql_fetch_assoc($rsAqSkills));

} //close foreach


// Available Skills for each Hero
$availableSkills = array();
foreach ($players as $sh){
	$query_rsAllSkills = sprintf("SELECT * FROM tbskills WHERE skill_class = %s AND skill_type != 'Overlord'", GetSQLValueString($sh['class'], "text"));
	$rsAllSkills = mysql_query($query_rsAllSkills, $dbDescent) or die(mysql_error());
	$row_rsAllSkills = mysql_fetch_assoc($rsAllSkills);
	$totalRows_rsAllSkills = mysql_num_rows($rsAllSkills);

		$availableSkills[$sh['id']] = array();
	do { 
		if ($row_rsAllSkills['skill_cost'] <= $sh['xp'] && (!(in_array($row_rsAllSkills['skill_id'], $acquiredSkills[$sh['id']])))){
			$availableSkills[$sh['id']][] = '<div class="col-sm-3"><div class="checkbox"><label><input type="checkbox" name="' . $sh['id'] . '[]" value="' . $row_rsAllSkills['skill_id'] . '"><div>' . $row_rsAllSkills['skill_name'] . '<br /><span class="search-gold">' . $row_rsAllSkills['skill_cost'] . 'XP</span></label></div></div></div>';
		}
	} while ($row_rsAllSkills = mysql_fetch_assoc($rsAllSkills));

} //close foreach


// Available Overlord Cards
$availableOverlordCards = array();
$availablePlotCards = array();

foreach ($players as $sh){
	// Skill Cards Overlord
	if ($sh['archetype'] == 'Overlord'){

		// Get all the cards
		$query_rsAllOverlordCards = sprintf("SELECT * FROM tbskills WHERE skill_expansion IN ($selExpansions) AND skill_plot = %s AND skill_type = 'Overlord' ORDER BY skill_class ASC, skill_cost ASC, skill_name ASC", GetSQLValueString(0, "int"));
		$rsAllOverlordCards = mysql_query($query_rsAllOverlordCards, $dbDescent) or die(mysql_error());
		$row_rsAllOverlordCards = mysql_fetch_assoc($rsAllOverlordCards);
		$totalRows_rsAllOverlordCards = mysql_num_rows($rsAllOverlordCards);

		$allCards = array();
		
		//Create an array for those
		do { 
			$allCards[] = array(
				"skill_id" => $row_rsAllOverlordCards['skill_id'],
				"skill_name" => $row_rsAllOverlordCards['skill_name'],
				"skill_class" => $row_rsAllOverlordCards['skill_class'],
				"skill_cost" => intval($row_rsAllOverlordCards['skill_cost']),
			);
		} while ($row_rsAllOverlordCards = mysql_fetch_assoc($rsAllOverlordCards));

		$aquiredCards = array();

		// Go through all aquired skills and create a new array that tracks from what decks the cards were bought and their cost 
		// (this to check if, for example, the Overlord bought 2 Warlord I cards, before he is able to buy a Warlord II card)
		foreach ($acquiredSkills[$sh['id']] as $acqs){
			$query_rsAcquiredOverlordCards = sprintf("SELECT * FROM tbskills WHERE skill_id = %s AND skill_plot = %s AND skill_type = 'Overlord'", GetSQLValueString($acqs, "int"), GetSQLValueString(0, "int"));
			$rsAcquiredOverlordCards = mysql_query($query_rsAcquiredOverlordCards, $dbDescent) or die(mysql_error());
			$row_rsAcquiredOverlordCards = mysql_fetch_assoc($rsAcquiredOverlordCards);
			$totalRows_rsAcquiredOverlordCards = mysql_num_rows($rsAcquiredOverlordCards);

			do { 
				if($row_rsAcquiredOverlordCards['skill_class'] != NULL){
					$aquiredCards[] = $row_rsAcquiredOverlordCards['skill_class'];
				}
			} while ($row_rsAcquiredOverlordCards = mysql_fetch_assoc($rsAcquiredOverlordCards));

		}

		$aquiredCardsCounted = array_count_values($aquiredCards);

		$availableOverlordCards[$sh['id']] = array();
		$alcstring = "";

		foreach($allCards as $alc) { 
		 	if ($alc['skill_cost'] <= $sh['xp'] && (!(in_array($alc['skill_id'], $acquiredSkills[$sh['id']])))){

		 		if($alc['skill_cost'] > 1){
		 			$alcstring = $alc['skill_class'];	
		 		}
		 		if($alc['skill_cost'] == 1 || $alc['skill_class'] == "Universal" || (isset($aquiredCardsCounted[$alcstring]) && $aquiredCardsCounted[$alcstring] >= 2)){
		 			$availableOverlordCards[$sh['id']][] = '<div class="col-sm-3"><div class="checkbox"><label><input type="checkbox" name="' . $sh['id'] . '[]" value="' . $alc['skill_id'] . '"><div>' . $alc['skill_name'] . '<br /><span class="search-gold">' . $alc['skill_cost'] . 'XP - <span class="text-muted">' . $alc['skill_class'] . '</span></span></label></div></div></div>';
				} 	
		 	}
		}

		$query_rsAllPlot = sprintf("SELECT * FROM tbskills WHERE skill_class = %s AND skill_plot = %s AND skill_type = 'Overlord'", GetSQLValueString($sh['class'], "text"),GetSQLValueString(1, "int"));
		$rsAllPlot = mysql_query($query_rsAllPlot, $dbDescent) or die(mysql_error());
		$row_rsAllPlot = mysql_fetch_assoc($rsAllPlot);
		$totalRows_rsAllPlot = mysql_num_rows($rsAllPlot);

		$availablePlot[$sh['id']] = array();
		do { 

			if ($row_rsAllPlot['skill_cost'] <= $campaign['threat'] && (!(in_array($row_rsAllPlot['skill_id'], $acquiredSkills[$sh['id']])))){
				//$availableSkills[$sh['id']][] = $row_rsAllSkills['skill_id'];
				$availablePlot[$sh['id']][] = '<div class="col-sm-3"><div class="checkbox"><label><input type="checkbox" name="' . $sh['id'] . '[]" value="' . $row_rsAllPlot['skill_id'] . '"><div>' . $row_rsAllPlot['skill_name'] . '<br /><span class="search-gold">' . $row_rsAllPlot['skill_cost'] . ' Tokens</span></label></div></div></div>';
			}
		} while ($row_rsAllPlot = mysql_fetch_assoc($rsAllPlot));
	
	} // close if overlord

} //close foreach

?>