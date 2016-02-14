<?php

$currentPage = $_SERVER["PHP_SELF"];

// FIX ME: Check if these db queries shouldn't be rewritten to something more simple
mysql_select_db($database_dbDescent, $dbDescent);
// Select all campaigns
$query_rsCampaignList = "SELECT cam_id, cam_name, expansion, cam_logo, cam_icon FROM tbcampaign WHERE cam_type = 'full' OR cam_type = 'mini' ORDER BY cam_type ASC";
$rsCampaignList = mysql_query($query_rsCampaignList, $dbDescent) or die(mysql_error());
$row_rsCampaignList = mysql_fetch_assoc($rsCampaignList);
$totalRows_rsCampaignList = mysql_num_rows($rsCampaignList);

// Select all progress/games
$query_rsSelectGroup = sprintf("SELECT * FROM tbquests_progress INNER JOIN tbgames ON progress_game_id = game_id INNER JOIN tbgroup ON game_grp_id = grp_id INNER JOIN users ON grp_startedby = id ORDER BY progress_timestamp DESC");
$rsSelectGroup = mysql_query($query_rsSelectGroup, $dbDescent) or die(mysql_error());
$row_rsSelectGroup = mysql_fetch_assoc($rsSelectGroup);
$totalRows_rsSelectGroup = mysql_num_rows($rsSelectGroup);

$gamingGroups = array();
$uniqueGroups = array();

do {

  $query_rsSelectGroupGames = sprintf("SELECT * FROM tbgames WHERE game_grp_id = %s", GetSQLValueString($row_rsSelectGroup['grp_id'], "int"));
  $rsSelectGroupGames = mysql_query($query_rsSelectGroupGames, $dbDescent) or die(mysql_error());
  $row_rsSelectGroupGames = mysql_fetch_assoc($rsSelectGroupGames);
  $totalRows_rsSelectGroupGames = mysql_num_rows($rsSelectGroupGames);

  $countGames = 0;
  
  do{
    $countGames += 1;
  } while ($row_rsSelectGroupGames = mysql_fetch_assoc($rsSelectGroupGames));

  
  if (!in_array($row_rsSelectGroup['grp_id'], $uniqueGroups)){
    $uniqueGroups[] = $row_rsSelectGroup['grp_id'];

    $gamingGroups[] = array(
      "grp_id" => $row_rsSelectGroup['grp_id'],
      "grp_name" => $row_rsSelectGroup['grp_name'],
      "grp_city" => $row_rsSelectGroup['grp_city'],
      "grp_state_country" => $row_rsSelectGroup['grp_state_country'],
      "dm" => $row_rsSelectGroup['username'],
      "special" => $row_rsSelectGroup['special'],
      "timestamp" => $row_rsSelectGroup['progress_timestamp'],
      "campaigns" => $countGames,
    );
  }
  
} while ($row_rsSelectGroup = mysql_fetch_assoc($rsSelectGroup));

// Select all games
$query_rsGamesStats = sprintf("SELECT * FROM tbgames");
$rsGamesStats = mysql_query($query_rsGamesStats, $dbDescent) or die(mysql_error());
$row_rsGamesStats = mysql_fetch_assoc($rsGamesStats);
$totalRows_rsGamesStats = mysql_num_rows($rsGamesStats);

// Select all characters/heroes
$query_rsCharStats = sprintf("SELECT * FROM tbcharacters INNER JOIN tbheroes ON char_hero = hero_id INNER JOIN tbplayerlist ON tbcharacters.char_player = tbplayerlist.player_id");
$rsCharStats = mysql_query($query_rsCharStats, $dbDescent) or die(mysql_error());
$row_rsCharStats = mysql_fetch_assoc($rsCharStats);
$totalRows_rsCharStats = mysql_num_rows($rsCharStats);

$OverlordTotal = 0;
$HeroesTotal = 0;

// Count how many heroes and overlords there are
do{
  if($row_rsCharStats['hero_type'] == "Overlord"){
    $OverlordTotal += 1;
  } else {
    $HeroesTotal += 1;
  }
} while ($row_rsCharStats = mysql_fetch_assoc($rsCharStats));

$OverlordQuests = 0;
$HeroesQuests = 0;
$undecidedQuests = 0;

