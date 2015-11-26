<?php
if($hideCk == 1){
  if ($attrType == "All"){
    $query_rsHeroesProperties = sprintf("SELECT * FROM tbheroes INNER JOIN tbcampaign on hero_expansion = cam_id WHERE hero_expansion != %s ORDER BY {$sortBy}, hero_expansion", GetSQLValueString(99, "int"));
  } else {
    $query_rsHeroesProperties = sprintf("SELECT * FROM tbheroes INNER JOIN tbcampaign on hero_expansion = cam_id WHERE hero_type = %s AND hero_expansion != %s ORDER BY {$sortBy}, hero_expansion", GetSQLValueString($attrType, "text"), GetSQLValueString(99, "int"));
  }
} else {
  if ($attrType == "All"){
    $query_rsHeroesProperties = sprintf("SELECT * FROM tbheroes INNER JOIN tbcampaign on hero_expansion = cam_id ORDER BY {$sortBy}, hero_expansion");
  } else {
    $query_rsHeroesProperties = sprintf("SELECT * FROM tbheroes INNER JOIN tbcampaign on hero_expansion = cam_id WHERE hero_type = %s ORDER BY {$sortBy}, hero_expansion", GetSQLValueString($attrType, "text"));
  }
}



$rsHeroesProperties = mysql_query($query_rsHeroesProperties, $dbDescent) or die(mysql_error());
$row_rsHeroesProperties = mysql_fetch_assoc($rsHeroesProperties);
$totalRows_rsHeroesProperties = mysql_num_rows($rsHeroesProperties);

$heroes = array();
$allTotalSpeed = 0;
$allTotalHealth = 0;
$allTotalStamina = 0;

$allTotalMight = 0;
$allTotalKnowledge = 0;
$allTotalWillpower = 0;
$allTotalAwareness = 0;

do {
  $heroes[] = $row_rsHeroesProperties;

  $allTotalSpeed += $row_rsHeroesProperties['hero_speed'];
  $allTotalHealth += $row_rsHeroesProperties['hero_health'];
  $allTotalStamina += $row_rsHeroesProperties['hero_stamina'];

  $allTotalMight += $row_rsHeroesProperties['hero_might'];
  $allTotalKnowledge += $row_rsHeroesProperties['hero_knowledge'];
  $allTotalWillpower += $row_rsHeroesProperties['hero_willpower'];
  $allTotalAwareness += $row_rsHeroesProperties['hero_awareness'];

} while ($row_rsHeroesProperties = mysql_fetch_assoc($rsHeroesProperties));

$allAvgSpeed = round($allTotalSpeed / $totalRows_rsHeroesProperties);
$allAvgHealth = round($allTotalHealth / $totalRows_rsHeroesProperties);
$allAvgStamina = round($allTotalStamina / $totalRows_rsHeroesProperties);

$allAvgMight = round($allTotalMight / $totalRows_rsHeroesProperties);
$allAvgKnowledge = round($allTotalKnowledge / $totalRows_rsHeroesProperties);
$allAvgWillpower = round($allTotalWillpower / $totalRows_rsHeroesProperties);
$allAvgAwareness = round($allTotalAwareness / $totalRows_rsHeroesProperties);

?>

