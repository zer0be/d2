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

	<body class="white"><?php
		$front = 1;
		include 'navbar.php';
		include 'homepage_data.php';
		include 'banner.php';
		include 'homepage_stats.php';
		include 'homepage_block1.php'; ?>


		<div class="container grey"><?php
			include 'campaign.php'; ?>
		</div><?php

		include 'homepage_block2.php';
		include 'homepage_changelog.php'; ?>



		<div class="dark-grey">
			<div class="container footer"><?php
				include 'footer.php';	?>
			</div>
		</div>

	</body>
</html>