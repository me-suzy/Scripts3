<?php
 /***********************************************************************
 * vbPortal: CMS mod for vBulletin                                      
 * vBulletin is Copyright ©2000, 2001, Jelsoft Enterprises Limited.     
 * ===========================                                          
 * vbPortal by wajones                                                  
 * Copyright (c) 2001 by William A. Jones                               
 * http://www.phpportals.com                                            
 *
 * Based in part of the Advanced Blocks System
 * Copyright (c) 2001 Patrick Kellum (webmaster@quahog-library.com)
 * http://quahog-library.com/
 *
 * Based in part of the blocks system in PHP-Nuke
 * Copyright (c) 2001 by Francisco Burzi (fbc@mandrakesoft.com)
 * http://phpnuke.org/
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *****************************************************************************/
function nukecode($text, $format='html') {
	switch($format) {
		default:
		case 'html':
       
			return nukecode2html($text);
			break;
	}
}

function nukecode2html($text) {
	$text = htmlentities($text);
	// simple markup
	$text = str_replace('[b]', '<b>', $text);
	$text = str_replace('[/b]', '</b>', $text);
	$text = str_replace('[i]', '<i>', $text);
	$text = str_replace('[/i]', '</i>', $text);
	$text = str_replace('[u]', '<u>', $text);
	$text = str_replace('[/u]', '</u>', $text);
	$text = str_replace('[center]', '<center>', $text);
	$text = str_replace('[/center]', '</center>', $text);
	// a href
	$text = eregi_replace('\[url=([^\[]*)\]([^\[]*)\[/url\]','<a href="\1" target="_blank" title="\2">\2</a>',$text);
	$text = eregi_replace('\[url\]([^\[]*)\[/url\]','<a href="\1" target="_blank" title="Link to: \1">\1</a>',$text);
	// email
	$text = eregi_replace('\[email\]([^\[]*)\[/email\]','<a href="mailto:\1" title="Email \1">\1</a>',$text);
	// img
	$text = eregi_replace('\[img url=([^\[]*) alt=([^\[]*)\]([^\[]*)\[/img\]','<a href="\1" target="_blank" title="\2"><img src="\3" border="0" alt="\2"></a>',$text);
	$text = eregi_replace('\[img alt=([^\[]*)\]([^\[]*)\[/img\]','<img src="\2" border="0" alt="\1">',$text);
	$text = eregi_replace('\[img\]([^\[]*)\[/img\]','<img src="\1" border="0" alt="">',$text);
	// shockwave
	$text = eregi_replace('\[swf width=([^\[]*) height=([^\[]*)\]([^\[]*)\[/swf\]','<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=4\,0\,2\,0\" width="\1" height="\2"><param name="quality" value="high"><param name="src" value="\3"><embed src="\3" quality="high" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="\1" height="\2"></embed></object>',$text);
	// font
	$text = eregi_replace('\[font size=([^\[]*) face=([^\[]*)\]([^\[]*)\[/font\]','<font size="\1" face="\2">\3</font>',$text);
	$text = eregi_replace('\[font size=([^\[]*)\]([^\[]*)\[/font\]','<font size="\1">\2</font>',$text);
	//
	// get rid of anoying newlines after tags
	// every tag after this point will be stripped of newlines immediately
	// following it
	//
	$text = str_replace("]\n",']',$text);
	$text = str_replace("]\r\n",']',$text);
	// headers
	$text = eregi_replace('\[h([1-6]) align=([^\[]*)\]([^\[]*)\[/h([1-6])\]','<h\1 align="\2">\3</h\1>',$text);
	$text = eregi_replace('\[h([1-6])\]([^\[]*)\[/h([1-6])\]','<h\1>\2</h\1>',$text);
	// hr
	$text = str_replace('[line]', '<hr noshade size="1">', $text);
	$text = eregi_replace('\[line width=([^\[]*) align=([^\[]*)\]','<hr noshade size="1" width="\1" align="\2">',$text);
	// quotes
	$text = eregi_replace('\[quote\]([^\[]*)\[/quote\]', '<blockquote>\1</blockquote>',$text);
	// lists
	$text = str_replace('[*]','<li>',$text);
	$text = eregi_replace('\[list type=([^\[]*)\]([^\[]*)\[/list\]','<ol type="\1">\2</ul>',$text);
	$text = eregi_replace('\[list\]([^\[]*)\[/list\]','<ul>\1</ul>',$text);
	// tables
	$text = eregi_replace('\[table border=([^\[]*)\]','<table border="\1">',$text);
	$text = eregi_replace('\[table border=([^\[]*) width=([^\[]*)\]','<table border="\1" width="\2">',$text);
	$text = str_replace('[/table]','</table>',$text);
	$text = eregi_replace('\[tr bgcolor=([^\[]*)\]','<tr bgcolor="\1">',$text);
	$text = str_replace('[tr]','<tr>',$text);
	$text = str_replace('[/tr]','</tr>',$text);
	$text = eregi_replace('\[td align=([^\[]*) valign=([^\[]*) bgcolor=([^\[]*)\]','<td align="\1" valign="\2" bgcolor="\3"><font size="2">',$text);
	$text = eregi_replace('\[td align=([^\[]*) valign=([^\[]*) width=([^\[]*)\]','<td align="\1" valign="\2" width="\3"><font size="2">',$text);
	$text = eregi_replace('\[td align=([^\[]*) valign=([^\[]*)\]','<td align="\1" valign="\2"><font size="2">',$text);
	$text = eregi_replace('\[td align=([^\[]*) bgcolor=([^\[]*)\]','<td align="\1" bgcolor="\2"><font size="2">',$text);
	$text = eregi_replace('\[td align=([^\[]*)\]','<td align="\1"><font size="2">',$text);
	$text = eregi_replace('\[td bgcolor=([^\[]*)\]','<td bgcolor="\1"><font size="2">',$text);
	$text = str_replace('[td]','<td><font size="2">',$text);
	$text = str_replace('[/td]','</font></td>',$text);
	// return the formated text
	return nl2br($text);
}
?>