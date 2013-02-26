<?php
session_start();
include('connect2db.php');
if(!isset($_SESSION['logged']))
{
include('mobster.php');
}
elseif ($_SESSION['logged']==1)
{
	$select = "SELECT name, lastcorrect FROM Players WHERE stillin='1'";
	$select_q = mysql_query($select);
	$find = "SELECT number, asktime FROM Question WHERE current='1'";
	$find_q = mysql_query($find);
	$find_q_fetch = mysql_fetch_row($find_q);
	if($find_q)
	{
		for ($a=0;$a<=mysql_num_rows($select_q)-1;$a++)
		{
			$select_q_fetch = mysql_fetch_row($select_q);
			if($select_q_fetch[1] < $find_q_fetch[0]-1)
			{
				$update = "UPDATE Players SET stillin='0' WHERE name='" . $select_q_fetch[0] . "'";
				$update_q = mysql_query($update);
			}
			elseif($select_q_fetch[1] == ($find_q_fetch[0] - 1) && $find_q_fetch[1] < (time() - $timer))
			{
				$update = "UPDATE Players SET stillin='0' WHERE name='" . $select_q_fetch[0] . "'";
				$update_q = mysql_query($update);
			}
		}
	}
	
	echo "<div><table width='100%' text-align='center'>";
	$fill = "SELECT DISTINCT team, stillin FROM Players ORDER BY stillin DESC";	
	$fill_q = mysql_query($fill);
	
	for ($a=0;$a<=mysql_num_rows($fill_q)-1;$a++)
		{
		$fill_q_fetch = mysql_fetch_row($fill_q);
		$team[$a][0] = $fill_q_fetch[0];
		$team[$a][1] = $fill_q_fetch[1];
		}
 	for ($a=0;$a<=count($team)-1;$a++)
		{
			if($team[$a][1]==1)
			{
			echo "<tr class='stillin'><td>" . $team[$a][0] . " - Still In!</td></tr>";
			}
		}
	echo "</table><br/><form action='mobquestion.php' method='POST'>
			<input type='submit' value='Go to next question'/>
			<input type='hidden' name='activate' value='1'/>
			</form><a href='mobster.php'>Admin Home</a><br/><br/><a href='mobquestion.php'>Current Question</a>";
}
?>