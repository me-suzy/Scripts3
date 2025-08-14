<?php
/*=================================================================
#	Copyright (c) 2002 by [ x-dev.de/x-gfx.de ]
#	Newsletter-Script by Robert Klikics [rob@x-dev.de]
#==================================================================
#	Website: 	http://www.x-gfx.de [english/german]      
#	Requires:	PHP 4.1.x ++
#	License: 	GPL/Free
#	[ Comments/Additions are welcome! ]
#==================================================================
#	Help: Please read the doc-files for further informations!
#==================================================================
#	File: index.php [main-file]
#===================================================================*/ 

/* include our files */
require 'conf.inc.php';
require 'language.inc.php';
include 'style/header.inc.php';

/* call the class */
$dummy = new xNewsletter;

/* the class */
class xNewsletter {
	
	var $_pw  = "";
	
	/* what to do? */
	function xNewsletter() {
		
		if( empty($_GET['act']) )	$act = $_POST['act'];
		else				$act = $_GET['act'];
		
		switch($act) {
			
			default:
				if( empty($_GET['secure']) )	echo $this->logon_form();
				else				echo $this->menu($_GET['secure']);
				break;
			case email:
				echo $this->form();
				break;
			case login:
				echo $this->login();
				break;	
			case subscribe:
				echo $this->subscribe();
				break;
			case unsubscribe:
				echo $this->unsubscribe();
				break;
			case send:
				echo $this->mailform();
				break;
			case data_handler:
				echo $this->data_handler();
				break;
			case mailfooter:
				echo $this->mailfooter_form();
				break;
			case save_footer:
				echo $this->save_mailfooter();
				break;
			case view_all:
				echo $this->showall();
				break;
		}
	}
	
	/* handles incoming data from the 'Send Newsletter' option */
	function data_handler() {
	
		// security-check
		$this->secure($_POST['secure']);
	
		// have we to change the textbox-size?
		if( !empty($_POST['plus']) || !empty($_POST['minus']) )	return $this->mailform($_POST['secure']);
		
		// or have we really to send some mails?	
		return $this->send_mails($_POST['secure']);
	}
	
	/* send's newsletter to all users from the list */
	function send_mails() {
		
		global $cfg;
		$cnt = 0;
		
		// input-errors?
		if($_POST['subject'] == "")	return "<div id='box'><li>Please enter a subject ...</li>\n<li><a href='javascript:history.back()' title='".BACK."'>".BACK."</a></li></div>";
		if($_POST['msg'] == "")		return "<div id='box'><li>Please enter a message ...</li>\n<li><a href='javascript:history.back()' title='".BACK."'>".BACK."</a></li></div>";
		
		// generate mail-header
		$headers  = "From: ".$cfg['name']." - Newsletter <".$cfg['mail'].">\n";
		$headers .= "X-Sender: <".$cfg['mail'].">\n";
		$headers .= "X-Mailer: xNewsletter 2.0 <http://www.x-gfx.de> with PHP ".phpversion()."\n"; 
		$headers .= "Return-Path: <".$cfg['return'].">\n";
		
		// add own address to bcc
		$headers .= "Bcc: ".$cfg['mail']."\n";
		
		// format (html|plain)
		if($_POST['html'] == 1)	{
			
			// add html header
			$headers .= "Content-Type: text/html\nContent-Transfer-Encoding: 8bit\n";
		
			// get footer, if checked
			if($_POST['footer'] == 1)	$footer = nl2br( $this->get_mailfooter() );
		
			// add breaks & delete \ & make clean HTML
			$letter = stripslashes($_POST['msg']);
			$letter = nl2br($letter);
			$subj   = stripslashes( strip_tags($_POST['subject']) );
		
			// auto-parse URL's in footer
			$pat    = '#(^|[^\"=]{1})(http://|https://|ftp://|mailto:|news:)([^\s<>]+)([\s\n<>]|$)#sm'; 
			$footer = preg_replace($pat,"\\1<a href=\"\\2\\3\" target=\"_blank\">\\2\\3</a>\\4", $footer);
			
			// auto-parse URL's in msg, if we want this
			if($_POST['parse'] == 1)	$letter = preg_replace($pat,"\\1<a href=\"\\2\\3\" target=\"_blank\">\\2\\3</a>\\4", $letter);
			
			// newsletter
			$newsl  = $letter . "<br /><br />" . $footer;
			
		} else {
			
			// get footer, if checked
			if($_POST['footer'] == 1)	$footer = $this->get_mailfooter();
			
			// del \
			$letter = stripslashes( strip_tags($_POST['msg']) );
			$subj   = stripslashes( strip_tags($_POST['subject']) );
			
			// del \r
			$letter = preg_replace("/\r/i", "", $letter);
			
			// newsletter
			$newsl  = $letter . "\n\n" . $footer;
				
		}
		
		// get email's
		$fp  = fopen($cfg['file'], "r");
		$str = fread( $fp, filesize($cfg['file']) );	
		fclose($fp);
		clearstatcache();
		
		$all_mails = explode("%", $str);
		
		// send them
		foreach($all_mails as $mail) {
			
			if( @mail($mail, $subj, $newsl, $headers) )	$cnt++;
			else						break;
			
		}
		
		// delete the count from the empty-address at the end
		$cnt--;
		
		return "<div id='box'><li>Newsletter successfully sended to <b>".$cnt." Users</b> ...</li>\n<li><a href='".$_SERVER['PHP_SELF']."?secure=".$_POST['secure']."' title='".BACK."'>".BACK."</a></li></div>";
	}
	
