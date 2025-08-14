<?php

require "dir.php";

$randomfilename = randdir("randomfiletest");
$randomtext = join(file("randomfiletest/".$randomfilename),"\n");

echo <<<EOF
<html>
<head>

<title>Webcreationz - Random File Picker</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta name="author" content="Webcreationz - web design and development">
<meta name="Email" content="webmaster@webcreationz.co.uk">
<meta name="description" content="Webcreationz - Keyword Generator">
<meta name="keywords" content="search engine, keyword generator">
<meta HTTP-EQUIV=Expires CONTENT='Mon, 01 Jan 1996 01:01:01 GMT'>
<meta HTTP-EQUIV=Pragma CONTENT='No-Cache'>

<link rel=stylesheet type="text/css" href="./keywordtest.css">
</head>
<body>
<h1>Random File Picker</h1>
This script which can be used in any PHP application, randomly selects a single file from a specified directory. As an example of this script the following information randomly selected from the randomfiletest directory is displayed below as a simple game of Scissors, Paper, Stone.<p>

Selected File : <B>$randomfilename</B><p>

Selected File Contents : <B>$randomtext</B><p>

<a href="$PHP_SELF" class=mainlink>Click to Retry Random Select</a><p>

Script by Chris Green - <a href="http://webcreationz.recalldigit.co.uk" class=mainlink>webcreationz.co.uk</a>
</body>
</html>
EOF;
?>