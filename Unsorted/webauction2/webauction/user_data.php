<SCRIPT Language=PHP>
/*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu

*/
	// Include messages file	
   require('includes/messages.inc.php');
  
   // Connect to sql server & inizialize configuration variables
   require('includes/config.inc.php');
   
		
	// Data check
	require('includes/datacheck.inc.php');
	require('includes/validateEmail.inc.php');
	
	if($action == "first"){
		$ERR = "ERR_".CheckFirstRegData();
		
		/* 
			**********************************************************************
			Check for VALID E-MAIL ADDRRESS using Shane Gibson's validateEmail ()
		   function: see the validateEmail.php file included in the distribution
		   for details.
		   **********************************************************************
		   PLEASE NOTE: 
		   	USING THIS FUNCTION MAY SLOW DOWN THE THE REGISTRATION PROCESS
		   	SINCE IT PERFORMS A CONNECTION OVER THE NET VERIFY THE E-MAIL
		   	ADDRESS YOU PASS TO IT.
		   	TEST IT TO EVALUATE THE PERFORMANCE BEFORE INCLUDING IT.
		   	Simply comment these lines if you don't want to use it.
		   **********************************************************************
		*/
	/*	
		if(!$$ERR){
			$is_valid = validateEmail($email);
		
			//-- $is_valid[0] == false means the e-mail address is not valid.
			//-- If you want a more deteiled check see the validateEmail.php
			//-- file for the return codes and their meanings.

			if(!$is_valid[0]){
				$ERR = "ERR_028";
			}
		}
*/
		//**********************************************************************
				
	}

	if($action == "second"){
		$ERR = "ERR_".CheckOtherRegData();
	}
	
   
	
</SCRIPT>

<HTML>
<HEAD>
<TITLE></TITLE>



<SCRIPT Language=Javascript>

function SubmitForm(){
	document.user_data.submit();
}


function ResetForm(){
	document.user_data.reset();
}

</SCRIPT>

</HEAD>

<BODY  BGCOLOR="#FFFFFF" TEXT="#000000" LINK="#000000" VLINK="#000000" ALINK="#000000">

<SCRIPT Language=PHP>

