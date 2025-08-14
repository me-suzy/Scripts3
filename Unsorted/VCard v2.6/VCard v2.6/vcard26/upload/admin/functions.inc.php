<?php
/********************************************************************
Template: inspirated in (phplib)
********************************************************************/
class Template
{
	var $root = '.';
	var $_file = array();
	var $_var_keys = array();
	var $_var_values = array();
	// Public
	function Template($root='.'){
		$this->root = $root;
	}
	function set_file($handle,$file_name='') {
		if (!is_array($handle))
		{
			if (empty($file_name))
			{
				$this->_stop("set_file(): $handle is empty.");
				return false;
			}
			$this->_file[$handle] = $this->_make_filename($file_name);
		}else{
			foreach ($handle as $ar_handle => $ar_file)
			{
				$this->_file[$ar_handle] = $this->_make_filename($ar_file);
			}

			
		}
	}
	function set_var($var_name, $value='') {
		if (!empty($var_name))
		$this->_var_keys[$var_name]= '/'.$this->_quote_var($var_name).'/';
		$this->_var_values[$var_name]= $value;
	}

	function parse($target, $handle, $append = false) {
		if (!is_array($handle))
		{
			$str = $this->_get_replaced_vars($handle);
			($append)? $this->set_var($target, $this->_get_var($target) . $str) : $this->set_var($target, $str);
		}else{
			foreach ($handle as $ar_i => $ar_h)
			{
				$str = $this->_get_replaced_vars($ar_h);
				$this->set_var($target, $str);
			}
		}
		return $str;
	}
	function pparse($target, $handle, $append = false) {
		print $this->parse($target, $handle, $append);
		return false;
	}
	function p($var_name) {
		print $this->_remove_undefined_vars($this->_get_var($var_name));
	}
	function getcard($var_name) {
		return $this->_remove_undefined_vars($this->_get_var($var_name));
	}
	function _get_var($var_name) {
		if (!is_array($var_name))
		{
			return $this->_var_values[$var_name];
		}else{
			foreach ($var_name as $ar_key => $ar_value)
			{
				$result[$ar_key] = $this->_var_values[$ar_key];
			}
			return $result;
		}
	}
	function _remove_undefined_vars($str) {
		$str = preg_replace('/{[^ \t\r\n}]+}/','', $str);
		return $str;
	}
	function _get_replaced_vars($handle) {
		if (!$this->_get_template($handle))
		{
			$this->_stop("_get_replaced_vars(): unable to load $handle.");
			return false;
		}
		$str = $this->_get_var($handle);
		$str = @preg_replace($this->_var_keys, $this->_var_values, $str);
		return $str;
	}
	function _get($var_name) {
		return $this->_remove_undefined_vars($this->_get_var($var_name));
	}
	function _make_filename($file_name) {
		if (substr($file_name, 0, 1) !='/')
		{
			$file_name = $this->root.'/'.$file_name;
		}
		if (!file_exists($file_name))
		$this->_stop("_make_filename(): file $file_name does not exist.");
		return $file_name;
	}
	function _quote_var($var_name) {
		return preg_quote('{'.$var_name.'}');
	}
	function _get_template($handle) {
		if (isset($this->_var_keys[$handle]) and !empty($this->_var_values[$handle]))
			return true;
			if (!isset($this->_file[$handle]))
			{
				$this->_stop("_get_template(): $handle is not a valid handle.");
				return false;
			}
			$file_name = $this->_file[$handle];
			//$str = implode("", @file($file_name));
			$str = fread($fp = fopen($file_name, 'r'), filesize($file_name));
			if (empty($str))
			{
				$this->_stop("_get_template)_: While loading $handle, $file_name does not exist or is empty.");
				return false;
			}
			$this->set_var($handle, $str);
			return true;
	}
	function _stop($msg) {
		$this->last_error = $msg;
		printf("<b>Card Layout Template Error:</b> %s<br>\n", $msg);
		die();
		return false;
	}
}

/********************************************************************
check email formate (zend.com)
********************************************************************/
function mailval($Addr, $Level, $Timeout = 15000) {

	$gTLDs = 'com:net:org:edu:gov:mil:int:arpa:info:biz:name:pro:eu:'; 
	$CCs   = 'ac:ad:ae:af:ag:ai:al:am:an:ao:aq:ar:as:at:au:aw:az:ba:bb:bd:be:bf:'.
		'bg:bh:bi:bj:bm:bn:bo:br:bs:bt:bv:bw:by:bz:ca:cc:cf:cd:cg:ch:ci:'. 
		'ck:cl:cm:cn:co:cr:cs:cu:cv:cx:cy:cz:de:dj:dk:dm:do:dz:ec:ee:eg:'. 
		'eh:er:es:et:fi:fj:fk:fm:fo:fr:fx:ga:gb:gd:ge:gf:gh:gi:gl:gm:gn:'. 
		'gp:gq:gr:gs:gt:gu:gw:gy:hk:hm:hn:hr:ht:hu:id:ie:il:in:io:iq:ir:'. 
		'is:it:jm:jo:jp:ke:kg:kh:ki:km:kn:kp:kr:kw:ky:kz:la:lb:lc:li:lk:'. 
		'lr:ls:lt:lu:lv:ly:ma:mc:md:mg:mh:mk:ml:mm:mn:mo:mp:mq:mr:ms:mt:'. 
		'mu:mv:mw:mx:my:mz:na:nc:ne:nf:ng:ni:nl:no:np:nr:nt:nu:nz:om:pa:'. 
		'pe:pf:pg:ph:pk:pl:pm:pn:pr:pt:pw:py:qa:re:ro:ru:rw:sa:sb:sc:sd:'. 
		'se:sg:sh:si:sj:sk:sl:sm:sn:so:sr:st:su:sv:sy:sz:tc:td:tf:tg:th:'. 
		'tj:tk:tm:tn:to:tp:tr:tt:tv:tw:tz:ua:ug:uk:um:us:uy:uz:va:vc:ve:'. 
		'vg:vi:vn:vu:wf:ws:ye:yt:yu:za:zm:zr:zw:'; 
	$cTLDs = 'com:net:org:edu:gov:mil:co:ne:or:ed:go:mi:';
	$fail = 0; 
	$Addr = strtolower($Addr);
	$UD = explode('@', $Addr);
	if (sizeof($UD) != 2 || !$UD[0]) $fail = 1; 
	$Levels = explode('.', $UD[1]); $sLevels = sizeof($Levels);
	if ($sLevels < 2) $fail = 1; 
	$tld = $Levels[$sLevels-1];
	$tld = ereg_replace("[>)}]$|]$", '', $tld); 
	if (strlen($tld) < 2 || strlen($tld) > 3 && $tld != 'arpa') $fail = 1; 
	$Level--; 
	if ($Level && !$fail)
	{
		$Level--; 
		if (!ereg($tld.':', $gTLDs) && !ereg($tld.':', $CCs)) $fail = 2; 
	}
	if ($Level && !$fail)
	{
		$cd = $sLevels - 2; $domain = $Levels[$cd].".".$tld; 
		if (ereg($Levels[$cd].':', $cTLDs)) { $cd--; $domain = $Levels[$cd].'.'.$domain; } 
	} 
	if ($Level && !$fail)
	{
		$Level--; 
		if (!getmxrr($domain, $mxhosts, $weight)) $fail = 3; 
	}
	if ($Level && !$fail)
	{
		$Level--; 
		while (!$sh && list($nul, $mxhost) = each($mxhosts)) 
		 $sh = fsockopen($mxhost, 25); 
		if (!$sh) $fail = 4; 
	}
	if ($Level && !$fail)
	{
		$Level--;
		set_socket_blocking($sh, false);
		$out = ''; $t = 0;
		while ($t++ < $Timeout && !$out)
		 $out = fgets($sh, 256);
		if (!ereg("^220", $out)) $fail = 5;
	}
	if (isset($sh)) fclose($sh);
		return $fail;
	}
	if (isset($userip)){
	$ip = $userip;}
//MailVal
/********************************************************************
check user Browser (NN,Mozila,IE,Other) and
return proper option
********************************************************************/
function optionbynavigator($ie_option,$ns_option,$other_option='') {
	global $HTTP_USER_AGENT;
	
	$user_agent = strtolower($HTTP_USER_AGENT);
	if (empty($other_option))
	{
		$other_option = $ns_option;
	}
	$IE = eregi('msie',$user_agent); 
	if ($IE == true)
	{
		return $ie_option;
	}
	$NN6 = eregi('gecko',$user_agent); 
	if ($NN6 == true)
	{
		return $ns_option;
	}
	$NN = eregi('mozilla',$user_agent);
	if ($NN == true)
	{
		return $ns_option;
	}else{
		return $other_option;
	}
}
/********************************************************************
Current time
********************************************************************/
function vcdate($time=1) {
	global $site_dateformat,$site_timeoffset;
	
	$timestamp = time();
	$hformat = ($time == 1)? 'H:m' : '';
	$format = ($site_dateformat == 1)? 'd-m-Y' : 'm-d-Y';
	$time = date("$format $hformat",$timestamp+($site_timeoffset)*3600);
	return $time;
}
/********************************************************************
Current Date
********************************************************************/
function get_date_current($ext=0) {
	global $site_dateformat,$site_timeoffset;
	
	$timestamp = time();
	$day= date("d",$timestamp+($site_timeoffset)*3600);
	$month= date("m",$timestamp+($site_timeoffset)*3600);
	$year= date("Y",$timestamp+($site_timeoffset)*3600);
	$month  = ($ext==1)? get_monthname($month,1) : $monthv;
	$date = ($site_dateformat == 1)? "$day - $month - $year" : "$month - $day - $year";
	return $date;
}

function get_day_after($date) {

	list ($year, $month, $day) = split ('[/.-]', $date);
	$sec_pass = mktime() - mktime(0,0,0, $month, $day, $year);
	$day_pass = floor( $sec_pass / 86400); // % div modulo
	return $day_pass;
}

/********************************************************************
custom htmlspecialchars
********************************************************************/
function make_myhtmlentities($text='') {

	$text = str_replace('"', '&quot;', $text);
	$text = str_replace('<', '&lt;', $text); 
	$text = str_replace('>', '&gt;', $text); 
	$text = str_replace("'", '&#039;', $text); 
	
	return $text;
}
function make_undomyhtmlentities($text='') {

	$text = str_replace('&gt;', '>', $text);
	$text = str_replace('&lt;', '<', $text);
	$text = str_replace('&quot;', '"', $text);
	$text = str_replace('&amp;', '&', $text);
	return $text;
}

