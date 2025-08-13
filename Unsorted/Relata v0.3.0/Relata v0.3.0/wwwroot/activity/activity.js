// changes the right frame and updates the history
function changerightframe(url)
{
	eval('parent.main' + parent.winid + '.location.href="' + url + '"');
	
	// save the current url we are on relative to the array
	parent.btnhistory_cnt++;
	// save the url
	parent.btnhistory[parent.btnhistory_cnt] = url;
	
	// enable back btn
	parent.toolbar.enablebackbtn();
}

// setup the initial url to element 0 in the array.. called once on page load
function setuphistory()
{
	parent.btnhistory[parent.btnhistory_cnt] = eval("parent.main" + parent.winid + ".location.href;");
}