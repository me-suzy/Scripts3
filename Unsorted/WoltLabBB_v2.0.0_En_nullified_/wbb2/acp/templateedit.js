function init() {
 design_mode_on();
 teditor.document.open();
 teditor.document.write("");
 teditor.document.close();
 key_watch();
 
 teditor.focus();
 
 var sCrlf=String.fromCharCode(10,13);
 var textareaData = document.tform.template.value;
 teditor.document.body.innerText=textareaData + sCrlf + sCrlf + sCrlf;	
 if(teditor.document.body.innerHTML == "") teditor.document.body.innerHTML="&nbsp;";
 teditor.document.body.innerHTML='<nobr>'+teditor.document.body.innerHTML+'</nobr>';
 highlight();				
 
 teditor.document.body.style.fontFamily = "monospace";
 teditor.document.body.style.fontSize = "10pt";
}

function design_mode_on() {
 teditor.document.designMode="On";
}

function key_watch() {
 teditor.document.body.onkeydown=check_key_event;									
 teditor.document.body.ondrop=kill_drop_event;			
}	

function check_range(objRange) {
 var textLength=objRange.text.length;
 
 objRange.moveStart("character",textLength);
 tagONE = objRange.parentElement().tagName;
 objRange.moveStart("character",textLength*-1);
 objRange.moveEnd("character",textLength*-1);
 tagTWO = objRange.parentElement().tagName;
 objRange.moveEnd("character",textLength);
 objRange.moveEnd("character",1);
 tagTHI = objRange.parentElement().tagName;
 objRange.moveEnd("character",-1);	
	
 if (tagONE=="FONT" || tagTWO=="FONT" || tagTHI=="FONT") return false;
 else return true;	
}

function check_key_event() {			
 objEditorDoc=teditor.document;
 if (objEditorDoc.queryCommandValue("OverWrite")) objEditorDoc.execCommand("OverWrite","",false);																												

 var objEvent=teditor.window.event;
 var objRange=objEditorDoc.selection.createRange();		 						
  
 if (objEvent.keyCode>40 || objEvent.keyCode<35) {
  if(!check_range(objRange)) return false;
 }
 
 if (objEvent.keyCode==8) {
  objRange.moveStart("character",-2);
  objRange.moveEnd("character",-2);
  objCheckLeft=objRange.parentElement();			
  if (objCheckLeft.tagName=="FONT") return false;
 }
 			
 if (objEvent.keyCode==46) {
  objRange.moveStart("character",2);
  objCheckRight=objRange.parentElement();			
   if (objCheckRight.tagName=="FONT") return false;
 } 
 			
 if (objEvent.keyCode==13 && !objEvent.shiftKey) {					
  objRange.text=objRange.text + String.fromCharCode(13);
  objRange.moveStart("Character",0);
  objRange.moveEnd("Character",0);
  objRange.select();
  return false;
 } 
}

function kill_drop_event() {			
 return false;
}

function highlight() {
 ColorizeVariables("{","#000000","#00FF00",2);
 ColorizeVariables("$","#000000","#FFFF00",1);
 
 var sBuffer=teditor.document.body.innerHTML;
 teditor.document.body.innerHTML=sBuffer.replace(/{IO}/g,"");
}

function ColorizeVariables(sSearchValue,TextColor,BgColor,x) {	
 var oRange=null;
 var sBookMark=null;			
 if(x==1) var VarArray=ScanVariables1();									
 else var VarArray=ScanVariables2(sSearchValue);
 var sVarName="";
 var sCrlf="";
 var sCheck="";
						
 for (var i=0; i<VarArray.length; i++) {						
  if (VarArray[i] != "") {
   var fExit=false;
   oRange = teditor.document.body.createTextRange();			
							
   while (!fExit) { 									
    var sColorRangeText=VarArray[i];
    if(oRange.findText(sColorRangeText)) {
     sBookMark = oRange.getBookmark();  
     oRange.moveToBookmark(sBookMark);																						
										
     oRange.pasteHTML("<FONT style='BACKGROUND-COLOR: " + BgColor + "' color=" + TextColor + ">{*}</FONT>");						
												
     oRange = teditor.document.body.createTextRange();					
     oRange.findText("{*}");
     sBookMark = oRange.getBookmark();											
     oRange.moveToBookmark(sBookMark);  																						
     oRange.text="{IO}"+sColorRangeText;			
    }
    else fExit=true;											
   }
  }
 }
			
 return VarArray;			
}

