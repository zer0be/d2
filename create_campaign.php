<?php

require_once('Connections/dbDescent.php');

// if the session hasn't been started, start the session
if (!isset($_SESSION)) {
  session_start();
}

// if the user is new, redirect him to the tutorial
if (isset($_SESSION['user']) && $_SESSION['user']['new'] == 1){
  header("Location: new_campaign_tutorial.php"); // FIX ME: Rename the tutorial file to create_campaign_tutorial.php   
  die("Redirecting to new_campaign_tutorial.php"); 
}

// If the current step isn't set or MM insert isn't set, reset all session variables

if (!isset($_GET["page"])){
  $_SESSION["campaigndata"] = array();
  $_SESSION["playerdata"] = array();
  $_SESSION["errorcode"] = array();
  $_SESSION["old_post"] = array();
}

// Includes
include 'includes/protected_page.php';
include 'includes/function_getSQLValueString.php';

mysql_select_db($database_dbDescent, $dbDescent);



// Select the campaigns from the database and give them a custom order
$query_rsCampaigns = "SELECT * FROM tbcampaign ORDER BY CASE WHEN cam_type = 'full' THEN '1' WHEN cam_type = 'mini' THEN '2' WHEN cam_type = 'act-one' THEN '3' WHEN cam_type = 'monster' THEN '4' ELSE cam_type END, cam_id ASC";
$rsCampaigns = mysql_query($query_rsCampaigns, $dbDescent) or die(mysql_error());
$row_rsCampaigns = mysql_fetch_assoc($rsCampaigns);
$totalRows_rsCampaigns = mysql_num_rows($rsCampaigns);

// Create variables for the Story dropdown and expansion select boxes.
$selectOptions = array();
$checkboxOptions = array();

// Filter Full Campaigns or Mini Campaigns from the Expansions so they can be selected as Campaign, add them to a select option variable
$currentType = "";
$checkboxOptions[] = '<div class="checkbox"><label><input type="checkbox" id="checkAll"/>Select All</label></div>';
do {
  if($row_rsCampaigns['cam_type'] == "full" || $row_rsCampaigns['cam_type'] == "mini" || $row_rsCampaigns['cam_type'] == "book" || $row_rsCampaigns['cam_type'] == "act-one"){
    $selectOptions[] = '<option value="' . $row_rsCampaigns['cam_id'] . '">' . $row_rsCampaigns['cam_name'] . '</option>';
  }

  if ($row_rsCampaigns['cam_type'] != $currentType){
    $currentType = $row_rsCampaigns['cam_type'];
    switch ($row_rsCampaigns['cam_type']){
      case "full":
        $checkboxOptions[] = '<div class="expansion-type"><strong>Large Box Expansion</strong></div>';
      break;
      case "mini":
        $checkboxOptions[] = '<div class="expansion-type"><strong class="expansion-type">Small Box Expansion</strong></div>';
      break;
      // case "act-one":
      //   $checkboxOptions[] = '<div class="expansion-type"><strong class="expansion-type">One Act Campaigns</strong></div>';
      // break;
      case "lieutenant":
        $checkboxOptions[] = '<div class="expansion-type"><strong class="expansion-type">Lieutenant Pack</strong></div>';
      break;
      case "monster":
        $checkboxOptions[] = '<div class="expansion-type"><strong class="expansion-type">Hero and Monster Collection</strong></div>';
      break;
      case "book":
        // $checkboxOptions[] = '<div class="expansion-type"><strong class="expansion-type">Other</strong></div>';
      break;
      case "other":
        $checkboxOptions[] = '<div class="expansion-type"><strong class="expansion-type">Other</strong></div>';
      break;
    }
  }

  if($row_rsCampaigns['cam_id'] != 0 && $row_rsCampaigns['cam_type'] != "book"){
    $checkboxOptions[] = '<div class="checkbox"><label><input type="checkbox" name="expansions[]" value="' . $row_rsCampaigns['cam_id'] . '" /> ' . $row_rsCampaigns['cam_name'] . '</label></div>';
  }

} while ($row_rsCampaigns = mysql_fetch_assoc($rsCampaigns));



// Get the groups belonging to the logged in user from the database.
$query_rsGroups = sprintf("SELECT * FROM tbgroup WHERE grp_startedby = %s ORDER BY grp_name ASC ", GetSQLValueString($_SESSION['user']['id'], "int"));
$rsGroups = mysql_query($query_rsGroups, $dbDescent) or die(mysql_error());
$row_rsGroups = mysql_fetch_assoc($rsGroups);
$totalRows_rsGroups = mysql_num_rows($rsGroups);

$groupOptions = array();

if($row_rsGroups == FALSE){ 
  // if there are no groups, redirect them to the groups page. 
  header("Location: mygroups.php"); 
  die("Redirecting to mygroups.php"); 
}

// Add groups of this user to select option variable 
do {
  $query_rsPlayerList = sprintf("SELECT * FROM tbplayerlist WHERE player_grp_id = %s", GetSQLValueString($row_rsGroups['grp_id'], "int"));
  $rsPlayerList = mysql_query($query_rsPlayerList, $dbDescent) or die(mysql_error());
  $row_rsPlayerList = mysql_fetch_assoc($rsPlayerList);
  $totalRows_rsPlayerList = mysql_num_rows($rsPlayerList);

  if ($totalRows_rsPlayerList > 1){
    $groupOptions[] = '<option value="' . $row_rsGroups['grp_id'] . '"> ' . $row_rsGroups['grp_name'] . '<br />';
  }

} while ($row_rsGroups = mysql_fetch_assoc($rsGroups));


// ------------------ //
// OUTPUT OF THE PAGE //
// ------------------ //

// echo '<pre>';
// var_dump($_SESSION["campaigndata"]);
// echo '</pre>';
// echo '<pre>';
// var_dump($_SESSION["playerdata"]);
// echo '</pre>';
// echo '<pre>';
// var_dump($_SESSION["old_post"]);
// echo '</pre>';

if (!isset($_GET["page"])) { // normal page or detail page?

  include 'create_campaign_expansions.php';

} else {

	if ($_GET['page'] == "Heroes" || $_GET['page'] == "Classes" || $_GET['page'] == "Overlord"){
	  include 'create_campaign_heroes.php';
	} else {
		include 'create_campaign_expansions.php';
	}

}