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

	$login = $_SESSION["login"];
	$r = mysql_query("SELECT cost FROM plan,member WHERE plan.id=member.acctype AND member.login='$login'");
	list($cost) = mysql_fetch_row($r);
	if ($cost > 0) {
		$_SESSION['cancel'] = 'pay';
	} else {
		$_SESSION['cancel'] = 'free';
	}

	if (isset($_REQUEST['info_change'])) {
  	extract($_REQUEST);
	$error = 0;

  	if (!preg_match('/[a-z]+/',$login))
 {
        	$_SESSION['error']['bad_password'] = 1;
		$error++;
	}

  	if ($password != $confirm)
 {
        	$_SESSION['error']['bad_confirm'] = 1;
		$error++;
	}
      
  	if (!preg_match('/\w+@[a-zA-Z0-9\-]+\.[a-z]+/',$email))
 {
        	$_SESSION['error']['bad_mail'] = 1;
		$error++;
	}

    $login = $_SESSION['login'];
    if ($error==0) {
    	mysql_query("UPDATE member SET passwd = '$password', email='$email', first = '$first', last='$last' WHERE login = '$login'");
      print mysql_error();
    }
 
  }
  
	if (isset($_REQUEST['info_unsubscribe'])) {
    $login = $_SESSION['login'];
  	mysql_query("DELETE FROM member WHERE login = '$login'");
    session_unregister('login');
    header("Location: ./");
    exit;
  }
?>
