// refresh group email page
function group_email_action(e)
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
	
	// make the mouseover effects
	button_effects(e.type,btn,btnid);
	
	if(e.type=="click" && btnid=="refresh")
	{
		// reload the main frame
		eval("parent.main" + parent.winid + ".location.reload();");
	}
	else if(e.type=="click" && btnid=="print")
	{
		// focus, then print the main frame
		eval("parent.main" + parent.winid + ".focus();");
		eval("parent.main" + parent.winid + ".print();");
	}
}

// print group email page
function group_email_print(e)
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
	
	// make the mouseover effects
	button_effects(e.type,btn,btnid);
	
	if(e.type == "click")
	{
		// focus, then print the main frame
		eval("parent.main" + winid + ".focus();");
		eval("parent.main" + winid + ".print();");
	}
}