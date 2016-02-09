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
include 'includes/function_getSQLValueString.php';

// include the campaign data 
include 'campaign_data.php';

$pID = $campaign['quests'][0]['id'];
$qID = $campaign['quests'][0]['quest_id'];
$token = md5(uniqid(rand(), TRUE));
$miniCampaigns = array(1,3,5);

$_SESSION['validate'] = array();

if ($owner == 1){

	$_SESSION['validate'] = array(
		"gameID" => $gameID,
		"pID" => $pID,
		"qID" => $qID,
		"oID" => NULL,
		"expID" => $campaign['camp_id'],
		"token" => $token,
		"gold" => $campaign['gold'],
	);

} else {
	$insertGoTo = "campaign_overview?urlGamingID=" . $gameID_obscured;
  header(sprintf("Location: %s", $insertGoTo));
  die("Redirecting to campaign_overview_save.php"); 
}

$_SESSION['players'] = $players;
$_SESSION['campaign'] = $campaign;
foreach ($players as $h){
	$playerIDs[] = $h['id']; 
  if ($h['archetype'] == 'Overlord'){
    $overlordID = $h['id'];
    $_SESSION['validate']['oID'] = $overlordID;
  } else {
  	$heroIDs[] = $h['id'];
  }
}

// echo '<pre>';
// var_dump($players);
// echo '</pre>';

$_SESSION['verify_values'] = array(
	"players" => $playerIDs,
	"heroes" => $heroIDs,
	"overlord" => $overlordID,
  "winner" => array("Heroes Win", "Overlord Wins"),
  "skills_acquired" => NULL,
  "skills_available" => NULL,
  "items_available" => NULL,
  "items_sellable" => NULL,
  "items_tradable" => NULL,
  "monsters_enc1" => NULL,
  "monsters_enc2" => NULL,
  "monsters_enc3" => NULL,
  "search_cards" => NULL,
  "threat" => array(),
  "yesno" => array("yes","no"),
  "time" => array(0,30,60,90,120,150,180,240,300,360,999),
);



// ------------------- //
// -- Quest Details -- //
// ------------------- //

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

// ------------------- //
// -- Travel Events -- //
// ------------------- //

	include 'campaign_overview_save_travel_data.php';

// -------------- //
// -- Spend XP -- //
// -------------- //

	include 'campaign_overview_save_skills_data.php';

// --------------- //
// -- Buy Items -- //
// --------------- //

	include 'campaign_overview_save_items_data.php';

// ---------- //
// -- Edit -- //
// ---------- //
// 
if(isset($_GET['part']) && $_GET['part'] == "edit") {// close if (isset) 

	include 'campaign_overview_save_edit_data.php';

}

// ------------ //
// -- Delete -- //
// ------------ //

if(isset($_GET['part']) && $_GET['part'] == "del") {// close if (isset) 

	include 'campaign_overview_save_delete_data.php';

}

?> 

<html>
	<head><?php 
  	$pagetitle = "Update Campaign";
  	include 'head.php'; ?>
		<script>
			$(document).ready(function(){
				$('#treasurechest').change(function(){
					if(this.checked)
					  $("#select-item").fadeIn('slow');
					else
						$("#select-item").fadeOut('fast');
			  });

			  $('#secretpassage').change(function(){
					if(this.checked)
					  $("#select-passage").fadeIn('slow');
					else
						$("#select-passage").fadeOut('fast');
			  });

			  $('#secretroom-cleared').change(function(){
					if(this.checked)
					  $("#select-passage-clear").fadeIn('slow');
					else
						$("#select-passage-clear").fadeOut('fast');
			  });

			  $("#secretroom").change(function(){
	        $(this).find("option:selected").each(function(){
	            if($(this).attr("value")=="3"){
	                $("#select-passage-item").show();
	            }
	            else if($(this).attr("value")=="4"){
	                $("#select-passage-item").show();
	            }
	            else if($(this).attr("value")=="8"){
	                $("#select-passage-item").show();
	            }
	            else{
	                $("#select-passage-item").hide();
	            }
	        });
	    	}).change();
			});
    </script>
	</head>

	<body>
		<!-- <div id="wrapper"> -->
		<?php include 'navbar.php'; ?>
    <div class="container">
			<div class="row"><?php
				foreach ($campaign['quests'] as $qs){	
					if($qs['quest_id'] == $qID){
						$filename = "img/quests/" . $qs['img'];
						if (file_exists($filename)) {
						
						} else {
						  $filename = "img/quests/default.jpg";
						} ?>
			      <div class="col-sm-3" style="background: url('<?php print $filename; ?>') no-repeat center;">
			  			<div class="col-quest-details">
			  				<div class="quest-name"><?php print $qs['name']; ?></div>
			  			</div>
			        <a class="btn btn-primary btn-block" href="campaign_overview.php?urlGamingID=<?php echo $gameID_obscured; ?>">Back to Overview</a>
			      </div>

						<div class="col-sm-9">
							<div class="row row-bg-white">
								<div class="col-sm-12">

									<?php
									if(isset($_GET['part']) && $_GET['part'] == "q") { 

										include 'campaign_overview_save_quest.php';

									} 
									else if(isset($_GET['part']) && $_GET['part'] == "xp") {// close if (isset)

										include 'campaign_overview_save_skills.php';

									} 
									else if(isset($_GET['part']) && $_GET['part'] == "it") {// close if (isset)
									
										include 'campaign_overview_save_items.php';

									} 
									else if(isset($_GET['part']) && $_GET['part'] == "t") {// close if (isset) 

										include 'campaign_overview_save_travel.php';

									}

									else if(isset($_GET['part']) && $_GET['part'] == "edit") {// close if (isset) 

										include 'campaign_overview_save_edit.php';

									}

									else if(isset($_GET['part']) && $_GET['part'] == "del") {// close if (isset) 

										include 'campaign_overview_save_delete.php';

									}

									?>

								</div>
							</div>
						</div><?php 
					} // close if (quest_id)
				} // close foreach
				?>
			</div>
		</div>

	</body>
</html>

<?php 

// echo '<pre>';
// var_dump($_SESSION['verify_values']);
// //var_dump($_SESSION['errorcode']);
// echo '</pre>';

?>

