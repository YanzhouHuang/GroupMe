<?php

include_once "../php/sqler.class.php";

session_start();

$day = filter_input(INPUT_POST, "day", FILTER_SANITIZE_NUMBER_INT);
$month = filter_input(INPUT_POST, "month", FILTER_SANITIZE_NUMBER_INT);
$year = filter_input(INPUT_POST, "year", FILTER_SANITIZE_NUMBER_INT);

$userId = $_SESSION["id_user"];

$sqler = new sqler();

$due = sprintf("%04d", $year) . "-" . sprintf("%02d", $month) . "-" . sprintf("%02d", $day);
$due2 = sprintf("%04d", $year).sprintf("%02d", $month).sprintf("%02d", $day);
echo "<a style='text-align:center;display:block;padding:2px;'>" . htmlentities($due) . "</a>";

$today = date('Y-m-d', time() - 2 * 86400);

if(isset($_SESSION["current_group"]))
{
	$groupid = $_SESSION["current_group"];
	$sqler->sendQuery("SELECT *, TIME_FORMAT(time, '%H') as return_hour, TIME_FORMAT(time, '%i') as return_minute FROM Event WHERE datedue = $due2 AND groupid = $groupid;");

	$index = 0;
	if ($row = $sqler->getRow()) {
		while ($row) {
			$tag = $row['name'];
			$img = "";
			$check = 'img/x.png';
			if($row['completion'] > 0) $check = 'img/check.png';
			if($row['type'] == 0) $img = "<img style='background-color:white;padding:2px;width:15px;height:15px;'src='".$check."'/> ";
			$cl = "eventButton";
			if($row['type'] == 1) $cl = "taskButton";
			if($row['type'] == 2) {
				$cl = "meetingButton";
				$phpdate = $row["return_hour"];
				$tag = $phpdate . ":" . $row["return_minute"] . " - " . $tag;
			}
			echo  "<button class='".$cl."' onclick='show_event(\"".$due."\", ".$index.")'>";
			if(strtotime($today) < strtotime($row["dateadded"])) echo "<a style='font-size:10px;background-color:white;padding:2px;margins:2px;'>NEW!</a>|";
			echo $img.$tag."</button>";
			$row = $sqler->getRow();
			$index += 1;
		}
	}
}