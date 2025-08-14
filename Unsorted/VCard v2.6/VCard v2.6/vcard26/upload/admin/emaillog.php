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
require('./lib.inc.php');

check_lvl_access($canviewemailog);

// ############################# DB ACTION #############################
if ($action == 'clean_db')
{
	$result = $DB_site->query("DELETE FROM vcard_emaillog ");
	dohtml_result(1,"$msg_admin_emaillog_dbempty");
	$action = "";
}


// ############################# ACTION SCREENS #############################
// SCREEN = DEFAULT
if ($action == 'view_log')
{
	dothml_pageheader();
	$loglist = $DB_site->query("SELECT * FROM vcard_emaillog ");
	dohtml_table_header("summary","$msg_admin_emaillog",2);
	dohtml_table_footer();
	
	echo "<br><pre>\n\n\n$msg_admin_name$separator$msg_admin_email\n";
	while ($log = $DB_site->fetch_array($loglist))
	{
		if ($HTTP_POST_VARS['item_name'] == 'name')
		{
			echo stripslashes(htmlspecialchars($log['name']));
		}
		echo $HTTP_POST_VARS['separator'];
		if ($HTTP_POST_VARS['item_email'] == 'email')
		{
			echo stripslashes(htmlspecialchars($log['email']));
		}
		echo "\n";
	}
	$DB_site->free_result($loglist);
	exit;
}

if ($action == 'download_log')
{
	$filename = "emails_".date("Y-m-d").".text";
	header("Content-type: text/plain");
	header("Content-Disposition: attachment; filename=$filename");
	$loglist = $DB_site->query("SELECT * FROM vcard_emaillog ");
	echo "$msg_admin_name$separator$msg_admin_email\n";
	while ($log = $DB_site->fetch_array($loglist))
	{
		if ($HTTP_POST_VARS['item_name'] == 'name')
		{
			echo stripslashes(htmlspecialchars($log['name']));
		}
		echo $HTTP_POST_VARS['separator'];
		if ($HTTP_POST_VARS['item_email'] == 'email')
		{
			echo stripslashes(htmlspecialchars($log['email']));
		}
		echo "\n";
	}
	$DB_site->free_result($loglist);
	exit;
}
// ############################# ACTION SCREENS #############################
// SCREEN = DEFAULT

if (empty($action))
{
	dothml_pageheader();
	dohtml_form_header("emaillog","view_log",0,1);
	dohtml_table_header("main","$msg_admin_operation_options",2)
?>
	<tr>
		<td><input type="checkbox" name="item_name" value="name"></td>
		<td><b><?php echo "$msg_admin_name"; ?> </b></td>
	</tr>
	<tr>
		<td><input type="checkbox" name="item_email" value="email" checked></td>
		<td><b><?php echo "$msg_admin_email"; ?> </b></td>
	</tr>
	<tr>
		<td>
		<input type="radio" name="separator" value="," checked>,<br>
		<input type="radio" name="separator" value="|">|<br>
		<input type="radio" name="separator" value=";">;<br>
		<input type="radio" name="separator" value="	">TAB<br>
		</td>
		<td><b><?php echo "$msg_admin_separator"; ?> </b></td>
	</tr>
<?php
dohtml_form_footer($msg_admin_menu_view);

	dohtml_form_header("emaillog","download_log",0,1);
	dohtml_table_header("main","$msg_admin_operation_options2",2)
?>
	<tr>
		<td><input type="checkbox" name="item_name" value="name"></td>
		<td><b><?php echo "$msg_admin_name"; ?> </b></td>
	</tr>
	<tr>
		<td><input type="checkbox" name="item_email" value="email" checked></td>
		<td><b><?php echo "$msg_admin_email"; ?> </b></td>
	</tr>
	<tr>
		<td>
		<input type="radio" name="separator" value="," checked>,<br>
		<input type="radio" name="separator" value="|">|<br>
		<input type="radio" name="separator" value=";">;<br>
		<input type="radio" name="separator" value="	">TAB<br>
		</td>
		<td><b><?php echo "$msg_admin_separator"; ?> </b></td>
	</tr>
<?php
dohtml_form_footer($msg_admin_menu_download);

$html = "<font color=\"#FF0000\"><b>$msg_admin_warning</b></h2> $msg_admin_emillog_note";
dohtml_form_header("emaillog","clean_db",1,1);
dohtml_table_header("emptylink",$msg_admin_emillog_empty);
dohtml_form_infobox($html);
dohtml_form_footer($msg_admin_empty);
dothml_pagefooter();

	exit;
}

?>
