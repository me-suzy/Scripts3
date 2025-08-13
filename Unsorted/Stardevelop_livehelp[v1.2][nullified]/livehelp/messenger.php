<?php
/*
stardevelop.com Live Help
International Copyright stardevelop.com

You may not distribute this program in any manner,
modified or otherwise, without the express, written
consent from stardevelop.com

You may make modifications, but only for your own 
use and within the confines of the License Agreement.
All rights reserved.

Selling the code for this program without prior 
written consent is expressly forbidden. Obtain 
permission before redistributing this program over 
the Internet or in any other medium.  In all cases 
copyright and header must remain intact.  
*/
include('./include/config_database.php');
include('./include/class.mysql.php');
include('./include/config.php');

$language_file = './locale/lang_' . $language_type . '.php';
if (file_exists($language_file)) {
include($language_file);
}
else {
include('./locale/lang_en.php');
}

ignore_user_abort(true);

session_start();
$login_id = $_SESSION['LOGIN_ID'];
session_write_close();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo($livehelp_name); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/JavaScript">
<!--
function toggle(object) {
  if (document.getElementById) {
    if (document.getElementById(object).style.visibility == 'visible')
      document.getElementById(object).style.visibility = 'hidden';
    else
      document.getElementById(object).style.visibility = 'visible';
  }

  else if (document.layers && document.layers[object] != null) {
    if (document.layers[object].visibility == 'visible' ||
        document.layers[object].visibility == 'show' )
      document.layers[object].visibility = 'hidden';
    else
      document.layers[object].visibility = 'visible';
  }

  else if (document.all) {
    if (document.all[object].style.visibility == 'visible')
      document.all[object].style.visibility = 'hidden';
    else
      document.all[object].style.visibility = 'visible';
  }

  return false;
}

function high(theobject) {
  if (theobject.style.MozOpacity)
    theobject.style.MozOpacity=1
  else if (theobject.filters)
   theobject.filters.alpha.opacity=100
}

function low(which2) {
  if (which2.style.MozOpacity)
    which2.style.MozOpacity=0.3
  else if (which2.filters)
    which2.filters.alpha.opacity=30
}
//-->
</script>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);
//-->
</script>
</head>
<body bgcolor="<?php echo($background_color); ?>" background="<?php echo($background_image); ?>" text="<?php echo($font_color); ?>" link="<?php echo($font_link_color); ?>" vlink="<?php echo($font_link_color); ?>" alink="<?php echo($font_link_color); ?>">
<div id="Layer1" style="position:absolute; left:200px; top:0px; width:196px; height:60px; z-index:0; visibility: hidden;"><img src="icons/bubble.gif" width="220" height="80"></div>
<div id="Layer2" style="position:absolute; left:214px; top:12px; width:184px; height:49px; z-index:1; visibility: hidden;"> 
  <div align="center"> 
