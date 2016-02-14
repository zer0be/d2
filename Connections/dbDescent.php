<?php
$hostname_dbDescent = "localhost";
$database_dbDescent = "d2etracker";
$username_dbDescent = "root";
$password_dbDescent = "";
$dbDescent = mysql_pconnect($hostname_dbDescent, $username_dbDescent, $password_dbDescent) or trigger_error(mysql_error(),E_USER_ERROR); 
?>