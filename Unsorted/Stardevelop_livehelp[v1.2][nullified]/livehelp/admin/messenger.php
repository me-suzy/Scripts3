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
include('../include/config_database.php');
include('../include/class.mysql.php');
include('../include/config.php');
include('../include/auth.php');

$language_file = '../locale/lang_' . $language_type . '.php';
if (file_exists($language_file)) {
include($language_file);
}
else {
include('../locale/lang_en.php');
}

ignore_user_abort(true);

$SQLDISPLAY = new mySQL; 
$SQLDISPLAY->connect();

session_start();
$current_session = session_id();
$login_id = $_SESSION['LOGIN_ID'];
$username = $_SESSION['USERNAME'];
$ip_address = $_SESSION['IP_ADDRESS'];
session_write_close();

if (!isset($_GET['LOGIN_ID'])){ $_GET['LOGIN_ID'] = ""; }
if (!isset($_GET['USER'])){ $_GET['USER'] = ""; }

if ($_GET['LOGIN_ID'] != "" && $_GET['USER'] != ""){
	$guest_login_id = $_GET['LOGIN_ID'];
	$guest_username = $_GET['USER'];
}
else {
	$guest_login_id = "";
	$guest_username = "";
}

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
<link href="../include/styles.css" rel="stylesheet" type="text/css">
</head>
<body onFocus="parent.document.title = 'Admin <?php echo($livehelp_name); ?>'";>
<div id="Layer1" style="position:absolute; left:253px; top:-7px; width:196px; height:60px; z-index:1; visibility: hidden;"><img src="../icons/bubble.gif" width="220" height="80"></div>
<div id="Layer2" style="position:absolute; left:270px; top:6px; width:184px; height:49px; z-index:2; visibility: hidden;"> 
  <div align="center"> <a href="#" onClick="javascript:appendText(';-P');toggle('Layer1');toggle('Layer2');toggle('Layer3')"><img src="../icons/22.gif" width="22" height="22" border="0" style="filter:alpha(opacity=30);-moz-opacity:0.3" onMouseover="high(this)" onMouseout="low(this)"></a><a href="#" onClick="javascript:appendText(':-$');toggle('Layer1');toggle('Layer2');toggle('Layer3')"><img src="../icons/23.gif" width="22" height="22" border="0"  style="filter:alpha(opacity=30);-moz-opacity:0.3" onMouseover="high(this)" onMouseout="low(this)"></a><a href="#" onClick="javascript:appendText('8-)');toggle('Layer1');toggle('Layer2');toggle('Layer3')"><img src="../icons/24.gif" width="22" height="22" border="0" style="filter:alpha(opacity=30);-moz-opacity:0.3" onMouseover="high(this)" onMouseout="low(this)"></a><a href="#" onClick="javascript:appendText(':-@');toggle('Layer1');toggle('Layer2');toggle('Layer3')"><img src="../icons/25.gif" width="22" height="22" border="0" style="filter:alpha(opacity=30);-moz-opacity:0.3" onMouseover="high(this)" onMouseout="low(this)"></a><a href="#" onClick="javascript:appendText(':-()');toggle('Layer1');toggle('Layer2');toggle('Layer3')"><img src="../icons/26.gif" width="22" height="22" border="0" style="filter:alpha(opacity=30);-moz-opacity:0.3" onMouseover="high(this)" onMouseout="low(this)"></a><a href="#" onClick="javascript:appendText(':-O');toggle('Layer1');toggle('Layer2');toggle('Layer3')"><img src="../icons/27.gif" width="22" height="22" border="0" style="filter:alpha(opacity=30);-moz-opacity:0.3" onMouseover="high(this)" onMouseout="low(this)"></a><a href="#" onClick="javascript:appendText(':(');toggle('Layer1');toggle('Layer2');toggle('Layer3')"><img src="../icons/28.gif" width="22" height="22" border="0" style="filter:alpha(opacity=30);-moz-opacity:0.3" onMouseover="high(this)" onMouseout="low(this)"></a><a href="#" onClick="javascript:appendText(';-)');toggle('Layer1');toggle('Layer2');toggle('Layer3')"><img src="../icons/29.gif" width="22" height="22" border="0" style="filter:alpha(opacity=30);-moz-opacity:0.3" onMouseover="high(this)" onMouseout="low(this)"></a><a href="#" onClick="javascript:appendText(':-S');toggle('Layer1');toggle('Layer2');toggle('Layer3')"><img src="../icons/30.gif" width="22" height="22" border="0" style="filter:alpha(opacity=30);-moz-opacity:0.3" onMouseover="high(this)" onMouseout="low(this)"></a><a href="#" onClick="javascript:appendText(':-|');toggle('Layer1');toggle('Layer2');toggle('Layer3')"><img src="../icons/31.gif" width="22" height="22" border="0" style="filter:alpha(opacity=30);-moz-opacity:0.3" onMouseover="high(this)" onMouseout="low(this)"></a><a href="#" onClick="javascript:appendText(':-P');toggle('Layer1');toggle('Layer2');toggle('Layer3')"><img src="../icons/32.gif" width="22" height="22" border="0" style="filter:alpha(opacity=30);-moz-opacity:0.3" onMouseover="high(this)" onMouseout="low(this)"></a><a href="#" onClick="javascript:appendText(':-D');toggle('Layer1');toggle('Layer2');toggle('Layer3')"><img src="../icons/33.gif" width="22" height="22" border="0" style="filter:alpha(opacity=30);-moz-opacity:0.3" onMouseover="high(this)" onMouseout="low(this)"></a><a href="#" onClick="javascript:appendText(':-');toggle('Layer1');toggle('Layer2');toggle('Layer3')"><img src="../icons/34.gif" width="22" height="22" border="0" style="filter:alpha(opacity=30);-moz-opacity:0.3" onMouseover="high(this)" onMouseout="low(this)"></a><a href="#" onClick="javascript:appendText(':-(');toggle('Layer1');toggle('Layer2');toggle('Layer3')"><img src="../icons/35.gif" width="22" height="22" border="0" style="filter:alpha(opacity=30);-moz-opacity:0.3" onMouseover="high(this)" onMouseout="low(this)"></a><a href="#" onClick="javascript:appendText(':-)');toggle('Layer1');toggle('Layer2');toggle('Layer3')"><img src="../icons/36.gif" width="22" height="22" border="0" style="filter:alpha(opacity=30);-moz-opacity:0.3" onMouseover="high(this)" onMouseout="low(this)"></a> 
  </div>
