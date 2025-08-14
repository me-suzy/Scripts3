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

	$search_done = 0;
//  header("Content-type: text/plain");
  function parse_include($match) {
    $fname = "template/".$match[1];
    if (!file_exists($fname)) {
      if (file_exists("template/default.template")) {
      	$fname="template/default.template";
      } else {
        return;
      }
    }
    $f = fopen($fname,"r");

    $include_content = "";
    while ($s = fgets($f,1024)) {
      $include_content .= $s;
    }
    return template_parse($include_content);
    fclose($f);
  }
  
  function parse_table($match) {
    $result = mysql_query($match[1]);
    print mysql_error();
    if (!$result) return;
    $s = "";
    while ($line = mysql_fetch_assoc($result)) {
      $s .= template_parse($match[3],$line);
    }
    return $s;
  }

  function do_search() {
  	global $search_done,$search_result;
  	$uri = "";
		$uri = rawurlencode(serialize($_POST));
		$server = $_SERVER['SERVER_NAME'];
		$path = dirname(dirname($_SERVER['PHP_SELF']));
	  $f = fopen("http://$server$path/search.php?post=$uri","r");
    $s = '';
    $form = '';
    $next = 0;
    $search_result = array();
		while ($line = fgets($f,1024)) {
    	if (!$next && preg_match('/(\S+)\t(.*?)\t(\S+)/',$line,$match)) {
      	$match[2] = preg_replace('/\s/','&nbsp;',$match[2]);
      	$search_result[] = array($match[1],$match[2],$match[3]);
      }
      if ($next) {
      	$form .= $line;
      }
      if (trim($line) == '---') {
				$next = 1;      	
      }
    }
    fclose($f);
    $_SESSION['monitor'] = $search_result;
    $_REQUEST['next_page'] = $form;
    $seach_done = 1; 
	}
  
  function parse_search($match) {
  	global $search_done,$search_result;
  	$body = $match[1];
    $id = 0;
    $s = "";
    foreach ($search_result as $var) {
/*    		if (strlen($var[0]) > 25) {			*/
/*        	$var[0] = substr($var[0],0,25)."...";		*/
/*        }							*/
      	$vars['1'] = $var[0];
      	$vars['2'] = $var[1];
      	$vars['3'] = $var[2];
        $vars['id'] = ++$id;
      	$s .= template_parse($body,$vars);
    }
        
    return $s;
  }
  
  function parse_list($match) {
  	$var_name = $match[1];
    global $$var_name;
    $s = "";
    if (!is_array($$var_name)) return;
    foreach ($$var_name as $value) {
    	$s .= $value; 
    }
    return $s;
  }
  
  function parse_error($match) {
  	if (isset($_SESSION['error'][$match[1]])) {
    	unset($_SESSION['error'][$match[1]]);
    	return template_parse($match[3]);
    } else {
    	return '';
    }
  }
  
  function template_parse($s,$v=array()) {
  	global $vars;
    if (count($v) != 0 or !isset($vars)) {
    	$vars = $v;
    }
     
    foreach ($vars as $name=>$value) {
      $s = preg_replace("/<%$name%>/",$value,$s);
    }
    foreach ($_REQUEST as $name=>$value) {
      $s = preg_replace("/<%$name%>/",$value,$s);
    }
    if (isset($_SESSION)) {
    	foreach ($_SESSION as $name=>$value) {
	      $s = preg_replace("/<%$name%>/","$value",$s);
  	  }
    }

    $s = preg_replace_callback("/(?s)<%error\((.+?)\)(%>)?(.*?)<%\/error(%>)?/",'parse_error',$s);
    $s = preg_replace_callback("/(?s)<%search\(\)(.*?)<%\/search/",'parse_search',$s);
    $s = preg_replace_callback("/<%list\((.+?)\)/",'parse_list',$s);
    $s = preg_replace_callback("/<%include\((.+?)\)(%>)?/",'parse_include',$s);

    $s = preg_replace_callback("/(?s)<%table\[(.+?)\](%>)?(.*?)<%\/table(%>)?/",'parse_table',$s);

    $s = preg_replace("/<%\w+%>/","",$s);
    return $s;   
  }
?>
