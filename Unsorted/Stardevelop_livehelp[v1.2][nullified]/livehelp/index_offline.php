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

$error = '';
$invalid_email = '';
$email = '';
$name = '';
$message = '';
$status = '';

if (!isset($_POST['COMPLETE'])){ $_POST['COMPLETE'] = ""; }
if (!isset($_POST['SEND_COPY_TO_SELF'])){ $_POST['SEND_COPY_TO_SELF'] = ""; }
if($_POST['COMPLETE'] == "true") {
  if ($_POST['EMAIL'] == "" || $_POST['NAME'] == "" || $_POST['MESSAGE'] == "") {
  $error = 'true';
  }
else {

$name = stripslashes($_POST['NAME']);
$email = stripslashes($_POST['EMAIL']);
$message = stripslashes($_POST['MESSAGE']);
$send_copy_to_self = $_POST['SEND_COPY_TO_SELF'];

if (!ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'.
              '@'.
              '[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.'.
              '[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $email)) {
              $invalid_email = 'true';
}
else {

$from_name = "$name";
$from_email = "$email";
$to_email = "$offline_email";
$subject = "$livehelp_name Offline Message";
$headers = "From: ".$from_name." <".$from_email.">\n";
if ($send_copy_to_self == 'true') { $headers .= "Cc: <".$from_email.">\n"; }
$headers .= "Reply-To: ".$from_name." <".$from_email.">\n";
mail($to_email, $subject, $message, $headers);
}
}
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo($livehelp_name); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="include/styles.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="<?php echo($background_color); ?>" background="<?php echo($background_image); ?>" text="<?php echo($font_color); ?>" link="<?php echo($font_link_color); ?>" vlink="<?php echo($font_link_color); ?>" alink="<?php echo($font_link_color); ?>">
<div align="center"> 
<img src="<?php echo($_GET['SERVER'] . $livehelp_logo); ?>" alt="<?php echo($livehelp_name); ?>" border="0" /> 
    <?php
  if($_POST['COMPLETE'] == "" || $error != "" || $invalid_email != "") {
  ?>
  <p><font size="<?php echo($font_size); ?>" face="<?php echo($font_type); ?>"><?php echo($language['welcome_message']); ?><br>
    <?php echo($language['unfortunately_offline']); ?><br>
    <?php echo($language['fill_details_below']); ?>:</font> </p>
  <form action="index_offline.php" method="post" name="offline_message_form" id="offline_message_form">
    <table width="400" border="0" cellspacing="2" cellpadding="2">
      <tr> 
        <td><img src="icons/mail_send.gif" alt="<?php echo($language['leave_msg']); ?>" width="22" height="22"></td>
        <td colspan="<?php echo($font_size); ?>"><font face="<?php echo($font_type); ?>"> 
          <?php echo($language['leave_msg']); ?> :: </font></td>
      </tr>
      <?php
	  if ($error != "") {
	  ?>
      <tr> 
        <td>&nbsp;</td>
        <td colspan="2" valign="bottom"><div align="center"><strong><font size="<?php echo($font_size); ?>" face="<?php echo($font_type); ?>"><?php echo($language['complete_error']); ?></font></strong></div></td>
      </tr>
      <?php
	  }
	  elseif ($invalid_email != "") {
	  ?>
      <tr> 
        <td>&nbsp;</td>
        <td colspan="2" valign="bottom"><div align="center"><strong><font size="<?php echo($font_size); ?>" face="<?php echo($font_type); ?>"><?php echo($language['invalid_email_error']); ?></font></strong></div></td>
      </tr>
      <?php
	  }
	  ?>
      <tr> 
        <td width="22">&nbsp;</td>
        <td valign="bottom"><div align="right"><font size="<?php echo($font_size); ?>" face="<?php echo($font_type); ?>"><?php echo($language['your_email']); ?>:</font></div></td>
        <td><input name="EMAIL" type="text" id="EMAIL" value="<?php echo($email); ?>" size="40"></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td valign="bottom"><div align="right"><font size="<?php echo($font_size); ?>" face="<?php echo($font_type); ?>"><?php echo($language['your_name']); ?>:</font></div></td>
        <td> <input name="NAME" type="text" id="NAME" value="<?php echo($name); ?>" size="40"></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td valign="top"><div align="right"><font size="<?php echo($font_size); ?>" face="<?php echo($font_type); ?>"><?php echo($language['message']); ?>:</font></div></td>
        <td align="right" valign="top"><div align="left"> 
            <textarea name="MESSAGE" cols="30" rows="3" id="MESSAGE"><?php echo($message); ?></textarea>
          </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td colspan="2" align="right" valign="top"><div align="left"><font size="<?php echo($font_size); ?>" face="<?php echo($font_type); ?>"><input name="SEND_COPY_TO_SELF" type="checkbox" value="true"><?php echo($language['send_copy']); ?></font></div></td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td colspan="2" align="right" valign="top"> <div align="center"> 
            <p> 
              <input name="COMPLETE" type="hidden" id="COMPLETE" value="true">
            </p>
            <table border="0" cellpadding="2" cellspacing="2">
              <tr> 
                <td width="30"><div align="center"><input name="SUBMIT" type="image" id="SUBMIT" src="icons/queue_sm.gif" alt="<?php echo($language['send_msg']); ?>" width="22" height="22" border="0"></div></td>
                <td><font size="1" face="<?php echo($font_type); ?>"><a href="#" onClick="processForm();" class="normlink"><?php echo($language['send_msg']); ?></a></font></td>
                <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">::</font></td>
                <td width="30"><div align="center"><a href="#" onClick="parent.close();"><img src="icons/close.gif" alt="<?php echo($language['close_window']); ?>" width="22" height="22" border="0"></a></div></td>
                <td><font size="1" face="<?php echo($font_type); ?>"><a href="#" onClick="parent.close();" class="normlink"><?php echo($language['close_window']); ?></a></font></td>
              </tr>
            </table>
<font size="1" face="<?php echo($font_type); ?>"><?php echo($language['stardevelop_copyright']); ?></font> 
            </div></td>
      </tr>
    </table>
    <script language="JavaScript">
<!--
function processForm() {
	void(document.offline_message_form.submit())
}
//-->
</script>
  </form>
  <?php
  }
  else {
  ?>
  <p><font size="<?php echo($font_size); ?>" face="<?php echo($font_type); ?>"><?php echo($language['thank_you_enquiry']); ?><br>
    <?php echo($language['contacted_soon']); ?></font></p>
  <table width="400" border="0" cellspacing="2" cellpadding="2">
    <tr> 
      <td><img src="icons/mail_send.gif" alt="<?php echo($language['leave_msg']); ?>" width="22" height="22"></td>
      <td colspan="2"><font face="<?php echo($font_type); ?>"><?php echo($language['leave_msg']); ?> ::</font></td>
    </tr>
    <?php
	  if ($status != "") {
	  ?>
    <?php
	  }
	  ?>
    <tr> 
      <td width="22">&nbsp;</td>
      <td valign="bottom"><div align="right"><font size="<?php echo($font_size); ?>" face="<?php echo($font_type); ?>"><?php echo($language['your_email']); ?>:</font></div></td>
      <td width="260"><em><font size="<?php echo($font_size); ?>" face="<?php echo($font_type); ?>"><?php echo($email); ?></font></em></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td valign="bottom"><div align="right"><font size="<?php echo($font_size); ?>" face="<?php echo($font_type); ?>"><?php echo($language['your_name']); ?>:</font></div></td>
      <td><em><font size="<?php echo($font_size); ?>" face="<?php echo($font_type); ?>"><?php echo($name); ?></font></em></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td valign="top"><div align="right"><font size="<?php echo($font_size); ?>" face="<?php echo($font_type); ?>"><?php echo($language['message']); ?>:</font></div></td>
      <td align="right" valign="top"><div align="left"> </div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td colspan="2" align="right" valign="top"><div align="center"> 
          <textarea name="textarea" cols="40" rows="8" id="textarea2"><?php echo($message); ?></textarea>
        </div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td colspan="2" align="right" valign="top"> <div align="center"> 
          <p>&nbsp;</p>
          <table border="0" cellpadding="2" cellspacing="2">
            <tr> 
              <td width="30"><div align="center"><a href="index_offline.php"><img src="icons/queue_sm.gif" alt="<?php echo($language['send_another_msg']); ?>" width="22" height="22" border="0"></a> 
                </div></td>
              <td><font size="1" face="<?php echo($font_type); ?>"><a href="index_offline.php" class="normlink"><?php echo($language['send_another_msg']); ?></a></font></td>
              <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">::</font></td>
              <td width="30"><div align="center"><a href="#" onClick="parent.close();"><img src="icons/close.gif" alt="<?php echo($language['close_window']); ?>" width="22" height="22" border="0"></a></div></td>
              <td><font size="1" face="<?php echo($font_type); ?>"><a href="#" onClick="parent.close();" class="normlink"><?php echo($language['close_window']); ?></a></font></td>
            </tr>
          </table>
<font size="1" face="<?php echo($font_type); ?>"><?php echo($language['stardevelop_copyright']); ?></font> 
          </div></td>
    </tr>
  </table>
  <?php
  }
  ?>
</div>
</body>
</html>