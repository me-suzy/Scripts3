<?php
//***************************************************************************//
//                                                                           //
//  Program Name    	: vCard PRO                                          //
//  Program Version     : 2.6                                                //
//  Program Author      : Joao Kikuchi,  Belchior Foundry                    //
//  Home Page           : http://www.belchiorfoundry.com                     //
//  Retail Price        : $80.00 United States Dollars                       //
//  WebForum Price      : $00.00 Always 100% Free                            //
//  Supplied by         : South [WTN]                                        //
//  Nullified By        : CyKuH [WTN]                                        //
//  Distribution        : via WebForum, ForumRU and associated file dumps    //
//                                                                           //
//                (C) Copyright 2001-2002 Belchior Foundry                   //
//***************************************************************************//
define('IN_VCARD', true);
$templatesused = 'addrbook';
$ab_id = '';
require('./lib.inc.php');

$htmlbody = html_body("img/abook","ab_bg.gif",$site_body_bgcolor,$site_body_text,$site_body_link,$site_body_vlink,$site_body_alink,$site_body_marginwidth,$site_body_marginheight);

unset($can_access);
// ############################## Local FUnctions ############################### //
function localerrorpage($expression,$message) {

	if($expression)
	{
		echo
		dolocalhtml_textheader($MsgABook_tit_error);
		//eval("make_output(\"".get_template("errorpage")."\");");
		echo "<br><p><blockquote><b>$message</b></blockquote>
		<div align=\"center\"><a href='javascript:history.go(-1)'><img border=0 src=\"img/abook/abback.gif\"  alt='back'></a></div>";
		dolocalhtml_textfooter();
		exit;
	}
}

function dolocalhtml_textheader($title) {

	global $MsgABook_tit_generaltitle,$site_font_face,$htmlbody,$htmldir,$CharSet;
echo "<html dir=\"$htmldir\">
<head>
	<meta http-equiv=\"CONTENT-TYPE\" content=\"text/html; charset=$CharSet\">
	<title>$MsgABook_tit_generaltitle</title>
	<style>
	.title	 	{font-family:$site_font_face; font-size: 18px; font-weight:bold;}
	.footernote 	{font-family:$site_font_face; font-size: 11px; }
	.headernote 	{font-family:$site_font_face; font-size: 12px; }
	.td2		{font-family:$site_font_face; font-size: 11px; font-weight:bold; }
	</style>
</head>
$htmlbody
<form method=\"post\" action=\"addrbook.php\" name=\"abookform\">
<table width=\"100%\" height=\"100%\">
<tr>
	<td width=\"30\" height=\"100%\"><img src=\"img/null.gif\" width=\"30\" height=\"1\" border=\"0\" alt=\"\"></td>
	<td align=\"center\" valign=\"top\"><span class=\"title\">$title</span> <br><p>";
}

function dolocalhtml_textfooter() {
	echo "
	<br clear=\"all\">
	<br>
	</td>
</tr>
</table>
<input type=\"hidden\" name=\"action\" value=\"\">
</form>
</body>
</html>";
exit;
}

// ################################# check fields ##################################### //
if ( !empty($HTTP_COOKIE_VARS['vc_ab_autolog']) && !empty($HTTP_COOKIE_VARS['vc_ab_n']) && !empty($HTTP_COOKIE_VARS['vc_ab_p']) && empty($HTTP_POST_VARS['action']) )
{
	$HTTP_POST_VARS['ab_password'] = $HTTP_COOKIE_VARS['vc_ab_p'];
	$HTTP_POST_VARS['ab_username'] = $HTTP_COOKIE_VARS['vc_ab_n'];
	$HTTP_POST_VARS['action'] = 'show';
}

if ( ($HTTP_POST_VARS['action'] =='edit_addr' || $HTTP_POST_VARS['action'] =='delete_addr') && empty($HTTP_POST_VARS['addrlistitem']) )
{
	$HTTP_POST_VARS['action'] = 'show';
}

// ################################# NO REQUIRE PASSWORD ##################################### //
if ($HTTP_POST_VARS['action']=='logout')
{
	unset($ab_id);
	unset($can_access);
}

