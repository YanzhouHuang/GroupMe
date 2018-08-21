<?php

include "../php/sqler.class.php";

session_start();

$sqler = new sqler();

$email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

$hashedPass = $sqler->hashPass($password);

$sqler->sendQuery("Select id from user where user.email='$email' and user.password='$hashedPass'");

if($row = $sqler->getRow())
{
    $_SESSION['id_user'] = $row['id'];
    $_SESSION['email_user'] = $email;
    echo 1;
}
else
{
    echo 0; // No such user or bad password
}
