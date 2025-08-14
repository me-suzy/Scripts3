<?
/*****************************************************************/
/* Program Name         : WGS-Expire						     */
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

	include "../lib/config.php";
	include "../lib/db.php";
  include "../lib/class.Whois.php";
  
  session_name("sClient");
  session_start();
  db_connect();

  $login = $_SESSION['login'];
  $r = mysql_query("SELECT plan.domains FROM plan,member WHERE plan.id = member.acctype AND member.login = '$login'");
  print mysql_error();
  $row = mysql_fetch_row($r);
  $d_max = $row[0];
	$_SESSION['d_max'] = $d_max;    
  $r = mysql_query("SELECT count(*) FROM monitor,member WHERE monitor.member = member.id AND member.login = '$login'");
  print mysql_error();
  $row = mysql_fetch_row($r);
  $d_count = $row[0];
  if ($d_count >= $d_max && $d_max != -1) {
 		$_SESSION['error']['too_many_domains'] = 1;
    header("Location: ./?page=monitor");
   	exit;
  }
	$domain = $_GET["domain"];
	$domain = strtolower($domain);
  if (!preg_match('/^[a-z0-9\-]+\.(com|net|org|ru)$/',$domain)) {
  	$_SESSION['error']['invalid_domain'] = 1;
    header("Location: ./?page=monitor");
    exit;
  }
  
  $r = mysql_query("SELECT * FROM monitor,domain_name,member WHERE domain_name.name = '$domain' AND monitor.member=member.id AND monitor.domain_name = domain_name.id AND member.login='$login'");
  print mysql_error();
  if (mysql_fetch_row($r)) {
  	$_SESSION['error']['already_exists'] = 1;
    header("Location: ./?page=monitor");
    exit;
  }
  
  $whois = new Whois;
  $s = $whois->lookup($domain);
  if (preg_match("/No/",$s)) {
		$state = "<font color='green'>FREE</font>";      			  	
  } else {
		$state = "<font color='red'>BUSY</font>";      			  	
  }

  $result = mysql_query("SELECT id FROM domain_name WHERE name='$domain'");
  $id_row = mysql_fetch_row($result);
  if (!$id_row) {
    $state = mysql_escape_string($state);
	  mysql_query("INSERT INTO domain_name (name,state,stamp) VALUES ('$domain','$state',NOW())");
 		print mysql_error();
	  $result = mysql_query("SELECT id FROM domain_name WHERE name='$domain'");
    $id_row=mysql_fetch_row($result);
  }
  $id = $id_row[0];
  $login = $_SESSION['login'];
  $result = mysql_query("SELECT id FROM member WHERE login='$login'");
  $id_row = mysql_fetch_row($result);
	$member_id = $id_row[0];
  	    
  mysql_query("INSERT INTO monitor (member,domain_name) VALUES ('$member_id','$id')");
  
  db_close();
  header("Location: ./");
?>
