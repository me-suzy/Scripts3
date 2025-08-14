<?php
require('setup.php');
$db = mysql_connect($my_host,$my_user,$my_pass); mysql_select_db($my_db, $db); global $db;

function addWord($word) { global $db; $word = strtolower(urldecode($word));
$result = mysql_query("SELECT * FROM keywords WHERE keyword='$word'", $db); if(mysql_num_rows($result)) {
mysql_query("UPDATE keywords SET wordhits=wordhits+1, lastuse='".time()."' WHERE keyword='$word'", $db);
} else { mysql_query("INSERT INTO keywords(keyword,wordhits,lastuse) VALUES ('$word',1,'".time()."') ", $db); }	return; }

function isGet($getvar) { global $url; $tmp = explode("&",$url[1]); 
foreach($tmp as $value) { $value=explode("=",$value); if($value[0]==$getvar) { $keyw=explode("+",$value[1]); break; } }
if(isset($keyw) && is_array($keyw)) { foreach($keyw as $value) { addWord($value); } } return; }

$actions = array();
$actions['http://search.lycos.com/default.asp'] = "isGet(\"query\");";
$actions['http://search.yahoo.com/search'] = "isGet(\"p\");";
$actions['http://web.ask.com/web'] = "isGet(\"q\");";
$actions['http://www.altavista.com/web/results'] = "isGet(\"q\");";
$actions['http://www.search.com/search'] = "isGet(\"q\");";
$actions['http://www.mamma.com/Mamma'] = "isGet(\"query\");";
$actions['http://search.msn.com/pass/results.aspx'] = "isGet(\"q\");";
$actions['http://www.alltheweb.com/search'] = "isGet(\"q\");";
$actions['http://search.looksmart.com/p/search'] = "isGet(\"qt\");";

if(isset($_SERVER['HTTP_REFERER']) && strlen($_SERVER['HTTP_REFERER'])) { $url=explode("?",$_SERVER['HTTP_REFERER']); global $url;
if(isset($url[1]) && strlen($url[1])) {
	if(isset($actions[$url[0]])) { eval($actions[$url[0]]); }
	else if(preg_match("/http:\/\/www\.google/i", $url[0])) { isGet("q"); }
}
}
?>