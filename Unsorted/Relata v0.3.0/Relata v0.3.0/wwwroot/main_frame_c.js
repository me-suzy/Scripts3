// taskbar functions

// taskbar object
// usage : task = taskmanager("Account Manager",5);

// initial distance from left
left = 5;

// add a task to the taskbar, with the label and unique window id
function addtask(label,id)
{
	// setup the class variables
	label 	= label;	// window title
	id		= id;		// unique id
	curwin	= id;		// currently active window
			
	// create the item on the taskbar
	taskbaritem = document.createElement("DIV");
	taskbaritem.className = "taskbaritem";
	//taskbaritem.style.left = left + "px";
	taskbaritem.style.borderStyle = "inset";
	label = document.createTextNode(label);
	taskbaritem.id = "taskbar" + id;

	taskbaritem.appendChild(label);
	taskbardiv = document.getElementById("taskbar");
	taskbardiv.appendChild(taskbaritem);
	
	addevent(taskbaritem,"click",taskbarclick,false);
	
	left = left + 158;
	
	return taskbaritem;
}

// when a button on the taskbar is clicked focus the window
function taskbarclick(e)
{
	if(document.all)
	{
		taskbaritemid = event.srcElement.id.slice(7);
	}
	else
	{
		taskbaritemid = e.currentTarget.id.slice(7);
	}
	
	parent.frames[0].focuswin(taskbaritemid);
}