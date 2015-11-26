<html>
  <head><?php 
    $pagetitle = "Page not Found";
    include 'head.php'; ?>
  </head>
  <body>
    <?php 
      include 'navbar.php'; 
      include 'banner.php';
    ?>

    <div class="container grey">
      <div class="row no-gutters">
        <div class="col-sm-8 confirm-deco-text">
        	<div class="row no-gutters">
        		<div class="col-sm-1">
        		</div>
        		<div class="col-sm-10">
    		      <h1>404: Journeys in the Page not Found</h1>
    		      <p class="lead">It seems the page you are trying the find does not exist, or can't be accessed.</p><?php

              if (isset($_GET['info'])){
                echo '<p><strong>Additional information:</strong></p>';
                if ($_GET['info'] == "campaignid"){
                  echo '<p>The campaign identifier used did not match any existing campaigns.</p>';
                }
              } ?> 
    	      </div>
    	    </div>
        </div>
        <div class="col-sm-3 confirm-deco">
        	<img src="img/GoblinWitcher.png" />
        </div>
      </div>
    </div>
  </body>
</html>