// Select all questprogress
$query_rsQuestStats = sprintf("SELECT * FROM tbquests_progress");
$rsQuestStats = mysql_query($query_rsQuestStats, $dbDescent) or die(mysql_error());
$row_rsQuestStats = mysql_fetch_assoc($rsQuestStats);
$totalRows_rsQuestStats = mysql_num_rows($rsQuestStats);

// Count the quests that are won by Heroes/Overlord/Undecided
do{
  if($row_rsQuestStats['progress_quest_winner'] == "Overlord Wins"){
    $OverlordQuests += 1;
  } else if($row_rsQuestStats['progress_quest_winner'] == "Heroes Win"){
    $HeroesQuests += 1;
  } else {
    $undecidedQuests += 1;
  }
} while ($row_rsQuestStats = mysql_fetch_assoc($rsQuestStats));

// Calculate the percentage for progressbar
$OverlordQuestsPerc = ($OverlordQuests /($totalRows_rsQuestStats - $undecidedQuests)) * 100;
$HeroesQuestsPerc = ($HeroesQuests / ($totalRows_rsQuestStats - $undecidedQuests)) * 100;

// if the user is still flagged as new or there is no user logged in then show the 'Get Started' block
if ((isset($_SESSION['user']) && $_SESSION['user']['new'] == 1) || !isset($_SESSION['user'])) { ?>
  <div class="jumbotron">
    <h1>Descent Campaign Tracker</h1>
    <p>Welcome to the Unofficial Campaign Tracker for the boardgame Descent: Journeys in the Dark - 2nd Edition. Here you can record your progress through it's various campaigns, as well as view stats about the game based on the campaigns that have been played.</p>
    <p><a href="new_campaign_tutorial.php" class="btn btn-success btn-lg">Get started</a></p>
  </div><?php
} ?>

<div class="row">
    <div class="col-md-12">&nbsp;</div>
</div>
<div class="row">
    <?php do { 

      // Show a list of images that link to a page with details about every campaign
      $short = $row_rsCampaignList['cam_name'];
      $short = strtolower($short);
      $short = str_replace(" ","_",$short);
      $short = preg_replace("/[^A-Za-z0-9_]/","",$short);
      ?>
      <div class="col-md-2 col-sm-4 col-xs-4">
        <a class="thumbnail" href="campaign_page.php?campaign=<?php echo $row_rsCampaignList['cam_id']; ?>"><img src="img/campaigns/logos/<?php echo $short; ?>.jpg" /></a>
      </div>
    <?php } while ($row_rsCampaignList = mysql_fetch_assoc($rsCampaignList)); ?>
</div>

