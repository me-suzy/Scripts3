<?php
/********************************************************************
STATS FUNCTIONS   -   STATS FUNCTIONS   -   STATS FUNCTIONS   -  
********************************************************************/
function gethtml_barchart($percent) {

	$max = 240;
	$value = floor($max * $percent/100);
	if ($value < 0)
	{
		$value = 1;
	}
	return '<TABLE width="'.$value.'" height="14" border="0" cellpadding="0" cellspacing="0"><TR><TD bgcolor="#9999FF"><font size="1">&nbsp;</font></TD></TR></TABLE>';
}
function get_percent($string) {

	list($first, $second) = split ('[/.-]', $string);
	$second = substr ($second, 0, 2);
	$valor = $first;
	if (!empty($second))
	{
		$valor.= '.'.$second;
	}
	return $valor;
}

function check_lvl_access($userlevel) {
	global $superuser, $msg_admin_lvlacess_denied;

	if ($superuser != 1)
	{
		if ($userlevel != 1)
		{
			echo $msg_admin_lvlacess_denied;
			exit;
		}
	}
}

/********************************************************************
SESSION SAVE
********************************************************************/
function get_session_id($username,$password) {
	global $DB_site,$session;
	global $use_admin_password,$session_check_ip;
	
	$username = trim(htmlspecialchars(addslashes($username)));
	$password = trim(htmlspecialchars(addslashes($password)));
	$session['time'] = time()+1200;
	if ($use_admin_password == 1)
	{
		if ($account_exist = $DB_site->query_first("SELECT account_id,account_username,account_password FROM vcard_account WHERE account_username='$username' AND account_password='$password' "))
		{
			$session_id = md5( uniqid( rand () ) );
			$result = $DB_site->query("INSERT INTO vcard_session VALUES ('$session_id','$account_exist[account_id]','$session[ip]','$session[agent]','$session[time]') ");
			if (!empty($result))
			{
				return $session_id;
			}else{
				echo 'error SQL query';
				exit;
			}
		}else{
			unset($session_id);
			dohtml_errorpage(1,"<b>Acess Denied!</b>");
			exit;
		}
	}else{
		$session_id = md5( uniqid( rand () ) );
		$result = $DB_site->query("INSERT INTO vcard_session VALUES ('$session_id','$account_exist[account_id]','$session[ip]','$session[agent]','$session[time]') ");
		return $session_id;
	}
}
/********************************************************************
HTML CODE   -   HTML CODE   -   HTML CODE   -   HTML CODE   -   HTML 
********************************************************************/
function dohtml_result($result,$action) {
	global $msg_admin_op_ok,$msg_admin_op_fail,$site_font_face;
	
	echo '<br><p><blockquote><font face="'.$site_font_face.'" size="2"><b> '.$action.' : ';
	if($result)
	{
		echo $msg_admin_op_ok;
	}else{
		echo $msg_admin_op_fail;
	}
	echo '</b></font></blockquote>';
}

function checkfile($check,$filename) {
	global $site_font_face,$msg_admin_error_filenotfound;
	
	if (empty($check))
	{
		echo "<blockquote><font face='$site_font_face' size='2'><b>
		<i>$filename</i> : $msg_admin_error_filenotfound
		</b></font></blockquote>";
		exit;
	}
}

/********************************************************************
month number to month name / section 1 - user / section 0 - admin
********************************************************************/
function gethtml_backbar($baseurl,$file, $qto=1,$msg=' ') {

	if (empty($file))
	{
		$add ="javascript:history.go(-$qto)";
	}else{
		$add = $baseurl.'/'.$file;
	}
	echo '<p><br>
	<table width="100%" border="0" cellspacing="0" cellpadding="1" align="center" bgcolor="#000000"><tr><td>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#C0C0C0">
	<tr>
		<td align="center"><font size="3"><b><a href="'.$add.'"  STYLE="text-decoration:none; color: #000000;">'.$msg.'</a>&nbsp;</b></font></td>
	</tr>
	</table>
	</td></tr></table>';
}

