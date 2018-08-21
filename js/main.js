var searchTimer = null; // the timer to allow a delay between the input and database queries for searching
var searching = false; // boolean maintaining the state of the search

var messageRequestTimer = null; // the timer to allow a delay between each message request
var conversationRequestTimer = null; // the timer to allow a delay between each conversation request

// Load these functions on page load
$(document).ready(function() {
    loadAllBackgroundFunctions();
});

// Loads all the background functions
function loadAllBackgroundFunctions() {
    loadEnterListener();
    //if ($("#chat_window").length != 0) {
    loadMessageRequester();
    //}
    //  if ($("#conversations_list").length != 0) {
    //    loadConversationRequester();
    //}
	change_group(-1);
}

// Load the 'enter' listener and specify the handler
function loadEnterListener() {
    // Catch keypresses to allow quick input interactions
    $("textarea, input").keypress(function(event) {
        // Catch enter key presses
        if (event.which == 13) {
            // Prevent the default mechanism
            event.preventDefault();
            console.log("Caught 'enter' keypress event!");
            // Get the focused input
            var focused = $(':focus');
            // If one exists
            if ($(focused).length != 0) {
                console.log("Found a nearby button and clicked it...");
                // Click the closest button
                $(focused).siblings("button").click();
            }
        }
    });
}


// Loads the function for repeatedly requesting the latest messages
var prevsize = -1;
function loadMessageRequester() {
    //console.log("Loading message requester!");
    if (messageRequestTimer != null) {
        clearInterval(messageRequestTimer);
        messageRequestTimer = null;
    }
    messageRequestTimer = setInterval(function() {
        //console.log("Checking for new messages!");
        var lastotherMessageId = parseInt($(".message_from_other").last().attr("data-id"));
        var lastmyMessageId = parseInt($(".message_from_self").last().attr("data-id"));
        var lastForeignMessageId = lastotherMessageId > lastmyMessageId ? lastotherMessageId : lastmyMessageId;

        $.ajax({
            url: 'ajax/chat/get_latest_messages.ajax.php',
            type: 'POST',
            data: {
                AJAX: true,
                lastForeignMessageId: lastForeignMessageId
            },
            success: function(data) {
                if (data != 0) {
                    console.log("Retrieved latest message successfully!");
                    $("#chat_window").append(data);
                    var objDiv = document.getElementById("chat_window");
                    if(prevsize == -1) prevsize = data.length;
                    if(objDiv != null)
                    	objDiv.scrollTop = objDiv.scrollHeight;
                    else {
                    	 //Notification
                    	 if(data.length > prevsize){
                    	 	document.getElementById("chatNOTIF").style.visibility = 'visible';
                    	 	prevsize = data.length;
                    	 }
                    }
                } else {
                    console.log("Error with retrieving latest message!" + data);
                }
            },
            error: function() {
                console.log("Error with retrieving latest messages!!");
            }
        });
    }, 2000);
}



// Try to log in with the login form data
function login() {
    var email = $("#login_email").val();
    var password = $("#login_password").val();

    clearInvalidInputNotifiers();

    if (email.length === 0) {
        $("#login_email_label").css({
            'color': 'red'
        });
        $("#invalid_login_message").text("Please enter your email!");
        return;
    }

    if (password.length === 0) {
        $("#login_pass_label").css({
            'color': 'red'
        });
        $("#invalid_login_message").text("Please enter your password!");
        return;
    }

    $.ajax({
        url: 'ajax/auth/login.ajax.php',
        type: 'POST',
        data: {
            AJAX: true,
            email: (email),
            password: (password)
        },
        success: function(data) {
            if (data == 1) {
                console.log("Login successful!");
                window.location.reload();
            } else {
                $("#login_email_label").css({
                    'color': 'red'
                });
                $("#login_pass_label").css({
                    'color': 'red'
                });
                $("#invalid_login_message").text("Invalid account or password!");
            }
        },
        error: function() {
            console.log("Error with login!");
        }
    });
}

