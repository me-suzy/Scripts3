switch(prefix)
{
	case "":
		f.prefix.options.selectedIndex = 0;
		break;
	case "mr":
		f.prefix.options.selectedIndex = 1;
		break;
	case "ms":
		f.prefix.options.selectedIndex = 2;
		break;
	case "mrs":
		f.prefix.options.selectedIndex = 3;
		break;
	case "dr":
		f.prefix.options.selectedIndex = 4;
		break;
	default:
		f.prefix.options.selectedIndex = 0;
		break;
}
switch(email_type)
{
	case "t":
		f.email_type.options.selectedIndex = 0;
		break;
	case "h":
		f.email_type.options.selectedIndex = 1;
		break;
	default:
		f.email_type.options.selectedIndex = 0;
}
switch(is_prospect)
{
	case "y":
		f.is_prospect.options.selectedIndex = 0;
		break;
	case "n":
		f.is_prospect.options.selectedIndex = 1;
		break;
	default:
		f.is_prospect.options.selectedIndex = 0;
}

f.contact_lbl1.selectedIndex = contact_lbl1;
f.contact_lbl2.selectedIndex = contact_lbl2;
f.contact_lbl3.selectedIndex = contact_lbl3;
f.contact_lbl4.selectedIndex = contact_lbl4;
f.contact_lbl5.selectedIndex = contact_lbl5;


if(f.palm_dphone.value == 1)
{
	f.palm_dphone[0].checked = true;
	dphone_button = f.contact_lbl1.selectedIndex;
}
else if(f.palm_dphone.value == 2)
{
	f.palm_dphone[1].checked = true;
	dphone_button = f.contact_lbl2.selectedIndex;
}
else if(f.palm_dphone.value == 3)
{
	f.palm_dphone[2].checked = true;
	dphone_button = f.contact_lbl3.selectedIndex;
}
else if(f.palm_dphone.value == 4)
{
	f.palm_dphone[3].checked = true;
	dphone_button = f.contact_lbl4.selectedIndex;
}
else if(f.palm_dphone.value == 5)
{
	f.palm_dphone[4].checked = true;
	dphone_button = f.contact_lbl5.selectedIndex;
}
else
{
	f.palm_dphone[0].checked = true;
	dphone_button = f.contact_lbl1.selectedIndex;
}