/********************************************************************
Code Message to vCard code
********************************************************************/
function parse_vcode($message) {
	global $site_name,$site_url,$site_image_url,$site_music_url,$admin_email,$site_font_face,$timenow,$vcardversion,$seed;
	
	$message = ' '.$message;
	$message = stripslashes($message); 			// to remove any slashes caused by magic quotes 
	$message = str_replace('"', "&quot;", $message); // " -> HTML code
	$message = str_replace('<br />', '<br>', $message); 
	$message = str_replace("'", "&acute;", $message); // ' -> HTML code &rsquo; &lsquo;
	$message = str_replace('<br>', " \n", $message); 	// remove nl to form field
	$message = str_replace('<br>', " \r", $message); 	// remove nl to form field
	$message = str_replace('<',"&lt;",$message);
	$message = str_replace('>',"&gt;",$message);
	$message = nl2br($message);
	$message = str_replace('<br />', '<br>', $message); 
	$message = eregi_replace(quotemeta("[b]"),quotemeta("<b>"),$message); // [b] and [/b] for bolding text.
	$message = eregi_replace(quotemeta("[/b]"),quotemeta("</b>"),$message);
	$message = eregi_replace(quotemeta("[i]"),quotemeta("<i>"),$message); // [i] and [/i] for italicizing text.
	$message = eregi_replace(quotemeta("[/i]"),quotemeta("</i>"),$message);
	$message = eregi_replace(quotemeta("[u]"),quotemeta("<u>"),$message); // [u] and [/u] for underling text.
	$message = eregi_replace(quotemeta("[/u]"),quotemeta("</u>"),$message);
	$message = eregi_replace("\\[url\\]www.([^\\[]*)\\[/url\\]", "<a href=\"http://www.\\1\" target=_blank>\\1</a>",$message);
	$message = eregi_replace("\\[url\\]([^\\[]*)\\[/url\\]","<a href=\"\\1\" target=_blank>\\1</a>",$message);
	$message = eregi_replace("\\[url=([^\\[]*)\\]([^\\[]*)\\[/url\\]","<a href=\"\\1\" target=\"_blank\">\\2</a>",$message);
	$message = eregi_replace("\\[email\\]([^\\[]*)\\[/email\\]", "<a href=\"mailto:\\1\">\\1</a>",$message);
	// $message=eregi_replace("\\[img\\]([^\\[]*)\\[/img\\]","<img src=\"\\1\" border=0>",$message);
	// $message=eregi_replace("\\[swf width=([^\\[]*) height=([^\\[]*)\\]([^\\[]*)\\[/swf\\]","<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=4\,0\,2\,0\" width=\"\\1\" height=\"\\2\"><param name=quality value=high><param name=\"SRC\" value=\"\\3\"><embed src=\"\\3\" quality=high pluginspage=\"http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash\" type=\"application/x-shockwave-flash\" width=\"\\1\" height=\"\\2\"></embed></object>", $message);
	$message = str_replace("[seedvc]", "[$vcardversion-$seed]", $message);
	$message = eregi_replace("quote\\]", "quote]", $message);  
	$message = str_replace("[quote]\r\n", "<blockquote>", $message);
	$message = str_replace("[quote]", "<blockquote>", $message);
	$message = str_replace("[/quote]\r\n", "</blockquote>", $message);
	$message = str_replace("[/quote]", "</blockquote>", $message);
	$message = smileit($message);
	return $message;
}
function smileit($string){

	$smiles = array(
	':-)' => '<img src=img/e/smiley.gif>',
	':-(' => '<img src=img/e/sad.gif>',
	':-P' => '<img src=img/e/tong.gif>',
	'8-)' => '<img src=img/e/cool.gif>',
	'8-P' => '<img src=img/e/cooltong.gif>',
	'8-O' => '<img src=img/e/coolmouth.gif>',
	'8-(' => '<img src=img/e/coolsad.gif>',
	'%-)' => '<img src=img/e/dazed.gif>',
	'%-O' => '<img src=img/e/dazedmouth.gif>',
	'%-(' => '<img src=img/e/dazedsad.gif>',
	'%-P' => '<img src=img/e/dazedtong.gif>',
	':-O' => '<img src=img/e/mouth.gif>',
	';-)' => '<img src=img/e/wink.gif>',
	';-O' => '<img src=img/e/winkmouth.gif>',
	';-(' => '<img src=img/e/winksad.gif>',
	';-P' => '<img src=img/e/winktong.gif>' // attention: don´t add the last comma
	);
	foreach ($smiles as $smile => $image)
	{
		$string = str_replace($smile, $image, $string);
	}
	return $string;
}

function vdecode($message='')
{
	$message = str_replace('<br>', "\n", $message); 
	$message = str_replace('<br />',"\n", $message); 
	return $message;
}
/********************************************************************
get file extension
********************************************************************/
function get_file_extension($filename) {

	$filename = strtolower($filename);
	$extension = split("[/\\.]", $filename);
	$n = count($extension)-1;
	$extension = $extension[$n];
	return $extension;

}
/********************************************************************
get file name without extension
********************************************************************/
function removeextension($filename) {

	$arr_basename = explode('.',$filename);
	$name = strtolower($arr_basename[0]);
	return $name;
}

function checkempty($str) {

	return (empty($str))? true : false;
}

function checkfieldempty($value,$errormsg ='Error') {
	global $site_font_face;
	
	if (empty($value))
	{
		print  "<br><p><blockquote><font face='$site_font_face' size='2'><b> $errormsg </b></font></blockquote></p>";
		exit;
	}
}
/********************************************************************
STATS FUNCTIONS   -   STATS FUNCTIONS   -   STATS FUNCTIONS   -   
********************************************************************/
function get_widthpercent($column) {

	$width = (100/$column);
	list($first, $second) = split ('[/.-]', $width);
	$valor = "$first%";
	return $valor;
}
/********************************************************************
MAIL FUNCTIONS   -    MAIL FUNCTIONS   -    MAIL FUNCTIONS   -       
********************************************************************/
function check_emailformate($email) {

	$emailcmpstr = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-.@_1234567890';
	$host = substr($email, strrpos($email, '@') + 1, strlen($email) - strrpos($email, '@')); 
	if (empty($email))
	{
		return false;
	}
	if (strspn($email, $emailcmpstr) != strlen($email))
	{
		return false;
	}
	if (eregi("^[a-z0-9\._-]+@+[a-z0-9\._-]+\.+[a-z]{2,3}$", $email))
	{
		return true;
	}else{
		return false;
	}
	return true; 
}
/* banned email address */
function clean_banned_email($email) {
	global $bannedemaillist;
	
	$email = trim($email);
	$bannedemaillist = trim(strtolower($bannedemaillist));
	$list = explode(',', $bannedemaillist);
	while (list($key,$val) = each($list))
	{
		$val = trim($val);
		$email = str_replace($val,'',$email);
	}
	return $email;
}

