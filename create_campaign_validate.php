<?php

require_once('Connections/dbDescent.php');

// Includes
include 'includes/protected_page.php';
include 'includes/function_getSQLValueString.php';

mysql_select_db($database_dbDescent, $dbDescent);

if (!isset($_SESSION)) {
  session_start();
}

// ------------------------------------------ //
// Post the selected campaigns and expansions //
// ------------------------------------------ //
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "new-game-form")) {

	$_SESSION["errorcode"] = array();
  $_SESSION['old_post']['campaign'] = $_POST;

  // The Base Game and selected Campaign are mandatory, so we explicitly add them to the submitted list of expansions
  $expansionsArray = array();
  $selectedExpansions = '0';
  $expansionsArray[] = 0;

  if($_POST['campaign_id'] != 0){
    $selectedExpansions = $selectedExpansions . ',' . $_POST['campaign_id'];
    $expansionsArray[] = $_POST['campaign_id'];
  }
  
  if (isset($_POST['expansions'])){
  	// Remove expansions already selected as story to avoid duplicates, then convert the array to a string.
    $expansionsDiff = array_diff($_POST['expansions'], $expansionsArray);
    if (count($expansionsDiff) != 0){
      $postExpansions = implode(",", $expansionsDiff);
      $selectedExpansions = $selectedExpansions . ',' . $postExpansions;
    } 
    
  }

  // Add that data to the session.
  $_SESSION["campaigndata"] = array(
    "group_id" => $_POST['group_id'],
    "dm" => $_SESSION['user']['id'],
    "campaign_id" => $_POST['campaign_id'],
    "expansions" => $selectedExpansions,
  );

  // Redirect to the next page
	$insertGoTo = "create_campaign.php?page=Heroes";
  header(sprintf("Location: %s", $insertGoTo));
  die("Redirecting to create_campaign.php"); 
}

//------------------- //
// Post the heroes -- //
// ------------------ //

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "set-heroes-form")){

	$_SESSION["errorcode"] = array();
  $_SESSION['old_post']['heroes'] = $_POST;
  // check how many heroes there are, and save them to an array
  $listHeroes = array();

  if (isset($_POST['select_hero1']) && $_POST['select_hero1'] != 0){
    $listHeroes[] = $_POST['select_hero1'];
  }

  if (isset($_POST['select_hero2']) && $_POST['select_hero2'] != 0){
    $listHeroes[] = $_POST['select_hero2'];
  } 
  if (isset($_POST['select_hero3']) && $_POST['select_hero3'] != 0){

    $listHeroes[] = $_POST['select_hero3'];
  }
  if (isset($_POST['select_hero4']) && $_POST['select_hero4'] != 0){
    $listHeroes[] = $_POST['select_hero4'];
  } 

  // check if all heroes are unique
  // here we get a count of the unique values in an array, compared to a count of the same array with all it's values
  $Unique = 1;
  if (count(array_unique($listHeroes)) !== count($listHeroes)) {
    $_SESSION["errorcode"][] = "Duplicate Heroes";

    // Redirect to the same page
		$insertGoTo = "create_campaign.php?page=Heroes";
	  header(sprintf("Location: %s", $insertGoTo));
	  die("Redirecting to create_campaign.php"); 

  } else if (count($listHeroes) < 2){
    $_SESSION["errorcode"][] = "Total Heroes";

    // Redirect to the same page
		$insertGoTo = "create_campaign.php?page=Heroes";
	  header(sprintf("Location: %s", $insertGoTo));
	  die("Redirecting to create_campaign.php"); 

  } else {

    $_SESSION["playerdata"] = array();

    foreach ($listHeroes as $ls){
      $query_rsSetHeroes = sprintf("SELECT * FROM tbheroes WHERE hero_id = %s", GetSQLValueString($ls, "int")); 
      $rsSetHeroes = mysql_query($query_rsSetHeroes, $dbDescent) or die(mysql_error());
      $row_rsSetHeroes = mysql_fetch_assoc($rsSetHeroes);
      $totalRows_rsSetHeroes = mysql_num_rows($rsSetHeroes);

      //create an array with data of the selected heroes
      do{
        $_SESSION["playerdata"][] = array(
          "id" => $row_rsSetHeroes['hero_id'],
          "img" => $row_rsSetHeroes['hero_img'],
          "name" => $row_rsSetHeroes['hero_name'],
        );
      } while ($row_rsSetHeroes = mysql_fetch_assoc($rsSetHeroes));
    }

    // Step 1 complete
    $_SESSION["errorcode"] = array();
    
    // Redirect to the next page
		$insertGoTo = "create_campaign.php?page=Classes";
	  header(sprintf("Location: %s", $insertGoTo));
	  die("Redirecting to create_campaign.php"); 
  }
}



