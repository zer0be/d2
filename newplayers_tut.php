<?php

include 'includes/protected_page.php';


//-----------------------//
//remove me after include//
//-----------------------//

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

$editFormAction = $_SERVER['PHP_SELF'];

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "formAddPlayer")) {
  $insertSQL = sprintf("INSERT INTO tbplayerlist (player_handle, player_grp_id, created_by) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['player_handle'], "text"),
                       GetSQLValueString($_POST['group_select'], "int"),
                       GetSQLValueString($_POST['created_by'], "int"));

  mysql_select_db($database_dbDescent, $dbDescent);
  $Result1 = mysql_query($insertSQL, $dbDescent) or die(mysql_error());
  //header("Location: index.php");
}

$query_rsGroupList = sprintf("SELECT * FROM tbgroup WHERE grp_startedby = %s", GetSQLValueString($_SESSION['user']['id'], "int"));
$rsGroupList = mysql_query($query_rsGroupList, $dbDescent) or die(mysql_error());
$row_rsGroupList = mysql_fetch_assoc($rsGroupList);
$totalRows_rsGroupList = mysql_num_rows($rsGroupList);

$query_rsPlayerList = sprintf("SELECT * FROM tbplayerlist WHERE created_by = %s", GetSQLValueString($_SESSION['user']['id'], "int"));
$rsPlayerList = mysql_query($query_rsPlayerList, $dbDescent) or die(mysql_error());
$row_rsPlayerList = mysql_fetch_assoc($rsPlayerList);
$totalRows_rsPlayerList = mysql_num_rows($rsPlayerList);

do {
  $groupOptions[] = '<option name="group_id" value="' . $row_rsGroupList['grp_id'] . '">' . $row_rsGroupList['grp_name'] . '</option>'; 
} while ($row_rsGroupList = mysql_fetch_assoc($rsGroupList));


?>

<html>
  <head><?php 
    $pagetitle = "New Player";
    include 'head.php'; ?>
  </head>
  <body>
    <?php 
      include 'navbar.php';
      include 'banner.php'; 
    ?>

    <div class="container grey">
      <h1>Add members</h1>
      <p class="top-lead lead text-muted">Create members and add them to your group.</p>
      <div class="row">
        <div class="col-sm-6">
          <form action="<?php echo $editFormAction; ?>" method="post" name="formAddPlayer" id="formAddPlayer" class="form-horizontal">

            <div class="form-group">
              <label for="group-id" class="col-sm-3 control-label">Group Name</label>
              <div class="col-sm-9">
                <select name="group_select" class="form-control"><?php
                  foreach($groupOptions as $go){
                    echo $go;
                  }?>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label for="player_handle" class="col-sm-3 control-label">Member name</label>
              <div class="col-sm-9">
                <input type="text" name="player_handle" value="" class="form-control" />
              </div>
            </div>

            <div class="form-group">
              <div class="col-sm-3"></div>
              <div class="col-sm-9">
                <input type="submit" value="Add member" class="btn btn-primary btn-block" /><?php
                  if ($totalRows_rsPlayerList > 1){ ?>
                    <a class="btn btn-success btn-block" href="new_campaign_tutorial.php" role="button">Done</a><?php 
                  } else { ?>
                    <span class="btn btn-default btn-block" role="button" disabled="disabled">Done</span><?php 
                  } ?>

              </div>

            </div>

            <input type="hidden" name="player_id" value="" />
            <input type="hidden" name="player_timestamp" value="" />
            <input type="hidden" name="created_by" value="<?php echo $_SESSION['user']['id']; ?>" />
            <input type="hidden" name="MM_insert" value="formAddPlayer" />
          </form>

        </div>

        <div class="col-sm-6">
          <p class="lead">Group members represent the people you play your Descent campaigns with.</p>
          <p>You can add them to your groups, and assign them to the heroes in your campaign.</p>
          <p>A group needs to have at least two members, but otherwise can have as many members as you want.</p>
        </div>

      </div>
    </div>
  </body>
</html>
