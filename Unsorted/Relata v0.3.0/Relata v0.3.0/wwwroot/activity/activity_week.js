// on the time view switch the edit fields back to normal
function normalview(time)
{
	edit = "edit_" + time;
	view = "view_" + time;
	txt  = "txt_" + time;
	
	document.getElementById(edit).style.display = "none";
	document.getElementById(view).style.display = "block";
	
	eval(txt + ".nodeValue = document.activities." + edit + ".value;");
}

// event listener when you click on a field
function edit(e)
{	
	if(document.all) ie = true;
	else ie = false;

	if(ie)
	{
		time = event.srcElement.id.split("_");
	}
	else
	{
		time = e.currentTarget.id.split("_");
	}
	
	edit = "edit_" + time[1];
	view = "view_" + time[1];

	document.getElementById(edit).style.display = "block";
	document.getElementById(view).style.display = "none";
	
	eval("document.activities." + edit + ".focus()");
}

// setup a new row for weekly view
var i = 0;
function setuprow(time,value)
{
	eval("document.activities.edit_" + time + ".value='" + value + "';");
	eval("txt_" + time + " = document.createTextNode(value);");

	eval("times[i] = document.getElementById('view_" + time + "');");
	eval("times[i].appendChild(txt_" + time + ");");
	addevent(times[i], "click", edit, false);
	
	i++;
}