</div>
<div id="Layer3" style="position:absolute; left:443px; top:32px; width:16px; height:16px; z-index:2; visibility: hidden;"><a href="#" onClick="toggle('Layer1');toggle('Layer2');toggle('Layer3')"><img src="../icons/miniclose.gif" width="16" height="16" border="0"></a></div>
<div align="center"> 
  <form name="message_form" method="GET" action="../send_message.php">
    <table width="480" border="0" cellspacing="2" cellpadding="2">
      <tr> 
        <td colspan="2"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['chatting_with']); ?>&nbsp; 
          <?php
		  if ($guest_username == "" ) {
		  	echo($language['click_chat_user']);
		  }
		  else {
		  	$query_select_server = "SELECT server FROM " . $table_prefix . "sessions WHERE login_id = '$guest_login_id'";
		  	$row = $SQLDISPLAY->selectquery($query_select_server);
		  	if (is_array($row)) {
		  	  $server = $row['server'];
		  	}
		  	echo(' ' . $guest_username . '@' . $server);
		  }
		  ?>
          </font></td>
      </tr>
      <tr> 
        <td colspan="2"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#CBE7F7">
            <tr> 
              <td width="20" height="20"><img src="../images/bottom_left.gif" width="20" height="20"></td>
              <td rowspan="3"> <div align="center"> 
                  <textarea name="MESSAGE" cols="40" rows="2" onKeyPress="return checkEnter(event)"></textarea>
                  <input type="hidden" name="FROM_LOGIN_ID" value="<?php echo($login_id); ?>">
                  <input type="hidden" name="TO_LOGIN_ID" value="<?php echo($guest_login_id); ?>">
                  <a href="#" onClick="processForm()"><img src="../icons/queue.gif" alt="<?php echo($language['send_msg']); ?>" width="32" height="32" border="0"></a> 
                  <a href="#" onClick="toggle('Layer1');toggle('Layer2');toggle('Layer3')" ><img src="../icons/36.gif" alt="<?php echo($language['add_smilie']); ?>" width="22" height="22" border="0"></a></div></td>
              <td width="20" height="20"><img src="../images/bottom_right.gif" width="20" height="20"></td>
            </tr>
            <tr> 
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr> 
              <td width="20" height="20"><img src="../images/header_left.gif" width="20" height="20"></td>
              <td width="20" height="20"><img src="../images/header_right.gif" width="20" height="20"></td>
            </tr>
          </table></td>
      </tr>
      <tr> 
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['responses']); ?>:</font></div></td>
        <td><select name="RESPONSES" id="RESPONSES" width="300" style="width:300px;">
        <?php
		$query_select_responses = "SELECT contents FROM " . $table_prefix . "responses";
		$rows = $SQLDISPLAY->selectall($query_select_responses);
		if (is_array($rows)) {
			foreach($rows as $row) {
				if (is_array($row)) {
		?>
             <option value="<?php echo($row['contents']); ?>"><?php echo($row['contents']); ?></option>
        <?php
				}
			}
		?>
          </select>
          <a href="#" onClick="appendResponse()"><img src="../icons/mail_send.gif" alt="<?php echo($language['append_response']); ?>" width="22" height="22" border="0"></a>&nbsp;<a href="manage_responses.php?<?php echo(SID); ?>" target="displayFrame"><img src="../icons/mail_edit.gif" alt="<?php echo($language['edit_responses']); ?>" width="22" height="22" border="0"></a> 
        <?php
		}
		else {
		?>
            <option value=""><?php echo($language['click_add_response']); ?></option>
		  </select>
		<a href="manage_responses.php?<?php echo(SID); ?>" target="displayFrame"><img src="../icons/mail_edit.gif" alt="<?php echo($language['edit_responses']); ?>" width="22" height="22" border="0"></a> 
        <?php
		}
		?>
        </td>
      </tr>
      <tr> 
        <td><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['commands']); ?>:</font></div></td>
        <td> <select name="COMMANDS" id="COMMANDS" width="300" style="width:300px;">
        <?php
		$query_select_commands = "SELECT contents,description,type FROM " . $table_prefix . "commands";
		$rows = $SQLDISPLAY->selectall($query_select_commands);
		if (is_array($rows)) {
			foreach($rows as $row) {
				if (is_array($row)) {
					?>
             <option value="<?php echo($row['contents']); ?>"><?php echo($row['type'] . " " . $row['description']); ?></option>
        <?php
				}
			}
		?>
          </select>
          <a href="#" onClick="appendCommand()"><img src="../icons/mail_send.gif" alt="<?php echo($language['append_command']); ?>" width="22" height="22" border="0"></a>&nbsp;<a href="manage_commands.php?<?php echo(SID); ?>" target="displayFrame"><img src="../icons/mail_edit.gif" alt="<?php echo($language['edit_commands']); ?>" width="22" height="22" border="0"></a> 
        <?php
		}
		else {
		?>
            <option value=""><?php echo($language['click_add_command']); ?></option>
		</select>
		<a href="manage_commands.php?<?php echo(SID); ?>" target="displayFrame"><img src="../icons/mail_edit.gif" alt="<?php echo($language['edit_commands']); ?>" width="22" height="22" border="0"></a> 
        <?php
		}
		?>
        </td>
      </tr>
      <tr> 
        <td colspan="2"><div align="center"> 
            <?php
		// query database to see if login is admin
		$query_select_admin = "SELECT user_id FROM " . $table_prefix . "users WHERE last_login_id = '$guest_login_id'";
		$row = $SQLDISPLAY->selectquery($query_select_admin);
		if (!(is_array($row))) {
			if ($guest_login_id != "") {
		?>
            <table border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td width="30"><div align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><a href="transfer_user.php?<?php echo(SID); ?>&LOGIN_ID=<?php echo($guest_login_id); ?>&USER=<?php echo($guest_username); ?>" target="displayFrame"><img src="../icons/reload.gif" alt="<?php echo($language['transfer_user']); ?>" border="0"></a></font></div></td>
                <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><a href="transfer_user.php?<?php echo(SID); ?>&LOGIN_ID=<?php echo($guest_login_id); ?>&USER=<?php echo($guest_username); ?>" target="displayFrame" class="normlink"><?php echo($language['transfer_user']); ?></a></font></td>
                <td width="20"><div align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">::</font></div></td>
                <td width="30"><div align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><a href="view_statistics.php?<?php echo(SID); ?>&LOGIN_ID=<?php echo($guest_login_id); ?>" target="displayFrame"><img src="../icons/stats_small.gif" alt="<?php echo($language['visitor_stats']); ?>" border="0"></a></font></div></td>
                <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><a href="view_statistics.php?<?php echo(SID); ?>&LOGIN_ID=<?php echo($guest_login_id); ?>" target="displayFrame" class="normlink"><?php echo($language['visitor_stats']); ?></a></font></td>
                <td width="20"><div align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">::</font></div></td>
                <td width="30"><div align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><a href="print_display.php?<?php echo(SID); ?>&LOGIN_ID=<?php echo($guest_login_id); ?>&USER=<?php echo($guest_username); ?>" target="displayFrame"><img src="../icons/fileprint.gif" alt="<?php echo($language['print_chat']); ?>" width="22" height="22" border="0"></a></font></div></td>
                <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><a href="print_display.php?<?php echo(SID); ?>&LOGIN_ID=<?php echo($guest_login_id); ?>&USER=<?php echo($guest_username); ?>" target="displayFrame" class="normlink"><?php echo($language['print_chat']); ?></a></font></td>
                <td width="20"><div align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">::</font></div></td>
                <td width="30"><div align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><a href="displayer_frame.php?<?php echo(SID); ?>&LOGIN_ID=<?php echo($guest_login_id); ?>&USER=<?php echo($guest_username); ?>" target="displayFrame"><img src="../icons/chat.gif" alt="<?php echo($language['display_chat']); ?>" width="22" height="22" border="0"></a></font></div></td>
                <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><a href="displayer_frame.php?<?php echo(SID); ?>&LOGIN_ID=<?php echo($guest_login_id); ?>&USER=<?php echo($guest_username); ?>" target="displayFrame" class="normlink"><?php echo($language['display_chat']); ?></a></font></td>
              </tr>
            </table>
            <?php
			}
		}
		
		$SQLDISPLAY->disconnect();
		?>
          </div></td>
      </tr>
    </table>
    <font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['stardevelop_copyright']); ?></font> 
    - <font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo($language['stardevelop_livehelp_version']); ?></font> 
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

function appendResponse() {
  var current = document.message_form.MESSAGE.value;
  var text = document.message_form.RESPONSES.value;
  document.message_form.MESSAGE.value = current + text;
  document.message_form.MESSAGE.focus();
}

function appendCommand() {
  var current = document.message_form.MESSAGE.value;
  var text = document.message_form.COMMANDS.value;
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