<div class="row">
  <div class="col-sm-5 text-center">
    <div class="row">
      <div class="col-sm-2">
        <div class="text-margin-5">

        </div>
      </div>
      <div class="col-sm-8 text-center">
        <div class="text-margin-10">
          <strong>Hero</strong>
        </div>
      </div>
      <div class="col-sm-2 text-center">
        <div class="text-margin-10"><strong>Class</strong></div>
      </div>
    </div>
  </div>
  <div class="col-sm-7">
    <div class="row no-gutters"><?php
      if ($hideCk == 1){
        $showurl = "&show=hide-ck";
      } else {
        $showurl = "";
      } ?>
      <div class="col-sm-1">
      </div>

      <div class="col-sm-5">
        <div class="col-sm-3">
           <a href="stats_attributes.php?sort=speed<?php if($sort == 'speed' && $sorting == 'DESC'){ echo '&by=ASC'; } else { echo '&by=DESC'; } ?><?php echo $showurl; ?>"><div class="hero-mini center-block" style="background: url('img/speed.png') center;"></div></a>
        </div>
        <div class="col-sm-3">
           <a href="stats_attributes.php?sort=health<?php if($sort == 'health' && $sorting == 'DESC'){ echo '&by=ASC'; } else { echo '&by=DESC'; } ?><?php echo $showurl; ?>"><div class="hero-mini center-block" style="background: url('img/health.png') center;"></div></a>
        </div>
        <div class="col-sm-3">
           <a href="stats_attributes.php?sort=stamina<?php if($sort == 'stamina' && $sorting == 'DESC'){ echo '&by=ASC'; } else { echo '&by=DESC'; } ?><?php echo $showurl; ?>"><div class="hero-mini center-block" style="background: url('img/stamina.png') center;"></div></a>
        </div>
        <div class="col-sm-3">
           <a href="stats_attributes.php?sort=defense<?php if($sort == 'defense' && $sorting == 'DESC'){ echo '&by=ASC'; } else { echo '&by=DESC'; } ?><?php echo $showurl; ?>"><div class="hero-mini center-block" style="background: url('img/defense.png') center;"></div></a>
        </div>
      </div>
      <div class="col-sm-5">
        <div class="col-sm-3">
          <a href="stats_attributes.php?sort=might<?php if($sort == 'might' && $sorting == 'DESC'){ echo '&by=ASC'; } else { echo '&by=DESC'; } ?><?php echo $showurl; ?>"><div class="hero-mini center-block" style="background: url('img/might.png') center;"></div></a>
        </div>
        <div class="col-sm-3">
          <a href="stats_attributes.php?sort=knowledge<?php if($sort == 'knowledge' && $sorting == 'DESC'){ echo '&by=ASC'; } else { echo '&by=DESC'; } ?><?php echo $showurl; ?>"><div class="hero-mini center-block" style="background: url('img/knowledge.png') center;"></div></a>
        </div>
        <div class="col-sm-3">
          <a href="stats_attributes.php?sort=willpower<?php if($sort == 'willpower' && $sorting == 'DESC'){ echo '&by=ASC'; } else { echo '&by=DESC'; } ?><?php echo $showurl; ?>"><div class="hero-mini center-block" style="background: url('img/willpower.png') center;"></div></a>
        </div>
        <div class="col-sm-3">
          <a href="stats_attributes.php?sort=awareness<?php if($sort == 'awareness' && $sorting == 'DESC'){ echo '&by=ASC'; } else { echo '&by=DESC'; } ?><?php echo $showurl; ?>"><div class="hero-mini center-block" style="background: url('img/awareness.png') center;"></div></a>
        </div>
      </div>
    </div>
  </div>
</div>



<div class="row stats-row">
  <div class="col-sm-5 text-center">
    <div class="row">
      <div class="col-sm-2">
        <div class="text-margin-5">

        </div>
      </div>
      <div class="col-sm-8 text-center">
        <div class="text-margin-10">
          Average<?php if ($attrType != "All") { echo " " . $attrType;} ?>
        </div>
      </div>
      <div class="col-sm-2 text-center">
        <div class="text-margin-10"></div>
      </div>
    </div>
  </div>
  <div class="col-sm-7">
    <div class="row no-gutters">
      <div class="col-sm-1">
      </div>

      <div class="col-sm-5">
        <div class="row no-gutters"><?php
          $avgAttributes = array($allAvgSpeed, $allAvgHealth, $allAvgStamina);
          $i = 1;
          foreach ($avgAttributes as $avg){ 
            if ($i == 2){ 
              $values = array(8,10,12,14,15,);
            } else {
              $values = array(2,3,4,5,6,);
            }
            $i++;?>
            <div class="col-sm-3 text-center">
              <span class="badge <?php 
                if($avg <= $values[0]){ 
                  echo 'dark-red';
                } else if($avg <= $values[1]){ 
                  echo 'red';
                } else if($avg <= $values[2]){ 
                  echo 'yellow';
                } else if($avg <= $values[3]){ 
                  echo 'green';
                } else if($avg >= $values[4]){ 
                  echo 'dark-green';
                } ?>">
                <?php print $avg; ?>
              </span>
            </div><?php
          } ?>
          <div class="col-sm-3">
            <div class="hero-mini center-block" style="background: url('img/defenseG.png') center;"></div>
          </div>
        </div>
      </div>

      <div class="col-sm-5">
        <div class="row no-gutters"><?php
          $avgAttributes = array($allAvgMight, $allAvgKnowledge, $allAvgWillpower, $allAvgAwareness);
          $values = array(1,2,3,4,5,);
          foreach ($avgAttributes as $avg){ ?>
            <div class="col-sm-3 text-center">
              <span class="badge <?php 
                if($avg <= $values[0]){ 
                  echo 'dark-red';
                } else if($avg <= $values[1]){ 
                  echo 'red';
                } else if($avg <= $values[2]){ 
                  echo 'yellow';
                } else if($avg <= $values[3]){ 
                  echo 'green';
                } else if($avg >= $values[4]){ 
                  echo 'dark-green';
                } ?>">
                <?php print $avg; ?>
              </span>
            </div><?php
          } ?>
        </div>
      </div>

    </div>
  </div>

</div>

