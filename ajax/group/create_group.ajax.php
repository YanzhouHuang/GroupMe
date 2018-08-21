<?php

include "../php/sqler.class.php";

session_start();

$sqler = new sqler();

$name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_STRING);
$des = filter_input(INPUT_POST, "des", FILTER_SANITIZE_STRING);
$date = date("Y-m-d H:i:s");

// create join code
$join1 = crc32($name);
$join2 = crc32($des);

$fin = $join1 | $join2;

$fin = (($fin << 8) * rand(15, 255)) | rand(15, 255);

$fin = dechex($fin & 0xFFFFFF);

$sqler->sendQuery("SELECT * FROM Groups where Groups.joincode = $fin");
while($row = $sqler->getRow()){
echo "exists";
}

if(!$stmt = $sqler->con->prepare("INSERT INTO Groups (name, description, joincode, datecreated) VALUES (?,?,?,?)"))
    {
        echo "Prepare fail (" . $sqler->con->errno . ") " . $sqler->con->error;
    }


    if(!$stmt->bind_param("ssss", $name, $des, $fin, $date))
    {
        echo "Bind fail (" . $stmt->errno . ") " . $stmt->error;
    }

    if($stmt->execute())
    {
        $_SESSION['current_group'] = mysqli_insert_id($sqler->con);
$stmt = $sqler->con->prepare("INSERT INTO UserGroup (userid, groupid) VALUES (?,?)");
$stmt->bind_param("ss", $_SESSION['id_user'], $_SESSION['current_group']);
$stmt->execute();
        echo 1; // Success
    }
    else
    {
        $error = "Execute fail (" . $stmt->errno . ") " . $stmt->error; // Print the error
        $stmt->close();
        echo $error;
    }