<?php

//include the db
require_once('Connections/dbDescent.php'); 

//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

//include functions
include 'includes/function_logout.php';
include 'includes/function_getSQLValueString.php';


mysql_select_db($database_dbDescent, $dbDescent);

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "formCreateGroup")) {

  $_SESSION['errorcode'] = array();

  if (!isset($_SESSION['validate']['token']) || $_SESSION['validate']['token'] != $_POST['token']){
    $_SESSION["errorcode"][] = "The submitted security token did not match, please try again.";
    $noError = 0; 
  }

  $query_rsAllgroups = sprintf("SELECT grp_name FROM tbgroup");
  $rsAllgroups = mysql_query($query_rsAllgroups, $dbDescent) or die(mysql_error());
  $row_rsAllgroups = mysql_fetch_assoc($rsAllgroups);
  $totalRows_rsAllgroups = mysql_num_rows($rsAllgroups);

  $groupNames = array();
  do{

    $groupNames[] = strtolower($row_rsAllgroups['grp_name']);
  } while ($row_rsAllgroups = mysql_fetch_assoc($rsAllgroups));


  if(in_array(strtolower($_POST['grp_name']), $groupNames)){

    $_SESSION['errorcode'][] = "This group name already exists. Please choose a different name for your group.";

    header("Location: account_newgroup.php");
    die("Redirecting to account_newgroup.php"); 

  } else {
    $insertSQL = sprintf("INSERT INTO tbgroup (grp_name, grp_creation, grp_startedby, grp_state_country, grp_city) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['grp_name'], "text"),
                       GetSQLValueString($_POST['grp_creation'], "date"),
                       GetSQLValueString($_POST['grp_startedby'], "text"),
                       GetSQLValueString($_POST['grp_state_country'], "text"),
                       GetSQLValueString($_POST['grp_city'], "text"));

    mysql_select_db($database_dbDescent, $dbDescent);
    $Result1 = mysql_query($insertSQL, $dbDescent) or die(mysql_error());

    header("Location: mygroups.php");
    die("Redirecting to mygroups.php"); 
  }

}





if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "formUpdateGroup")) {
  $_SESSION["errorcode"] = array();

  // echo '<pre>';
  // var_dump($_POST['grp_members']);
  // echo '</pre>';

  // echo '<pre>';
  // var_dump($_SESSION['can_delete']);
  // echo '</pre>';


  if(!empty($_POST['grp_name'])){
    $grpName = $_POST['grp_name'];
  } else {
    $grpName = $_SESSION['group_details']['name'];
  }

  if(!empty($_POST['grp_state_country'])){
    $grpStateCountry = $_POST['grp_state_country'];
  } else {
    $grpStateCountry = $_SESSION['state_country'];
  }

  if(!empty($_POST['grp_city'])){
    $grpCity = $_POST['grp_city'];
  } else {
    $grpCity = $_SESSION['group_details']['city'];
  }

  $insertSQL = sprintf("UPDATE tbgroup SET grp_name = %s, grp_state_country = %s, grp_city = %s WHERE grp_id = %s", 
                       GetSQLValueString($grpName, "text"),
                       GetSQLValueString($grpStateCountry, "text"),
                       GetSQLValueString($grpCity, "text"),
                       GetSQLValueString($_SESSION['group_select'], "int"));

  mysql_select_db($database_dbDescent, $dbDescent);
  $Result1 = mysql_query($insertSQL, $dbDescent) or die(mysql_error());

  if(count(array_unique($_POST['grp_members']))<count($_POST['grp_members'])) {
    $_SESSION["errorcode"][] = " One or more player names were duplicates.";
    $insertGoTo = "mygroups_edit.php?grpID=" . $_SESSION["group_select"];
    header(sprintf("Location: %s", $insertGoTo));
    die("Redirecting to mygroups_edit.php"); 
  } else {
      // Array does not have duplicates
  }

  $x = 0;
  foreach ($_POST['grp_members'] as $m){
    if ($m != ""){
      $insertSQL2 = sprintf("UPDATE tbplayerlist SET player_handle = %s WHERE player_id = %s AND player_grp_id = %s", 
                       GetSQLValueString($m, "text"),
                       GetSQLValueString($_SESSION['group_details']['members'][$x]['id'], "int"),
                       GetSQLValueString($_SESSION['group_select'], "int"));

      mysql_select_db($database_dbDescent, $dbDescent);
      $Result2 = mysql_query($insertSQL2, $dbDescent) or die(mysql_error());
    } else {

      if ($_SESSION['can_delete'][$x] == 1){

        $insertSQLdel = sprintf("DELETE FROM tbplayerlist WHERE player_id = %s AND player_grp_id = %s", 
                         GetSQLValueString($_SESSION['group_details']['members'][$x]['id'], "int"),
                         GetSQLValueString($_SESSION['group_select'], "int"));

        mysql_select_db($database_dbDescent, $dbDescent);
        $Resultdel = mysql_query($insertSQLdel, $dbDescent) or die(mysql_error());

      } else {
        $_SESSION["errorcode"][] = " A player name was left blank, but the player can't be deleted because he/she is used in a campaign.";
      }
      
    }
    $x++;
  }

  $insertGoTo = "mygroups_edit.php?grpID=" . $_SESSION["group_select"];
  header(sprintf("Location: %s", $insertGoTo));
  die("Redirecting to mygroups_edit.php"); 
}






if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "formAddPlayer")) {
  $_SESSION["errorcode"] = array();

  if (!in_array($_POST['player_handle'], $_SESSION['names'])){
    $insertSQL = sprintf("INSERT INTO tbplayerlist (player_handle, player_grp_id, created_by) VALUES (%s, %s, %s)",
                         GetSQLValueString($_POST['player_handle'], "text"),
                         GetSQLValueString($_SESSION['group_select'], "int"),
                         GetSQLValueString($_SESSION['user']['id'], "int"));

    mysql_select_db($database_dbDescent, $dbDescent);
    $Result1 = mysql_query($insertSQL, $dbDescent) or die(mysql_error());

  } else {
    $_SESSION["errorcode"][] = " This name already exists in this group.";
  }

  $insertGoTo = "mygroups_edit.php?grpID=" . $_SESSION["group_select"];
  header(sprintf("Location: %s", $insertGoTo));
  die("Redirecting to mygroups_edit.php"); 
}

?>