require("header.php");


	print "<FONT FACE=\"Verdana,Helvetica,Arial\" SIZE=\"4\">";
 	print "<BR><CENTER><B>$MSG_182</B></CENTER>";

	if(!$action || $$ERR){
	
		//-- Get user's data
		
		$query = "select * from ".$dbfix."_users where nick=\"$user\"";
		$result = mysql_query($query);

		$password 	= mysql_result($result,0,"password");
		$name 		= mysql_result($result,0,"name");
		$address 	= mysql_result($result,0,"address");
		$city 		= mysql_result($result,0,"city");
		$prov 		= mysql_result($result,0,"prov");
		$country 	= mysql_result($result,0,"country");
		$zip 			= mysql_result($result,0,"zip");
		$phone 		= mysql_result($result,0,"phone");
		$email 		= mysql_result($result,0,"email");
		
		
		print "<FORM NAME=\"user_data\" ACTION=\"user_data.php\" METHOD=POST>";
		
		print "<CENTER>";
		print "<TABLE WIDTH=\"500\" CELLPADDING=\"3\" CELLSPACING=\"0\" BORDER=\"0\">";
		//--Display message & error (if any)
		print "<TR>";
	  	print "<TD WIDTH=\"20%\"> </TD>";
		print "<TD WIDTH=\"60%\">";

	  	if($$ERR){
	  		print "<FONT FACE=\"Verdana,Helvetica,Arial\" SIZE=\"2\" COLOR=\"red\"><CENTER>";
	  		print $$ERR;
	  		print "</CENTER></FONT>";
	  	}
	  	print "<BR>";
	   print "</TD>";
		print "<TD  WIDTH=\"20%\">";
		print "</TD >";
		print "</TR>\n";
		

		print "<TR> ";
	  	print "<TD WIDTH=\"204\" VALIGN=\"top\" ALIGN=\"right\">"; 
  		print "<FONT FACE=\"Verdana,Helvetica,Arial\"  SIZE=\"2\">";
  		print "<B>$MSG_002</B>";
  		print "</FONT>";
  		print "</TD>";
  		print "<TD WIDTH=\"486\">";
  		print "<INPUT TYPE=text NAME=name SIZE=30 VALUE=\"$name\">";
  		print "</TD>";
		print "</TR>";
		
		print "<TR> ";
  		print "<TD WIDTH=\"204\" VALIGN=\"top\" ALIGN=\"right\">"; 
  		print "<FONT FACE=\"Verdana,Helvetica,Arial\" SIZE=\"2\">";
  		print "<B>$MSG_009</B>";
  		print "</FONT>";
  		print "</TD>";
  		print "<TD WIDTH=\"486\">";
  		print "<INPUT TYPE=text NAME=address SIZE=40 VALUE=\"$address\">";
  		print "</TD>";
		print "</TR>";
		
		print "<TR> ";
		print "<TD WIDTH=\"204\" VALIGN=\"top\" ALIGN=\"right\">"; 
		print "<FONT FACE=\"Verdana,Helvetica,Arial\" SIZE=\"2\">";
		print "<B>$MSG_010</B>";
		print "</FONT>";
		print "</TD>";
		print "<TD WIDTH=\"486\">";
		print "<INPUT TYPE=text NAME=city SIZE=30  VALUE=\"$city\">";
		print "</TD>";
		print "</TR>";
		
		print "<TR>"; 
  		print "<TD WIDTH=\"204\" VALIGN=\"top\" ALIGN=\"right\">"; 
   	print "<FONT FACE=\"Verdana,Helvetica,Arial\" SIZE=\"2\">";
   	print "<B>$MSG_011</B>";
   	print "</FONT>";
  		print "</TD>";
  		print "<TD WIDTH=\"486\">";
  		print "<INPUT TYPE=text NAME=prov SIZE=10 VALUE=\"$prov\">";
  		print "</TD>";
		print "</TR>";
		
		print "<TR> ";
  		print "<TD WIDTH=\"204\" VALIGN=\"top\" ALIGN=\"right\">";
   	print "<FONT FACE=\"Verdana,Helvetica,Arial\" SIZE=\"2\">";
   	print "<B>$MSG_014</B>";
   	print "</FONT>";
  		print "</TD>";
  		print "<TD WIDTH=\"486\">";
   	print "<SELECT NAME=country>";
		print "<OPTION VALUE=\"\">";
		print $MSG_015;
		print "</OPTION>";

   	
   	$query = "select * from ".$dbfix."_countries order by name";
   	$result = mysql_query($query);
   	if(!$result){
   		print $ERR_001;
   		exit;
   	}
		
		$num_countries = mysql_num_rows($result);
		$i = 0;
		while($i < $num_countries){
			$country_code = mysql_result($result,$i,"code");
			$contry_name  = mysql_result($result,$i,"name");
			print "<OPTION VALUE=\"$country_code\"";
			if($country == $country_code){
				print "SELECTED";
			}
			print ">";
			print "$contry_name";
			print "</OPTION>\n";
			$i++;
		}
		
		print "</SELECT>";
  		print "</TD>";
		print "</TR>";

		print "<TR> ";
  		print "<TD WIDTH=\"204\" VALIGN=\"top\" ALIGN=\"right\">"; 
   	print "<FONT FACE=\"Verdana,Helvetica,Arial\" SIZE=\"2\">";
   	print "<B>$MSG_012</B>";
		print "</FONT>";
  		print "</TD>";
  		print "<TD WIDTH=\"486\">";
  		print "<INPUT TYPE=text NAME=zip SIZE=8 MAXLENGTH=8 VALUE=\"$zip\">";
  		print "</TD>";
		print "</TR>";

		print "<TR> ";
  		print "<TD WIDTH=\"204\" VALIGN=\"top\" ALIGN=\"right\"> ";
   	print "<FONT FACE=\"Verdana,Helvetica,Arial\" SIZE=\"2\">";
   	print "<B>$MSG_013</B>";
   	print "</FONT>";
  		print "</TD>";
  		print "<TD WIDTH=\"486\">";
  		print "<INPUT TYPE=text NAME=phone SIZE=30 VALUE=\"$phone\">";
  		print "</TD>";
		print "</TR>";
		
		print "<TR>";
  		print "<TD WIDTH=\"204\"  VALIGN=\"top\" ALIGN=\"right\">";
   	print "<FONT FACE=\"Verdana,Helvetica,Arial\" SIZE=\"2\">";
   	print "<B>$MSG_006</B>";
   	print "</FONT>";
  		print "</TD>";
  		print "<TD WIDTH=\"486\">";
  		print "<INPUT TYPE=text NAME=email SIZE=30 VALUE=\"$email\">";
  		print "</TD>";
		print "</TR>		";
		
		print "<TR>";
  		print "<TD WIDTH=\"204\">&nbsp;</TD>";
  		print "<TD WIDTH=\"486\">";
   	print "<FONT FACE=\"Verdana,Helvetica,Arial\" SIZE=\"2\">";
   	print $MSG_097;
   	print "</FONT>";
		print "<BR><BR>";
		print "<INPUT TYPE=hidden NAME=action VALUE=\"second\">";
		print "<INPUT TYPE=hidden NAME=user VALUE=\"$user\">";
	   print "<A HREF=\"javascript:SubmitForm()\"><IMG SRC=\"./images/bt_enviar_datos.gif\" BORDER=\"0\"></A> ";
   	print "<A HREF=\"javascript:ResetForm()\"><IMG SRC=\"./images/bt_borrar.gif\" BORDER=\"0\"></A>";
  		print "</TD>";
		print "</TR>";
		
		print "</TABLE>";
		print "</FORM>";
		
	  }



	if($action && !$$ERR){
	
		//-- update USERS table
		
		$query = "update ".$dbfix."_users set name=\"$name\",
										   address=\"$address\", 
										   city=\"$city\", 
										   prov=\"$prov\", 
										   country=\"$country\", 
										   zip=\"$zip\", 
										   phone=\"$phone\", 
										   email=\"$email\"
										   where nick=\"$user\"";
		$result = mysql_query($query);

		
		print "<CENTER>";
		print "<TABLE WIDTH=\"500\" CELLPADDING=\"3\" CELLSPACING=\"0\" BORDER=\"0\">";
		//--Display message & error (if any)
		print "<TR>";
	  	print "<TD WIDTH=\"5%\" COLSPAN=\"3\">";
	  	print "<FONT FACE=\"Verdana,Helvetica,Arial\" SIZE=\"2\"><CENTER><BR>";
  		print $MSG_183;
  		print "</FONT></CENTER><BR>";
  		print "</TD>";
		print "</TR>\n";
		

		print "<TR> ";
	  	print "<TD WIDTH=\"204\" VALIGN=\"top\" ALIGN=\"right\">"; 
  		print "<FONT FACE=\"Verdana,Helvetica,Arial\"  SIZE=\"2\">";
  		print "<B>$MSG_002</B>";
  		print "</FONT>";
  		print "</TD>";
  		print "<TD WIDTH=\"486\">";
  		print "<FONT FACE=\"Verdana,Helvetica,Arial\"  SIZE=\"2\">";
  		print $name;
  		print "</TD>";
		print "</TR>";
		
		print "<TR> ";
  		print "<TD WIDTH=\"204\" VALIGN=\"top\" ALIGN=\"right\">"; 
  		print "<FONT FACE=\"Verdana,Helvetica,Arial\" SIZE=\"2\">";
  		print "<B>$MSG_009</B>";
  		print "</FONT>";
  		print "</TD>";
  		print "<TD WIDTH=\"486\">";
  		print "<FONT FACE=\"Verdana,Helvetica,Arial\"  SIZE=\"2\">";
  		print $address;
  		print "</TD>";
		print "</TR>";
		
		print "<TR> ";
		print "<TD WIDTH=\"204\" VALIGN=\"top\" ALIGN=\"right\">"; 
		print "<FONT FACE=\"Verdana,Helvetica,Arial\" SIZE=\"2\">";
		print "<B>$MSG_010</B>";
		print "</FONT>";
		print "</TD>";
		print "<TD WIDTH=\"486\">";
  		print "<FONT FACE=\"Verdana,Helvetica,Arial\"  SIZE=\"2\">";
		print $city;
		print "</TD>";
		print "</TR>";
		
		print "<TR>"; 
  		print "<TD WIDTH=\"204\" VALIGN=\"top\" ALIGN=\"right\">"; 
   	print "<FONT FACE=\"Verdana,Helvetica,Arial\" SIZE=\"2\">";
   	print "<B>$MSG_011</B>";
   	print "</FONT>";
  		print "</TD>";
  		print "<TD WIDTH=\"486\">";
  		print "<FONT FACE=\"Verdana,Helvetica,Arial\"  SIZE=\"2\">";
  		print $prov;
  		print "</TD>";
		print "</TR>";
		
		print "<TR> ";
  		print "<TD WIDTH=\"204\" VALIGN=\"top\" ALIGN=\"right\">";
   	print "<FONT FACE=\"Verdana,Helvetica,Arial\" SIZE=\"2\">";
   	print "<B>$MSG_014</B>";
   	print "</FONT>";
  		print "</TD>";
  		print "<TD WIDTH=\"486\">";

   	$query = "select name from ".$dbfix."_countries where code>=\"$country\"";
   	$result = mysql_query($query);
   	
   	$country_name = mysql_result($result,0,"name");
   	print "<FONT FACE=\"Verdana,Helvetica,Arial\" SIZE=\"2\">";
   	print $country_name;
   	
  		print "</TD>";
		print "</TR>";

		print "<TR> ";
  		print "<TD WIDTH=\"204\" VALIGN=\"top\" ALIGN=\"right\">"; 
   	print "<FONT FACE=\"Verdana,Helvetica,Arial\" SIZE=\"2\">";
   	print "<B>$MSG_012</B>";
		print "</FONT>";
  		print "</TD>";
  		print "<TD WIDTH=\"486\">";
  		print $zip;
  		print "</TD>";
		print "</TR>";

		print "<TR> ";
  		print "<TD WIDTH=\"204\" VALIGN=\"top\" ALIGN=\"right\"> ";
   	print "<FONT FACE=\"Verdana,Helvetica,Arial\" SIZE=\"2\">";
   	print "<B>$MSG_013</B>";
   	print "</FONT>";
  		print "</TD>";
  		print "<TD WIDTH=\"486\">";
  		print "<FONT FACE=\"Verdana,Helvetica,Arial\"  SIZE=\"2\">";
  		print $phone;
  		print "</TD>";
		print "</TR>";
		
		print "<TR>";
  		print "<TD WIDTH=\"204\"  VALIGN=\"top\" ALIGN=\"right\">";
   	print "<FONT FACE=\"Verdana,Helvetica,Arial\" SIZE=\"2\">";
   	print "<B>$MSG_006</B>";
   	print "</FONT>";
  		print "</TD>";
  		print "<TD WIDTH=\"486\">";
  		print "<FONT FACE=\"Verdana,Helvetica,Arial\"  SIZE=\"2\">";
  		print $email;
  		print "</TD>";
		print "</TR>";
		
		print "</TABLE>";
		print "</FORM>";										   
										   
		
	}

</SCRIPT>
<CENTER>
<FONT FACE="Verdana,Helvetica,Arial" SIZE="2">
<A HREF="./user_menu.php?user=$user"><? print $MSG_205; ?></A>

<? require("./footer.php"); ?>
</BODY>
</HTML>
