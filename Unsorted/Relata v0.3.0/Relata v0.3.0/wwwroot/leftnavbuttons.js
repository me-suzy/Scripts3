// add a button to left nav and return the object
function addbutton(title)
{
	addbutton = document.createElement("DIV");
	addbutton.className = "add-button-norm";
	addbutton.id = "addbutton";
	document.body.appendChild(addbutton);
	addbutton.appendChild(document.createTextNode(title));
	
	addevent(addbutton,"mouseover",addbtnaction,true);
	addevent(addbutton,"mouseout",addbtnaction,true);
	addevent(addbutton,"mousedown",addbtnaction,true);
	addevent(addbutton,"mouseup",addbtnaction,true);
	addevent(addbutton,"click",addbtnaction,true);
	
	return addbutton;
}

// change the add button onmouseover,onmouseout,onmousedown
function addbtnaction(e)
{
	if(document.all) e = event;	
	
	addbutton = document.getElementById('addbutton');
	
	switch(e.type)
	{
		case "mouseover":
			addbutton.className = "add-button-over";
			break;
		case "mouseout":
			addbutton.className = "add-button-norm";
			break;
		case "mousedown":
			addbutton.className = "add-button-down";
			break;
		case "mouseup":
			addbutton.className = "add-button-over";
			break;
		case "click":
			if(typeof(extra_params) == "undefined")
			{
				extra_params = "";
			}
		
			eval("parent.main" + parent.winid + ".location.href = \"add.php?session_id=" + session_id + extra_params + "\"");
			break;
	}
}

function orderBy(what) {
var url = 'contact_left.php?order_by=' + what;

location.href = url;

}
