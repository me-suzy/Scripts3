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
require("./lib.inc.php");
@header ("Pragma: no-cache");

dothml_pageheader();
function indexcard($card_id,$reindex="0") {
	global $DB_site;
	
	if ($reindex == 1)
	{
		$DB_site->query("DELETE FROM vcard_search WHERE card_id='$card_id' ");
	}
	$cardinfo = $DB_site->query_first("SELECT card_id,card_caption,card_keywords FROM vcard_cards WHERE card_id='$card_id' ");
	/* Open the noise words into an array */
	$allwords	= trim($cardinfo['card_caption'])." ".trim($cardinfo['card_keywords']);
	$allwords 	= ereg_replace("^"," ",$allwords);
	$noise_words 	= file("noisewords.txt");
	
	for ($i=0; $i<count($noise_words); $i++)
	{
		$filter_words 	= trim($noise_words[$i]);
		$allwords 	= eregi_replace(" $filter_words "," ",$allwords);
	}
	$allwords	= eregi_replace("[\n\t\r,]"," ",$allwords);
	$allwords	= preg_replace("/(\.+)($| |\n|\t)/s"," ", $allwords);
	$allwords 	= eregi_replace("/"," ",$allwords);
	$allwords 	= preg_replace("/[\(\)\"':*;%,\[\]?!#{}.&_$<>|=`\-+\\\\]/s"," ",$allwords); // '
	$allwords 	= eregi_replace(" ([[0-9a-z]{1,2}) "," "," $allwords ");
	$allwords 	= eregi_replace(" ([[0-9a-z]{1,2}) "," "," $allwords ");
	$allwords 	= eregi_replace("[[:space:]]+"," ", $allwords);
	$allwords	= strtolower(trim($allwords));

	$wordarray	= explode(" ",$allwords);
	$getwordidsql 	= "word_str IN ('" . str_replace(" ","','",$allwords) . "')";
	$words 		= $DB_site->query("SELECT word_id,word_str FROM vcard_word WHERE $getwordidsql");

	while ($word = $DB_site->fetch_array($words))
	{
		$wordcache[$word[word_str]] = $word[word_id];
	}
	$DB_site->free_result($words);

	for ($k=0; $k<count($wordarray); $k++)
	{
		if (strlen($wordarray[$k])>2 && strlen($wordarray[$k])<20)
		{
			$wordarray[$k] = stripslashes(trim($wordarray[$k]));
			//echo "|$eachword[$k]|\n";
			if (isset($wordcache[$wordarray[$k]]))
			{
				$word_id = $wordcache[$wordarray[$k]];
			}else{
				$insert = $DB_site->query("INSERT IGNORE INTO vcard_word(word_id,word_str) VALUES (NULL, '".addslashes($wordarray[$k])."')  ");
				$word_id = $DB_site->insert_id();
			}
			if ($word_id)
			{
				$DB_site->query("INSERT INTO vcard_search (word_id,card_id,cat_id) VALUES ('$word_id','$card_id','$cat_id') ");
			}
		}
	}
}

