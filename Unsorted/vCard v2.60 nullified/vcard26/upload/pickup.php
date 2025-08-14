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
$templatesused = 'pickup';
require('./lib.inc.php');

$message_id = addslashes($HTTP_GET_VARS['message_id']);
if(!ereg('^[0-9]{12}[a-zA-Z0-9]{10,12}', $message_id) ){ 
	die("Try hacking somebody else's site."); 
}

$usercard_find 	= $DB_site->query("SELECT * FROM vcard_user WHERE message_id='$message_id' ");
$number = $DB_site->num_rows($usercard_find);

if ($number == 0)
{
	make_error_page(!check_emailformate($sender_email),$MsgErrorNotFoundTxt,1);
	exit;
}

$usercard_data = $DB_site->fetch_array($usercard_find);
extract($usercard_data);
// card_id
if (!empty($card_id))
{
	$card_info = $DB_site->query_first("SELECT * FROM vcard_cards WHERE card_id='$card_id' ");
	$card_file = $card_info['card_imgfile'];
	$card_width = $card_info['card_width'];
	$card_height = $card_info['card_height'];
	$card_caption = $card_info['card_caption'];
	//extract($card_info);
	if (empty($card_info['card_template']))
	{
		$card_template = $usercard_data['card_template'];
	}
}
$sender_name = stripslashes($sender_name);
$recip_name	= stripslashes($recip_name);
$card_caption = stripslashes($card_caption);
$card_message = stripslashes($card_message);
$card_sig = stripslashes($card_sig);
$card_heading	= stripslashes($card_heading);
if (!empty($card_poem))
{
	$poem_info	= $DB_site->query_first("SELECT * FROM vcard_poem WHERE poem_id='$card_poem' ");
	if($poem_info['poem_style']==1)
	{
		$poem_title = stripslashes($poem_info['poem_title']);
		$poem_text 	= stripslashes($poem_info['poem_text']);
	}else{
		$poem_title = stripslashes(htmlspecialchars($poem_info['poem_title']));
		$poem_text 	= nl2br(stripslashes(htmlspecialchars($poem_info['poem_text'])));
		$poem_text 	= eregi_replace("<br />", "<br>", $poem_text); 
	}
}
	
//////////////////////////////////////////////////
// COOKIE FUNCTIONS: SENDER                     //
//////////////////////////////////////////////////
@setcookie ("vCard_sendername", $recip_name, time()+36000, "/");
@setcookie ("vCard_senderemail", $recip_email, time()+36000, "/");
@setcookie ("vCard_recpname", $sender_name, time()+900, "/");
@setcookie ("vCard_recpemail", $sender_email, time()+900, "/");
if ($card_read == 0)
{
if ($card_notify == 1)
{
	sendmail_notify($sender_email,$sender_name,$recip_name,$recip_email);
}
// insert update info into card user database
$usercard_update = $DB_site->query("UPDATE vcard_user SET card_read='1' WHERE message_id='$message_id' ");
}
// Get Music Info
if (!empty($card_sound))
{
	$music_info_find= $DB_site->query_first("SELECT * FROM vcard_sound WHERE sound_file='$card_sound' ");
	extract($music_info_find);
	$sound_name 	= stripslashes($sound_name);
	$sound_author 	= stripslashes($sound_author);
	$musicinfo 	= "$MsgMusic : $sound_name - $sound_author";
}

$bbcode_card_message = parse_vcode(stripslashes($card_message));
$bbcode_card_heading = parse_vcode($card_heading);
$bbcode_card_sig = parse_vcode($card_sig);

// Check FLASH or GIF/JPEG
$card_file = (eregi('attachment.php',$card_file))? $card_file."&ck=$message_id" : $card_file;
$html_post_image = get_html_image($site_image_url,$card_file,$card_width,$card_height);
// Check MUSIC
$html_card_sound = get_html_music($site_music_url,$card_sound);
// Check STAMP
$card_stamp = cexpr($user_stamp_allow==1,$card_stamp,$user_stamp_default);

$html_card_stamp = get_html_stamp($site_image_url,$card_stamp,$site_url,$site_name);

