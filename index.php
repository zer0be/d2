<?php 

//include the db
require_once('Connections/dbDescent.php'); 

//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

//include functions
include 'includes/function_logout.php';
include 'includes/function_getSQLValueString.php';
include 'includes/function_createProgressBar.php';

?>


<html>
	<head><?php 
    $pagetitle = "Home";
    include 'head.php'; ?>
	</head>
	<body>
		<?php 
			include 'navbar.php';
			include 'banner.php'; 
		?>

		<div class="container grey">
			<?php 
				include 'campaign.php';
			?>
		</div>
	</body>
</html>