<?php
//Typical connection crap
session_start();
include('connect2db.php');

{
echo "
<script type='text/javascript'>
var mins
var secs;

function cd() {
 	mins = 1 * m('0'); // change minutes here
 	secs = 0 + s(':" . $timer . "'); // change seconds here (always add an additional second to your total)
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
  	    window.location = 'mobtrivia.php' // redirects to specified page once timer ends and ok button is pressed
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

//Check if they're logged in, else dump to login page
if(isset($_SESSION['username']))
	{
	if(isset($_POST['answer']))
		{
		$fill = "SELECT correct, number, asktime FROM Question WHERE number='" . $_POST['number'] ."'";
		$fill_q = mysql_query($fill);
		$fill_q_fetch = mysql_fetch_row($fill_q);
		$pull = "SELECT lastanswered FROM Players WHERE name='" . $_SESSION['username'] ."'";
		$pull_q = mysql_query($pull);
		$pull_q_fetch = mysql_fetch_row($pull_q);
		$lastanswered = $pull_q_fetch[0];
		$time = (time() - $timer);
		if ($lastanswered==$_POST['number'])
			{
			echo "Sorry, you can only answer a question once!<br/><br/><form>
			<input type='submit' value='Refresh'/>
			</form>";
			}
		elseif ($lastanswered!=($_POST['number']-1))
			{
			$update = "UPDATE Players SET stillin='0' WHERE name='" . $_SESSION['username'] . "'";
			$update_q = mysql_query($update);
			echo "Sorry, you cannot skip any questions. You unfortunately forfeit. Womp Womp.<br/><br/><img src='saddan.png'/><br/><form>
			<input type='submit' value='Refresh'/>
			</form>";
			}
		elseif ($fill_q_fetch[2]<$time)
		{
			$update = "UPDATE Players SET stillin='0' WHERE name='" . $_SESSION['username'] . "'";
			$update_q = mysql_query($update);
			echo "Sorry, you ran out of time! You unfortunately forfeit. Womp Womp.<br/><br/><img src='saddan.png'/><br/><form>
			<input type='submit' value='Refresh'/>
			</form>";
		}
		else
			{
			$update = "UPDATE Players SET lastanswered='" . $_POST['number'] . "' WHERE name='" . $_SESSION['username'] . "'";
			$update_q = mysql_query($update);
			echo "Thank you for submitting your guess. The correct answer will be displayed soon.<br/><br/><img src='saddan.png'/><br/><form>
			<input type='submit' value='Refresh'/>
			</form>";
			if ($_POST['answer'] == $fill_q_fetch[0])
				{
				$update = "UPDATE Players SET lastcorrect='" . $_POST['number'] . "' WHERE name='" . $_SESSION['username'] . "'";
				$update_q = mysql_query($update);
				}
			else
				{
				$update = "UPDATE Players SET stillin='0' WHERE name='" . $_SESSION['username'] . "'";
				$update_q = mysql_query($update);
				}
			}
	}
	else
		{
		$fill = "SELECT question, correct, wrong1, wrong2, number, asktime FROM Question WHERE current='1'";
		$fill_q = mysql_query($fill);
		$fill_q_fetch = mysql_fetch_row($fill_q);
		$question = $fill_q_fetch[0];
		$answer[0] = $fill_q_fetch[1];
		$answer[1] = $fill_q_fetch[2];
		$answer[2] = $fill_q_fetch[3];
		$number = $fill_q_fetch[4];
		$asktime = $fill_q_fetch[5];
		$pull = "SELECT lastanswered, stillin FROM Players WHERE name='" . $_SESSION['username'] ."'";
		$pull_q = mysql_query($pull);
		$pull_q_fetch = mysql_fetch_row($pull_q);
		$lastanswered = $pull_q_fetch[0];
		if (!$fill_q_fetch || $lastanswered == $number)
			{
			echo "The next question will be displayed soon. Be patient!<br/><br/><img src='saddan.png'/><br/><form>
			<input type='submit' value='Refresh'/>
			</form>";
			}
		else
			{
				if ($pull_q_fetch[1]==0)
				{
					echo "You dun answered a question wrong! You're out! Good luck to the rest of your team!";
				}
				else
				{
					shuffle($answer);
					echo "<form name='answer' method='POST'> Question Number " . $number . ": " . $question;
					echo "<br/><br/><input type='radio' name='answer' value='" . $answer[0] . "' />   " . $answer[0];
					echo "<br/><br/><input type='radio' name='answer' value='" . $answer[1] . "' />   " . $answer[1];
					echo "<br/><br/><input type='radio' name='answer' value='" . $answer[2] . "' />   " . $answer[2];
					echo "<input type='hidden' name='number' value='" . $number . "'/>";
					echo "<br/><br/><input type='submit' value='Answer!'></form>";
					echo "<form name='cd'>
					<input id='txt' readonly='true' type='text' value='00:59' border='0' name='disp'>
					</form>";
				}
			}
		}
	}
else
	{
	if (isset($_POST['username']))
		{
		$_SESSION['username'] = $_POST['username'];
		$fill = "INSERT INTO Players VALUES ('" . $_POST['username'] . "', '" . $_POST['teamname'] . "', '1', '0', '0')";
		$fill_q = mysql_query($fill);
		echo "Logged in as: " . $_SESSION['username'];
		echo "<br><br>Thank you for joining Mob Trivia! The next question will be displayed soon<br/><br/><form name='logged'><input type='submit' value='refresh'/></form>";
		}
	else
		{
		echo "<form name='login' method='POST'>Team Name: <select name='teamname'>
		<option value='Schrodinger Cat'>Schrodinger Cat</option>
		<option value='Kimballers'>Kimballers</option>
		<option value='Cloudy Chloroform'>Cloudy Chloroform</option>
		<option value='Back Sack Box'>Back Sack Box</option>
		
		<option value='Team Loaf'>Team Loaf</option>
		<option value='Touching Michael Jackson'>Touching Michael Jackson</option>
		<option value='Team Stange'>Team Stange</option>
		<option value='Bologna'>Bologna</option>
		
		<option value='Pirates'>Pirates</option>
		<option value='Suck It'>Suck It</option>
		<option value='Tim Tebow'>Tim Tebow</option>
		<option value='Eating Fallopian Lubricant'>Eating Fallopian Lubricant</option>
		
		<option value='Super Squad'>Super Squad</option>
		<option value='This is Barta!'>This is Barta!</option>
		<option value='Smoke Alarm Cooking'>Smoke Alarm Cooking</option>
		<option value='Crazy train'>Crazy train</option>
		
		<option value='Fallen Angels'>Fallen Angels</option>
		<option value='LDSSA'>LDSSA</option>
		</select><br/><br/>Username:
		<input type='text' name='username'/><br/><br/>
		<input type='submit' value='submit'/>
		</form>";
		}
	}
?>