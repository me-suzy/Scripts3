<?

  

/*



   Copyright (c), 1999, 2000 - phpauction.org                  

   

   This program is free software; you can redistribute it and/or modify 

   it under the terms of the GNU General Public License as published by 

   the Free Software Foundation (version 2 or later).                                  

                                                                        

   This program is distributed in the hope that it will be useful,      

   but WITHOUT ANY WARRANTY; without even the implied warranty of       

   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        

   GNU General Public License for more details.                         

                                                                        

   You should have received a copy of the GNU General Public License    

   along with this program; if not, write to the Free Software          

   Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.

   

*/







//require("./config.inc.php");
require("./includes/messages.inc.php");



$buffer = file("./includes/usermail.inc.php");



$i = 0;

$j = 0;

while($i < count($buffer)){

	if(!ereg("^#(.)*$",$buffer[$i])){

		$skipped_buffer[$j] = $buffer[$i];

		$j++;

	}

	$i++;

}

//--Reteve message



$message = implode($skipped_buffer,"");



//--Change TAGS with variables content


		$message = ereg_replace("<#c_id#>",AddSlashes($TPL_id_hidden),$message);
		$message = ereg_replace("<#c_name#>",AddSlashes($TPL_name_hidden),$message);
		$message = ereg_replace("<#c_nick#>",AddSlashes($TPL_nick_hidden),$message);
		$message = ereg_replace("<#c_address#>",AddSlashes($TPL_address),$message);
		$message = ereg_replace("<#c_city#>",AddSlashes($TPL_city),$message);
		$message = ereg_replace("<#c_prov#>",AddSlashes($TPL_prov),$message);
		$message = ereg_replace("<#c_zip#>",AddSlashes($TPL_zip),$message);
		$message = ereg_replace("<#c_country#>",AddSlashes($countries[$TPL_country]),$message);
		$message = ereg_replace("<#c_phone#>",AddSlashes($TPL_phone),$message);
		$message = ereg_replace("<#c_email#>",AddSlashes($TPL_email_hidden),$message);
		$message = ereg_replace("<#c_password#>",AddSlashes($TPL_password_hidden),$message);
		$message = ereg_replace("<#c_sitename#>",$SETTINGS[sitename],$message);
		$message = ereg_replace("<#c_siteurl#>",$SETTINGS[siteurl],$message);
		$message = ereg_replace("<#c_adminemail#>",$adminEmail,$message);                    	

		mail($TPL_email_hidden,"$MSG_098",$message,"From:$SETTINGS[sitename] <$SETTINGS[adminmail]>\nReplyTo:$SETTINGS[adminmail]");

?>

