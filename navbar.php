<?php

?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-65082470-1', 'auto');
  ga('send', 'pageview');

</script>

<nav class="navbar navbar-default navbar-fixed-top">
<!-- <nav class="navbar navbar-inverse navbar-fixed-top"> -->
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="index.php"><img alt="Descent Campaign Tracker" title="Home" src="logo.png"></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Campaigns <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu"> 
            <li><a href="mycampaigns.php">My Campaigns</a></li>
            <li><a href="create_campaign.php">New Campaign</a></li>
            <li><a href="mycampaigns.php?view=all">All Campaigns</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Groups <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="mygroups.php">My Groups</a></li>
            <li><a href="account_newgroup.php">New Group</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">View Stats & Info<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="stats_quests.php">Quest Stats</a></li>
            <li><a href="stats_heroes_classes.php">Hero & Classes stats</a></li>
            <li><a href="stats_attributes.php">Hero Info</a></li>
            <li><a href="info_classes.php">Classes Info</a></li>
            <li><a href="stats_items.php">Item Stats</a></li>
            <li><a href="stats_search.php">Search Stats</a></li>
            <li><a href="stats_monsters.php">Monster Stats</a></li>
            <li><a href="stats_travel.php">Travel Stats</a></li>
          </ul>
        </li>
      </ul><?php
      
      if (!isset($_SESSION['user'])){ ?>    
        <ul class="nav navbar-nav navbar-right">
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Welcome anonymous <span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
              <li><a href="login.php">Login</a></li>
              <li><a href="account_register.php">Register</a></li>
            </ul>
          </li>
        </ul><?php 
      } 
      else if (isset($_SESSION['user'])){ ?>
        <ul class="nav navbar-nav navbar-right">
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Logged in as <strong><?php echo htmlentities($_SESSION['user']['username'], ENT_QUOTES, 'UTF-8'); ?></strong> <span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
              <li><a href="account_edit.php">Edit account</a></li>
              <li><a href="logout.php">Logout</a></li>
            </ul>
          </li>
        </ul><?php 
      } ?>
      

    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>