function isinBuffer(buffer,value) {
 temp = buffer.split(",");
 x=false;
 for(ii=0;ii<temp.length;ii++) {
  if(temp[ii]==value) {
   x=true;
   break;
  }		
 }
 return x;
}

function ScanVariables1(sSearchValue) {
 var sHtmlData=teditor.document.body.innerText;
 var sVarBuffer="";
 var mode=0;
 var varbuffer="";
  
 for(i=0;i<sHtmlData.length;i++) {
  if(mode==1) {
   if(sHtmlData.charAt(i)=='>') {
    if(!isinBuffer(sVarBuffer,varbuffer)) sVarBuffer=sVarBuffer+","+varbuffer;
    varbuffer="";
    mode=0;
   }
   else varbuffer+=sHtmlData.charAt(i);
  }
  if(mode==0) {
   if(sHtmlData.charAt(i)=='<' && sHtmlData.charAt(i+1)=='$') {
    mode=1;
   }
  }
 }				
 return sVarBuffer.split(",");
}

function ScanVariables2(sSearchValue) {
 var sHtmlData=teditor.document.body.innerText;
 var sVarBuffer="";
 var mode=0;
 var varbuffer="";
  
 for(i=0;i<sHtmlData.length;i++) {
  if(mode==1) {
   if(sHtmlData.charAt(i)=='}') {
    if(!isinBuffer(sVarBuffer,varbuffer)) sVarBuffer=sVarBuffer+","+varbuffer;
    varbuffer="";
    mode=0;
   }
   else varbuffer+=sHtmlData.charAt(i);
  }
  if(mode==0) {
   if(sHtmlData.charAt(i)=='{') {
    mode=1;
   }
  }
 }				
 
 return sVarBuffer.split(",");
}

function formSubmit() {
 document.tform.template.value=teditor.document.body.innerText;
 document.tform.submit();
}

function clearFile() {
 var sCrlf=String.fromCharCode(10,13);
 teditor.document.body.innerText=sCrlf + sCrlf + sCrlf;	
 if(teditor.document.body.innerHTML == "") teditor.document.body.innerHTML="&nbsp;";
 teditor.document.body.innerHTML='<nobr>'+teditor.document.body.innerHTML+'</nobr>';
}

var n = 0;

function findInPage(str) {
 var txt, i, found;
 if(str == '') return false;
 txt = teditor.document.body.createTextRange();
 for(i = 0; i <= n && (found = txt.findText(str)) != false; i++) {
  txt.moveStart('character', 1);
  txt.moveEnd('textedit');
 }
 
 if(found) {
  txt.moveStart('character', -1);
  txt.findText(str);
  txt.select();
  txt.scrollIntoView();
  n++;
 }
 else {
  if(n > 0) {
   n = 0;
   findInPage(str);
  }
  else alert('Search word not found.');
 }
 return false;
}

function replaceInPage(str,replace) {
 var txt, i=0;
 if (str == '') return false;
 if (str == replace) return false; 
 txt = teditor.document.body.createTextRange();
 while (txt.findText(str)!= false) {
  txt.select();
  txt.scrollIntoView();
  
  obj_range=teditor.document.selection.createRange();
  if(check_range(obj_range)) obj_range.text=replace;
  
  txt.moveStart('character', 1);
  txt.moveEnd('textedit');
 }
}

function delVar() {
 var objRange=teditor.document.selection.createRange();		 						
 if(!check_range(objRange)) {
  count=0;
  do {
   objRange.moveStart("character",-1);
   count++;
  }
  while(objRange.parentElement().tagName=="FONT");
  objRange.moveStart("character",count);
  do {
   objRange.moveEnd("character",1);
  }
  while(objRange.parentElement().tagName=="FONT");
  objRange.moveStart("character",count*-1);
  
  objRange.text='';	
 }	
}