	/* show all eMail's in list */
	function showall() {
		
		global $cfg;
		
		// get them
		$fp  = fopen($cfg['file'], "r");
		$str = fread( $fp, filesize($cfg['file']) );	
		fclose($fp);
		clearstatcache();
		
		$adr = explode("%", $str);
		asort($adr);
		
		// js for confirm-deleting
		?>
		<script language='JavaScript'>
		/* delete? */
		function del(url, mail) {
		
			if( confirm("Delete '" + mail + "' from list?") )	window.location = url;
			else							alert("Nothing deleted ...");
			
		}
		</script>
		<?php echo "[ <a href='".$_SERVER['PHP_SELF']."?secure=".$_GET['secure']."' title='menu'>back to menu</a> ]"; ?>
		<p>
		<h3>All Addresses in List sorted from A - Z</h3>
		<b>Click one to delete!</b>
		<p>
		<div id='box'>
		<?php
		
		// print them
		foreach($adr as $mail) {
		
			if($mail != "")	echo "\n\t<li><a href=\"javascript:del('".$_SERVER['PHP_SELF']."?act=unsubscribe&mail=".$mail."','".$mail."')\" title=\"delete ".$mail."\">".$mail."</a></li>";	
			
		}
		
		echo "\n</div>\n";
	}
	
	/* little pw-security */
	function secure($ph) {
		
		global $cfg;
				
		// check it
		if( empty($ph) || strlen($ph) != 32 || $ph != md5($cfg['pw']) )		die("<h1>Fatal Security-Error ... Please Re-Logon!</h1>");
	}
	
	/* validate any email-address by syntax */
	function check_email($email) {
		
		if( !preg_match("/^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,4}|museum$/i", $email) )	return false;
		
		return true;
	}
	
	/* subscribe/unsubscribe form */
	function form() {
		
		global $cfg;
		
		$subs   = SUBSCRIBE;
		$unsubs = UNSUBSCRIBE;
		$submit = FORM_SUBMIT;
		$value  = FORM_VALUE;
		$info   = INFO;
		
		// is this an admin?
		if($_GET['act'] == "edit")	$chk = "selected";
		else				$chk = "";
		
		return <<<END_OF_FORM
		<h3>$cfg[name] - Newsletter</h3>
		$info
		<p>
		<form action='$_SERVER[PHP_SELF]' method='get'>
			<input name='mail' size='30' type='text' value='$value' onFocus='this.value=""'>
			<p>
			<select name='act'>
				<option value='subscribe'>$subs
				<option value='unsubscribe' $chk>$unsubs
			</select>
			<input value='$submit' type='submit'>
		 </form>
END_OF_FORM;
	}
	
