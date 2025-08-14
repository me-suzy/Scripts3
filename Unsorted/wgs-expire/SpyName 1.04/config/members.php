<?
/*****************************************************************/
/* Program Name         : WGS-Expire				             */
/* Program Version      : 1.02                                   */
/* Program Author       : Webguy Studios                         */
/* Site                 : http://www.webguystudios.com           */
/* Email                : contact@webguystudios.com              */
/*                                                               */
/* Copyright (c) 2002,2003 webguystudios.com All rights reserved.   */
/* Do NOT remove any of the copyright notices in the script.     */
/* This script can not be distributed or resold by anyone else   */
/* than the author, unless special permisson is given.           */
/*                                                               */
/*****************************************************************/

	if (isset($_REQUEST['select'])) {
		$id = $_REQUEST['select'];
		$r = mysql_query("SELECT * FROM member WHERE id=$id");
		print mysql_error();
		$info = mysql_fetch_assoc($r);
		foreach ($info as $name=>$value)
		$vars[$name] = $value;
	} 
  
	if (isset($_REQUEST['member_add'])) {
		$login = $_REQUEST['login'];
		$password = $_REQUEST['password'];
		$first = $_REQUEST['first'];
		$last = $_REQUEST['last'];
		$email = $_REQUEST['email'];
		$plan = $_REQUEST['plan'];
		
		$error = 0;

		if ($login == "") {
			$_SESSION['error']['bad_login'] = 1;
			$error++;
		}
		if ($password == "") {
			$_SESSION['error']['bad_password'] = 1;
			$error++;
		}
		if ($email == "") {
			$_SESSION['error']['bad_email'] = 1;
			$error++;
		}
		if (!isset($_REQUEST['plan'])) {
			 $_SESSION['error']['bad_plan'] = 1;
			 $error++;
		}

		if ($error==0) {
			mysql_query("INSERT INTO member (login,passwd,first,last,email,acctype) VALUES ('$login','$password','$first','$last','$email','$plan')");
			print mysql_error();
		} else {
			print_r($_SESSION);
		}
	}

	if (isset($_REQUEST['member_modify'])) {
		$id = $_REQUEST['id'];
		$login = $_REQUEST['login'];
		$password = $_REQUEST['password'];
		$first = $_REQUEST['first'];
		$last = $_REQUEST['last'];
		$email = $_REQUEST['email'];
		$plan = $_REQUEST['plan'];
		
		$error = 0;

		if ($login == "") {
			$_SESSION['error']['bad_login'] = 1;
			$error++;
		}
		if ($password == "") {
			$_SESSION['error']['bad_password'] = 1;
			$error++;
		}
		if ($email == "") {
			$_SESSION['error']['bad_email'] = 1;
			$error++;
		}
		if (!isset($_REQUEST['plan'])) {
			$_SESSION['error']['bad_plan'] = 1;
			$error++;
		}

		if ($error==0) {
			mysql_query("UPDATE member SET login='$login',passwd='$password',first='$first',last='$last',email='$email',acctype='$plan' WHERE id=$id");
			print mysql_error();
		}
 else {
		        print_r($_SESSION);
		}
	}

	if (isset($_REQUEST['send_mail']) && isset($_REQUEST['member_id'])) {
		$id = $_REQUEST['member_id'];
		if (!is_array($id)) {
			$id = array($id=>'on');
	    }
    	session_register('mails');
    	session_register('mail_id');
    	$mails = array();
    	$mail_id = array();
	  	foreach ($id as $name=>$value) {
   			$r = mysql_query("SELECT email FROM member WHERE id = $name");
	    	$mail = mysql_fetch_row($r);
	    	$mails[] = $mail[0];
    		$mail_id[] = $name;
		}
  		header("Location: ./?page=massmail");
    	exit;
	}
  
	if (isset($_REQUEST['activate'])) {
		$id = $_REQUEST['activate'];
		mysql_query("UPDATE member SET active = NOT active WHERE id = $id");  
	}
  
	if (isset($_REQUEST['del'])) {
		$id = $_REQUEST['member_id'];
		if (!is_array($id)) {
			$id = array($id=>'on');
		}
		foreach ($id as $name=>$value) {
			mysql_query("DELETE FROM member WHERE id=$name");
		}
	}
?>