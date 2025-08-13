// reset the back/forward history
function resethistory()
{
	parent.curbtn = null;
	parent.firstbtn = true;
	parent.btnhistory_cnt = 0;
}

// change the page when the user clicks on the radio button
function changetype(what)
{
	// GROUPS
	if(what.selectedIndex == 0)
	{
		// change the main frame
		location.href = "settings_main.php?type=groups&session_id=" + session_id;
		// change the left frame
		eval("parent.left" + parent.winid + ".location.href=\"left.php?type=groups&session_id=" + session_id + "\";");
		resethistory();
	}
	// EXTRA FIELDS
	else if(what.selectedIndex == 1)
	{
		// change the main frame
		location.href = "settings_main.php?type=extrafields&session_id=" + session_id;
		// change the left frame
		eval("parent.left" + parent.winid + ".location.href=\"left.php?type=extrafields&session_id=" + session_id + "\";");
		resethistory();
	}
}

// in IE5, unless you have more than one text input fields and press enter on a form it wont submit the
// submit button variable over, so we need to set it manually
function ieformsubmitbugfix_groups()
{
	action = document.settings.action;
	
	if(action.value == "")
	{
		action.value = "   OK   ";
	}
}

// same as ieformsubmitbuxfix_groups except for extra fields
function ieformsubmitbugfix_xfields()
{
	action = document.settings.action;

	if(action.value == "")
	{
		action.value = "   OK   ";
	}
}