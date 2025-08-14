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
$templatesused = 'cardsent';
require('./lib.inc.php');

$sender_name = $HTTP_POST_VARS['sender_name'];
$sender_email = $HTTP_POST_VARS['sender_email'];
$recip_name = $HTTP_POST_VARS['recip_name'];
$recip_email = $HTTP_POST_VARS['recip_email'];
$card_file = addslashes($HTTP_POST_VARS['card_file']);
$card_id = $HTTP_POST_VARS['card_id'];
$card_cat = $HTTP_POST_VARS['card_cat'];
if ($HTTP_POST_VARS['card_stamp'] == "null.gif"){
	$card_stamp ='';
}else{
	$card_stamp = $HTTP_POST_VARS['card_stamp'];
}
$card_message = $HTTP_POST_VARS['card_message'];
$card_sig = $HTTP_POST_VARS['card_sig'];
$card_poem = $HTTP_POST_VARS['card_poem'];
$card_heading = $HTTP_POST_VARS['card_heading'];
$card_sound = $HTTP_POST_VARS['card_sound'];
if ($HTTP_POST_VARS['card_background'] == "null.gif"){
	$card_background ='';
}else{
	$card_background = $HTTP_POST_VARS['card_background'];
}
$card_color = $HTTP_POST_VARS['card_color'];
$card_template = $HTTP_POST_VARS['card_template'];
$card_fontface = $HTTP_POST_VARS['card_fontface'];
$card_fontcolor = $HTTP_POST_VARS['card_fontcolor'];
$card_fontsize = $HTTP_POST_VARS['card_fontsize'];
$card_notify = $HTTP_POST_VARS['card_notify'];
$card_copy = $HTTP_POST_VARS['card_copy'];
$card_tosend = $HTTP_POST_VARS['card_tosend'];
$attach_id = $HTTP_POST_VARS['attach_id'];
$receive_newsletter = $HTTP_POST_VARS['receive_newsletter'];

$recip_email1  = cexpr(!mailval($HTTP_POST_VARS['recip_email1'],2),$HTTP_POST_VARS['recip_email1'],"");
$recip_email2  = cexpr(!mailval($HTTP_POST_VARS['recip_email2'],2),$HTTP_POST_VARS['recip_email2'],"");
$recip_email3  = cexpr(!mailval($HTTP_POST_VARS['recip_email3'],2),$HTTP_POST_VARS['recip_email3'],"");
$recip_email4  = cexpr(!mailval($HTTP_POST_VARS['recip_email4'],2),$HTTP_POST_VARS['recip_email4'],"");
$recip_email5  = cexpr(!mailval($HTTP_POST_VARS['recip_email5'],2),$HTTP_POST_VARS['recip_email5'],"");
$recip_email6  = cexpr(!mailval($HTTP_POST_VARS['recip_email6'],2),$HTTP_POST_VARS['recip_email6'],"");
$recip_email7  = cexpr(!mailval($HTTP_POST_VARS['recip_email7'],2),$HTTP_POST_VARS['recip_email7'],"");
$recip_email8  = cexpr(!mailval($HTTP_POST_VARS['recip_email8'],2),$HTTP_POST_VARS['recip_email8'],"");
$recip_email9  = cexpr(!mailval($HTTP_POST_VARS['recip_email9'],2),$HTTP_POST_VARS['recip_email9'],"");
$recip_email10 = cexpr(!mailval($HTTP_POST_VARS['recip_email10'],2),$HTTP_POST_VARS['recip_email10'],"");
$recip_email11 = cexpr(!mailval($HTTP_POST_VARS['recip_email11'],2),$HTTP_POST_VARS['recip_email11'],"");
$recip_email12 = cexpr(!mailval($HTTP_POST_VARS['recip_email12'],2),$HTTP_POST_VARS['recip_email12'],"");
$recip_email13 = cexpr(!mailval($HTTP_POST_VARS['recip_email13'],2),$HTTP_POST_VARS['recip_email13'],"");
$recip_email14 = cexpr(!mailval($HTTP_POST_VARS['recip_email14'],2),$HTTP_POST_VARS['recip_email14'],"");
$recip_email15 = cexpr(!mailval($HTTP_POST_VARS['recip_email15'],2),$HTTP_POST_VARS['recip_email15'],"");

