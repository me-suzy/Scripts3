<!--
winprop = 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,';
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}

function winhelp(topic, w, h){
  topicwin = window.open( 'help.php?topic=' + topic, 'topic', winprop + ',width=' + w + ',height=' + h + '  ');
    if ( (navigator.appName != "Microsoft Internet Explorer") && (navigator.appVersion.substring(0,1) == "3") )
  topicwin.focus();
}
function refer(w,h){
  referwin = window.open( 'emailfriend.php', 'referwin', winprop + ',width=' + w + ',height=' + h + '  ');
    if ( (navigator.appName != "Microsoft Internet Explorer") && (navigator.appVersion.substring(0,1) == "3") )
  referwin.focus();
}
// *******************************************************
function viewpoem(formObj){
  PoemID = formObj.card_poem.options[formObj.card_poem.selectedIndex].value;
  if(PoemID != ""){
    poemwin=window.open('help.php?topic=poem&poem_id='+PoemID, 'poem', winprop + 'width=550,height=270');
    poemwin.focus();
  }else{ alert(msg_alert_poem);}
}
// *******************************************************
var soundwin;
function playmusic(formObj){
  if(soundwin && soundwin.open && !soundwin.close) {soundwin.focus();
  }else{
    song = formObj.card_sound.options[formObj.card_sound.selectedIndex].value;
    winStats = winprop;
      if (navigator.appName.indexOf("Microsoft")>=0){ winStats+=',width=225,height=195,left=300,top=300';
	}else{ winStats+=',width=250,height=220,screenX=300,screenY=300,alwaysRaised=yes'; }
	if(song == ""){ alert(msg_alert_music)
	}else{
	soundwin=window.open('help.php?topic=music&song='+song,'soundwin', winStats); }
  }
}
// *******************************************************
var addressbookwin;
function openaddressbook(){
  if (addressbookwin && addressbookwin.open && !addressbookwin.closed) {addressbookwin.focus();
  }else{
  var tp,lft;
  lft=(screen.availWidth/2)-225;
  tp=(screen.availHeight/2)-187;
  addressbookwin=window.open('addrbook.php?action=','addressbookwin',winprop + 'dependent=0,width=420,height=400,screenX=' + lft + ',screenY=' +tp + ',top=' + tp + ',left=' + lft);
  }
}
function closeaddressbook(){
  if (addressbookwin && addressbookwin.open && !addressbookwin.closed) {addressbookwin.close();} 
};
// *******************************************************
function changestamp() {
  current = document.vCardform.card_stamp.selectedIndex;
  document.images.sample.src = imagedir + document.vCardform.card_stamp[current].value;
}
function changebg() {
  current = document.vCardform.card_background.selectedIndex;
  document.images.sample.src = imagedir + document.vCardform.card_background[current].value;
}
// *******************************************************
function smilie(thesmilie) {
  document.vCardform.card_message.value += thesmilie+" ";
  document.vCardform.card_message.focus();
}
// *******************************************************
function DisplayInfo(pagina,janela,width,height,scrolling) {
 if (!scrolling) { scrolling='auto' }
 resultado = window.open(pagina,janela,'width='+width+',height='+height+',scrollbars='+scrolling+',toolbar=no,location=no,status=no,menubar=no,resizable=no,left=550,top=5')
}
function openFeedbackWindow(thePage){feedbackWin=window.open(thePage,'feedback','width=300,height=300,scrollbars')}
function openPrintWindow(thePage){printWin=window.open(thePage,'print')}
function openEmailWindow(thePage){emailWin=window.open(thePage,'email','width=340,height=450')}
function openWindow(url,w,h){var winame='popup';popupWin=window.open(url,winame,'scrollbars,menubar,resizeable,width='+w+',height='+h);}
// Rating
function js_rating(jan) {
DisplayInfo('',jan,300,300);
}
//-->