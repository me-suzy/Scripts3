// called when someone clicks on the checkbox
function change_calendar_item()
{
	// the activity is a calendar item
	if(document.activity_detail.is_calendar_item[0].checked)
	{
		document.getElementById("calendar_item").style.display = "block";
		document.getElementById("non_calendar_item").style.display = "none";
	}
	// it is a todo item
	else if(document.activity_detail.is_calendar_item[1].checked)
	{
		document.getElementById("calendar_item").style.display = "none";
		document.getElementById("non_calendar_item").style.display = "none";
	}
	else
	{
		document.getElementById("calendar_item").style.display = "none";
		document.getElementById("non_calendar_item").style.display = "block";
	}
}

// read the value the template put in
function init()
{
	change_calendar_item();
}