<?php

$name = $_GET['name'];
$des = $_GET['des'];
$day = $_GET['day'];
$month = $_GET['month']+1;
$year = $_GET['year'];

$groupid = $_SESSION["current_group"];

$con = mysqli_connect('localhost', 'id734527_main', 'password', 'id734527_ourgroup'); 
// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$stmt = $con->prepare("INSERT INTO Event(name, description, dateadded, datedue, groupid) VALUES(?, ?, ?, ?, ?)");

$dat = date("Y-m-d H:i:s");
$due = sprintf("%04d", $year) . "-" . sprintf("%02d", $month) . "-" . sprintf("%02d", $day);

$stmt->bind_param("sssss", $name, $des, $dat, $due, $groupid);

$stmt->execute();

//$result = $stmt->get_result();
//while ($row = $result->fetch_assoc()) 
//{
//        echo "id: " . $row["name"];
//}

//$stmt->close();
$con->close();
?>