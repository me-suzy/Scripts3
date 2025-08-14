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

  require "../lib/template.php";
  require "../lib/config.php";
  require "../lib/db.php";
  require "../lib/deleteddomains.php";


  session_name('sClient');
  session_start();
  if (isset($_GET["logout"])) {
  	session_destroy();
    header("Location: ./");
    exit;
  }

  if (isset($_REQUEST['save'])) {
		db_connect();
    		    
  	$login = $_REQUEST['login'];
  	$passwd = $_REQUEST['pass'];
		$r = mysql_query("select active from member WHERE login = '$login' and passwd = '$passwd'");
    if ($line = mysql_fetch_row($r)) {
    	if ($line[0] == 0) {
      	$_SESSION['error']['inactive'] = 1;
      } else {
 				session_register('login');
      }
      header("Location: ./");
      exit;
    } else {
    	session_unregister('login');
    	$_SESSION['error']['invalid_login'] = 1;
    }
  }

  if (!isset($_SESSION['login'])) {
		print template_parse("<%include(login.template)");
    exit;
  }

 	db_connect();
  
  if (!isset($_REQUEST['page'])) {
	  $_REQUEST['page'] = 'monitor';
  }

  if ($_REQUEST['page'] == 'info') {
  	include "info.php";
  }
  
  if (isset($_REQUEST['delete'])) {
  	foreach ($_REQUEST as $name=>$value) {
    	if (preg_match("/del_(\d+)/",$name,$match)) {
      	$id = $match[1];
      	mysql_query("DELETE FROM monitor WHERE id = $id");
        print mysql_error();
      }
    }
    header("Location: ./");
    exit;
  }
  
  if (isset($_REQUEST['save'])) {
    session_register("login");
  }

  if (isset($_REQUEST['monitor'])) {
    $id = $_REQUEST["id"];
    if (!is_array($id)) {
    	$id = array($id=>'on');
    }
    $login = $_SESSION['login'];
    $r = mysql_query("SELECT domains FROM plan,member WHERE plan.id = member.acctype AND member.login = '$login'");
    print mysql_error();
    $row = mysql_fetch_row($r);
    $d_max = $row[0];
		$_SESSION['d_max'] = $d_max;    
    
    foreach ($id as $domain_id=>$on) {
    		$domain_info = $_SESSION['monitor'][$domain_id-1];
      	$domain = ($domain_info[0]);
      	$stamp = ($domain_info[2]);
      	$state = ($domain_info[1]);

        $r = mysql_query("SELECT count(*) FROM monitor,member WHERE monitor.member = member.id AND member.login = '$login'");
        print mysql_error();
        $row = mysql_fetch_row($r);
        $d_count = $row[0];

        if ($d_count >= $d_max && $d_max != -1) {
  				$_SESSION['error']['too_many_domains'] = 1;
          break;
        }
        
  			if (!preg_match('/\S\.(com|net|org|ru)/',$domain)) {
  				$_SESSION['error']['invalid_domain'] = 1;
          continue;
  			}

			  $r = mysql_query("SELECT * FROM monitor,domain_name,member WHERE domain_name.name = '$domain' AND monitor.member=member.id AND monitor.domain_name = domain_name.id AND member.login='$login'");
				print mysql_error();
				if (mysql_fetch_row($r)) {
  				$_SESSION['error']['already_exists'] = 1;
          continue;
			  }
        
  			$result = mysql_query("SELECT id FROM domain_name WHERE name='$domain'");
  			$id_row = mysql_fetch_row($result);
  			if (!$id_row) {
    			$state = mysql_escape_string($state);
	  			mysql_query("INSERT INTO domain_name (name,state,stamp) VALUES ('$domain','$state','$stamp')");
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
	        print mysql_error();
  
    } //foreach
		header("Location: ./?page=monitor");
		exit;    
  }

  $_SESSION['cid'] = get_cid();
  
	if ($_REQUEST['page'] == 'search') {
  	$_SESSION['sid'] = get_sid($_SESSION['cid']);
  }

	if ($_REQUEST['page'] == 'stat') {
  	$_SESSION['stat_info'] = get_info($_SESSION['cid']);
  }
    
  $s = "";

  if ($_REQUEST['page'] == 'view_search') {
		$login = $_SESSION['login'];
		mysql_query("UPDATE member SET sstat = sstat + 1 WHERE login = '$login'");
   	do_search();
   }
	print template_parse("<%include(index.template)");
  db_close();
?>
