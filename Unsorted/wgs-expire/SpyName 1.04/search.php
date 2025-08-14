<?
/*****************************************************************/
/* Program Name         : WGS-Expire				             */
/* Program Version      : 1.02                                   */
/* Program Author       : Webguy Studios                         */
/* Site                 : http://www.webguystudios.com           */
/* Email                : contact@webguystudios.com              */
/*                                                               */
/*                                                               */
/* Copyright (c) 2002,2003 webguystudios.com All rights reserved.   */
/* Do NOT remove any of the copyright notices in the script.     */
/* This script can not be distributed or resold by anyone else   */
/* than the author, unless special permisson is given.           */
/*                                                               */
/*****************************************************************/

  header("Content-type: text/plain");
	require "lib/class.HtmlSource.php";
	$source = new HtmlSource();

	$source->host = "deleteddomains.com";
	$source->port = 80;
	$source->page = "/cgi-bin/browse.pl";
  $source->accept = "text/html,text/plain,image/png,image/jpeg,image/gif";
  $source->accept_encoding = "gzip,deflate,compress;q=0.9";
  $source->accept_language = "en-us,en;q=0.5";
	$source->method = "POST";
	$source->httpversion = "1.0";
  $source->referer = "http://deleteddomains.com/search.shtml?cid=23167-31668";
  $source->useragent = "Mozilla/5.0 (Windows; U; Win98; en-US; rv:1.3a) Gecko/20021216 Phoenix/0.5";
	$source->timeout = 5;

  $vars = unserialize(stripslashes($_REQUEST['post']));
//  print_r($vars);
  unset($vars['PHPSESSID']);
  unset($vars['page']);
  foreach ($vars as $name=>$value) {
  	$source->addPostVar($name,$value);
  }
	print_r($source->postvars);
	$source->striptags  = false;
	$source->stripheaders  = true;
	$source->showsource = false;

// Output
  $s = $source->getSource();
//  print htmlspecialchars($s);
//  print $s;
	print nl2br($source->request);
	if (preg_match_all('!<font color="#[^"]+">([^<]*)</font></b>[^<]*<font size="1">\(((ON HOLD) since|<b>(DELETED)</b> on) (\d+-\d+-\d+)\)</font></td>!',$s,$match,PREG_SET_ORDER)) {
  	foreach ($match as $line) {
  		if ($line[3] == '') {
  			$result[] = array($line[1],$line[4],$line[5]);
	   	} else {
  			$result[] = array($line[1],$line[3],$line[5]);
  	  }
	  }
  }
  foreach($result as $domain) {
  	echo $domain[0]."\t";
  	echo $domain[1]."\t";
  	echo $domain[2]."<br>\n";
  } 
  print "---\n";
  if (preg_match('|(?s)</form>.*(<form method="post" action="/cgi-bin/browse.pl">.*</form>)|',$s,$match)) {
  	$next = preg_replace('|<form(.*)>|','<form$1><input type="hidden" name="page" value="view_search">',$match[1]);
  	$next = preg_replace('|action="/cgi-bin/browse.pl"|',
    						 'action="./"',$next);
  	$next = preg_replace('|<div[^>]+>|','',$next);
  	$next = preg_replace('|</div>|','',$next);
  	print $next;
  }
?>