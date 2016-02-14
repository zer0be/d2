<?php 

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

mysql_select_db($database_dbDescent, $dbDescent);

$query_rsDetailSkills = sprintf("SELECT * FROM tbskills");
$rsDetailSkills = mysql_query($query_rsDetailSkills, $dbDescent) or die(mysql_error());
$row_rsDetailSkills = mysql_fetch_assoc($rsDetailSkills);
$totalRows_rsDetailSkills = mysql_num_rows($rsDetailSkills);


do{


	$pos = strpos($row_rsDetailSkills['skill_text'], 'last');

	if ($pos === false) {
	    
	} else {
	//    echo $row_rsDetailSkills['skill_name'] . '<br />';
		echo $row_rsDetailSkills['skill_id'] . " - " . $row_rsDetailSkills['skill_name'] . '<br />';
	  echo $row_rsDetailSkills['skill_text'] . '<br /><br />';
	}
} while ($row_rsDetailSkills = mysql_fetch_assoc($rsDetailSkills)); 

$query_rsDetailItems = sprintf("SELECT * FROM tbitems");
$rsDetailItems = mysql_query($query_rsDetailItems, $dbDescent) or die(mysql_error());
$row_rsDetailItems = mysql_fetch_assoc($rsDetailItems);
$totalRows_rsDetailItems = mysql_num_rows($rsDetailItems);


do{

	//echo $row_rsDetailItems['item_name'] . " - " . $row_rsDetailItems['item_text'] . '<br />';

	//$pos = strpos($row_rsDetailItems['item_text'], '"');
	$pos = strpos($row_rsDetailItems['item_text'], 'last');

	if ($pos === false) {
	    
	} else {
	  // echo $row_rsDetailItems['item_id'] . " - " . $row_rsDetailItems['item_name'] . '<br />';
	  // echo $row_rsDetailItems['item_text'] . '<br /><br />';
	}
} while ($row_rsDetailItems = mysql_fetch_assoc($rsDetailItems)); 


$query_rsDetailQuests = sprintf("SELECT * FROM tbquests");
$rsDetailQuests = mysql_query($query_rsDetailQuests, $dbDescent) or die(mysql_error());
$row_rsDetailQuests = mysql_fetch_assoc($rsDetailQuests);
$totalRows_rsDetailQuests = mysql_num_rows($rsDetailQuests);

?>

<form>
  <div class="form-group">
    <input style="width: 100%" type="email" class="form-control" id="exampleInputEmail1" placeholder="">
  </div>
 </form>

<?php

do{


	// $pos = strpos($row_rsDetailQuests['quest_errata'], '"');

	// if ($pos === false) {
	    
	// } else {
	//     echo $row_rsDetailQuests['quest_name'] . '<br />';
	// }

	// $pos = strpos($row_rsDetailQuests['quest_errata'], '“');

	// if ($pos === false) {
	    
	// } else {
	//     echo $row_rsDetailQuests['quest_name'] . '<br />';
	// }
	if(isset($row_rsDetailQuests['quest_description'])){
		echo "<strong>" . $row_rsDetailQuests['quest_name'] . "</strong> - " . $row_rsDetailQuests['quest_description'] . '<br />';
	}
	

} while ($row_rsDetailQuests = mysql_fetch_assoc($rsDetailQuests)); 



$query_rsDetailheroes = sprintf("SELECT * FROM tbheroes ORDER BY hero_type, hero_name, hero_expansion");
$rsDetailheroes = mysql_query($query_rsDetailheroes, $dbDescent) or die(mysql_error());
$row_rsDetailheroes = mysql_fetch_assoc($rsDetailheroes);
$totalRows_rsDetailheroes = mysql_num_rows($rsDetailheroes);

$full = array();
$mini = array();
$done = 0;
$todo = 0;

do{
	
	$filename = "img/heroes/" . $row_rsDetailheroes['hero_img'];
	if (file_exists($filename)) {
		$done++;
	} else {
	  $todo++;
	}
	$full[$row_rsDetailheroes['hero_type']][] = '<img src="img/heroes/' . $row_rsDetailheroes['hero_img'] . '" width="300px" alt="' . $row_rsDetailheroes['hero_name'] . '" />';
	$mini[$row_rsDetailheroes['hero_type']][] = '<img src="img/heroes/mini_' . $row_rsDetailheroes['hero_img'] . '" width="30px" alt="' . $row_rsDetailheroes['hero_name'] . '" title="' . $row_rsDetailheroes['hero_name'] . '"/>';
	
} while ($row_rsDetailheroes = mysql_fetch_assoc($rsDetailheroes)); 

$classes = array("Healer","Mage","Warrior","Scout",);

// foreach ($classes as $c){
// 	echo '<h2>' . $c . 's</h2>';
// 	foreach ($full[$c] as $f){
// 		echo $f;
// 	}

// 	echo '<br /><br />';

// 	foreach ($mini[$c] as $m){
// 		echo $m;
// 	}
// }


// echo '<br /><br />';
// echo '<h2>' . $done . ' done, ' . $todo . ' to do.</h2>';
?>

