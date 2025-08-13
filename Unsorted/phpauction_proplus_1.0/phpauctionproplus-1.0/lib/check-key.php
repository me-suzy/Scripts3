<?#//v.1.0.0
#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////


	#//
	$SECRETKEY = "cantachetipassalapaura";
	
	#// Open Key file
	$F = @fopen("phpauction-key.key","r");
	if(!$F) exit;
	
	#// Read key from file
	$KEY =fgets($F, 33);
	$MD5 = md5($SECRETKEY.$HTTP_HOST);
	
	if($MD5 == $KEY)
	{
		exit;
	}
	else
	{
		print "$MD5<BR>$KEY";
		print "Mierda";
	}
	
	
	

?>