// ############################# DB ACTION #############################
if($action == 'insert')
{
	checkfieldempty($HTTP_POST_VARS['card_thmfile'],"$msg_admin_card_thmpath $msg_admin_error_formempty");
	if (!eregi("http://",$HTTP_POST_VARS['card_thmfile']))
	{
		$check = file_exists("$site_image_path/$HTTP_POST_VARS[card_thmfile]");
		checkfile($check,$HTTP_POST_VARS['card_thmfile']);
		if(!empty($HTTP_POST_VARS['card_imgfile']) && !eregi("http://",$HTTP_POST_VARS['card_imgfile']))
		{
			$check = file_exists("$site_image_path/$HTTP_POST_VARS[card_imgfile]");
			checkfile($check,$HTTP_POST_VARS['card_imgfile']);
			$extension	= get_file_extension($HTTP_POST_VARS['card_imgfile']);
			if(( $extension == 'gif' || $extension == 'jpg' || $extension == 'jpeg' || $extension == 'png' || $extension == 'swf' ) && (empty($HTTP_POST_VARS['card_width']) || $HTTP_POST_VARS['card_width']==0) )
			{
				$size = GetImageSize ("$site_image_path/$HTTP_POST_VARS[card_imgfile]");
				$card_width = $size[0];
				$card_height = $size[1];
			}
		}
	}
	if (empty($HTTP_POST_VARS['card_imgfile']) && empty($HTTP_POST_VARS['card_template'])) //WRONG
	{
		echo "$msg_admin_card_note";
		exit;
	}
	$result = $DB_site->query("
				INSERT 
				INTO vcard_cards ( card_id, card_category,card_group, card_imgfile, card_thmfile, card_width, card_height, card_template, card_event, card_date, card_caption, card_keywords )
				VALUES (NULL, '$HTTP_POST_VARS[card_category]','$HTTP_POST_VARS[card_group]','$HTTP_POST_VARS[card_imgfile]','$HTTP_POST_VARS[card_thmfile]', '$card_width', '$card_height', '$HTTP_POST_VARS[card_template]','$HTTP_POST_VARS[card_event]','".date("Y-m-d")."','".addslashes($HTTP_POST_VARS[card_caption])."', '$HTTP_POST_VARS[card_keywords]')
				");
	
	$index_card_id = $DB_site->insert_id();
	indexcard($index_card_id);
	make_recount_ncards_to_cat();
	dohtml_result($result,$msg_admin_reg_add);
	$action = 'add';
}

if ($action == 'insertmultiple')
{
	$imgspath = $HTTP_POST_VARS['imgspath'];
	$imgsperpage = $HTTP_POST_VARS['imgsperpage'];

	if (is_array($HTTP_POST_VARS['cardinclude']))
	{
		$count = 0;

		$cat = $HTTP_POST_VARS['cat'];
		$thm = $HTTP_POST_VARS['thm'];
		$event = $HTTP_POST_VARS['event'];
		$caption = $HTTP_POST_VARS['caption'];
		$keywords = $HTTP_POST_VARS['keywords'];
		$group = $HTTP_POST_VARS['group'];

		while (list($key,$val)=each($HTTP_POST_VARS['cardinclude']))
		{
			$imgtmp 	= $imgspath."/".stripslashes($key);
			$cattmp		= $cat[$key];
			$thmtmp		= $thm[$key];
			$eventtmp	= $event[$key];
			$captiontmp	= $caption[$key];
			$keywordstmp	= $keywords[$key];
			$card_grouptmp	= $group[$key];
			//echo "img: $imgtmp <br>thm: $thmtmp <br>cat: $cattmp<br>event: $eventtmp<hr>";
			$extension	= get_file_extension($imgtmp);
			if(( $extension == 'gif' || $extension == 'jpg' || $extension == 'jpeg' || $extension == 'png' || $extension == 'swf' ) && (empty($HTTP_POST_VARS['card_width']) || $HTTP_POST_VARS['card_width']==0) )
			{
				$size = GetImageSize ("$site_image_path/$imgtmp");
				$widthtmp = $size[0];
				$heighttmp = $size[1];
			}
			$DB_site->query("INSERT
					 INTO vcard_cards ( card_category, card_group, card_imgfile, card_thmfile,card_width,card_height,card_template, card_event, card_date, card_caption, card_keywords )
					 VALUES ('$cattmp','$card_grouptmp','$imgtmp','$thmtmp','$widthtmp','$heighttmp','$templatetmp','$eventtmp','".date("Y-m-d")."','".addslashes($captiontmp)."', '".addslashes($keywordstmp)."')
					 ");
			$count++;
		}
		make_recount_ncards_to_cat();
		//end while
		if ($count == $postcardcount)
		{
			// We inserted all of our available Cards
			$action = '';
		}else{
			echo "<script language=\"javascript\">window.location=\"cards.php?action=addmultiple&imgspath=$HTTP_POST_VARS[imgspath]&imgsperpage=$HTTP_POST_VARS[imgsperpage]&default_event=$HTTP_POST_VARS[default_event]&default_category=$HTTP_POST_VARS[default_category]&default_thumbnail_syntax=$HTTP_POST_VARS[default_thumbnail_syntax]&s=$s\";</script>";
			echo "<p><a href=\"cards.php?action=addmultiple&imgspath=$HTTP_POST_VARS[imgspath]&imgsperpage=$HTTP_POST_VARS[imgsperpage]&default_event=$HTTP_POST_VARS[default_event]&default_category=$HTTP_POST_VARS[default_category]&default_thumbnail_syntax=$HTTP_POST_VARS[default_thumbnail_syntax]&s=$s\">$msg_admin_linkforwarded</a></p>";
			exit;
		}
	}else{
		echo "<p>$msg_admin_notselected<p>";
		$action = 'addmultiple';
	}
}

if ($action == 'do_upload')
{
	$card_caption = eregi_replace('"',"'",$HTTP_POST_VARS['card_caption']);
	checkfieldempty($HTTP_POST_FILES['card_thmfile']['name'],"$msg_admin_filethm $msg_admin_error_formempty");
	if ( !empty($HTTP_POST_FILES['card_thmfile']['name']) && empty($HTTP_POST_FILES['card_imgfile']['name']) && empty($HTTP_POST_VARS['card_template']) )//WRONG
	{
		echo "$msg_admin_error_card_assoc";
		exit;
	}

	$datefilename = date("Y-m-d_His");
	// Check empty
	checkfieldempty($HTTP_POST_FILES['card_thmfile'],"$msg_admin_filethm $msg_admin_error_formempty");
	// get file extension.
	$extension	= get_file_extension($HTTP_POST_FILES['card_thmfile']['name']);
	$thm_name 	= "thm_$datefilename.$extension";
	do_fileupload($HTTP_POST_FILES['card_thmfile']['tmp_name'],$HTTP_POST_FILES['card_thmfile']['name'],$thm_name,1,0);


	if (!empty($HTTP_POST_FILES['card_imgfile']))
	{
		// get file extension.
		$extension	= get_file_extension($HTTP_POST_FILES['card_imgfile']['name']);
		$pic_name 	= "pic_$datefilename.$extension";
		do_fileupload($HTTP_POST_FILES['card_imgfile']['tmp_name'],$HTTP_POST_FILES['card_imgfile']['name'],$pic_name,1,0);
        }
	if(( $extension == 'gif' || $extension == 'jpg' || $extension == 'jpeg' || $extension == 'png' || $extension == 'swf' ) && (empty($HTTP_POST_VARS['card_width']) || $HTTP_POST_VARS['card_width']==0) )
	{
		$size = GetImageSize ("$site_image_path/$pic_name");
		$card_width = $size[0];
		$card_height = $size[1];
	}

	$result = $DB_site->query("
				INSERT
				INTO vcard_cards ( card_category, card_group, card_imgfile, card_thmfile, card_width, card_height, card_template, card_event, card_date, card_caption, card_keywords)
				VALUES ('$HTTP_POST_VARS[card_category]','$HTTP_POST_VARS[card_group]','$pic_name','$thm_name','$card_width', '$card_height', '$HTTP_POST_VARS[card_template]','$HTTP_POST_VARS[card_event]','".date("Y-m-d")."','".addslashes($HTTP_POST_VARS[card_caption])."', '$HTTP_POST_VARS[card_keywords]')
				");

	$index_card_id = $DB_site->insert_id();
	indexcard($index_card_id);
	make_recount_ncards_to_cat();
	dohtml_result($result,$msg_admin_reg_add);
	$action = 'upload';
}

if ($action == 'update')
{
	$result = $DB_site->query("
				UPDATE vcard_cards
				SET
				 card_category='$HTTP_POST_VARS[card_category]',
				 card_group='$HTTP_POST_VARS[card_group]',
				 card_imgfile='$HTTP_POST_VARS[card_imgfile]',
				 card_thmfile='$HTTP_POST_VARS[card_thmfile]',
				 card_width='$HTTP_POST_VARS[card_width]',
				 card_height='$HTTP_POST_VARS[card_height]',
				 card_template='$HTTP_POST_VARS[card_template]',
				 card_event='$HTTP_POST_VARS[card_event]',
				 card_caption='".addslashes($HTTP_POST_VARS[card_caption])."',
				 card_keywords='".addslashes($HTTP_POST_VARS[card_keywords])."'
				WHERE card_id='$HTTP_POST_VARS[card_id]'
				");
	indexcard($HTTP_POST_VARS['card_id'],1);
	dohtml_result($result,$msg_admin_reg_update);
	$getcardinfo = $DB_site->query_first("SELECT card_category FROM vcard_cards WHERE card_id='$HTTP_POST_VARS[card_id]' ");
	
	echo "<script language=\"javascript\">window.location=\"browse.php?action=cat_browser&cat_id=$getcardinfo[card_category]&s=$s\";</script>";
	echo "<p><a href='browse.php?action=cat_browser&cat_id=$getcardinfo[card_category]&s=$s'>$msg_admin_linkforwarded</a></p>";
	exit;
}

if ($action == 'delete')
{
	dohtml_form_header("cards","post_delete_yes",0,1);
	dohtml_table_header("edit","$msg_admin_op_confirm_question",2);
	dohtml_form_hidden("card_id",$HTTP_GET_VARS['card_id']);
	$html .= "<b>$msg_admin_menu_delete</b>";
	dohtml_form_label($msg_admin_op_confirm_question,$html);
	dohtml_table_footer();
	dohtml_form_footer($msg_button_confirm);
	exit;
}
	
if ($action == 'post_delete_yes' && ($superuser==1 || $vcuser['candeletecard']==1))
{
	$result = $DB_site->query(" DELETE FROM vcard_cards WHERE card_id='".addslashes($HTTP_POST_VARS['card_id'])."' ");
	$delindex= $DB_site->query("DELETE FROM vcard_search WHERE card_id='".addslashes($HTTP_POST_VARS['card_id'])."' ");
	dohtml_result($result,"$msg_admin_reg_delete");
	make_cachereflash();
	make_recount_ncards_to_cat();
	$action = 'edit';
}



// ############################# ACTION SCREENS #############################
// ############################# EDIT CARD #############################
if ($action == 'card_edit')
{
	$myrow 		= $DB_site->query_first("SELECT * FROM vcard_cards WHERE card_id='".addslashes($HTTP_GET_VARS['card_id'])."' ");
	$post_id 	= $myrow['card_id'];
	$post_cat 	= $myrow['card_category'];
	$post_file 	= $myrow['card_imgfile'];
	$post_thm 	= $myrow['card_thmfile'];
	$post_width	= $myrow['card_width'];
	$post_height	= $myrow['card_height'];
	$post_template 	= $myrow['card_template'];
	$post_event	= $myrow['card_event'];
	$post_group	= $myrow['card_group'];
	$post_caption 	= stripslashes(htmlspecialchars($myrow['card_caption']));
	$post_keywords 	= stripslashes(htmlspecialchars($myrow['card_keywords']));
	$post_width	= cexpr($myrow[card_width]==0,"",$myrow['card_width']);
	$post_height	= cexpr($myrow[card_height]==0,"",$myrow['card_height']);
	//$post_caption 	= eregi_replace('"',"'",$post_caption);
	$temp_eventoption	= gethtml_eventoption($post_event);
	$temp_groupoption	= gethtml_groupoption($post_group);
	
dohtml_form_header("cards","update",0,0);
dohtml_table_header("edit","$msg_admin_menu_edit");

?>
	<tr class="<?php echo get_row_bg(); ?>">
			<td><b><?php echo "$msg_admin_category"; ?> : </b></td>
			<td><select name="card_category">
			<?php echo categoryoption($canworkcategory,$post_cat); ?>
			</select>
		</td>
	</tr>
<?
dohtml_form_select2($msg_admin_event,"event_name","card_event","vcard_event","event_id","event_name",$post_event,"$msg_admin_none");
dohtml_form_select2($msg_admin_cardsgroup,"cardsgroup_name","card_group","vcard_cardsgroup","cardsgroup_id","cardsgroup_name",$post_group,"$msg_admin_none");
//  dohtml_form_file($msg_admin_file,"pattern_file",10000000);
dohtml_form_hidden("card_id",$post_id);
dohtml_form_input($msg_admin_file,"card_imgfile",$post_file);
dohtml_form_input($msg_admin_filethm,"card_thmfile",$post_thm);
dohtml_form_input($msg_admin_width,"card_width",$post_width);
dohtml_form_input($msg_admin_height,"card_height",$post_height);
dohtml_form_input($msg_admin_template,"card_template",$post_template);
dohtml_form_input($msg_admin_caption,"card_caption",$post_caption);
dohtml_form_input($msg_admin_keywords,"card_keywords",$post_keywords);
dohtml_form_footer($msg_admin_reg_update);
dohtml_form_header("cards","post_delete",0,0);
dothml_pagefooter();
	exit;
}

// ############################# ADD NORMAL #############################
if ($action == 'add')
{

dohtml_form_header("cards","insert",0,1);
dohtml_table_header("add","$msg_admin_menu_add");

	$temp_catlist 		= categoryoption($canworkcategory,0);
	$temp_eventoption	= gethtml_eventoption($event_id);
	$temp_groupoption	= gethtml_groupoption($post_group);
?>
	<tr>
		<td><b><?php echo "$msg_admin_category"; ?> : </b></td>
		<td><select name="card_category">
		<?php echo "$temp_catlist"; ?>
		</select>
		</td>
	</tr>
	<tr>
		<td><b><?php echo "$msg_admin_event"; ?> : </b></td>
		<td><select name="card_event">
			<option><?php echo "$msg_admin_none"; ?></option>
			<option></option>
			<?php echo $temp_eventoption;?>
		</select>
		</td>
	</tr>
	<tr>
		<td><b><?php echo "$msg_admin_cardsgroup"; ?> : </b></td>
		<td><select name="card_group">
				<option><?php echo "$msg_admin_none"; ?></option>
				<option></option>
				<?php echo $temp_groupoption;?>
			</select>
		</td>
	</tr>
	<tr>
		<td><b><?php echo "$msg_admin_path $msg_admin_file"; ?>  : </b></td>
		<td><input type="text" name="card_imgfile" size="30"></td>
	</tr>
	<tr>
		<td><b><?php echo "$msg_admin_path $msg_admin_filethm"; ?> :*</b></td>
		<td><input type="text" name="card_thmfile" size="30"></td>
	</tr>
	<tr>
		<td><b><?php echo "$msg_admin_width"; ?> :** </b></td>
		<td><input type="text" name="card_width" size="4"></td>
	</tr>
	<tr>
		<td><b><?php echo "$msg_admin_height"; ?> :**</b></td>
		<td><input type="text" name="card_height" size="4"></td>
	</tr>
	<tr>
		<td><b><?php echo "$msg_admin_template"; ?> : </b></td>
		<td><input type="text" name="card_template" size="30"></td>
	</tr>
	<tr>
		<td><b><?php echo "$msg_admin_caption"; ?> : </b></td>
		<td><input type="text" name="card_caption" size="30" maxlength="180"></td>
	</tr>
	<tr>
		<td><b><?php echo "$msg_admin_keywords"; ?> : </b></td>
		<td><input type="text" name="card_keywords" size="30" maxlength="180"></td>
	</tr>
<?php
	dohtml_form_infobox("<b> $msg_admin_note </b>: $msg_admin_card_note <p> * $msg_admin_help_imagepath <p> ** $msg_admin_help_imagewidth");
	dohtml_form_footer($msg_admin_reg_add);
	if ($superuser ==1)
	{
		dohtml_form_header("cards","addmultiple",0,0,"form");
		dohtml_table_header("default","$msg_admin_menu_multiple",2);
		
		echo "<tr><td>$msg_admin_path</td><td><b>$site_image_url/</b><input type=\"text\" name=\"imgspath\" value=\"\" size=40></td></tr>";
		echo "<tr><td>$msg_admin_default</td><td>
		<table>
		<tr><td> $msg_admin_thumbnail*:	</td><td> <input type=\"text\" name=\"default_thumbnail_syntax\" value=\"\" size=40></td></tr>
		<tr><td> $msg_admin_category: 	</td><td> <select name=\"default_category\"> $temp_catlist </select></td></tr>
		<tr><td> $msg_admin_event : 	</td><td> <select name=\"default_event\"><option value=\"\">$msg_admin_none </option><option value=\"\"> </option>$temp_eventoption </select></td></tr>
		
		<tr><td> $msg_admin_cardsgroup :</td><td> <select name=\"default_group\"><option value=\"\">$msg_admin_none </option><option value=\"\"> $temp_groupoption</select></td></tr>
		</table>
		</td></tr>";
		dohtml_form_infobox("<b> $msg_admin_note </b>: $msg_admin_help_multiple");
		dohtml_form_footer($msg_admin_reg_add);
	
		//echo "<tr><td>Auto-Thumbnail?</td><td>Yes<input type=\"radio\" name=\"autothumb\" value=\"1\">No<input type=\"radio\" name=\"autothumb\" value=\"0\" checked>(GD lib required)</td></tr>";	
	}
	dothml_pagefooter();
exit;
}

// ############################# ADD UPLOAD #############################
if ($action == 'upload')
{
dohtml_form_header("cards","do_upload",1,1);
dohtml_table_header("upload","$msg_admin_menu_add : $msg_admin_menu_upload");
$temp_groupoption	= gethtml_groupoption($post_group);
$temp_eventoption	= gethtml_eventoption($event_id);
?>
	<tr>
		<td><b><?php echo "$msg_admin_category"; ?> : </b></td>
		<td><select name="card_category">
		<?php echo categoryoption($canworkcategory,0); ?>
		</select>
		</td>
	</tr>
	<tr>
		<td><b><?php echo "$msg_admin_event"; ?> : </b></td>
		<td><select name="card_event">
			<option><?php echo "$msg_admin_none"; ?></option>
			<option></option>
			<?php echo $temp_eventoption;?>
		</select>
		</td>
	</tr>
	<tr>
		<td><b><?php echo "$msg_admin_cardsgroup"; ?> : </b></td>
		<td><select name="card_group">
				<option><?php echo "$msg_admin_none"; ?></option>
				<option></option>
				<?php echo $temp_groupoption;?>
			</select>
		</td>
	</tr>
	<tr>
		<td><b><?php echo "$msg_admin_file"; ?>  : </b></td>
		<td><input type="file" name="card_imgfile" size="30"></td>
	</tr>
	<tr>
		<td><b><?php echo "$msg_admin_filethm"; ?> :*</b></td>
		<td><input type="file" name="card_thmfile" size="30"></td>
	</tr>
	<tr>
		<td><b><?php echo "$msg_admin_width"; ?> :**</b></td>
		<td><input type="text" name="card_width" size="4"></td>
	</tr>
	<tr>
		<td><b><?php echo "$msg_admin_height"; ?> :**</b></td>
		<td><input type="text" name="card_height" size="4"></td>
	</tr>
	<tr>
		<td><b><?php echo "$msg_admin_template"; ?> : </b></td>
		<td><input type="text" name="card_template" size="30"></td>
	</tr>
	<tr>
		<td><b><?php echo "$msg_admin_caption"; ?> : </b></td>
		<td><input type="text" name="card_caption" size="30" maxlength="180"></td>
	</tr>
	<tr>
		<td><b><?php echo "$msg_admin_keywords"; ?> : </b></td>
		<td><input type="text" name="card_keywords" size="30" maxlength="180"></td>
	</tr>
<?php
	dohtml_form_infobox("<b> $msg_admin_note </b>: $msg_admin_card_note <p> * $msg_admin_help_safemode <p> ** $msg_admin_help_imagewidth");
	dohtml_form_footer($msg_admin_reg_add);
	dothml_pagefooter();
exit;
}
// ############################# EDIT #############################
if ($action == 'edit')
{
	dohtml_table_header("show","$msg_admin_menu_show");
	if ($superuser == 1 || $canworkcategory==0)
	{
		$catlist = $DB_site->query(" SELECT * FROM vcard_category WHERE cat_subid='' ORDER BY cat_order ");
		while ($cat = $DB_site->fetch_array($catlist))
		{
			extract($cat);
			$cat_name = stripslashes(htmlspecialchars($cat_name));
			echo "<tr class=\"".get_row_bg()."\"><td> ". cexpr($cat_active,"<font size=\"1\" color=\"#FF0000\">($msg_admin_on)","<font size=\"1\">($msg_admin_off)") ."</font>";
			echo "</td><td><a href=\"browse.php?mode=cat_browser&cat_id=$cat_id&s=$s\"><b>$cat_name</b></a></td></tr>\n";
			
			$subcatlist = $DB_site->query(" SELECT * FROM vcard_category WHERE cat_subid='$cat_id' ORDER BY cat_order ");
			while ($subcat = $DB_site->fetch_array($subcatlist))
			{
				extract($subcat);
				$cat_name = stripslashes(htmlspecialchars($cat_name));
				echo "<tr class=\"".get_row_bg()."\"><td> ". cexpr($cat_active,"<font size=\"1\" color=\"#FF0000\">($msg_admin_on)","<font size=\"1\">($msg_admin_off)") ."</font>";
				echo "</td><td><a href=\"browse.php?mode=cat_browser&cat_id=$cat_id&s=$s\"><b>&nbsp; &nbsp; &raquo; $cat_name</b></a></td></tr>\n";
			}
			$DB_site->free_result($subcatlist);
		}
		$DB_site->free_result($catlist);
	}
	else
	{
		$catinfo = $DB_site->query_first("SELECT * FROM vcard_category WHERE cat_id='$canworkcategory' ");
		$catinfo['cat_name'] = stripslashes(htmlspecialchars($catinfo['cat_name']));
		echo "<tr><td> ". cexpr($catinfo[cat_active],"<font size=\"1\" color=\"#FF0000\">($msg_admin_on)","<font size=\"1\">($msg_admin_off)") ."</font>";
		echo "</td><td><a href=\"browse.php?mode=cat_browser&cat_id=$catinfo[cat_id]&s=$s\"><b>$catinfo[cat_name]</b></a></td></tr>\n";
		return $html;
		
	}
	echo "</table>";
	dothml_pagefooter();
	exit;
}

// ############################# INSERTMULTIPLE #############################
if ($action == 'addmultiple')
{
	if (!empty($HTTP_GET_VARS['imgspath']))
	{
		$imgspath = $HTTP_GET_VARS['imgspath'];
	}else{
		$imgspath = $HTTP_POST_VARS['imgspath'];
	}
	if (!empty($HTTP_GET_VARS['imgsperpage']))
	{
		$imgsperpage = $HTTP_GET_VARS['imgsperpage'];
	}else{
		$imgsperpage = $HTTP_POST_VARS['imgsperpage'];
	}
	if (!empty($HTTP_GET_VARS['default_event']))
	{
		$default_event = $HTTP_GET_VARS['default_event'];
	}else{
		$default_event = $HTTP_POST_VARS['default_event'];
	}
	if (!empty($HTTP_GET_VARS['default_category']))
	{
		$default_category = $HTTP_GET_VARS['default_category'];
	}else{
		$default_category = $HTTP_POST_VARS['default_category'];
	}
	if (!empty($HTTP_GET_VARS['default_thumbnail_syntax']))
	{
		$default_thumbnail_syntax = $HTTP_GET_VARS['default_thumbnail_syntax'];
	}else{
		$default_thumbnail_syntax = $HTTP_POST_VARS['default_thumbnail_syntax'];
	}
	if (!empty($HTTP_GET_VARS['startpage']))
	{
		$startpage = $HTTP_GET_VARS['startpage'];
	}else{
		$startpage = $HTTP_POST_VARS['startpage'];
	}
	if (!empty($HTTP_GET_VARS['default_group']))
	{
		$default_group = $HTTP_GET_VARS['default_group'];
	}else{
		$default_group = $HTTP_POST_VARS['default_group'];
	}
	
	


?>
<script language="JavaScript">
<!--
function CheckAll(){
	for (var i=0;i<document.form.elements.length;i++){
		var e = document.form.elements[i];
		if ((e.name != 'allbox') && (e.type=='checkbox'))
		e.checked = document.form.allbox.checked;}}
function CheckCheckAll(){
	var TotalBoxes = 0;
	var TotalOn = 0;
	for (var i=0;i<document.form.elements.length;i++){
		var e = document.form.elements[i];
		if ((e.name != 'allbox') && (e.type=='checkbox')){
			TotalBoxes++;
			if (e.checked){
				TotalOn++;}}}
	if (TotalBoxes==TotalOn){
		document.form.allbox.checked=true;}
	else{
		document.form.allbox.checked=false;}}
//-->
</script>
<?php
	$catlist = $DB_site->query(" SELECT * FROM vcard_category WHERE cat_subid='' ORDER BY cat_order ");

	while ($cat = $DB_site->fetch_array($catlist))
	{
		extract($cat);
		$cat_name = stripslashes(htmlspecialchars($cat_name));
		$category_tmp	.= "<option value=\"$cat_id\"". cexpr($default_category==$cat_id," selected","") ."> $cat_name</option>\n";
		$subcatlist = $DB_site->query(" SELECT * FROM vcard_category WHERE cat_subid='$cat_id' ORDER BY cat_order ");
		while ($subcat = $DB_site->fetch_array($subcatlist))
		{
			extract($subcat);
			$cat_name 	= stripslashes(htmlspecialchars($cat_name));
			$category_tmp	.= "<option value=\"$cat_id\"". cexpr($default_category==$cat_id," selected","") .">&nbsp;&nbsp; &raquo; $cat_name</option>\n";
		}
		$DB_site->free_result($subcatlist);
	}
	$DB_site->free_result($catlist);
	
	$eventlist = $DB_site->query(" SELECT * FROM vcard_event ORDER BY event_month,event_day ASC ");
	$event_tmp 	.="<option value=\"\">None</option>";
	while ($event = $DB_site->fetch_array($eventlist))
	{
		extract($event);
		$event_name 	= stripslashes(htmlspecialchars($event_name));
		$event_tmp 	.="<option value=\"$event_id\"". cexpr($default_event=="$event_id"," selected","") .">$event_day-$event_dayend/" . get_monthname($event_month,1) ." - $event_name</option>\n";
	}
	$DB_site->free_result($eventlist);
	
	$temp_groupoption 	="<option value=\"\">None</option>";
	$temp_groupoption	= gethtml_groupoption($default_group);
	
	if (intval($startpage) < 1)
	{
		$startpage = 1;
	}
	if (intval($imgsperpage) < 1)
	{
		$imgsperpage = 10;
		$imgspath = preg_replace("/\/$/s","",$imgspath);
	}
	chdir($site_image_path);
	if ($dirhandle = @opendir($imgspath))
	{
		dohtml_form_header("cards","insertmultiple",0,0,"form");
		dohtml_table_header("domultiple","$msg_admin_mutiple - <b>$site_image_url/$imgspath</b>",4);
		echo "<input type=\"hidden\" name=\"imgspath\" value=\"$imgspath\">
		<input type=\"hidden\" name=\"imgsperpage\" value=\"$imgsperpage\">";
		echo "	<tr>
			<td align=\"center\"><b>$msg_admin_include</b><br>$msg_admin_all:<input name=\"allbox\" type=\"checkbox\" value=\"Check All\" onClick=\"CheckAll();\"></td>
			<td align=\"center\"><b>$msg_admin_image</b></td>
			<td align=\"center\"><b>$msg_admin_thumbnail $msg_admin_path / $msg_admin_category / $msg_admin_event</b></td>
			</tr>";
		$cardsindb = $DB_site->query("SELECT card_imgfile FROM vcard_cards");
		while ($cards = $DB_site->fetch_array($cardsindb))
		{
			$cardArray[$cards[card_imgfile]] = 'yes';
		}
		while ($filename = readdir($dirhandle))
		{
			$cardpath = $imgspath.'/'.$filename;
			if ($filename!='.' && $filename!='..' && !$cardArray[$cardpath] && (($filelen=strlen($filename))>=5))
			{
				$fileext = strtolower(substr($filename,$filelen-4,$filelen-1));
				if ($fileext=='.gif' || $fileext=='.jpg' || $fileext=='jpeg' || $fileext=='.png')
				{
					//echo "$filename <br>\n";
					$FileArray[count($FileArray)] = addslashes($filename);
				}
			}
		}
		$imsgcount = count($FileArray);
		if ((($imsgcount / $imgsperpage) - ((int) ($imsgcount / $imgsperpage))) == 0)
		{
			$numpages = $imsgcount / $imgsperpage;
		}else{
			$numpages = (int) ($imsgcount / $imgsperpage) + 1;
		}
		if ($startpage == 1)
		{
			$startpostcard 	= 0;
			$endpostcard 	= $imgsperpage - 1;
		}else{
			$startpostcard 	= ($startpage - 1) * $imgsperpage;
			$endpostcard 	= ( $imgsperpage * $startpage ) - 1 ;
		}
		for ($x = 0; $x < $imsgcount; $x++)
		{
			if ($x >= $startpostcard && $x <= $endpostcard)
			{
				echo "<tr>\n";
				echo "<td align=\"center\" valign=\"top\"><input type=\"checkbox\" name=\"cardinclude[$FileArray[$x]]\" value=\"yes\"></td>\n";
				echo "<td align=\"center\" valign=\"top\">$imgspath/$FileArray[$x]<br><img src=\"$site_image_url/$imgspath/$FileArray[$x]\" border=0 align=\"center\"></td>\n";
				echo "<td valign=\"top\">\n";
				
					$thm_filenameonly = removeextension($FileArray[$x]);
					$padrao = str_replace("[directory]", "$imgspath", $default_thumbnail_syntax);
					$padrao = str_replace("[name]", "$thm_filenameonly", $padrao);
					$padrao = str_replace("[fullname]", "$FileArray[$x]", $padrao);
					//$padrao = "$imgspath/thm/$FileArray[$x]";
				
				echo "
				$msg_admin_thumbnail:<br><input type=\"text\" name=\"thm[$FileArray[$x]]\"  value=\"$padrao\" size=40><br>
				<img src=\"$site_image_url/$padrao\" border=0 alt=\"\">
				<p>
				$msg_admin_category:<br><select name=\"cat[$FileArray[$x]]\">$category_tmp</select><p>
				$msg_admin_cardsgroup:<br><select name=\"group[$FileArray[$x]]\">$temp_groupoption</select><p>
				$msg_admin_event:<br><select name=\"event[$FileArray[$x]]\">$event_tmp</select><p>
				$msg_admin_caption:<br><input type=\"text\" name=\"caption[$FileArray[$x]]\"  value=\"\" size=40><p>
				$msg_admin_keywords:<br><input type=\"text\" name=\"keywords[$FileArray[$x]]\"  value=\"\" size=40>
				</td>";
				echo "</tr>\n";
			}
		}
		if ($numpages > 1)
		{
			for ($x = 1; $x <= $numpages; $x++)
			{
				if ($x == $startpage)
				{
					$pagelinks .= "<b> $x </b>";
				}else{
					$pagelinks .= " <a href=\"cards.php?startpage=$x&imgsperpage=$imgsperpage&action=addmultiple&imgspath=$imgspath&default_category=$default_category&default_event=$default_event&default_thumbnail_syntax=$default_thumbnail_syntax&default_group=$default_group&s=$s\">$x</a> ";
				}
			}
			if ($startpage != $numpages)
			{
				$nextstart 	= $startpage + 1;
				$nextpage 	= " <a href=\"cards.php?startpage=$nextstart&imgsperpage=$imgsperpage&action=addmultiple&imgspath=$imgspath&default_category=$default_category&default_event=$default_event&default_thumbnail_syntax=$default_thumbnail_syntax&default_group=$default_group&s=$s\">&raquo;</a>";
				$epostcard 	= $endpostcard + 1;
			}else{
				$epostcard 	= $imsgcount;
			}
			if ($startpage!=1)
			{
				$prevstart 	= $startpage - 1;
				$prevpage 	= "<a href=\"cards.php?startpage=$prevstart&imgsperpage=$imgsperpage&action=addmultiple&imgspath=$imgspath&default_category=$default_category&default_event=$default_event&default_thumbnail_syntax=$default_thumbnail_syntax&default_group=$default_group&s=$s\">&laquo;</a> ";
			}
			$spostcard = $startpostcard +  1;
			echo "<tr><td align=\"center\" colspan=4>$spostcard - $epostcard/$imsgcount<br><br>$prevpage $pagelinks $nextpage</td></tr>";
		}
		echo "<input type=\"hidden\" name=\"default_category\" value=\"$default_category\">
		<input type=\"hidden\" name=\"default_event\" value=\"$default_event\">
		<input type=\"hidden\" name=\"default_group\" value=\"$default_group\">
		<input type=\"hidden\" name=\"default_thumbnail_syntax\" value=\"$default_thumbnail_syntax\">
		<input type=\"hidden\" name=\"imsgcount\" value=\"$imsgcount\">";
		dohtml_form_footer($msg_admin_reg_add);
		closedir($dirhandle);
	}
	else
	{
		echo "<p><b>$imgspath</b> $msg_admin_notfound";
	}
}
?>
