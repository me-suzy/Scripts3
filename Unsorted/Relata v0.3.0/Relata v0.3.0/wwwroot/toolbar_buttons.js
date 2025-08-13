if(document.all) ie = true;
else ie = false;

// make the mouseover,mouseout... effects
// example... button_effects("mouseover",image obj, "refresh");
function button_effects(event_type,image,image_type)
{
	switch(event_type)
	{
		case "mouseover":
			image.src =  parent.www_dir + "templates/images/" + btnid + "_roll.gif";
			break;
		case "mouseout":
			image.src =  parent.www_dir + "templates/images/" + btnid + "_norm.gif";
			break;
		case "mousedown":
			image.src =  parent.www_dir + "templates/images/" + btnid + "_down.gif";
			break;
		case "mouseup":
			image.src =  parent.www_dir + "templates/images/" + btnid + "_roll.gif";
			break;
	}
}

// event listener for the toolbar buttons (refresh,back,forward,print...)
function toolbarbtnaction(e)
{
	if(ie)
	{
		// capture the type of button in IE
		e = event;
		btnid = e.srcElement.id;
		btn = e.srcElement;
	}
	else
	{
		// capture the type of button in W3 DOM browsers
		btnid = e.target.id;		
		btn = e.target;		
	}
	
	// button effects	
	button_effects(e.type,btn,btnid);
	
	if(e.type=="click")
	{
		switch(btnid)
		{
			case "refresh":
				// refresh the left & main frames
				eval("parent.main" + parent.winid + ".location.reload();");
				eval("parent.left" + parent.winid + ".location.reload();");
				
				break;
			case "print":
				// focus, then print the main frame
				eval("parent.main" + parent.winid + ".focus();");
				eval("parent.main" + parent.winid + ".print();");
				
				break;
			case "back":
				// if it uses list.js
				if(parent.frames[1].sidebarlist == true)
				{
					goback();
				}
				else
				{
					// we are on the activity page (doesnt use list.js)
					gobackactivity();
				}	

				break;
			case "forward":
				// if it uses list.js
				if(parent.frames[1].sidebarlist == true)
				{
					goforward();
				}
				else
				{
					// we are on the activity page (doesnt use list.js)
					goforwardactivity();
				}
				break;
		}
	}
}

// add the events to each button (back,forward,refresh)
function addtoolbarbtnevent(obj)
{
	addevent(obj,"mouseover",toolbarbtnaction,false);
	addevent(obj,"mouseout",toolbarbtnaction,false);
	addevent(obj,"mousedown",toolbarbtnaction,false);
	addevent(obj,"mouseup",toolbarbtnaction,false);
	addevent(obj,"click",toolbarbtnaction,false);
}

// remove the events to each button (back,forward,refresh)
function removetoolbarbtnevent(obj)
{
	removeevent(obj,"mouseover",toolbarbtnaction,false);
	removeevent(obj,"mouseout",toolbarbtnaction,false);
	removeevent(obj,"mousedown",toolbarbtnaction,false);
	removeevent(obj,"mouseup",toolbarbtnaction,false);
	removeevent(obj,"click",toolbarbtnaction,false);
}

// close the window
function closewin()
{
	// remove it from the taskbar
	task = parent.parent.parent.frames[0].domwin[parent.winid].task;
	task.parentNode.removeChild(task);

	// IE 5.0 doesn't support creation of IFRAMES w/ javascript
	// so we have to put it in "standby" rather than remove it completely
	if(navigator.userAgent.indexOf("MSIE 5.0") != -1)
	{
		win = parent.parent.parent.frames[0].domwin[parent.winid].contentarea;
		parent.parent.parent.frames[0].activewindows[parent.winid] = false;
		win.src="about:blank";
		win.style.display = "none";
		win.width="0px";
		win.height="0px";
	}
	else
	{
		// remove the window
		win = parent.parent.parent.frames[0].domwin[parent.winid].contentarea;
		parent.parent.parent.frames[0].activewindows[parent.winid] = false;
		win.parentNode.removeChild(win);
	}
}

// minimize the window
function minimize()
{
	if(document.all) parent.parent.parent.frames[0].domwin[parent.winid].contentarea.style.display = "none";
	else parent.parent.parent.frames[0].domwin[parent.winid].contentarea.style.visibility = "hidden";

	// set it out outset
	task = parent.parent.parent.frames[0].domwin[parent.winid].task;
	task.style.borderStyle = "outset";
}

