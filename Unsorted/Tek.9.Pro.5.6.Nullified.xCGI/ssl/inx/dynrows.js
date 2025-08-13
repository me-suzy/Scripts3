function findTag(eItem){
var objATag = new Object();
objATag = eItem.all.tags("A").item(0)
return objATag;}
function ovrRow(eItem){eItem.DefaultBgColor=eItem.style.backgroundColor;eItem.style.backgroundColor="#CCCC99";eItem.style.cursor="hand"}
function outRow(eItem){eItem.style.backgroundColor=eItem.DefaultBgColor;eItem.style.cursor="hand"}
function ovrCell(eItem){eItem.style.backgroundColor="#CCCC99";eItem.style.cursor="hand"}
function outCell(eItem){eItem.style.backgroundColor="#E5E5CA";eItem.style.cursor="hand"}
function go(eItem){top.location.href= findTag(eItem).getAttribute("HREF")}
