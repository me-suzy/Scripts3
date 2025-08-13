<?#//v.1.0.0
#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////


	// Include messages file	
   require('./includes/messages.inc.php');
  
   // Connect to sql server & inizialize configuration variables
   require('./includes/config.inc.php');
   


require("header.php");

        if (!$topic) { $topic = 'General'; }
        $query = "select helptext from PHPAUCTIONPROPLUS_help where topic = '" . $topic . "';";
	$result = mysql_query($query);
	if (!$result)
	{
		MySQLError($query);
		exit;
	}
        if (mysql_num_rows($result)) {
		$TPL_helptext = mysql_result($result,0,"helptext");
	} else { 	
		$TPL_helptext = $ERR_116;
	}
        $TPL_topic = $topic;


        $query = "select topic from PHPAUCTIONPROPLUS_help order by topic;";
	$result = mysql_query($query);
	if (!$result)
	{
		MySQLError($query);
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

?>

<? require("./footer.php"); ?>
</BODY>
</HTML>
