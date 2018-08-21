<?php
// returns 0 if fails 1 if succeeds and 2 if user is already in group

include "../php/sqler.class.php";

session_start();

$sqler = new sqler();

$userid = $_SESSION["id_user"];
$joincode = filter_input(INPUT_POST, "joincode", FILTER_SANITIZE_STRING);

$sqler->sendQuery("SELECT * FROM Groups WHERE Groups.joincode = '$joincode'");
$group = $sqler->getRow();

// make sure user doesn't already belong to the group then add them
$sqler->sendQuery("SELECT * FROM UserGroup where userid = '$joincode' AND groupid = " . $group["id"]);
if ($sqler->getRow() == null){
// now have the group
if(!$stmt = $sqler->con->prepare("INSERT INTO UserGroup (userid, groupid) VALUES (?,?)"));
if(!$stmt->bind_param("ss", $userid, $group["id"]));
if($stmt->execute()) echo 1; else {$stmt->close(); echo 0;};
} else {
echo 2;
}