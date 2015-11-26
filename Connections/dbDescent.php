<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_dbDescent = "localhost";
$database_dbDescent = "zer0_d2etracker";
$username_dbDescent = "zer0_d2euser";
$password_dbDescent = "dBd2e_87";
$dbDescent = mysql_pconnect($hostname_dbDescent, $username_dbDescent, $password_dbDescent) or trigger_error(mysql_error(),E_USER_ERROR); 
?>