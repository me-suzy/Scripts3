function mOvr(src,clrOver){ 
	if (!src.contains(event.fromElement)){ 
		src.style.cursor = 'hand'; 
		src.bgColor = clrOver; 
	} 
} 
function mOut(src,clrIn){ 
	if (!src.contains(event.toElement)){ 
		src.style.cursor = 'default'; 
		src.bgColor = clrIn; 
	} 
} 
function mClk(src){ 
	if(event.srcElement.tagName=='TD')
		src.children.tags('A')[0].click();
}
	function GoAddressWindow(w)
	{
		if ("undefined" != typeof(GoAddressWindowOld))
		{
			GoAddressWindowOld(w);
		}
	}
var L_H_TEXT = "the Compose page";
var H_KEY = "HM_Compose";