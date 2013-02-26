<?php
session_start();
include('connect2db.php');
if(!isset($_SESSION['logged']))
{
include('mobster.php');
}
elseif ($_SESSION['logged']==1)
{
	if ($_POST['activate']==1)
	{
		$fill = "SELECT question, correct, wrong1, wrong2, number FROM Question WHERE current='0' AND asktime = '0'";
		$fill_q = mysql_query($fill);
		$fill_q_fetch = mysql_fetch_row($fill_q);
		$number = $fill_q_fetch[4];
		$updat = "UPDATE Question SET current='0'";
		$updat_q = mysql_query($updat);
		$update = "UPDATE Question SET current='1', asktime ='" . time() . "' WHERE number='" . $number . "'";
		$update_q = mysql_query($update);
		$question = $fill_q_fetch[0];
		$answer[0] = $fill_q_fetch[1];
		$answer[1] = $fill_q_fetch[2];
		$answer[2] = $fill_q_fetch[3];
		$number = $fill_q_fetch[4];
		$count = "SELECT asktime FROM Question WHERE current='1'";
		$count_q = mysql_query($count);
		$count_q_fetch = mysql_fetch_row($count_q);
		if(mysql_num_rows($count_q)!=0)
		{
		$counter = $timer - (time() - $count_q_fetch[0]);
		}
		else
		{
		$counter = 0;
		}
		{
echo "
<script type='text/javascript'>
var mins
var secs;

function cd() {
 	mins = 1 * m('0'); // change minutes here
 	secs = 0 + s(':" . $counter . "'); // change seconds here (always add an additional second to your total)
 	redo();
}

function m(obj) {
 	for(var i = 0; i < obj.length; i++) {
  		if(obj.substring(i, i + 1) == ':')
  		break;
 	}
 	return(obj.substring(0, i));
}

function s(obj) {
 	for(var i = 0; i < obj.length; i++) {
  		if(obj.substring(i, i + 1) == ':')
  		break;
 	}
 	return(obj.substring(i + 1, obj.length));
}

function dis(mins,secs) {
 	var disp;
 	if(mins <= 9) {
  		disp = ' 0';
 	} else {
  		disp = ' ';
 	}
 	disp = '00' + ':';
 	if(secs <= 9) {
  		disp += '0' + secs;
 	} else {
  		disp += secs;
 	}
 	return(disp);
}

function redo() {
 	secs--;
 	if(secs == -1) {
  		secs = 59;
  		mins--;
 	}
 	document.cd.disp.value = dis(mins,secs); // setup additional displays here.
 	if(secs == 0) {
  		window.alert('Time is up. Press OK to continue.'); // change timeout message as required
  	    window.location = 'mobquestion.php' // redirects to specified page once timer ends and ok button is pressed
 	} else {
 		cd = setTimeout('redo()',1000);
 	}
}

function init() {
  cd();
}
window.onload = init;

</script>";
}
		shuffle($answer);
		echo "Question Number " . $number . ": " . $question;
		echo "<br/><br/>" . $answer[0];
		echo "<br/><br/>" . $answer[1];
		echo "<br/><br/>" . $answer[2] . "<br/>";
		echo "<br/><form method='POST'>
			<input type='submit' value='Refresh'/><input type='hidden' name='activate' value='0'/>
			</form><br/><a href='mobster.php'>Question Admin</a>";				
		echo "<form name='cd'>
					<input id='txt' readonly='true' type='text' value='" . (time() - $asktime) . "' border='0' name='disp'>
					</form>";
				

	}
	else
	{
		$time = (time()-$timer);
		$fill = "SELECT question, correct, wrong1, wrong2, number FROM Question WHERE current='1' AND asktime > '" . $time . "'";
		$fill_q = mysql_query($fill);
		$fill_q_fetch = mysql_fetch_row($fill_q);
		$question = $fill_q_fetch[0];
		$answer[0] = $fill_q_fetch[1];
		$answer[1] = $fill_q_fetch[2];
		$answer[2] = $fill_q_fetch[3];$number = $fill_q_fetch[4];
		$count = "SELECT asktime FROM Question WHERE current='1'";
		$count_q = mysql_query($count);
		$count_q_fetch = mysql_fetch_row($count_q);
		if(mysql_num_rows($count_q)!=0)
		{
		$counter = $timer - (time() - $count_q_fetch[0]);
		}
		else
		{
		$counter = 0;
		}
		{
echo "
<script type='text/javascript'>
var mins
var secs;

function cd() {
 	mins = 1 * m('0'); // change minutes here
 	secs = 0 + s(':" . $counter . "'); // change seconds here (always add an additional second to your total)
 	redo();
}

function m(obj) {
 	for(var i = 0; i < obj.length; i++) {
  		if(obj.substring(i, i + 1) == ':')
  		break;
 	}
 	return(obj.substring(0, i));
}

function s(obj) {
 	for(var i = 0; i < obj.length; i++) {
  		if(obj.substring(i, i + 1) == ':')
  		break;
 	}
 	return(obj.substring(i + 1, obj.length));
}

function dis(mins,secs) {
 	var disp;
 	if(mins <= 9) {
  		disp = ' 0';
 	} else {
  		disp = ' ';
 	}
 	disp = '00' + ':';
 	if(secs <= 9) {
  		disp += '0' + secs;
 	} else {
  		disp += secs;
 	}
 	return(disp);
}

function redo() {
 	secs--;
 	if(secs == -1) {
  		secs = 59;
  		mins--;
 	}
 	document.cd.disp.value = dis(mins,secs); // setup additional displays here.
 	if(secs == 0) {
  		window.alert('Time is up. Press OK to continue.'); // change timeout message as required
  	    window.location = 'mobquestion.php' // redirects to specified page once timer ends and ok button is pressed
 	} else {
 		cd = setTimeout('redo()',1000);
 	}
}

function init() {
  cd();
}
window.onload = init;

</script>";
}
		$lastanswered = $pull_q_fetch[0];
		$lastq = "SELECT correct FROM Question WHERE asktime != '0' ORDER BY asktime DESC LIMIT 0, 1";
		$lastq_q = mysql_query($lastq);
		$lastq_q_fetch = mysql_fetch_row($lastq_q);
		if (!$fill_q_fetch)
			{
			if ($lastq_q_fetch)
			{
				echo "The answer to the previous question was:<br/><br/><h1>" . $lastq_q_fetch[0] . "</h1><br/><br/>";
				echo "<br/><a href='teamsin.php'>Display Teams</a><br/><br/><a href='mobster.php'>Admin Home</a>";
			}
			else
			{
				echo "<br/>
				<form method='POST'>
				<input type='submit' value='Start Mob Trivia!'/>
				<input type='hidden' name='activate' value='1'/>
				</form>";
				echo "<br/><a href='teamsin.php'>Display Teams</a><br/><br/><a href='mobster.php'>Admin Home</a>";
			}
			}
		else
			{
				shuffle($answer);
				echo "Question Number " . $number . ": " . $question;
				echo "<br/><br/>" . $answer[0];
				echo "<br/><br/>" . $answer[1];
				echo "<br/><br/>" . $answer[2] . "<br/><br/><form method='POST'>
			<input type='submit' value='Refresh'/><input type='hidden' name='activate' value='0'/>
			</form>";
			echo "<br/><a href='teamsin.php'>Display Teams</a><br/><br/><a href='mobster.php'>Admin Home</a>";
			echo "<form name='cd'>
					<input id='txt' readonly='true' type='text' value='" . (time() - $asktime) . "' border='0' name='disp'>
					</form>";
				}
		
	}
}
?>