// called when you change the contact label dropdown.. make the email button hidden or visible
function show_email_button(selectbtn, is_on)
{
	field_num = selectbtn.name.charAt(11);
	// if email is selected
	if(selectbtn.selectedIndex == 4)
		visibility = "visible";
	else
		visibility = "hidden";

	document.getElementById("email_btn" + field_num).style.visibility = visibility;
	if(is_on) 
	{
	if(selectbtn.selectedIndex == 0)
		{
			visibility = "visible";
			document.getElementById("dphone_work").style.visibility = visibility;
			visibility = "hidden";
			document.getElementById("dphone_home").style.visibility = visibility;
			document.getElementById("dphone_fax").style.visibility = visibility;
			document.getElementById("dphone_other").style.visibility = visibility;
			document.getElementById("dphone_email").style.visibility = visibility;
			document.getElementById("dphone_main").style.visibility = visibility;
			document.getElementById("dphone_pager").style.visibility = visibility;
			document.getElementById("dphone_mobile").style.visibility = visibility;
		}
	else if(selectbtn.selectedIndex == 1)
		{
			visibility = "visible";
			document.getElementById("dphone_home").style.visibility = visibility;
			visibility = "hidden";
			document.getElementById("dphone_work").style.visibility = visibility;
			document.getElementById("dphone_fax").style.visibility = visibility;
			document.getElementById("dphone_other").style.visibility = visibility;
			document.getElementById("dphone_email").style.visibility = visibility;
			document.getElementById("dphone_main").style.visibility = visibility;
			document.getElementById("dphone_pager").style.visibility = visibility;
			document.getElementById("dphone_mobile").style.visibility = visibility;
		}
	else if(selectbtn.selectedIndex == 2)
		{
			visibility = "visible";
			document.getElementById("dphone_fax").style.visibility = visibility;
			visibility = "hidden";
			document.getElementById("dphone_work").style.visibility = visibility;
			document.getElementById("dphone_home").style.visibility = visibility;
			document.getElementById("dphone_other").style.visibility = visibility;
			document.getElementById("dphone_email").style.visibility = visibility;
			document.getElementById("dphone_main").style.visibility = visibility;
			document.getElementById("dphone_pager").style.visibility = visibility;
			document.getElementById("dphone_mobile").style.visibility = visibility;
		}
	else if(selectbtn.selectedIndex == 3)
		{
			visibility = "visible";
			document.getElementById("dphone_other").style.visibility = visibility;
			visibility = "hidden";
			document.getElementById("dphone_work").style.visibility = visibility;
			document.getElementById("dphone_home").style.visibility = visibility;
			document.getElementById("dphone_fax").style.visibility = visibility;
			document.getElementById("dphone_email").style.visibility = visibility;
			document.getElementById("dphone_main").style.visibility = visibility;
			document.getElementById("dphone_pager").style.visibility = visibility;
			document.getElementById("dphone_mobile").style.visibility = visibility;
		}
	else if(selectbtn.selectedIndex == 4)
		{
			visibility = "visible";
			document.getElementById("dphone_email").style.visibility = visibility;
			visibility = "hidden";
			document.getElementById("dphone_work").style.visibility = visibility;
			document.getElementById("dphone_home").style.visibility = visibility;
			document.getElementById("dphone_fax").style.visibility = visibility;
			document.getElementById("dphone_other").style.visibility = visibility;
			document.getElementById("dphone_main").style.visibility = visibility;
			document.getElementById("dphone_pager").style.visibility = visibility;
			document.getElementById("dphone_mobile").style.visibility = visibility;
		}
	else if(selectbtn.selectedIndex == 5)
		{
			visibility = "visible";
			document.getElementById("dphone_main").style.visibility = visibility;
			visibility = "hidden";
			document.getElementById("dphone_work").style.visibility = visibility;
			document.getElementById("dphone_home").style.visibility = visibility;
			document.getElementById("dphone_fax").style.visibility = visibility;
			document.getElementById("dphone_other").style.visibility = visibility;
			document.getElementById("dphone_email").style.visibility = visibility;
			document.getElementById("dphone_pager").style.visibility = visibility;
			document.getElementById("dphone_mobile").style.visibility = visibility;
		}
	else if(selectbtn.selectedIndex == 6)
		{
			visibility = "visible";
			document.getElementById("dphone_pager").style.visibility = visibility;
			visibility = "hidden";
			document.getElementById("dphone_work").style.visibility = visibility;
			document.getElementById("dphone_home").style.visibility = visibility;
			document.getElementById("dphone_fax").style.visibility = visibility;
			document.getElementById("dphone_other").style.visibility = visibility;
			document.getElementById("dphone_email").style.visibility = visibility;
			document.getElementById("dphone_main").style.visibility = visibility;
			document.getElementById("dphone_mobile").style.visibility = visibility;
		}
	else if(selectbtn.selectedIndex == 7)
		{
			visibility = "visible";
			document.getElementById("dphone_mobile").style.visibility = visibility;
			visibility = "hidden";
			document.getElementById("dphone_work").style.visibility = visibility;
			document.getElementById("dphone_home").style.visibility = visibility;
			document.getElementById("dphone_fax").style.visibility = visibility;
			document.getElementById("dphone_other").style.visibility = visibility;
			document.getElementById("dphone_email").style.visibility = visibility;
			document.getElementById("dphone_main").style.visibility = visibility;
			document.getElementById("dphone_pager").style.visibility = visibility;
		}
	}
}
function changedphone(what)
{
	// if email is selected
	if(what == 0)
		{
			visibility = "visible";
			document.getElementById("dphone_work").style.visibility = visibility;
			visibility = "hidden";
			document.getElementById("dphone_home").style.visibility = visibility;
			document.getElementById("dphone_fax").style.visibility = visibility;
			document.getElementById("dphone_other").style.visibility = visibility;
			document.getElementById("dphone_email").style.visibility = visibility;
			document.getElementById("dphone_main").style.visibility = visibility;
			document.getElementById("dphone_pager").style.visibility = visibility;
			document.getElementById("dphone_mobile").style.visibility = visibility;
		}
	else if(what == 1)
		{
			visibility = "visible";
			document.getElementById("dphone_home").style.visibility = visibility;
			visibility = "hidden";
			document.getElementById("dphone_work").style.visibility = visibility;
			document.getElementById("dphone_fax").style.visibility = visibility;
			document.getElementById("dphone_other").style.visibility = visibility;
			document.getElementById("dphone_email").style.visibility = visibility;
			document.getElementById("dphone_main").style.visibility = visibility;
			document.getElementById("dphone_pager").style.visibility = visibility;
			document.getElementById("dphone_mobile").style.visibility = visibility;
		}
	else if(what == 2)
		{
			visibility = "visible";
			document.getElementById("dphone_fax").style.visibility = visibility;
			visibility = "hidden";
			document.getElementById("dphone_work").style.visibility = visibility;
			document.getElementById("dphone_home").style.visibility = visibility;
			document.getElementById("dphone_other").style.visibility = visibility;
			document.getElementById("dphone_email").style.visibility = visibility;
			document.getElementById("dphone_main").style.visibility = visibility;
			document.getElementById("dphone_pager").style.visibility = visibility;
			document.getElementById("dphone_mobile").style.visibility = visibility;
		}
	else if(what == 3)
		{
			visibility = "visible";
			document.getElementById("dphone_other").style.visibility = visibility;
			visibility = "hidden";
			document.getElementById("dphone_work").style.visibility = visibility;
			document.getElementById("dphone_home").style.visibility = visibility;
			document.getElementById("dphone_fax").style.visibility = visibility;
			document.getElementById("dphone_email").style.visibility = visibility;
			document.getElementById("dphone_main").style.visibility = visibility;
			document.getElementById("dphone_pager").style.visibility = visibility;
			document.getElementById("dphone_mobile").style.visibility = visibility;
		}
	else if(what == 4)
		{
			visibility = "visible";
			document.getElementById("dphone_email").style.visibility = visibility;
			visibility = "hidden";
			document.getElementById("dphone_work").style.visibility = visibility;
			document.getElementById("dphone_home").style.visibility = visibility;
			document.getElementById("dphone_fax").style.visibility = visibility;
			document.getElementById("dphone_other").style.visibility = visibility;
			document.getElementById("dphone_main").style.visibility = visibility;
			document.getElementById("dphone_pager").style.visibility = visibility;
			document.getElementById("dphone_mobile").style.visibility = visibility;
		}
	else if(what == 5)
		{
			visibility = "visible";
			document.getElementById("dphone_main").style.visibility = visibility;
			visibility = "hidden";
			document.getElementById("dphone_work").style.visibility = visibility;
			document.getElementById("dphone_home").style.visibility = visibility;
			document.getElementById("dphone_fax").style.visibility = visibility;
			document.getElementById("dphone_other").style.visibility = visibility;
			document.getElementById("dphone_email").style.visibility = visibility;
			document.getElementById("dphone_pager").style.visibility = visibility;
			document.getElementById("dphone_mobile").style.visibility = visibility;
		}
	else if(what == 6)
		{
			visibility = "visible";
			document.getElementById("dphone_pager").style.visibility = visibility;
			visibility = "hidden";
			document.getElementById("dphone_work").style.visibility = visibility;
			document.getElementById("dphone_home").style.visibility = visibility;
			document.getElementById("dphone_fax").style.visibility = visibility;
			document.getElementById("dphone_other").style.visibility = visibility;
			document.getElementById("dphone_email").style.visibility = visibility;
			document.getElementById("dphone_main").style.visibility = visibility;
			document.getElementById("dphone_mobile").style.visibility = visibility;
		}
	else if(what == 7)
		{
			visibility = "visible";
			document.getElementById("dphone_mobile").style.visibility = visibility;
			visibility = "hidden";
			document.getElementById("dphone_work").style.visibility = visibility;
			document.getElementById("dphone_home").style.visibility = visibility;
			document.getElementById("dphone_fax").style.visibility = visibility;
			document.getElementById("dphone_other").style.visibility = visibility;
			document.getElementById("dphone_email").style.visibility = visibility;
			document.getElementById("dphone_main").style.visibility = visibility;
			document.getElementById("dphone_pager").style.visibility = visibility;
		}
}


// open the popup window for the email.. send the email to be auto filled as parameter
function send_email_window(email)
{
	f = document.contacts_form;
	fname = f.fname.value;
	lname = f.lname.value;
	
	url = 'send_email.php?session_id='+session_id+'&email='+email+'&fname='+fname+'&lname='+lname;
	window.open(url,'send_email','directories=no,resizable=yes,location=no,menubar=no,scrollbars=yes,toolbar=no,width=600,height=500');
}