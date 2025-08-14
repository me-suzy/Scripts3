<?
/*****************************************************************/
/* Program Name         : WGS-Expire				             */
/* Program Version      : 1.02                                   */
/* Program Author       : Webguy Studios                         */
/* Site                 : http://www.webguystudios.com           */
/* Email                : contact@webguystudios.com              */
/* Copyright (c) 2002,2003 webguystudios.com All rights reserved.   */
/* Do NOT remove any of the copyright notices in the script.     */
/* This script can not be distributed or resold by anyone else   */
/* than the author, unless special permisson is given.           */
/*                                                               */
/*****************************************************************/

	require "../lib/template.php";
  require "../lib/config.php";
  require "../lib/db.php";

  session_name(md5('admin'));
	session_start();
 	$vars = config_read();
  if (!isset($_SESSION['login'])) {
  	if (isset($_REQUEST['login'])) {
      if ($vars['admlogin'] == $_REQUEST['login'] and
      	$vars['admpasswd'] == $_REQUEST['pass']) {
        $login = $_REQUEST['login'];
        session_register('login');
        header('Location: ./');
        exit;
      } else {
      	$_REQUEST['invalid_login'] = 'Login invalid. Please try again';
      }
    }

  	print template_parse("<%include(login.template)");
    exit;
  }

  if (isset($_REQUEST['logout'])) {
  	session_destroy();
    header("Location: ./");
    exit;
  }

  if (isset($_REQUEST['page']) and $_REQUEST['page'] != 'database') {
	  db_connect();
  } else {
  	$_REQUEST['page'] = 'database';
  }

  $page = $_REQUEST['page'];
  if ($page == 'plan') {
  	include "plan.php";
  } elseif ($page=='members') {
  	include "members.php";
  } elseif ($page=='massmail') {
  	include "massmail.php";
  } elseif ($page=='template') {
  	include "template.php";
  } elseif ($page=='cron') {
  	include "cron.php";
  } elseif ($page=='admin') {
        if ($_POST && ($_POST['admpasswd'] != $_POST['admconfirm'])) {
	    header("Location: ./?page=adm_error");
	    exit;
	}
  }

  if (isset($_REQUEST['save'])) {
    foreach ($_REQUEST as $name=>$value) {
    	$vars[$name] = $value;
    }
  	unset($vars['PHPSESSID']);
  	unset($vars['save']);
  	unset($vars['page']);
  	unset($vars['confirmation']);
    config_write($vars);
    header("Location: ./?page=".$_REQUEST['page']);
    exit;
  }


  print template_parse('<%include(index.template)',$vars);

  if (isset($_REQUEST['page']) and $_REQUEST['page'] != 'database') {
  	db_close();
  }
?>
