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
   

	
</SCRIPT>

<HTML>
<HEAD>
<TITLE></TITLE>


</HEAD>

<BODY  BGCOLOR="#FFFFFF" TEXT="#000000" LINK="#000000" VLINK="#000000" ALINK="#000000">

<SCRIPT Language=PHP>

require("header.php");

        if (!$topic) { $topic = 'General'; }
        $query = "select newstext from ".$dbfix."_news where topic = '" . $topic . "';";
	$result = mysql_query($query);
	if (!$result){
		print "$err_font $ERR_001 </font> <br>";
		require("./footer.php");
		exit;
	}
        if (mysql_num_rows($result)) {
		$TPL_helptext = mysql_result($result,0,"newstext");
	} else { 	
		$TPL_helptext = $ERR_116a;
	}
        $TPL_topic = $topic;


        $query = "select topic from ".$dbfix."_news order by topic;";
	$result = mysql_query($query);
	if (!$result){
		print "$err_font $ERR_001 </font> <br>";
		require("./footer.php");
		exit;
	}
        if (mysql_num_rows($result)) {
		$TPL_otherhelp = "<b>" . $MSG_918a . "</b><br>";
                $num_topics = mysql_num_rows($result);
                $i = 0;
                while($i < $num_topics){
		
			$TPL_otherhelp .= "<a href=\"news.php?topic=";
                        $TPL_otherhelp .= mysql_result($result,$i,"topic");
                        $TPL_otherhelp .= "\">";
                        $TPL_otherhelp .= mysql_result($result,$i,"topic");
                        $TPL_otherhelp .= "<br>";
			$i++;
		}
	} else { 	
		$TPL_otherhelp = "";
	}

        include "templates/view_newsI_php3.html";

</SCRIPT>

<? require("./footer.php"); ?>
</BODY>
</HTML>
