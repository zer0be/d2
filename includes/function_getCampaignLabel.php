<?php

function getCampaignLabel($var, $view){
  $camClass = "";
  $camName = $var;

  if ($view == "mini"){
    $words = explode(" ", $camName);
    $acronym = "";

    foreach ($words as $w) {
      $acronym .= $w[0];
    }
  } else {
    $acronym = $camName;
  }

  switch($var){
    case "The Shadow Rune":
      $camClass = "label-info";
      break;
    case "Lair of the Wyrm":
      $camClass = "label-danger";
      break;
    case "Labyrinth of Ruin":
      $camClass = "label-warning";
      break;
    case "The Trollfens":
      $camClass = "label-success";
      break;
    case "Shadow of Nerekhall":
      $camClass = "label-primary purple";
      break;
    case "Manor of Ravens":
      $camClass = "label-primary";
      break;
    case "Mists of Bilehall":
      $camClass = "label-primary greenblue";
      break;
    default:
      $camClass = "label-default";
      break;
  }

  echo '<span class="label ' . $camClass . '">' . $acronym . '</span>';
  // $return = '<span class="label ' . $camClass . '">' . $acronym . '</span>';
  // return $return;

}