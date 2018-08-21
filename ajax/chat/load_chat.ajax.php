<?php

include "../php/sqler.class.php";
include "../php/ourgroup_window.class.php";

session_start();

if(isset($_SESSION['current_group']))
echo new ourgroup_window();
else
echo "Select or Create a Group";