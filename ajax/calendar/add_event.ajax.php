<?php

include "../php/sqler.class.php";

session_start();

$sqler = new sqler();

$day = filter_input(INPUT_POST, "day", FILTER_SANITIZE_NUMBER_INT);
$month = filter_input(INPUT_POST, "month", FILTER_SANITIZE_NUMBER_INT);
$year = filter_input(INPUT_POST, "year", FILTER_SANITIZE_NUMBER_INT);

$type = filter_input(INPUT_POST, "type", FILTER_SANITIZE_STRING);
$groupid = $_SESSION["current_group"];
$name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_STRING);
$des = filter_input(INPUT_POST, "des", FILTER_SANITIZE_STRING);
$date = date("Y-m-d H:i:s");
$due = sprintf("%04d", $year) . "-" . sprintf("%02d", $month) . "-" . sprintf("%02d", $day);

$loc = $_POST["location"];

$datedue = DateTime::createFromFormat('m/d/Y', $_POST["datedue"]);
$output = $datedue->format('Y-m-d');

parse_str($_POST['members'], $values);

$eventnum = -1;
if($type == "Task") $eventnum = 0;
if($type == "Event") $eventnum = 1;
if($type == "Meeting") $eventnum = 2;

if(!($type == "Task" || $type == "Event" || $type == "Meeting"))
	echo "Invalid Event Type " + $type;
else
if($loc == "" && $type == "Meeting")
	echo "Meetings must have a location!";
else
if($name == "")
	echo "Missing event title";
else
{
	//TODO: use a transaction for this
	if(!$stmt = $sqler->con->prepare("INSERT INTO Event (name, description, groupid, dateadded, datedue, type, location) VALUES (?,?,?,?,?,?,?)"))
    {
        echo "Prepare fail (" . $sqler->con->errno . ") " . $sqler->con->error;
    }

    if(!$stmt->bind_param("sssssss", $name, $des, $groupid, $date, $output, $eventnum, $loc))
    {
        echo "Bind fail (" . $stmt->errno . ") " . $stmt->error;
    }

    if($stmt->execute())
    {
    		// link the users to this event
		$eventid = mysqli_insert_id($sqler->con);
		foreach($values as $r) {
			if(count($r) == 0){
				$stmt = $sqler->con->prepare("INSERT INTO UserEvent (eventid, userid) VALUES (?,?)");
				$stmt->bind_param("ss", $eventid, $_SESSION["id_user"]);
				$stmt->execute();
			}
			foreach($r as $v) {
				$stmt = $sqler->con->prepare("INSERT INTO UserEvent (eventid, userid) VALUES (?,?)");
				$stmt->bind_param("ss", $eventid, $v);
				$stmt->execute();
			}
		}
		$stmt->close();
    }
    else
    {
        $error = "Execute fail (" . $stmt->errno . ") " . $stmt->error; // Print the error
        $stmt->close();
        echo $error;
    }
}