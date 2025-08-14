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
$templatesused = 'helppage,helpwindow';
require('./lib.inc.php');

function localhtml_table($link,$title,$colspan="2") {
	global $site_font_face,$site_body_bgcolor,$site_body_text;
	
	return "\n<table  cellpadding=\"4\" border=\"0\" cellspacing=\"1\" width=\"100%\" align=\"center\">\n
	<tr><td colspan=\"$colspan\"><a name=\"$link\"><font face=\"$site_font_face\"size=\"2\"><b>$title</b></font></a></td></tr>";
}
function localhtml_tablefooter() {

	return '</table><p>';
}

$html = '';
if ( !empty($HTTP_GET_VARS['topic']) ) { $topic = addslashes($HTTP_GET_VARS['topic']); }else{ $topic='';}
if ( !empty($HTTP_GET_VARS['type']) )  { $type = addslashes($HTTP_GET_VARS['type']);     }else{ $type='';}

if (empty($topic))
{
	$html .= localhtml_table("help","$MsgHelp",1);
	$html .= "
	<tr>
		<td>
		<ul>
			<li><a href='help.php?topic=vcode&type=page'>$MsgWinvCode</a>
			<li><a href='help.php?topic=colors&type=page'>$MsgWinColors</a>
			<li><a href='help.php?topic=background&type=page'>$MsgWinBackground</a>
			<li><a href='help.php?topic=stamp&type=page'>$MsgWinStamp</a>
			<li><a href='help.php?topic=fonts&type=page'>$MsgWinFonts</a>
			<li><a href='help.php?topic=poemlist&type=page'>$MsgWinPoem</a>
			<li><a href='help.php?topic=abook&type=page'>$MsgABook_tit_generaltitle</a>
			
		</ul>
		</td>
	</tr>";
	$html .= localhtml_tablefooter();
}
elseif ($topic=='vcode')
{
	$html .= localhtml_table("vcode","$MsgWinvCode",1);
	$html .="
		<tr>
		<td>
		".make_html_startfont(2)." <b>$MsgWinEmoticons</b><center>
		$MsgWinEmoticonsNote
		<table border=\"0\" cellspacing=\"2\" cellpadding=\"1\" BGCOLOR=\"#000000\">
		  <tr><td>
		     <table width=\"170\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\" BGCOLOR=\"#FFFFFF\">
		        <tr><td align=\"center\"><font face=\"Courier New,'Courier New',Courier,monospace\"><b>:-)</b></font></td><td align=\"center\">=</td><td align=\"center\"><img src=\"img/e/smiley.gif\" alt=\":-)\" width=16 height=16 border=0></td></tr>
		        <tr><td align=\"center\"><font face=\"Courier New,'Courier New',Courier,monospace\"><b>:-(</b></font></td><td align=\"center\">=</td><td align=\"center\"><img src=\"img/e/sad.gif\" alt=\":-(\" width=16 height=16 border=0></td></tr>
		        <tr><td align=\"center\"><font face=\"Courier New,'Courier New',Courier,monospace\"><b>:-P</b></font></td><td align=\"center\">=</td><td align=\"center\"><img src=\"img/e/tong.gif\" alt=\":-P\" width=16 height=16 border=0></td></tr>
		        <tr><td align=\"center\"><font face=\"Courier New,'Courier New',Courier,monospace\"><b>8-)</b></font></td><td align=\"center\">=</td><td align=\"center\"><img src=\"img/e/cool.gif\" alt=\"8-)\" width=16 height=16 border=0></td></tr>
		        <tr><td align=\"center\"><font face=\"Courier New,'Courier New',Courier,monospace\"><b>8-P</b></font></td><td align=\"center\">=</td><td align=\"center\"><img src=\"img/e/cooltong.gif\" alt=\"8-P\" width=16 height=16 border=0></td></tr>
		        <tr><td align=\"center\"><font face=\"Courier New,'Courier New',Courier,monospace\"><b>8-O</b></font></td><td align=\"center\">=</td><td align=\"center\"><img src=\"img/e/coolmouth.gif\" alt=\"8-O\" width=16 height=16 border=0></td></tr>
		        <tr><td align=\"center\"><font face=\"Courier New,'Courier New',Courier,monospace\"><b>8-(</b></font></td><td align=\"center\">=</td><td align=\"center\"><img src=\"img/e/coolsad.gif\" alt=\"8-(\" width=16 height=16 border=0></td></tr>
		        <tr><td align=\"center\"><font face=\"Courier New,'Courier New',Courier,monospace\"><b>%-)</b></font></td><td align=\"center\">=</td><td align=\"center\"><img src=\"img/e/dazed.gif\" alt=\"%-)\" width=16 height=16 border=0></td></tr>
		     </table>
		  </td><td>
		     <table width=\"170\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\" BGCOLOR=\"#FFFFFF\">
		        <tr><td align=\"center\"><font face=\"Courier New,'Courier New',Courier,monospace\"><b>%-O</b></font></td><td align=\"center\">=</td><td align=\"center\"><img src=\"img/e/dazedmouth.gif\" alt=\"%-O\" width=16 height=16 border=0></td></tr>
		        <tr><td align=\"center\"><font face=\"Courier New,'Courier New',Courier,monospace\"><b>%-(</b></font></td><td align=\"center\">=</td><td align=\"center\"><img src=\"img/e/dazedsad.gif\" alt=\"%-(\" width=16 height=16 border=0></td></tr>
		        <tr><td align=\"center\"><font face=\"Courier New,'Courier New',Courier,monospace\"><b>%-P</b></font></td><td align=\"center\">=</td><td align=\"center\"><img src=\"img/e/dazedtong.gif\" alt=\"%-P\" width=16 height=16 border=0></td></tr>
		        <tr><td align=\"center\"><font face=\"Courier New,'Courier New',Courier,monospace\"><b>:-O</b></font></td><td align=\"center\">=</td><td align=\"center\"><img src=\"img/e/mouth.gif\" alt=\":-O\" width=16 height=16 border=0></td></tr>
		        <tr><td align=\"center\"><font face=\"Courier New,'Courier New',Courier,monospace\"><b>;-)</b></font></td><td align=\"center\">=</td><td align=\"center\"><img src=\"img/e/wink.gif\" alt=\";-)\" width=16 height=16 border=0></td></tr>
		        <tr><td align=\"center\"><font face=\"Courier New,'Courier New',Courier,monospace\"><b>;-O</b></font></td><td align=\"center\">=</td><td align=\"center\"><img src=\"img/e/winkmouth.gif\" alt=\";-O\" width=16 height=16 border=0></td></tr>
		        <tr><td align=\"center\"><font face=\"Courier New,'Courier New',Courier,monospace\"><b>;-(</b></font></td><td align=\"center\">=</td><td align=\"center\"><img src=\"img/e/winksad.gif\" alt=\";-(\" width=16 height=16 border=0></td></tr>
		        <tr><td align=\"center\"><font face=\"Courier New,'Courier New',Courier,monospace\"><b>;-P</b></font></td><td align=\"center\">=</td><td align=\"center\"><img src=\"img/e/winktong.gif\" alt=\";-P\" width=16 height=16 border=0></td></tr>
		     </table>
		</td></tr></table>
		</td>
	</tr>
	<tr><td>
	<tr><td>$MsgWinEmoticonsNoteFotter<p><div align=\"center\"><b><font face=\"'Courier New',Courier,monospace\"> :<font color=\"#FF0000\">-</font>) ->> :)</font></b></div></td></tr>
	";
 	$html .= localhtml_tablefooter();
 	$html .= localhtml_table("textcode","$MsgWinTextCode",1);
	$html .= "
	<tr>
		<td>
			<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\" BGCOLOR=\"#FFFFFF\">
			   <tr><td align=\"center\"><font face=\"Verdana\" size=\"1\"><b>[url]</b>http://www.test.com<b>[/url]</b></font></td><td align=\"center\">=</td><td align=\"center\"><font face=\"Verdana\" size=\"1\"><a href=\"http://www.test.com\" target=\"_blank\">http://www.test.com</a></td></tr>
			   <tr><td align=\"center\"><font face=\"Verdana\" size=\"1\"><b>[email]</b>mail@mail.com<b>[/email]</b></font></td><td align=\"center\">=</td><td align=\"center\"><font face=\"Verdana\" size=\"1\"><a href=\"mailto:mail@mail.com\">mail@mail.com</a></td></tr>
			   <tr><td align=\"center\"><font face=\"Verdana\" size=\"1\"><b>[b]</b></font><font face=\"$site_font_face\" size=\"1\">$MsgSomeText<font face=\"Verdana\" size=\"1\"><b>[/b]</b></font></td><td align=\"center\">=</td><td align=\"center\"><font face=\"$site_font_face\" size=\"1\"><b>$MsgSomeText</b></td></tr>
			   <tr><td align=\"center\"><font face=\"Verdana\" size=\"1\"><b>[i]</b></font><font face=\"$site_font_face\" size=\"1\">$MsgSomeText<font face=\"Verdana\" size=\"1\"><b>[/i]</b></font></td><td align=\"center\">=</td><td align=\"center\"><font face=\"$site_font_face\" size=\"1\"><i>$MsgSomeText</i></td></tr>
			   <tr><td align=\"center\"><font face=\"Verdana\" size=\"1\"><b>[u]</b></font><font face=\"$site_font_face\" size=\"1\">$MsgSomeText<font face=\"Verdana\" size=\"1\"><b>[/u]</b></font></td><td align=\"center\">=</td><td align=\"center\"><font face=\"$site_font_face\" size=\"1\"><u>$MsgSomeText</u></td></tr>
			</table>
		</td>
	</tr>
	";
	$html .= localhtml_tablefooter();
}
elseif ($topic=='colors')
{
 	$html .= localhtml_table("colors","$MsgWinColors",2);
	$html .= "<tr><td>".make_html_startfont(2)." $MsgWinName</font></td><td>".make_html_startfont(2)."$MsgWinSample</td></tr>	";
	$colors_list = array ("Aliceblue"=>"#F0F8FF","Antiquewhite"=>"#FAEBD7","Aqua"=>"#00FFFF","Aquamarine"=>"#7FFFD4","Azure"=>"#F0FFFF","Beige"=>"#F5F5DC","Bisque"=>"#FFE4C4","Black"=>"#000000","Blanchedalmond"=>"#FFEBCD","Blue"=>"#0000FF","Blueviolet"=>"#8A2BE2","Brown"=>"#A52A2A","Burlywood"=>"#DEB887","Cadetblue"=>"#5F9EA0","Chartreuse"=>"#7FFF00","Chocolate"=>"#D2691E","Coral"=>"#FF7F50","Cornflowerblue"=>"#6495ED","Cornsilk"=>"#FFF8DC","Crimson"=>"#DC143C","Cyan"=>"#00FFFF","Darkblue"=>"#00008B","Darkcyan"=>"#008B8B","Darkgoldenrod"=>"#B8860B","Darkgray"=>"#A9A9A9","Darkgreen"=>"#006400","Darkkhaki"=>"#BDB76B","Darkmagenta"=>"#8B008B","Darkolivegreen"=>"#556B2F","Darkorange"=>"#FF8C00","Darkorchid"=>"#9932CC","Darkred"=>"#8B0000","Darksalmon"=>"#E9967A","Darkseagreen"=>"#8FBC8F","Darkslateblue"=>"#483D8B","Darkslategray"=>"#2F4F4F","Darkturquoise"=>"#00CED1","Darkviolet"=>"#9400D3","deeppink"=>"#FF1493","Deepskyblue"=>"#00BFFF","Dimgray"=>"#696969","Dodgerblue"=>"#1E90FF","Firebrick"=>"#B22222","Floralwhite"=>"#FFFAF0","Forestgreen"=>"#228B22","Fuchsia"=>"#FF00FF","Gainsboro"=>"#DCDCDC","Ghostwhite"=>"#F8F8FF","Gold"=>"#FFD700","Goldenrod"=>"#DAA520","Gray"=>"#808080","Green"=>"#008000","Greenyellow"=>"#ADFF2F","Honeydew"=>"#F0FFF0","Hotpink"=>"#FF69B4","Indianred"=>"#CD5C5C","Indigo"=>"#4B0082","Ivory"=>"#FFFFF0","Khaki"=>"#F0E68C","Lavender"=>"#E6E6FA","Lavenderblush"=>"#FFF0F5","Lawngreen"=>"#7CFC00","Lemonchiffon"=>"#FFFACD","Lightblue"=>"#ADD8E6","Lightcoral"=>"#F08080","Lightcyan"=>"#E0FFFF","Lightgoldenrodyellow"=>"#FAFAD2","Lightgreen"=>"#90EE90","Lightgrey"=>"#D3D3D3","Lightpink"=>"#FFB6C1","Lightsalmon"=>"#FFA07A","Lightseagreen"=>"#20B2AA","Lightskyblue"=>"#87CEFA","Lightslategray"=>"#778899","Lightsteelblue"=>"#B0C4DE","Lightyellow"=>"#FFFFE0","Lime"=>"#00FF00","Limegreen"=>"#32CD32","Linen"=>"#FAF0E6","Magenta"=>"#FF00FF","Maroon"=>"#800000","Mediumauqamarine"=>"#66CDAA","Mediumblue"=>"#0000CD","Mediumorchid"=>"#BA55D3","Mediumpurple"=>"#9370D8","Mediumseagreen"=>"#3CB371","Mediumslateblue"=>"#7B68EE","Mediumspringgreen"=>"#00FA9A","Mediumturquoise"=>"#48D1CC","Mediumvioletred"=>"#C71585","Midnightblue"=>"#191970","Mintcream"=>"#F5FFFA","Mistyrose"=>"#FFE4E1","Moccasin"=>"#FFE4B5","Navajowhite"=>"#FFDEAD","Navy"=>"#000080","Oldlace"=>"#FDF5E6","Olive"=>"#808000","Olivedrab"=>"#688E23","Orange"=>"#FFA500","Orangered"=>"#FF4500","Orchid"=>"#DA70D6","Palegoldenrod"=>"#EEE8AA","Palegreen"=>"#98FB98","Paleturquoise"=>"#AFEEEE","Palevioletred"=>"#D87093","Papayawhip"=>"#FFEFD5","Peachpuff"=>"#FFDAB9","Peru"=>"#CD853F","Pink"=>"#FFC0CB","Plum"=>"#DDA0DD","Powderblue"=>"#B0E0E6","Purple"=>"#800080","Red"=>"#FF0000","Rosybrown"=>"#BC8F8F","Royalblue"=>"#4169E1","Saddlebrown"=>"#8B4513","Salmon"=>"#FA8072","Sandybrown"=>"#F4A460","Seagreen"=>"#2E8B57","Seashell"=>"#FFF5EE","Sienna"=>"#A0522D","Silver"=>"#C0C0C0","Skyblue"=>"#87CEEB","Slateblue"=>"#6A5ACD","Slategray"=>"#708090","Snow"=>"#FFFAFA","Springgreen"=>"#00FF7F","Steelblue"=>"#4682B4","Tan"=>"#D2B48C","Teal"=>"#008080","Thistle"=>"#D8BFD8","Tomato"=>"#FF6347","Turquoise"=>"#40E0D0","Violet"=>"#EE82EE","Wheat"=>"#F5DEB3","White"=>"#FFFFFF","Whitesmoke"=>"#F5F5F5","Yellow"=>"#FFFF00","YellowGreen"=>"#9ACD32");
	while (list($key, $val) = each($colors_list))
	{
		$html .= "<tr><td><b> $key </td><td bgcolor=\"$val\"> &nbsp; </td></tr>";
		//  echo "$key - $val\n";
	}
	$html .= localhtml_tablefooter();
}
elseif ($topic=='background')
{
	$html .= localhtml_table("poem","$MsgWinBackground",2);
	$html .= "<tr><td width=\"90\">".make_html_startfont(2)."$MsgWinSample</font></td><td width=\"160\">".make_html_startfont(2)."$MsgWinName</font></td></tr>";
	$patternlist = $DB_site->query("SELECT * FROM vcard_pattern WHERE pattern_active='1' ORDER BY ".cexpr($site_lang_special,"pattern_file","pattern_name") ."");
	while ($pattern = $DB_site->fetch_array($patternlist))
	{
		$patternfile = $pattern["pattern_file"];
	        $patternname = stripslashes(htmlspecialchars($pattern['pattern_name']));
		// Display list fo pattern
		$html .=  "<tr><td>	<img src=\"$site_image_url/$patternfile\" width=60 height=60 border=1 align=\"MIDDLE\"> </td><td> <b>$patternname </b> </td></tr>\n";
	}
	$DB_site->free_result($patternlist);
	$html .= localhtml_tablefooter();

}
elseif ($topic == 'poem')
{

	$poem_info = $DB_site->query_first("SELECT * FROM vcard_poem WHERE poem_id='".addslashes($HTTP_GET_VARS['poem_id'])."'");
	if($poem_info['poem_style']==1)
	{
		$poem_title 	= stripslashes($poem_info['poem_title']);
		$poem_text 	= stripslashes($poem_info['poem_text']);
	}else{
		$poem_title 	= stripslashes(htmlspecialchars($poem_info['poem_title']));
		$poem_text 	= nl2br(stripslashes(htmlspecialchars($poem_info['poem_text'])));
		$poem_text 	= eregi_replace("<br />", "<br>", $poem_text); 
	}
	$html .= localhtml_table("poem","&nbsp;&nbsp;&nbsp;&nbsp; $poem_title ",1);
	$html .=  "<tr><td><blockquote>$poem_text</blockquote></td></tr>";
	$html .= localhtml_tablefooter();

}
elseif ($topic =='poemlist')
{
	$poems = $DB_site->query("SELECT poem_id,poem_title FROM vcard_poem ORDER BY poem_title DESC");
	$html .= localhtml_table("poem","&nbsp;&nbsp;&nbsp;&nbsp; $poem_title ",1);
	while ($poem = $DB_site->fetch_array($poems) )
	{
		$poem['title'] = stripslashes(htmlspecialchars($poem['title']));
		//$poem_text = stripslashes(htmlspecialchars($poem_text));
		//$poem_text = nl2br($poem_text);
		$html .=  "<tr><td><a href=\"help.php?topic=poem&poem_id=$poem[poem_id]&type=page\"><b>$poem[poem_title]</b></a></td></tr>";
	}
	$html .= localhtml_tablefooter();
}
elseif($topic == 'stamp')
{
	$html .= localhtml_table("stamp","$MsgWinStamp",2);
	$html .="<tr><td width=\"90\">".make_html_startfont(2)."$MsgWinSample</font></td><td width=\"160\">".make_html_startfont(2)."$MsgWinName</font></td></tr>";
	$stamplist = $DB_site->query("SELECT * FROM vcard_stamp WHERE stamp_active='1' ORDER BY ". cexpr($site_lang_special,"stamp_file","stamp_name")."");
	while ($stamp = $DB_site->fetch_array($stamplist))
	{
		$stampfile = $stamp['stamp_file'];
		$stampname = stripslashes(htmlspecialchars($stamp['stamp_name']));
		$html .= "<tr><td>	<img src=\"$site_image_url/$stampfile\" border=0 align=\"MIDDLE\"> </td><td><font color=\"$site_body_text\"> <b>$stampname </b> </td></tr>\n";
	}
	$DB_site->free_result($stamplist);
	$html .= localhtml_tablefooter();
}
elseif ($topic == 'fonts')
{
	$html .= localhtml_table("fonts","$MsgWinFonts",2);
	$html .= "
	<tr>
		<td width=\"120\">".make_html_startfont(2)." $MsgWinName</font></td>
		<td width=\"120\">".make_html_startfont(2)." $MsgWinSample</font></td>
	</tr>
	";
	// Font List
	$form_font_list = ereg_replace(', ', ',', $form_font_list);
	$arr_basename = explode(",",$form_font_list);
	sort($arr_basename);
	reset($arr_basename);
	while (list($key, $val) = each($arr_basename))
	{
		$html .= "<tr><td><font face=\"$val\" color=\"$site_body_text\" size=\"2\">$val </font></td><td><font face=\"$val\" color=\"$site_body_text\" size=\"2\">$MsgWinSampleString</td></tr>\n";
	}
	$html .= "<tr><td colspan=\"2\">$MsgWinFontsNote</td></tr>";
	$html .= localhtml_tablefooter();
}
elseif ($topic == 'music')
{
	
	$html .= "<br><font face=\"$site_font_face\" size=\"2\"><div align=\"center\">$MsgWinMusicNote2</div></font><br clear=\"all\">";
	$html .= "<p align=\"center\">\n";
	$html .= get_html_music($site_music_url,$HTTP_GET_VARS['song']);
	$html .= "</p>\n";
	$html .= "<script language=\"JavaScript\"> window.focus() </script>";
}
elseif ($topic == 'abook')
{
	
	$html .= "<br><font face=\"$site_font_face\" size=\"2\"><div align=\"center\">$MsgABook_tit_generaltitle</div></font><br clear=\"all\">";
	$html .= "<blockquote><p>\n";
	$html .= $MsgABook_helppage;
	$html .= "</p></blockquote>\n";
}
else
{
	$html .= '';
}

if(empty($type) && !empty($topic))
{
	$content = $html;
	$content .= "<p align=\"center\"><a href=\"javascript:window.close()\"><img src=\"img/icon_close.gif\" border=\"0\"  align=\"ABSMIDDLE\" alt=\"$MsgCloseWindow\"></a></p><br>";
	eval("make_output(\"".get_template("helpwindow")."\");");
}
else
{
	$content 	= $html;
	$topx_list_cat 	= $topx_list;
	eval("make_output(\"".get_template("helppage")."\");");
}
if($debug==1){ $timer->end_time(); $timer->elapsed_time();}
$DB_site->close();
if($use_gzip == 1) {ob_end_flush(); }
?>