	/* admin-login-form */
	function logon_form() {
		
		return <<<EOF
		<h3>Please enter the admin-password:</h3>
		<p>
		<form action='$_SERVER[PHP_SELF]' method='post'>
			<input type='hidden'      name='act' value='login'>
			<input type='password'    name='pw'>
			<input type='submit'      value='Login'>
		</form>
EOF;
	}
	
	/* get the mailfooter */
	function get_mailfooter() {
		
		global $cfg;
		
		$fp     = fopen($cfg['ffile'], "r");
		$footer = fread($fp, filesize($cfg['ffile']) );
		fclose($fp);
		clearstatcache();
		
		return stripslashes($footer);
	}
	
	/* save the mailfooter */
	function save_mailfooter() {
	
		global $cfg;
		
		// del \r
		$str = preg_replace("/\r/i", "", $_POST['foot']);
		
		$fp = fopen($cfg['ffile'], "w");
		$ok = fwrite($fp, $str);
		fclose($fp);
		
		if($ok)	return "<div id='box'><li>Thanks, mail-footer saved ...</li>\n<li><a href='".$_SERVER['PHP_SELF']."?secure=".$_POST['secure']."' title='".BACK."'>".BACK."</a></li></div>";
		else	return "Sorry, could not save the footer to csv-file ...";
	}
	
	/* mailfooter-form */
	function mailfooter_form() {
		
		global $cfg;
	
		// get footer
		$footer = $this->get_mailfooter();
		
		// form
		return <<<END_FOOTER
		<script language='JavaScript'>
		/* script for adding 'useful links' */
		function addLinks() {
			
			unsubs_url = "Unsubscribe at: \\n$cfg[url]?act=email";
			page_url   = "\\n\\n$cfg[name] - $cfg[hp]";
			
			document.foot_form.foot.value += "----------\\n\\n" + unsubs_url + page_url;	
		}
		</script>
		[ <a href='$_SERVER[PHP_SELF]?secure=$_GET[secure]' title='menu'>back to menu</a> ]
		<p>
		<h3>Edit Mailfooter</h3>
		<p>
		This text will be added at the bottom of every newsletter-mail ...
		<p>
		<form action='$_SERVER[PHP_SELF]' method='post' name='foot_form'>
			<input type='hidden' name='act'    value='save_footer'>
			<input type='hidden' name='secure' value='$_GET[secure]'>
			<textarea cols='80'  rows='10' name='foot' style='font:12px verdana' wrap='soft'>$footer</textarea>
			<p>
			<input type='submit' value='Save Footer'>&nbsp;
			<input type='button' value='Add Useful Links' onClick='javascript:addLinks()'>&nbsp;
			<input type='button' value='Reset' onclick='document.foot_form.foot.value=""'>
		</form>
END_FOOTER;
	}
	
