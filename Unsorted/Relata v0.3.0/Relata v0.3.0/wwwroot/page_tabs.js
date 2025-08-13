// add event listeners to all functions
tabone = document.getElementById("tab1");
addevent(tabone,"click",tabclick,false);

tabtwo = document.getElementById('tab2');
addevent(tabtwo,"click",tabclick,false);

tabthree = document.getElementById('tab3');
addevent(tabthree,"click",tabclick,false);

tabfour = document.getElementById('tab4');
addevent(tabfour,"click",tabclick,false);

// event listener function... called when you click on a tab
function tabclick(e)
{
	// if ie
	if(window.event)
	{
		e = event;
		tabid = e.srcElement.id;
	}
	else
	{
		tabid = e.currentTarget.id;
	}
	
	// get the tab #
	tabid = tabid.charAt(3);
	
	changeiframe(tabid);
}

// changes the iframe, called by event listener
function changeiframe(tabid)
{
	// change the visibility of the IFRAME and change the buttons
	for(i = 1; i <= 4; i++)
	{	
		if(i == tabid) 
		{
			visibility = "visible";
			tabstatus = "tab-selected";
		}
		else 
		{
			visibility = "hidden";
			tabstatus = "tab-normal";
		}
		
		document.getElementById("tab" + i).className = tabstatus;
		document.getElementById("iframe" + i).style.visibility = visibility;
	}
}