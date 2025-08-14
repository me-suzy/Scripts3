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

dothml_pageheader();
// Category

// ############################# DB ACTION #############################
if ($action == 'update')
{
	$event_dayend = cexpr($HTTP_POST_VARS['event_dayend']=='',$HTTP_POST_VARS['event_day'],$HTTP_POST_VARS['event_dayend']);
	if ($event_dayend < $HTTP_POST_VARS['event_day'])
	{
		$event_dayend = $HTTP_POST_VARS['event_day'];
	}
	$result = $DB_site->query(" UPDATE vcard_event SET event_name='".addslashes($HTTP_POST_VARS['event_name'])."', event_day='$HTTP_POST_VARS[event_day]', event_dayend='$event_dayend', event_month='$HTTP_POST_VARS[event_month]' WHERE event_id='$HTTP_POST_VARS[event_id]' ");
	dohtml_result($result,"$msg_admin_reg_update");
	$action = '';
}

if ($action == 'delete')
{
	dohtml_form_header("event","delete_yes",0,1);
	dohtml_table_header("edit","$msg_admin_op_confirm_question",2);
	dohtml_form_hidden("event_id",$HTTP_GET_VARS['event_id']);
	$html .= "<b>$msg_admin_menu_delete</b>";
	dohtml_form_label($msg_admin_op_confirm_question,$html);
	dohtml_table_footer();
	dohtml_form_footer($msg_button_confirm);
	exit;
}

if ($action == 'delete_yes' && ($superuser==1 || $vcuser[candeleteevent]==1))
{
	$result = $DB_site->query(" DELETE FROM vcard_event WHERE event_id='$HTTP_POST_VARS[event_id]' ");
	dohtml_result($result,"$msg_admin_reg_delete");
	$action = '';
}

if ($action == 'insert')
{
	// check if is a valide data
	checkfieldempty($HTTP_POST_VARS['event_name'],"$msg_admin_event $msg_admin_error_formempty");
	$event_dayend = cexpr($HTTP_POST_VARS['event_dayend']=='',$HTTP_POST_VARS['event_day'],$HTTP_POST_VARS['event_dayend']);
	if ($event_dayend < $HTTP_POST_VARS['event_day'])
	{
		$event_dayend = $HTTP_POST_VARS['event_day'];
	}
	$result = $DB_site->query("
			INSERT
			INTO vcard_event (event_id,event_name,event_day,event_dayend,event_month)
			VALUES (NULL,'".addslashes($HTTP_POST_VARS[event_name])."','$HTTP_POST_VARS[event_day]','$event_dayend','$HTTP_POST_VARS[event_month]')
			");
	dohtml_result($result,"$msg_admin_reg_add");
	$action = 'add';
}


// ############################# ACTION SCREENS #############################
// SCREEN = EDIT EVENT
if ($action == 'edit')
{
	$result = $DB_site->query_first(" SELECT * FROM vcard_event WHERE event_id='$HTTP_GET_VARS[event_id]' ");
	extract($result);
	$event_name = stripslashes($event_name);
	
dohtml_form_header("event","update",0,1);
dohtml_table_header("edit","$msg_admin_menu_edit");
dohtml_form_hidden("event_id",$HTTP_GET_VARS['event_id']);
dohtml_form_input($msg_admin_name,"event_name",$event_name);
dohtml_form_label("$msg_admin_start $msg_admin_day",dohtml_form_selectdate("event_day","$event_day",31));
dohtml_form_label("$msg_admin_end $msg_admin_day",dohtml_form_selectdate("event_dayend","$event_dayend",31));
dohtml_form_label($msg_admin_month,dohtml_form_selectdate("event_month","$event_month",12));
dohtml_form_footer($msg_admin_reg_update);
dothml_pagefooter();
exit;
}

// SCREEN = ADD EVENT
if ($action == 'add')
{
dohtml_form_header("event","insert",0,1);
dohtml_table_header("add","$msg_admin_menu_add");
dohtml_form_input($msg_admin_name,"event_name");
dohtml_form_label("$msg_admin_start $msg_admin_day",dohtml_form_selectdate("event_day","",31));
dohtml_form_label("$msg_admin_end $msg_admin_day",dohtml_form_selectdate("event_dayend","",31));
dohtml_form_label($msg_admin_month,dohtml_form_selectdate("event_month","",12));
dohtml_form_footer($msg_admin_reg_add);
dothml_pagefooter();
exit;
}

// SCREEN = DEFAULT
if (empty($action))
{
	dohtml_table_header("edit","$msg_admin_menu_edit",4);
	$eventlist = $DB_site->query(" SELECT * FROM vcard_event ORDER BY event_month,event_day ASC ");
	$html .= "<table><tr><td><b>$MsgCalendarMonth</b> &nbsp;</td><td><b>$MsgCalendarDayBegin</b> &nbsp;</td><td><b>$MsgCalendarDayEnd</b> &nbsp;</td><td width=\"65%\"><b>$MsgCalendarEventName</b></td></tr>\n";
	while ($event = $DB_site->fetch_array($eventlist))
	{
		extract($event);
		$event_name 	= stripslashes(htmlspecialchars($event_name));
		// Display list of categories
		//$Month = cexpr($event_month<10,"$MsgMonth[$event_month]","$event_month");
		$html .= "<tr class=\"".get_row_bg()."\"><td>". get_monthname($event_month) ."</td><td>$event_day &nbsp; </td><td>$event_dayend &nbsp;</td><td><b>$event_name</b> &nbsp;".
		cexpr(($superuser==1 || $vcuser[caneditevent]==1),"<a href=\"event.php?action=edit&event_id=$event_id&s=$s\">[$msg_admin_menu_edit]</a>&nbsp;","").
		cexpr(($superuser==1 || $vcuser[candeleteevent]==1),"<a href=\"event.php?action=delete&event_id=$event_id&s=$s\">[$msg_admin_menu_delete]</a>","").
		"</td></tr>\n";
	}
	$DB_site->free_result($eventlist);
	$html .= "</table>";
	dohtml_form_label($msg_admin_event,$html);
	dohtml_table_footer();
	dothml_pagefooter();
	exit;
}
?>
