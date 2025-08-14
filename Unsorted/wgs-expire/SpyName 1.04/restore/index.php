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
	require "../lib/config.php";
	require "../lib/db.php";
  
 	session_start();
  if (isset($_REQUEST['save'])) {
  	db_connect();
    $email = mysql_escape_string($_REQUEST['email']);
    $result = mysql_query("SELECT * FROM member WHERE email = '$email'");
    $vars = config_read();
    $sent = 0;
    while ($info = mysql_fetch_assoc($result)) {
    	$restore_message = $vars['restore_message'];
    	$restore_subject = $vars['restore_subject'];
    	foreach ($info as $name=>$value) {
      	$restore_message = preg_replace("/@$name@/",$value,$restore_message);
      }
			$mail_from = $vars['mail_from'];
      mail($email,$restore_subject,$restore_message,"From: $mail_from");
      $sent = 1;
    }
    db_close();
    if ($sent == 1) {
	  	print template_parse("<%include(done.template)");
    } else {
    	$_SESSION['error']['no_user'] =1 ;
      header("Location: ./");
    }
    exit;
  }
  
  print template_parse("<%include(index.template)");
?>
