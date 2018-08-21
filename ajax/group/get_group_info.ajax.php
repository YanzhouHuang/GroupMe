<?php

include_once "../php/sqler.class.php";

session_start();

$sqler = new sqler();

$userId = $_SESSION["id_user"];

if(isset($_POST["index"]))
{
// change current group section
$sqler->sendQuery("SELECT Groups.id FROM Groups, UserGroup
WHERE UserGroup.groupid = Groups.id AND UserGroup.userid = $userId;");
$i = 0;
if ($row = $sqler->getRow()) {
	while ($row) {
	if($i == $_POST["index"]) $_SESSION["current_group"] = $row["id"];
        $row = $sqler->getRow(); 
	$i += 1;
	}
}
}
if(isset($_SESSION["current_group"])){
$group = $_SESSION["current_group"];

$sqler->sendQuery("SELECT name, joincode FROM Groups WHERE id = $group");
if ($row = $sqler->getRow()) {
	while ($row) {
        echo "<div style='float:left;'><h2>" . $row['name'] . "</h2><h4>" . $row['joincode'] . "</h4></div>";
	$row = $sqler->getRow();
	}
}
}else
{
	echo "Select or Create a Group";
}