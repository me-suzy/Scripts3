<?#//v.1.0.0
	include "loggedin.inc.php";

#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////
	
	include "../includes/config.inc.php";
	include "../includes/messages.inc.php";
	
	
	$query = "delete from PHPAUCTIONPROPLUS_categories";
	$result = @mysql_query($query);
	if(!$result){
		print $ERR_001;
		exit;
	}
	$buffer = file("./categories.txt");
	$count_cat  = 0;
	$counter    = 0;
	$id		    = 0;
	$actuals[0] = 0;
	while(!ereg("^1@(.)*$",$buffer[$counter])){
	  $counter++;
	}

	while($counter < count($buffer))
	{
		$category    = explode("@", $buffer[$counter]);
		$category[1] = ereg_replace(10,"",$category[1]);
		$category[1] = ereg_replace(13,"",$category[1]);
		$id++;;
		if($category[0] != $actual){
			$actual = $category[0];
		}
		$actuals[$actual]	= $id;
		$father = $actuals[$actual - 1];
		$query = "insert into PHPAUCTIONPROPLUS_categories (cat_id, parent_id, cat_name, deleted, sub_counter, counter) values($id,$father,\"$category[1]\",0,0,0)";
		$result = mysql_query($query);
		if(!$result)
		{
				print $ERR_001;
				print "<BR>$query - $actual";
				exit;
		}

		$counter++;
		$count_cat++;
	}

	include "util_cc1.php";
	include "util_cc2.php";
	
	require("./header.php");
	require('../includes/styles.inc.php'); 
?>


<SCRIPT Language=Javascript>

function window_open(pagina,titulo,ancho,largo,x,y){

  var Ventana= 'toolbar=0,location=0,directories=0,scrollbars=1,screenX='+x+',screenY='+y+',status=0,menubar=0,resizable=0,width='+ancho+',height='+largo;
  open(pagina,titulo,Ventana);

}

</SCRIPT>
<TABLE BORDER=0 WIDTH=100% CELLPADDING=0 CELLSPACING=0 BGCOLOR="#FFFFFF">
<TR>
		<TD ALIGN=CENTER><BR>
<FORM NAME=conf ACTION=<?=basename($PHP_SELF)?> METHOD=POST>
				<TABLE WIDTH="650" BORDER="0" CELLSPACING="0" CELLPADDING="1" BGCOLOR="#296FAB">
					<TR> 
						<TD ALIGN=CENTER><FONT COLOR=#FFFFFF FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="4"><B>
							<? print $MSG_369; ?>
							</B></FONT></TD>
					</TR>
					<TR> 
						<TD> 
							<TABLE WIDTH=100% CELLPADDING=2 BGCOLOR="#FFFFFF">
								<TR> 
									<TD WIDTH=50 HEIGHT="21">&nbsp;</TD>
									<TD COLSPAN=4 HEIGHT="21"> <FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2"> 
										<?=$MSG_407?>
										</FONT> </TD>
								</TR>
								<TR> 
									<TD WIDTH=50 HEIGHT="22"></TD>
									<TD WIDTH="302" HEIGHT="22">&nbsp; </TD>
								</TR>
								<TR> 
									<TD WIDTH=50></TD>
									<TD WIDTH="302"> </TD>
								</TR>
							</TABLE>
						</TD>
					</TR>
				</TABLE>
				</FORM>
<BR><BR>

<FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2">
<A HREF="admin.php" CLASS="links">Admin Home</A>
</FONT>
<BR><BR>

</TD>
</TR>
</TABLE>


<? require("./footer.php"); ?>
