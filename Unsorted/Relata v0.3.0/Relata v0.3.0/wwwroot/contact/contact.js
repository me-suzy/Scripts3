// using IE?
ie = (document.all)?true:false

// offset the first tab
if(ie)	var x = -57;
else 	var x = -65;

// make a tab
// takes the name shown on the tab, and the unique id
// returns the tab object
function make_tab(name,id)
{
		// move the tab over each time we add a new one
		if(ie)	x = x + 67;
		else 	x = x + 73;
			
		// make the tab
		tab = document.createElement("DIV");
		
		tab.className = "tab-normal";
		
		text = document.createTextNode(name);
		tab.appendChild(text);
		
		// give it a name
		tab.id = id;
		
		document.getElementById("tabs").appendChild(tab);
		
		addevent(tab,"click",tabclick,false);
		
		return tab;
}

// event listener for the tabs
function tabclick(e)
{
	// the button the user clicked on
	if (!ie)
	{
		btn = e.currentTarget.id;
	}
	else
	{
		e = event;
		btn = window.event.srcElement.id;		
	}
	
	if(btn == "tab_name")
	{
		// show email buttons
		f = document.contacts_form;
		show_email_button(f.contact_lbl1);
		show_email_button(f.contact_lbl2);
		show_email_button(f.contact_lbl3);
		show_email_button(f.contact_lbl4);
		show_email_button(f.contact_lbl5);
	}
	else
	{
		// hide email buttons
		for(i = 1; i <= 5; i++)
		{
			document.getElementById("email_btn" + i).style.visibility = "hidden";
		}
	}	
	
	// change the appropriate button
	check_btn(btn,"tab_name");
	check_btn(btn,"tab_palm");
	check_btn(btn,"tab_xtra");

	// clickbtn = the button that was clicked on
	// checkbtn = the button we are preforming the check on
	function check_btn(clickbtn,checkbtn)
	{
		var tab_top = "5px";
		
		main_div = checkbtn.slice(4);
	
		// if the button was clicked on
		if(checkbtn == clickbtn)
		{
			document.getElementById(main_div).style.visibility = "visible";
			document.getElementById(clickbtn).className = "tab-selected";
		}
		else
		{
			document.getElementById(main_div).style.visibility = "hidden";
			document.getElementById(checkbtn).className = "tab-normal";
		}
	}
}

// perform startup functions
function start()
{
	// make the tabs in everything but N6
	if(navigator.userAgent.indexOf("Netscape6") == -1)
	{
		// make the tabs and set "name" to default
		tab = make_tab("Main","tab_name");
		tab.className="tab-selected";
		make_tab("Palm Fields","tab_palm");
		make_tab("Extra Fields","tab_xtra");
		
		// set default address to business
		changeaddr();
	}
}

// change the address info in the text boxes when a radio btn is changed
function changeaddr(changeto)
{
	// business is checked
	if(document.contacts_form.address[0].checked)
	{
		document.contacts_form.street.value 	= document.contacts_form.bus_street.value;
		document.contacts_form.city.value 		= document.contacts_form.bus_city.value;
		document.contacts_form.state.value 		= document.contacts_form.bus_state.value;
		document.contacts_form.country.value 	= document.contacts_form.bus_country.value;
		document.contacts_form.zip.value 		= document.contacts_form.bus_zip.value;
	}
	
	// personal is checked
	if(document.contacts_form.address[1].checked)
	{
		document.contacts_form.street.value 	= document.contacts_form.hm_street.value;
		document.contacts_form.city.value 		= document.contacts_form.hm_city.value;
		document.contacts_form.state.value 		= document.contacts_form.hm_state.value;
		document.contacts_form.country.value 	= document.contacts_form.hm_country.value;
		document.contacts_form.zip.value 		= document.contacts_form.hm_zip.value;
	}

	// alternate is checked
	if(document.contacts_form.address[2].checked)
	{
		document.contacts_form.street.value 	= document.contacts_form.alt_street.value;
		document.contacts_form.city.value 		= document.contacts_form.alt_city.value;
		document.contacts_form.state.value 		= document.contacts_form.alt_state.value;
		document.contacts_form.country.value 	= document.contacts_form.alt_country.value;
		document.contacts_form.zip.value 		= document.contacts_form.alt_zip.value;
	}
}

// change the info in the hidden inputs when a user clickes on a radio btn
function changeaddrfield(type)
{
	// business is checked
	if(document.contacts_form.address[0].checked)
	{
		eval("document.contacts_form.bus_" + type + ".value = document.contacts_form." + type + ".value");
	}
	
	// personal is checked
	if(document.contacts_form.address[1].checked)
	{
		eval("document.contacts_form.hm_" + type + ".value = document.contacts_form." + type + ".value");
	}
	
	if(document.contacts_form.address[2].checked)
	{
		eval("document.contacts_form.alt_" + type + ".value = document.contacts_form." + type + ".value");
	}
}

// called on page submit to take the data from the iframe into a hidden frame on the main page
function save_phone_comm()
{
	if(document.contacts_form.mode.value != "add")
	{
		// save the data from the iframe to the local form
		document.contacts_form.user_notes.value = ph_comm_win.document.ph_comm_form.ph_comm_textarea.value;
	}
}
