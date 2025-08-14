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

	if (isset($_REQUEST['template_save'])) {
  	$path = $_REQUEST['path'];
  	$part = $_REQUEST['part'];

		$template_source = stripslashes($_REQUEST['template_source']);
	  $template_source = preg_replace('/&lt;/','<',$template_source);
  	$template_source = preg_replace('/&gt;/','>',$template_source);
	  $f = fopen("../$part/template/$path.template",'wb');
	  fwrite($f,$template_source);
	  fclose($f);
	  header("Location: ./?page=$page");
		exit;
	}

	if (!isset($_REQUEST['path'])) {
  	$path = "index";
  	$_REQUEST['path'] = $path;
		
  }
  else {
  	$path = $_REQUEST['path'];
  }

	if (!isset($_REQUEST['part'])) {
  	$part = "member";
  	$_REQUEST['part'] = $part;
  }
  else {
  	$part = $_REQUEST['part'];
  }
	
  $f = fopen("../$part/template/$path.template",'r');
  $template_source = '';
  while ($s = fgets($f,1024)) {
  	$template_source .= $s;
  }
  $template_source = preg_replace('/</','&lt;',$template_source);
  $template_source = preg_replace('/>/','&gt;',$template_source);

  $_REQUEST['template_source'] = $template_source;
  fclose($f);
?>