// when you press on a button make it push in
function changeicon(e)
{
	if(document.all) e = event;
	
	if(e.type == "mousedown")
	{
		if(document.all)
		{
			if(window.event.srcElement.id == "minimize")
			{
				window.event.srcElement.src = parent.www_dir + "templates/images/minimize_down.gif";
			}
			else if(window.event.srcElement.id == "close")
			{
				window.event.srcElement.src = parent.www_dir + "templates/images/close_down.gif";
			}
		}
		else
		{
			if(e.currentTarget.id == "minimize")
			{
				e.currentTarget.src = parent.www_dir + "templates/images/minimize_down.gif";
			}
			else if(e.currentTarget.id == "close")
			{
				e.currentTarget.src = parent.www_dir + "templates/images/close_down.gif";
			}
		}
	}
	if(e.type == "mouseup" || e.type == "mouseout")
	{
		if(document.all)
		{
			if(window.event.srcElement.id == "minimize")
			{
				window.event.srcElement.src = parent.www_dir + "templates/images/minimize_norm.gif";
			}
			else if(window.event.srcElement.id == "close")
			{
				window.event.srcElement.src = parent.www_dir + "templates/images/close_norm.gif";
			}
		}
		else
		{
			if(e.currentTarget.id == "minimize")
			{
				e.currentTarget.src = parent.www_dir + "templates/images/minimize_norm.gif";
			}
			else if(e.currentTarget.id == "close")
			{
				e.currentTarget.src = parent.www_dir + "templates/images/close_norm.gif";
			}
		}
	}
}

// enable back button on toolbar
function enablebackbtn()
{
	// adding event listeners more than once ( IE bug ) forces the event to fire more than once
	if(!backbtn_enable)
	{
		// enable back btn
		backbtn = document.getElementById("back");
		addtoolbarbtnevent(backbtn);
		backbtn.src =  parent.www_dir + "templates/images/back_norm.gif";
	}

	backbtn_enable = true;
}
// enable forward button on toolbar
function enableforwardbtn()
{
	// adding event listeners more than once ( IE bug ) forces the event to fire more than once
	if(!forwardbtn_enable)
	{
		// enable forward btn
		fowardbtn = document.getElementById("forward");
		addtoolbarbtnevent(forwardbtn);
		forwardbtn.src =  parent.www_dir + "templates/images/forward_norm.gif";
	}
	
	forwardbtn_enable = true;
}

// disable back button on toolbar
function disablebackbtn()
{
	// disable back btn
	backbtn = document.getElementById("back");
	removetoolbarbtnevent(backbtn);
	backbtn.src = parent.www_dir + "templates/images/back_disable.gif";
	
	backbtn_enable = false;
}

// disable forward button on toolbar
function disableforwardbtn()
{
	// disable forward btn
	forwardbtn = document.getElementById("forward");
	removetoolbarbtnevent(forwardbtn);
	forwardbtn.src =  parent.www_dir + "templates/images/forward_disable.gif";

	forwardbtn_enable = false;
}

// go back in history
function goback()
{
	// if we aren't at the first element in the history
	if(parent.btnhistory_cnt > 0)
	{				
		enableforwardbtn();
		
		do
		{
			// find out the last page from the history array
			parent.btnhistory_cnt--;
			btnid = parent.btnhistory[parent.btnhistory_cnt];

			// cant go back any more
			if(parent.btnhistory_cnt <= 0)
			{
				disablebackbtn();
				validrecord = true;
			}
			
			// go there and change the button
			eval("validrecord = parent.left" + parent.winid + ".changebtn(btnid);");
		} while(validrecord == false)
	}
	else
	{
		disablebackbtn();
	}
}

// go forward in history
function goforward()
{
	// if we aren't at the last element in the history
	if(parent.btnhistory_cnt < (parent.btnhistory.length-1))
	{
		enablebackbtn();
		
		do
		{
			// find out the last page from the history array
			parent.btnhistory_cnt++;	
			btnid = parent.btnhistory[parent.btnhistory_cnt];
	
			// can't go forward any more
			if(parent.btnhistory_cnt >= (parent.btnhistory.length-1))
			{
				disableforwardbtn();
				validrecord = true;
			}
		
			// go there
			eval("validrecord = parent.left" + parent.winid + ".changebtn(btnid);");
		} while(validrecord == false)
	}
}

// activitity page is structured differently we so need a special function for it
function gobackactivity()
{
	enableforwardbtn();

	// go back one in the page history
	parent.btnhistory_cnt--;
	
	eval('parent.main' + parent.winid + '.location.href = "' + parent.btnhistory[parent.btnhistory_cnt] + '"');
	
	// cant go back any more
	if(parent.btnhistory_cnt <= 0)
	{
		disablebackbtn();
	}
}

// go forward on activity page
function goforwardactivity()
{
	enablebackbtn();

	// go back one in the page history
	parent.btnhistory_cnt++;
	
	eval('parent.main' + parent.winid + '.location.href = "' + parent.btnhistory[parent.btnhistory_cnt] + '"');
	
	// cant go back any more
	if(parent.btnhistory_cnt >= (parent.btnhistory.length-1))
	{
		disableforwardbtn();
	}
}