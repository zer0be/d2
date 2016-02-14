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
include 'includes/function_logout.php';
include 'includes/function_createProgressBar.php';
include 'includes/function_getSQLValueString.php';
include 'includes/function_getCampaignLabel.php';

//select the database
mysql_select_db($database_dbDescent, $dbDescent);

$query_rsDetailClasses = sprintf("SELECT * FROM tbclasses INNER JOIN tbcampaign ON class_exp_id = cam_id ORDER BY class_exp_id ASC");
$rsDetailClasses = mysql_query($query_rsDetailClasses, $dbDescent) or die(mysql_error());
$row_rsDetailClasses = mysql_fetch_assoc($rsDetailClasses);
$totalRows_rsDetailClasses = mysql_num_rows($rsDetailClasses);

$classes = array();

do{
  $classes[] = array(
    "name" => $row_rsDetailClasses['class_name'],
    "archetype" => $row_rsDetailClasses['class_archetype'],
    "expansion" => $row_rsDetailClasses['cam_name'],
  ); 
} while ($row_rsDetailClasses = mysql_fetch_assoc($rsDetailClasses)); 


// Get the skills
$query_rsDetailSkills = sprintf("SELECT * FROM tbskills INNER JOIN tbcampaign ON skill_expansion = cam_id ORDER BY skill_expansion, skill_cost, skill_name ASC");
$rsDetailSkills = mysql_query($query_rsDetailSkills, $dbDescent) or die(mysql_error());
$row_rsDetailSkills = mysql_fetch_assoc($rsDetailSkills);
$totalRows_rsDetailSkills = mysql_num_rows($rsDetailSkills);


$skills = array();
$olCardClass = array();
$olCardClassInfo = array();
$olCardExtraInfo = array();

do{

  $tooltipSkills = 
  "<div class='text-center tooltip-div row'>" .
    "<div class='col-sm-12'>" . 
      "<div><strong>" . $row_rsDetailSkills['skill_name'] . "</strong></div>" . 
      "<div class='col-sm-12 item-text'>" . $row_rsDetailSkills['skill_text'] . "</div>" . 
      "<div class='col-sm-3 text-margin-5'>" . $row_rsDetailSkills['skill_cost'] . "XP</div>" . "<div class='col-sm-2'></div>" . "<div class='col-sm-7 text-margin-5'>" . $row_rsDetailSkills['skill_stamina_cost'] . " Stamina</div>" . 
    "</div>" .
  "</div>";

  $skills[] = array(
    "skill_name" => $row_rsDetailSkills['skill_name'],
    "skill_cost" => $row_rsDetailSkills['skill_cost'],
    "skill_class" => $row_rsDetailSkills['skill_class'],
    "skill_stamina_cost" => $row_rsDetailSkills['skill_stamina_cost'],
    "skill_plot" => $row_rsDetailSkills['skill_plot'],
    "tooltip" => $tooltipSkills,
  );   

  if ($row_rsDetailSkills['skill_type'] == "Overlord" && $row_rsDetailSkills['skill_plot'] != 1){
    if ($row_rsDetailSkills['skill_class'] != "Corrupt Citizen" && $row_rsDetailSkills['skill_class'] != "Overlord Reward" && $row_rsDetailSkills['skill_class'] != "Quest Reward" && $row_rsDetailSkills['skill_class'] != "Rumor Reward"){
      if (!in_array($row_rsDetailSkills['skill_class'], $olCardClass)){
        $olCardClassInfo[] = array(
          "name" => $row_rsDetailSkills['skill_class'],
          "archetype" => $row_rsDetailSkills['skill_type'],
          "expansion" => $row_rsDetailSkills['cam_name'],
        );
        $olCardClass[] = $row_rsDetailSkills['skill_class'];
      }
    } else {
      if (!in_array($row_rsDetailSkills['skill_class'], $olCardClass)){
        $olCardExtraInfo[] = array(
          "name" => $row_rsDetailSkills['skill_class'],
          "archetype" => $row_rsDetailSkills['skill_type'],
          "expansion" => $row_rsDetailSkills['cam_name'],
        );
        $olCardClass[] = $row_rsDetailSkills['skill_class'];
      }
    }
  }

} while ($row_rsDetailSkills = mysql_fetch_assoc($rsDetailSkills)); 


$archetypes = array("Healer", "Mage", "Scout", "Warrior");

?>

