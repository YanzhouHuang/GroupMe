// Calender Code
months = ['January', 'February', 'March', 'April',
    'May', 'June', 'July', 'August', 'September',
    'October', 'November', 'December'
];
days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thr', 'Fri', 'Sat'];
monthDayCount = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

day = 0;
month = null;
year = null;

// This needs to be Run FIRST!!
function initializeCalendar() {
    var chtml = "";

    // Calendar Generation
    currentDate = new Date();
    month = (month == null) ? currentDate.getMonth() : month;
    year = (year == null) ? currentDate.getFullYear() : year;

    chtml += "<div style='padding: 25px 50px 50px 50px;'>";
	chtml += "<ul id='legend' style='width:100%;'>";
	chtml += "<li style='display:inline;'><span style='display:inline;width:16px;height:16px;background-color:#f0a000'></span> Events</li>";
	chtml += "<li style='display:inline;'><span style='display:inline;width:16px;height:16px;background-color:#00a0f0'/> Tasks</li>";
	chtml += "<li style='display:inline;'><span style='display:inline;width:16px;height:16px;background-color:#FF2000'/> Meetings</li>";
	chtml += "</ul>";
    chtml += "<table class='calendar' id='calendarDisplay'>";

    chtml += "<tr>";
    chtml += "<td><button class='calendarNavButton' type='button' onclick='prevMonth()'>\<</button></td>";
    chtml += "<td COLSPAN='5' ALIGN=center class='monthDisplay'><a id='month'></a> <a id='year'></a></td>";
    chtml += "<td><button class='calendarNavButton' type='button' onclick='nextMonth()'>\></button></td>";
    chtml += "</tr>";

    // add the days

    chtml += "<tr class='dayDisplay'>";
    for (i = 0; i < 7; i++) {
        chtml += "<td >" + days[i] + "</td>";
    }
    chtml += "</tr'>";

    for (i = 0; i < 6; i++) {
        chtml += "<tr class='daysDisplay'>";
        for (j = 0; j < 7; j++) {
            chtml += "<td class='calendarBox' style='border: 1px solid black;'></td>";
        }
        chtml += "</tr>";
    }

    chtml += "</table>";
    chtml += "</div>";

    document.getElementById('content').innerHTML = chtml;

    renderCalendar();
}

function renderCalendar() {
    // set the month and year
    document.getElementById('month').innerHTML = months[month];
    document.getElementById('year').innerHTML = year;

    // determine the starting day
    var day = new Date(year, month, 1);
    // get day count for the month
    var dayCount = monthDayCount[month];
    // correct February!!
    if (this.month == 1) {
        if ((this.year % 4 == 0 && this.year % 100 != 0) || this.year % 400 == 0)
            dayCount = 29;
    }

    // start on first day and fill out the other days
    var startDay = day.getDay();

    var table = document.getElementById("calendarDisplay");

    for (var i = 2, row; row = table.rows[i]; i++) {
        for (var j = 0, col; col = row.cells[j]; j++) {
            table.rows[i].cells[j].innerHTML = "<div class='dayScroll'/>";
        }
    }

    // add new days / Events
    for (i = 0; i < dayCount; i++) {
        var x = (startDay + i) % 7;
        var y = Math.floor((startDay + i) / 7);

        var d = "<div class='dayScroll'>";
        d += "<button onclick='AddEventButton(" + (i + 1) + ")' class='newEvent'>+</button>" + "<div class='dayDate'>" + (i + 1) + "</div>";

        d += "<p id='day" + i + "'>";
        d += getEvents(i);
        d += "</p>";
        d += "</div>";
        table.rows[y + 2].cells[x].innerHTML = d;
    }
}

