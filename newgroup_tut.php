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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "formCreateGroup")) {
  $insertSQL = sprintf("INSERT INTO tbgroup (grp_name, grp_creation, grp_startedby, grp_state_country, grp_city) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['grp_name'], "text"),
                       GetSQLValueString($_POST['grp_creation'], "date"),
                       GetSQLValueString($_POST['grp_startedby'], "text"),
                       GetSQLValueString($_POST['grp_state_country'], "text"),
                       GetSQLValueString($_POST['grp_city'], "text"));

  mysql_select_db($database_dbDescent, $dbDescent);
  $Result1 = mysql_query($insertSQL, $dbDescent) or die(mysql_error());

  header("Location: new_campaign_tutorial.php");
  die("Redirecting to new_campaign_tutorial.php"); 
}



?>

<html>
  <head><?php 
    $pagetitle = "Create Group";
    include 'head.php'; ?>
  </head>
  <body>
    <?php include 'navbar.php'; ?>

    <a href='index.php'>
      <div id="header"></div>
    </a>
    <div class="container grey">
      <h1>Create Group</h1>
      <p class="top-lead lead text-muted">Create a new group to play campaigns with.</p>
      <div class="row">
        <div class="col-sm-6">
          <form action="<?php echo $editFormAction; ?>" method="post" name="formCreateGroup" id="formCreateGroup" class="form-horizontal">

            <div class="form-group">
              <label for="grp_name" class="col-sm-3 control-label">Group Name</label>
              <div class="col-sm-9">
                <input type="text" name="grp_name" value="" class="form-control" />
              </div>
            </div>

            <div class="form-group">
              <label for="grp_state_country" class="col-sm-3 control-label">State or Country</label>
              <div class="col-sm-9">
                <input type="text" name="grp_state_country" value="" class="form-control" />
              </div>
            </div>

            <div class="form-group">
              <label for="grp_city" class="col-sm-3 control-label">City</label>
              <div class="col-sm-9">
                <input type="text" name="grp_city" value="" class="form-control" />
              </div>
            </div>

            <div class="form-group">
              <div class="col-sm-3"></div>
              <div class="col-sm-9"><input name="buttonCreateGroup" type="submit" id="buttonCreateGroup" value="Create Group" class="btn btn-primary btn-block" /></div>
            </div>

            <input type="hidden" name="grp_creation" />
            <input type="hidden" name="grp_startedby" value="<?php echo $_SESSION['user']['id']; ?>" />
            <input type="hidden" name="MM_insert" value="formCreateGroup" />
          </form>
        </div>

        <div class="col-sm-6">
          <p class="lead">A group is a collection of people that play Descent together.</p> 
          <p>Playing a game of Descent with your regular board games group, or with your mates from work? Just create a group for both of those occasions.</p> 
          <p>A user can create as many groups as he wants, and a group can start as many campaigns as they want.</p>
        </div>
      </div>
    </div>
  </body>
</html>
