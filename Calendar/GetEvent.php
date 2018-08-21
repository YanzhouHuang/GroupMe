<?php
$day = $_GET['day'];
$month = $_GET['month'];
$year = $_GET['year'];

$con = mysqli_connect('localhost', 'id734527_main', 'password', 'id734527_ourgroup'); 
// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$due = sprintf("%04d", $year) . "-" . sprintf("%02d", $month) . "-" . sprintf("%02d", $day);
echo $due;
$stmt = $con->prepare("SELECT * FROM Event WHERE datedue = ?");

$stmt->bind_param("s", $due);

$stmt->execute();

$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) 
{
        echo  "<button class='eventButton' onclick=''>".$row['name']."</button>";
}

$stmt->close();
$con->close();
?>