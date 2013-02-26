<?php
session_start();
$mysql_database="MobTrivia";
$mysql_username="root";
$mysql_password="quizzle.10";
$timer = 44;
$link = mysql_connect("localhost",$mysql_username,$mysql_password) or die ("Unable to connect to SQL server");
mysql_select_db($mysql_database,$link) or die ("Unable to select database");

echo "<head>
<link rel='stylesheet' type='text/css' href='mobtriv.css' />
</head><body><br/><br/><div>";

?>