// Try to register with the register form data
function register() {
    var name = $("#register_name").val();
    var email = $("#register_email").val();
    var password = $("#register_password").val();
    var confirmPass = $("#confirm_password").val();

    clearInvalidInputNotifiers();

    if (email.length === 0) {
        $("#register_email_label").css({
            'color': 'red'
        });
        $("#invalid_registration_message").text("Your email cannot be empty!");
        return;
    }

    if (!(email.includes(".com") || email.includes(".cn"))) {
        $("#register_email_label").css({
            'color': 'red'
        });
        $("#invalid_registration_message").text("Your should enter email to your account!");
        return;
    }

    if (password.length === 0) {
        $("#register_pass_label").css({
            'color': 'red'
        });
        $("#invalid_registration_message").text("Your password cannot be empty!");
        return;
    }

    if (password.length < 6) {
        $("#register_pass_label").css({
            'color': 'red'
        });
        $("#invalid_registration_message").text("Your password should not short than 6 characters");
        return;
    }

    if (password !== confirmPass) {
        $("#register_pass_label").css({
            'color': 'red'
        });
        $("#confirm_label").css({
            'color': 'red'
        });
        $("#invalid_registration_message").text("Your passwords do not match!");
        return;
    }
    var cap = grecaptcha.getResponse();
    $.ajax({
        url: 'ajax/auth/register.ajax.php',
        type: 'POST',
        data: {
            AJAX: true,
            email: (email),
            password: (password),
            grecaptcharesponse: (cap),
            name: (name)
        },
        success: function(data) {
            if (data == 1) {
                console.log("Successful login!");
                alert("Registration successful! You may now login.");
                $("#register_email").val(""); // Clear the register email input
                $("#register_password").val(""); // Clear the register password input
                $("#confirm_password").val(""); // Clear the confirm password input
                $("#login_email").val(email); // Set the login email input to the registered input
            } else if (data == 2) {
                console.log("Error: " + data);
                $("#register_email_label").css({
                    'color': 'red'
                });
                $("#invalid_registration_message").text("Captcha verify fail");
            } else {
                console.log("Error: " + data); // For debugging issues with the query
                $("#register_email_label").css({
                    'color': 'red'
                });
                $("#invalid_registration_message").text("This email has already been taken!");
            }
        },
        error: function() {
            console.log("Error with registering!");
        }
    });
}


// Clear all invalid input messages and label color changes
function clearInvalidInputNotifiers() {
    $("label").css({
        'color': 'black'
    });
    $("#invalid_registration_message").text("");
    $("#invalid_login_message").text("");
}

// File Share

function LoadFileShare() {
	
	document.getElementById("content").innerHTML = "<section><form id='fileInfo'><input type='file' id='fileInput' name='files'/></form><div class='progress'><div class='label'>Send progress: </div><progress id='sendProgress' max='0' value='0'></progress></div><div class='progress'><div class='label'>Receive progress: </div><progress id='receiveProgress' max='0' value='0'></progress></div><div id='bitrate'></div><a id='download'></a><span id='status'></span></section>";

	
localConnection;
remoteConnection;
sendChannel;
receiveChannel;
pcConstraint;
bitrateDiv = document.querySelector('div#bitrate');
fileInput = document.querySelector('input#fileInput');
downloadAnchor = document.querySelector('a#download');
sendProgress = document.querySelector('progress#sendProgress');
receiveProgress = document.querySelector('progress#receiveProgress');
statusMessage = document.querySelector('span#status');

receiveBuffer = [];
receivedSize = 0;

bytesPrev = 0;
timestampPrev = 0;
timestampStart;
statsInterval = null;
bitrateMax = 0;

fileInput.addEventListener('change', handleFileInputChange, false);

}

//ProgressView functions-------------------------------------------------------------------

function LoadProgressView() {
    $.ajax({
        url: 'ajax/load_progress/load_progress_view.ajax.php',
        type: 'POST',
        data: {
            AJAX: true,
        },
        success: function(data) {
            document.getElementById('content').innerHTML = data;
        },
        error: function() {
            document.getElementById('content').innerHTML = ("error loading progress view, try again");
        }
    });
}