//Creates the adding event form
function AddEventButton(d) {
    $.ajax({
        url: 'ajax/get_group_memberlist.ajax.php',
        type: 'POST',
        data: {
            AJAX: true,
        },
        success: function(data) {
            var frm = "<form>";
            frm += "<h2 style='text-align:center;'>Add Event for: " + (month + 1) + "-" + d + "-" + year + "</h2>";
            frm += "Event Title: <br/><input id='addName' type='text'style='width:400px;'></input><br/><br/>";
            frm += "Event Description: <br/><textarea id='addDescription' style='width:100%;height:100px;'></textarea><br/>";
            frm += "<p>Event Type: <select id='eventtype'><option value='Task'>Task</option><option value='Event'>Event</option><option value='Meeting'>Meeting</option></select></p>";
            frm += "<p>Location: <input type='text' id='location'></p>";
            frm += "<p>Date of Event: <input type='text' id='due' value='" + pad(month + 1, 2) + "/" + pad(d, 2) + "/" + year + "'></p>";
            frm += "<p>Time: (For Meetings)</p>";
            frm += "</form><br/>";
            frm += "<p>Members:<input type='checkbox' onClick='toggle(this)' /> Toggle All<br/>"
            frm += data + "</p>";
            frm += "<p id='errorEvent' style='color:red;'></p>"
            frm += "<button onclick='addEvent()'>Create Event</button>";

            $("<div id='search_clickout' onclick='closeSearchUsersPopup();'></div>").hide().appendTo("body").fadeIn(700);
            $("<div id='search_popup' style='text-align:left;'>" + frm + "</div></div>").hide().appendTo("body").fadeIn(700);

            $("#due").datepicker();
            addSearchBoxListener();
        },
        error: function() {}
    });
}

function pad(num, size) {
    var s = num + "";
    while (s.length < size) s = "0" + s;
    return s;
}

function toggle(source) {
    checkboxes = document.getElementsByName('usercb[]');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = source.checked;
    }
}

// Adds a new event from the given form
// will notify of errors in the form
function addEvent() {
    var name = document.getElementById("addName").value;
    var des = document.getElementById("addDescription").value;
    var type = document.getElementById("eventtype");
    var typevalue = type.options[type.selectedIndex].value;
    var datedue = document.getElementById("due").value;
    var location = document.getElementById("location").value;

    if (datedue.length < 1) {
        document.getElementById("errorEvent").innerHTML = "Enter a Due Date";
    } else
    if (name.length < 1) {
        document.getElementById("errorEvent").innerHTML = "Event name cannot be blank";
    } else
    if (!(typevalue == "Task" || typevalue == "Event" || typevalue == "Meeting")) {
        document.getElementById("errorEvent").innerHTML = "Invalid task type";
    } else {
        $.ajax({
            url: 'ajax/add_event.ajax.php',
            type: 'POST',
            data: {
                AJAX: true,
                name: (name),
                des: (des),
                day: (day),
                month: month + 1,
                year: (year),
                type: (typevalue),
                members: $('.usercb:checked').serialize(),
                location: (location),
                datedue: (datedue),
            },
            success: function(data) {
                if (data.length > 0) {
                    document.getElementById("errorEvent").innerHTML = data;
                } else {
                    closeSearchUsersPopup();
                    renderCalendar();
                }
            },
            error: function() {
                document.getElementById("errorEvent").innerHTML = "Error adding event: " + data;
            }
        });
    }
}

// Returns the events for the given day
// input is date format yyyy-mm-dd
function getEvents(day) {
    $.ajax({
        url: 'ajax/get_calendar_events.ajax.php',
        type: 'POST',
        data: {
            AJAX: true,
            day: day + 1,
            month: month + 1,
            year: year
        },
        success: function(data) {
            document.getElementById("day" + day).innerHTML = data;
            return data;
        },
        error: function() {
            console.log("Error with retrieving calendar events!");
            return "Error Loading...";
        }
    });
    return "Loading...";
}

// Shows the details of an event
// Also creates an editable interface
function show_event(day, index) {
    $.ajax({
        url: 'ajax/show_edit_event.ajax.php',
        type: 'POST',
        data: {
            AJAX: true,
            date: (day),
            index: (index),
        },
        success: function(data) {
            $("<div id='search_clickout' onclick='closeSearchUsersPopup();'></div>").hide().appendTo("body").fadeIn(700);
            $("<div id='search_popup'>" + data + "</div></div>").hide().appendTo("body").fadeIn(700);
            addSearchBoxListener();
        },
        error: function() {
            console.log("Error add calendar events!");
        }
    });
}

function updateEventCompletion(eid){
    $.ajax({
        url: 'ajax/update_completion.ajax.php',
        type: 'POST',
        data: {
            AJAX: true,
            eid: (eid),
			checked: document.getElementById("completeCheck").checked
        },
        success: function(data) {
            renderCalendar();
        },
        error: function() {
			
        }
    });
}

// Navigation
function prevMonth() {
    month -= 1;
    if (month < 0) {
        month = 11;
        year -= 1;
    }
    renderCalendar();
}

function nextMonth() {
    month += 1;
    if (month > 11) {
        month = 0;
        year += 1;
    }
    renderCalendar();
}