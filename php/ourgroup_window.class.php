<?php

include_once "sqler.class.php";


class ourgroup_window
{
    // For maintaining this user's conversations
    protected $currentUsersConversationIds;
    protected $new;

    public function __construct($new = FALSE) {
        // Initialize fields, etc.
        $this->new = $new;
    }

    public function __toString()
    {
        return $this->buildChatWindow(); 
    }

    private function buildChatWindow() {
        $sendButton = "<button id='send_button' onclick='sendMessage()'>Send</button>";
        $messageBox = "<textarea id='message_box'></textarea>";
        $replyContainer = "<div id='reply_container'>" . $messageBox . $sendButton . "</div>";

        if (!$this->new && isset($_SESSION["current_group"])) {
            $messages = self::loadMessagesForCurrentgroup($_SESSION["current_group"]);
            $container = "<div id='chat_window'>" . $messages . "</div>";
            return $container . $replyContainer;
        }
        else {
            $container = "<div id='chat_window'></div>";
            return $container . $replyContainer;
        }
    }

   

    private function buildLogoutButton() {
        $logoutButton = "<button id='logout_button' onclick='logout();'><p>Logout</p></button>";
        return $logoutButton;
    }

    private function buildUserIdLabel() {
        $name = self::getNameForUserId($_SESSION["id_user"], FALSE);

        $container = "<div title='Current User is $name' id='user_id_label'>";
        $container .= "<p id='user_name_label'>$name</p>";
        $container .= "<p id='user_email_label'>" . self::getEmailForUserId($_SESSION["id_user"], FALSE) . "</p>";
        $container .= "</div>";
        return $container;
    }

    public static function getNameForUserId($id, $useYou = TRUE) {
        if ($useYou && $id == $_SESSION["id_user"]) {
            return "You";
        }

        $sqler = new sqler();
        $sqler->sendQuery("Select name from user where id=$id");
        if ($row = $sqler->getRow()) {
            $name = $row["name"];
      
            return $name;
        }
        else {
            return null;
        }
    }

    public static function loadMessagesForCurrentgroup($id) {
        $sqler = new sqler();
        $groupid = $_SESSION["current_group"];
        $sqler->sendQuery("Select * from message where group_id = $groupid");
        $messages = [];
        while ($row = $sqler->getRow()) {
            $contents = $row["contents"];
			$timestamps = $row["timestamp"];
            $id = $row["id"];
            if ($row["sender"] == $_SESSION["id_user"]) {
                $messages[] = "<div data-id=$id class='message message_from_self'>
				<p>$contents</p>
				<span style=' color: blue; font-size: 5pt; '>" . $timestamps . " </span></div>";
            }
            else {
                $messages[] = "<div data-id=$id class='message message_from_other'><p><span style=' color: #60c5bb; font-size: 12pt; font-style: italic;'>" . self::getNameForUserId($row["sender"]) . " </span>:</p>
				<p>$contents</p>
				<span style=' color: blue; font-size: 5pt; '>" . $timestamps . " </span></div>";
            }
        }
        return implode("", $messages);
    }

    public static function getEmailForUserId($id) {
        $sqler = new sqler();
        $sqler->sendQuery("Select email from user where id=$id");
        if ($row = $sqler->getRow()) {
            return $row["email"];
        }
        else {
            return null;
        }
    }

  
}