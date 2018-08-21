<?php

class Login_Window
{
    public function __toString() {
        return $this->buildDisplay();
    }

    public function buildDisplay() {
        return "<div class='authenticate_container'>
                    <img id='logo' src='res/topper_chat_logo.png'></br>
                    <div id='register_container'>
                        <p>Register</p></br>
                        <label id='register_user_label'>Email:</label>
                        <input id='register_email' type='text'></br>
                        <label id='register_pass_label'>Password:</label>
                        <input id='register_password' type='password'></br>
                        <label id='confirm_label'>Confirm Password:</label>
                        <input id='confirm_password' type='password'></br>
                        <p id='invalid_registration_message'></p>
                        <button id='register_button' type='button' onclick='register();'>Register</button>
                    </div>
                    <div id='login_container'>
                        <p>Login</p></br>
                        <label id='login_email_label'>Email:</label>
                        <input id='login_email' type='text'></br>
                        <label id='login_pass_label'>Password:</label>
                        <input id='login_password' type='password'></br>
                        <p id='invalid_login_message'></p>
                        <button id='login_button' type='button' onclick='login();'>Login</button>
                    </div>
                </div>";
    }
}

?>