// ---------------------------- //
// POST the classes and players //
// ---------------------------- //
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "save-heroes-form")){

	$_SESSION["errorcode"] = array();
  $_SESSION['old_post']['classes'] = $_POST;

  // Create an array with all data so we can check if Classes & Players are unique
  $listClasses = array();
  $listPlayers = array();
  if (isset($_POST['heroId1'])){
    $listClasses[] = $_POST['class1'];
    $listPlayers[] = $_POST['player1'];
  } 
  if (isset($_POST['heroId2'])){
    $listClasses[] = $_POST['class2'];
    $listPlayers[] = $_POST['player2'];
  } 
  if (isset($_POST['heroId3'])){
    $listClasses[] = $_POST['class3'];
    $listPlayers[] = $_POST['player3'];
  } 
  if (isset($_POST['heroId4'])){
    $listClasses[] = $_POST['class4'];
    $listPlayers[] = $_POST['player4'];
  } 

  // here we get a count of the unique values in an array, compared to a count of the same array with all it's values
  $UniqueS = 1;
  if (count(array_unique($listClasses)) !== count($listClasses)) {
    $UniqueS = 0;
  }

  $playerLeft = 1;
  $playersAvailable = explode(",", $_POST['playersAvailable']);
  $diff = array_diff($playersAvailable, $listPlayers);
  if (count($diff) < 1) {
    $playerLeft = 0;
  }

  // if Classes aren't duplicate
  if($UniqueS == 1 && $playerLeft == 1){

      // add hero 1 data to an array
      $_SESSION["playerdata"][0]['class'] = $_POST['class1'];
      $_SESSION["playerdata"][0]['player'] = $_POST['player1'];
      
      // add hero 2 data to an array
      $_SESSION["playerdata"][1]['class'] = $_POST['class2'];
      $_SESSION["playerdata"][1]['player'] = $_POST['player2'];

      // add hero 3 data to an array, if there is a third hero
      if (isset($_POST['heroId3'])){
        $_SESSION["playerdata"][2]['class'] = $_POST['class3'];
        $_SESSION["playerdata"][2]['player'] = $_POST['player3'];
      }
      // add hero 4 data to an array, if there is a fourth hero
      if (isset($_POST['heroId4'])){
        $_SESSION["playerdata"][3]['class'] = $_POST['class4'];
        $_SESSION["playerdata"][3]['player'] = $_POST['player4'];
      }

      // Step 2 complete
      $_SESSION["errorcode"] = array();

      // Redirect to the next page
			$insertGoTo = "create_campaign.php?page=Overlord";
		  header(sprintf("Location: %s", $insertGoTo));
		  die("Redirecting to create_campaign.php"); 

      
  } else {
    if($UniqueS == 0){
      $_SESSION["errorcode"][] = "Duplicate Classes";
    }
    if($playerLeft == 0){
      $_SESSION["errorcode"][] = "Used Players";
    }
    // Redirect to same page
		$insertGoTo = "create_campaign.php?page=Classes";
	  header(sprintf("Location: %s", $insertGoTo));
	  die("Redirecting to create_campaign.php"); 
  }

} //end POST



// ----------------- //
// POST the Overlord //
// ------------------//

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "save-overlord-form")){

	$_SESSION["errorcode"] = array();
  $_SESSION['old_post']['overlord'] = $_POST;

  $_SESSION["playerdata"]['overlord'] = array(
    "id" => $_POST['selectoverlord'],
    "name" => "overlord",
    "class" => $_POST['classoverlord'],
    "player" => $_POST['playeroverlord'],
  );

  // Step complete
  $_SESSION["errorcode"] = array();
  $_SESSION["old_past"] = array();
  include 'create_campaign_save.php';

} //end POST