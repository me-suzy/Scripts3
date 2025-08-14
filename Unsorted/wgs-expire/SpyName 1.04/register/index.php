<?
/*****************************************************************/
/* Program Name         : WGS-Expire                             */
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

  require "../lib/template.php";
  require "../lib/db.php";
  require "../lib/config.php";

  function register($userinfo) {
    extract($userinfo);
    global $news;

    if ($news == "on") {
    	$news = 1;
    } else {
    	$news = 0;
    }
    $r = mysql_query("SELECT * FROM member WHERE login = '$login'");
    if (mysql_fetch_array($r)) {
    	print template_parse("<%include(exists.template)%>");
      exit;
    }
    $acctype= $userinfo['acctype'];
    mysql_query("INSERT INTO member (login,first,last,email,news,passwd,acctype) VALUES ('$login','$first','$last','$email',$news,'$passwd','$acctype')");
    print mysql_error();
  }
  
  function activate($login,$vars) {
  	$sql = "UPDATE member SET active = 1 WHERE login = '$login'";
  	mysql_query($sql) or die(mysql_error());
		$r = mysql_query("SELECT * FROM member WHERE login = '$login'");
		$line = mysql_fetch_assoc($r);

		$to = $line['email'];
		$body = $vars['welcome_message'];
		$subject = $vars['welcome_subject'];
		$from = $vars['mail_from'];
		foreach ($line as $name=>$value) {
			$body = preg_replace("/@$name@/",$value,$body);
		}

		mail($to,$subject,$body,"From: $from");
  }
  
	db_connect();
  if (isset($_REQUEST['step1'])) {
		$vars = config_read();
		$acctype = $_POST["acctype"];
    $r = mysql_query("SELECT cost,name FROM plan WHERE id='$acctype'");
    $line = mysql_fetch_row($r);
    $cost = $line[0];

    $_REQUEST['cost'] = $cost;
    $_REQUEST['name'] = $line[1];
        
   	register($_REQUEST,$acctype);
    
    if ($cost > 0) {
	 		print template_parse("<%include(pay.template)%>",$vars);
    }
		else {
	 		print template_parse("<%include(free.template)%>",$vars);
			activate($_REQUEST['login'],$vars);
    }
  } else {
	 print template_parse("<%include(index.template)%>");
  }
  db_close();
?>
