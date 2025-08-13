<?#//v.1.0.0
	
#///////////////////////////////////////////////////////
#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
#//  For Source code for the GPL version go to        //  
#//  http://phpauction.org and download               //
#//  Supplied by CyKuH [WTN]                          //
#///////////////////////////////////////////////////////
	
	
	
	
	#// Errors handling functions
	
	Function MySQLError($Q)
	{
		global 	$SESSION_NAME,
				$SESSION_ERROR,
				$ERR_0001;
		
		$SESSION_ERROR = $ERR_001."<br>".$Q."<br>".mysql_error();
		
		session_name($SESSION_NAME);
		session_register("SESSION_ERROR");
		
		print "<SCRIPT LANGUAGE=Javascript>
			   document.location.href='error.php'
			   </SCRIPT>";
		
		return;
	}
?>