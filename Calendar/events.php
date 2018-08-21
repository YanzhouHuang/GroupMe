<?php
//$q = intval($_GET['q']);
//mysqli_report(MYSQLI_REPORT_ALL);

$con = mysqli_connect('localhost', 'id734527_main', 'password', 'id734527_ourgroup'); 
// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$stmt = $con->prepare("SELECT * FROM User");

//if($stmt == FALSE)
//echo "Error";

$stmt->execute();

$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) 
{
        echo "id: " . $row["name"];
}

$stmt->close();
$con->close();
?>