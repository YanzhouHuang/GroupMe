<?php

include "../php/sqler.class.php";
include "../php/fileshare_window.class.php";

session_start();

if(isset($_SESSION['current_group']))
echo new fileshare_window();
else
echo "Select or Create a Group";