<?php


class Conversation_Box
{
    protected $id;
    protected $allParticipants;
    protected $selected;

    // Creates the div for this conversation to be added to the conversation history
    // Note: $allParticipants includes the creator
    public function __construct($id, $allParticipants, $selected = FALSE)
    {
        $this->id = $id;
        $this->allParticipants = $allParticipants;
        $this->selected = $selected;
        return $this->__toString();
    }

    public function __toString()
    {
        return $this->buildConversationBox();
    }

    private function buildConversationBox() {
        $allParticipantsAsString = implode(", ", $this->allParticipants);
        if ($this->selected) {
            $container = "<div class='conversation_box selected_conversation' onclick='selectConversation(this);' data-id=$this->id><p>$allParticipantsAsString</p></div>";
        }
        else {
            $container = "<div class='conversation_box' onclick='selectConversation(this);' data-id=$this->id><p>$allParticipantsAsString</p></div>";
        }
        return $container;
    }

}