if ($HTTP_POST_VARS['action']=='dogetpassword')
{
	abook_send_password($HTTP_POST_VARS['ab_email']);
	$HTTP_POST_VARS['action'] = '';
}

if ($HTTP_POST_VARS['action']=='create_abook')
{
	
	localerrorpage(checkempty($HTTP_POST_VARS['ab_username']),$MsgABook_error);
	localerrorpage(checkempty($HTTP_POST_VARS['ab_password']),$MsgABook_error);
	localerrorpage(checkempty($HTTP_POST_VARS['ab_password2']),$MsgABook_error);
	if ($HTTP_POST_VARS['ab_password'] != $HTTP_POST_VARS['ab_password2'] )
	{
		localerrorpage(1,$MsgABook_error2);
	}
	localerrorpage(checkempty($HTTP_POST_VARS['ab_realname']),$MsgABook_error);
	localerrorpage(checkempty($HTTP_POST_VARS['ab_email']),$MsgABook_error);

	$checkifexiste = $DB_site->query_first("SELECT ab_username FROM vcard_abook WHERE ab_username='".addslashes($HTTP_POST_VARS['ab_username'])."'");
	if(!empty($checkifexiste))
	{
		localerrorpage(1,$MsgABook_error_username);
	}else{
		$result = $DB_site->query(" INSERT INTO vcard_abook (ab_id, ab_username, ab_password, ab_realname, ab_email,ab_date,ab_md5password) VALUES (NULL,'".addslashes($HTTP_POST_VARS['ab_username'])."','".addslashes($HTTP_POST_VARS['ab_password'])."','".addslashes($HTTP_POST_VARS['ab_realname'])."','".addslashes($HTTP_POST_VARS['ab_email'])."','". date("Y-m-d")."','".md5(addslashes($HTTP_POST_VARS['ab_password']))."') ");
		//$ab_id 	= $DB_site->insert_id();
		$HTTP_COOKIE_VARS['vc_ab_n']	= $ab_username;
		$HTTP_COOKIE_VARS['vc_ab_p']	= md5($ab_password);
	}
	$HTTP_POST_VARS['action'] = '';
}

// ################################### Require Access Code ############################## //
if ($HTTP_POST_VARS['action'] =='login')
{
	$ab_password = trim(htmlspecialchars(addslashes($HTTP_POST_VARS['ab_password'])));
	$ab_username = trim(htmlspecialchars(addslashes($HTTP_POST_VARS['ab_username'])));
	$checkpwd = $DB_site->query_first("SELECT * FROM vcard_abook WHERE (ab_username='$ab_username') and (ab_md5password='".md5($ab_password)."')");
	if(empty($checkpwd))
	{
		//localerrorpage(1,$MsgABook_error_invalidloginnote);
		unset($ab_password);
		unset($ab_username);
		unset($can_access);
		unset($ab_id);
		$HTTP_POST_VARS['action'] = '';
		//echo 'ACESSO NEGADO';
	}else{
		//echo 'ACESSO OK';
		$ab_id		= $checkpwd['ab_id'];
		$ab_realname 	= stripslashes(htmlspecialchars($checkpwd['ab_realname']));
		$ab_username    = stripslashes(htmlspecialchars($checkpwd['ab_username']));
		$ab_email 	= stripslashes(htmlspecialchars($checkpwd['ab_email']));
		$ab_password 	= md5(stripslashes(htmlspecialchars($checkpwd['ab_password'])));
		//setcookie ("vCard_abookvisible", "1", time()+36000, "/");
		if ($HTTP_POST_VARS['ab_remember']==1)
		{
			setcookie ("vc_ab_n", $ab_username  , time()+ 31536000, "/");
			setcookie ("vc_ab_p", $ab_password , time()+ 31536000, "/");
			setcookie ("vc_ab_autolog",'1', time()+ 31536000, "/");
		}else{
			setcookie ("vc_ab_n", '', time() - 3600, "/");
			setcookie ("vc_ab_p", '', time() - 3600, "/");
			setcookie ("vc_ab_autolog",'0', time()+ 31536000, "/");
	
		}
		$HTTP_POST_VARS['action'] = 'show';
		$can_access = 1;
	}
}

