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

if ($action == 'delete')
{
	// Delete Incomplete upload files
	$gtime = date ("Y-m-d_His");
	// auto delete incomplete upload files
	$deleted_attach = $DB_site->query("DELETE FROM vcard_attach WHERE ( time < '$gtime' ) AND ( status='incomplete' )");
	$result = $DB_site->query("SELECT message_id FROM vcard_user WHERE (card_tosend <= DATE_SUB(CURRENT_DATE, INTERVAL $HTTP_POST_VARS[interval] DAY)) AND (card_read = '$HTTP_POST_VARS[readed]')");
	$number = 0;
	while ($row = $DB_site->fetch_array($result))
	{
		// Display list of card to delete
		if ($admin_cards_deletelist == 1)
		{
			$delete_card_list .= "<a href=\"./../pickup.php?message_id=" . $row['message_id'] . "\">postcard #". $row['message_id'] ."</a><br>\n";
		}
		$number++;
	}
	$DB_site->free_result($result);
	$html = cexpr($admin_cards_deletelist,"<table><tr><td> $delete_card_list </td></tr></table>","");
	
	$MsgReaded = cexpr($readed,"$msg_admin_main_del_read","$msg_admin_main_del_noread");
	if ($number == 0)
	{
		dohtml_table_header($link,$msg_admin_main_cardcontrol,$colspan="2");
		$html =  "<p><b>$msg_admin_error_nocriteria</p></b><br>";
		dohtml_form_label($msg_admin_main_cardcontrol,$html);
		dohtml_table_footer();
		exit;
	}

	$html =  "<center><H2>$msg_admin_warning</H2></center>
	$msg_admin_main_del_alert <br>
	- <b><i><font color=\"#FF0000\">[ $MsgReaded ]</font></i></b><br>
	- <b><i><font color=\"#FF0000\">[ $HTTP_POST_VARS[interval] ]</font></i></b> $msg_admin_main_del_dayold 
	<p> <i><b><font color=\"#FF0000\">[ $number ]</font></b></i> $msg_admin_main_del_criteria </p>
	$msg_admin_op_confirm_question
	</b><center>" . $html;
	
	dohtml_form_header("main","delete_confirm",0,1);
	dohtml_table_header("top","$msg_admin_main_cardcontrol",2);
	dohtml_form_hidden("interval",$HTTP_POST_VARS['interval']);
	dohtml_form_hidden("readed",$HTTP_POST_VARS['readed']);
	dohtml_form_label($msg_admin_delete,$html);
	dohtml_table_footer();
	dohtml_form_footer($msg_button_confirm);
	exit;
}

if ($action == 'delete_confirm')
{
	$result = $DB_site->query(" SELECT message_id, card_file FROM vcard_user WHERE (card_tosend <= DATE_SUB(CURRENT_DATE, INTERVAL $HTTP_POST_VARS[interval] DAY)) AND (card_read = '$HTTP_POST_VARS[readed]') ");
	$icounter = 0;
	while ($row = $DB_site->fetch_array($result))
	{
		// Delete if file was uploaded
		if (ereg("attachment.",$row['card_file']))
		{
			$delattach = $DB_site->query("DELETE FROM vcard_attach WHERE messageid ='$row[message_id]'");
		}
		$icounter++;
	}
	$DB_site->free_result($result);
	$delcards = $DB_site->query("DELETE FROM vcard_user WHERE (card_tosend <= DATE_SUB(CURRENT_DATE, INTERVAL $HTTP_POST_VARS[interval] DAY)) AND (card_read = '$HTTP_POST_VARS[readed]')");
	$html = "<b>[$icounter]</b> $msg_admin_main_del_result";
	dohtml_table_header("top","$msg_admin_main_cardcontrol",2);
	dohtml_form_label($msg_admin_deleted,$html);
	dohtml_table_footer();
	exit;
}
// ############################# ACTION SCREENS #############################
// SCREEN = DELETE GREETINGS
if (empty($action))
{
	if ($canviewcontrol==1 or $superuser==1)
	{
	dohtml_form_header("main","delete",0,0);
	dohtml_table_header("main","$msg_admin_main_cardcontrol");
?>
	<tr>
		<td>
		<select name="interval">
		<option value="7">7 		<?php echo "$msg_admin_days"; ?></option>
		<option value="14" selected>14  <?php echo "$msg_admin_days"; ?></option>
		<option value="30">30 		<?php echo "$msg_admin_days"; ?></option>
		<option value="60">60 		<?php echo "$msg_admin_days"; ?></option>
		</select>&nbsp;&nbsp;&nbsp;
		<select name="readed">
		<option value="1" selected><?php echo "$msg_admin_main_onlypicked"; ?></option>
		<option value="0"><?php echo "$msg_admin_main_onlynopicked"; ?></option>
		</select>
		</td>
	</tr>
<?php
	dohtml_form_infobox("$msg_admin_note $msg_admin_main_note");
	dohtml_form_footer("$msg_admin_reg_delete");
	}

dohtml_table_header("extrainfo","$msg_admin_extrainfo");
?>
<tr>
	<td>
	</td>
</tr>
</table>
<!-- /EXTRA -->
<?php
dothml_pagefooter();
exit;
}
?>
