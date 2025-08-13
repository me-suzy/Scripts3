/* 
prints the top navigation buttons that open the mini windows in the top frame.

Relates to:
/a.js 
/templates/a.htm
/templates/a.css
*/

// offset of first element
var x = 75;
// spacing in between elements
var spacing = 95;
// the array for the windows
domwin = new Array;
// stores which windows are open
activewindows = new Array;
	
// using IE are we?
ie = (document.all)?true:false

// make the top nav button
function makebtn(label,type)
{
	div = document.createElement("DIV");
	
	// determines the type of button in the event listener functions
	div.id = type;
	
	// make it look nice
	div.className = "button";
	
	// add the text	
	div.appendChild(document.createTextNode(label));
	
	// put it into the document
	div.style.left = x + "px";
	
	buttondiv = document.getElementById("buttons");
	buttondiv.appendChild(div);
	
	addevent(div,"mousedown",btnchange,false);
	addevent(div,"mouseup",btnchange,false);
	addevent(div,"mouseout",btnchange,false);
	addevent(div,"click",gothere,false);
	
	x = x + spacing;
}

// on button click indent it
function btnchange(e)
{
	if(ie) e = event;

	if(e.type=="mouseup" || e.type=="mouseout")
	{
		if(ie) e.srcElement.style.borderStyle="outset";
		else e.currentTarget.style.borderStyle="outset";
	}
	else if(e.type=="mousedown")
	{
		if(ie) e.srcElement.style.borderStyle="groove";
		else e.currentTarget.style.borderStyle="groove";
	}
}

// open a new window
function gothere(e)
{
	if(ie) id = event.srcElement.id;
	else id = e.currentTarget.id;
	
	// find out what button the user clicked on
	switch(id)
	{
		case "contactmanager":
			windowid = 0;
			url = "./contact/index.php?winid="+windowid+"&session_id=" + parent.frames[1].session_id;
			title = "Contact Manager";
			break;
		case "account":
			windowid = 1;
			url = "./account/index.php?winid="+windowid+"&session_id=" + parent.frames[1].session_id;
			title = "Account Manager";
			break;
		case "opportunities":
			windowid = 2;
			url = "./opportunity/index.php?winid="+windowid+"&session_id=" + parent.frames[1].session_id;
			title = "Opportunity Manager";
			break;
		case "activities":
			windowid = 3;
			url = "./activity/index.php?winid="+windowid+"&session_id=" + parent.frames[1].session_id;
			title = "Activity Manager";
			break;
		case "groupemail":
			windowid = 4;
			url = "./group_email/index.php?winid="+windowid+"&session_id=" + parent.frames[1].session_id;
			title = "Group Email";
			break;		
		case "settings":
			windowid = 5;
			url = "./settings/index.php?winid="+windowid+"&session_id=" + parent.frames[1].session_id;
			title = "Settings Manager";
			break;
	}
	
	// make the new window and add it to the domwin[] array
	if(!activewindows[windowid])
	{
		domwin[windowid] = new parent.frames[1].win(title,url,windowid);
		activewindows[windowid] = true;
	}
	
	focuswin(windowid);
}

// USAGE: focuswin(2); will minimize all windows except 2
// note for future possible speed enhancement:
// instead of looping through and minimizing all windows/taskbar items record
// the last active window somewhere and minimize only that one
function focuswin(focuswinid)
{
	// minimize every open window except the one we want to focus
	for(i = 0; i < domwin.length; i++)
	{
		if(i != focuswinid && activewindows[i] == true)
		{
			// ie bug... using visibility is faster
			if(document.all) domwin[i].contentarea.style.display = "none";
			else domwin[i].contentarea.style.visibility = "hidden";
			
			domwin[i].task.style.borderStyle = "outset";
		}
	}
	
	// maximize the window we want to focus
	if(document.all) domwin[focuswinid].contentarea.style.display = "block";
	else domwin[focuswinid].contentarea.style.visibility = "visible";
	
	domwin[focuswinid].task.style.borderStyle = "inset";
}