<div class="row">
  <div class="col-md-4">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h2 class="panel-title">Stats</h2>
      </div>
      <div class="panel-body"><?php 
          // Show a small stats block
          echo '<p><strong>' . count($uniqueGroups) . '</strong> gaming groups, playing <strong>' . $totalRows_rsGamesStats . '</strong> games.<br />'; 
          echo '<strong>' . $HeroesTotal . '</strong> Heroes vs. <strong>' . $OverlordTotal . '</strong> Overlords.<br />';
          echo '<strong>' . ($totalRows_rsQuestStats - $undecidedQuests) . '</strong> quests have been played, ' . $undecidedQuests . ' are in progress.</p>';
          createProgressBar($HeroesQuestsPerc, "Won (Heroes)", $OverlordQuestsPerc, "Won (Overlord)"); ?>
      </div>
      <div class="panel-footer">
        <a href="stats_quests.php" class="btn btn-primary ">View More</a>
      </div>
    </div>

    <div class="panel panel-default">
      <div class="panel-heading">
        <h2 class="panel-title">Changelog</h2>
      </div>
      <div class="panel-body">  
        <small>
          <strong>February 13th, 2016</strong>
          <ul>
            <li>Added Mists of Bilehall!</li>
          </ul>
          <strong>February 11th, 2016</strong>
          <ul>
            <li>Major Update, full changelog coming soon!</li>
          </ul>
          <strong>December 17th, 2015</strong>
          <ul>
            <li>Hero overview page stats now correctly shows a speed that is lower than the max speed when it is forced by an item, also a small text was added to indicate this is still work in progress</li>
            <li>Missing special objective added for SoN - The Incident</li>
            <li>Missing special objective added for SoN - Respected Citizen</li>
            <li>Missing special objective added for SoN - Prison of Khinn</li>
            <li>Fixed incorrect follow-up quests to HoB - Prison of Khinn</li>
          </ul>
        </small>
      </div>
      <div class="panel-footer">
        <a href="changelog.php" class="btn btn-primary ">View More</a>
      </div>
    </div>     
  </div>
  <div class="col-md-8">

    <div class="panel panel-default">
      <div class="panel-heading">
        <h2 class="panel-title">Recently Updated Groups</h2>
      </div>
      <div class="panel-body">
        <div class="row hidden-xs">
          <div class="col-sm-3">
            <strong>Group Name</strong>
          </div>
          <div class="col-sm-2">
            <strong>User</strong>
          </div>
          <div class="col-sm-3">
            <strong>Location</strong>
          </div>
          <div class="col-sm-2 text-center">
            <strong>Games</strong>
          </div>
          <div class="col-sm-2">
            <strong>Updated</strong>
          </div>
        </div><?php 
        $xg = 1;
        foreach ($gamingGroups as $gg) { 
          // Show the x most recently updated groups
          if ($xg <= 15) { ?>
            <div class="row campaign-row">
              <div class="col-sm-3">
                <span class="visible-xs-inline"><strong>Group Name: </strong></span><a href="mycampaigns.php?urlGgrp=<?php echo $gg['grp_id']; ?>&view=group"><strong><?php echo htmlentities($gg['grp_name'], ENT_QUOTES, 'UTF-8'); ?></strong></a><br />
              </div>
              <div class="col-sm-2">
                <span class="visible-xs-inline"><strong>User: </strong></span><?php echo htmlentities($gg['dm'], ENT_QUOTES, 'UTF-8'); ?> 
              </div>
              <div class="col-sm-3">
                <span class="visible-xs-inline"><strong>Location: </strong></span><?php 
                if (isset($gg['grp_city'])){
                  echo ucfirst(htmlentities($gg['grp_city'], ENT_QUOTES, 'UTF-8'));
                } else {
                  echo "<i>Somewhere</i>";
                }
                echo ", ";
                if (isset($gg['grp_state_country'])){
                  echo ucfirst(htmlentities($gg['grp_state_country'], ENT_QUOTES, 'UTF-8'));
                } else {
                  echo "<i>Someplace</i>";
                } ?>
              </div>
              <div class="col-sm-2 text-center hidden-xs">
                <?php echo $gg['campaigns']; ?>
              </div>
              <div class="col-sm-2 visible-xs-inline">
                <strong>Games: </strong><?php echo $gg['campaigns']; ?>
              </div>

              <div class="col-sm-2">
                <span class="visible-xs-inline"><strong>Updated: </strong></span><?php 
                $grpTimestamp = strtotime($gg['timestamp']); 
                $grpDate = date('d-m-Y', $grpTimestamp);
                $grpTime = date('Gi.s', $grpTimestamp);
                echo $grpDate; ?>
              </div>
            </div><?php
          }
          $xg++;
        } ?>

      </div>
      <div class="panel-footer">
        <a href="mycampaigns.php" class="btn btn-primary ">View My Groups</a>&nbsp;<a href="mycampaigns.php?view=all" class="btn btn-primary ">View All Groups</a>
      </div>
    </div>
    
  </div>
</div>
<div class="row">
  <div class="col-sm-12 text-center">
    <small class="text-muted">
      <p>The d2etracker is an unofficial campaign tracker for Descent: Journeys in the Dark - Second Edition created by <a href="https://community.fantasyflightgames.com/user/239371-atom4gevampire/">Atom4geVampire.</a><br />
      This is a fan project and not affiliated with Fantasy Flight Games in any way. Images are property of Fantasy Flight Games.</p>
      <p>I would like to thank the following people for their help during development of this site: <br />
      Tundrra, any2Cards, ND Jones, Indalecio, progger, Chav, odintsaq, odgregg, SlimShady0208 and everyone at the FFG forums.</p>
    </small>
  </div>
</div>