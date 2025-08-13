var old_btn;

// used to identify that the list.js file is used
var sidebarlist = true;

// using IE?
ie = (document.all)?true:false

// setup a button
function addbtn(label,id)
{
	box = document.createElement("DIV");
	if(document.getElementById("contact-leftnavbox"))
	{
		document.getElementById("contact-leftnavbox").appendChild(box);
	}
	else
	{
		document.getElementById("leftnavbox").appendChild(box);
	}
	// add style
	box.className = "buttonnorm";
	
	box.id = id;
	if(ie)	box.style.cursor = "hand";
	else	box.style.cursor = "pointer";
	
	label = document.createTextNode(label);
	box.appendChild(label);
	
	addevent(box,"mouseover",btnover,false);
	addevent(box,"mouseout",btnover,false);
	addevent(box,"click",btnclick,false);
		
	// if this is the button for the selected field..
	if(parent.firstbtn == true && parent.curbtn != id)
	{		
		parent.curbtn = id;
		parent.firstbtn = false;
	}

	if(parent.curbtn == id)
	{
		box.className = "buttonselected";
		
		// get the next available spot on the array
		ar_length = parent.btnhistory.length;
		
		// save the current btn we are on relative to the array
		parent.btnhistory[ar_length] = id;
	}
	return box;
}

// change the button on mouseover
function btnover(e)
{
	// the button the user clicked on
	if (ie)
	{
		// record the button
		btn = window.event.srcElement;
		// record whether it was a mouseover or mouseout
		eventtype = window.event.type;
	}
	else
	{
		// record the button
		btn = e.currentTarget;
		// record whether it was a mouseover or mouseout
		eventtype = e.type;
	}
	
	// if the mouse isn't moving over the currently selected btn
	if(btn.id != parent.curbtn)
	{
		if(eventtype == "mouseover")
		{
			btn.className = "buttonover";
		}
		else if(eventtype == "mouseout")
		{
			btn.className = "buttonnorm";
		}
	}
}

// event... called when you click on a button
function btnclick(e)
{
	// the button the user clicked on
	if (ie)
	{
		// record the button
		btn = window.event.srcElement;
	}
	else
	{
		// record the button
		btn = e.currentTarget;
	}
	
	// change the new button and record it
	id = btn.id;
	changebtn(id);
		
	// save the current btn we are on relative to the array
	parent.btnhistory_cnt++;
	parent.btnhistory[parent.btnhistory_cnt] = id;
	
	// enable back btn
	parent.toolbar.enablebackbtn();
}

// change the button on mouseclick ( called by btnclick() )
function changebtn(id)
{
	oldbutton = document.getElementById(parent.curbtn);
	
	// if there is no old button return false
	if(!oldbutton) return false;
		
	// set the old button back to normal
	oldbutton.className = "buttonnorm";
		
	thebtn = document.getElementById(id);
	if(!thebtn)	return false;
	thebtn.className = "buttonselected";
	
	eval("url = parent.main" + parent.winid + ".location.href");
	
	// if the right frame is loaded to main.php
	if(url.indexOf("main.php") != -1)
	{	
		// change the preloader ( hidden ) frame
  		eval("parent.main" + parent.winid + ".loader.location.href = \"" + loader_url + id + "\";");
	}
	else
	{
		// change to the right frame
  		eval("parent.main" + parent.winid + ".location.href = \"" + main_url + id + "\";");
	}	
	
	parent.curbtn = id;
}

btns = new Array;
