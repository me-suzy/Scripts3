<?
/*
# File: logout.php
# Script Name: vSignup 2.5
# Author: Vincent Ryan Ong
# Email: support@beanbug.net
#
# Description:
# vSignup is a member registration script which utilizes vAuthenticate
# for its security handling. This handy script features email verification,
# sending confirmation email message, restricting email domains that are 
# allowed for membership, and much more.
#
# This script is a freeware but if you want to give donations,
# please send your checks (coz cash will probably be stolen in the
# post office) to:
#
# Vincent Ryan Ong
# Rm. 440 Wellington Bldg.
# 655 Condesa St. Binondo, Manila
# Philippines, 1006
*/
	// Destroy Sessions
	setcookie ("USERNAME", "");
	setcookie ("PASSWORD", "");	
	include_once ("authconfig.php");
?>
<html>
<head>
<title>Member's Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF" text="#000000">
<p><font face="Arial, Helvetica, sans-serif" size="5"><b>You have successfully logged off.</b></font></p>
<p><font face="Arial, Helvetica, sans-serif" size="2"><b>Click <a href="<? echo $login; ?>">here</a> to re-login.</b></font></p>
