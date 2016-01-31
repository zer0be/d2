<?php

if (!isset($_SESSION)) {
  session_start();
}

// check if the user needs to be logged in to see this.
include 'includes/protected_page.php';

?>

<html>
  <head><?php 
    $pagetitle = "Create Group";
    include 'head.php'; ?>
  </head>
  <body>
    <?php 
    include 'navbar.php';
    include 'banner.php'; 
    ?>
    
    <div class="container grey">
      <h1>Create Group</h1>
      <p class="top-lead lead text-muted">Create a new group to play campaigns with.</p><?php
        foreach($_SESSION["errorcode"] as $ec){ ?>
          <div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> <?php
            echo $ec; ?>
          </div><?php 
        } ?>
      <div class="row">
        <div class="col-sm-6">
          <form action="account_validate.php" method="post" name="formCreateGroup" id="formCreateGroup" class="form-horizontal">

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
          <p>Playing a game of Descent with your regular board games group, or with your colleagues from work? Just create a group for both of those occasions.</p> 
          <p>A user can create as many groups as he wants, and a group can start as many campaigns as they want.</p>
        </div>
      </div>
    </div>
  </body>
</html>
