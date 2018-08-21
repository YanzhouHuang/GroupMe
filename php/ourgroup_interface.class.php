<?php

include_once "sqler.class.php";
include_once "ourgroup_window.class.php";

class ourgroup_interface
{

    public function __construct($new = FALSE) {
        // Initialize fields, etc.
        $this->new = $new;
    }

    public function __toString()
    {
    	$mainbody = "
<div id='groupnav' class='groupnav'>
  <a href='javascript:void(0)' class='closebtn' onclick='closeNav()'>&times;</a>
  <button class='groupButton' onclick='open_create_group()'>Create Group</button>
  <button class='groupButton' onclick='open_join_group()'>Join Group</button>
  <div id='group_list'></div>
</div>

<div id='main' >

<div class = 'modulePanel'>
<div class = 'userBlock'><span class='helper'></span>
<button style='float:left;' onclick='openNav()'><img style='width:90px;height:90px;' src='img/group.png'/></button>
<p id='groupinfo'></p>
<img src='img/Lenna.png' style='float:right;width: 90px;height:90px;padding:5px;vertical-align: middle;'/>
<p style='text-align:right;padding:5px;'>
". self::buildUserIdLabel($_SESSION["id_user"], FALSE) ."</br>
". self::getEmailForUserId($_SESSION["id_user"]) ."</br></br></br>
<button class='logoutbutton' onclick='logout();' style='bottom: 30px;'>Log Out</button>
</p>
</div>
</div>

<ul id='moduleNavBar' class='navBar'>
<li><button onclick='LoadChat()'><img id='chatNOTIF' src='img/alert.png' style='width:15px;height:15px;visibility:hidden;'/>Chat<img style='width:15px;'/></button></li>
  <li><button onclick='initializeCalendar()'>Calendar</button></li>
  <li><button onclick='LoadProgressView()'>View Progress</button></li>
  <li><button onclick='LoadSettings()'>Settings</button></li>
</ul>  
<br/>
<div class = 'contentPanel' id = 'content'>
</div>
</div>";

return $mainbody;
    }

    private function buildUserIdLabel() {
        $name = self::getNameForUserId($_SESSION["id_user"], FALSE);
        $container = $name;
        return $container;
    }
    
    public static function getNameForUserId($id, $useYou = TRUE) {
        if ($useYou && $id == $_SESSION["id_user"]) {
            return "You";
        }

        $sqler = new sqler();
        $sqler->sendQuery("Select email from user where id=$id");
        if ($row = $sqler->getRow()) {
            $email = $row["email"];
            $emailPreAt = substr($email, 0, strpos($email, '@'));
            $nameWithSpaces = str_replace(".", " ", $emailPreAt);
            $nameWithoutNumsAndUpCased = ucwords(preg_replace('/[0-9]+/', '', $nameWithSpaces));
            return $nameWithoutNumsAndUpCased;
        }
        else {
            return null;
        }
    }
    public static function getEmailForUserId($id) {
        $sqler = new sqler();
        $sqler->sendQuery("Select email, name from user where id=$id");
        if ($row = $sqler->getRow()) {
            if($row["name"] != "")
            return $row["name"];
            else
            return $row["email"];
        }
        else {
            return null;
        }
    }

    public static function get_group_info(){
if(!(isset($_SESSION["id_user"]) && isset($_SESSION["current_group"])))
    return "";

$userId = $_SESSION["id_user"];
$group = $_SESSION["current_group"];

$sqler = new sqler();

$sqler->sendQuery("SELECT name, joincode FROM Groups WHERE id = $group");
$fin = "";
if ($row = $sqler->getRow()) {

	while ($row) {
        $fin .= "<h4 style='float:left;'>" . $row['name'] . "</h4><h2 style='float:left;'>" . $row['joincode'] . "</h2>";
	$row = $sqler->getRow();
	}

}
return $fin;
}

}

?>