<?php


include_once "../php/sqler.class.php";
include_once "../php/ourgroup_window.class.php";

session_start();

$lastMessageId = filter_input(INPUT_POST, "lastMessageId", FILTER_SANITIZE_NUMBER_INT);
$userId = $_SESSION["id_user"];
$groupid = $_SESSION["current_group"];

$sqler = new sqler();

if (!isset($lastMessageId) || is_null($lastMessageId) || $lastMessageId == 0) {
    $sqler->sendQuery("Select `timestamp` from message where message.group_id=$groupid");
}
else {
    $sqler->sendQuery("Select `timestamp` from message where message.id=$lastMessageId and message.group_id=$groupid");
}

if ($row = $sqler->getRow()) {
    $timestamp = $row["timestamp"];
    $sqler->sendQuery("Select * from message where message.timestamp > '" . $timestamp . "' and message.group_id=$groupid");
    $messagesAppear = [];
    while ($row = $sqler->getRow()) {
        $contents = $row["contents"];
		$messageId = $row["id"];
        if (intval($row["sender"]) == $userId) {
            $messagesAppear[] = "<div data-id=$messageId class='message message_from_self'>
			<p>$contents</p>
			<span style=' color: blue; font-size: 5pt; '>" . $timestamp . " </span></div>";
        }
        else {
            $messagesAppear[] = "<div data-id=$messageId class='message message_from_other'><span style=' color: #60c5bb; font-size: 12pt; font-style: italic;'>" . ourgroup_window::getNameForUserId($row["sender"]) . "</span> : 
							<p>$contents</p>
				<span style=' color: blue; font-size: 5pt; '>" . $timestamp . " </span></div>";
		}
    }
    echo implode("", $messagesAppear);
}
else {
    echo 0;
}