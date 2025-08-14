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
function advheadlines($row) {
	global
		$nuketable,$block_sidetemplate
	;
//	print "\n<!-- start: ".microtime()." -->\n";
	$max_items = 10;
	$show_descriptions = true;
	$past = time() - $row[refresh];
	if($row[unix_update] < $past && $row[url]) {
//	if(true) {
		$rss = parse_url($row[url]);
// retrive the rss file
		$fp = fsockopen($rss[host], 80, &$errno, &$errstr, 5);
		if(!$fp) {
			$content = addslashes('<font size="2">' . _RSSPROBLEM . '</font>');
			$next_try = time() + 600;
			$result = mysql_query("UPDATE $nuketable[advblocks] SET content='$content',last_update=FROM_UNIXTIME($next_try) WHERE bid=$bid");
			themesidebox("$row[title] *", "$row[content]\n\n\n<!--\n\n\n\n\n\n\n".strftime(_DATESTRING,$row[unix_update])."\n\n\n\n\n-->\n\n\n\n");
			return;
		}
		else {
			fputs($fp, 'GET ' . $rss[path] . '?' . $rss[query] . " HTTP/1.0\r\n");
			fputs($fp, 'HOST: ' . $rss[host] . "\r\n\r\n");
			$rss_file = '';
			$start_time = time();
			while(!feof($fp)) {
				$line = fgets($fp, 4096);
				if(!$go) {
					if($line[0] == '<') {
						$go = true;
					}
//					else{print '<!-- '.trim($line)." -->\n";}
				}
				else {
					$rss_file[] = trim($line);
				}
				if((time() - $start_time) == 5) { // if the source server is too slow, we give up. 5 seconds is more then enough time
					fputs($fp, "Connection: close\r\n\r\n");
					fclose($fp);
					$sql = "UPDATE $nuketable[advblocks] SET last_update=0 WHERE bid=$row[bid]";
					if(!mysql_query($sql)) { // we want to try again in a few minutes
						print "\n\n\n<!--Time Out\n\n\n".mysql_error()."\n\n\n$sql\n\n\n-->";
					}
					return;
				}
			}
			fputs($fp, "Connection: close\r\n\r\n");
			fclose($fp);
			$struct = rss_parse_array($rss_file);
//			print "\n\n\n\n\n\n\n\n\n\n\n\n<!--$rdf_file-->\n\n\n\n\n\n\n\n\n\n\n";
// parse the file
			$channel_data = '';
			$image_data = '';
			$item_data = '';
			$search_data = '';
			$total_items = 0;
			foreach($struct as $v) {
				if(!is_array($v)) {
					continue;
				}
				if($v[type] == 'open') {
					switch($v[tag]) {
						case 'channel' :
							$cur_block = 'channel';
							break;
						case 'image' :
							$cur_block = 'image';
							break;
						case 'item' :
							$cur_block = 'item';
							break;
						case 'textinput' :
							$cur_block = 'textinput';
							break;
					}
				}
				elseif($v[type] == 'close') {
					switch($v[tag]) {
						case 'channel' :
							$cur_block = '';
							break;
						case 'image' :
							$cur_block = '';
							break;
						case 'item' :
							$cur_block = '';
							$total_items++;
							break;
						case 'textinput' :
							$cur_block = '';
							break;
					}
				}
				elseif($v[type] == 'complete') {
					$tag = $v[tag];
					switch($cur_block) {
						case 'channel' :
							$channel_data[$tag] = $v[value];
							break;
						case 'image' :
							$image_data[$tag] = $v[value];
							break;
						case 'item' :
							$item_data[$total_items][$tag] = $v[value];
							break;
						case 'textinput' :
							$search_data[$tag] = $v[value];
							break;
					}
				}
			}
// start generating content
			$content = '';
// image & link
			if($image_data[url]) {
				if(!$image_data[link]) {
					$image_data[link] = $channel_data[link];
				}
				if(!$image_data[title]) {
					$image_data[title] = $channel_data[title];
				}
				if(!$image_data[description]) {
					if($channel_data[description]) {
						$image_data[description] = $channel_data[description];
					}
					else {
						$image_data[description] = 'No description provided...';
					}
				}
				if(!$image_data[width]) {
					$image_data[width] = 88;
				}
				if(!$image_data[height]) {
					$image_data[height] = 31;
				}
				$content .= "<div align=\"center\"><a href=\"$image_data[link]\" target=\"_blank\" title=\"$image_data[description]\">\n"
					."<img src=\"$image_data[url]\" border=\"0\" alt=\"$image_data[title]\" width=\"$image_data[width]\" height=\"$image_data[height]\"></a>\n"
					.'</div>'
				;
			}
// pub date
			if($channel_data[pubDate]) {
				$content .= "<div align=\"center\"><b>($channel_data[pubDate])</b></div>";
			}
// items
			if($max_items > $total_items) { // we don't want a bunch of empty item spaces
				$max_items = $total_items;
			}
			for($i = 0; $i < ($max_items); $i++) {
				if($i) {
					$content .= "<hr noshade size=\"1\" width=\"50%\">\n";
				}
				if(!$item_data[$i][title]) {
					$item_data[$i][title] = '<i>[no title]</i>';
				}
				$content .= '<a href="' . $item_data[$i][link] . '" title="' . $item_data[$i][title] . '" target="_blank">' . $item_data[$i][title] . '</a><br>';
				if($show_descriptions && $item_data[$i][description]) {
					$content .= '<font size="1"><i>' . $item_data[$i][description] . '</i></font><br>';
				}
			}
// search
			if($search_data[link] && $search_data[name] && $search_data[title]) {
				$content .= "<hr noshade size=\"1\" width=\"50%\"><center><form method=\"get\" action=\"$search_data[link]\" target=\"_blank\">\n";
				if($search_data[description]) {
					$content .= htmlentities($search_data[description]) . '<br>';
				}
				$content .= "<input type=\"text\" name=\"$search_data[name]\" size=\"15\"><br><input type=\"submit\" value=\"$search_data[title]\"></form></center>\n";
			}
// copyright
			if($channel_data[copyright]) {
				$content .= "<div align=\"center\"><font size=\"1\">$channel_data[copyright]</font></div>\n";
			}
// done with rdf file
			$content .= "<div align=\"right\"><a href=\"$channel_data[link]\" target=\"_blank\"><b>" . _HREADMORE . "</b></a></div>\n";
			$row[content] = "<font size=\"1\">$content</font>\n";
		}
		$sql_content = addslashes($row[content]);
		$sql = "UPDATE $nuketable[advblocks] SET content='$sql_content',last_update=NOW() WHERE bid=$row[bid]";
		if(!mysql_query($sql)) {
			$row[title] .= ' *';
			$row[content] .= "<!--\n\n\n".mysql_error()."\n\n\n$sql\n\n\n-->";
		}
	}
	// themesidebox('News: ' . $row[title], $row[content]);
	$block_title ='News: ' . $row[title];
	$block_content = $row[content];
	eval("dooutput(\"".gettemplate($block_sidetemplate)."\");");

//	print "\n<!-- end: ".microtime()." -->\n";
}

