<?php
include "../php/sqler.class.php";

session_start();

$sqler = new sqler();

if(isset($_SESSION['current_group']))
{
	$groupid = $_SESSION['current_group'];
	
	// DISPLAY TOTAL PROGRESS
	$stmt2 = $sqler->con->prepare("SELECT name, completion, datedue FROM Event WHERE groupid = ? AND type = 0;");
	$stmt2->bind_param("s", $groupid);
	$stmt2->execute();
	$stmt2->bind_result($eventname, $comp, $date);
	$total = 0;
	$complete = 0;
	
	$notcomplete = array();
	while ($stmt2->fetch()) {
		$total += 1;
		$complete += ($comp > 0 ? 1 : 0);
		if($comp <= 0) array_push($notcomplete, $eventname . " - " . $date);
	}
	$stmt2->close();
	
	$totalComplete = ($total > 0) ? (($complete / $total)*100) : 0;
	echo "<div style='margins:20px;padding:20px;'>";
	echo "<h2>Current Total Group Progress ".round($totalComplete)."%</h2>";
	echo "<div class='progress'><div class='progress-bar' role='progressbar' aria-valuenow='".$totalComplete."' aria-valuemin='0' aria-valuemax='100' style='width:".$totalComplete."%;'><span class='sr-only'>".$totalComplete."% Complete</span></div></div>";
	
	// not completed tasks
	echo "<h2>Pending Tasks:</h2><br/><div style='max-height:200px;overflow-y: scroll;'>";
	foreach ($notcomplete as $value) echo "<span style='padding:3px;background-color:red;color:white;width:100%;'>" . htmlentities($value) . "</span><br/>";
	echo "</div>";
	
	echo "</div>";
	
	
	// DISPLAY PROGRESS FOR CURRENT USER
	
	
	// DISPLAY PROGESS FOR OTHER USERS
	
	
}
else
echo "Select or Create a Group";