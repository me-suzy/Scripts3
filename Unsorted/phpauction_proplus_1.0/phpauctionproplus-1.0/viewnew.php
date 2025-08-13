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
   

	
?>

<HTML>
<HEAD>
<TITLE></TITLE>


</HEAD>

<BODY  BGCOLOR="#FFFFFF" TEXT="#08428C" LINK="#08428C" VLINK="#08428C" ALINK="#08428C">

<?

require("header.php");

$query = "SELECT * from PHPAUCTIONPROPLUS_news where id='$id'";
$res = mysql_query($query);
if(!$res)
{
	MySQLError($query);
	exit;
}
$new = mysql_fetch_array($res);

include "templates/template_view_new_php.html";

?>

<? require("./footer.php"); ?>
</BODY>
</HTML>