	/* form to send newsletter(s) */
	function mailform($hash = false) {
		
		global $cfg;
		
		if(!$hash)	$this->secure($_GET['secure']);
		else		$this->secure($hash);
		
		// get total number of users in list
		$fp  = fopen($cfg['file'], "r");
		$str = fread( $fp, filesize($cfg['file']) );	
		fclose($fp);
		clearstatcache();
		
		$number = count( explode("%", $str) ) - 1;
		
		// dynamic textbox-size
		if( empty($_POST['plus']) && empty($_POST['minus']) )	{
			
			$cols = 80;
			$rows = 20;
			
		} else {
			
			// larger?
			if( !empty($_POST['plus']) )	{ $cols = $_POST['act_cols'] + 10; $rows = $_POST['act_rows'] + 5; }
			
			// smaller?
			if( !empty($_POST['minus']) )   { $cols = $_POST['act_cols'] - 10; $rows = $_POST['act_rows'] - 5; } 
		}
		
		// subject text-input size
		$subj_size = $cols;
		
		// get pw-hash
		if(!$hash)	$ph = $_GET['secure'];
		else		$ph = $_POST['secure'];
		
		// get footer
		$footer = nl2br( htmlspecialchars( $this->get_mailfooter() ) );
		
		return <<<END_MAILFORM
		<script language='JavaScript'>
		/* confirming before sending */
		function ask() {
			
			var temp = 0;		
			var html = "disabled";
			var foot = "not be included";
			var pars = "not be auto-parsed";
			
			// status
			if( document.LETTER.html.checked ) 	html = "enabled";
			if( document.LETTER.footer.checked )	foot = "be included";
			if( document.LETTER.parse.checked )	pars = "be auto-parsed";
			
			// create question
			var question = "Send this Newsletter now to all $number Users?\\n\\n------------------------------------------------\\n\\nInfo:\\n------\\n" + "HTML in Newsletter is " + html + "!\\nThe Footer will " + foot + "!\\nPosted URLs will " + pars + "!";
				
			if( confirm(question) )	temp = 1;
			
			  if (temp == 0) {
            			
           			return false;
           			 
       			 } else {
       			 	
			        document.LETTER.submit.disabled = true;
			        return true;
			            
       			 }
		}
		
		/* auto-check checkbox */
		function parseform() {
			
			if( document.LETTER.html.checked )	document.LETTER.parse.checked = true;
			else 					document.LETTER.parse.checked = false;					
		}
		</script>
		[ <a href='$_SERVER[PHP_SELF]?secure=$ph' title='menu'>back to menu</a> ]
		<p>
		<font size='2'><b>Send Newsletter to all subscribed users</b></font>
		<p>
		[ Users in list: $number ]
		<p>
		<form action='$_SERVER[PHP_SELF]' method='post' name='LETTER'>
			<!-- please do not change this -->
			<input type='hidden' name='secure'   value='$ph'>
			<input type='hidden' name='act'      value='data_handler'>
			<input type='hidden' name='act_cols' value='$cols'>
			<input type='hidden' name='act_rows' value='$rows'>
			<b>Newsletter-Subject:</b><p>
			<input type='text'   name='subject' size='$subj_size' value='$_POST[subject]' style='font:12px verdana'>
			<p>
			<b>Newsletter-Text:</b>
			<p>
			<textarea cols='$cols' rows='$rows' wrap='virtual' name='msg' style='font:12px verdana' wrap='soft'>$_POST[msg]</textarea>
			<p>
			<table width='400' cellspacing='0' cellpadding='3' align='center' border='0'>
			 <tr>
			  <td>
			   <input type='checkbox' name='html' value='1' style='border:0' onClick='parseform()'>
			  </td>
			  <td  style='background:#eeeeee;border:1px solid #cecece'>
			   <b>Send Newsletter in HTML-Format?</b> (default setting: Plain Text)<br>
			   Newlines (\\n) will be parsed automatically to &lt;br /&gt;
			  </td>
			 </tr>
			 <tr><td>&nbsp;</td></tr>
			 <tr>
			  <td>
			   <input type='checkbox' name='parse' value='1' style='border:0' checked>
			  </td>
			  <td style='background:#eeeeee;border:1px solid #cecece'>
			  	<b>Auto-Parse URL's, when sending in HTML-Format?</b><br>
			  	'http://domain.com' will be parsed to '<a href="http://domain.com" target="_blank">http://domain.com</a>'
			  </td>
			 </tr>
			 <tr><td colspan='2' align='center'><br><b>Add the default footer at the bottom of the message?</b></td></tr>
			 <tr>
			  <td>
			   <input type='checkbox' name='footer' value='1' checked style='border:0'>
			  </td>
			  <td colspan='2' style='background:#eeeeee;border:1px solid #cecece'>
			   $footer
			  </td>
			 </tr>
			</table>
			<p>&nbsp;</p>
			<input type='submit' name='send'  value='Send Newsletter' title='Send Newsletter to $number Users' style='padding:5px' onClick='return ask()'>&nbsp;
			<input type='submit' name='plus'  value='Increase Textbox' title='Textbox-Size: Larger' style='padding:5px'>&nbsp;
			<input type='submit' name='minus' value='Make Textbox Smaller' title='Textbox-Size: Smaller' style='padding:5px'> 
		</form>
		<p>
		
END_MAILFORM;
	}
	
