<?php

include_once "../php/sqler.class.php";

session_start();

$day     = filter_input(INPUT_POST, "date", FILTER_SANITIZE_NUMBER_INT);
$index   = filter_input(INPUT_POST, "index", FILTER_SANITIZE_NUMBER_INT);
$groupid = $_SESSION["current_group"];

$sqler = new sqler();

echo "<h2>" . $day . " - ";

if (!$stmt = $sqler->con->prepare("SELECT id, name, description, type, completion, location FROM Event WHERE datedue = ? AND groupid = ?")) {
    echo "Prepare fail (" . $sqler->con->errno . ") " . $sqler->con->error;
}
if (!$stmt->bind_param("ss", $day, $groupid)) {
    echo "Bind fail (" . $stmt->errno . ") " . $stmt->error;
}
if ($stmt->execute()) {
	
    $stmt->bind_result($id, $name, $description, $type, $completion, $location);
	
    $i = 0;
    while ($stmt->fetch()) {
		if($i == $index){
    
    			switch($type){
    			case 0: echo "Task"; break;
    			case 1: echo "Event"; break;
    			case 2: echo "Meeting"; break;
    			}	
    
    			echo "</h2>";
			echo "<p style='text-align:left;'><h2>Title: " . $name."</h2>";
			
			echo "<p style = 'text-align:left;border-color:lightgrey;border-width:1px;border-style:solid;min-height:100px;margins:5px;padding:5px;'>" . $description . "</p>";
			
			// meetings display members and time
			if($type == 2){
				echo "<p><b>Meeting Location:</b> " . $location . "</p>";
			}
			
			// Task displays completion
			if($type == 0){
				echo "<input id = 'completeCheck' class='completionCheckbox' style='transform: scale(1.5);' onChange='updateEventCompletion(".$id.")' type='checkbox' ".($completion > 0 ? "checked = 'true'" : "").">";
			}
			
			// Event displays ??
			
			echo "</p>";
			break;
		}
        	$i += 1;
    	}
	$stmt->close();
	
	// meetings display members and time
	if($type == 2){
			
		echo "<p style='text-align:left;'>Members for Meeting:<br/>";
			
		$stmt2 = $sqler->con->prepare("SELECT user.name FROM UserEvent, user WHERE UserEvent.eventid = ? AND UserEvent.userid = user.id;");
		$stmt2->bind_param("s", $id);
		$stmt2->execute();
		$stmt2->bind_result($name);
		while ($stmt2->fetch()) {
			echo "<img src='img/Lenna.png' style='width:15px;height:15px;'/>" . $name . "<br/>";
		}
		$stmt2->close();
		echo "</p>";
	}
	
} else {
    $error = "Execute fail (" . $stmt->errno . ") " . $stmt->error; // Print the error
    $stmt->close();
    echo $error;
}

