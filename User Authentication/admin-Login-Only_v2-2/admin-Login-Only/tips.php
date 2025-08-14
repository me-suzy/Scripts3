<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
	<link rel="stylesheet" type="text/css" href="admin-login-only.css">

	<TITLE>admin-Login-Only - Tips and Notes</TITLE>

		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<meta name="keywords" content="admin-Login-Only, protect, secure, authenticate, verify, make sure, restrict, admin, only">
		<meta name="description" content="This file is setup.html. It describes how to setup the Globalissa.com software called admin-Login-Only. admin-Login-Only lets you easily protect any web page using a PHP session.">
		<meta name="author" content="Globalissa - Global I.S. S.A.">
		<meta name="generator" content="World Impact">
		<meta name="robots" content="NOINDEX,NOFOLLOW">
		<meta name="revisit-after" content="7 days">
		<meta name="distribution" content="Global">
		<meta name="rating" content="General">
		<meta http-equiv="expires" content="0">
		<meta http-equiv="pragma" content="no-cache">
		<meta http-equiv="cache-control" content="no-cache">
		<meta name="resource-type" content="document">
</HEAD>


<BODY>


<!-- navigation - start -->
<div align="center">
	<a href="setup.html">Setup | </a> 
	<a href="protect.html">Protect | </a> 
	<a href="tips.php">Tips | </a> 
	<a href="vote.php">Vote | </a> 
	<a href="index.html">About</a> 
</div>
<br />
<!-- navigation - end -->




<table width="800">
<tr>
	<td>
		<H1>Tips</H1>
		<H2>Tips, tips, tips</H2>
		

		<table>
<tr>
	<td>
<ul>
	<li>all protected pages must require the file called <b>adminOnly.php</b> at the top of each protected page</li>
	<li>you must enable cookies in your browser - cookies are automatically removed when the session ends</li>
	<li>your session will end when you close your browser</li>
	<li>keep in mind your password is sent &quot;in the clear&quot;. A network sniffer can intercept your unencrypted password</li>
</ul>	
	</td>
	<td>
		<img src="images/dice.gif" width="220" height="150" border=0 alt="Tips, tips, tips">	
	</td>
</tr>
</table>



		<H2>Notes</H2>
<ul>
	<li>PHP documentation: <a href="http://www.php.net/manual/fi/ref.session.php">http://www.php.net/manual/fi/ref.session.php</a></li>
	<li>you must access files on the web server with PHP 4.04 for admin-Login-Only version 1.2, or have PHP 4.1.2 (or higher) available when using admin-Login-Only version 2.x or higher</li>
	<li>if your server allows it, connect to admin.php using a secure https:// connection. The small overhead in time delay is well worth it</li>
</ul>


	</td>
</tr>
</table>




<p><br></p>
<p><br></p>
<p><br></p>

</BODY>
</HTML>
