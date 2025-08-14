<?php
// *************************************************************************************************
// Title: 		PHP AGTC-Membership system v1.1a
// Developed by: Andy Greenhalgh
// Email:		andy@agtc.co.uk
// Website:		agtc.co.uk
// Copyright:	2005(C)Andy Greenhalgh - (AGTC)
// Licence:		GPL, You may distribute this software under the terms of this General Public License
// *************************************************************************************************
// 
// PLEASE AMEND THE CODE BELOW WITH YOUR DETAILS FOR YOUR SERVERS DATABASE
$localhost = "localhost"; // YOUR LOCAL HOST, USUALLY localhost
$dbuser = "dbuser"; // YOUR DATABASE USERNAME
$dbpass = "dbpass"; // YOUR DATABASE PASSWORD
$dbtable = "dbuserdb";// THE NAME OF YOUR DATABASE , THIS SHOULD HAVE BEEN SET WHEN YOU INSTALLED dbuserdb.sql, SO YOU CAN LEAVE THIS

// PLEASE AMEND THE CODE BELOW WITH YOUR DETAILS FOR THE AUTO RESPONDER EMAIL

$sendersName = "Administration"; // CHANGE THIS TO YOUR OWN NAME
$sendersEmail = "admin@yourweb.com"; // YOUR WEBSITE EMAIL ADDRESS
$sendersComment = "Thank you for joining our site"; // THE COMMENT TO YOUR NEW SUBCRIBER
$serverFolder = "http://www.agtc.co.uk/_holding/dbase/v11a/"; // THE FOLDER IN WHICH YOU ARE RUNNING THIS SCRIPT
$loginPage = "login.php"; // THE PAGE YOU LINK TO FROM THE NEW USERS EMAIL THAT WAS SENT ON SIGN UP, NORMALLY login.php
$emailSubject = "Email to new member"; // THE EMAIL SUBJECT LINE

// YOU DO NOT NEED TO EDIT BELOW THIS LINE
$con = mysql_connect("$localhost","$dbuser","$dbpass")

        or die("Error Could not connect");

$db = mysql_select_db("$dbtable", $con)
		or die("Error Could not select database");
?>