// Verification login/password
if(empty($can_access))
{
	$ab_password = trim(htmlspecialchars(addslashes($HTTP_POST_VARS['ab_password']))); 
	$ab_username = trim(htmlspecialchars(addslashes($HTTP_POST_VARS['ab_username']))); 
	//echo "$ab_username / $ab_password";
	$verifiydb = $DB_site->query_first("SELECT * FROM vcard_abook WHERE (ab_username='$ab_username') and (ab_md5password='$ab_password')");
	if(empty($verifiydb))
	{ // dont match
		$abvisible 	= 0;
		unset($ab_password);
		unset($ab_username);
		unset($ab_id);
		setcookie ("vc_ab_n", '', time() - 3600, "/");
		setcookie ("vc_ab_p", '', time() - 3600, "/");
		setcookie ("vc_ab_autolog",0, time() - 3600, "/");
		$can_access = 0;
		//$HTTP_POST_VARS['action'] ='';
	// match
	}else{
		$ab_id		= $verifiydb['ab_id'];
		$ab_realname 	= stripslashes(htmlspecialchars($verifiydb['ab_realname']));
		$ab_email 	= stripslashes(htmlspecialchars($verifiydb['ab_email']));
		$ab_password 	= md5(stripslashes(htmlspecialchars($verifiydb['ab_password'])));
		$can_access = 1;
	}
}

// ################################### AB database actions ############################## //

if($HTTP_POST_VARS['action'] == 'doadd_addr' && !empty($can_access) )
{
	localerrorpage(checkempty($HTTP_POST_VARS['addr_name']),$Msg_error_emptyfield);
	localerrorpage(checkempty($HTTP_POST_VARS['addr_email']),$Msg_error_emptyfield);
	localerrorpage(mailval($HTTP_POST_VARS['addr_email'],2),$MsgABook_error_emailformate);
	$insert = $DB_site->query(" INSERT INTO vcard_address (addr_id, ab_id, addr_name,addr_email) VALUES (NULL,'$ab_id','".addslashes($HTTP_POST_VARS['addr_name'])."','".addslashes($HTTP_POST_VARS['addr_email'])."') ");
	$HTTP_POST_VARS['action'] = 'show';
}

if($HTTP_POST_VARS['action']=='dodelete_addr' && !empty($can_access))
{
	$deleted_attach = $DB_site->query("DELETE FROM vcard_address WHERE (addr_id='".addslashes($HTTP_POST_VARS[addr_id])."' AND ab_id='$ab_id' )");
	$HTTP_POST_VARS['action'] = 'show';
}

if($HTTP_POST_VARS['action']=='update_addr' && !empty($can_access))
{
	$result = $DB_site->query("UPDATE vcard_address SET addr_name='".addslashes($HTTP_POST_VARS['addr_name'])."', addr_email='".addslashes($HTTP_POST_VARS['addr_email'])."' WHERE (addr_id='".addslashes($HTTP_POST_VARS['addr_id'])."' AND ab_id='$ab_id' ) ");
	$HTTP_POST_VARS['action'] = 'show';
}

if($HTTP_POST_VARS['action']=='update_profile' && !empty($can_access))
{
	localerrorpage(checkempty($HTTP_POST_VARS['new_ab_realname']),$MsgABook_error);
	localerrorpage(checkempty($HTTP_POST_VARS['new_ab_email']),$MsgABook_error);
	if($HTTP_POST_VARS['new_ab_password'] != $HTTP_POST_VARS['new_ab_password2'])
	{
		localerrorpage(1,$MsgABook_error2);
	}
	
	$result = $DB_site->query("UPDATE vcard_abook SET ab_password='".addslashes($HTTP_POST_VARS['new_ab_password'])."', ab_realname='".addslashes($HTTP_POST_VARS['new_ab_realname'])."', ab_email='".addslashes($HTTP_POST_VARS['new_ab_email'])."', ab_md5password='".md5(addslashes($HTTP_POST_VARS['new_ab_password']))."' WHERE ab_id='$ab_id' ");
	$HTTP_POST_VARS['action'] = '';
	$can_access = 0;
}


