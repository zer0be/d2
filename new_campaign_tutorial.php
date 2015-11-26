<?php

//include 'includes/protected_page.php';


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
include 'includes/function_createProgressBar.php';
include 'includes/function_logout.php';
include 'includes/function_getSQLValueString.php';


mysql_select_db($database_dbDescent, $dbDescent);

$stepLoggedIn = 0;
$stepGroup = 0;
$stepMembers = 0;
$procent = 0;

if (isset($_SESSION['user'])){

  $stepLoggedIn = 1;
  $procent += 34;

  $query_rsGroupList = sprintf("SELECT * FROM tbgroup WHERE grp_startedby = %s", GetSQLValueString($_SESSION['user']['id'], "int"));
  $rsGroupList = mysql_query($query_rsGroupList, $dbDescent) or die(mysql_error());
  $row_rsGroupList = mysql_fetch_assoc($rsGroupList);
  $totalRows_rsGroupList = mysql_num_rows($rsGroupList);

  if ($totalRows_rsGroupList > 0){
    $stepGroup = 1;
    $procent += 33;
  }



  $query_rsPlayerList = sprintf("SELECT * FROM tbplayerlist WHERE created_by = %s", GetSQLValueString($_SESSION['user']['id'], "int"));
  $rsPlayerList = mysql_query($query_rsPlayerList, $dbDescent) or die(mysql_error());
  $row_rsPlayerList = mysql_fetch_assoc($rsPlayerList);
  $totalRows_rsPlayerList = mysql_num_rows($rsPlayerList);

  if($totalRows_rsPlayerList > 1) {
    $stepMembers = 1;
    $procent += 33;
  }

}

$editFormAction = $_SERVER['PHP_SELF'];

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "formStartCampaign")) {
  mysql_select_db($database_dbDescent, $dbDescent);
  $insertSQLUser = sprintf("UPDATE users SET new = 0 WHERE id = %s", GetSQLValueString($_SESSION['user']['id'], "int"),
                      GetSQLValueString($_GET['PID'], "int"),
                      GetSQLValueString($_GET['urlGamingID'], "int"),
                      GetSQLValueString($siv['id'], "int"));
  $ResultUser = mysql_query($insertSQLUser, $dbDescent) or die(mysql_error());
  $_SESSION['user']['new'] = 0;
  header("Location: create_campaign.php");
  die("Redirecting to: create_campaign.php");
}

?>

