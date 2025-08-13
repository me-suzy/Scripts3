<SCRIPT Language=PHP>

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


	// Include messages file	
   require('./includes/messages.inc.php');
  
   // Connect to sql server & inizialize configuration variables
   require('./includes/config.inc.php');
   


require("header.php");

        if (!$topic) { $topic = 'General'; }
        $query = "select helptext from PHPAUCTION_help where topic = '" . $topic . "';";
	$result = mysql_query($query);
	if (!$result){
		print "$err_font $ERR_001 </font> <br>";
		require("./footer.php");
		exit;
	}
        if (mysql_num_rows($result)) {
		$TPL_helptext = mysql_result($result,0,"helptext");
	} else { 	
		$TPL_helptext = $ERR_116;
	}
        $TPL_topic = $topic;


        $query = "select topic from PHPAUCTION_help order by topic;";
	$result = mysql_query($query);
	if (!$result){
		print "$err_font $ERR_001 </font> <br>";
		require("./footer.php");
		exit;
	}
        if (mysql_num_rows($result)) {
		$TPL_otherhelp = "<b>" . $MSG_918 . "</b><br>";
                $num_topics = mysql_num_rows($result);
                $i = 0;
                while($i < $num_topics){
		
			$TPL_otherhelp .= "<a href=\"help.php?topic=";
                        $TPL_otherhelp .= mysql_result($result,$i,"topic");
                        $TPL_otherhelp .= "\">";
                        $TPL_otherhelp .= mysql_result($result,$i,"topic");
                        $TPL_otherhelp .= "<br>";
			$i++;
		}
	} else { 	
		$TPL_otherhelp = "";
	}

        include "templates/template_view_help_php.html";

</SCRIPT>

<? require("./footer.php"); ?>
</BODY>
</HTML>
