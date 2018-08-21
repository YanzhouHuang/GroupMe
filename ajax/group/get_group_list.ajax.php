<?php

include_once "../php/sqler.class.php";

session_start();

$userId = $_SESSION["id_user"];

$sqler = new sqler();

$sqler->sendQuery("SELECT Groups.name FROM Groups, UserGroup
WHERE UserGroup.groupid = Groups.id AND UserGroup.userid = $userId;");
$i = 0;
if ($row = $sqler->getRow()) {
	while ($row) {
        echo  "<li style='color:white;font-size:24px;width:100%;height:40px;background-color:#303030;list-style-type: none;'><a onclick='change_group(".$i.")'>".$row['name']."</a></li>";
        $i += 1;
	$row = $sqler->getRow();
	}
}