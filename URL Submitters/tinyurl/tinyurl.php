<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>TinyURL Creator</title>
<style type="text/css">
input,textarea {
	background-color:#eeeeee;
	border-top: 1px solid #006699;
	border-bottom: 1px solid #006699;
	border-left: 1px solid #006699;
	border-right: 1px solid #006699;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size: 80%;
}
body {
	background-color: #FFFFFF;
	font-family:Verdana;
	font-size: 12px;
	color: #333333;
}
body,td,th {
	color: #333333;
}
a:link,a:active,a:visited { 
    color : #006699;
	text-decoration:underline;
}
a:hover	{ 
    text-decoration:underline;
	color : #DD6900; 
}
</style>
</head>
<body>
<h1>TinyURL Creator</h1>
<?php
       if(!($_POST["url"])){
?>
<form method="post" action="tinyurl.php">
Input URL to be made tiny!
<input type="text" name="url" />
<input type="submit" value="Make it tiny!" />
<?php
       }
       else{
?>
<?
error_reporting(E_ERROR | E_WARNING | E_PARSE);
$url="http://tinyurl.com//create.php?url=" . $_POST['url'] . "";
$fileopen=file($url);
$count=count($fileopen);
for($i=0;$i<$count;$i++){
	$filegets.=$fileopen[$i];
}
$tmpget_1=explode("<blockquote><b>http://tinyurl.com/",$filegets);
$result=explode("</b><p><small>[<a href=\"http://tinyurl.com/",$tmpget_1[1]);
echo "<font color=\"red\">TinyURL Creation Sucess!</font> <br /><br /><a href=\"" . $_POST['url'] . "\">" . $_POST['url'] . "</a> is now <a href=\"http://tinyurl.com/$result[0]\">http://tinyurl.com/$result[0]</a>";
?>
<br />
<br />
<br />
<br />
<hr align="left" width="45" />
<form method="post" action="tinyurl.php">
  Another URL?
  <input type="text" name="url" />
  <input type="submit" value="Make it tiny!" />
</form>
<?php  }?><br /><br /><br /><small><a href="http://ki4bbo.org" style="text-decoration:none !IMPORTANT;color: #333333 !IMPORTANT;">It's the Bright One, it's the Right One, that's KI4BBO.org.</a></small>
</body>
</html>