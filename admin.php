<?php
session_start(); 

if($_SESSION['logged']==1)

{
$fill = "SELECT question, correct, wrong1, wrong2, number FROM Question WHERE complete='0' ORDER BY number";
$fill_q = mysql_query($fill);
$fill_q_fetch = mysql_fetch_row($fill_q);
}

?>