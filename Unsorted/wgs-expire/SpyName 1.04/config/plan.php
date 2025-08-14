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
 
  if (isset($_REQUEST['plan_update'])) {
  	$id = $_REQUEST['id'];
  	$name = $_REQUEST['name'];
  	$cost = $_REQUEST['cost'];
  	$domains = $_REQUEST['domains'];
        (isset($_REQUEST['linkpop'])) ? $linkpop = $_REQUEST['linkpop'] : $linkpop = "";
        (isset($_REQUEST['digger'])) ? $digger = $_REQUEST['digger'] : $digger = "";
        (isset($_REQUEST['yamoz'])) ? $yamoz = $_REQUEST['yamoz']: $yamoz = "";
   	mysql_query("UPDATE plan SET name='$name', cost=$cost, domains=$domains, linkpop='$linkpop', digger='$digger', yamoz='$yamoz' WHERE id=$id");
  }
  if (isset($_REQUEST['plan_add'])) {
   	$id = $_REQUEST['id'];
  	$name = $_REQUEST['name'];
  	$cost = $_REQUEST['cost'];
  	$domains = $_REQUEST['domains'];
        (isset($_REQUEST['linkpop'])) ? $linkpop = $_REQUEST['linkpop'] : $linkpop = "";
        (isset($_REQUEST['digger'])) ? $digger = $_REQUEST['digger'] : $digger = "";
        (isset($_REQUEST['yamoz'])) ? $yamoz = $_REQUEST['yamoz']: $yamoz = "";
    $r = mysql_query("SELECT * FROM plan WHERE name = '$name'");
    if (mysql_fetch_row($r)) {
    	$_SESSION['error']['plan_exists'] = 1;
    } else {
	mysql_query("INSERT INTO plan (name,cost,domains,linkpop,digger,yamoz) VALUES ('$name','$cost','$domains','$linkpop','$digger','$yamoz')");
    }
  }

  if (isset($_REQUEST['plan_delete'])) {
		foreach ($_REQUEST['del'] as $name=>$value ) {
    	mysql_query("DELETE FROM plan WHERE id='$name'");
      print mysql_error();
    }
    header("Location:./?page=plan");
    exit;  	
  }
  
  if (isset($_REQUEST['select'])) {
  	$id = $_REQUEST['select'];
    $r = mysql_query("SELECT * FROM plan WHERE id=$id");
    print mysql_error();
    $line = mysql_fetch_assoc($r);
    $vars = array_merge($vars,$line);
  }
?>
