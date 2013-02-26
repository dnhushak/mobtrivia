<?php
session_start();
include('connect2db.php');
$password = "818148bd2207b6e3ccb6a7bd40369935";
$loginhtml = "<form name='mobster' action='mobster.php' method='POST'>Password: <input name='password' type='password'><br/><br/><input type='submit' value='login'></form>";

if(!isset($_SESSION['logged']))
{
if(!isset($_POST['password']))
	{
		echo $loginhtml;
	}
else
	{
	if(md5($_POST['password'])==$password)
		{
		$_SESSION['logged']=1;
		include('mobster.php');
		}
	else
		{
		echo "Login Failed <br/><br/>";
		echo $loginhtml;
		}
	}
}
elseif ($_SESSION['logged']==1)
{
	echo "Mobster Administrator<br/><br/><a href='teamsin.php'>Display Teams</a><br/><br/><a href='mobquestion.php'>Current Question</a><br/><br/><form action='reset.php' method='POST'>Reset Password: <input type='password' name='password'><br/><br/><input type='submit' value='Reset Mob Trivia'></form>";
}
?>