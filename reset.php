<?php
session_start();
include('connect2db.php');
$password = '7d9b53f484b070d715252daf0a3f334b';
if(!isset($_SESSION['logged']))
{
include('mobster.php');
}
elseif ($_SESSION['logged']==1)
{
	if(md5($_POST['password'])==$password)
	{
		$update = "UPDATE Question SET current='0', asktime ='0'";
		$update_q = mysql_query($update);
		$update2 = "UPDATE Players SET stillin='1', lastanswered ='0', lastcorrect='0'";
		$update2_q = mysql_query($update2);
		echo "Mob Trivia Successfuly reset!<br/>";
	}
	else 
	{
		echo "Invalid Password<br/>";
	}
include ('mobster.php');
}
?>