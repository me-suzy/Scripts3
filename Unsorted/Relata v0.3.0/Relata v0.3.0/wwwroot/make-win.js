// win object
// takes title of page, url & unique identifier id and the number of windows currently created (cnt)
// usage : mywin = new win("Contact Manager", "http://www.relata.org/contactmanager",2,5);

// ie 5.0 doesn't support creation of IFRAMES using JS
if(navigator.userAgent.indexOf("MSIE 5.0") != -1)
{
	document.write('<iframe id="contentarea0" style="display: none;"></iframe>');
	document.write('<iframe id="contentarea1" style="display: none;"></iframe>');
	document.write('<iframe id="contentarea2" style="display: none;"></iframe>');
	document.write('<iframe id="contentarea3" style="display: none;"></iframe>');
	document.write('<iframe id="contentarea4" style="display: none;"></iframe>');
	document.write('<iframe id="contentarea5" style="display: none;"></iframe>');
}

win = function(title, url, id)
{
	// setup the object variables
	this.title 	= title;
	this.url	= url;
	this.id		= id;
	
	// ie 5.0 doesn't support creation of IFRAMES w/ javascript
	if(navigator.userAgent.indexOf("MSIE 5.0") != -1)
	{
		this.contentarea = document.getElementById('contentarea'+id);
	}
	else
	{
			this.contentarea = document.createElement("IFRAME");
	}
	
	// setup the new window	
	this.contentarea.style.height 		= "100%";
	this.contentarea.style.border 		= "0px";
	this.contentarea.style.width 		= "100%";	
	this.contentarea.style.position		= "absolute";
	this.contentarea.style.visibility	= "visible";
	this.contentarea.src 				= url;
	this.contentarea.id 				= "contentarea" + id;
	document.body.appendChild(this.contentarea);
	
	// add it to the taskbar
	this.task = parent.frames[2].addtask(title,id);
}