<html>
  <head>
    <!-- <link rel="stylesheet" type="text/css" href="content.css"> -->
    <link rel="stylesheet" type="text/css" href="content.css">
    <title>Descent 2nd Edition Campaign Tracker</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <META NAME="description" CONTENT="Descent Mobile is a quick reference campaing tracker for the table top stagety game Descent Journeys in the Dark, 2nd edition">
    <META NAME="keywords" CONTENT="Descent Journeys in the dark, Road to Legend, Sea of Blood, SoB, RtL, JitD, Descent, descent 2nd, descentinthedark.com, descentinthedark, fantasy flight games, fantasyflightgames, second edition, campaign track, campaign, table top gaming, gaming, shadow rune" />
  </head>
  <body>
    <?php 
      include 'navbar.php'; 
      include 'banner.php';
    ?>

    <div class="container grey">
      <div class="row">
        <div class="col-xs-12">
          <h1>First Time Setup</h1>
          <p class="top-lead lead text-muted">A short guide to setting up everything you need to use the website.</p>
        </div>
        <div class="col-sm-8">   
          <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true"><?php

            if ($stepLoggedIn == 0){ ?>
              <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingThree">
                  <h4 class="panel-title">
                    <span class="glyphicon glyphicon-remove text-danger" aria-hidden="true"></span> 
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                      <span class="text-danger">Account</span>
                    </a>
                  </h4>
                </div>
                <div id="collapseThree" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingThree">
                  <div class="panel-body">
                     <div class="alert alert-danger" role="alert">
                      <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                      <span class="sr-only">Error:</span>
                      You are not logged in.
                    </div>
                    <div class="col-sm-6">
                      <p>Don't have an account yet? Sign up right here and start tracking your campaigns with ease!</p>
                      <a class="btn btn-primary" href="register_tut.php" role="button">Register</a>
                    </div>
                    <div class="col-sm-6">
                      <p>Already have an account? Login here!</p>
                      <a class="btn btn-primary" href="login_tut.php" role="button">Login</a>
                    </div>
                  </div>
                </div>
              </div><?php

            } 
            else { ?>

              <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingThree">
                  <h4 class="panel-title">
                    <span class="glyphicon glyphicon-ok text-success" aria-hidden="true"></span> 
                      <span class="text-success">Account</span>
                  </h4>
                </div>
                <div id="collapseThree" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingThree">

                </div>
              </div><?php

            }

            if ($stepGroup == 0){ ?>
              <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingOne">
                  <h4 class="panel-title">
                    <span class="glyphicon glyphicon-remove text-danger" aria-hidden="true"></span> 
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                      <span class="text-danger">Groups</span>
                    </a>
                  </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse <?php if ($stepLoggedIn == 1){ echo 'in'; } ?>" role="tabpanel" aria-labelledby="headingOne">
                  <div class="panel-body">
                     <div class="alert alert-danger" role="alert">
                      <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                      <span class="sr-only">Error:</span>
                      You have not created any groups yet.
                    </div>
                    <p>Groups are collections of players that play Descent together. Playing a game of Descent with your regular board games group, or with your mates from work? Just create a group for both of those occasions.</p> 
                    <p>A user can create as many groups as he wants, and a group can start as many campaigns as they want.</p><?php
                    if ($stepLoggedIn == 1){ ?>
                      <a class="btn btn-primary" href="newgroup_tut.php" role="button">Create a group</a><?php 
                    } else { ?>
                      <span class="btn btn-default" role="button" disabled="disabled">Create a group</span><?php 
                    } ?>
                  </div>
                </div>
              </div><?php

            } 
            else { ?>

              <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingOne">
                  <h4 class="panel-title">
                    <span class="glyphicon glyphicon-ok text-success" aria-hidden="true"></span> 
                      <span class="text-success">Groups</span>
                  </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">

                </div>
              </div><?php

            }

           if ($stepMembers == 0){ ?>
            <div class="panel panel-default">
              <div class="panel-heading" role="tab" id="headingTwo">
                <h4 class="panel-title">
                  <span class="glyphicon glyphicon-remove text-danger" aria-hidden="true"></span> 
                  <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                    <span class="text-danger">Members</span>
                  </a>
                </h4>
              </div>
              <div id="collapseTwo" class="panel-collapse collapse<?php if ($stepGroup == 1){ echo 'in'; } ?>" role="tabpanel" aria-labelledby="headingTwo">
                <div class="panel-body">
                   <div class="alert alert-danger" role="alert">
                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                    <span class="sr-only">Error:</span>
                    You have not added enough members to a group yet.
                  </div>
                  <p>Group members represent the people you play your Descent campaigns with. You can add them to your groups, and assign them to the heroes in your campaign.</p>
                  <p>A group needs to have at least two members, but otherwise can have as many members as you want.</p><?php
                  if ($stepGroup == 1){ ?>
                    <a class="btn btn-primary" href="newplayers_tut.php" role="button">Add some members</a><?php 
                  } else { ?>
                    <span class="btn btn-default" role="button" disabled="disabled">Add some members</a><?php 
                  } ?>
                  
                </div>
              </div>
            </div><?php

            } 
            else { ?>

              <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingTwo">
                  <h4 class="panel-title">
                    <span class="glyphicon glyphicon-ok text-success" aria-hidden="true"></span> 
                      <span class="text-success">Members</span>
                  </h4>
                </div>
                <div id="collapseTwo" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingTwo">

                </div>
              </div><?php

            } ?>

          </div>

        </div>
        <div class="col-sm-4">
          <div class="well">
            <p class="lead">As soon as you have completed all steps, you are ready to start tracking your first campaign!</p>
            <?php createProgressBar($procent,"",0,"");
            if ($stepLoggedIn == 1 && $stepGroup == 1 && $stepMembers == 1){ ?>
              <form action="<?php echo $editFormAction; ?>" method="post" name="formStartCampaign" id="formStartCampaign">
                <input name="buttonCreateGroup" type="submit" id="buttonCreateGroup" value="Start Campaign!" class="btn btn-primary btn-lg" />
                <input type="hidden" name="MM_insert" value="formStartCampaign" />
              </form><?php 
            } else { ?>
              <span class="btn btn-default btn-lg" role="button" disabled="disabled">Start Campaign!</a><?php 
            } ?>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>