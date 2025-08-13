<?php // email.php
/* first generation stealth email
   
	Example: 
		echo"<p>Send an email to <a href=\"email.php?to=$to&domain=$domain\">Email Me</a></p>";

	The TO and DOMAIN constants below are defined in configure.php
   Instead of using these TO and DOMAIN constants you may 
		a) change 'TO' to a string "info" and 
		b) change 'DOMAIN' to a string "yourDomain.com"
*/
require_once("configure.php"); // bring in your constants
if(!isset($_GET['to'])) { // if the variable $to was not passed then use the constant value
	$_GET['to'] = TO;
}
if(!isset($domain)) { // if the variable '$domain' was not passed then use the constant value 
    $_GET['domain'] = DOMAIN;
}
$to = $_GET['to']; // assign the final value for $to
$domain = $_GET['domain']; // assign the final value for $domain
$emailAddress = $to . "@" . $domain; // put the email address together
header( "Location: mailto:$emailAddress" ); // send email header to page
?>