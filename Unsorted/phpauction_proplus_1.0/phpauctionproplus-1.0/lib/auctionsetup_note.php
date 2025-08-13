<?#//v.1.0.0
#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////

	if($SETTINGS[sellersetupfee] == 1)
	{
		if($SETTINGS[sellersetuptype] == 1)
		{
			print "<BR><FONT SIZE=2>".$MSG_425."&nbsp;".$SETTINGS[sellersetupvalue].$MSG_426;
		}
		else
		{
			print "<BR><FONT SIZE=2>".$MSG_427."&nbsp;".print_money($SETTINGS[sellersetupvalue]).$MSG_428;
		}
	}
?>