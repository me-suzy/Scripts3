// IE / N6 event listener
// ex: addevent(button,"click",dosomething,false);
function addevent(obj, evType, fn, useCapture)
{
	// check for standards compliant browser
	if (obj.addEventListener)
	{
		obj.addEventListener(evType, fn, useCapture);
		return true;
	}
	// else use the IE specific event listeners
	else if (obj.attachEvent)
	{
		var r = obj.attachEvent("on"+evType, fn);
		return r;
	}
}
// remove event listener
function removeevent(obj, evType, fn, useCapture)
{
	if (obj.removeEventListener)
	{
    	obj.removeEventListener(evType, fn, useCapture);
    	return true;
	} 
	else if (obj.detachEvent)
	{
    	var r = obj.detachEvent("on"+evType, fn);
    	return r;
	} 
	else 
	{
    	alert("Handler could not be removed");
	}
}

