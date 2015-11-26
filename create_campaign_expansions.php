<html>
  <head><?php 
    $pagetitle = "Create Campaign";
    include 'head.php'; ?>
    <script>
      $(document).ready(function(){
        $("#checkAll").change(function () {
          $("input:checkbox").prop('checked', $(this).prop("checked"));
        });
      });
    </script>
  </head>
  <body><?php 
    include 'navbar.php'; 
    include 'banner.php'; ?>

    <div class="container grey">
      <div class="row">
        <div class="col-sm-12">
          <h1>Create a New Campaign</h1>
          <p class="top-lead lead text-muted">Begin your newest Descent adventure here!</p>
          <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#" >Campaign</a></li>
            <li role="presentation"><a href="#"><span class="text-muted">Heroes</span></a></li>
            <li role="presentation"><a href="#"><span class="text-muted">Classes and Players</span></a></li>
            <li role="presentation"><a href="#"><span class="text-muted">Overlord and Plot Deck</span></a></li>
          </ul>
        </div>
        <div class="col-sm-12"><p>&nbsp;</p></div>
      </div>
      <div class="row">
        <div class="col-sm-7">

          <div class="row">
            <div class="col-sm-12">

              <form action="create_campaign_validate.php" method="post" name="start-quest-form" id="new-game-form" class="form-horizontal">

                <div class="form-group">
                  <label for="group-id" class="col-sm-2 control-label">Group</label>
                  <div class="col-sm-10">
                    <select name="group_id" class="form-control" id="group-id"><?php 
                      foreach($groupOptions as $go){
                        echo $go;
                      } ?>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label for="campaign-id" class="col-sm-2 control-label">Story</label>
                  <div class="col-sm-10">
                    <select name="campaign_id" class="form-control" id="campaign-id"><?php 
                      foreach($selectOptions as $so) {
                        echo $so;
                      } ?>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label for="expansion-id" class="col-sm-2 control-label">Expansions</label>
                  <div class="col-sm-10" id="expansion-id">
                    <div class="well"><?php 
                      foreach($checkboxOptions as $co) {
                        echo $co;
                      } ?>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label for="submit-button" class="col-sm-2 control-label"></label>
                  <div class="col-sm-10" id="expansion-id">
                    <input type="submit" class="btn btn-primary btn-block" value="Continue to Hero Selection" />
                  </div>
                </div>        
                <input type="hidden" name="MM_insert" value="new-game-form" />

              </form>

            </div>
          </div>

        </div>
        <div class="col-sm-5">
          <div class="row">
            <div class="col-sm-12">
              <p class="lead">Use the story dropdown to select the campaign you will be using the quest guide from and select all expansions you will be using content from.</p>
              <p>The selected expansions determine which heroes, items, travel cards,... will be available in the tracker, be sure to select all the nescessary ones for you new campaign.</p>
              <p>Selecting every Lieutenant Pack you are using is optional as they add no other elements <strong>(except for the Serena and Raythen pack)</strong> than a plot deck, of which you can select only one anyway. </p>
            </div>
          </div>
        </div>
      </div>
    <div>
  </body>
</html>
