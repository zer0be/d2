<?php

// Select the travel events
$query_rsTravelData = sprintf("SELECT * FROM tbtravel WHERE travel_exp_id IN ($selExpansions)");
$rsTravelData = mysql_query($query_rsTravelData, $dbDescent) or die(mysql_error());
$row_rsTravelData = mysql_fetch_assoc($rsTravelData);
$totalRows_rsTravelData = mysql_num_rows($rsTravelData);

// create array with select options
// 
$allTravel = array();

do {
	//echo $row_rsTravelData['travel_name'] . $row_rsTravelData['travel_type'] . '<br />';
	$allTravel[] = array(
			"id" => $row_rsTravelData['travel_id'],
			"type" => $row_rsTravelData['travel_type'],
			"card" =>  $row_rsTravelData['travel_card'],
			"option" => '<option name="travel" value="' . $row_rsTravelData['travel_id'] . '">' . $row_rsTravelData['travel_name'] . '</option>',
			"result" => $row_rsTravelData['travel_result'],
			"special" => $row_rsTravelData['travel_special'],
	);

} while ($row_rsTravelData = mysql_fetch_assoc($rsTravelData));

if (!isset($_GET['data'])){
	$_SESSION['travelevents'] = array();
	$_SESSION['lastspecial'] = NULL;
	$_SESSION['lastspecialtype'] = NULL;
	$_SESSION['addedstep'] = NULL;
}

$_SESSION['alltravel'] = $allTravel;

$usedCards = array();
foreach($_SESSION['travelevents'] as $tsc){
  if ($tsc['card'] != 0){
    $usedCards[] = $tsc['card'];
  }
}

// echo '<pre>';
// var_dump($_SESSION['travelevents']);
// echo '</pre>';

?>