// ################################ show address book ###########################
if( ($HTTP_POST_VARS['action']=='insert_postcard' || $HTTP_POST_VARS['action']=='show')  && !empty($can_access))
{
	dolocalhtml_textheader("$MsgABook_tit_generaltitle <br> $ab_realname ");
	if($HTTP_POST_VARS['action']=='insert_postcard' && !empty($HTTP_POST_VARS['addrlistitem']))
	{
		if (count($HTTP_POST_VARS['addrlistitem']))
		{
			echo "<script language=\"javascript\">
			if(window.opener.closed){ alert(\"$MsgABook_note1\"); window.close();
			} else {  ";
			$i= '';
			while( list($key,$val) = each($HTTP_POST_VARS['addrlistitem']) )
			{
					$selected_addrinfo = $DB_site->query_first("SELECT * FROM vcard_address WHERE addr_id='".addslashes($val)."' ");
					echo "
					if(window.opener.document.vCardform.recip_name$i)
					  {window.opener.document.vCardform.recip_name$i.value=\"$selected_addrinfo[addr_name]\"};
					if(window.opener.document.vCardform.recip_email$i)
					  {window.opener.document.vCardform.recip_email$i.value=\"$selected_addrinfo[addr_email]\"};
					";
				$i++;
			}
			echo"
				}
				   window.opener.document.vCardform.sender_name.value=\"$ab_realname\";
				   window.opener.document.vCardform.sender_email.value=\"$ab_email\";
				   window.opener.focus();
			</script>";
		}
		//echo $content;
		//exit;
	}
	//$DB_site->free_result($selected_addrinfo);
?>

<table border="0" cellspacing="0" cellpadding="4" align="center">
<tr><td valign=top>
<?php
	$getuserabookaddr = $DB_site->query("SELECT addr_id,addr_name,addr_email FROM vcard_address WHERE ab_id='$ab_id' ORDER BY addr_name ");
	$i = 0;
	echo "<select name=\"addrlistitem[]\" size=\"10\" multiple>\n";
	while($entries = $DB_site->fetch_array($getuserabookaddr))
	{
		$addr_name = stripslashes(htmlspecialchars($entries['addr_name']));
		$addr_email = stripslashes(htmlspecialchars($entries['addr_email']));
		//echo "<input type=\"checkbox\" name=\"addrlistitem[$i]\"  value=\"$entries[addr_id]\">$addr_name ($addr_email)<br>\n";
		echo "<option value=\"$entries[addr_id]\">$addr_name - ($addr_email)</option>\n";
		$i++;
	}
	$DB_site->free_result($getuserabookaddr);
	if($i == 0)
	{
		echo "<option value=''>Include Some Entry</option>";
	}
	echo "</select>";
?>
</td>
<td valign="top">
	<a href="javascript:document.abookform.submit();" onClick="document.abookform.action.value='add_addr';"><img border=0 src="img/abook/abadd.gif" vspace=1 alt="Add"></a><br>
	<a href="javascript:document.abookform.submit();" onClick="document.abookform.action.value='edit_addr';"><img border=0 src="img/abook/abedit.gif" vspace=1 alt="Edit"></a><br>
	<a href="javascript:document.abookform.submit();" onClick="document.abookform.action.value='delete_addr';"><img border=0 src="img/abook/abdelete.gif" vspace=1 alt="Delete"></a><br>
	<!-- <a href="javascript:document.abookform.submit();" onClick="document.abookform.action.value='reminder_scr';"><img border=0 src="img/abook/abreminder.gif" vspace=1 alt='reminder'></a><br> -->
	<a href="javascript:document.abookform.submit();" onClick="document.abookform.action.value='help';"><img border=0 src="img/abook/abhelp.gif" vspace=1 alt="Help"></a><br>
	<a href="javascript:window.close()"><img border=0 src="img/abook/abclose.gif" vspace=1 alt='close'></a><br>
	<a href="javascript:document.abookform.submit();" onClick="document.abookform.action.value='logout';"><img border=0 src="img/abook/ablogout.gif" vspace=1 alt="Log Out"></a><br>
</td>
</table>
<center>
<a href="javascript:document.abookform.submit();" onClick="abookform.action.value='insert_postcard';"><img border=0 src="img/abook/abinsertinfo.gif" alt="Insert Email Adresses"></a>
<a href="javascript:document.abookform.submit();" onClick="abookform.action.value='edit_profile'"><img border=0 src="img/abook/abeditinfo.gif" alt="Edit Profile"></a>
<p align="left" class="footernote"><?php echo "$MsgABook_bottonnote2 <br> $MsgABook_bottonnote"; ?></p>
<?php
make_html_hiddenfield("ab_username",$ab_username);
make_html_hiddenfield("ab_password",$ab_password);

dolocalhtml_textfooter();
}


// ################################ add entry to address book ###########################
if ($HTTP_POST_VARS['action']=='add_addr'  && !empty($can_access))
{
	dolocalhtml_textheader($MsgABook_tit_addrecord);
?>
<table align=center border=0>
<tr><td align=right class="td2"><?php echo "$MsgABook_name";?>:</td>
<td align=left><input type="text" name="addr_name" size="20" maxlength="50"></td></tr>
<tr><td align=right class="td2"><?php echo "$MsgABook_email";?>:</td>
<td align=left><input type="text" name="addr_email" size="20" maxlength="50"></td></tr>
</table><br>
<center>
<a href="javascript:document.abookform.submit();" onClick="abookform.action.value='doadd_addr';"><img border=0 src="img/abook/abaddrecord.gif" alt='add record'></a>&nbsp;
<a href="javascript:document.abookform.submit();" onclick="abookform.reset();return false"><img border=0 src="img/abook/abreset.gif" alt='reset'></a>&nbsp;
<a href="javascript:document.abookform.submit();" onClick="abookform.action.value='show';"><img border=0 src="img/abook/abcancel.gif" alt='cancel'></a>
<?php

make_html_hiddenfield("ab_username",$ab_username);
make_html_hiddenfield("ab_password",$ab_password);

dolocalhtml_textfooter();
}

// ################################ edit address ###########################
if ($HTTP_POST_VARS['action']=='edit_addr' && !empty($can_access))
{
	while( list($key,$val) = each($HTTP_POST_VARS['addrlistitem']) )
	{
		$selected_addrinfo = $DB_site->query_first("SELECT * FROM vcard_address WHERE addr_id='".addslashes($val)."' ");
		$addr_id	= $selected_addrinfo['addr_id'];
		$addr_name 	= stripslashes(htmlspecialchars($selected_addrinfo['addr_name']));
		$addr_email 	= stripslashes(htmlspecialchars($selected_addrinfo['addr_email']));
		break;
	}
	dolocalhtml_textheader("$MsgABook_tit_updaterecord");
?>
<table align=center border=0>
<tr><td align=right class="td2"><?php echo "$MsgABook_name";?>:</td>
<td align=left><input type="text" name="addr_name" value="<?php echo "$addr_name";?>" size="20" maxlength="50"></td></tr>
<tr><td align=right class="td2"><?php echo "$MsgABook_email";?>:</td>
<td align=left><input type="text" name="addr_email" value="<?php echo "$addr_email";?>" size="20" maxlength="50"></td></tr>
</table><br>


<?php
make_html_hiddenfield("addr_id",$addr_id); // address id
make_html_hiddenfield("ab_username",$ab_username);
make_html_hiddenfield("ab_password",$ab_password);
?>
<center>
<a href="javascript:document.abookform.submit();" onClick="abookform.action.value='update_addr';"><img border=0 src="img/abook/absend.gif" alt='update record'></a>&nbsp;
<a href="javascript:document.abookform.submit();" onclick="abookform.reset();return false"><img border=0 src="img/abook/abreset.gif" alt='reset'></a>&nbsp;
<a href="javascript:document.abookform.submit();" onClick="abookform.action.value='show';"><img border=0 src="img/abook/abcancel.gif" alt='cancel'></a>
<?php
dolocalhtml_textfooter();
}

// ################################ edit user profile ###########################
if($HTTP_POST_VARS['action'] =='edit_profile' && !empty($can_access))
{
	dolocalhtml_textheader("$MsgABook_tit_editprofile <br> $MsgABook_username: $ab_username");
?>
<span class="headernote"<?php echo $MsgABook_profile_note; ?></span><BR>
<table align=center>
<tr><td align=right class="td2"><?php echo "$MsgABook_realname";?>:</td>
<td align=left><input type="text" name="new_ab_realname" value="<?php echo "$ab_realname";?>" maxlenght="30" length="20"></td></tr>
<tr><td align=right class="td2"><?php echo "$MsgABook_email";?>:</td>
<td align=left><input type="text" name="new_ab_email" value="<?php echo "$ab_email";?>" maxlenght="30" length="20"></td></tr>
<tr><td align=right class="td2"> &nbsp;&nbsp; </td>
<td align=left>&nbsp;&nbsp;  </td></tr>
<tr><td align=right class="td2"><?php echo "$MsgABook_password";?>:</td>
<td align=left><input type="password" name="new_ab_password" value="<?php echo "$verifiydb[ab_password]";?>" maxlenght="30" length="20"></td></tr>
<tr><td align=right class="td2"><?php echo "$MsgABook_password2";?>:</td>
<td align=left><input type="password" name="new_ab_password2" value="<?php echo "$verifiydb[ab_password]";?>" maxlenght="30" length="20"></td></tr>
</table><BR>

<center>
<a href="javascript:document.abookform.submit();" onClick="abookform.action.value='update_profile';"><img border=0 SRC="img/abook/absave.gif" alt='Save'></a>&nbsp;
<a href="javascript:document.abookform.submit();" onCLick="abookform.reset(); return false"><img border=0 SRC="img/abook/abreset.gif" alt='Reset'></a>&nbsp;
<a href="javascript:document.abookform.submit();" onClick="abookform.action.value='show';"><img border=0 SRC="img/abook/abcancel.gif" alt='Cancel'></a>
</center>
<?php
make_html_hiddenfield("ab_username",$ab_username);
make_html_hiddenfield("ab_password",$ab_password);

dolocalhtml_textfooter();
}

// ################################ edit address ###########################
if($HTTP_POST_VARS['action']=='delete_addr' && !empty($can_access))
{
	while( list($key,$val) = each($HTTP_POST_VARS['addrlistitem']) )
	{
		$selected_addrinfo = $DB_site->query_first("SELECT * FROM vcard_address WHERE addr_id='".addslashes($val)."' ");
		$addr_id	= $selected_addrinfo['addr_id'];
		$addr_name 	= stripslashes(htmlspecialchars($selected_addrinfo['addr_name']));
		$addr_email 	= stripslashes(htmlspecialchars($selected_addrinfo['addr_email']));
		break;
	}
	dolocalhtml_textheader("$MsgABook_tit_deleterecord");
?>
<table align=center border=0>
<tr><td>
Are you sure?<p>
<br><b><?php echo "$addr_name ( $addr_email )";?></b>
</td></tr>
</table><br>

<?php
make_html_hiddenfield("addr_id",$addr_id);
make_html_hiddenfield("ab_username",$ab_username);
make_html_hiddenfield("ab_password",$ab_password);
?>
<center>
<a href="javascript:document.abookform.submit();" onClick="abookform.action.value='dodelete_addr';"><img border=0 src="img/abook/absend.gif" alt='update record'></a>&nbsp;
<a href="javascript:document.abookform.submit();" onClick="abookform.action.value='show';"><img border=0 src="img/abook/abcancel.gif" alt='cancel'></a>
</center>
<?php
	dolocalhtml_textfooter();
}
// ##################################################################################### //
// ################################### PUBLIC ACCESS ################################### //
// ##################################################################################### //
// ################################ Clean Cookie ###########################
if($HTTP_POST_VARS['action']=='clean_cookie')
{
	setcookie ("vc_ab_n", "", time(), "/");
	setcookie ("vc_ab_p", "", time(), "/");
	setcookie ("vc_ab_autolog",0, time(), "/");
	
	dolocalhtml_textheader($MsgABook_tit_cleancookie);
	echo "$MsgABook_cleancookie_note";
?>

<center>
<a href="javascript:document.abookform.submit();" onClick="abookform.action.value='';"><img border=0 src="img/abook/ablogin.gif" alt='login'></a>&nbsp;
<?php
	dolocalhtml_textfooter();
}

// ################################ sign up ###########################
if($HTTP_POST_VARS['action']=='newuser')
{
	dolocalhtml_textheader("$MsgABook_tit_createabook");
?>
<span class="headernote"><?php echo "$MsgABook_create_note";?></span><br>

<table align=center>
<tr><td align=right class="td2"><?php echo "$MsgABook_username";?>:</td>
<td align=left><input type="text" name="ab_username" maxlength="30" length="20"></td></tr>
<tr><td align=right class="td2"><?php echo "$MsgABook_password";?>:</td>
<td align=left><input type="password" name="ab_password" maxlenght="30" length="20"></td></tr>
<tr><td align=right class="td2"><?php echo "$MsgABook_password2";?>:</td>
<td align=left><input type="password" name="ab_password2" maxlenght="30" length="20"></td></tr>
<tr><td align=right class="td2"><?php echo "$MsgABook_realname";?>:</td>
<td align=left><input type="text" name="ab_realname" maxlenght="30" length="20"></td></tr>
<tr><td align=right class="td2"><?php echo "$MsgABook_email";?>:</td>
<td align=left><input type="text" name="ab_email" maxlenght="30" length="20"></td></tr>
</table><br>

<center>
<a href="javascript:document.abookform.submit();" onClick="abookform.action.value='create_abook';"><img border=0 src="img/abook/abcreate.gif" alt='create'></a>&nbsp;
<a href="javascript:document.abookform.submit();" onclick="abookform.reset(); return false;"><img border=0 src="img/abook/abreset.gif" alt='reset'></a>&nbsp;
<a href="javascript:document.abookform.submit();" onClick="abookform.action.value='';"><img border=0 src="img/abook/abcancel.gif" alt='cancel'></a></center>
<?php

dolocalhtml_textfooter();
}


// ################################ edit user profile ###########################
if($HTTP_POST_VARS['action']=='getpassword')
{
	dolocalhtml_textheader("$MsgABook_tit_login");
?>
<span class="headernote"<?php echo $MsgABook_forgotpword_note; ?></span><br><br>
<table align=center>
<tr>
	<td align=left class="td2"><?php echo $MsgABook_email; ?></td>
</tr>
<tr>
	<td align=left class="td2"><input type="text" name="ab_email" maxlength="30" length="20"></td>
</tr>
</table>
<br>
<center>
<a href="javascript:document.abookform.submit();" onClick="abookform.action.value='dogetpassword';"><img border=0 src="img/abook/absend.gif" alt='send'></a>&nbsp;
<a href="javascript:document.abookform.submit();" onClick="abookform.action.value='newuser';"><img border=0 src="img/abook/abnewuser.gif" alt='new user'></a>&nbsp;
<a href="javascript:window.close()"><img border=0 src="img/abook/abclose.gif" vspace=1 alt='close'></a></center><br>
<?php
	dolocalhtml_textfooter();
}

// ################################ help ###########################
if($HTTP_POST_VARS['action']=='help')
{
dolocalhtml_textheader($MsgABook_tit_help);
?>
<table width="100%" border="0" cellspacing="5" cellpadding="1">
<tr>
	<td>
	<img src="img/abook/abadd.gif" border=0 alt="Add"><br>
	<?php echo $MsgABook_help_add; ?>
	<p><p>
	<img src="img/abook/abedit.gif" border="0" alt="Edit"><br>
	<?php echo $MsgABook_help_edit; ?>
	<p><p>
	<img src="img/abook/abdelete.gif" border=0 alt="Delete"><br>
	<?php echo $MsgABook_help_delete; ?>
	<p><p>
	<img src="img/abook/abhelp.gif" border=0 alt="Help"><br>
	<?php echo $MsgABook_help_help; ?>
	<p><p>
	<img src="img/abook/ablogout.gif" border=0 alt="Log Out"><br>
	<?php echo $MsgABook_help_logout; ?>
	<p><p>
	<img src="img/abook/abclose.gif" border=0 alt="Close Window"><br>
	<?php echo $MsgABook_help_close; ?>
	<p><p>
	<img src="img/abook/abinsertinfo.gif" border=0 alt="Insert Email Adresses"><br>
	<?php echo $MsgABook_help_insert; ?>
	<p><p>
	<img src="img/abook/abeditinfo.gif" border=0 alt="Edit Profile"><br>
	<?php echo $MsgABook_help_profile; ?>
	</td>
</tr>
</table>
<br>
<br>
<center>
<a href="javascript:document.abookform.submit();" onClick="abookform.action.value='show';"><img src="img/abook/abback.gif" border=0 alt='back'></a>
</center>
</font>
<?php
make_html_hiddenfield("ab_username",$ab_username);
make_html_hiddenfield("ab_password",$ab_password);

dolocalhtml_textfooter();
}

// ################################ default ###########################
if ( empty($HTTP_POST_VARS['action']) || $HTTP_POST_VARS['action']=='logout' )
{
	if($HTTP_POST_VARS['action']=='logout' && !empty($HTTP_COOKIE_VARS['vc_ab_autolog']) )
	{
		$verifiydb = $DB_site->query_first("SELECT * FROM vcard_abook WHERE (ab_username='$ab_username') and (ab_md5password='$ab_password')");
		$vc_ab_p = $verifiydb['ab_password'];
		$vc_ab_n = $verifiydb['ab_username'];
	}
	dolocalhtml_textheader($MsgABook_tit_login);
?>
<span class="headernote"><?php echo $MsgABook_forgotpword_note2; ?></span><br><br>

<table align=center>
<tr><td align="right" class="td2"><b><?php echo "$MsgABook_username"; ?>:</td>
<td align=left><input type="text" name="ab_username" value="<?php echo "$vc_ab_n"; ?>" maxlength="30" length="20"></td></tr>
<tr><td align=right class="td2"><?php echo "$MsgABook_password"; ?>:</td>
<td align=left><input type="password" name="ab_password" value="<?php echo "$vc_ab_p"; ?>" maxlenght="30" length="20">
</td></table>
<center>
<input type="checkbox" name="ab_remember"  value="1" <?php echo ($HTTP_COOKIE_VARS['vc_ab_autologin']==1)?'checked':''; ?>>
<span class="footernote"><b><?php echo $MsgABook_pwdremeber; ?></b></span>
</center><br>
<input type="hidden" name="loging" value="1">
<center>
<a href="javascript:document.abookform.submit();" onClick="abookform.action.value='login';"><img border=0 src="img/abook/ablogin.gif" alt='login'></a>&nbsp;
<a href="javascript:document.abookform.submit();" onClick="abookform.action.value='newuser';abookform.ab_username.value='';abookform.ab_password.value='';"><img border=0 src="img/abook/abnewuser.gif" alt='new user'></a>&nbsp;
<a href="javascript:window.close()"><img border=0 src="img/abook/abclose.gif" vspace=1 alt='close'></a>
</center><br>

<center>
<a href="javascript:document.abookform.submit();" onClick="abookform.action.value='getpassword';"><span class="footernote"><?php echo $MsgABook_helppassword; ?></span></a><p>
<a href="javascript:document.abookform.submit();" onClick="abookform.action.value='clean_cookie';"><span class="footernote"><?php echo $MsgABook_cleancookie; ?></span></a>
</center>
<?php
	dolocalhtml_textfooter();
}


if($debug==1){ $timer->end_time(); $timer->elapsed_time();}
$DB_site->close();
if($use_gzip == 1) {ob_end_flush(); }
?>