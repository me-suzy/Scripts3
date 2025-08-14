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

		if (isset($_REQUEST['send'])) {
			foreach ($_SESSION['mail_id'] as $id) {
    		$r = mysql_query("SELECT * FROM member WHERE id=$id");
    		$message = $body;
      	if ($info = mysql_fetch_assoc($r)) {
	      	foreach ($info as $name=>$value) {
						$message = preg_replace("/@$name@/","$value",$message);
    	    }      	
      	  $mail = $info['email']; 
      	  $from = $vars['mail_from'];
	        if (strlen($cc) > 0) {
  	      	$headers = "From: $from\r\nCc: $cc\r\n";
    	    } else {
  	      	$headers = "From: $from\r\n";
        	}
    	  	mail($mail,$subject,$message,$headers);
      	} //if
    	} //foreach
    }//foreach
?>  