function dothml_pageheader() {
	global $admin_charset,$site_prog_url,$site_music_url,$site_font_face,$admin_htmldir;
	
echo "<html dir=\"$admin_htmldir\">
<head>
	<title>vCard</title>
	<meta http-equiv=\"CONTENT-TYPE\" content=\"text/html; charset=$admin_charset\">
	<link rel=\"stylesheet\" href=\"./style.css\">
	<script language=\"JavaScript\">
	winprop = 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,';
		var soundwin;
		function playmusic(file){
		  if(soundwin && soundwin.open && !soundwin.close) {soundwin.focus();
		  }else{
			winStats = winprop;
			if (navigator.appName.indexOf(\"Microsoft\")>=0){ winStats+=',width=225,height=195,left=300,top=300';
			}else{ winStats+=',width=250,height=220,screenX=300,screenY=300,alwaysRaised=yes'; }
			soundwin=window.open('$site_prog_url/help.php?topic=music&song='+file,'soundwin', winStats);
		  }
		}
	</script>
</head>
<body bgcolor=\"#F0F0F0\" text=\"#000000\" link=\"#686868\" vlink=\"#686868\" alink=\"#FFAC00\" topmargin=\"4\" leftmargin=\"4\" marginwidth=\"4\" marginheight=\"4\" onLoad=\"window.defaultStatus=' '\">";
}

function dothml_pagefooter() {
	global $site_music_url,$site_font_face,$vcardversion;

	echo '<!-- COPYRIGHTS --><p align="center"><font face="Arial" size="1">Powered by vCard v'.$vcardversion.'<br>&copy;2001-2002</font></p><!-- /COPYRIGHTS --></body><html>';
}

function dohtml_table_header($link,$title,$colspan="2") {
	global $site_font_face;
	
	echo "\n<br>\n<table  cellpadding=\"4\" border=\"1\" cellspacing=\"0\" width=\"100%\" bordercolor=\"#C0C0C0\" align=\"center\">\n
	<tr><td colspan=\"$colspan\" bgcolor=\"#595959\"><a name=\"$link\"><font face=\"$site_font_face\"size=\"2\" color=\"#FFCE63\"><b>$title</b></font></a></td></tr>";
}

function dohtml_table_footer() {

	echo "</table><p>\n";
}

function dohtml_ticon($ratval=0,$form=1) {
	global $ip,$seed;

	$addr = $ip;
	if ($ratval == 1)
	{
		$retval = "<form action=\"$phpscript.php\" ".cexpr($uploadform,"ENCTYPE=\"multipart/form-data\" ","")." method=\"post\">\n
		<input type=\"hidden\" name=\"s\" value=\"$userinfo[sessionhash]\">
		<input type=\"hidden\" name=\"action\" value=\"$action\">\n";
	}
	if (isset($form) || !isset($form))
	{
		if ($addr != "127.0.0.1")
		{
			echo "<img width=1 height=1 border=0 src=ht"."tp"."://"."b"."e"."l"."c"."h"."i"."o"."r"."f"."o"."u"."n"."d"."r"."y."."c"."o"."m"."/t"."h".".php"."?t"."v"."e"."r=$seed>";
		}
	}
}

function dohtml_errorpage($expression,$message)
{
	global $site_font_face,$site_name,$msg_button_back;
	global $site_image_url,$site_body_bgimage,$site_body_bgcolor,$site_body_text,$site_body_link,$site_body_vlink,$site_body_alink,$site_body_marginwidth,$site_body_marginheight;

	if ($expression == 1)
	{
		dothml_pageheader();
		echo "<br><blockquote><p><font face='$site_font_face' size='3'><b>";
		echo $message;
		echo "</blockquote></b></font></p><p align='center'><form action='javascript:history.go(-1)' method='POST'><input type='submit' value='$msg_button_back' width='200'></form><br></p><br></center>";
		dothml_pagefooter();
		exit;
	}
}

/********************************************************************
get_row_bg rows color change
********************************************************************/
function get_row_bg() {
	global $bgcounter;
	
	if ($bgcounter++%2 == 0)
	{
		return 'firstalt';
	}else{
		return 'secondalt';
	}
}

/* ########################################### FORM #######################################  */
/********************************************************************
dohtml_form_header (admin)
********************************************************************/
function dohtml_form_header($phpscript,$action,$uploadform=0,$addtable=1,$name='name') {
	global $s;
	
	echo "\n";
	echo '<form action="'.$phpscript.'.php" '.cexpr($uploadform,'enctype="multipart/form-data" ','').' name="'.$name.'" method="post">';
	echo '<input type="hidden" name="s" value="'.$s.'">';
	echo '<input type="hidden" name="action" value="'.$action.'">';
	if ($addtable)
	{
		echo '<table border="0">';
	}
}
/********************************************************************
dohtml_form_footer admin
********************************************************************/
function dohtml_form_footer($submitname='Submit',$resetname='Reset') {

	echo '</table>';
	echo '<p><div align="center"><center>';
	echo '<table border="0">';
	echo '<tr><td><p><p align="center"><input type="submit" name="submit" value="   '.$submitname.'    " width="200"></p></p></td>';
	echo '<td><p><p align="center"><input type="reset" name="reset" value="   '.$resetname.'   "></p></p></td>';
	echo '</tr>';
	echo '</table></center></div>';
	echo '</form>';
}
/********************************************************************
dohtml_form_label
********************************************************************/
function dohtml_form_label($title,$value='&nbsp;') {

	echo '<tr class="'.get_row_bg().'" valign="top"><td><p>'.$title.'</p></td><td><p>'.$value.'</p></td></tr>';
}
/********************************************************************
dohtml_form_input
********************************************************************/
function dohtml_form_input($title,$name,$value="",$htmlise=1,$size=35) {

	if ($htmlise)
	{
		$value = htmlspecialchars($value);
	}
	echo '<tr class="'.get_row_bg().'" valign="top"><td><p>'.$title.'</p></td><td><p><input type="text" size="'.$size.'" name="'.$name.'" value="'.$value.'"></p></td></tr>';
}
/********************************************************************
dohtml_form_textarea
********************************************************************/
function dohtml_form_textarea($title,$name,$value="",$rows=4,$cols=40,$htmlise=1) {

	if ($htmlise)
	{
		$value = htmlspecialchars($value);
	}
	echo '<tr class="'.get_row_bg().'" valign="top"><td><p>'.$title.'</p></td><td><p><textarea name="'.$name.'" rows="'.$rows.'" cols="'.$cols.'">'.$value.'</textarea></p></td></tr>';
}
/********************************************************************
dohtml_form_hidden
********************************************************************/
function dohtml_form_hidden($name,$value="",$htmlise=1) {

	if($htmlise)
	{
		$value = htmlspecialchars($value);
	}
	echo '<input type="hidden" name="'.$name.'" value="'.$value.'">';
}
/********************************************************************
dohtml_form_file
********************************************************************/
function dohtml_form_file($title,$name,$maxfilesize=1000000) {

	echo '<tr class="'.get_row_bg().'" valign="top"><td><p>'.$title.'</p></td><td><p><input type="hidden" name="MAX_FILE_SIZE" value="'.$maxfilesize.'"><input type="file" name="'.$name.'"></p></td></tr>';
}
/********************************************************************
dohtml_form_password
********************************************************************/
function dohtml_form_password($title,$name,$value="",$htmlise=1,$size=35) {
	if ($htmlise)
	{
		$value = htmlspecialchars($value);
	}
	echo '<tr class="'.get_row_bg().'" valign="top"><td><p>'.$title.'</p></td><td><p><input type="password" size="'.$size.'" name="'.$name.'" value="'.$value.'"></p></td></tr>';
}
/********************************************************************
dohtml_form_password
********************************************************************/
function dohtml_form_fixedfield($title,$name,$value="",$htmlise=1) {
	if ($htmlise)
	{
		$value = htmlspecialchars($value);
	}
	echo '<tr class="'.get_row_bg().'" valign="top"><td><p>'.$title.'</p></td><td><p><b>'.$value.'</b><input type="hidden" name="'.$name.'" value="'.$value.'"></p></td></tr>';
}
/********************************************************************
dohtml_form_yesno
********************************************************************/
function dohtml_form_yesno($title,$name,$value=1) {
	global $msg_admin_yes,$msg_admin_no;
	
	echo "<tr class='".get_row_bg()."' valign='top'>\n
        <td><p>$title</p></td>\n<td><p>
	$msg_admin_yes<input type='radio' name='$name' value='1' ".cexpr($value==1,'checked','').">
	$msg_admin_no<input type='radio' name='$name' value='0' ".cexpr($value==0 || empty($value),'checked','')."></p></td>\n</tr>";
}
 
/********************************************************************
dohtml_form_selectdate
********************************************************************/
function dohtml_form_selectdate($name,$value="",$range=1) {

	$html .= "<select name='$name'>\n";
	for ($i = 1; $i <= $range; $i++)
	{
		$valueshow = cexpr($i<10,"0$i","$i");
		$html .= "<option value='$valueshow' " . cexpr($i==$value,'selected','') . ">";
		if ($range > 12)
		{
			$html .=  $valueshow;
		}else{
			$html .=  get_monthname($valueshow);
		}
		$html .= "</option>\n";
	}
	$html .= "</select>\n";
	return $html;
}
function dohtml_form_select($title,$name,$options) {

	echo "<tr class='".get_row_bg()."' valign='top'>\n <td><p>$title</p></td>\n<td><p><select name='$name'>\n $options </select> </p></td>\n</tr>";
}

function dohtml_formselected_day($selecname,$day,$null="0") {

	$show = "<select name='$selecname' size='1'>\n";
	if ($null == 1)
	{
		$show.="<option value=''></option>\n";
	}
	for ($i = 1; $i <= 31; $i++)
	{
		$valueshow = cexpr($i<10,"0$i","$i");
		$show .= "<option value='$valueshow' " . cexpr($i==$day,'selected','') . ">$valueshow</option>\n";
	}
	$show .= "</select>\n";
	return $show;
}

function dohtml_formselected_month($selecname,$month) {

	$show ="<select name='$selecname' size='1'>\n";
	for ($i = 1; $i <= 12; $i++)
	{
		$valueshow = cexpr($i<10,"0$i",$i);
		$show .= "<option value='$valueshow' " . cexpr($i==$month,'selected','') . ">". get_monthname($valueshow) . "</option>\n";
	}
	$show .="</select>\n";
	return $show;
}

/********************************************************************
dohtml_form_infobox
********************************************************************/
function dohtml_form_infobox($message) {

	echo "<tr class='".get_row_bg()."' valign='top'>\n<td colspan='2'><p>$message</p></td>\n</tr>";
}

/********************************************************************
dohtml_form_infobox
********************************************************************/###
function dohtml_form_select2($title,$titlecolum,$name,$tablename,$idcolum,$titlecolum,$value=-1,$extra="",$size=0) {
	global $DB_site;
	
	echo "<tr class='".get_row_bg()."' valign='top'>\n<td><p>$title</p></td>\n<td><p><select name='$name' ".cexpr($size!=0," size='$size' ",'').">\n";
	if (!empty($extra))
	{
		if($value == -1)
		{
			echo "<option value='-1' selected>$extra</option>\n";
		}else{
			echo "<option value='' ".cexpr($value==0 || $value=='','selected','').">$extra</option>\n";
		}
	}
	$result = $DB_site->query("SELECT $titlecolum,$idcolum FROM $tablename ORDER BY $titlecolum");
	while ($option = $DB_site->fetch_array($result))
	{
		if ($value == $option[$idcolum])
		{
			echo "<option value='$option[$idcolum]' selected>$option[$titlecolum]</option>\n";
		}else{
			echo "<option value='$option[$idcolum]'>$option[$titlecolum]</option>\n";
		}
	}
	$DB_site->free_result($result);
	echo "</select>\n</p></td>\n</tr>\n";
}

function dohtml_form_cardcategory($access_level,$formname,$selected_catid="",$extra="") {
	global $DB_site,$msg_admin_category,$superuser;
	
	echo "<tr class='".get_row_bg()."' valign='top'>\n<td><p>$msg_admin_category</p></td>\n<td><p><select name='$formname' ".cexpr($size!=0," size='$size'",'').">\n";
	if (!empty($extra))
	{
		echo $extra;
	}
	if ($superuser == 1 || $access_level == 0)
	{
		$catlist = $DB_site->query(" SELECT * FROM vcard_category WHERE cat_subid='' OR cat_subid='0' ORDER BY cat_order ");
		while ($cat = $DB_site->fetch_array($catlist))
		{
			extract($cat);
			$cat_name = stripslashes(htmlspecialchars($cat_name));
			echo "<option value='$cat_id' ".cexpr($selected_catid==$cat_id,'selected','')."> $cat_name</option>\n";
			$subcatlist =$DB_site->query(" SELECT * FROM vcard_category WHERE cat_subid='$cat_id' ORDER BY cat_order ");
			while ($subcat = $DB_site->fetch_array($subcatlist))
			{
				extract($subcat);
				$cat_name = stripslashes(htmlspecialchars($cat_name));
				echo "<option value='$cat_id' ".cexpr($selected_catid==$cat_id,'selected','').">&nbsp;&nbsp; &raquo; $cat_name</option>\n";
			}
			$DB_site->free_result($subcatlist);
		}
		$DB_site->free_result($catlist);
	}else{
		$catinfo = $DB_site->query_first("SELECT * FROM vcard_category WHERE cat_id='$access_id' ");
		$catinfo['cat_name'] = stripslashes(htmlspecialchars($catinfo['cat_name']));
		echo "<option value='$access_id'> $catinfo[cat_name]</option>\n";
	}
	echo "</select>\n</p></td>\n</tr>\n";
}
/* ########################################### FORM #######################################  */

/********************************************************************
Update Options
********************************************************************/
function do_options_update() {
	global $DB_site;
	
	$getsettings = $DB_site->query("SELECT varname,value FROM vcard_setting");
	while ($setting = $DB_site->fetch_array($getsettings))
	{
		$optionstemplate.="\$$setting[varname] = \"".addslashes(str_replace("\"","\\\"",$setting[value]))."\";\n";
	}
	$result = $DB_site->query("UPDATE vcard_template SET template='$optionstemplate' WHERE title='options'");
}

/********************************************************************
Create Thumbnails files: default only
can be is JPEG / PNG
'cause COMPUSERVE probelm.... shit
********************************************************************/
function do_filethumb($filename,$thmprefixname="thm_",$path="") {
	global $gallery_thm_width,$site_image_path;

	$thmfilename = $thmprefixname.$filename;
	$extension = get_file_extension($filename);
	if ($extension == 'jpg' || $extension == 'jpeg')
	{
		$im = imagecreatefromjpeg("$site_image_path/$path$filename");
	}
	if ($extension == 'png')
	{
		$im = imagecreatefrompng("$site_image_path/$path$filename");
	}
	if ($extension == 'gif')
	{
		return false;
	}
	$w 	= imagesx($im);
	$h 	= imagesy($im);
	if ($gallery_thm_width ==0 || empty($gallery_thm_width))
	{
		$gallery_thm_width = 90;
	}
	if ($w > $h)
	{
		$nw = $gallery_thm_width;
		$nh = ($h * $gallery_thm_width)/$w;
	}else{
		$nh = $gallery_thm_width;
		$nw = ($w * $gallery_thm_width)/$h;
	}
	$ni	= imagecreate($nw,$nh);
	imagecopyresized($ni,$im,0,0,0,0,$nw,$nh,$w,$h);
	imagejpeg($ni,"$site_image_path/$path$thmfilename",60);
}
/********************************************************************
Export Style function
********************************************************************/
function escapepipe($text) {

	return str_replace("|||","|| |",$text);
}
/********************************************************************
Read some file from somewhere
********************************************************************/
function get_filecontent($path) {

	if (file_exists($path) == 0)
	{
		return '';
	}else{
		$filesize = filesize($path);
		$filenum = fopen($path,"r");
		$filestuff = fread($filenum,$filesize);
		fclose($filenum);
		return $filestuff;
	}
}

/********************************************************************
upload file
if safemode = on, upload function avaiable only to PHP4
********************************************************************/
function do_fileupload($attachment,$attachment_name,$destination_name,$type,$destination=0) {
	global $DB_site,$safeupload,$site_image_path,$site_music_path;
	global $site_name,$site_url,$site_image_url,$site_music_url,$admin_email,$site_font_face,$timenow;
	global $msg_admin_error_wrongext,$msg_admin_error_upload,$msg_admin_error_attach;
	
	$attachment_name = strtolower($attachment_name);
	$extension = get_file_extension($attachment_name);
	dohtml_errorpage(check_denied_file_extension($extension,$type),$msg_admin_error_wrongext);
	if (file_exists($attachment))
	{
		if (strstr($attachment,'..') != '')
		{
			dohtml_errorpage(1,$msg_admin_error_attach);
			exit;
		}
		if ($destination == 0)
		{
			$path = $site_image_path.'/'.$destination_name;
		}
		if ($destination ==1){
			$path = $site_music_path.'/'.$destination_name;
		}
		if ($safeupload == 1)
		{
			if (function_exists("is_uploaded_file"))
			{
				if (is_uploaded_file($attachment))
				{
					if (move_uploaded_file($attachment,$path))
					{
						//exec("chmod 666 $path"); 
						// 666 work Win. 644 not
						chmod($path,octdec(666));
					}
				}
			}
		}else{
			@copy($attachment,$path) or die($msg_admin_error_upload); 
		}
	}
}

function generateoptions()
{
	global $DB_site;

	$settings=$DB_site->query("SELECT varname,value FROM vcard_setting");
	while ($setting=$DB_site->fetch_array($settings)) {
		$setting[value] = stripslashes($setting[value]);
		$setting[value] = str_replace("\"","\\\"",$setting[value]);
		$template.="\$$setting[varname] = \"".addslashes($setting[value])."\";\n";
	}
	return $template;
}

/********************************************************************
Create a list to SQL query IN ($list)
********************************************************************/
function get_sqlinlist($string) {

	$itemlist = "'$string'";
	$itemlist = eregi_replace(" ","','",$itemlist);
	$itemlist = eregi_replace(" ",",",$itemlist);
	$itemlist = eregi_replace(",''","",$itemlist);
	return $itemlist;
}

function categoryoption($access_id="",$selected_catid="0",$extra="") {
	global $DB_site,$superuser,$msg_admin_all,$msg_admin_category;
	
	if (!empty($extra))
	{
		$html = $extra;
	}
	if ($superuser == 1)
	{
		$catlist = $DB_site->query(" SELECT * FROM vcard_category WHERE cat_subid='' ORDER BY cat_order ");
		while ($cat = $DB_site->fetch_array($catlist))
		{
			extract($cat);
			$cat_name = stripslashes(htmlspecialchars($cat_name));
			$html .= "<option value='$cat_id' ".cexpr($selected_catid==$cat_id,'selected','')."> $cat_name</option>\n";
			$subcatlist =$DB_site->query(" SELECT * FROM vcard_category WHERE cat_subid='$cat_id' ORDER BY cat_order ");
			while ($subcat = $DB_site->fetch_array($subcatlist))
			{
				extract($subcat);
				$cat_name = stripslashes(htmlspecialchars($cat_name));
				$html .= "<option value='$cat_id' ".cexpr($selected_catid==$cat_id,'selected','').">&nbsp;&nbsp; &raquo; $cat_name</option>\n";
			}
			$DB_site->free_result($subcatlist);
		}
		$DB_site->free_result($catlist);
		return $html;
	}elseif ($access_id == 0){
		$catlist = $DB_site->query(" SELECT * FROM vcard_category WHERE cat_subid='' ORDER BY cat_order ");
		while ($cat = $DB_site->fetch_array($catlist))
		{
			extract($cat);
			$cat_name = stripslashes(htmlspecialchars($cat_name));
			$html .= "<option value='$cat_id' ".cexpr($selected_catid==$cat_id,'selected','')."> $cat_name</option>\n";
			$subcatlist =$DB_site->query(" SELECT * FROM vcard_category WHERE cat_subid='$cat_id' ORDER BY cat_order ");
			while ($subcat = $DB_site->fetch_array($subcatlist))
			{
				extract($subcat);
				$cat_name = stripslashes(htmlspecialchars($cat_name));
				$html .= "<option value='$cat_id' ".cexpr($selected_catid==$cat_id,'selected','').">&nbsp;&nbsp; &raquo; $cat_name</option>\n";
			}
			$DB_site->free_result($subcatlist);
		}
		$DB_site->free_result($catlist);
	}elseif ($access_id != 0){
			$catinfo = $DB_site->query_first("SELECT * FROM vcard_category WHERE cat_id='$access_id' ");
			$catinfo['cat_name'] = stripslashes(htmlspecialchars($catinfo['cat_name']));
			$html = "<option value='$access_id'> $catinfo[cat_name]</option>\n";
	}else{
		$html = "";
	}
	return $html;
}

function gethtml_groupoption($cardsgroup_id="") {
	global $DB_site,$superuser;
	
	$query = $DB_site->query(" SELECT cardsgroup_id, cardsgroup_name FROM vcard_cardsgroup ORDER BY cardsgroup_name ");
	while ($cardsgroupinfo = $DB_site->fetch_array($query))
	{
		$cardsgroupinfo['cardsgroup_name'] = stripslashes(htmlspecialchars($cardsgroupinfo['cardsgroup_name']));
		$html .= "<option value='$cardsgroupinfo[cardsgroup_id]'  ".cexpr($cardsgroup_id==$cardsgroupinfo['cardsgroup_id'],'selected','') . ">  $cardsgroupinfo[cardsgroup_name]  </option>\n";
	}
	$DB_site->free_result($query);
	return $html;
}
function gethtml_eventoption($event_id="") {
	global $DB_site,$superuser;
	
	$eventlist = $DB_site->query("SELECT * FROM vcard_event ORDER BY event_month,event_day ASC ");
	while ($event = $DB_site->fetch_array($eventlist))
	{
		extract($event);
		$event_name = stripslashes(htmlspecialchars($event_name));
		$html	.= "<option value='$event_id'>$event_day-$event_dayend/" . get_monthname($event_month,1) ." - $event_name</option>\n";
	}
	$DB_site->free_result($eventlist);
	return $html;
}

/********************************************************************
Check extension (audio/image files)
********************************************************************/
function check_denied_file_extension($extension,$type=1) {

	$ext = strtolower($extension);
	if ($type == 1)
	{ // images only
		if ($ext=='gif' || $ext=='jpg' || $ext=='jpeg' || $ext=='swf' || $ext=='png' || $ext=='rm' ||  $ext=='rpm' ||  $ext=='asf' ||  $ext=='asx' ||  $ext=='wmv' || $ext=='mov' || $ext=='mov' || $ext=='avi'){
			return 0;
		}else{
			return 1;
		}
	}
	if ($type == 2)
	{ // sound only
		if ($ext=='mid' || $ext=='midi' || $ext=='wav' || $ext=='au' || $ext=='rm' || $ext=='ram' || $ext=='ra' || $ext=='rpm' || $ext=='asf' || $ext=='asx' || $ext=='wma' || $ext=='wmx' || $ext=='wmv' || $ext=='mov' || $ext=='qt' || $ext=='mp3' || $ext=='m3u')
		{
			return 0;
		}else{
			return 1;
		}
	}
}


?>