//Chat functions-------------------------------------------------------------------

function LoadChat() {
	document.getElementById("chatNOTIF").style.visibility = 'hidden';
    $.ajax({
        url: 'ajax/chat/load_chat.ajax.php',
        type: 'POST',
        data: {
            AJAX: true,
        },
        success: function(data) {
            document.getElementById('content').innerHTML = data;
            var objDiv = document.getElementById("chat_window");
            objDiv.scrollTop = objDiv.scrollHeight;
        },
        error: function() {
            console.log("Error with retrieving latest messages!!");
        }
    });
}

// Allows the user to send a message 
function sendMessage() {

    var text = $("#message_box").val(); // Get the message contents from the input


    if (text.length == 0) {
        alert("You cannot send an empty message!");
        return;
    }
    

    if (text.length > 255) {
        var difference = text.length - 255;
        alert("Your message is " + difference + " characters too long (255 characters max)!");
        return;
    }
    if ($("#chat_window").is(':empty')) {
  
        $("#to_button").prop('disabled', true);
    }

    $.ajax({
        url: 'ajax/chat/send_message.ajax.php',
        type: 'POST',
        data: {
            AJAX: true,
            text: (text),
           
        },
        success: function(data) {
            // For appending the conversation box
            try {
                var jData = JSON.parse(data);
            } catch (error) {
                alert(data);
                return;
            }

           

            // For sending the message
            if (jData["messageId"] != null) {
                console.log("Sending message!");

                var messageId = jData["messageId"];

                // Create the message
                var message = $("<div data-id=" + messageId + " class='message message_from_self'><p>" + text + "</p></div>");

                // If this is the first message, add to the top
                if ($("#chat_window").children(".message_from_self").length == 0) {
                    $(message).hide().appendTo("#chat_window").fadeIn(700);
                }
                // Otherwise, add it after the last message
                else {
                    $(message).hide().insertAfter($("#chat_window").children(".message").last()).fadeIn(700);
                }

                // Clear the message box
                $("#message_box").val("");

                // Scroll to the bottom of the chat window
                $("#chat_window").animate({
                    scrollTop: $("#chat_window").prop("scrollHeight")
                }, 'slow');

                // Update the recipients box to include the sender (if its the first message in the conversation
                if ($("#chat_window").is(':empty')) {
                    $("#recipients").attr('value', jData["senderEmail"] + "; " + recipients);
                    $("#recipients").val(jData["senderEmail"] + "; " + recipients);
                }
            } else {
                alert(data);
            }
        },
        error: function() {
            console.log("Error with sending message!");
        }
    });
}
function addSearchBoxListener() {
    // Catch typing so we can search the database
    $('#search_box').on('input', function() {
        // Get the input value
        var keyword = $("#search_box").val();

        if (keyword.length < 3) {
            // Empty the search results container
            $("#search_results").empty();
            // Update the search results to reflect the absence of matching records
            $("#search_results").append("<p class='empty_search_results_text'>Nothing to show</p>");
            return;
        }

        // Don't send another AJAX request if still searching already
        if (searching) {
            return;
        }

        // Update the search state
        searching = true;

        // Start the timer to delay searching again (0.8s between searches)
        searchTimer = setTimeout(function() {
            searching = false;
            clearTimeout(searchTimer);
            searchTimer = null;
        }, 800);

        // Search for matching user records
        console.log("Searching the database for matching user records...");
        $("#search_results p").text("Searching...");
        $.ajax({
            url: 'ajax/search_for_user.ajax.php',
            type: 'POST',
            data: {
                AJAX: true,
                keyword: keyword
            },
            success: function(data) {
                // Clear the search results div
                $("#search_results").empty();
                // If matching records were found
                if (data != 0) {
                    console.log("Found matching user records for the search!");
                    // Add the results to the search results div
                    $("#search_results").append(data);
                } else { // No matching records
                    console.log("No results for the user search.");
                    // Update the search results to reflect the absence of matching records
                    $("#search_results").append("<p class='empty_search_results_text'>Nothing to show</p>");
                }
            },
            error: function() {
                console.log("Error with searching for user!");
            }
        });
    });
}
// Allows the user to logout
function logout() {
    $.ajax({
        url: 'ajax/auth/logout.ajax.php',
        type: 'POST',
        data: {
            AJAX: true
        },
        success: function() {
            console.log("Logging out!");
            window.location.reload(); // Reload the page now that the user id has been cleared from the session
        },
        error: function() {
            console.log("Error with logging out!");
        }
    });
}