<html>
  <head><?php 
    $pagetitle = "Heroes Info";
    include 'head.php'; ?>
    <script>
      $(function() { 
          // for bootstrap 3 use 'shown.bs.tab', for bootstrap 2 use 'shown' in the next line
          $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
              // save the latest tab; use cookies if you like 'em better:
              localStorage.setItem('lastTab', $(this).attr('href'));
          });

          // go to the latest tab, if it exists:
          var lastTab = localStorage.getItem('lastTab');
          if (lastTab) {
              $('[href="' + lastTab + '"]').tab('show');
          }
      });
    </script>
    <script>
    $(document).ready(function(){

      $('[data-toggle="tooltip"]').tooltip({'placement': 'bottom', html: true});

    });
    </script>
  </head>
  <body>
    <?php 
      include 'navbar.php'; 
      include 'banner.php';
    ?>

    <div class="container grey">

      <h1>Classes Information</h1>
      <p class="top-lead lead text-muted">An overview of all hero skills and overlord cards.</p>

      <div class="row"><?php
        $i = 1;
        foreach($archetypes as $ac){ ?>
          <div class="col-md-3">
            <div class="row">
              <div class="col-md-12">&nbsp;</div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <h2 class="panel-title"><?php echo $ac; ?></h2>
                  </div>

                  <div class="panel-body" id="accordion" role="tablist" aria-multiselectable="true"><?php
                    
                    foreach($classes as $cl){ 
                      if ($cl['archetype'] == $ac){ ?>
                        <div class="row stats-row">
                          <div class="col-xs-12">
                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $i; ?>" aria-expanded="true" aria-controls="collapse<?php echo $i; ?>">
                              <div class="row">
                                <div class="col-md-8"> 
                                  <p><strong><?php echo $cl['name']; ?></strong></p>
                                </div>
                                <div class="col-md-4 text-right"><?php 
                                  getCampaignLabel($cl['expansion'], "mini"); ?>
                                </div>
                              </div>
                            </a>
                            <div id="collapse<?php echo $i; ?>" class="row panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?php echo $i; ?>"> 
                              <div class="detail-items col-md-12"><?php 
                                foreach($skills as $sk){ 
                                  if ($sk['skill_class'] == $cl['name']){ ?>
                                    <div class="skill row no-gutters">
                                      <div class="col-sm-2">
                                        <div class="hero-mini" style="background: url('img/staminacost<?php print $sk['skill_stamina_cost']; ?>.png') center;"></div>
                                      </div> 
                                      <div class="skill-name col-sm-8"><a href="#" data-toggle="tooltip" title="<?php print $sk['tooltip']; ?>"><?php print $sk['skill_name']; ?></a></div><?php 
                                      if ($sk['skill_cost'] == 0){ ?>
                                        <div class="col-sm-2 text-center"><span class="badge green">Free</span></div><?php 
                                      } else { ?>
                                        <div class="col-sm-2 text-center"><span class="badge red"><?php echo $sk['skill_cost']; ?><span class="skill-xp-label">XP</span></span></div><?php 
                                      } ?>
                                    </div><?php
                                  }
                                } ?>
                              </div>
                            </div>
                          </div>
                        </div><?php
                        $i++;
                      }
                    } ?>
                  </div>
                </div> 
              </div>
            </div>
          </div><?php
        } ?>
        <div class="col-md-12">
          <div class="row">
            <div class="col-md-12">&nbsp;</div>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h2 class="panel-title">Overlord Card Decks</h2>
                </div>

                <div class="panel-body" id="accordion" role="tablist" aria-multiselectable="true"><?php
                  
                  foreach($olCardClassInfo as $ocl){ 
                    if ($ocl['archetype'] == "Overlord"){ ?>
                      <div class="row stats-row">
                        <div class="col-xs-12">
                          <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $i; ?>" aria-expanded="true" aria-controls="collapse<?php echo $i; ?>">
                            <div class="row">
                              <div class="col-md-6"> 
                                <p><strong><?php echo $ocl['name']; ?></strong></p>
                              </div>
                              <div class="col-md-6 text-right"><?php 
                                getCampaignLabel($ocl['expansion'], "normal"); ?>
                              </div>
                            </div>
                          </a>
                          <div id="collapse<?php echo $i; ?>" class="row panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?php echo $i; ?>"> 
                            <div class="detail-items col-md-12"><?php 
                              foreach($skills as $sk){ 
                                if ($sk['skill_class'] == $ocl['name']){ ?>
                                  <div class="skill row no-gutters">
                                    <div class="col-sm-2">
                                      <div class="hero-mini" style="background: url('img/staminacost<?php print $sk['skill_stamina_cost']; ?>.png') center;"></div>
                                    </div> 
                                    <div class="skill-name col-sm-8"><a href="#" data-toggle="tooltip" title="<?php print $sk['tooltip']; ?>"><?php print $sk['skill_name']; ?></a></div><?php 
                                    if ($sk['skill_cost'] == 0){ ?>
                                      <div class="col-sm-2 text-center"><span class="badge green">Free</span></div><?php 
                                    } else { ?>
                                      <div class="col-sm-2 text-center"><span class="badge red"><?php echo $sk['skill_cost']; ?><span class="skill-xp-label">XP</span></span></div><?php 
                                    } ?>
                                  </div><?php
                                }
                              } ?>
                            </div>
                          </div>
                        </div>
                      </div><?php
                      $i++;
                    }
                  } ?>
                </div>
              </div> 

              <div class="panel panel-default">
                <div class="panel-heading">
                  <h2 class="panel-title">Overlord Rewards</h2>
                </div>

                <div class="panel-body" id="accordion" role="tablist" aria-multiselectable="true"><?php
                  
                  foreach($olCardExtraInfo as $ecl){ 
                    if ($ecl['archetype'] == "Overlord"){ ?>
                      <div class="row stats-row">
                        <div class="col-xs-12">
                          <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $i; ?>" aria-expanded="true" aria-controls="collapse<?php echo $i; ?>">
                            <div class="row">
                              <div class="col-md-6"> 
                                <p><strong><?php echo $ecl['name']; ?></strong></p>
                              </div>
                              <div class="col-md-6 text-right"><?php 
                                // getCampaignLabel($ecl['expansion'], "normal"); ?>
                              </div>
                            </div>
                          </a>
                          <div id="collapse<?php echo $i; ?>" class="row panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?php echo $i; ?>"> 
                            <div class="detail-items col-md-12"><?php 
                              foreach($skills as $sk){ 
                                if ($sk['skill_class'] == $ecl['name']){ ?>
                                  <div class="skill row no-gutters">
                                    <div class="col-sm-2">
                                      <div class="hero-mini" style="background: url('img/staminacost<?php print $sk['skill_stamina_cost']; ?>.png') center;"></div>
                                    </div> 
                                    <div class="skill-name col-sm-8"><a href="#" data-toggle="tooltip" title="<?php print $sk['tooltip']; ?>"><?php print $sk['skill_name']; ?></a></div><?php 
                                    if ($sk['skill_cost'] == 0){ ?>
                                      <div class="col-sm-2 text-center"><span class="badge green">Free</span></div><?php 
                                    } else { ?>
                                      <div class="col-sm-2 text-center"><span class="badge red"><?php echo $sk['skill_cost']; ?><span class="skill-xp-label">XP</span></span></div><?php 
                                    } ?>
                                  </div><?php
                                }
                              } ?>
                            </div>
                          </div>
                        </div>
                      </div><?php
                      $i++;
                    }
                  } ?>
                </div>
              </div> 

            </div>
            <div class="col-sm-6">
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h2 class="panel-title">Overlord Plot Decks</h2>
                </div>

                <div class="panel-body" id="accordion" role="tablist" aria-multiselectable="true"><?php
                  
                  foreach($classes as $cl){ 
                    if ($cl['archetype'] == "Overlord"){ ?>
                      <div class="row stats-row">
                        <div class="col-xs-12">
                          <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $i; ?>" aria-expanded="true" aria-controls="collapse<?php echo $i; ?>">
                            <div class="row">
                              <div class="col-md-6"> 
                                <p><strong><?php echo $cl['name']; ?></strong></p>
                              </div>
                              <div class="col-md-6 text-right"> 
                                <?php getCampaignLabel($cl['expansion'], "normal"); ?>
                              </div>
                            </div>
                          </a>
                          <div id="collapse<?php echo $i; ?>" class="row panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?php echo $i; ?>"> 
                            <div class="detail-items col-md-12"><?php 
                              foreach($skills as $sk){ 
                                if ($sk['skill_class'] == $cl['name']){ ?>
                                  <div class="skill row no-gutters">
                                    <div class="col-sm-2">
                                      <div class="hero-mini" style="background: url('img/staminacost<?php print $sk['skill_stamina_cost']; ?>.png') center;"></div>
                                    </div> 
                                    <div class="skill-name col-sm-8"><a href="#" data-toggle="tooltip" title="<?php print $sk['tooltip']; ?>"><?php print $sk['skill_name']; ?></a></div><?php 
                                    if ($sk['skill_cost'] == 0){ ?>
                                      <div class="col-sm-2 text-center"><span class="badge green">Free</span></div><?php 
                                    } else { ?>
                                      <div class="col-sm-2 text-center"><span class="badge red"><?php echo $sk['skill_cost']; ?><span class="skill-xp-label"> Threat</span></span></div><?php 
                                    } ?>
                                  </div><?php
                                }
                              } ?>
                            </div>
                          </div>
                        </div>
                      </div><?php
                      $i++;
                    }
                  } ?>
                </div>
              </div> 
            </div>
          </div>

        </div>


      </div>
    </div>
  </body>
</html>