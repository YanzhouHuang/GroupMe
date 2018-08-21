<?php

include_once "../php/sqler.class.php";

session_start();

$sqler = new sqler();

$userId = $_SESSION["id_user"];
$groupid = $_SESSION["current_group"];

if(isset($_SESSION["current_group"]))
{
$sqler->sendQuery("SELECT user.id, user.email, user.Name FROM UserGroup, user
WHERE UserGroup.groupid = $groupid AND UserGroup.userid = user.id");
echo "<div class='groupcheck'>";
if ($row = $sqler->getRow()) {
	while ($row) {
		echo "<input type='checkbox' class='usercb' name='usercb[]' value='".$row["id"]."'/> <img src='img/Lenna.png' style='width:25px;height;25px;'/>".htmlentities($row["Name"])."<br/>";
		$row = $sqler->getRow(); 
	}
}
echo "</div>";
}