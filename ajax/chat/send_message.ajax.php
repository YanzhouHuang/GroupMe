<?php


include "../php/sqler.class.php";
include "../php/ourgroup_window.class.php";

session_start();

$sqler = new sqler();

$text = filter_input(INPUT_POST, "text", FILTER_SANITIZE_STRING);
$groupId = null;

if (isset($_SESSION["current group"])) {
    $groupId = $_SESSION["current_group"];
}


$reces = filter_input(INPUT_POST, "reces", FILTER_SANITIZE_STRING);
$recesArray = explode("; ", $reces);

$userId = $_SESSION["id_user"];

// Check the quality of each of the recipients and echo the issues to be alerted to the user via JS
// Also get the user ids that match each of the emails
$count = 1;
$receid = array_fill(0, 5, null);
foreach ($recesArray as $reces) {
    // Check for bad recipients (not in the database)
    $sqler->sendQuery("Select id from user where user.email='$reces'");
    $row = $sqler->getRow();
    if ($row["id"] == $_SESSION["id_user"] && $conversationId == null) {
        echo "You cannot send a message to yourself! Please remove your email address from the recipients box.";
        return;
    }
    else {
        $receid[$count-1] = $row["id"];
    }
    $count++;
}
$receid = array_values($receid);

    if(!$stmt = $sqler->con->prepare("INSERT INTO message (group_id, sender, contents) VALUES (?,?,?)"))
    {
        echo "Prepare fail (" . $sqler->con->errno . ") " . $sqler->con->error;
    }

    if(!$stmt->bind_param("iis", $_SESSION["current_group"], $userId, $text))
    {
        echo "Bind fail (" . $stmt->errno . ") " . $stmt->error;
    }
    if($stmt->execute())
    {
        $messageId = $stmt->insert_id;
        $stmt->close();
        echo json_encode(["messageId" => $messageId]); // Success
    }
    else {
        $error = "Execute fail (" . $stmt->errno . ") " . $stmt->error; // Print the error
        $stmt->close();
        echo $error;
    }


   