if (empty($card_notify))
{
	$card_notify = 0;
}
// query to stat
$result 	= $DB_site->query_first("SELECT * FROM vcard_cards WHERE card_imgfile='".addslashes($card_file)."'");
$card_imgthm	= $result['card_thmfile'];
external_filelog($card_file);

	if (!empty($recip_email)){
		$recip_emails .= "$recip_email, ";
		$recip_names .= "$recip_name, ";
		$message_id = save_data_card($recip_email  ,$recip_name,$sender_name,$sender_email,$card_file,$card_id,$card_cat,$card_stamp,$card_message,$card_sig,$card_heading,$card_poem,$card_sound,$card_background,$card_color,$card_template,$card_fontface,$card_fontcolor,$card_fontsize,$card_notify,$card_tosend);}

	if (!empty($attach_id))
	{
		$DB_site->query("UPDATE vcard_attach SET status='complete',messageid='$message_id' WHERE attach_id='".addslashes($attach_id)."'");
		unset($message_id);
	}
	
	if (!empty($recip_email1)){
		$recip_emails .= "$recip_email1, ";
		$recip_names .= "$recip_name1, ";
		save_data_card($recip_email1 ,$HTTP_POST_VARS['recip_name1'] ,$sender_name,$sender_email,$card_file,$card_id,$card_cat,$card_stamp,$card_message,$card_sig,$card_heading,$card_poem,$card_sound,$card_background,$card_color,$card_template,$card_fontface,$card_fontcolor,$card_fontsize,$card_notify,$card_tosend);}
	if (!empty($recip_email2)){
		$recip_emails .= "$recip_email2, ";
		$recip_names .= "$recip_name2, ";
		save_data_card($recip_email2 ,$HTTP_POST_VARS['recip_name2'] ,$sender_name,$sender_email,$card_file,$card_id,$card_cat,$card_stamp,$card_message,$card_sig,$card_heading,$card_poem,$card_sound,$card_background,$card_color,$card_template,$card_fontface,$card_fontcolor,$card_fontsize,$card_notify,$card_tosend);}
	if (!empty($recip_email3)){
		$recip_emails .= "$recip_email3, ";
		$recip_names .= "$recip_name3, ";
		save_data_card($recip_email3 ,$HTTP_POST_VARS['recip_name3'] ,$sender_name,$sender_email,$card_file,$card_id,$card_cat,$card_stamp,$card_message,$card_sig,$card_heading,$card_poem,$card_sound,$card_background,$card_color,$card_template,$card_fontface,$card_fontcolor,$card_fontsize,$card_notify,$card_tosend);}
	if (!empty($recip_email4)){
		$recip_emails .= "$recip_email4, ";
		$recip_names .= "$recip_name4, ";
		save_data_card($recip_email4 ,$HTTP_POST_VARS['recip_name4'] ,$sender_name,$sender_email,$card_file,$card_id,$card_cat,$card_stamp,$card_message,$card_sig,$card_heading,$card_poem,$card_sound,$card_background,$card_color,$card_template,$card_fontface,$card_fontcolor,$card_fontsize,$card_notify,$card_tosend);}
	if (!empty($recip_email5)){
		$recip_emails .= "$recip_email5, ";
		$recip_names .= "$recip_name5, ";
		save_data_card($recip_email5 ,$HTTP_POST_VARS['recip_name5'] ,$sender_name,$sender_email,$card_file,$card_id,$card_cat,$card_stamp,$card_message,$card_sig,$card_heading,$card_poem,$card_sound,$card_background,$card_color,$card_template,$card_fontface,$card_fontcolor,$card_fontsize,$card_notify,$card_tosend);}
	if (!empty($recip_email6)){
		$recip_emails .= "$recip_email6, ";
		$recip_names .= "$recip_name6, ";
		save_data_card($recip_email6 ,$HTTP_POST_VARS['recip_name6'] ,$sender_name,$sender_email,$card_file,$card_id,$card_cat,$card_stamp,$card_message,$card_sig,$card_heading,$card_poem,$card_sound,$card_background,$card_color,$card_template,$card_fontface,$card_fontcolor,$card_fontsize,$card_notify,$card_tosend);}
	if (!empty($recip_email7)){
		$recip_emails .= "$recip_email7, ";
		$recip_names .= "$recip_name7, ";
		save_data_card($recip_email7 ,$HTTP_POST_VARS['recip_name7'] ,$sender_name,$sender_email,$card_file,$card_id,$card_cat,$card_stamp,$card_message,$card_sig,$card_heading,$card_poem,$card_sound,$card_background,$card_color,$card_template,$card_fontface,$card_fontcolor,$card_fontsize,$card_notify,$card_tosend);}
	if (!empty($recip_email8)){
		$recip_emails .= "$recip_email8, ";
		$recip_names .= "$recip_name8, ";
		save_data_card($recip_email8 ,$HTTP_POST_VARS['recip_name8'] ,$sender_name,$sender_email,$card_file,$card_id,$card_cat,$card_stamp,$card_message,$card_sig,$card_heading,$card_poem,$card_sound,$card_background,$card_color,$card_template,$card_fontface,$card_fontcolor,$card_fontsize,$card_notify,$card_tosend);}
	if (!empty($recip_email9)){
		$recip_emails .= "$recip_email9, ";
		$recip_names .= "$recip_name9, ";
		save_data_card($recip_email9 ,$HTTP_POST_VARS['recip_name9'] ,$sender_name,$sender_email,$card_file,$card_id,$card_cat,$card_stamp,$card_message,$card_sig,$card_heading,$card_poem,$card_sound,$card_background,$card_color,$card_template,$card_fontface,$card_fontcolor,$card_fontsize,$card_notify,$card_tosend);}
	if (!empty($recip_email10)){
		$recip_emails .= "$recip_email10, ";
		$recip_names .= "$recip_name10, ";
		save_data_card($recip_email10,$HTTP_POST_VARS['recip_name10'],$sender_name,$sender_email,$card_file,$card_id,$card_cat,$card_stamp,$card_message,$card_sig,$card_heading,$card_poem,$card_sound,$card_background,$card_color,$card_template,$card_fontface,$card_fontcolor,$card_fontsize,$card_notify,$card_tosend);}
	if (!empty($recip_email11)){
		$recip_emails .= "$recip_email11, ";
		$recip_names .= "$recip_name11, ";
		save_data_card($recip_email11,$HTTP_POST_VARS['recip_name11'],$sender_name,$sender_email,$card_file,$card_id,$card_cat,$card_stamp,$card_message,$card_sig,$card_heading,$card_poem,$card_sound,$card_background,$card_color,$card_template,$card_fontface,$card_fontcolor,$card_fontsize,$card_notify,$card_tosend);}
	if (!empty($recip_email12)){
		$recip_emails .= "$recip_email12, ";
		$recip_names .= "$recip_name12, ";
		save_data_card($recip_email12,$HTTP_POST_VARS['recip_name12'],$sender_name,$sender_email,$card_file,$card_id,$card_cat,$card_stamp,$card_message,$card_sig,$card_heading,$card_poem,$card_sound,$card_background,$card_color,$card_template,$card_fontface,$card_fontcolor,$card_fontsize,$card_notify,$card_tosend);}
	if (!empty($recip_email13)){
		$recip_emails .= "$recip_email13, ";
		$recip_names .= "$recip_name13, ";
		save_data_card($recip_email13,$HTTP_POST_VARS['recip_name13'],$sender_name,$sender_email,$card_file,$card_id,$card_cat,$card_stamp,$card_message,$card_sig,$card_heading,$card_poem,$card_sound,$card_background,$card_color,$card_template,$card_fontface,$card_fontcolor,$card_fontsize,$card_notify,$card_tosend);}
	if (!empty($recip_email14)){
		$recip_emails .= "$recip_email14, ";
		$recip_names .= "$recip_name14, ";
		save_data_card($recip_email14,$HTTP_POST_VARS['recip_name14'],$sender_name,$sender_email,$card_file,$card_id,$card_cat,$card_stamp,$card_message,$card_sig,$card_heading,$card_poem,$card_sound,$card_background,$card_color,$card_template,$card_fontface,$card_fontcolor,$card_fontsize,$card_notify,$card_tosend);}
	if (!empty($recip_email15)){
		$recip_emails .= "$recip_email15, ";
		$recip_names .= "$recip_name15, ";
		save_data_card($recip_email15,$HTTP_POST_VARS['recip_name15'],$sender_name,$sender_email,$card_file,$card_id,$card_cat,$card_stamp,$card_message,$card_sig,$card_heading,$card_poem,$card_sound,$card_background,$card_color,$card_template,$card_fontface,$card_fontcolor,$card_fontsize,$card_notify,$card_tosend);}

if ($card_copy == 1)
{
	save_data_cardcopy($recip_emails,$recip_names,$sender_name,$sender_email,$card_file,$card_id,$card_imgthm,$card_cat,$card_stamp,$card_message,$card_sig,$card_heading,$card_poem,$card_sound,$card_background,$card_color,$card_template,$card_fontface,$card_fontcolor,$card_fontsize,$card_notify,$card_tosend);
}

make_redirectpage("done.php");
exit;
?>