	/* login */
	function login() {
	
		global $cfg;
		
		// admin-pw ok? if not, get out here! ;)
		if($_POST['pw'] != $cfg['pw'])	return $this->logon_form();	
		
		// show admin-menu and give enrypted pw wo the function
		return $this->menu(md5($cfg['pw']));
	}
	
	/* admin-menu */
	function menu($pass_hash) {
		
		$this->secure($pass_hash);
		
		return <<<EOF
		<h3>xNewsletter 2.0 Admin-Menu</h3>
		<div id='box'>
			<li><a href='$_SERVER[PHP_SELF]?act=send&secure=$pass_hash' title='Send Newsletter'>Send Newsletter</a></li>
			<li><a href='$_SERVER[PHP_SELF]?act=mailfooter&secure=$pass_hash' title='Edit Mail-Footer'>Edit Mail-Footer</a></li>
			<li><a href='$_SERVER[PHP_SELF]?act=view_all&secure=$pass_hash' title='Show all'>Show all Adresses in List</a></li>
			<li><a href='$_SERVER[PHP_SELF]?act=email' 'Form'>Show Newsletter-Form</a></li>
			<li><a href='$_SERVER[PHP_SELF]' title='Logout'>Logout</a></li>
		</div>
EOF;
	}
	
	/* add an email to the list */
	function subscribe() {
		
		global $cfg;
		$error = "";
		
		// error's ?
		if( !$this->check_email(trim($_GET['mail'])) )	$error .= "<li>".INVALID_EMAIL."</li>\n";
		if( $this->in_list(trim($_GET['mail'])) )	$error .= "<li>".ALREADY_IN_LIST."</li>\n";
		if($error != "")				return "<div id='box'>".$error."<li><a href='javascript:history.back()' title='".BACK."'>".BACK."</a></div>";
	
		// save to listfile
		$fp = fopen($cfg['file'], "a+");
		flock($fp, 2);
		$ok = fputs($fp, trim($_GET['mail'])."%" );
		flock($fp, 3);
		fclose($fp);
		
		if($ok)	return "<div id='box'>".THANKS_SAVED."</div>";
		else	return "Sorry, could not save email ...";
	}
	
	/* delete an email from the list */
	function unsubscribe() {
		
		global $cfg;
	
		// is the email really in the list?
		if( !$this->in_list($_GET['mail']) )		return "<div id='box'><li>".NOT_IN_LIST."</li>\n<li><a href='javascript:history.back()' title='".BACK."'>".BACK."</a></div>";
	
		// get data
		$fp  = fopen($cfg['file'], "r");
		$str = fread( $fp, filesize($cfg['file']) );	
		fclose($fp);
		clearstatcache();
		
		// delete email from string
		$email   = quotemeta($_GET['mail']."%") ;
		$new_str = preg_replace("/".$email."/i", "", $str);
		
		// save to listfile
		$fp = fopen($cfg['file'], "w");
		flock($fp, 2);
		$ok = fwrite($fp, $new_str);
		flock($fp, 3);
		fclose($fp);
		
		if($ok)	return "<div id='box'>".THANKS_DELETED."</div>";
		else	return "Sorry, could not delete email ...";
	}
	
	/* $email already in list? */
	function in_list($mail) {
		
		global $cfg;
	
		// get data
		$fp  = fopen($cfg['file'], "r");
		$str = fread( $fp, filesize($cfg['file']) );	
		fclose($fp);
		clearstatcache();
		
		// check data
		$arr = explode("%", $str);
		foreach($arr as $addy) {
			
			if($addy == $mail && $addy != "")	return true;	
		}
		
		return false;
	}
	
/* end of class xNewsletter */	
}

/* ©, please don't remove! */
echo <<<EOF

<!-- xNewsletter 2.0 by http://www.x-gfx.de | Robert Klikics <rob@x-gfx.de> -->
<p>
	<i>- <font face='verdana,arial' size='1'>powered by</font> <a href='http://www.x-gfx.de' target='_blank'><font face='verdana,arial' size='1'>xNewsletter 2.0</font></a> -</i>
</p>
EOF;

/* footer */
include 'style/footer.inc.php';

?>