<?php

include "../php/sqler.class.php";
require_once "../recaptchalib.php";

// validate captcha
$secret = "6LeGVR4UAAAAAFAChxIE8zYkYYlWz89VENG4bfs9";
$response = null;
$reCaptcha = new ReCaptcha($secret);
$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=" . $_POST["grecaptcharesponse"] . "&remoteip=" . $_SERVER['REMOTE_ADDR']);
$decoded_response = json_decode($response);


if ($decoded_response->success) {
session_start();

$sqler = new sqler();

$name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

$sqler->sendQuery("Select id from user where user.email='$email'");

// Duplicate email so refuse the registration
if ($sqler->getRow()) {
    echo 0;
}
else {
    if(!$stmt = $sqler->con->prepare("INSERT INTO user (email, password, name) VALUES (?,?,?)"))
    {
        echo "Prepare fail (" . $sqler->con->errno . ") " . $sqler->con->error;
    }


    if(!$stmt->bind_param("sss", $email, $password, $name))
    {
        echo "Bind fail (" . $stmt->errno . ") " . $stmt->error;
    }

    $password = $sqler->hashPass($password);

    if($stmt->execute())
    {
        echo 1; // Success
    }
    else
    {
        $error = "Execute fail (" . $stmt->errno . ") " . $stmt->error; // Print the error
        $stmt->close();
        echo $error;
    }
}

}  else {// captcha fail
echo 2;
}