// GROUP AND NAV BAR CODE
function openNav() {
    document.getElementById("groupnav").style.width = "260px";
    $.ajax({
        url: 'ajax/group/get_group_list.ajax.php',
        type: 'POST',
        data: {
            AJAX: true,
        },
        success: function(data) {
            console.log(data);
            document.getElementById("group_list").innerHTML = data;
        },
        error: function() {
            console.log("Error with group create!");
        }
    });

}

function closeNav() {
    document.getElementById("groupnav").style.width = "0";
}


// opens the create new group menu
function open_create_group(d) {
    var frm = "<form>";
    frm += "Group Name: <br/><input id='gname' type='text'style='width:100%;'></input><br/><br/>";
    frm += "Group Description: <br/><textarea id='gdescription' style='width:100%;height:60%;'></textarea><br/>";
    frm += "</form>";
    frm += "<button onclick='create_group()'>Create Group</button>";

    $("<div id='search_clickout' onclick='closeSearchUsersPopup();'></div>").hide().appendTo("body").fadeIn(700);
    $("<div id='search_popup'>" + frm + "</div></div>").hide().appendTo("body").fadeIn(700);
}

// opens the join code input menu
function open_join_group() {
    var frm = "<form>";
    frm += "Enter Join-Code: <br/><input id='joincodeinput' type='text'style='width:100%;'></input><br/>";
    frm += "</form>";
    frm += "<button onclick='join_group()'>Join Group!</button><br/>";
    frm += "<a style='color:red;' id='joinerror'></a>";

    $("<div id='search_clickout' onclick='closeSearchUsersPopup();'></div>").hide().appendTo("body").fadeIn(700);
    $("<div id='search_popup'>" + frm + "</div></div>").hide().appendTo("body").fadeIn(700);
}

// changes the currently selected group and updates the page
function change_group(i) {
    // clear content
    document.getElementById("content").innerHTML = "";

    // gather and switch group session
    $.ajax({
        url: 'ajax/group/get_group_info.ajax.php',
        type: 'POST',
        data: {
            AJAX: true,
            index: (i)
        },
        success: function(data) {
            document.getElementById("groupinfo").innerHTML = data;
            closeNav();
        },
        error: function() {
            console.log("Error loading group info");
        }
    });

}

// try to add user to group
function join_group() {
    var jc = $("#joincodeinput").val(); // Get the join code
    if (jc.length < 6)
        return;
    document.getElementById("joinerror").innerHTML = "";
    $.ajax({
        url: 'ajax/group/join_group.ajax.php',
        type: 'POST',
        data: {
            AJAX: true,
            joincode: (jc)
        },
        success: function(data) {
            if (data == 1) {
                closeSearchUsersPopup();
                closeNav();
            }
            if (data == 2) {
                document.getElementById("joinerror").innerHTML = "Already a member of given group";
            }
        },
        error: function() {
            console.log("Error loading group info");
        }
    });
}

// creates a new group base on the group creation form
function create_group() {
    // validate group
    var name = $("#gname").val(); // Get the message contents from the input
    var des = $("#gdescription").val(); // Get the message contents from the input

    if (name.length == 0) {
        return;
    }
    if (des.length == 0) {
        return;
    }
    $.ajax({
        url: 'ajax/group/create_group.ajax.php',
        type: 'POST',
        data: {
            AJAX: true,
            name: (name),
            des: (des)
        },
        success: function(data) {
            console.log(data);
            if (data == 1) {
                closeSearchUsersPopup();
                closeNav();
            }
            change_group();
        },
        error: function() {
            console.log("Error with group create!");
        }
    });

}