<?php
foreach ($heroes as $ho){
  if($ho['hero_type'] != "Overlord"){ ?>
    <div class="row stats-row">
      <div class="col-sm-5">
        <div class="row">
          <div class="col-sm-2">
            <div class="text-margin-5"><?php 
            echo '<img src="img/heroes/mini_' . $ho['hero_img'] . '" />'; ?>
            </div>
          </div>
          <div class="col-sm-8 text-center">
            <div class="text-margin-10"><?php
              echo $ho['hero_name'] . " "; 
              getCampaignLabel($ho['cam_name'], "mini"); ?>
            </div>
          </div>
          <div class="col-sm-2 text-center">
            <div class="text-margin-10"><?php echo $ho['hero_type']; ?></div>
          </div>
        </div>
      </div>

      <div class="col-sm-7">
        <div class="skill row no-gutters">

          <div class="col-sm-1">
          </div>

          <div class="col-sm-5">
            <div class="row no-gutters"><?php
              $heroAvgs = array();
              $values = array(2,3,4,5,6,);
              $heroAvgs[] = array(
                "heroStat" => $ho['hero_speed'],
                "avgStat" => $allAvgSpeed,
                "values" => $values,
              );
              $values = array(8,10,12,14,15,);
              $heroAvgs[] = array(
                "heroStat" => $ho['hero_health'],
                "avgStat" => $allAvgHealth,
                "values" => $values,
              );
              $values = array(1,2,3,4,5,);
              $heroAvgs[] = array(
                "heroStat" => $ho['hero_stamina'],
                "avgStat" => $allAvgStamina,
                "values" => $values,
              );

              foreach ($heroAvgs as $havg){ ?>
                <div class="col-sm-3 text-center">
                  <span class="badge <?php 
                    if($havg['heroStat'] <= $havg['values'][0]){ 
                      echo 'dark-red';
                    } else if($havg['heroStat'] <= $havg['values'][1]){ 
                      echo 'red';
                    } else if($havg['heroStat'] <= $havg['values'][2]){ 
                      echo 'yellow';
                    } else if($havg['heroStat'] <= $havg['values'][3]){ 
                      echo 'green';
                    } else if($havg['heroStat'] >= $havg['values'][4]){ 
                      echo 'dark-green';
                    } ?>">
                    <?php print $havg['heroStat']; ?>
                  </span><?php 
                  if ($havg['heroStat'] > $havg['avgStat']){ ?>
                    <small><span class="glyphicon glyphicon-triangle-top text-green" aria-hidden="true"></span></small><?php
                  } else if ($havg['heroStat'] < $havg['avgStat']){ ?>
                    <small><span class="glyphicon glyphicon-triangle-bottom text-red" aria-hidden="true"></span></small><?php
                  } else { ?>
                    <small><span class="glyphicon glyphicon-minus text-yellow" aria-hidden="true"></span></small><?php
                  } ?>

                </div><?php
              } ?>
              
              <div class="col-sm-3">
                <div class="hero-mini center-block" style="background: url('img/defense<?php print $ho['hero_defense']; ?>.png') center;"></div>
              </div>
            </div>
          </div>


          <div class="col-sm-5">
            <div class="row no-gutters"><?php
              $heroAvgs = array();
              $values = array(1,2,3,4,5,);
              $heroAvgs[] = array(
                "heroStat" => $ho['hero_might'],
                "avgStat" => $allAvgMight,
                "values" => $values,
              );
              $heroAvgs[] = array(
                "heroStat" => $ho['hero_knowledge'],
                "avgStat" => $allAvgKnowledge,
                "values" => $values,
              );
              $heroAvgs[] = array(
                "heroStat" => $ho['hero_willpower'],
                "avgStat" => $allAvgWillpower,
                "values" => $values,
              );
              $heroAvgs[] = array(
                "heroStat" => $ho['hero_awareness'],
                "avgStat" => $allAvgAwareness,
                "values" => $values,
              );

              foreach ($heroAvgs as $havg){ ?>
                <div class="col-sm-3 text-center">
                  <span class="badge <?php 
                    if($havg['heroStat'] <= $havg['values'][0]){ 
                      echo 'dark-red';
                    } else if($havg['heroStat'] <= $havg['values'][1]){ 
                      echo 'red';
                    } else if($havg['heroStat'] <= $havg['values'][2]){ 
                      echo 'yellow';
                    } else if($havg['heroStat'] <= $havg['values'][3]){ 
                      echo 'green';
                    } else if($havg['heroStat'] >= $havg['values'][4]){ 
                      echo 'dark-green';
                    } ?>">
                    <?php print $havg['heroStat']; ?>
                  </span><?php 
                  if ($havg['heroStat'] > $havg['avgStat']){ ?>
                    <small><span class="glyphicon glyphicon-triangle-top text-green" aria-hidden="true"></span></small><?php
                  } else if ($havg['heroStat'] < $havg['avgStat']){ ?>
                    <small><span class="glyphicon glyphicon-triangle-bottom text-red" aria-hidden="true"></span></small><?php
                  } else { ?>
                    <small><span class="glyphicon glyphicon-minus text-yellow" aria-hidden="true"></span></small><?php
                  } ?>

                </div><?php
              } ?>

            </div>
          </div>


        </div>
      </div>

    </div><?php 
  }
} ?>