<a href="#" onClick="javascript:appendText(';-P');toggle('Layer1');toggle('Layer2');toggle('Layer3')"><img src="icons/22.gif" width="22" height="22" border="0" style="filter:alpha(opacity=30);-moz-opacity:0.3" onMouseover="high(this)" onMouseout="low(this)"></a><a href="#" onClick="javascript:appendText(':-$');toggle('Layer1');toggle('Layer2');toggle('Layer3')"><img src="icons/23.gif" width="22" height="22" border="0"  style="filter:alpha(opacity=30);-moz-opacity:0.3" onMouseover="high(this)" onMouseout="low(this)"></a><a href="#" onClick="javascript:appendText('8-)');toggle('Layer1');toggle('Layer2');toggle('Layer3')"><img src="icons/24.gif" width="22" height="22" border="0" style="filter:alpha(opacity=30);-moz-opacity:0.3" onMouseover="high(this)" onMouseout="low(this)"></a><a href="#" onClick="javascript:appendText(':-@');toggle('Layer1');toggle('Layer2');toggle('Layer3')"><img src="icons/25.gif" width="22" height="22" border="0" style="filter:alpha(opacity=30);-moz-opacity:0.3" onMouseover="high(this)" onMouseout="low(this)"></a><a href="#" onClick="javascript:appendText(':-()');toggle('Layer1');toggle('Layer2');toggle('Layer3')"><img src="icons/26.gif" width="22" height="22" border="0" style="filter:alpha(opacity=30);-moz-opacity:0.3" onMouseover="high(this)" onMouseout="low(this)"></a><a href="#" onClick="javascript:appendText(':-O');toggle('Layer1');toggle('Layer2');toggle('Layer3')"><img src="icons/27.gif" width="22" height="22" border="0" style="filter:alpha(opacity=30);-moz-opacity:0.3" onMouseover="high(this)" onMouseout="low(this)"></a><a href="#" onClick="javascript:appendText(':(');toggle('Layer1');toggle('Layer2');toggle('Layer3')"><img src="icons/28.gif" width="22" height="22" border="0" style="filter:alpha(opacity=30);-moz-opacity:0.3" onMouseover="high(this)" onMouseout="low(this)"></a><a href="#" onClick="javascript:appendText(';-)');toggle('Layer1');toggle('Layer2');toggle('Layer3')"><img src="icons/29.gif" width="22" height="22" border="0" style="filter:alpha(opacity=30);-moz-opacity:0.3" onMouseover="high(this)" onMouseout="low(this)"></a><a href="#" onClick="javascript:appendText(':-S');toggle('Layer1');toggle('Layer2');toggle('Layer3')"><img src="icons/30.gif" width="22" height="22" border="0" style="filter:alpha(opacity=30);-moz-opacity:0.3" onMouseover="high(this)" onMouseout="low(this)"></a><a href="#" onClick="javascript:appendText(':-|');toggle('Layer1');toggle('Layer2');toggle('Layer3')"><img src="icons/31.gif" width="22" height="22" border="0" style="filter:alpha(opacity=30);-moz-opacity:0.3" onMouseover="high(this)" onMouseout="low(this)"></a><a href="#" onClick="javascript:appendText(':-P');toggle('Layer1');toggle('Layer2');toggle('Layer3')"><img src="icons/32.gif" width="22" height="22" border="0" style="filter:alpha(opacity=30);-moz-opacity:0.3" onMouseover="high(this)" onMouseout="low(this)"></a><a href="#" onClick="javascript:appendText(':-D');toggle('Layer1');toggle('Layer2');toggle('Layer3')"><img src="icons/33.gif" width="22" height="22" border="0" style="filter:alpha(opacity=30);-moz-opacity:0.3" onMouseover="high(this)" onMouseout="low(this)"></a><a href="#" onClick="javascript:appendText(':-');toggle('Layer1');toggle('Layer2');toggle('Layer3')"><img src="icons/34.gif" width="22" height="22" border="0" style="filter:alpha(opacity=30);-moz-opacity:0.3" onMouseover="high(this)" onMouseout="low(this)"></a><a href="#" onClick="javascript:appendText(':-(');toggle('Layer1');toggle('Layer2');toggle('Layer3')"><img src="icons/35.gif" width="22" height="22" border="0" style="filter:alpha(opacity=30);-moz-opacity:0.3" onMouseover="high(this)" onMouseout="low(this)"></a><a href="#" onClick="javascript:appendText(':-)');toggle('Layer1');toggle('Layer2');toggle('Layer3')"><img src="icons/36.gif" width="22" height="22" border="0" style="filter:alpha(opacity=30);-moz-opacity:0.3" onMouseover="high(this)" onMouseout="low(this)"></a>
  </div>
</div>
<div id="Layer3" style="position:absolute; left:389px; top:39px; width:16; height:16; z-index:1; visibility: hidden;"><a href="#" onClick="toggle('Layer1');toggle('Layer2');toggle('Layer3')"><img src="icons/miniclose.gif" width="16" height="16" border="0"></a></div>
<br>
<div align="center">
<form name="message_form" method="GET" action="send_message.php">
    <p> 
      <textarea name="MESSAGE" cols="35" rows="2" onKeyPress="return checkEnter(event)"></textarea>
      <input type="hidden" name="FROM_LOGIN_ID" value="<?php echo($login_id); ?>">
      <a href="#" onClick="processForm()"><img src="icons/queue.gif" alt="<?php echo($language['send_msg']); ?>" width="32" height="32" border="0"></a>
	  <a href="#" onClick="toggle('Layer1');toggle('Layer2');toggle('Layer3')" ><img src="icons/36.gif" alt="<?php echo($language['add_smilie']); ?>" width="22" height="22" border="0"></a><br>
      <font size="1" face="<?php echo($font_type); ?>"><?php echo($language['stardevelop_copyright']); ?></font> </p>
</form>

<script language="JavaScript">
<!--
document.message_form.MESSAGE.focus();

function processForm() {
  void(document.message_form.submit());
  document.message_form.MESSAGE.value="";
  document.message_form.MESSAGE.focus();
}

function appendText(text) {
  var current = document.message_form.MESSAGE.value;
  document.message_form.MESSAGE.value = current + text;
  document.message_form.MESSAGE.focus();
}

function checkEnter(e) {
  var characterCode

  if(e && e.which){
    e = e
	characterCode = e.which
  }
  else{							
    e = event						
	characterCode = e.keyCode
  } 
  
  if(characterCode == 13){ 
    processForm()
    return false 
  }
  else{
    return true 
  }
}
//-->
</script>
</div>
</body>
</html>