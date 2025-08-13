sortitems = false;  					// Automatically sort items within lists? (true or false)

if(document.all) elid = 4;
else elid = 3;

f = document.opportunity_form;

// The function selectAll and store are for passing multiple select values to a form
// processing script, which will be the focus of an upcoming article.
function selectall()
{
	for (i = 0; i < f.elements[elid].length; i++)
	{  
        if (f.elements[elid].options[i] != -1)
		{
			f.elements[elid].options[i].selected = true; 
			validForm = 1;          
		}
	}
}

// add a new available contact
function new_avail_contact(text,id)
{
	f.avail_contacts.options[f.avail_contacts.length] = new Option(text,id);
}

// add a new 'new opportunity contact'
function new_opp_contact(text,id)
{
	f.elements[elid].options[f.elements[elid].length] = new Option(text,id);
}

// called when loading a new record to clear the array
function clear_opp_contacts()
{
	f.elements[elid].options.length = 0;
	f.avail_contacts.options.length = 0;
}

// move contacts back and forth
function move(fbox,tbox) 
{
	for(var i=0; i<fbox.options.length; i++) 
	{
		if(fbox.options[i].selected && fbox.options[i].value != "") 
		{
			var no = new Option();
			no.value = fbox.options[i].value;
			no.text = fbox.options[i].text;
			tbox.options[tbox.options.length] = no;
			fbox.options[i].value = "";
			fbox.options[i].text = "";
   		}
	}
	
	BumpUp(fbox);

	if (sortitems) SortD(tbox);
}

// after adding a new record to the box it has to bump up the other records
function BumpUp(box)  
{
	for(var i=0; i<box.options.length; i++) 
	{
		if(box.options[i].value == "")  
		{
			for(var j=i; j<box.options.length-1; j++)  
			{
				box.options[j].value = box.options[j+1].value;
				box.options[j].text = box.options[j+1].text;
			}
			
			var ln = i;
			break;
   		}
	}

	if(ln < box.options.length)  
	{
		box.options.length -= 1;
		BumpUp(box);
   }
}

// sort the contents
function SortD(box)  
{
	var temp_opts = new Array();
	var temp = new Object();
	for(var i=0; i<box.options.length; i++)  
	{
		temp_opts[i] = box.options[i];
	}

	for(var x=0; x<temp_opts.length-1; x++)  
	{
		for(var y=(x+1); y<temp_opts.length; y++)  
		{
			if(temp_opts[x].text > temp_opts[y].text)  
			{
				temp = temp_opts[x].text;
				temp_opts[x].text = temp_opts[y].text;
				temp_opts[y].text = temp;
				temp = temp_opts[x].value;
				temp_opts[x].value = temp_opts[y].value;
				temp_opts[y].value = temp;
	      	}
		}
	}

	for(var i=0; i<box.options.length; i++)  
	{
		box.options[i].value = temp_opts[i].value;
		box.options[i].text = temp_opts[i].text;
	}
}