if(dphone_button == 0)
	{
	  		visibility = "visible";
	 		f.document.getElementById("dphone_work").style.visibility = visibility;
			visibility = "hidden";
			f.document.getElementById("dphone_home").style.visibility = visibility;
			f.document.getElementById("dphone_fax").style.visibility = visibility;
			f.document.getElementById("dphone_other").style.visibility = visibility;
			f.document.getElementById("dphone_email").style.visibility = visibility;
			f.document.getElementById("dphone_main").style.visibility = visibility;
			f.document.getElementById("dphone_pager").style.visibility = visibility;
			f.document.getElementById("dphone_mobile").style.visibility = visibility;
		}
	else if(dphone_button == 1)
		{
			visibility = "visible";
			f.document.getElementById("dphone_home").style.visibility = visibility;
			visibility = "hidden";
			f.document.getElementById("dphone_work").style.visibility = visibility;
			f.document.getElementById("dphone_fax").style.visibility = visibility;
			f.document.getElementById("dphone_other").style.visibility = visibility;
			f.document.getElementById("dphone_email").style.visibility = visibility;
			f.document.getElementById("dphone_main").style.visibility = visibility;
			f.document.getElementById("dphone_pager").style.visibility = visibility;
			f.document.getElementById("dphone_mobile").style.visibility = visibility;
		}
	else if(dphone_button == 2)
		{
			visibility = "visible";
			f.document.getElementById("dphone_fax").style.visibility = visibility;
			visibility = "hidden";
			f.document.getElementById("dphone_work").style.visibility = visibility;
			f.document.getElementById("dphone_home").style.visibility = visibility;
			f.document.getElementById("dphone_other").style.visibility = visibility;
			f.document.getElementById("dphone_email").style.visibility = visibility;
			f.document.getElementById("dphone_main").style.visibility = visibility;
			f.document.getElementById("dphone_pager").style.visibility = visibility;
			f.document.getElementById("dphone_mobile").style.visibility = visibility;
		}
	else if(dphone_button == 3)
		{
			visibility = "visible";
			f.document.getElementById("dphone_other").style.visibility = visibility;
			visibility = "hidden";
			f.document.getElementById("dphone_work").style.visibility = visibility;
			f.document.getElementById("dphone_home").style.visibility = visibility;
			f.document.getElementById("dphone_fax").style.visibility = visibility;
			f.document.getElementById("dphone_email").style.visibility = visibility;
			f.document.getElementById("dphone_main").style.visibility = visibility;
			f.document.getElementById("dphone_pager").style.visibility = visibility;
			f.document.getElementById("dphone_mobile").style.visibility = visibility;
		}
	else if(dphone_button == 4)
		{
			visibility = "visible";
			f.document.getElementById("dphone_email").style.visibility = visibility;
			visibility = "hidden";
			f.document.getElementById("dphone_work").style.visibility = visibility;
			f.document.getElementById("dphone_home").style.visibility = visibility;
			f.document.getElementById("dphone_fax").style.visibility = visibility;
			f.document.getElementById("dphone_other").style.visibility = visibility;
			f.document.getElementById("dphone_main").style.visibility = visibility;
			f.document.getElementById("dphone_pager").style.visibility = visibility;
			f.document.getElementById("dphone_mobile").style.visibility = visibility;
		}
	else if(dphone_button == 5)
		{
			visibility = "visible";
			f.document.getElementById("dphone_main").style.visibility = visibility;
			visibility = "hidden";
			f.document.getElementById("dphone_work").style.visibility = visibility;
			f.document.getElementById("dphone_home").style.visibility = visibility;
			f.document.getElementById("dphone_fax").style.visibility = visibility;
			f.document.getElementById("dphone_other").style.visibility = visibility;
			f.document.getElementById("dphone_email").style.visibility = visibility;
			f.document.getElementById("dphone_pager").style.visibility = visibility;
			f.document.getElementById("dphone_mobile").style.visibility = visibility;
		}
	else if(dphone_button == 6)
		{
			visibility = "visible";
			f.document.getElementById("dphone_pager").style.visibility = visibility;
			visibility = "hidden";
			f.document.getElementById("dphone_work").style.visibility = visibility;
			f.document.getElementById("dphone_home").style.visibility = visibility;
			f.document.getElementById("dphone_fax").style.visibility = visibility;
			f.document.getElementById("dphone_other").style.visibility = visibility;
			f.document.getElementById("dphone_email").style.visibility = visibility;
			f.document.getElementById("dphone_main").style.visibility = visibility;
			f.document.getElementById("dphone_mobile").style.visibility = visibility;
		}
	else if(dphone_button == 7)
		{
			visibility = "visible";
			f.document.getElementById("dphone_mobile").style.visibility = visibility;
			visibility = "hidden";
			f.document.getElementById("dphone_work").style.visibility = visibility;
			f.document.getElementById("dphone_home").style.visibility = visibility;
			f.document.getElementById("dphone_fax").style.visibility = visibility;
			f.document.getElementById("dphone_other").style.visibility = visibility;
			f.document.getElementById("dphone_email").style.visibility = visibility;
			f.document.getElementById("dphone_main").style.visibility = visibility;
			f.document.getElementById("dphone_pager").style.visibility = visibility;
		}
	else
		{
			visibility = "hidden";
			f.document.getElementById("dphone_mobile").style.visibility = visibility;
			f.document.getElementById("dphone_work").style.visibility = visibility;
			f.document.getElementById("dphone_home").style.visibility = visibility;
			f.document.getElementById("dphone_fax").style.visibility = visibility;
			f.document.getElementById("dphone_other").style.visibility = visibility;
			f.document.getElementById("dphone_email").style.visibility = visibility;
			f.document.getElementById("dphone_main").style.visibility = visibility;
			f.document.getElementById("dphone_pager").style.visibility = visibility;
		}


parent.show_email_button(f.contact_lbl1);
parent.show_email_button(f.contact_lbl2);
parent.show_email_button(f.contact_lbl3);
parent.show_email_button(f.contact_lbl4);
parent.show_email_button(f.contact_lbl5);
