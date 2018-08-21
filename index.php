<?php

spl_autoload_register(function ($class) {
    include 'php/' . $class . '.class.php';
});

require_once "recaptchalib.php";

session_start();
//<meta content='width=device-width, initial-scale=1' name='viewport'/>

$head = "<head><meta content='width=device-width, initial-scale=1' name='viewport'/>
            <link rel='stylesheet' type='text/css' href='css/main.css'>
            <link rel='stylesheet' type='text/css' href='Calendar/styles.css'>
            <script type='text/javascript' src='js/jquery-latest.js'></script>
            <script type='text/javascript' src='js/main.js'></script>
            <script type='text/javascript' src='Calendar/calendar.js'></script>
            <script type='text/javascript' src='js/FileShare.js'></script>
            <title>OurGroup</title>

<!-- Latest compiled and minified JavaScript -->
<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js' integrity='sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa' crossorigin='anonymous'></script>
<script src='//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js'></script>
<link rel='stylesheet' type='text/css' href='http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css'/>
<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
 </head>";

if (isset($_SESSION['id_user'])) {
    $body = "<body>" . new ourgroup_interface() .
            "</body>";
}
else {
	$body = "<body>


		<img src='img/sprite0_logo.png' style='
    display: block;
    margin: 0 auto;
    vertical-align:middle;'/>
		<div class='authenticate_container'>
			<div id='login_container'>
			<table style='width:100%;text-align:left;'>
			<tr style='text-align:center;'>
				<th colspan=2><p>Login</p></th>
				<th></th> 
			</tr>
			<tr>
				<th><label id='login_email_label'>Email:</label></th>
				<th><input id='login_email' type='text'></th> 
			</tr>
			<tr>
				<th><label id='login_pass_label'>Password:</label></th>
				<th><input id='login_password' type='password'></br></th> 
			</tr>
			<tr>
				<th colspan=2><p id='invalid_login_message'></p></th>
				<th></th> 
			</tr>
			<tr>
				<th><button id='login_button' type='button' onclick='login();'>Login</button></p></th>
				<th></th> 
			</tr>
			</table>
                        </div>

                    	<div id='register_container'>
                        <table style='text-align:left;'>
			<tr style='width:50%;text-align:center;'>
				<th colspan=2><p>Register</p></th>
			</tr>
			<tr>
				<th><label id='register_name_label'>Name:</label></th>
				<th><input id='register_name' type='text'></th> 
			</tr>
			<tr>
				<th><label id='register_user_label'>Email:</label></th>
				<th><input id='register_email' type='text'></th> 
			</tr>
			<tr>
				<th><label id='register_pass_label'>Password:</label></th>
				<th><input id='register_password' type='password'></th> 
			</tr>
			<tr>
				<th><label id='confirm_label'>Confirm Password:</label></th>
				<th><input id='confirm_password' type='password'/></th> 
			</tr>
			<tr>
				<th colspan=2><p id='invalid_registration_message'></p></th>
			</tr>
			<tr>
				<th colspan=2></th>
			</tr>
			<tr>
				<th colspan=2><button id='register_button' type='button' onclick='register();'>Register</button></th>
			</tr>
			</table><br/><br/><br/><br/><br/><br/><div style='' class='g-recaptcha' data-sitekey='6LeGVR4UAAAAAM49xeJs-Uo8LE_MSWgCAZGJYYaf' style='align:bottom;'></div>
                        </div>
                </div><script src='https://www.google.com/recaptcha/api.js'></script></body>";
}



echo $head . $body;
?>