/*
 * Parse RSS File (as array of lines)
 *
 * A rather un-optimized function to parse an rss file (sent as an array)
 * I'll have to clean it up some later.
 *
 * If all goes well, the resulting array should be compatable with the results from
 * the built-in xml_parse_into_struct() function.  Except for some differences in
 * parsing of html entities.
 *
 */
function rss_parse_array($f) {
	$struct = '';
	foreach($f as $line) {
		$parse = '';
		// get our positions
		$sp = strpos($line,'>');
		$ep = strrpos($line,'<');
		$ep2 = strrpos($line,'>');
		// split into first tag, last tag, and content
		$first_tag = substr($line,1,($sp - 1));
		$last_tag = substr($line,($ep + 1),(($ep2 - $ep) - 1));
		$content = substr($line,($sp + 1),(($ep - 1) - $sp));
		if(!$line) { // blank line
			continue;
		}
		if($first_tag == $last_tag) { // no content, single tag line
			if($first_tag[0] == '/') {
				$parse[type] = 'close';
				$parse[tag] = strtolower(substr($first_tag,1,(strlen($first_tag) - 1)));
			}
			else {
				$parse[type] = 'open';
				$parse[tag] = strtolower($first_tag);
			}
			$parse[value] = '';
		}
		else { // complete
			$parse[type] = 'complete';
			$parse[tag] = strtolower($first_tag);
			if($content) { // convert everything to html entities except tags
				$content = htmlentities($content);
				$content = str_replace('&amp;','&',$content); // me no like, but no other way I can find
				$content = str_replace('&gt;','>',$content);
				$content = str_replace('&lt;','<',$content);
			}
			$parse[value] = $content;
		}
		$struct[] = $parse;
	}
	return $struct;
}
?>