if(!empty($card_template))
{
	$t = new Template("./templates");
	$t->set_file(array("mainpage" => "$card_template.ihtml"));
	// new schema v 2.3
	$t->set_var("SITE_NAME"		,$site_name);
	$t->set_var("SITE_URL"		,$site_url);
	$t->set_var("SITE_FONTFACE"	,$site_font_face);
	$t->set_var("SENDER_NAME"	,$sender_name);
	$t->set_var("SENDER_EMAIL"	,$sender_email);
	$t->set_var("RECIP_NAME"	,$recip_name);
	$t->set_var("RECIP_EMAIL"	,$recip_email);
	$t->set_var("POST_IMAGE"	,$html_post_image);
	$t->set_var("POST_FILE"		,$card_file);
	$t->set_var("POST_WIDTH"	,$card_width);
	$t->set_var("POST_HEIGHT"	,$card_height);
	$t->set_var("POST_CAPTION"	,$card_caption);
	$t->set_var("POST_MESSAGE"	,$bbcode_card_message);
	$t->set_var("POST_SIGNATURE"	,$card_sig);
	$t->set_var("POST_HEADING"	,$card_heading);
	$t->set_var("POST_SOUND"	,$html_card_sound);
	$t->set_var("POST_STAMP"	,$html_card_stamp);
	$t->set_var("POST_COLOR"	,$card_color);
	$t->set_var("POST_FONTFACE"	,$card_fontface);
	$t->set_var("POST_FONTSIZE"	,$card_fontsize);
	$t->set_var("POST_FONTCOLOR"	,$card_fontcolor);
	$t->set_var("POST_POEMTITLE"	,$poem_title);
	$t->set_var("POST_POEMBODY"	,$poem_text);
	$t->set_var("MSG_SENTTO"	,$MsgSendTo);
	$t->set_var("MSG_CLICKHERE"	,$MsgClickHere);
	
	$t->parse("output","mainpage");
	
	$outputcard 	= $t->getcard("output");
}
$printlink	= "<a href=\"print.php?message_id=$message_id\"><img src=\"img/icon_print.gif\" alt=\"$MsgPrintable\" border=\"0\"><br><font face=\"$site_font_face\">$MsgPrintable</b></font></a>";
$musicinfo	= $musicinfo;
$card 		= $outputcard;
$htmlbody 	= html_body($site_image_url,$card_background,$site_body_bgcolor,$site_body_text,$site_body_link,$site_body_vlink,$site_body_alink,$site_body_marginwidth,$site_body_marginheight);

if(eregi('.html',$card_file))
{
	$htmlcard = str_replace('.html','',$card_file);
	$t2 = new Template("$site_image_path/");
	$t2->set_file(array("mainpage" => $htmlcard.".html"));

	// new schema v 2.3
	$t2->set_var("SITE_NAME"	,$site_name);
	$t2->set_var("SITE_URL"		,$site_url);
	$t2->set_var("SENDER_NAME"	,$sender_name);
	$t2->set_var("SENDER_EMAIL"	,$sender_email);
	$t2->set_var("SITE_FONTFACE"	,$site_font_face);
	$t2->set_var("RECIP_NAME"	,$recip_name);
	$t2->set_var("RECIP_EMAIL"	,$recip_email);
	$t2->set_var("POST_IMAGE"	,$html_post_image);
	$t2->set_var("POST_FILE"	,$card_file);
	$t2->set_var("POST_WIDTH"	,$card_width);
	$t2->set_var("POST_HEIGHT"	,$card_height);
	$t2->set_var("POST_CAPTION"	,$card_caption);
	$t2->set_var("POST_MESSAGE"	,$bbcode_card_message);
	$t2->set_var("POST_SIGNATURE"	,$bbcode_card_sig);
	$t2->set_var("POST_HEADING"	,$bbcode_card_heading);
	$t2->set_var("POST_SOUND"	,$html_card_sound);
	$t2->set_var("POST_STAMP"	,$html_card_stamp);
	$t2->set_var("POST_COLOR"	,$card_color);
	$t2->set_var("POST_FONTFACE"	,$card_fontface);
	$t2->set_var("POST_FONTSIZE"	,$card_fontsize);
	$t2->set_var("POST_FONTCOLOR"	,$card_fontcolor);
	$t2->set_var("POST_POEMTITLE"	,$poem_title);
	$t2->set_var("POST_POEMBODY"	,$poem_text);

	$t2->set_var("MSG_SENTTO"	,$MsgSendTo);
	$t2->set_var("MSG_CLICKHERE"	,$MsgClickHere);
	
	$t2->set_var("CARDFORM"	,$printlink);
	$t2->set_var("SOUND_INFO"	,$musicinfo);
	$t2->set_var("HEADER"		,$header);
	$t2->set_var("FOOTER"		,$footer);
	$t2->set_var("HEADINCLUDE"	,$headinclude);
	$t2->parse("output","mainpage");
	$t2->p("output");
}else{
	eval("make_output(\"".get_template("pickup")."\");");
}

if($debug==1){ $timer->end_time(); $timer->elapsed_time();}
$DB_site->close();
if($use_gzip == 1) {ob_end_flush(); }
?>
