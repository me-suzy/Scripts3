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
$templatesused 	= 'create,create_form,rating_box,preview';
require('./lib.inc.php');

$thisborder = 0;
$thiscellspacing = 1;
$thiscellpadding = 2;
$thisalign = 'center';
$thiswidth = '100%';

if ( empty($HTTP_POST_VARS['action']) || $HTTP_POST_VARS['action']=='edit' )
{

$cardsgroup_newsletter	= 1;
$mailinglist_checked 	= 1;
$html_formfont ="<font face='$form_font_face' size='$form_font_size' color='$form_font_color'>";

/*#########################################################################
IMAGE INFO: template, image file, caption 
#########################################################################*/
if (!empty($HTTP_GET_VARS['card_id']) || !empty($HTTP_POST_VARS['card_id']))
{
	if(!empty($HTTP_GET_VARS['card_id']))
	{
		$card_id = $HTTP_GET_VARS['card_id'];
	}else{
		$card_id = $HTTP_POST_VARS['card_id'];
	}
	$cardinfo = $DB_site->query_first("SELECT * FROM vcard_cards WHERE card_id='".addslashes($card_id)."'");
	//extract($result);
	$card_file = $cardinfo['card_imgfile'];
	$card_thumbnail = $cardinfo['card_thmfile'];
	$template = $cardinfo['card_template'];
	$width = $cardinfo['card_width'];
	$height = $cardinfo['card_height'];
	$card_caption = stripslashes($cardinfo['card_caption']);
	$card_file = $cardinfo['card_imgfile'];
	$card_width = $cardinfo['card_width'];
	$card_height = $cardinfo['card_height'];
	$int_templatecheck	= checkonlytemplate($card_file,$template);
	$card_group = $cardinfo['card_group'];
	//echo "$card_width/$card_height";
// case : image is f or attachment
}else{
	$card_file = $HTTP_POST_VARS['card_file'];
}

// ######################### card group ######################### //
if (!empty($card_group) && $card_group!=0)
{
	$card_groupinfo = $DB_site->query_first("SELECT * FROM vcard_cardsgroup WHERE cardsgroup_id='".addslashes($card_group)."' ");
	extract($card_groupinfo);
	$cardsgroup_multiplerecp = $user_multirecip_allow;
}else{
	$cardsgroup_fontface 	= 1;
	$cardsgroup_fontcolor 	= 1;
	$cardsgroup_fontsize 	= 1;
	
	$cardsgroup_cardcolor 	= 1;
	$cardsgroup_heading 	= 1;
	$cardsgroup_signature 	= 1;
	
	$cardsgroup_music 	= $user_music_allow;
	$cardsgroup_background 	= $user_pattern_allow;
	$cardsgroup_stamp 	= $user_stamp_allow;
	$cardsgroup_poem 	= $user_poem_allow;
	$cardsgroup_layout 	= 1;
	$cardsgroup_copy 	= 1;
	$cardsgroup_notify 	= $user_notify_allow;
	$cardsgroup_advancedate = $user_advance_allow;
	$cardsgroup_multiplerecp = $user_multirecip_allow;
	//echo "XIZ!";
}

if (!empty($HTTP_GET_VARS['uploaded']) && empty($card_file))
{
	$card_file = "attachment.php?id=$HTTP_GET_VARS[uploaded]&file=$HTTP_GET_VARS[file]";
	$cardsgroup_multiplerecp= 0;
}elseif (!empty($HTTP_GET_VARS['f'])){
	$card_file = addslashes($HTTP_GET_VARS['f']);
}elseif (empty($card_file)){
	
}

// default template
if(empty($card_color)) {$card_color = 'white';}
if(empty($card_fontcolor)) {$card_fontcolor = 'black';}
if(empty($card_fontface)) {$card_fontface = $site_font_face;}
if(empty($card_fontsize)) {$card_fontsize = '+1';}



if (!empty($template) && empty($user_template_only))
{
	$temp_template = $template;
	$cardsgroup_layout = 0;
}
elseif (empty($template) && !empty($user_template_only))
{
	$template = $user_template_only;
	$temp_template = $template;
	$cardsgroup_layout = 0;
}
elseif (!empty($template) && !empty($user_template_only))
{
	$template = $user_template_only;
	$temp_template = $user_template_only;
	$cardsgroup_layout = 0;
}
elseif (empty($template) && empty($user_template_only))
{
	$temp_template = 'template01'; // createpage
	$cardsgroup_layout = 1;
}

if(eregi('.html',$card_file))
{
	$cardsgroup_layout = 0;
}

//echo "$card_width/$card_height ($site_image_url,$card_file,$card_width,$card_height)";
$html_post_image = get_html_image($site_image_url,$card_file,$card_width,$card_height);

if (!empty($card_id) && $user_rating_allow==1){
	eval("\$rating_box = \"".get_template('rating_box')."\";");
}


/*#########################################################################
PAGE TEMP: colors list
#########################################################################*/
function colorlist($value="")
{
	$colors_list = array ("Aliceblue"=>"#F0F8FF","Antiquewhite"=>"#FAEBD7","Aqua"=>"#00FFFF","Aquamarine"=>"#7FFFD4","Azure"=>"#F0FFFF","Beige"=>"#F5F5DC","Bisque"=>"#FFE4C4","Black"=>"#000000","Blanchedalmond"=>"#FFEBCD","Blue"=>"#0000FF","Blueviolet"=>"#8A2BE2","Brown"=>"#A52A2A","Burlywood"=>"#DEB887","Cadetblue"=>"#5F9EA0","Chartreuse"=>"#7FFF00","Chocolate"=>"#D2691E","Coral"=>"#FF7F50","Cornflowerblue"=>"#6495ED","Cornsilk"=>"#FFF8DC","Crimson"=>"#DC143C","Cyan"=>"#00FFFF","Darkblue"=>"#00008B","Darkcyan"=>"#008B8B","Darkgoldenrod"=>"#B8860B","Darkgray"=>"#A9A9A9","Darkgreen"=>"#006400","Darkkhaki"=>"#BDB76B","Darkmagenta"=>"#8B008B","Darkolivegreen"=>"#556B2F","Darkorange"=>"#FF8C00","Darkorchid"=>"#9932CC","Darkred"=>"#8B0000","Darksalmon"=>"#E9967A","Darkseagreen"=>"#8FBC8F","Darkslateblue"=>"#483D8B","Darkslategray"=>"#2F4F4F","Darkturquoise"=>"#00CED1","Darkviolet"=>"#9400D3","deeppink"=>"#FF1493","Deepskyblue"=>"#00BFFF","Dimgray"=>"#696969","Dodgerblue"=>"#1E90FF","Firebrick"=>"#B22222","Floralwhite"=>"#FFFAF0","Forestgreen"=>"#228B22","Fuchsia"=>"#FF00FF","Gainsboro"=>"#DCDCDC","Ghostwhite"=>"#F8F8FF","Gold"=>"#FFD700","Goldenrod"=>"#DAA520","Gray"=>"#808080","Green"=>"#008000","Greenyellow"=>"#ADFF2F","Honeydew"=>"#F0FFF0","Hotpink"=>"#FF69B4","Indianred"=>"#CD5C5C","Indigo"=>"#4B0082","Ivory"=>"#FFFFF0","Khaki"=>"#F0E68C","Lavender"=>"#E6E6FA","Lavenderblush"=>"#FFF0F5","Lawngreen"=>"#7CFC00","Lemonchiffon"=>"#FFFACD","Lightblue"=>"#ADD8E6","Lightcoral"=>"#F08080","Lightcyan"=>"#E0FFFF","Lightgoldenrodyellow"=>"#FAFAD2","Lightgreen"=>"#90EE90","Lightgrey"=>"#D3D3D3","Lightpink"=>"#FFB6C1","Lightsalmon"=>"#FFA07A","Lightseagreen"=>"#20B2AA","Lightskyblue"=>"#87CEFA","Lightslategray"=>"#778899","Lightsteelblue"=>"#B0C4DE","Lightyellow"=>"#FFFFE0","Lime"=>"#00FF00","Limegreen"=>"#32CD32","Linen"=>"#FAF0E6","Magenta"=>"#FF00FF","Maroon"=>"#800000","Mediumauqamarine"=>"#66CDAA","Mediumblue"=>"#0000CD","Mediumorchid"=>"#BA55D3","Mediumpurple"=>"#9370D8","Mediumseagreen"=>"#3CB371","Mediumslateblue"=>"#7B68EE","Mediumspringgreen"=>"#00FA9A","Mediumturquoise"=>"#48D1CC","Mediumvioletred"=>"#C71585","Midnightblue"=>"#191970","Mintcream"=>"#F5FFFA","Mistyrose"=>"#FFE4E1","Moccasin"=>"#FFE4B5","Navajowhite"=>"#FFDEAD","Navy"=>"#000080","Oldlace"=>"#FDF5E6","Olive"=>"#808000","Olivedrab"=>"#688E23","Orange"=>"#FFA500","Orangered"=>"#FF4500","Orchid"=>"#DA70D6","Palegoldenrod"=>"#EEE8AA","Palegreen"=>"#98FB98","Paleturquoise"=>"#AFEEEE","Palevioletred"=>"#D87093","Papayawhip"=>"#FFEFD5","Peachpuff"=>"#FFDAB9","Peru"=>"#CD853F","Pink"=>"#FFC0CB","Plum"=>"#DDA0DD","Powderblue"=>"#B0E0E6","Purple"=>"#800080","Red"=>"#FF0000","Rosybrown"=>"#BC8F8F","Royalblue"=>"#4169E1","Saddlebrown"=>"#8B4513","Salmon"=>"#FA8072","Sandybrown"=>"#F4A460","Seagreen"=>"#2E8B57","Seashell"=>"#FFF5EE","Sienna"=>"#A0522D","Silver"=>"#C0C0C0","Skyblue"=>"#87CEEB","Slateblue"=>"#6A5ACD","Slategray"=>"#708090","Snow"=>"#FFFAFA","Springgreen"=>"#00FF7F","Steelblue"=>"#4682B4","Tan"=>"#D2B48C","Teal"=>"#008080","Thistle"=>"#D8BFD8","Tomato"=>"#FF6347","Turquoise"=>"#40E0D0","Violet"=>"#EE82EE","Wheat"=>"#F5DEB3","White"=>"#FFFFFF","Whitesmoke"=>"#F5F5F5","Yellow"=>"#FFFF00","YellowGreen"=>"#9ACD32");
	$html ='';
	foreach ($colors_list as $key=>$val)
	{
		$html.= "<option value=\"$val\" ".cexpr($val==$value,'selected','').">$key";
	}
	return $html;
}

/*#########################################################################
PROCESS TO SHOW:
#########################################################################*/
// IF use any template
if (!empty($temp_template))
{

	$card_stamp = ($user_stamp_allow!=1)? $user_stamp_default : '';
	$html_card_stamp = get_html_stamp($site_image_url,$card_stamp,$site_url,$site_name);

	$t = new Template("./templates");
	$t->set_file(array("mainpage" => "$temp_template.ihtml"));
	// new schema v 2.3
	$t->set_var("SITE_NAME"		,$site_name);
	$t->set_var("SITE_URL"		,$site_url);
	$t->set_var("SENDER_NAME"	,$MsgYourName);
	$t->set_var("SENDER_EMAIL"	,$MsgYourEmail);
	$t->set_var("RECIP_NAME"	,$MsgRecpName);
	$t->set_var("RECIP_EMAIL"	,$MsgRecpEmail);
	$t->set_var("POST_IMAGE"	,$html_post_image);
	$t->set_var("POST_FILE"		,$card_file);
	$t->set_var("POST_WIDTH"	,$card_width);
	$t->set_var("POST_HEIGHT"	,$card_height);
	$t->set_var("POST_STAMP"	,$html_card_stamp);
	$t->set_var("POST_CAPTION"	,$card_caption);
	$t->set_var("POST_MESSAGE"	,$MsgMessageHere);
	$t->set_var("POST_SIGNATURE"	,$MsgSignature);
	$t->set_var("POST_HEADING"	,$MsgYourTitle);
	$t->set_var("POST_COLOR"	,$card_color);
 	$t->set_var("POST_FONTFACE"	,$card_fontface);
	$t->set_var("POST_FONTCOLOR"	,$card_fontcolor);
	$t->set_var("POST_FONTSIZE"	,$card_fontsize);
	$t->set_var("POST_POEMTITLE"	,$poem_title);
	$t->set_var("POST_POEMBODY"	,$poem_text);
	
	$t->parse("output","mainpage");
	$output_card = $t->getcard("output");
	
// IF not use any template
}else {
// IF not use any template
	// IF not use any template > Show Image
	$output_card = "<!-- THE POSTCARD -->\n";
	$output_card.= $HTML_image_file;
	$output_card.= "\n<!-- /THE POSTCARD -->\n";
}
 

/*#########################################################################
HTML CODE > FORM:
#########################################################################*/
// COOKIE : read 

if ( !empty($HTTP_COOKIE_VARS['vCard_recpname']) ) 	{$recip_name 	= stripslashes($HTTP_COOKIE_VARS['vCard_recpname']);	}else{ $recip_name='';}
if ( !empty($HTTP_POST_VARS['recip_name']) )		{$recip_name 	= $HTTP_POST_VARS['recip_name']; }else{ $recip_name='';}
if ( !empty($HTTP_COOKIE_VARS['vCard_recpemail']) )	{$recip_email 	= stripslashes($HTTP_COOKIE_VARS['vCard_recpemail']);	}else{ $recip_email='';}
if ( !empty($HTTP_POST_VARS['recip_email']) )		{$recip_email 	= $HTTP_POST_VARS['recip_email']; }else{ $recip_email='';}
if ( !empty($HTTP_COOKIE_VARS['vCard_sendername']) )	{$sender_name 	= stripslashes($HTTP_COOKIE_VARS['vCard_sendername']);	}else{ $sender_name='';}
if ( !empty($HTTP_POST_VARS['sender_name']) )		{$sender_name 	= $HTTP_POST_VARS['sender_name']; }else{ $sender_name='';}
if ( !empty($HTTP_COOKIE_VARS['vCard_senderemail']) )	{$sender_email 	= stripslashes($HTTP_COOKIE_VARS['vCard_senderemail']);	}else{ $sender_email='';}
if ( !empty($HTTP_POST_VARS['sender_email']) )		{$sender_email 	= $HTTP_POST_VARS['sender_email']; }else{ $sender_email='';}

// GENERAL CODE FOR ALL
$html_form_hiddenfields = "<input type=\"hidden\" name=\"card_file\" value=\"$card_file\">";

//Check if uploaded image
if (!empty($HTTP_POST_VARS['uploaded']) || !empty($HTTP_GET_VARS['uploaded']))
{
	$uploaded = cexpr($HTTP_POST_VARS['uploaded'],$HTTP_POST_VARS['uploaded'],$HTTP_GET_VARS['uploaded']);
	$html_form_hiddenfields .= "<input type='hidden' name='attach_id' value='$uploaded'>";
}

// ######################### recipients ######################### //
	$html_form_recpsenderinfo = "
		<table width=\"100%\" border=\"0\">
			<tr>
				<td>$html_formfont<b>$MsgRecpName :</b></font></td>
				<td>$html_formfont<b>$MsgRecpEmail :</b></font></td>
			</tr>
			<tr>
				<td><input type=\"text\" name=\"recip_name\" value=\"".htmlspecialchars(stripslashes($recip_name))."\" size=\"$form_field_size\" maxlength=\"50\"></td>
				<td><input type=\"text\" name=\"recip_email\" value=\"".htmlspecialchars(stripslashes($recip_email))."\" size=\"$form_field_size\" maxlength=\"50\"></td>
			</tr>";
if (!empty($HTTP_POST_VARS['addrecip']) && $cardsgroup_multiplerecp == 1)
{
	$range = $HTTP_POST_VARS['addrecip'];
	$counter = 1;
	$range = (int)$range;
	for ($counter; $counter <= $range; $counter++)
	{
		eval ("\$l_recip_name = \$HTTP_POST_VARS['recip_name$counter'];");
		eval ("\$l_recip_email = \$HTTP_POST_VARS['recip_email$counter'];");
		$html_form_recpsenderinfo.= "<tr>
		<td><input type=\"text\" name=\"recip_name$counter\" value=\"".htmlspecialchars(stripslashes($l_recip_name))."\" size=\"$form_field_size\" maxlength=\"50\"></td>
		<td><input type=\"text\" name=\"recip_email$counter\" value=\"".htmlspecialchars(stripslashes($l_recip_email))."\" size=\"$form_field_size\" maxlength=\"50\"></td>
		</tr> \n";
	}
}
if ($cardsgroup_multiplerecp==1 && !eregi("attachment",$card_file))
{
	if (empty($HTTP_POST_VARS['addrecip']))
	{
		$addrecip = 0;
	}else{
		$addrecip = $HTTP_POST_VARS['addrecip'];
	}
	$html_form_recpsenderinfo .= "
			<tr>
				<td colspan=\"1\">$html_formfont
				<b>$MsgTotalRecp:</b> <select name=\"addrecip\" onChange=\"vCardform.action.value='edit';document.vCardform.submit(); return false;\">
				<option value='0' ".cexpr($addrecip==0,'selected','').">1</option>
				<option value='1' ".cexpr($addrecip==1,'selected','').">2</option>
				<option value='2' ".cexpr($addrecip==2,'selected','').">3</option>
				<option value='3' ".cexpr($addrecip==3,'selected','').">4</option>
				<option value='4' ".cexpr($addrecip==4,'selected','').">5</option>
				<option value='5' ".cexpr($addrecip==5,'selected','').">6</option>
				<option value='6' ".cexpr($addrecip==6,'selected','').">7</option>
				<option value='7' ".cexpr($addrecip==7,'selected','').">8</option>
				<option value='8' ".cexpr($addrecip==8,'selected','').">9</option>
				<option value='9' ".cexpr($addrecip==9,'selected','').">10</option>
				<option value='10' ".cexpr($addrecip==10,'selected','').">11</option>
				<option value='11' ".cexpr($addrecip==11,'selected','').">12</option>
				<option value='12' ".cexpr($addrecip==12,'selected','').">13</option>
				<option value='13' ".cexpr($addrecip==13,'selected','').">14</option>
				<option value='14' ".cexpr($addrecip==14,'selected','').">15</option>
				</select>
				</td>
				<td><input type=\"button\" value=\"$Msg_label_addressbook\" onClick=\"javascript:openaddressbook();\"></td>
			</tr>";
}else{
	$html_form_recpsenderinfo .= "
			<tr>
				<td colspan=\"2\"><input type=\"button\" value=\"$Msg_label_addressbook\" onClick=\"javascript:openaddressbook();\"></td>
			</tr>";
}
$html_form_recpsenderinfo .= "
			<tr>
				<td>$html_formfont<b>$MsgYourName :</b></font></td>
				<td>$html_formfont<b>$MsgYourEmail :</b></font></td>
			</tr>
			<tr>
				<td><input type=\"text\" name=\"sender_name\" value=\"$sender_name\" size=\"$form_field_size\" maxlength=\"50\"></td>
				<td><input type=\"text\" name=\"sender_email\" value=\"$sender_email\" size=\"$form_field_size\" maxlength=\"50\"></td>
			</tr>
		</table>";



// ######################### title ######################### //
if ($cardsgroup_heading==1)
{
	$htmlform_title = "$html_formfont<b>$MsgYourTitle :</b></font><br><input type=\"text\" name=\"card_heading\" value=\"".htmlspecialchars(stripslashes($HTTP_POST_VARS['card_heading']))."\" size=\"$form_field_size\" maxlength=\"100\">";
}
	$htmlform_message = "$html_formfont<b>$MsgMessage :</b></font><br><textarea name=\"card_message\" cols=\"$form_areatext_cols\" rows=\"10\" wrap=\"VIRTUAL\">".vdecode(stripslashes($HTTP_POST_VARS['card_message']))."</textarea><a href=\"javascript:winhelp('vcode',430,370)\"><img src=\"img/icon_help.gif\" border=\"0\" align=\"TOP\" alt=\"$MsgOptionsHelp\"></a>";

// ######################### fonts ######################### //
if ($cardsgroup_fontcolor==1)
{
	$htmlform_cardfontcolor = "$html_formfont<b>$MsgFont:</b></font><br>
	<select name=\"card_fontcolor\" size=\"1\">
	<option value=\"black\">$MsgFontColorBlack</option>".
	colorlist($HTTP_POST_VARS['card_fontcolor']).
	"</select> <a href=\"javascript:winhelp('colors',290,290)\"><img src=\"img/icon_help.gif\" border=\"0\" align=\"TOP\" alt=\"$MsgOptionsHelp\"></a>";
}
if ($cardsgroup_fontface==1)
{
	$htmlform_cardfontface = "<select name=\"card_fontface\"><option value=\"\">$MsgNoFontFace</option><option value=\"\"></option>";
	$form_font_list = ereg_replace(', ', ',', $form_font_list);
	$arr_basename = explode(",",$form_font_list);
	foreach ($arr_basename as $key=>$val)
	{
		$htmlform_cardfontface .= "<option value=\"$val\" ".cexpr($HTTP_POST_VARS['card_fontface']==$val,'selected','').">$val";
	}
	$htmlform_cardfontface .= "</select> <a href=\"javascript:winhelp('fonts',550,270)\"><img src=\"img/icon_help.gif\" border=\"0\" align=\"TOP\" alt=\"$MsgOptionsHelp\"></a>";
}
if ($cardsgroup_fontsize==1)
{
	$htmlform_cardfontsize ="<select name=\"card_fontsize\">
	<option value='-1' ".cexpr($HTTP_POST_VARS['card_fontsize']=='-1','selected','').">$MsgFontSizeSmall</option>
	<option value='+1' ".cexpr($HTTP_POST_VARS['card_fontsize']=='+1' || $HTTP_POST_VARS['card_fontsize']=='','selected','').">$MsgFontSizeMedium</option>
	<option value='+3' ".cexpr($HTTP_POST_VARS['card_fontsize']=='+3','selected','').">$MsgFontSizeLarge</option>
	<option value='+4' ".cexpr($HTTP_POST_VARS['card_fontsize']=='+4','selected','').">$MsgFontSizeXLarge</option></select>";
}

// ######################### signature ######################### //
if ($cardsgroup_signature==1)
{
	$htmlform_signature = "$html_formfont<b>$MsgSignature :</b></font><br><input type=\"text\" name=\"card_sig\" value=\"". htmlspecialchars(stripslashes($HTTP_POST_VARS['card_sig'])) ."\" size=\"$form_field_size\" maxlength=\"50\">";
}

// ######################### poem ######################### //
if ($cardsgroup_poem==1)
{
	$htmlform_poem ="$html_formfont<b>$MsgChoosePoem<b> :</b></font><br><select name=\"card_poem\" size=\"1\"><option value=\"\">$MsgNone</option><option value=\"\"></option>";
	$poemlist = $DB_site->query("SELECT poem_id,poem_title FROM vcard_poem WHERE poem_active='1' ORDER BY". cexpr($site_lang_special," poem_id"," poem_title") ."");
	while ($poem = $DB_site->fetch_array($poemlist))
	{
		$htmlform_poem .= "<option value=\"$poem[poem_id]\" ".cexpr($HTTP_POST_VARS['card_poem']==$poem['poem_id'],'selected','').">". stripslashes(htmlspecialchars($poem['poem_title'])) ."</option>\n";
	}
	$DB_site->free_result($poemlist);
	$htmlform_poem .="</select><br><input type=\"button\" value=\"$MsgView\" width=\"100\" onClick=\"viewpoem(this.form)\">";
}

// ######################### advance card send ######################### //
if($cardsgroup_advancedate==1)
{
	$htmlform_advancedate = "$html_formfont<b>$MsgChooseDate :</b></font><br>".get_html_formselector_advdate($user_advance_range,$site_dateformat,"card_tosend",$HTTP_POST_VARS['card_tosend']);
}else{
	$htmlform_advancedate = "<input type=\"hidden\" name=\"card_tosend\" value=\"".date ("Y-m-d")."\">";
}

// ######################### card layout ######################### //
if($cardsgroup_layout==1)
{
	$card_template = $HTTP_POST_VARS['card_template'];
	if(empty($card_template))
	{
		$card_template = 'template01';
	}
	$htmlform_cardlayout = "$html_formfont<b>$MsgChooseLayout:</b></font><br>
		<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
			<tr>
				<td align=\"center\"><input type=\"radio\" name=\"card_template\" value=\"template01\"".cexpr($card_template=='template01'," checked","")."></td>
				<td align=\"center\"><input type=\"radio\" name=\"card_template\" value=\"template02\"".cexpr($card_template=='template02'," checked","")."></td>
				<td align=\"center\"><input type=\"radio\" name=\"card_template\" value=\"template03\"".cexpr($card_template=='template03'," checked","")."></td>
			</tr>
			<tr>
				<td align=\"center\"><img src=\"img/style_01.gif\" border=\"0\" alt=\"\"></td>
				<td align=\"center\"><img src=\"img/style_02.gif\" border=\"0\" alt=\"\"></td>
				<td align=\"center\"><img src=\"img/style_03.gif\" border=\"0\" alt=\"\"></td>
			</tr>
		</table>";
}else{
	$htmlform_cardlayout = "<input type=\"hidden\" name=\"card_template\" 	VALUE=\"$template\">";
}


// ######################### card color ######################### //
if($cardsgroup_cardcolor==1)
{
	$htmlform_cardcolor = "$html_formfont<b>$MsgPostColor:</b></font><br>
	<select name=\"card_color\" size=\"1\"><option value=\"white\">$MsgFontColorWhite</option>".
	colorlist($HTTP_POST_VARS['card_color']).
	"</select> <a href=\"javascript:winhelp('colors',290,280)\"><img src=\"img/icon_help.gif\" border=\"0\" align=\"TOP\" alt=\"$MsgOptionsHelp\"></a>";
}

// ######################### stamp ######################### //
if($cardsgroup_stamp==1)
{
	$htmlform_stamp = "$html_formfont<b>$MsgChooseStamp :</b></font><br><select name=\"card_stamp\" size=\"1\" onChange=\"changestamp(this.form);\"><option value=\"null.gif\">$MsgNone</option><option value=\"null.gif\"></option>";
	$stamplist = $DB_site->query("SELECT * FROM vcard_stamp WHERE stamp_active='1' ORDER BY ". cexpr($site_lang_special,"stamp_file","stamp_name") ."");
	while ($stamp = $DB_site->fetch_array($stamplist))
	{
		$htmlform_stamp .= "<option value=\"$stamp[stamp_file]\" ".cexpr($HTTP_POST_VARS['card_stamp']==$stamp['stamp_file'],'selected','').">". stripslashes(htmlspecialchars($stamp['stamp_name'])) ."</option>";
	}
	$DB_site->free_result($stamplist);
	$htmlform_stamp .= "</select> <a href=\"javascript:winhelp('stamp',300,290)\"><img src=\"img/icon_help.gif\" border=\"0\" align=\"TOP\" alt=\"$MsgOptionsHelp\"></a>";
}


// ######################### pattern ######################### //
if ($cardsgroup_background==1)
{
	$htmlform_pattern ="$html_formfont<b>$MsgPageBackground:</b></font><br><select name=\"card_background\" size=\"1\" onChange=\"changebg(this.form);\"><option value=\"null.gif\">$MsgNone</option><option value=\"null.gif\"></option>";
	$patternlist = $DB_site->query("SELECT * FROM vcard_pattern WHERE pattern_active='1' ORDER BY " . cexpr($site_lang_special,"pattern_file","pattern_name") . "");
	while ($pattern = $DB_site->fetch_array($patternlist))
	{
		// Display list fo pattern
	        $htmlform_pattern .= "<option value=\"$pattern[pattern_file]\" ".cexpr($HTTP_POST_VARS['card_background']==$pattern['pattern_file'],'selected','').">". stripslashes(htmlspecialchars($pattern['pattern_name'])) ."</option>";
	}
	$DB_site->free_result($patternlist);
	$htmlform_pattern .= "</select> <a href=\"javascript:winhelp('background',250,290)\"><img src=\"img/icon_help.gif\" border=\"0\" align=\"TOP\" alt=\"$MsgOptionsHelp\"></a>";
}else{
	$htmlform_pattern .= "<input type=\"hidden\" name=\"card_background\" 	VALUE=\"$site_body_bgimage\">";
}


// ######################### sample ######################### //
if ($cardsgroup_background==1 OR $cardsgroup_stamp==1)
{
	$htmlform_imagepreviewbox = "<img src=\"img/null.gif\" name=\"sample\" width=\"80\" height=\"80\">";
}

// ######################### sound ######################### //
if ($cardsgroup_music==1)
{
	$htmlform_music = "$html_formfont<b>$MsgMusic:</b></font><br><select name=\"card_sound\"><option value=\"\">$MsgNone</option><option value=\"\"></option>";
	$musiclist = $DB_site->query("SELECT * FROM vcard_sound WHERE sound_active='1' ORDER BY sound_order ");
	while ($music = $DB_site->fetch_array($musiclist))
	{
		$htmlform_music .= "<option value=\"$music[sound_file]\" ".cexpr($HTTP_POST_VARS['card_sound']==$music['sound_file'],'selected','').">". stripslashes(htmlspecialchars(" $music[sound_genre] - $music[sound_author] - $music[sound_name]")) ."</option>";
	}
	$DB_site->free_result($musiclist);
	$htmlform_music		.= "</select><br><input type=\"button\" value=\"$MsgPlay\" width=\"100\" onClick=\"playmusic(this.form)\">";
}


// ######################### retrieve notification ######################### //
if ($cardsgroup_notify==1)
{
	$htmlform_cardnotify = "$html_formfont<b>$MsgNotify :</b></font><br><input type=\"radio\" name=\"card_notify\" value=\"1\"".cexpr($HTTP_POST_VARS['card_notify']==1," checked","").">$MsgYes <input type=\"radio\" name=\"card_notify\" value=\"0\"".cexpr($HTTP_POST_VARS['card_notify']==1,""," checked").">$MsgNo ";
}

// ######################### card copy ######################### //
if ($cardsgroup_copy==1)
{
	$htmlform_cardcopy = "$html_formfont<b>$MsgCopyWant :</b></font><br><input type=\"radio\" name=\"card_copy\" value=\"1\"".cexpr($HTTP_POST_VARS['card_copy']==1," checked","").">$MsgYes <input type=\"radio\" name=\"card_copy\" value=\"0\"".cexpr($HTTP_POST_VARS['card_copy']==0," checked","").">$MsgNo ";
}

// ######################### newsletter ######################### //
if ($cardsgroup_newsletter==1)
{
	$htmlform_newsletter = "<input type=\"checkbox\" name=\"receive_newsletter\" value=\"1\" ". cexpr($mailinglist_checked==1 || $HTTP_POST_VARS['receive_newsletter']==1," checked","") .">$html_formfont <b>$MsgNewsletter_join</b> </font>";
}

$html_form_header = "<form action='create.php' method='post' name='vCardform'>";
$html_form_footer = "";
eval("\$html_form_all = \"".get_template('create_form')."\";");
$output_form = $html_form_header.$html_form_all.$html_form_footer;

$headinclude = "\n<script language=\"JavaScript\" type=\"text/javascript\">
<!--
msg_alert_poem = '$MsgWinPoemNote';
msg_alert_music = '$MsgWinMusicNote';
imagedir = '$site_image_url/';
// -->
</script>". $headinclude ."<noscript><b><font face=\"$site_font_face\" size=3 COLOR=\"#FF0000\">$MsgActiveJS</font></b><p></noscript>";

$button = $html_form_hiddenfields."<input type='hidden' name='card_reload' value=''>\n<input type='hidden' name='card_id' value='$card_id'>\n<input type='hidden' name='action' value=''>\n<input type='submit' name='preview' value=\"$MsgPreviewButton\" onClick=\"vCardform.action.value='preview';\">\n  <input type='submit' name='sendnow' value=\"$MsgSendButton\" onClick=\"vCardform.action.value='sendnow';\"></form>";

$form = $output_form;
$card = $output_card;

if(eregi('.html',$card_file))
{
	$htmlcard = str_replace('.html','',$card_file);
	$t2 = new Template($site_image_path);
	$t2->set_file(array("mainpage" => $htmlcard.'.html'));
	// new schema v 2.3
	$t2->set_var("SITE_NAME"	,$site_name);
	$t2->set_var("SITE_URL"		,$site_url);
	$t2->set_var("SENDER_NAME"	,$MsgYourName);
	$t2->set_var("SENDER_EMAIL"	,$MsgYourEmail);
	$t2->set_var("RECIP_NAME"	,$MsgRecpName);
	$t2->set_var("RECIP_EMAIL"	,$MsgRecpEmail);
	$t2->set_var("POST_IMAGE"	,$html_post_image);
	$t2->set_var("POST_FILE"	,$card_file);
	$t2->set_var("POST_WIDTH"	,$card_width);
	$t2->set_var("POST_HEIGHT"	,$card_height);
	$t2->set_var("POST_STAMP"	,$html_card_stamp);
	$t2->set_var("POST_CAPTION"	,$card_caption);
	$t2->set_var("POST_MESSAGE"	,$MsgMessageHere);
	$t2->set_var("POST_SIGNATURE"	,$MsgSignature);
	$t2->set_var("POST_HEADING"	,$MsgYourTitle);
	$t2->set_var("POST_COLOR"	,$card_color);
 	$t2->set_var("POST_FONTFACE"	,$card_fontface);
	$t2->set_var("POST_FONTCOLOR"	,$card_fontcolor);
	$t2->set_var("POST_FONTSIZE"	,$card_fontsize);
	$t2->set_var("POST_POEMTITLE"	,$poem_title);
	$t2->set_var("POST_POEMBODY"	,$poem_text);
	
	$t2->set_var("RATING_BOX"	, $rating_box);
	$t2->set_var("CARDFORM"	,$output_form);
	$t2->set_var("BUTTON"		,$button);
	$t2->set_var("HEADER"		,$header);
	$t2->set_var("FOOTER"		,$footer);
	$t2->set_var("HEADINCLUDE"	,$headinclude);
	$t2->parse("output","mainpage");
	$t2->p("output");

}else{
	eval("make_output(\"".get_template("create")."\");");
}

/* ######################################################################################################### */
/* ###################################### preview page ##################################################### */
/* ######################################################################################################### */
}elseif( $HTTP_POST_VARS['action']=='preview' ) {


$sender_name = $HTTP_POST_VARS['sender_name'];
$sender_email = $HTTP_POST_VARS['sender_email'];
$recip_name = $HTTP_POST_VARS['recip_name'];
$recip_email = $HTTP_POST_VARS['recip_email'];
$card_file = addslashes($HTTP_POST_VARS['card_file']);
$card_id = $HTTP_POST_VARS['card_id'];
$card_cat = $HTTP_POST_VARS['card_cat'];
if ($HTTP_POST_VARS['card_stamp'] =='null.gif' || $user_stamp_allow!=1){
	$card_stamp = $user_stamp_default;
}else{
	$card_stamp = $HTTP_POST_VARS['card_stamp'];
}
//$card_stamp = (!empty($card_stamp))? $card_stamp : $user_stamp_default;
$card_message = $HTTP_POST_VARS['card_message'];
$card_sig = $HTTP_POST_VARS['card_sig'];
$card_poem = $HTTP_POST_VARS['card_poem'];
$card_heading = $HTTP_POST_VARS['card_heading'];
$card_sound = $HTTP_POST_VARS['card_sound'];
if ($HTTP_POST_VARS['card_background'] == 'null.gif'){
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

// card_id
if (!empty($card_id))
{
	$cardinfo = $DB_site->query_first("SELECT * FROM vcard_cards WHERE card_id='".addslashes($card_id)."'");
	$T_card_caption	= stripslashes($cardinfo['card_caption']);
	$card_cat = $cardinfo['card_category'];
	$card_file = $cardinfo['card_imgfile'];
	$card_width	= $cardinfo['card_width'];
	$card_height = $cardinfo['card_height'];
}

// begin errorchecking
///// recip
make_error_page(checkempty($recip_name),$MsgErrorRecpName);
make_error_page(checkempty($recip_email),$MsgErrorRecpEmail);
make_error_page(mailval($recip_email,2),$MsgErrorRecpEmail2);
//// sender
make_error_page(checkempty($sender_name),$MsgErrorSenderName);
make_error_page(checkempty($sender_email),$MsgErrorSenderEmail);
make_error_page(mailval($sender_email,2),$MsgErrorSenderEmail2);
//// message
//make_error_page(checkempty($card_message),$MsgErrorMessage);

////////////////////////////////////////////////////////////////////////////////////
// Special Characters Handling:
////////////////////////////////////////////////////////////////////////////////////
$H_card_message = stripslashes(make_myhtmlentities($card_message)); // to hidden field
$bbcode_card_message = parse_vcode(make_myhtmlentities($card_message));	// VCODE
$bbcode_card_heading = parse_vcode($card_heading);
$bbcode_card_sig = parse_vcode($card_sig);


$bbcode_card_message = eregi_replace('<br />', '<br>', $bbcode_card_message);
$card_heading = stripslashes(make_myhtmlentities($card_heading));
$card_sig = stripslashes(make_myhtmlentities($card_sig));
$recip_name = stripslashes(make_myhtmlentities($recip_name));
$sender_name = stripslashes(make_myhtmlentities($sender_name));

$H_card_message = nl2br($H_card_message); 			// new line to <br> tag
$H_card_message = eregi_replace("\n",' ',$H_card_message); 	// remove nl to form field
$H_card_message = eregi_replace("\r",' ',$H_card_message); 	// remove nl to form field
$H_card_message = eregi_replace('<br />','<br>',$H_card_message); 	// remove nl to form field

//////////////////////////////////////////////////
// COOKIE FUNCTIONS: SENDER                     //
//////////////////////////////////////////////////
@setcookie("vCard_sendername", $sender_name, time()+36000, "/");
@setcookie("vCard_senderemail", $sender_email, time()+36000, "/");
@setcookie("vCard_recpname", "", time()+1, "/");
@setcookie("vCard_recpemail", "", time()+1, "/");

$html_post_image = get_html_image($site_image_url,$card_file,$card_width,$card_height);
$html_card_sound = get_html_music($site_music_url,$card_sound);
$html_card_stamp = get_html_stamp($site_image_url,$card_stamp,$site_url,$site_name);

$poem_info	= $DB_site->query_first("SELECT * FROM vcard_poem WHERE poem_id='".addslashes($card_poem)."'");
if($poem_info['poem_style']==1)
{
	$poem_title = stripslashes($poem_info['poem_title']);
	$poem_text 	= stripslashes($poem_info['poem_text']);
}else{
	$poem_title = stripslashes(htmlspecialchars($poem_info['poem_title']));
	$poem_text 	= nl2br(stripslashes(htmlspecialchars($poem_info['poem_text'])));
	$poem_text 	= eregi_replace("<br />", "<br>", $poem_text); 
}
if(!empty($card_template))
{
	$t = new Template("./templates");
	$t->set_file(array("mainpage" => "$card_template.ihtml"));

	// new schema v2.3
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
	$t->set_var("POST_SIGNATURE"	,$bbcode_card_sig);
	$t->set_var("POST_HEADING"	,$bbcode_card_heading);
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
	$output_card = $t->getcard("output");
}

$output_form = 
get_html_hiddenfield("sender_name",$sender_name,0) . 
get_html_hiddenfield("sender_email",$sender_email,0) . 
get_html_hiddenfield("recip_name",$recip_name,0) . 
get_html_hiddenfield("recip_email",$recip_email,0) . 
get_html_hiddenfield("card_file",$card_file,0) . 
get_html_hiddenfield("card_id",$card_id,0) . 
get_html_hiddenfield("card_cat",$card_cat,0) . 
get_html_hiddenfield("card_stamp",$card_stamp,0) . 
get_html_hiddenfield("card_message",$H_card_message,0) . 
get_html_hiddenfield("card_sig",$card_sig,0) . 
get_html_hiddenfield("card_poem",$card_poem,0) . 
get_html_hiddenfield("card_heading",$card_heading,0) . 
get_html_hiddenfield("card_sound",$card_sound,0) . 
get_html_hiddenfield("card_background",$card_background,0) . 
get_html_hiddenfield("card_color",$card_color,0) . 
get_html_hiddenfield("card_template",$card_template,0) . 
get_html_hiddenfield("card_fontface",$card_fontface,0) . 
get_html_hiddenfield("card_fontcolor",$card_fontcolor,0) . 
get_html_hiddenfield("card_fontsize",$card_fontsize,0) . 
get_html_hiddenfield("card_notify",$card_notify,0) . 
get_html_hiddenfield("card_copy",$card_copy,0) . 
get_html_hiddenfield("card_tosend",$card_tosend,0) . 
get_html_hiddenfield("attach_id",$attach_id,0) . 
get_html_hiddenfield("receive_newsletter",$receive_newsletter,0);
$extra_recip ='';
if (!mailval($HTTP_POST_VARS['recip_email1'],2)) {
	$extra_recip++;
	$output_form.= get_html_hiddenfield("recip_name$extra_recip",$HTTP_POST_VARS['recip_name1'],0);
	$output_form.= get_html_hiddenfield("recip_email$extra_recip",$HTTP_POST_VARS['recip_email1'],0);
}
if (!mailval($HTTP_POST_VARS['recip_email2'],2)) {
	$extra_recip++;
	$output_form.= get_html_hiddenfield("recip_name$extra_recip",$HTTP_POST_VARS['recip_name2'],0);
	$output_form.= get_html_hiddenfield("recip_email$extra_recip",$HTTP_POST_VARS['recip_email2'],0);
}
if (!mailval($HTTP_POST_VARS['recip_email3'],2)) {
	$extra_recip++;
	$output_form.= get_html_hiddenfield("recip_name$extra_recip",$HTTP_POST_VARS['recip_name3'],0);
	$output_form.= get_html_hiddenfield("recip_email$extra_recip",$HTTP_POST_VARS['recip_email3'],0);
}
if (!mailval($HTTP_POST_VARS['recip_email4'],2)) {
	$extra_recip++;
	$output_form.= get_html_hiddenfield("recip_name$extra_recip",$HTTP_POST_VARS['recip_name4'],0);
	$output_form.= get_html_hiddenfield("recip_email$extra_recip",$HTTP_POST_VARS['recip_email4'],0);
}
if (!mailval($HTTP_POST_VARS['recip_email5'],2)){
	$extra_recip++;
	$output_form.= get_html_hiddenfield("recip_name$extra_recip",$HTTP_POST_VARS['recip_name5'],0);
	$output_form.= get_html_hiddenfield("recip_email$extra_recip",$HTTP_POST_VARS['recip_email5'],0);
}
if (!mailval($HTTP_POST_VARS['recip_email6'],2)){
	$extra_recip++;
	$output_form.= get_html_hiddenfield("recip_name$extra_recip",$HTTP_POST_VARS['recip_name6'],0);
	$output_form.= get_html_hiddenfield("recip_email$extra_recip",$HTTP_POST_VARS['recip_email6'],0);
}
if (!mailval($HTTP_POST_VARS['recip_email7'],2)){
	$extra_recip++;
	$output_form.= get_html_hiddenfield("recip_name$extra_recip",$HTTP_POST_VARS['recip_name7'],0);
	$output_form.= get_html_hiddenfield("recip_email$extra_recip",$HTTP_POST_VARS['recip_email7'],0);
}
if (!mailval($HTTP_POST_VARS['recip_email8'],2)){
	$extra_recip++;
	$output_form.= get_html_hiddenfield("recip_name$extra_recip",$HTTP_POST_VARS['recip_name8'],0);
	$output_form.= get_html_hiddenfield("recip_email$extra_recip",$HTTP_POST_VARS['recip_email8'],0);
}
if (!mailval($HTTP_POST_VARS['recip_email9'],2)){
	$extra_recip++;
	$output_form.= get_html_hiddenfield("recip_name$extra_recip",$HTTP_POST_VARS['recip_name9'],0);
	$output_form.= get_html_hiddenfield("recip_email$extra_recip",$HTTP_POST_VARS['recip_email9'],0);
}
if (!mailval($HTTP_POST_VARS['recip_email10'],2)){
	$extra_recip++;
	$output_form.= get_html_hiddenfield("recip_name$extra_recip",$HTTP_POST_VARS['recip_name10'],0);
	$output_form.= get_html_hiddenfield("recip_email$extra_recip",$HTTP_POST_VARS['recip_email10'],0);
}
if (!mailval($HTTP_POST_VARS['recip_email11'],2)){
	$extra_recip++;
	$output_form.= get_html_hiddenfield("recip_name$extra_recip",$HTTP_POST_VARS['recip_name11'],0);
	$output_form.= get_html_hiddenfield("recip_email$extra_recip",$HTTP_POST_VARS['recip_email11'],0);
}
if (!mailval($HTTP_POST_VARS['recip_email12'],2)){
	$extra_recip++;
	$output_form.= get_html_hiddenfield("recip_name$extra_recip",$HTTP_POST_VARS['recip_name12'],0);
	$output_form.= get_html_hiddenfield("recip_email$extra_recip",$HTTP_POST_VARS['recip_email12'],0);
}
if (!mailval($HTTP_POST_VARS['recip_email13'],2)){
	$extra_recip++;
	$output_form.= get_html_hiddenfield("recip_name$extra_recip",$HTTP_POST_VARS['recip_name13'],0);
	$output_form.= get_html_hiddenfield("recip_email$extra_recip",$HTTP_POST_VARS['recip_email13'],0);
}
if (!mailval($HTTP_POST_VARS['recip_email14'],2)){
	$extra_recip++;
	$output_form.= get_html_hiddenfield("recip_name$extra_recip",$HTTP_POST_VARS['recip_name14'],0);
	$output_form.= get_html_hiddenfield("recip_email$extra_recip",$HTTP_POST_VARS['recip_email14'],0);
}
if (!mailval($HTTP_POST_VARS['recip_email15'],2)){
	$extra_recip++;
	$output_form.= get_html_hiddenfield("recip_name$extra_recip",$HTTP_POST_VARS['recip_name15'],0);
	$output_form.= get_html_hiddenfield("recip_email$extra_recip",$HTTP_POST_VARS['recip_email15'],0);
}

$output_form.= get_html_hiddenfield("addrecip",$extra_recip).get_html_hiddenfield('action','');

$buttonsendnow = "<form action='send.php' method='post' name='vCardform'>$output_form <input type=\"submit\"  name=\"sendnow\" value=\"$MsgSendButton\" width=\"200\" onClick=\"vCardform.action.value='sendnow';\"></form>";
$buttonbackedit = "<form action='create.php' method='post' name='vCardform'>$output_form <input type=\"submit\" name=\"editcard\" value=\"$MsgBackEditButton\" width=\"200\" onClick=\"vCardform.action.value='preview';\"></form>";
$button = "$buttonsendnow $buttonbackedit ";

$card			= $output_card;
$htmlbody 		= html_body($site_image_url,$card_background,$site_body_bgcolor,$site_body_text,$site_body_link,$site_body_vlink,$site_body_alink,$site_body_marginwidth,$site_body_marginheight);
if(eregi('.html',$card_file))
{
	$htmlcard = str_replace('.html','',$card_file);
	$t2 = new Template($site_image_path);
	$t2->set_file(array("mainpage" => $htmlcard.".html"));

	// new schema v 2.3
	$t2->set_var("SITE_NAME"	,$site_name);
	$t2->set_var("SITE_URL"		,$site_url);
	$t2->set_var("SITE_FONTFACE"	,$site_font_face);
	$t2->set_var("SENDER_NAME"	,$sender_name);
	$t2->set_var("SENDER_EMAIL"	,$sender_email);
	$t2->set_var("RECIP_NAME"	,$recip_name);
	$t2->set_var("RECIP_EMAIL"	,$recip_email);
	$t2->set_var("POST_IMAGE"	,$html_post_image);
	$t2->set_var("POST_FILE"	,$card_file);
	$t2->set_var("POST_WIDTH"	,$card_width);
	$t2->set_var("POST_HEIGHT"	,$card_height);
	$t2->set_var("POST_CAPTION"	,$card_caption);
	$t2->set_var("POST_HEADING"	,$bbcode_card_heading);
	$t2->set_var("POST_MESSAGE"	,$bbcode_card_message);
	$t2->set_var("POST_SIGNATURE"	,$bbcode_card_sig);
	$t2->set_var("POST_STAMP"	,$html_card_stamp);
	$t2->set_var("POST_SOUND"	,$html_card_sound);
	$t2->set_var("POST_COLOR"	,$card_color);
 	$t2->set_var("POST_FONTFACE"	,$card_fontface);
	$t2->set_var("POST_FONTCOLOR"	,$card_fontcolor);
	$t2->set_var("POST_FONTSIZE"	,$card_fontsize);
	$t2->set_var("POST_POEMTITLE"	,$poem_title);
	$t2->set_var("POST_POEMBODY"	,$poem_text);
	
	$t2->set_var("CARDFORM"	,'');
	$t2->set_var("BUTTON"		,$button);
	$t2->set_var("HEADER"		,$header);
	$t2->set_var("FOOTER"		,$footer);
	$t2->set_var("HEADINCLUDE"	,$headinclude);
	$t2->parse("output","mainpage");
	$t2->p("output");
}else{
	eval("make_output(\"".get_template("preview")."\");");
}

/* ######################################################################################################### */
/* ###################################### send card page ################################################### */
/* ######################################################################################################### */
}elseif($HTTP_POST_VARS['action']=='sendnow'){
// If user want send postcard now

	$sender_name = $HTTP_POST_VARS['sender_name'];
	$sender_email = $HTTP_POST_VARS['sender_email'];
	$recip_name = $HTTP_POST_VARS['recip_name'];
	$recip_email = $HTTP_POST_VARS['recip_email'];
	$card_file = addslashes($HTTP_POST_VARS['card_file']);
	$card_id = $HTTP_POST_VARS['card_id'];
	$card_cat = $HTTP_POST_VARS['card_cat'];
	if ($HTTP_POST_VARS['card_stamp'] == 'null.gif' || $user_stamp_allow!=1){
	 $card_stamp = $user_stamp_default;
	}else{
	 $card_stamp = $HTTP_POST_VARS['card_stamp'];
	}
	$card_stamp = (!empty($card_stamp))? $card_stamp : $user_stamp_default;
	$card_message = $HTTP_POST_VARS['card_message'];
	$card_sig = $HTTP_POST_VARS['card_sig'];
	$card_poem = $HTTP_POST_VARS['card_poem'];
	$card_heading = $HTTP_POST_VARS['card_heading'];
	$card_sound = $HTTP_POST_VARS['card_sound'];
	if ($HTTP_POST_VARS['card_background'] == "null.gif"){
	  $card_background ="";
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
	
	//// recipients
	make_error_page(checkempty($recip_name),$MsgErrorRecpName);
	make_error_page(checkempty($recip_email),$MsgErrorRecpEmail);
	make_error_page(mailval($recip_email,2),$MsgErrorRecpEmail2);
	//// sender
	make_error_page(checkempty($sender_name),$MsgErrorSenderName);
	make_error_page(checkempty($sender_email),$MsgErrorSenderEmail);
	make_error_page(mailval($sender_email,2),$MsgErrorSenderEmail2);
	//// message
	//make_error_page(checkempty($card_message),$MsgErrorMessage);
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
	external_filelog($card_file);
	// query to stat
	$result 	= $DB_site->query_first("SELECT * FROM vcard_cards WHERE card_imgfile='".addslashes($card_file)."'");
	$card_imgthm	= $result['card_thmfile'];
	
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

	// Card copy
	if ($card_copy == 1)
	{
		save_data_cardcopy($recip_emails,$recip_names,$sender_name,$sender_email,$card_file,$card_id,$card_imgthm,$card_cat,$card_stamp,$card_message,$card_sig,$card_heading,$card_poem,$card_sound,$card_background,$card_color,$card_template,$card_fontface,$card_fontcolor,$card_fontsize,$card_notify,$card_tosend);
	}
	
	make_redirectpage("done.php");
}
if($debug==1){ $timer->end_time(); $timer->elapsed_time();}
$DB_site->close();
if($use_gzip == 1) {ob_end_flush(); }
?>