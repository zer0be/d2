<?php

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
include 'includes/protected_page.php';
include 'includes/function_logout.php';
include 'includes/function_getSQLValueString.php';

if (isset($_GET['grpID'])) {
  $grpID = $_GET['grpID'];
} else {
  header("Location: mygroups.php");
  die("Redirecting to mygroups.php");
}

mysql_select_db($database_dbDescent, $dbDescent);

$query_rsGroupList = sprintf("SELECT * FROM tbgroup WHERE grp_id = %s", GetSQLValueString($grpID, "int"));
$rsGroupList = mysql_query($query_rsGroupList, $dbDescent) or die(mysql_error());
$row_rsGroupList = mysql_fetch_assoc($rsGroupList);
$totalRows_rsGroupList = mysql_num_rows($rsGroupList);

if ($row_rsGroupList["grp_startedby"] != $_SESSION['user']['id']) {
  header("Location: mygroups.php");
  die("Redirecting to mygroups.php");
}

do {

  $members = array();
  $query_rsMemberList = sprintf("SELECT * FROM tbplayerlist WHERE player_grp_id = %s", GetSQLValueString($row_rsGroupList['grp_id'], "int"));
  $rsMemberList = mysql_query($query_rsMemberList, $dbDescent) or die(mysql_error());
  $row_rsMemberList = mysql_fetch_assoc($rsMemberList);
  $totalRows_rsMemberList = mysql_num_rows($rsMemberList);

  $names = array();
  do {
    if ($row_rsMemberList != FALSE){

      $names[] = $row_rsMemberList['player_handle'];

      $members[] = array(
        "id" => $row_rsMemberList['player_id'],
        "name" => $row_rsMemberList['player_handle'],
        "added" => $row_rsMemberList['player_timestamp'],
      );
    }

  } while ($row_rsMemberList = mysql_fetch_assoc($rsMemberList));

  $groupDetails = array(
    "id" => $row_rsGroupList['grp_id'],
    "name" => $row_rsGroupList['grp_name'],
    "state_country" => $row_rsGroupList['grp_state_country'],
    "city" => $row_rsGroupList['grp_city'],
    "members" => $members,
  );

  $groupOptions[] = '<option name="group_id" value="' . $row_rsGroupList['grp_id'] . '">' . $row_rsGroupList['grp_name'] . '</option>'; 
} while ($row_rsGroupList = mysql_fetch_assoc($rsGroupList));


$_SESSION['group_details'] = $groupDetails;
$_SESSION['group_select'] = $grpID;
$_SESSION['names'] = $names;
$_SESSION['can_delete'] = array();

?>

<html>
  <head><?php 
    $pagetitle = "Edit Groups";
    include 'head.php'; ?>
  </head>
  <body>
    <?php 
      include 'navbar.php'; 
      include 'banner.php';
    ?>

    <div class="container grey">
      <h1>Edit groups</h1>
      <p class="top-lead lead text-muted">Update your group information or add players to your group here.</p><?php 
      if (isset($_SESSION["errorcode"])){
        foreach ($_SESSION["errorcode"] as $ec){ ?>
          <div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span><?php
            echo $ec; ?>
          </div><?php 
        }
      } ?>
      <p>You can also remove players from your group that aren't used in any campaigns.</p>
      <div class="row">
        <div class="col-sm-6">
          <h2>Update group and players</h2>
          <form action="account_validate.php" method="post" name="formUpdateGroup" id="formUpdateGroup" class="form-horizontal">

            <div class="form-group">
              <label for="grp_name" class="col-sm-3 control-label">Group Name</label>
              <div class="col-sm-9">
                <input type="text" name="grp_name" value="<?php echo htmlentities($groupDetails['name'], ENT_QUOTES, 'UTF-8'); ?>" class="form-control" />
              </div>
            </div>


            <div class="form-group">
              <label for="grp_state_country" class="col-sm-3 control-label">State or Country</label>
              <div class="col-sm-9">
                <input type="text" name="grp_state_country" value="<?php echo htmlentities($groupDetails['state_country'], ENT_QUOTES, 'UTF-8'); ?>" class="form-control" />
              </div>
            </div>

            <div class="form-group">
              <label for="grp_city" class="col-sm-3 control-label">City</label>
              <div class="col-sm-9">
                <input type="text" name="grp_city" value="<?php echo htmlentities($groupDetails['city'], ENT_QUOTES, 'UTF-8'); ?>" class="form-control" />
              </div>
            </div><?php 

            $i = 1;
            foreach ($groupDetails['members'] as $gdm){ 
              $query_rsCharactersGames = sprintf("SELECT * FROM tbcharacters WHERE char_player = %s", GetSQLValueString($gdm['id'], "int"));
              $rsCharactersGames = mysql_query($query_rsCharactersGames, $dbDescent) or die(mysql_error());
              $row_rsCharactersGames = mysql_fetch_assoc($rsCharactersGames);
              $totalRows_rsCharactersGames = mysql_num_rows($rsCharactersGames);

              ?>

              <div class="form-group">
                <label for="grp_member" class="col-sm-3 control-label">Member #<?php echo $i; ?></label>
                <div class="col-sm-9">
                  <input type="text" name="grp_members[]" value="<?php echo htmlentities($gdm['name'], ENT_QUOTES, 'UTF-8'); ?>" class="form-control" /><?php
                  if ($totalRows_rsCharactersGames == 0){ ?>
                    <p class="text-muted"><small>(Leave empty to delete this player.)</small></p><?php
                  }
                  if ($totalRows_rsCharactersGames == 0){
                    $_SESSION['can_delete'][] = 1;
                  } else {
                    $_SESSION['can_delete'][] = 0;
                  } ?>
                </div>
              </div><?php

              $i++;
            }

            ?>
            <div class="form-group">
              <div class="col-sm-3"></div>
              <div class="col-sm-9"><input name="buttonUpdateGroup" type="submit" id="buttonUpdateGroup" value="Update Group" class="btn btn-primary btn-block" /></div>
            </div>

            <input type="hidden" name="MM_insert" value="formUpdateGroup" />
          </form>
        </div>

        <div class="col-sm-6">
          <h2>Add new player</h2>
          <form action="account_validate.php" method="post" name="formAddPlayer" id="formAddPlayer" class="form-horizontal">

            <div class="form-group">
              <label for="player_handle" class="col-sm-3 control-label">Player name</label>
              <div class="col-sm-9">
                <input type="text" name="player_handle" value="" class="form-control" />
              </div>
            </div>

            <div class="form-group">
              <div class="col-sm-3"></div>
              <div class="col-sm-9"><input type="submit" value="Add member" class="btn btn-primary btn-block" /></div>
            </div>

            <input type="hidden" name="created_by" value="<?php echo $_SESSION['user']['id']; ?>" />
            <input type="hidden" name="MM_insert" value="formAddPlayer" />
          </form>
        </div>
      </div>
    </div>
  </body>
</html>