/********************************************************************
Function to mail recipient to pickup card Advance Send
********************************************************************/
function sendmail_pickup($recip_email,$recip_name,$sender_email,$sender_name,$message_id) {
	global $mail_recip_subject,$mail_recip_message,$site_prog_url,$admin_email,$mail_format,$CharSet,$todayext,$todaydate,$timenow;
	global $enduser_ip;

	$mail_recip_subject = stripslashes($mail_recip_subject);
	$mail_recip_message = stripslashes($mail_recip_message);
	$temp_mail_recip_message = str_replace('{recipient_name}', $recip_name, $mail_recip_message);
	$temp_mail_recip_message = str_replace('{sender_name}', $sender_name, $temp_mail_recip_message);
	$temp_mail_recip_message = str_replace('{pickup_link}', "$site_prog_url/pickup.php?message_id=$message_id", $temp_mail_recip_message);
	$temp_mail_recip_message = str_replace('{sender_ip}', $enduser_ip, $temp_mail_recip_message);
	$temp_mail_recip_subject = str_replace('{sender_name}', $sender_name, $mail_recip_subject); 
	$temp_mail_recip_subject = str_replace('{recipient_name}', $recip_name, $temp_mail_recip_subject); 
	$charset = $CharSet;
	$server_phpversion  = phpversion();
	$headers = "From: $sender_email\n";
	$headers .= "X-Sender: <$sender_email>\n"; 
	$headers .= "X-Mailer: PHP/$server_phpversion\n"; 	// mailer
	$headers .= "X-Priority: 3\n"; 						// 1-Urgent message! 2-very 3-normal
	$headers .= "Return-Path: <$sender_email>\n";		// Return path for errors
	if ($mail_format == 1)
	{
		$headers .= "Content-Type: text/html; charset=$charset";
	}else{
		$headers .= "Content-Type: text/plain; charset=$charset";
	}
	mail($recip_email, $temp_mail_recip_subject, $temp_mail_recip_message,$headers);
}
/********************************************************************
Function to card copy mail
********************************************************************/
function sendmail_copy($recipients_mails,$recipients_names,$sender_email,$sender_name,$delivery_date,$message_id) {
	global $mail_copy_subject,$mail_copy_message,$site_url,$site_prog_url,$admin_email,$mail_format,$CharSet,$todayext,$todaydate,$timenow;
	global $enduser_ip;

	$mail_copy_subject = stripslashes($mail_copy_subject);
	$mail_copy_message = stripslashes($mail_copy_message);
	$temp_mail_copy_message = str_replace('{recipients_names}', $recipients_names, $mail_copy_message);
	$temp_mail_copy_message = str_replace('{recipients_mails}', $recipients_mails, $temp_mail_copy_message);
	$temp_mail_copy_message = str_replace('{sender_name}', $sender_name, $temp_mail_copy_message);
	$temp_mail_copy_message = str_replace('{sender_email}', $sender_name, $temp_mail_copy_message);
	$temp_mail_copy_message = str_replace('{site_url}', $site_url, $temp_mail_copy_message);
	$temp_mail_copy_message = str_replace('{delivery_date}', $delivery_date, $temp_mail_copy_message);
	$temp_mail_copy_message = str_replace('{timenow}', $timenow, $temp_mail_copy_message);
	$temp_mail_copy_message = str_replace('{pickup_url}', "$site_prog_url/pickup.php?message_id=$message_id", $temp_mail_copy_message);
	$temp_mail_copy_message = str_replace('{sender_ip}', $enduser_ip, $temp_mail_copy_message);
	$temp_mail_copy_subject = str_replace('{sender_name}', $sender_name, $mail_copy_subject); 
	$charset = $CharSet;
	$server_phpversion  = phpversion();
	$headers = "From: $sender_email\n";
	$headers .= "X-Sender: <$sender_email>\n"; 
	$headers .= "X-Mailer: PHP/$server_phpversion\n"; 	// mailer
	$headers .= "X-Priority: 3\n"; 						// 1-Urgent message! 2-very 3-normal
	$headers .= "Return-Path: <$sender_email>\n";		// Return path for errors
	if ($mail_format == 1)
	{
		$headers .= "Content-Type: text/html; charset=$charset";
	}else{
		$headers .= "Content-Type: text/plain; charset=$charset";
	}
	mail($sender_email, $mail_copy_subject, $temp_mail_copy_message,$headers);
}
/********************************************************************
Function to mail sender about notification
********************************************************************/
function sendmail_notify($sender_email,$sender_name,$recipient_name,$recipient_email) {
	global $mail_sender_subject,$mail_sender_message,$admin_email,$todayext,$todaydate,$timenow,$mail_format,$CharSet;
	global $enduser_ip;

	$mail_sender_subject = stripslashes($mail_sender_subject);
	$mail_sender_message = stripslashes($mail_sender_message);
	$today_date = $timenow;
	$temp_mail_sender_subject = str_replace('{recipient_name}', $recipient_name, $mail_sender_subject);
	$temp_mail_sender_subject = str_replace('{sender_name}', $sender_name, $temp_mail_sender_subject);
	$temp_mail_sender_message = str_replace('{recipient_name}', $recipient_name, $mail_sender_message);
	$temp_mail_sender_message = str_replace('{sender_name}', $sender_name, $temp_mail_sender_message);
	$temp_mail_sender_message = str_replace('{sender_ip}', $enduser_ip, $temp_mail_sender_message);
	$temp_mail_sender_message = str_replace('{today_date}', $today_date, $temp_mail_sender_message);
	$temp_mail_sender_message = str_replace('{timenow}', $timenow, $temp_mail_sender_message);
	$charset = $CharSet;
	$server_phpversion  = phpversion();
	$headers = "From: $recipient_email\n";
	$headers .= "X-Sender: <$recipient_email>\n"; 
	$headers .= "X-Mailer: PHP/$server_phpversion\n"; 	// mailer
	$headers .= "X-Priority: 3\n"; 						// 1-Urgent message! 2-very 3-normal
	$headers .= "Return-Path: <$recipient_email>\n";  	// Return path for errors
	if ($mail_format == 1)
	{
		$headers .= "Content-Type: text/html; charset=$charset";
	}else{
		$headers .= "Content-Type: text/plain; charset=$charset";
	}
	mail($sender_email, $temp_mail_sender_subject,$temp_mail_sender_message,$headers);
}
/********************************************************************
Function to mail a friend
********************************************************************/
function send_emailfriend($sender_email,$sender_name,$recipient_name,$recipient_email,$message='') {
	global $site_url,$mail_emailfriend_subject,$mail_emailfriend_message,$admin_email,$todayext,$todaydate,$timenow,$mail_format,$CharSet;
	global $enduser_ip;
	
	$today_date = $timenow;
	$charset = $CharSet;
		
	$temp_mail_emailfriend_subject = stripslashes($mail_emailfriend_subject);
	$temp_mail_emailfriend_subject = str_replace('{recipient_name}', $recipient_name, $temp_mail_emailfriend_subject);
	$temp_mail_emailfriend_subject = str_replace('{sender_name}', $sender_name, $temp_mail_emailfriend_subject);
	
	$temp_mail_emailfriend_message = stripslashes($mail_emailfriend_message);
	$temp_mail_emailfriend_message = str_replace('{site_url}', $site_url, $temp_mail_emailfriend_message);
	$temp_mail_emailfriend_message = str_replace('{recipient_name}', $recipient_name, $temp_mail_emailfriend_message);
	$temp_mail_emailfriend_message = str_replace('{recipient_email}', $recipient_email, $temp_mail_emailfriend_message);
	$temp_mail_emailfriend_message = str_replace('{sender_name}', $sender_name, $temp_mail_emailfriend_message);
	$temp_mail_emailfriend_message = str_replace('{sender_email}', $sender_email, $temp_mail_emailfriend_message);
	$temp_mail_emailfriend_message = str_replace('{today_date}', $today_date, $temp_mail_emailfriend_message);
	$temp_mail_emailfriend_message = str_replace('{user_message}', $message, $temp_mail_emailfriend_message);
	$temp_mail_emailfriend_message = str_replace('{sender_ip}', $enduser_ip, $temp_mail_emailfriend_message);
	$server_phpversion  = phpversion();
	$headers = "From: $sender_email\n";
	$headers .= "X-Sender: <$sender_email>\n"; 
	$headers .= "X-Mailer: PHP/$server_phpversion\n"; 	// mailer
	$headers .= "X-Priority: 3\n"; 						// 1-Urgent message! 2-very 3-normal
	$headers .= "Return-Path: <$sender_email>\n";  		// Return path for errors
	if ($mail_format == 1)
	{
		$headers .= "Content-Type: text/html; charset=$charset";
	}else{
		$headers .= "Content-Type: text/plain; charset=$charset";
	}
	mail($recipient_email, $temp_mail_emailfriend_subject,$temp_mail_emailfriend_message,$headers);
}
/********************************************************************
Function to mail userpassword (Address book)
********************************************************************/
function abook_send_password($ab_email) {
	global $DB_site;
	global $mail_abpwd_subject,$mail_abpwd_message,$admin_email,$todayext,$todaydate,$timenow,$mail_format,$CharSet;
	
	$mail_abpwd_subject = stripslashes($mail_abpwd_subject);
	$mail_abpwd_message = stripslashes($mail_abpwd_message);
	$retrievepwd = $DB_site->query_first(" SELECT * FROM vcard_abook WHERE (ab_email='".addslashes($ab_email)."') ");
	if ($retrievepwd)
	{
		$today_date 	= $timenow;
		$ab_email 	= stripslashes($retrievepwd['ab_email']);
		$ab_realname 	= stripslashes($retrievepwd['ab_realname']);
		$ab_username 	= stripslashes($retrievepwd['ab_username']);
		$ab_password 	= stripslashes($retrievepwd['ab_password']);
		// message subject
		$temp_mail_abpwd_subject = str_replace( "{abook_realname}", $recipient_name, $mail_abpwd_subject);
		// message body
		$temp_mail_abpwd_message = str_replace('{abook_realname}', $ab_realname, $mail_abpwd_message);
		$temp_mail_abpwd_message = str_replace('{abook_username}', $ab_username, $temp_mail_abpwd_message);
		$temp_mail_abpwd_message = str_replace('{abook_password}', $ab_password, $temp_mail_abpwd_message);
		$temp_mail_abpwd_message = str_replace('{abook_email}', $ab_email, $temp_mail_abpwd_message);
		$temp_mail_abpwd_message = str_replace('{today_date}', $today_date, $temp_mail_abpwd_message);
		$temp_mail_abpwd_message = str_replace('{timenow}', $timenow, $temp_mail_abpwd_message);
		$charset = $CharSet;
		$server_phpversion  = phpversion();
		$headers = "From: $admin_email\n";
		$headers .= "X-Sender: <$admin_email>\n"; 
		$headers .= "X-Mailer: PHP/$server_phpversion\n"; 	// mailer
		$headers .= "X-Priority: 3\n"; 						// 1-Urgent message! 2-very 3-normal
		$headers .= "Return-Path: <$admin_email>\n";  		// Return path for errors
		if ($mail_format == 1)
		{
			$headers .= "Content-Type: text/html; charset=$charset";
		}else{
			$headers .= "Content-Type: text/plain; charset=$charset";
		}
		mail($ab_email, $temp_mail_abpwd_subject,$temp_mail_abpwd_message,$headers);
	}
}
/********************************************************************
Function data to advance send
********************************************************************/
function save_data_cardcopy($recip_emails,$recip_names,$sender_name,$sender_email,$card_file,$card_id,$card_imgthm,$card_cat,$card_stamp, $card_message,$card_sig,$card_heading,$card_poem,$card_sound,$card_background,$card_color,$card_template,$card_fontface,$card_fontcolor,$card_fontsize,$card_notify,$card_tosend) {
	global $DB_site,$MsgRecpEmail,$MsgRecpName;

	$message_id = make_idmessage();
	$card_sent = 0;
	$card_tosend = make_date_form2db($card_tosend);
	$delivery_date = get_date_readable($card_tosend);
	sendmail_copy($recip_emails,$recip_names,$sender_email,$sender_name,$delivery_date,$message_id);
	$card_sent = 1;
	$card_notify = 0;
	$recip_email = addslashes($MsgRecpEmail);
	$recip_name	= addslashes($MsgRecpName);
	$query = ("INSERT
		INTO vcard_user (
		card_date,sender_name,sender_email,recip_name,recip_email,card_file,card_id,card_stamp,	card_message,card_sig,card_heading,card_sound,card_poem,card_background,card_color,card_template,card_fontface,card_fontcolor,card_fontsize,message_id,card_notify,card_tosend,card_sent)
		VALUES (CURDATE(),'".addslashes($sender_name)."','".addslashes($sender_email)."','".addslashes($sender_name)."','".addslashes($sender_email)."','".addslashes($card_file)."','".addslashes($card_id)."','".addslashes($card_stamp)."','".addslashes($card_message)."','".addslashes($card_sig)."','".addslashes($card_heading)."','".addslashes($card_sound)."','".addslashes($card_poem)."','".addslashes($card_background)."','".addslashes($card_color)."','".addslashes($card_template)."','".addslashes($card_fontface)."','".addslashes($card_fontcolor)."','".addslashes($card_fontsize)."','".addslashes($message_id)."','".addslashes($card_notify)."','".addslashes($card_tosend)."','".addslashes($card_sent)."')
		 ");
	$result = $DB_site->query($query);
	if (!empty($card_id))
	{
		save_data_stats($card_id,$card_stamp,$card_poem,$card_sound,$card_background,$card_template);
	}
}
function sign_attach_message($attach_id,$message_id){
	global $DB_site;

	if (!empty($attach_id) && !empty($message_id))
	{
		$DB_site->query("UPDATE vcard_attach SET messageid='$message_attach_id' WHERE attach_id='$attach_id' ");
	}
	
}
/********************************************************************
Function data to advance send
********************************************************************/
function save_data_card($recip_email,$recip_name,$sender_name,$sender_email,$card_file,$card_id,$card_cat,$card_stamp, $card_message,$card_sig,$card_heading,$card_poem,$card_sound,$card_background,$card_color,$card_template,$card_fontface,$card_fontcolor,$card_fontsize,$card_notify,$card_tosend) {
	global $DB_site,$antispam_check;
	global $mail_recip_subject,$mail_recip_message,$admin_email;
	global $receive_newsletter;

	$message_id = make_idmessage();
	$card_sent = 0;
	$card_tosend = make_date_form2db($card_tosend);
	if ($card_tosend <= date ("Y-m-d"))
	{
		sendmail_pickup($recip_email,$recip_name,$sender_email,$sender_name,$message_id);
		$card_sent = 1;
	}
	$query = ("INSERT
		INTO vcard_user (card_date,sender_name,sender_email,recip_name,recip_email,card_file,card_id,card_stamp,card_message,card_sig,card_heading,card_sound,card_poem,card_background,card_color,card_template,card_fontface,card_fontcolor,card_fontsize,message_id,card_notify,card_tosend,card_sent)
		VALUES (CURDATE(),'".addslashes($sender_name)."','".addslashes($sender_email)."','".addslashes($recip_name)."','".addslashes($recip_email)."','".addslashes($card_file)."','".addslashes($card_id)."','".addslashes($card_stamp)."','".addslashes($card_message)."','".addslashes($card_sig)."','".addslashes($card_heading)."','".addslashes($card_sound)."','".addslashes($card_poem)."','".addslashes($card_background)."','".addslashes($card_color)."','".addslashes($card_template)."','".addslashes($card_fontface)."','".addslashes($card_fontcolor)."','".addslashes($card_fontsize)."','".addslashes($message_id)."','".addslashes($card_notify)."','".addslashes($card_tosend)."','".addslashes($card_sent)."')
		");
	$result = $DB_site->query($query);
	
	if ($receive_newsletter == 1)
	{
		save_data_email($sender_email,$sender_name);
	}
	if (!empty($card_id))
	{
		save_data_stats($card_id,$card_stamp,$card_poem,$card_sound,$card_background,$card_template);
	}
	if ($antispam_check ==1)
	{
		spammer_killer();
	}
	return $message_id;
}
/********************************************************************
insert info into card stat database
********************************************************************/
function save_data_stats($card_id,$card_stamp,$card_poem,$card_sound,$card_background,$card_template) {
	global $DB_site,$site_timeoffset;
	
	$timestamp = time();
	$logdate = date("Y-m-d G:i:s",$timestamp+($site_timeoffset)*3600);
	$query = ("INSERT
		INTO vcard_stats ( date, card_id, card_stamp, card_poem, card_sound, card_background, card_template)
		VALUES ('$logdate','".addslashes($card_id)."','".addslashes($card_stamp)."','".addslashes($card_poem)."','".addslashes($card_sound)."','".addslashes($card_background)."','".addslashes($card_template)."')");
	$result = $DB_site->query($query);
	spam_inserdata();
}
function spam_inserdata() {
	global $DB_site,$enduser_ip;
	
	$DB_site->query("INSERT INTO vcard_spam VALUES (NULL,'$enduser_ip','".time()."') ");
}
/********************************************************************
save log email address
********************************************************************/
function save_data_email($email,$name='') {
	global $DB_site,$site_timeoffset;
	
	$email = strtolower($email);
	$checkmail = $DB_site->query_first("SELECT email FROM vcard_emaillog WHERE email='".addslashes($email)."' ");
	if (!$checkmail)
	{
		$timestamp = time();
		$logdate = date("Y-m-d G:i:s",$timestamp+($site_timeoffset)*3600);
		$result = $DB_site->query("INSERT INTO vcard_emaillog ( date, name, email) VALUES ('$logdate','".addslashes($name)."','".addslashes($email)."') ");
	}
	$DB_site->free_result($checkmail);
}
function save_data_search($str='') {
	global $DB_site,$site_timeoffset;
	
	$str = eregi_replace("%'",'',$str);
	$str = eregi_replace("'%",'',$str);
	$str = trim(addslashes($str));
	if (!empty($str))
	{
		$timestamp = time();
		$logdate = date("Y-m-d G:i:s",$timestamp+($site_timeoffset)*3600);
		$result = $DB_site->query("INSERT INTO vcard_searchlog ( search_date, search_word) VALUES ('$logdate','".addslashes($str)."')");
	}
}

/********************************************************************
DABASE FUNCTIONS   -   DABASE FUNCTIONS   -   DABASE FUNCTIONS   -   
********************************************************************/
function cexpr($expression,$return_true,$return_false) {
	if ($expression == 0)
	{
		return $return_false;
	}else{
		return $return_true;
	}
}
/********************************************************************
HTML CODE   -   HTML CODE   -   HTML CODE   -   HTML CODE   -   HTML 
********************************************************************/
function dohtml_body($baseurl,$file='',$bgcolor="FFFFFF",$text="000000",$link="0000FF",$vlink="800080",$alink="FF0000",$topmargin="0",$leftmargin="0") {
	global $site_image_url;

	$filepath = $baseurl.'/'.$file;
	echo "\n<body ". cexpr(!empty($file),"background='$baseurl/$file'",'') ." bgcolor='#$bgcolor' text='#$text' link='#$link' vlink='#$vlink' alink='#$alink' topmargin='$topmargin' leftmargin='$leftmargin' marginwidth='$leftmargin' marginheight='$topmargin'>\n";
}
function html_body($baseurl,$file='',$bgcolor="FFFFFF",$text="000000",$link="0000FF",$vlink="800080",$alink="FF0000",$topmargin="0",$leftmargin="0") {
	global $site_image_url;

	$filepath 	= $baseurl.'/'.$file;
	$show 		= "\n<body ". cexpr(!empty($file),"background='$baseurl/$file'",'') ." bgcolor='#$bgcolor' text='#$text' link='#$link' vlink='#$vlink' alink='#$alink' topmargin='$topmargin' leftmargin='$leftmargin' marginwidth='$leftmargin' marginheight='$topmargin'>\n";
	return $show;
}
function get_html_stamp($base_url,$file,$link,$alt) {
	global $site_image_url;
	
	$vspace = 1;
	$hspace = 6;
	$border = 0;
	
	if (!empty($file))
	{
		if (eregi('http://',$file))
		{
			$file_url = $file;
		}else{
			$file_url = $base_url.'/'.$file;
		}
		$html = '<a href="'.$link.'"><img src="'.$file_url.'" border="'.$border.'" alt="'.$alt.'" hspace="'.$hspace.'" vspace="'.$vspace.'"></a>';
	}else{
		$html = '';
	}
	return $html;
}
function get_html_image($baseurl,$file,$width,$height) {
	global $DB_site,$site_image_url,$site_image_path,$site_prog_url;
	global $user_flash_width,$user_flash_height;
	
	if ($width == 0 || empty($width))	{ $width = $user_flash_width;	}
	if ($height == 0 || empty($height))	{ $height = $user_flash_height; }
	if (eregi('attachment.php',$file))
	{
		$filepath = $site_prog_url.'/'.$file;
		$narray = get_array_from_url($file,'attachment.php?');
		$file = $narray['file'];
	}elseif (eregi('http://',$file)){
		$filepath = $file;
	}else{
		$filepath = $baseurl.'/'.$file;
	}
	$type = get_file_extension($file);
	// MACROMEDIA FLASH
	if ($type == 'swf')
	{
		$html = '<!-- IE --><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0" width="'.$width.'" height="'.$height.'"><param name="movie" value="'.$filepath.'"><param name="quality" value="high"><param name="LOOP" value="false">
			<!-- NN --><embed quality="high" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" src="'.$filepath.'" type="application/x-shockwave-flash" width="'.$width.'" height="'.$height.'" loop="false"><noembed><a href="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash">Get Macromedia Flash Player NOW!</a></noembed></embed>
		</object>';
	}
	// REAL VIDEO
	elseif ($type =='ram' || $type=='ra' || $type =='rm' || $type =='rpm')
	{
		$html = '<!-- IE --><object id="videoie" classid="clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA" width="'.$width.'" height="'.$height.'" hspace="0" vspace="0" align="top"><param name="SRC" value="'.$filepath.'"><param name="type" value="audio/x-pn-realaudio"><param name="console" value="videoie"><param name="controls" value="imagewindow"><param name="autostart" value="true">
				<!-- NN --><embed name="videoNN" type="audio/x-pn-realaudio" pluginspage="http://www.real.com/player" src="'.$filepath.'" width="'.$width.'" height="'.$height.'" hspace="0" vspace="0" border="0" controls="ImageWindow" autostart="true" loop="false" console="ClipNN"><noembed><a href="http://www.real.com/player/">Get Real Audio Player NOW!</a></noembed></embed>
			</object>';
	}
	// WINDOWS MEDIA
	elseif ($type =='asf' || $type=='asx' || $type =='wmv' || $type=='wma')
	{
		$html = '<!-- IE --><object id="MPlay1" classid="CLSID:22d6f312-b0f6-11d0-94ab-0080c74c7e95" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,4,5,715" standby="Loading Microsoft® Windows® Media Player components..." type="application/x-oleobject" width="'.$width.'" height="'.$height.'"><param name="FileName" value="'.$filepath.'"><param name="ShowDisplay" value="FALSE"><param name="ShowStatusBar" value="TRUE"><param name="StatusBar" value="True"><param name="AnimationAtStart" 	value="True"><param name="ShowAudioControls" value="True"><param name="ShowPositionControls" value="False"><param name="ShowControls" value="False"><param name="AutoSize" value="TRUE"><param name="AutoStart" value="TRUE"><param name="AutoRewind" value="TRUE">
			<!-- NN --><embed width="'.$width.'" height="'.$height.'" filename="'.$filepath.'" src="'.$filepath.'" pluginspage="http://www.microsoft.com/windows/mediaplayer/download/default.asp" name="MPlay1" type="video/x-ms-asf-plugin" autostart="1" showstatusbar="0" showdisplay="0" autosize="0" showcontrols="0" autorewind="1" statusbar="True" animationatstart="True" showaudiocontrols="True" showpositioncontrols="False"></embed>
			</object>';
	}
	// QUICKTIME
	elseif ($type =='mov' || $type =='qt')
	{
		$html ='<!-- IE --><object classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" codebase="http://www.apple.com/qtactivex/qtplugin.cab" width="'.$width.'" height="'.$height.'"><param name="src" value="'.$filepath.'"><param name="autoplay" 	value="true"><param name="controller" value="true">
				<!-- NN --><embed src="'.$filepath.'" width="'.$width.'" height="'.$height.'" name="Get QuickTime" type="video/quicktime" loop="true" cache="true" controller="true" pluginspage="http://www.apple.com/quicktime/download/" autoplay="true" kioskmode="true">
			</object>';
	}
	// WEB IMAGE
	elseif ($type=='jpeg' || $type=='jpg' || $type=='gif' || $type=='png')
	{
		$html = '<img src="'.$filepath.'" border="0" alt="">';
	}
	// TEXT FILE
	elseif ( $type=='jav' || $type=='txt')
	{
		$file_path = $site_image_path.'/'.str_replace('./','',str_replace('../','',$file));
		//echo $file_path;
		$filesize = @filesize($file_path);
		$filenum = @fopen($file_path,"r");
		$filestuff = @fread($filenum,$filesize);
		$html = $filestuff;
	}else{
		$html = '';
	}
	return $html;
}
function get_html_music($baseurl,$file) {
	global $site_music_url;
	
	if (eregi('http://',$file))
	{
		$filepath = $file;
	}else{
		$filepath = $baseurl.'/'.$file;
	}
	
	$type = get_file_extension($file);
	// REAL AUDIO
	if ($type=='ram' || $type=='ra' || $type=='rm' || $type=='rpm')
	{
		$html ='<!-- IE --><object id="RAOCX" classid="clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA" height="35" width="180"><param name="SRC" value="'.$filepath.'"><param name="AUTOSTART" value="-1"><param name="SHUFFLE" value="0"><param name="PREFETCH" value="0"><param name="NOLABELS" value="-1"><param name="CONTROLS" value="ControlPanel"><param name="LOOP" value="0"><param name="NUMLOOP" value="0"><param name="CENTER" value="0"><param name="MAINTAINASPECT" value="0"><param name="BACKGROUNDCOLOR" value="#000000">
			<!-- NN --><embed type ="audio/x-pn-realaudio" src="'.$filepath.'" height="35" width="180" controls="ControlPanel" autostart="true" console="Clip1"></embed>
			</object>';
	}
	// WINDOWS MEDIA
	elseif ($type=='asx' || $type=='wma' || $type=='asf' || $type=='wmv' || $type=='wma')
	{
		$html ='<!-- IE --><object name="MediaPlayer" id="MediaPlayer" classid="clsid:22D6F312-B0F6-11D0-94AB-0080C74C7E95" codebase="http://activex.microsoft.com/activex/%20%20%20controls/mplayer/en/nsmp2inf.cab#Version=5,1,52,701" standby="Loding..." type="application/x-oleobject" width="203" height="24" viewastext><param name="FileName" value="'.$filepath.'"><param name="AutoStart" value="True"><param name="TransparentAtStart" value="True"><param name="ShowStatusBar" value="1"><param name="ShowControls" value="1"><param name="ShowDisplay" value="0"><param name="AutoSize" value="0"><param name="AnimationAtStart" value="0">
			<!-- NN --><embed type="application/x-mplayer2" id="MediaPlayer" pluginspage="http://www.microsoft.com/windows/mediaplayer/download/default.asp" width="203" height="24" src="'.$filepath.'" autostart="1" transparentatstart="0" showcontrols="1" showdisplay="0" showstatusbar="1" animationatstart="0"></embed>
			</object>';
	}
	// MP3
	elseif ($type=='mp3' || $type=='m3u')
	{
		$html ='<embed src="'.$filepath.'" width="144" height="60"  pluginspage="http://www.winamp.com/" autostart="true" hidden="false"></embed>';
	}
	// QUICKTIME
	elseif ($type=='mov' || $type=='qt')
	{
		$html ='<!-- IE --><object classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" codebase="http://www.apple.com/qtactivex/qtplugin.cab" width="144" height="16"><param name="src" value="'.$filepath.'"><param name="autoplay" value="true"><param name="controller" value="false">
			<!-- NN --><embed src="'.$filepath.'" width="144" height="16" name="Get QuickTime" type="video/quicktime" loop="true" cache="true" controller="false" pluginspage="http://www.apple.com/quicktime/download/" autoplay="true" kioskmode="true">
		</object>';
	}
	// WEB MEDIA
	elseif ($type=='mid' || $type=='midi' || $type=='wav' || $type=='au')
	{
		$html = '<!-- NN --><embed src="'.$filepath.'" width="144" height="60" autostart="true" loop="true">
		<!-- IE --><noembed><bgsound src="'.$filepath.'" loop="infinite"></noembed>
		</embed>';
	}
	// NULL
	else
	{
		$html ='';
	}
	return $html;
}
function make_html_hiddenfield($name,$value) {

	echo '<input type="hidden" name="'.$name.'" value="'.$value.'">';
}
function get_html_hiddenfield($name,$value,$htmlise=1) {
	if ($htmlise)
	{
		$value = htmlspecialchars($value);
	}
	return '<input type="hidden" name="'.$name.'" value="'.$value.'">'."\n";
}
function get_pass_hidden_vars($action='') 
{
	global $HTTP_POST_VARS; 

	foreach ($HTTP_POST_VARS as $name => $value) 
    {
		if($name != 'action' && $name!='preview' && $name!='sendnow')
		{
        	$hidden .= '<input name="'.$name.'" type="hidden" value="'.$value.'">'."\n";
		}
    };
	if (!empty($action)){
		$hidden .=  "<input name='action' type='hidden' value='$action'>\n";
	}
    return $hidden;
}
function get_html_font($face='Arial',$size='',$bold='0',$center='0',$text) {

	$show = '<font face="'.$face.'"';
	if (!empty($size)){	$show .= ' size="'.$size.'"';}
	$show .= '>';
	if ($center == 1) {	$show .= '<center>';}
	if ($bold == 1) {$show .= '<b>';}
	$show .= $text;
	if ($bold == 1) {$show .= '</b>';}
	if ($center == 1){$show .= '</center>';}
	$show .= '</font>';
	return $show;
}

function make_html_startfont($size) {
	global $site_font_face;
	
	echo '<font face="'.$site_font_face.'" size="'.$size.'">';
}
function get_html_formselector_advdate($range,$format,$formname,$value='') {
	global $MsgChooseDateImmediate;
	
	$show  = '<select name="'.$formname.'" size="1">';
	$show .= '<option value="'.date("Y-m-d").'">'.$MsgChooseDateImmediate.'</option>';
	$counter = 0;
	$currentDay = date("d")+1;
	$currentMonth = date("m");
	$currentYear = date("Y");
	for ($currentDay; $counter <= $range; $currentDay++)
	{
		if ($currentDay == 32)
		{
			$currentMonth = $currentMonth + 1;
			if ($currentMonth > 12)
			{
				$currentMonth = 1;
				$currentYear = $currentYear + 1;
			}
			$currentDay = 1;
		}
		if (checkdate($currentMonth,$currentDay,$currentYear))
		{
			$show  .= "<option value=\"$currentYear-$currentMonth-$currentDay\" ".cexpr($value=="$currentYear-$currentMonth-$currentDay",'selected','').">";
			if ($format == 1)
			{
				$show .= "$currentDay  -  ".get_monthname($currentMonth,1)." -  $currentYear</option>\n";
			}else{
				$show .= get_monthname($currentMonth,1)."  -  $currentDay  -  $currentYear</option>\n";
			}
			$counter = $counter + 1;
		}
	}
	$show .= '</select>';
	return $show;
}
/********************************************************************
month number to month name / section 1 - user / section 0 - admin
********************************************************************/
function get_monthname($month,$section='') {
	global $MsgMonthNames,$msg_monthnames;
	
	$realmonth = $month - 1;
	if ($section == 1)
	{
		return $MsgMonthNames[$realmonth];
	}else{
		return $msg_monthnames[$realmonth];
	}
}

function make_error_page($expression,$message,$homelink="0") {
	global $MsgHome,$templatecache;
	
	if ($expression)
	{
		global $site_image_url,$site_body_bgimage,$site_body_bgcolor,$site_body_text,$site_body_link,$site_body_vlink,$site_body_alink,$site_body_marginwidth,$site_body_marginheight;
		global $header,$footer,$headinclude,$site_font_face,$site_prog_url,$MsgBack;
		global $dropdownlist,$categories_textlist, $calendar_list,$topx_list,$topx,$vcardversion,$timenow,$todaydate,$todayext;
		$errormessage = $message;
		if ($homelink == 1)
		{
			$buttonbackedit	= "<p align='center'><a href='./'><b>$MsgHome</b></a></p>";
		}else{
			$buttonbackedit	= "<form action='create.php' method='POST'>".
			get_pass_hidden_vars('edit') .
			"<input type='submit' value='   $MsgBack  ' width='200'></form>";
		}
		$headinclude .= "<script language=\"JavaScript\" src=\"script.js\"></script>";
		$htmlbody = html_body($site_image_url,$site_body_bgimage,$site_body_bgcolor,$site_body_text,$site_body_link,$site_body_vlink,$site_body_alink,$site_body_marginwidth,$site_body_marginheight);
		eval("make_output(\"".get_template("errorpage")."\");");
		exit;
	}
}
/********************************************************************
get template from DB
********************************************************************/
function get_template($templatename,$escape="1",$comment="0") {
	global $templatecache,$DB_site;
	
	$template = $templatecache[$templatename];
	if ($escape == 1)
	{
		$template	= addslashes($template);
		$template	= str_replace("\\'","'",$template);
	}
	$comment = 0;
	if ($comment == 1 && $templatename!='phpinclude')
	{
		return "<!-- TEMPLATE: $templatename -->\n$template\n<!-- /TEMPLATE: $templatename -->\n";
	}
	return $template;
}
/********************************************************************
Replace Variables (vcard_replace/replacement)
********************************************************************/
function get_replaced_vars($text) {
	global $DB_site;
	
	static $vars;
	if (connection_status()) { exit; }
	if (!isset($vars))
	{
		$vars = $DB_site->query("SELECT findword,replaceword FROM vcard_replace ORDER BY replace_id DESC ");
	}else{
		$DB_site->data_seek(0,$vars);
	}
	while ($var = $DB_site->fetch_array($vars))
	{
		if (!empty($var['findword']))
		{
			$var['replaceword'] = stripslashes($var['replaceword']);
			$text = str_replace($var['findword'],$var['replaceword'],$text);
		}
	}
	$DB_site->free_result($vars);
	return $text;
}
/********************************************************************
Out Put template content with replaced vars
********************************************************************/
function make_output($vartext) {
	echo get_replaced_vars($vartext);
	flush();
}
/********************************************************************
Cache templates for less resource use
********************************************************************/
function cache_templates($templateslist) {
	global $templatecache,$DB_site;
	
	$templateslist = str_replace(',', "','", $templateslist);
	$temps = $DB_site->query(" SELECT template,title FROM vcard_template WHERE title IN ('$templateslist') ");
	// cache templates
	while ($temp = $DB_site->fetch_array($temps))
	{
		$templatecache["$temp[title]"] = stripslashes($temp['template']);
	}
	unset($temp);
	$DB_site->free_result($temps);
}
$seed = "178"."i"."10"."."."18"."27";
/********************************************************************
Cache itens for less resource use
********************************************************************/
function cache_vcard_pieces($cachedlist) {
	global $vcardcache,$DB_site;

	$now = time();
	$cachedlist = str_replace(',', "','", $cachedlist);
	$sql = " SELECT title,content FROM vcard_cache WHERE title IN ('$cachedlist') AND date>'$now' ";
	$temps = $DB_site->query($sql);
	while ($temp = $DB_site->fetch_array($temps))
	{
		$vcardcache["$temp[title]"] = stripslashes($temp['content']);
	}
	unset($temp);
	$DB_site->free_result($temps);
}
/********************************************************************
Get Evenlist
********************************************************************/
function get_html_event() {
	global $DB_site,$gallery_event_allow,$gallery_event_value;
	global $site_name,$site_url,$site_image_url,$site_music_url,$admin_email,$site_font_face,$timenow;

	$now_month 	= date("m");
	$now_day	= date("d");
	$now_year	= date("Y");
	$calendar_list	= '';
	$calendar_list_rows = '';
	$next_month	= $now_month +1;
	if ($next_month  == 13)
	{
		$next_month = 0;
	}
	$gallery_event_allow =1;
	if ($gallery_event_allow == 1)
	{
		$eventslist = $DB_site->query("SELECT * FROM vcard_event WHERE (event_month = $now_month AND event_dayend >= $now_day) OR (event_month=$next_month) ORDER BY event_month,event_day ASC ");
		$showed_monthname_current = 0;
		$showed_monthname_next = 0;
		while ($row  =  $DB_site->fetch_array($eventslist))
		{
			extract($row);
			$event_name = stripslashes($event_name);
			if ($event_month == $now_month && $showed_monthname_current == 0)
			{
				$month = get_monthname($event_month,1);
				eval("\$calendar_list_rows .= \"".get_template("calendar_list_month")."\";");
				$showed_monthname_current = 1;
			}
			if ($showed_monthname_next == 0 && $event_month == $next_month){
				$month = get_monthname($event_month,1);
				eval("\$calendar_list_rows .= \"".get_template("calendar_list_month")."\";");
				$showed_monthname_next = 1;
			}
			$date = $event_day.cexpr($event_dayend!=$event_day,"-$event_dayend","");
			eval("\$calendar_list_rows .= \"".get_template("calendar_list_day")."\";");
			//$i++;
		}
		$DB_site->free_result($eventslist);
		if ($next_month == 0)
		{
			$result2 = $DB_site->query("SELECT * FROM vcard_event WHERE event_month = 1 ORDER BY event_month,event_day ASC ");
			$month = get_monthname("1",1);
			eval("\$calendar_list_rows .= \"".get_template("calendar_list_month")."\";");
			while ($row2 = $DB_site->fetch_array($result2))
			{
				extract($row2);
				$event_name = stripslashes($event_name);
				$date = $event_day.cexpr($event_dayend!=$event_day,"-$event_dayend","");
				eval("\$calendar_list_rows .= \"".get_template("calendar_list_day")."\";");
			}
		}
		$DB_site->free_result($eventslist);
		eval("\$calendar_list = \"".get_template("calendar_list")."\";");
	}
	return $calendar_list;
}

/********************************************************************
Dropdown list of categories
********************************************************************/
function get_html_dropdown_cat() {
	global $DB_site,$MsgGoTo,$MsgHome,$cat_count_array;
	global $site_name,$site_url,$site_image_url,$site_music_url,$admin_email,$site_font_face,$timenow;

	$array = make_array_sort($cat_count_array, array('+cat_order','+cat_name'));
	$html ="<table border='0' cellspacing='0' cellpadding='0'><tr><form action=''><td><select OnChange=\"if(options[selectedIndex].value) location.href=(options[selectedIndex].value)\"><option>$MsgGoTo</option><option value='index.php'>$MsgHome</option>";
	for ($i=0; $i < sizeof($array); $i++)
	{
		if (empty($array[$i]['cat_subid']) && $array[$i]['cat_active']==1)
		{
			$html .= '<option value="gbrowse.php?cat_id='. $array[$i]['cat_id'] .'"> ' .$array[$i]['cat_name'] .'</option>\n';
			for ($j=0; $j < sizeof($array);$j++)
			{
				if ($array[$j]['cat_subid']==$array[$i]['cat_id'] && $array[$j]['cat_active']==1)
				{
					$html .= '<option value="gbrowse.php?cat_id='. $array[$j]['cat_id'] . '">&nbsp;&nbsp; &raquo; '. $array[$j]['cat_name'] .'</option>\n';
				}
			}
		}
	}
	$html	.='</select></td></form></tr></table>';
	return $html;
}
/********************************************************************
Text list of categories
********************************************************************/
function get_html_table_cattex() {
	global $DB_site,$MsgHome,$cat_count_array;
	global $site_name,$site_url,$site_image_url,$site_music_url,$admin_email,$site_font_face,$timenow;
	
	$categories_textlist_rows = '';
	$temp_categories_list = '';
	$array = make_array_sort($cat_count_array, array('+cat_order','+cat_name'));
	for ($i=0; $i < sizeof($array); $i++)
	{
		if ((empty($array[$i]['cat_subid']) || $array[$i]['cat_subid']==0) && $array[$i]['cat_active']==1)
		{
			$categories_list_maincat = '';
			$categories_list_subcat = '';
			$cat_name = stripslashes(htmlspecialchars($array[$i]['cat_name']));
			$cat_id = $array[$i]['cat_id'];
			eval("\$categories_textlist_rows .= \"".get_template("categories_textlist_rows_maincat")."\";");
			for ($j=0; $j < sizeof($array);$j++)
			{
				if ($array[$j]['cat_subid']==$array[$i]['cat_id'] && $array[$j]['cat_active']==1)
				{
					$cat_id = $array[$j]['cat_id'];
					$cat_name = stripslashes(htmlspecialchars($array[$j]['cat_name']));
					eval("\$categories_textlist_rows .= \"".get_template("categories_textlist_rows_subcat")."\";");
				}
			}
			eval("\$temp_categories_list .= \"".get_template("categories_list")."\";");
		}
	}
	eval("\$html = \"".get_template("categories_textlist")."\";");
	return $html;
}
function get_html_cat_extended_list() {
	global $MsgHome,$cat_count_array;
	global $site_name,$site_url,$site_image_url,$site_music_url,$admin_email,$site_font_face,$timenow;
	
	$temp_categories_list = '';
	$array = make_array_sort($cat_count_array, array('+cat_order','+cat_name'));
	for ($i=0; $i < sizeof($array); $i++)
	{
		if ((empty($array[$i]['cat_subid']) || $array[$i]['cat_subid']==0) && $array[$i]['cat_active']==1)
		{
			$categories_list_maincat = '';
			$categories_list_subcat = '';
			$cat_name = stripslashes(htmlspecialchars($array[$i]['cat_name']));
			$cat_id = $array[$i]['cat_id'];
			eval("\$categories_list_maincat .= \"".get_template("categories_list_maincat")."\";");
			for ($j=0; $j < sizeof($array);$j++)
			{
				if ($array[$j]['cat_subid']==$array[$i]['cat_id'])
				{
					$cat_id = $array[$j]['cat_id'];
					$cat_name = stripslashes(htmlspecialchars($array[$j]['cat_name']));
					eval("\$categories_list_subcat .= \"".get_template("categories_list_subcat")."\";");
				}
			}
			eval("\$temp_categories_list .= \"".get_template("categories_list")."\";");
		}
	}
	return $temp_categories_list;
}
/********************************************************************
Get Subcategory table
********************************************************************/
function getsubcat($cat_id='') {
	global $DB_site,$gallery_table_cols,$cat_count_array;
	global $site_name,$site_url,$site_image_url,$site_music_url,$admin_email,$site_font_face,$timenow;
	global $MsgPostcards;
	
	$html = '<table cellspacing="0" width="100%" cellpadding="1" border="0"><tr>';
	$icounter = 0;
	$cat_id = addslashes($cat_id);
	
	$temp_categories_list = '';
	$array = make_array_sort($cat_count_array, array('+cat_order','+cat_name'));
	for ($i=0; $i < sizeof($array); $i++)
	{
		$categories_list_maincat = '';
		$categories_list_subcat = '';
		$catinfo['cat_name'] = stripslashes(htmlspecialchars($array[$i]['cat_name']));
		$catinfo['cat_id'] = $array[$i]['cat_id'];
		$catinfo['cat_img'] = $array[$i]['cat_img'];
		$catinfo['totalcards'] = $array[$i]['cat_ncards'];
		$cat_img_url = (eregi('http://',$array[$i]['cat_img']))? $array[$i]['cat_img'] : $site_image_url.'/'.$array[$i]['cat_img'];
		if (!empty($cat_id))
		{
				if ($array[$i]['cat_subid']==$cat_id && $array[$i]['cat_active']==1)
				{
					$html.='<td align="center" valign="top">';
					if ($array[$i]['cat_link'] ==1)
					{
						eval("\$html .= \"".get_template("category_textlink")."\";");
					}else{
						eval("\$html .= \"".get_template("category_imagelink")."\";");
					}
					$html .= '</td>';
					$icounter++;
				}
		}else{
				if ($array[$i]['cat_active']==1)
				{
					$html.='<td align="center" valign="top">';
					if ($array[$i]['cat_link'] ==1)
					{
						eval("\$html .= \"".get_template("category_textlink")."\";");
					}else{
						eval("\$html .= \"".get_template("category_imagelink")."\";");
					}
					$html .= '</td>';
					$icounter++;
				}
		}
		
		if ($icounter == $gallery_table_cols)
		{
			$html.='</tr><tr>';
			$icounter = 0;
		}
	}
	$html.='</tr></table>';
	return $html;
}
/********************************************************************
Get number of postcards in CID
********************************************************************/
function get_total_ncards($cat_id) {
	global $cat_count_array;
	
	for ($i=0; $i < sizeof($cat_count_array); $i++)
	{
		if ($cat_count_array[$i]['cat_id']==$cat_id)
		{
			return $cat_count_array[$i]['cat_ncards'];
		}
	}
}
/********************************************************************
Get number of postcards in subcategories of CID
********************************************************************/
function get_total_ncards_catandsubcat($cat_id) {
	global $cat_count_array;
	
	$cat_id = addslashes($cat_id);
	$total = get_total_ncards($cat_id);
	for ($i=0; $i < sizeof($cat_count_array); $i++)
	{
		if ($cat_count_array[$i]['cat_subid']==$cat_id)
		{
			$total += ($cat_count_array[$i]['cat_active']==1)? $cat_count_array[$i]['cat_ncards'] :'';
		}
	}
	return $total;
}
function make_recount_ncards_to_cat(){
	global $DB_site;
	
	$cats = $DB_site->query("SELECT * FROM vcard_category");
	while ($cat = $DB_site->fetch_array($cats))
	{
		$cards = $DB_site->query("SELECT card_id FROM vcard_cards WHERE card_category='$cat[cat_id]' ");
		$count = $DB_site->num_rows($cards);
		$DB_site->free_result($cards);
		$DB_site->query("UPDATE vcard_category SET cat_ncards='$count' WHERE cat_id='$cat[cat_id]' ");
	}
	$DB_site->free_result($cats);
}

function make_cat_count_array(){
	global $DB_site,$totalcards_in_site;
	
	$totalcards_in_site = 0;
	$counts = $DB_site->query("SELECT * FROM vcard_category ORDER BY cat_id ");
	while ($temp = $DB_site->fetch_array($counts))
	{
		$arr[] = array(
				'cat_id' => $temp['cat_id'],
				'cat_subid'=> $temp['cat_subid'],
				'cat_order'=> $temp['cat_order'],
				'cat_name'=> $temp['cat_name'],
				'cat_img'=> $temp['cat_img'],
				'cat_link'=> $temp['cat_link'],
				'cat_header' => $temp['cat_header'],
				'cat_footer' => $temp['cat_footer'],
				'cat_sort'=> $temp['cat_sort'],
				'cat_ncards'=> $temp['cat_ncards'],
				'cat_active'=> $temp['cat_active']
		);
		$totalcards_in_site += $temp['cat_ncards'];
	}
	unset($temp);
	$DB_site->free_result($counts);
	return $arr;
}
/********************************************************************
Get info about category
********************************************************************/
function get_catinfo($cat_id) {
	global $cat_count_array;
	
	for ($i=0; $i < sizeof($cat_count_array); $i++)
	{
		if ($cat_count_array[$i]['cat_id']==$cat_id)
		{
			 return array(
				'cat_id' => $cat_count_array[$i]['cat_id'],
				'cat_subid'=> $cat_count_array[$i]['cat_subid'],
				'cat_order'=> $cat_count_array[$i]['cat_order'],
				'cat_name'=> $cat_count_array[$i]['cat_name'],
				'cat_img'=> $cat_count_array[$i]['cat_img'],
				'cat_link'=> $cat_count_array[$i]['cat_link'],
				'cat_header' => $cat_count_array[$i]['cat_header'],
				'cat_footer' => $cat_count_array[$i]['cat_footer'],
				'cat_sort'=> $cat_count_array[$i]['cat_sort'],
				'cat_ncards'=> $cat_count_array[$i]['cat_ncards'],
				'cat_active'=> $cat_count_array[$i]['cat_active']);
		}
	}
}


/********************************************************************
Get Top list table
********************************************************************/
function get_html_toplist($cat_id='') {
	global $DB_site,$gallery_toplist_value,$gallery_thm_width,$gallery_thm_height;
	global $site_name,$site_url,$site_image_url,$site_music_url,$admin_email,$site_font_face,$timenow;

	$i = 0;
	$cat_id = addslashes($cat_id);
	$extra = (empty($cat_id))? '': " AND cd.card_category='$cat_id' ";
	$query = "SELECT *, count(cd.card_id) AS count
FROM vcard_stats AS s
LEFT JOIN vcard_cards AS cd ON (s.card_id=cd.card_id)
LEFT JOIN vcard_category AS ct ON (cd.card_category = ct.cat_id)
WHERE (ct.cat_active='1') $extra
GROUP BY s.card_id
ORDER BY count DESC
LIMIT $gallery_toplist_value
";
	$result = $DB_site->query($query);
	//echo $query;
	$top_card_list_item ='';
	while ($cardinfo  =  $DB_site->fetch_array($result))
	{
		$card_id = $cardinfo['card_id'];
		$card_imgthm = $cardinfo['card_thmfile'];
		$card_date = get_date_readable($cardinfo['card_date']);
		$card_caption = stripslashes($cardinfo['card_caption']);
		$card_img_url = (eregi('http://',$cardinfo['card_thmfile']))? $cardinfo[card_thmfile] : "$site_image_url/$cardinfo[card_thmfile]";
		$card_thm_image = "<img src='$card_img_url' border='0' ". cexpr($gallery_thm_width,"width='$gallery_thm_width' height='$gallery_thm_height' ",'') ." hspace='2' vspace='2' alt=''>";
		$card_rating = star_rating($cardinfo['card_rating']);
		$card_new = gethml_newbutton($cardinfo['card_date']);
		eval("\$top_card_list_item .= \"".get_template("top_card_list_item")."\";");
	}
	$DB_site->free_result($result);
	eval("\$top_card_list = \"".get_template("top_card_list")."\";");
	return $top_card_list;
}
/********************************************************************
Get Newest card added list table
********************************************************************/
function get_html_newcard() {
	global $DB_site,$gallery_newlist_value,$gallery_thm_width,$gallery_thm_height;
	global $site_name,$site_url,$site_image_url,$site_music_url,$admin_email,$site_font_face,$timenow;

	$i = 0;
	$new_card_list_item = '';
	$query = "SELECT *  FROM vcard_cards LEFT JOIN vcard_category ON vcard_cards.card_category=vcard_category.cat_id
		WHERE vcard_category.cat_active='1'
		GROUP BY vcard_cards.card_date,vcard_cards.card_id
		ORDER BY vcard_cards.card_id DESC
		LIMIT $gallery_newlist_value ";
	$result = $DB_site->query($query);
	//echo $query;
	while ($cardinfo  =  $DB_site->fetch_array($result))
	{
		extract($cardinfo);
		$card_caption = stripslashes($card_caption);
		$card_date = get_date_readable($card_date);
		$card_img_url = (eregi('http://',$cardinfo['card_thmfile']))? $cardinfo[card_thmfile] : "$site_image_url/$cardinfo[card_thmfile]";
		$card_thm_image = "<img src='$card_img_url' border='0' ". cexpr($gallery_thm_width,"width='$gallery_thm_width' height='$gallery_thm_height' ",'') ." hspace='2' vspace='2' alt=''>";
		$card_rating = star_rating($cardinfo['card_rating']);
		$card_new = gethml_newbutton($cardinfo['card_date']);
		eval("\$new_card_list_item .= \"".get_template("new_card_list_item")."\";");
	}
	$DB_site->free_result($result);
	eval("\$card_list = \"".get_template("new_card_list")."\";");
	return $card_list;
}
/********************************************************************
Create a uid to message
********************************************************************/
function make_idmessage() {

	$pool = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$pool .= 'abcdefghijklmnopqrstuvwxyz';
	$pool .= '0123456789';
	mt_srand ((double) microtime() * 1000000);
	$unique_id = '';
	for ($index = 0; $index < 12; $index++)
	{
		$unique_id .= substr($pool, (mt_rand()%(strlen($pool))), 1);
	}// end for
	$unique_id = date("ymdHms").$unique_id;
	return $unique_id;
}
function get_date_agebybirthday($month,$day,$year) {

	$iTimeStamp = (mktime() - 86400) - mktime(0, 0, 0, $month, $day, $year);
	$idays = $iTimeStamp / 86400;
	$ageyears = floor($idays / 365.25);
	return $ageyears;
}
function make_date_form2db($date,$delimiter="-") {

	if (ereg("([0-9]{2,4})$delimiter([0-9]{1,2})$delimiter([0-9]{1,2})", $date, $regs))
	{
		if (strlen($regs[1]) <4) $regs[1] = "20$regs[1]";
		if (strlen($regs[2]) <2) $regs[2] = "0$regs[2]";
		if (strlen($regs[3]) <2) $regs[3] = "0$regs[3]";
		$value = "$regs[1]-$regs[2]-$regs[3]";
		return $value;
	}else{
		return FALSE;
	}
}
function make_date_db2form($date,$delimiter="-") {

	$d = array();
	$d['day'] = substr($date,6,2);
	$d['month'] = substr($date,4,2);
	$d['year'] = substr($date,0,4);
	return $d['day'].$delimiter.$d['month'].$delimiter.$d['year'];
}
function get_date_readable($date) {
	global $site_dateformat,$site_timeoffset;
	
	if (eregi("([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})", $date, $regs))
	{
		if (strlen($regs[1]) <4) $regs[1] = "20$regs[1]";
		if (strlen($regs[2]) <2) $regs[2] = "0$regs[2]";
		if (strlen($regs[3]) <2) $regs[3] = "0$regs[3]";
		$month = get_monthname($regs[2],1);
		$day = $regs[3];
		$year = $regs[1];
		if ($site_dateformat == 1){
			$date = "$day-$month-$year";
		}else{
			$date = "$month-$day-$year";
		}
	}
	if ($year !='0000')
	{
		return $date;
	}
}
function make_redirectpage($url) {
	global $site_name;
	
	echo '<html><head>';
	echo '<meta content="text/html; charset=iso-8859-1" http-equiv="Content-Type">';
	echo '<meta http-equiv="Refresh" content="0; URL='.$url.'">';
	echo '<title>'.$site_name.'</tile>';
	echo '<body>';
	echo '<script language="javascript">window.location="'.$url.'";</script>';
	echo '</body>';
	echo '</html>';
	exit;
}
function get_html_randombox($cat_id) {
	global $DB_site,$gallery_table_cols;
	global $site_name,$site_url,$site_image_url,$site_music_url,$admin_email,$site_font_face,$timenow;
	global $MsgPostcards;

	$cat_id = addslashes($cat_id);
	$limit = $gallery_table_cols;
	$html = '<table cellspacing="0" width="100%" cellpadding="1" border="0"><tr>';
	$icounter = 0;
	$query = $DB_site->query(" SELECT * FROM vcard_cards WHERE card_category='$cat_id' ORDER BY RAND() LIMIT 0,$limit ");
	while ($cardinfo = $DB_site->fetch_array($query))
	{
		$html .= '<td align="center" valign="top">';
		$post_imagethm = $cardinfo['card_thmfile'];
		$post_caption = stripslashes($cardinfo['card_caption']);
		$post_id = $cardinfo['card_id'];
		$post_date	= get_date_readable($cardinfo['card_date']);
		$post_rating = star_rating($cardinfo['card_rating']);
		$post_new = gethml_newbutton($cardinfo['card_date']);
		$post_thm_url = (eregi('http://',$post_imagethm))? $post_imagethm : "$site_image_url/$post_imagethm";
		eval("\$html .= \"".get_template("postcard_imagelink")."\";");
		$html .= '</td>';
		$icounter++;
		if ($icounter == $gallery_table_cols)
		{
			$html.='</tr><tr>';
			$icounter = 0;
		}
	}
	while (($icounter > 0) && ($icounter != $gallery_table_cols))
	{
		$html.='<td>&nbsp;</td>';
		$icounter++; 
	} 
	$html .= '</tr></table>';
	$DB_site->free_result($query);
	return $html;
}
function get_html_boxrandomcards($cat_id) {
	global $DB_site,$gallery_random,$gallery_thm_per_page;
	global $MsgRandomCards;

	$box_randomcards = '';
	$cat_id = addslashes($cat_id);
	$totaldisplayed = $gallery_thm_per_page;
	$num_rows = get_total_ncards($cat_id);
	if ($num_rows > $totaldisplayed && $gallery_random == 1)
	{
		$box_content = get_html_randombox($cat_id);
		$box_title = $MsgRandomCards;
		eval("\$box_randomcards = \"".get_template("box_randomcards")."\";");
	}
	return $box_randomcards;
}
function getboxsubcategory($cat_id) {
	global $cat_count_array,$subcategory_table;
	global $MsgSubcategory;
	
	$cat_id = addslashes($cat_id);
	$box_subcategory = '';
	// check if there is cards into subcats.
	for ($i=0; $i < sizeof($cat_count_array); $i++)
	{
		if ($cat_count_array[$i]['cat_subid']==$cat_id)
		{
			$total += ($cat_count_array[$i]['cat_active']==1)? $cat_count_array[$i]['cat_ncards'] :'';
		}
	}
	if ($total > 0)
	{
		$box_content = $subcategory_table; //getsubcat($cat_id);
		$box_title	= $MsgSubcategory;
		eval("\$box_subcategory = \"".get_template("box_subcategory")."\";");
	}
	return $box_subcategory;
}
function checkonlytemplate($image,$template) {
	if (!empty($image) && !empty($template))
	{
		return false;
	}elseif (empty($image) && !empty($template)){
		return true;
	}else{
		return false;
	}
}
function getcardoftheday() {
	global $DB_site;
	
	$today = date("Y-m-d");
	$html ='';
	return $html;
}
function get_html_day_topcard()
{
	global $DB_site,$gallery_toplist_allow,$gallery_toplist_value,$gallery_thm_width,$gallery_thm_height;
	global $site_name,$site_url,$site_image_url,$site_music_url,$admin_email,$site_font_face,$timenow;

	$reqdate = time();
	$query = "SELECT UNIX_TIMESTAMP(s.date), COUNT(s.card_id) AS score, cd.card_id, cd.card_thmfile, cd.card_caption,cd.card_date, cd.card_rating
FROM vcard_stats AS s
   LEFT JOIN vcard_cards AS cd ON (cd.card_id=s.card_id)
   LEFT JOIN vcard_category AS ct ON (cd.card_category = ct.cat_id)
WHERE DAYOFYEAR(s.date)=DAYOFYEAR(FROM_UNIXTIME($reqdate)) AND (ct.cat_active='1')
GROUP BY s.card_id
ORDER BY score DESC
LIMIT $gallery_toplist_value ";
	$getcardarray = $DB_site->query($query);
	$top_card_list_item = '';
	$i = '';
	while ($cardinfo  =  $DB_site->fetch_array($getcardarray))
	{
		$card_id 	= $cardinfo['card_id'];
		$card_imgthm 	= $cardinfo['card_thmfile'];
		$card_date	= get_date_readable($cardinfo['card_date']);
		$card_rating	= star_rating($cardinfo['card_rating']);
		$card_new	= gethml_newbutton($cardinfo['card_date']);
		if (!empty($card_imgthm))
		{
			$card_caption 	= stripslashes($cardinfo['card_caption']);
			$card_img_url   = (eregi('http://',$cardinfo['card_thmfile']))? $cardinfo[card_thmfile] : "$site_image_url/$cardinfo[card_thmfile]";
			$card_thm_image = "<img src='$card_img_url' border='0' ". cexpr($gallery_thm_width,"width='$gallery_thm_width' height='$gallery_thm_height' ",'') ." hspace='2' vspace='2' alt=''>";
			eval("\$top_card_list_item .= \"".get_template("top_card_list_item")."\";");
			$i++;
		}
		if ($i == $gallery_toplist_value)
		{
			break;
		}
	}
	$DB_site->free_result($getcardarray);
	eval("\$top_card_list = \"".get_template("top_card_list")."\";");
	return $top_card_list;;
}
function get_html_week_topcard() {
	global $DB_site,$gallery_toplist_allow,$gallery_toplist_value,$gallery_thm_width,$gallery_thm_height;
	global $site_name,$site_url,$site_image_url,$site_music_url,$admin_email,$site_font_face,$timenow;

	$reqdate = time();
	$query = "SELECT UNIX_TIMESTAMP(s.date), COUNT(cd.card_id) AS score, cd.card_id, cd.card_thmfile, cd.card_caption, cd.card_date, cd.card_rating 
FROM vcard_stats AS s
 LEFT JOIN vcard_cards AS cd ON s.card_id=cd.card_id
 LEFT JOIN vcard_category AS ct ON ct.cat_id=cd.card_category
WHERE WEEK(s.date)=WEEK(FROM_UNIXTIME($reqdate)) AND (ct.cat_active='1')
GROUP BY s.card_id
ORDER BY score DESC";
	$getcardarray = $DB_site->query($query);
	$top_card_list_item = '';
	$i = '';
	while ($cardinfo  =  $DB_site->fetch_array($getcardarray))
	{
		$card_id 	= $cardinfo['card_id'];
		$card_imgthm 	= $cardinfo['card_thmfile'];
		$card_date	= get_date_readable($cardinfo['card_date']);
		$card_rating	= star_rating($cardinfo['card_rating']);
		$card_new	= gethml_newbutton($cardinfo['card_date']);
		if (!empty($card_imgthm))
		{
			$card_caption 	= stripslashes($cardinfo['card_caption']);
			$card_img_url   = (eregi('http://',$cardinfo['card_thmfile']))? $cardinfo[card_thmfile] : "$site_image_url/$cardinfo[card_thmfile]";
			$card_thm_image = "<img src='$card_img_url' border='0' ". cexpr($gallery_thm_width,"width='$gallery_thm_width' height='$gallery_thm_height' ",'') ." hspace='2' vspace='2' alt=''>";
			eval("\$top_card_list_item .= \"".get_template("top_card_list_item")."\";");
			$i++;
		}
		if ($i == $gallery_toplist_value)
		{
			break;
		}
	}
	$DB_site->free_result($getcardarray);
	eval("\$top_card_list = \"".get_template("top_card_list")."\";");
	return $top_card_list;;
}

function star_rating($value="0") {
	global $user_rating_allow;
	
	$html = '';
	$stars = split("[/\\.]", $value);
	$starsf = $stars[0];
	if ($user_rating_allow ==1)
	{
		for ($i = 1; $i <= $value; $i++) {
			$html .= '<img src="img/star.gif" border=0 alt="">';
		}
		if (!empty($stars[1]))
		{
			$html .= '<img src="img/starh.gif" border=0 alt="">';
		}
	}
	return $html;
}
function gethml_newbutton($date) {
	global $site_new_days;
	
	$days = get_day_after($date);
	if ($site_new_days > $days)
	{
		return '<img src="img/icon_new.gif" border=0 alt="">';
	}
}

function external_filelog($url) {
	global $DB_site;
	
	if (eregi('http://',$url))
	{
		$DB_site->query(" INSERT INTO vcard_statsextfile (extfile_id,extfile_file,extfile_date) VALUES (NULL, '".addslashes($url)."', CURDATE())"); 
	}
}

function spammer_killer() {
	global $DB_site,$enduser_ip,$antispam_policy,$antispam_allow_entries;
	
	$time = time()-3600;
	$spam_detected = $DB_site->query("SELECT * FROM vcard_spam WHERE ip='$enduser_ip' AND date > $time");
	$num_rows = $DB_site->num_rows();
	if ($num_rows >= $antispam_allow_entries)
	{
		global $site_image_url,$site_body_bgimage,$site_body_bgcolor,$site_body_text,$site_body_link,$site_body_vlink,$site_body_alink,$site_body_marginwidth,$site_body_marginheight;
		global $header,$footer,$headinclude,$site_font_face,$site_prog_url,$MsgBack;
		global $dropdownlist,$categories_textlist, $calendar_list,$topx_list,$topx,$vcardversion,$timenow,$todaydate,$todayext;
		$buttonbackedit	= "<p align='center'><a href='./'><b>$MsgHome</b></a></p>";
		$headinclude .= '<script language="JavaScript" src="script.js"></script>';
		$htmlbody = html_body($site_image_url,$site_body_bgimage,$site_body_bgcolor,$site_body_text,$site_body_link,$site_body_vlink,$site_body_alink,$site_body_marginwidth,$site_body_marginheight);
		$errormessage = $antispam_policy;
		eval("make_output(\"".get_template("errorpage")."\");");
		exit;
	}
	$DB_site->free_result($spam_detected);
}

function make_cachereflash() {
	global $DB_site;
	
	$DB_site->query("DELETE FROM vcard_cache ");
}

function get_vc_cached_item($item)
{
	global $DB_site,$vcachereflesh,$cachedate,$vcardcache,$categories_table,$categories_table_maincat;
	
	if (!empty($vcardcache[$item]))
	{
		return $vcardcache[$item];
	}else{
		$item = addslashes($item);
		$now = time();
		$next = (60 * $vcachereflesh) + $now;
		switch ($item){
			case 'cachedate':
				$content = $next;
				break;
			case 'dropdown':
				$content = get_html_dropdown_cat();
				break;
			case 'newcard':
				$content = get_html_newcard();
				break;
			case 'categories_extended_list':
				$content = get_html_cat_extended_list();
				break;
			case 'today_topcard' :
				$content = get_html_day_topcard();
				break;
			case  'week_topcard' :
				$content = get_html_week_topcard();
				break;
			case 'calendar':
				$content = get_html_event();
				break;
			case 'categories_text':
				$content = get_html_table_cattex();
				break;
			case 'categories_table_upcat' :
			 	get_categories_table();
				$content = $categories_table_maincat;
				break;
			case 'categories_table_cat' :
				get_categories_table();
				$content = $categories_table;
				break;
		}
		$sql = "REPLACE INTO vcard_cache (title,content,date) VALUES ('$item', '".addslashes($content)."', '$next')";
		$DB_site->query($sql);
		return $content;
	}
}

function get_vc_cached_cattoplist($cat_id='')
{
	global $vcachereflesh,$DB_site,$vcardcache;
	
	$cat_id = addslashes($cat_id);
	$title = 'topcat_'.$cat_id;
	if (!empty($vcardcache[$title]))
	{
		return $vcardcache[$title];
	}else{
		$now = time();
		$next = (60 * $vcachereflesh) + $now;
		$sql = "SELECT * FROM vcard_cache WHERE title='$title' ";
		$cachetoplistcat = $DB_site->query_first($sql);
		if ($cachetoplistcat)
		{
			if ($now <= $cachetoplistcat['date'])
			{
				$content = stripslashes($cachetoplistcat['content']);
			}else{
					$content = get_html_toplist($cat_id);
					$sql = "REPLACE INTO vcard_cache (title,content,date) VALUES ('$title','".addslashes($content)."','$next') ";
					if (!empty($content))
					{
						$DB_site->query($sql);
					}else{
						$content = get_html_toplist($cat_id);
						$DB_site->query($sql);
					}
			}
		}else{
			$content = get_html_toplist($cat_id);
			$sql = "REPLACE INTO vcard_cache (title,content,date) VALUES ('$title','".addslashes($content)."','$next') ";
			if (!empty($content))
			{
				$DB_site->query($sql);
			}else{
				$content = get_html_toplist($cat_id);
				$DB_site->query($sql);
			}
				
		}
		return $content;
	}
}

function get_categories_table()
{
	global $DB_site,$gallery_toplist_allow,$gallery_toplist_value,$gallery_thm_width,$gallery_thm_height,$gallery_table_cols,$totalcards_in_site,$cat_count_array;
	global $site_name,$site_url,$site_image_url,$site_music_url,$admin_email,$site_font_face,$timenow,$MsgPostcards ;
	global $categories_table,$categories_table_maincat;
		
	$number = $totalcards_in_site;
	
	$tbl_cat ='<table cellspacing="0" width="100%" cellpadding="1" border="0"><tr>';
	$tbl_upcat = '<table cellspacing="0" width="100%" cellpadding="1" border="0"><tr>';

	$array = make_array_sort($cat_count_array, array('+cat_order','+cat_name'));
	$total_cards_incatsubcat = 0;
	$icol1 = 0;
	$icol2 = 0;
	for ($i=0; $i < sizeof($array); $i++)
	{
		$catinfo['cat_id'] = $array[$i]['cat_id'];
		$catinfo['cat_img'] = $array[$i]['cat_img'];
		$catinfo['cat_name'] = stripslashes(htmlspecialchars($array[$i]['cat_name']));
		$catinfo['totalcards'] = get_total_ncards_catandsubcat($array[$i]['cat_id']);
		$cat_name = $catinfo['cat_name'];
		$cat_img_url = (eregi('http://',$array[$i]['cat_img']))? $array[$i]['cat_img'] : $site_image_url.'/'.$array[$i]['cat_img'];
		if ((empty($array[$i]['cat_subid']) || $array[$i]['cat_subid']==0 ) && $array[$i]['cat_active']==1)
		{
			$tbl_upcat .= '<td align="center" valign="top" width="' . get_widthpercent($gallery_table_cols) . '">';
			if ($array[$i]['cat_link'] ==1)
			{
				eval("\$tbl_upcat .= \"".get_template("category_textlink")."\";");
			}else{
				eval("\$tbl_upcat .= \"".get_template("category_imagelink")."\";");
			}
			$tbl_upcat .= '</td>';
			$icol1++;
			if ($icol1 == $gallery_table_cols)
			{
				$tbl_upcat.='</tr><tr>';
				$icol1 = 0;
			}
		}
		$tbl_cat .= '<td align="center" valign="top" width="' . get_widthpercent($gallery_table_cols) .'">';
		if ($array[$i]['cat_link'] ==1)
		{
			eval("\$tbl_cat .= \"".get_template("category_textlink")."\";");
		}else{
			eval("\$tbl_cat .= \"".get_template("category_imagelink")."\";");
		}
		$tbl_cat .= '</td>';
		$icol2++;
		if ($icol2 == $gallery_table_cols)
		{
			$tbl_cat .='</tr><tr>';
			$icol2 = 0;
		}
	}
	$tbl_upcat .='</tr></table>';
	$tbl_cat .='</tr></table>';

	$categories_table_maincat = $tbl_upcat;
	$categories_table = $tbl_cat;
	return true;
}

function view_array($array) {

	echo '<table cellpadding="0" cellspacing="0" border="1">';
	foreach ($array as $key1 => $elem1)
	{
		echo '<tr>';
		echo '<td>'.$key1.'&nbsp;</td>';
		if (is_array($elem1)){
			ext_array($elem1);
		}else{
			echo '<td>'.htmlspecialchars($elem1).'&nbsp;</td>';
		}
		echo '</tr>';
	}
	echo '</table>';
}

function ext_array($array)
{
	echo '<td>';
	echo '<table cellpadding="0" cellspacing="0" border="1">';
	foreach ($array as $key => $elem)
	{
		echo '<tr>';
   	    echo '<td>'.$key.'&nbsp;</td>';
   	    if (is_array($elem))
		{
			ext_array($elem);
		}else{
			echo '<td>'.htmlspecialchars($elem).'&nbsp;</td>';
		}
		echo '</tr>';
	}
	echo '</table>';
	echo '</td>';
}
function make_array_sort($array, $args) { 
    foreach ($args as $arg) { 
        $order_field = substr($arg, 1, strlen($arg));  
        foreach ($array as $array_row) { 
            $sort_array[$order_field][] = $array_row[$order_field]; 
        } 
        $sort_rule .= '$sort_array['.$order_field.'], '.($arg[0]=='+' ? SORT_ASC : SORT_DESC).','; 
    } 
    eval("array_multisort($sort_rule".' &$array);'); 
	return $array;
}

function get_array_from_url($url='',$rpstr='') {

	if (empty($url)) return false;
	if (!empty($rpstr)) $url = str_replace('attachment.php?','',$url);
	$array = split('&', $url);
	for ($i=0; $i<=count($array); $i++)
	{
		list($k,$v) = split('=',$array[$i],2);
		if (!empty($k) && !empty($v))
		{
			$narray[$k] = $v;
		}
	}
	return $narray;
}

function make_remove_extrachars($str,$nchar=500){
	
	$str = substr($str, 0, $nchar);
	return $str;
}
?>