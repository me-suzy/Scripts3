<?php
//***************************************************************************//
//                                                                           //
//  Program Name    	: vCard PRO                                          //
//  Program Version     : 2.6                                                //
//  Program Author      : Joao Kikuchi,  Belchior Foundry                    //
//  Home Page           : http://www.belchiorfoundry.com                     //
//  Retail Price        : $80.00 United States Dollars                       //
//  WebForum Price      : $00.00 Always 100% Free                            //
//  Supplied by         : South [WTN]                                        //
//  Nullified By        : CyKuH [WTN]                                        //
//  Distribution        : via WebForum, ForumRU and associated file dumps    //
//                                                                           //
//                (C) Copyright 2001-2002 Belchior Foundry                   //
//***************************************************************************//
define('IN_VCARD', true);
$templatesused = '';
require('./lib.inc.php');
$time = date ("Y-m-d");

// check date to send card use
$advresult = $DB_site->query("SELECT * FROM vcard_user WHERE (card_tosend<='$time') AND (card_sent='0')");
$number = 0;
while( $sendmailinfo = $DB_site->fetch_array($advresult) )
{
	//extract($sendmailinfo);
	// send mail to the recipient notifying them of the card that s waiting for them
	sendmail_pickup("$sendmailinfo[recip_email]","$sendmailinfo[recip_name]","$sendmailinfo[sender_email]","$sendmailinfo[sender_name]","$sendmailinfo[message_id]");
	$result2 = $DB_site->query("UPDATE vcard_user SET card_sent='1' WHERE message_id='$sendmailinfo[message_id]'");
	$number++;
}

//dohtml_admin_tableheader("default","$msg_admin_advancepostsend",1);
//echo "<tr><td><p>$number $msg_admin_adv_result </p></td></tr>";
echo "<p>$number $msg_admin_adv_result </p>";
//dohtml_admin_tablefooter();
if($debug==1){ $timer->end_time(); $timer->elapsed_time();}
$DB_site->close();
if($use_gzip == 1) {ob_end_flush(); }
exit;
?>