<?php 

$test = array();
foreach ($statsArray as $key => $row) {
  $test[$key]  = $row['count']; 
}
array_multisort($test, SORT_DESC, $statsArray);

// FIX ME: Temp array, load from db
$campaignstemp = array(
  0 => "The Shadow Rune",
  // 1 => "Lair of the Wyrm",
  2 => "Labyrinth of Ruin",
  // 3 => "The Trollfens",
  4 => "Shadow of Nerekhall",
  // 5 => "Manor of Ravens",
  29 => "Heirs of Blood",
  30 => "Mists of Bilehall",
);

?>
<div class="row">&nbsp;</div><?php

foreach ($campaignstemp as $key => $ctemp){ ?>

  <div class="panel panel-default">
    <div class="panel-heading">
      <h2 class="panel-title"><?php echo $ctemp; ?> - Balance</h2>
    </div>
    <div class="panel-body"><?php

      $totalHeroWins = 0;
      $totalOLWins = 0;
      $totalHeroWinsFinale = 0;
      $totalOLWinsFinale = 0;
      foreach ($statsArray as $sa){
        if($sa['expansion_id'] == $key){
          $totalHeroWins += $sa['hero_wins'];
          $totalOLWins += $sa['overlord_wins'];

          if($sa['act'] == "Finale"){
            $totalHeroWinsFinale += $sa['hero_wins'];
            $totalOLWinsFinale += $sa['overlord_wins'];

            if (($sa['hero_wins'] + $sa['overlord_wins']) > 0){
              $QuestWins = calcQuestWins($sa['overlord_wins'], $sa['hero_wins']);?>

              <div class="row stats-row">
                <div class="col-xs-12">

                  <div class="row">
                    <div class="col-md-6">
                      <p><strong><?php echo $sa['quest_name']; ?></strong></p>
                    </div>
                    <div class="col-md-6 text-right"> 
                      <?php getCampaignLabel($sa['quest_campaign'], "normal"); ?>
                    </div>
                  </div>
          
                  <div class="row">
                    <div class="col-md-6">         
                      <p class="text-left text-muted"><small><?php echo $QuestWins['HeroText']; ?></small></p>
                    </div>
                    <div class="col-md-6">         
                      <p class="text-right text-muted"><small><?php echo $QuestWins['OverlordText']; ?></small></p>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <?php createProgressBar($QuestWins['HeroPerc'], "Won (Heroes)", $QuestWins['OverlordPerc'], "Won (Overlord)"); ?>         
                    </div>
                  </div>

                </div>
              </div><?php
            }
          }


        }
      } 

      $QuestWins = calcQuestWins($totalOLWinsFinale, $totalHeroWinsFinale); ?>
      <div class="row stats-row">
        <div class="col-xs-12">

          <div class="row">
            <div class="col-md-6">
              <p><strong>Overall Campaign Victories</strong></p>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">         
              <p class="text-left text-muted"><small><?php echo $QuestWins['HeroText']; ?></small></p>
            </div>
            <div class="col-md-6">         
              <p class="text-right text-muted"><small><?php echo $QuestWins['OverlordText']; ?></small></p>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <?php createProgressBar($QuestWins['HeroPerc'], "Won (Heroes)", $QuestWins['OverlordPerc'], "Won (Overlord)"); ?>         
            </div>
          </div>

        </div>
      </div><?php
      $QuestWins = calcQuestWins($totalOLWins, $totalHeroWins); ?>
      <div class="row stats-row">
        <div class="col-xs-12">

          <div class="row">
            <div class="col-md-6">
              <p><strong>Overall Quest Victories</strong></p>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">         
              <p class="text-left text-muted"><small><?php echo $QuestWins['HeroText']; ?></small></p>
            </div>
            <div class="col-md-6">         
              <p class="text-right text-muted"><small><?php echo $QuestWins['OverlordText']; ?></small></p>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <?php createProgressBar($QuestWins['HeroPerc'], "Won (Heroes)", $QuestWins['OverlordPerc'], "Won (Overlord)"); ?>         
            </div>
          </div>

        </div>
      </div>
    </div>
  </div><?php

} ?>