/***************************************************************
Copyright: OSI Codes
Author: Trent Arbor, trent@osicodes.com ("js king")

These functions are written by OSI Codes and is property of OSI Codes.
Modification or re-use of this code must comply with the OSI Codes
Software License Agreement.  A copy of the License Agreement is
located with the Software package.
***************************************************************/

function replace(target, oldT, newT)
{
	var work = target;
	var ind = 0;
	var next = 0;
	
	while((ind = work.indexOf(oldT,next)) >= 0)
	{
		target = target.substring(0,ind) + newT + target.substring(ind+oldT.length,target.length);
		work = work.substring(0,ind) + newT + work.substring(ind+oldT.length,work.length);

		next = ind + newT.length;
		if(next >= work.length) { break; }
	}
	return target;
}

function letternumber(e)
{
	var key;
	var keychar;

	if (window.event)
	key = window.event.keyCode;
	else if (e)
		key = e.which;
	else
		return true;

	keychar = String.fromCharCode(key);
	keychar = keychar.toLowerCase();

	if ((key==null) || (key==0) || (key==8) || (key==9) || (key==13) || (key==27) )
		return true;

	else if ((("abcdefghijklmnopqrstuvwxyz0123456789_-").indexOf(keychar) > -1))
		return true;
	else
		return false;
}

function numbersonly(e)
{
	var key;
	var keychar;

	if (window.event)
	key = window.event.keyCode;
	else if (e)
		key = e.which;
	else
		return true;

	keychar = String.fromCharCode(key);
	keychar = keychar.toLowerCase();

	if ((key==null) || (key==0) || (key==8) || (key==9) || (key==13) || (key==27) )
		return true;

	else if ((("0123456789").indexOf(keychar) > -1))
		return true;
	else
		return false;
}

function floatsonly(e)
{
	var key;
	var keychar;

	if (window.event)
	key = window.event.keyCode;
	else if (e)
		key = e.which;
	else
		return true;

	keychar = String.fromCharCode(key);
	keychar = keychar.toLowerCase();

	if ((key==null) || (key==0) || (key==8) || (key==9) || (key==13) || (key==27) )
		return true;

	else if ((("0123456789.").indexOf(keychar) > -1))
		return true;
	else
		return false;
}
