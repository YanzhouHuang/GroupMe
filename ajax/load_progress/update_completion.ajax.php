<?php

include "../php/sqler.class.php";

session_start();

$sqler = new sqler();

if(isset($_SESSION["current_group"]))
{
	$eid = filter_input(INPUT_POST, "eid", FILTER_SANITIZE_NUMBER_INT);
	$check = $_POST["checked"];
	$groupid = $_SESSION["current_group"];
	
	if(!$stmt = $sqler->con->prepare("UPDATE Event SET completion = ? WHERE groupid = ? AND id = ?;"))
    {
        echo "Prepare fail (" . $sqler->con->errno . ") " . $sqler->con->error;
    }
	
	$complete = $check == "true" ? 1 : 0;

    if(!$stmt->bind_param("sss", $complete, $groupid, $eid))
    {
        echo "Bind fail (" . $stmt->errno . ") " . $stmt->error;
    }

    if($stmt->execute())
    {
    	$stmt->close();
    }
    else
    {
        $error = "Execute fail (" . $stmt->errno . ") " . $stmt->error; // Print the error
        $stmt->close();
        echo $error;
    }
}