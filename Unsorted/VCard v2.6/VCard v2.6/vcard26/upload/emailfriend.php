<?php
/***************************************************************************
 *   script               : vCard PRO
 *   copyright            : (C) 2001-2002 Belchior Foundry
 *   website              : www.belchiorfoundry.com
 *
 *   This program is commercial software; you can´t redistribute it under
 *   any circumstance without explicit authorization from Belchior Foundry.
 *   http://www.belchiorfoundry.com/
 *
 ***************************************************************************/
define('IN_VCARD', true);
$templatesused = 'emailfriend';

require('./lib.inc.php');

$htmlbody 	= html_body("","",$site_body_bgcolor,$site_body_text,$site_body_link,$site_body_vlink,$site_body_alink,$site_body_marginwidth,$site_body_marginheight);
$fieldsize 	= optionbynavigator("30","15");

function localerrorpage($expression,$message) {
	global $MsgBack,$MsgABook_tit_error;
	
	if ($expression)
	{
		dolocalhtml_textheader($MsgABook_tit_error);
		echo "<tr><td><br><p><blockquote>$message</blockquote>
		<div align=\"center\"><a href='javascript:history.go(-1)'>$MsgBack</a></div>";
		echo "</td></tr></table> ";
		exit;
	}
}

function dolocalhtml_textheader($title,$action="submit") {
	global $MsgReferFriend,$site_font_face,$htmlbody,$htmldir,$CharSet;
	
echo "<html dir=\"$htmldir\">
<html>
<head>
	<title>$MsgReferFriend</title>
	<meta http-equiv=\"CONTENT-TYPE\" content=\"text/html; charset=$CharSet\">
	<style>
	BODY		{font-family:$site_font_face; font-size: 11px; }
	TD		{font-family:$site_font_face; font-size: 11px; }
	.title	 	{font-family:$site_font_face; font-size: 18px; font-weight:bold;}
	.footernote 	{font-family:$site_font_face; font-size: 11px; }
	.headernote 	{font-family:$site_font_face; font-size: 12px; }
	.td2		{font-family:$site_font_face; font-size: 11px; font-weight:bold; }
	</style>
</head>
$htmlbody
<form method=\"post\" action=\"emailfriend.php\" name=\"form\">
<input type=\"hidden\" name=\"action\" value=\"$action\">
<table width=\"90%\" border=\"0\" align=\"center\">
<tr>
	<td align=\"center\" colspan=\"2\" valign=\"top\"><span class=\"title\">$title</span> </td>
</tr>";
}

function dolocalhtml_textfooter($submitname="Submit",$resetname="Reset") {

	echo "</table>\n";
	echo "<p><div align=\"center\"><center>\n";
	echo "<table border=\"0\">\n";
	echo "<tr><td><p><p align=\"center\"><input type=\"submit\" name=\"submit\" value=\"   $submitname   \" width=\"200\"></p></p></td>\n";
	echo "<td><p><p align=\"center\"><input type=\"reset\" name=\"reset\" value=\"   $resetname   \"></p></p></td>\n";
	echo "</tr>";
	echo "</table>\n</center></div>\n";
	echo "</form>\n";
	exit;
}

if (!empty($HTTP_COOKIE_VARS['vCard_sendername']) && !empty($HTTP_COOKIE_VARS['vCard_senderemail']))
{
	$cookie_name = stripslashes($HTTP_COOKIE_VARS['vCard_sendername']);
	$cookie_email= stripslashes($HTTP_COOKIE_VARS['vCard_senderemail']);
}
else
{
	$cookie_name = '';
	$cookie_email= '';
}

// ################################ default ###########################
if ($HTTP_POST_VARS['action'] == 'submit')
{
	localerrorpage(checkempty($HTTP_POST_VARS['recipient_name']),$MsgReferFriend_error);
	localerrorpage(checkempty($HTTP_POST_VARS['recipient_email']),$MsgReferFriend_error);
	localerrorpage(checkempty($HTTP_POST_VARS['sender_name']),$MsgReferFriend_error);
	localerrorpage(checkempty($HTTP_POST_VARS['sender_email']),$MsgReferFriend_error);
	localerrorpage(mailval($HTTP_POST_VARS['sender_email'],2),$MsgReferFriend_error_emailformate);
	localerrorpage(mailval($HTTP_POST_VARS['recipient_email'],2),$MsgReferFriend_error_emailformate);
	
	send_emailfriend($HTTP_POST_VARS['sender_email'],$HTTP_POST_VARS['sender_name'],$HTTP_POST_VARS['recipient_name'],$HTTP_POST_VARS['recipient_email'],$HTTP_POST_VARS['message']);
	dolocalhtml_textheader($MsgReferFriend);
	echo "<tr><td>";
	echo "<p><br></p>$MsgReferFriend_thanks $HTTP_POST_VARS[sender_name],<p> $MsgReferFriend_end : ".$HTTP_POST_VARS['recipient_name'] ." (". $HTTP_POST_VARS['recipient_email'] .")";
	echo "<p align=\"center\"><a href=\"javascript:window.close()\"><img src=\"img/icon_close.gif\" border=\"0\"  align=\"ABSMIDDLE\" alt=\"$MsgCloseWindow\"></a></p><br>";
	echo "</td></tr>";
	echo "<tr><td></td></tr>";
	echo "</table>";
	
	exit;
}

if (empty($HTTP_GET_VARS['action']))
{
	dolocalhtml_textheader($MsgReferFriend,"submit");
?>
	<tr>
		<td><b><?php echo "$MsgReferFriend_friendname"; ?> : </b></td>
		<td><input type="text" name="recipient_name" value="" size="<?php echo "$fieldsize"; ?>"></td>
	</tr>
	<tr>
		<td><b><?php echo "$MsgReferFriend_friendemail"; ?> : </b></td>
		<td><input type="text" name="recipient_email" value="" size="<?php echo "$fieldsize"; ?>"></td>
	</tr>
	<tr>
		<td><b><?php echo "$MsgYourName"; ?> : </b></td>
		<td><input type="text" name="sender_name" value="<?php echo "$cookie_name"; ?>" size="<?php echo "$fieldsize"; ?>"></td>
	</tr>
	<tr>
		<td><b><?php echo "$MsgYourEmail"; ?> : </b></td>
		<td><input type="text" name="sender_email" value="<?php echo "$cookie_email"; ?>" size="<?php echo "$fieldsize"; ?>"></td>
	</tr>
	<tr>
		<td colspan="2"><b><?php echo "$MsgReferFriend_custommessage"; ?></b> :<br>
		<textarea cols="<?php echo $fieldsize + 5; ?>" rows="5" name="message"></textarea></td>
	</tr>
<?php
	dolocalhtml_textfooter();
}
$DB_site->close();
if($use_gzip == 1) {ob_end_flush(); }
?>