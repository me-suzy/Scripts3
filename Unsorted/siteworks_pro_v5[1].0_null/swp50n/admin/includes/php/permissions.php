<?php
///////////////////////////////////////////////////////////////////////////////
//                                                                           //
//   Program Name         : SiteWorks Professional                           //
//   Release Version      : 5.0                                              //
//   Program Author       : SiteCubed Pty. Ltd.                              //
//   Supplied by          : CyKuH [WTN]                                      //
//   Nullified by         : CyKuH [WTN]                                      //
//   Packaged by          : WTN Team                                         //
//   Distribution         : via WebForum, ForumRU and associated file dumps  //
//                                                                           //
//                       WTN Team `2000 - `2002                              //
///////////////////////////////////////////////////////////////////////////////
	//load the permissions per user

	if(IsLoggedIn())
		{

			include_once(realpath("includes/php/variables.php"));
		
			$dtls = new usrCredentials;
		
			$ursDtls = $dtls->GetData();
		
			$strQuery = "SELECT alPermissions FROM tbl_AdminLogins WHERE alUserName = '{$ursDtls[0]}'";
		
			$dbVars = new dbVars();
					
			@$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);
								
			if($svrConn)
				{
					$dbConn = mysql_select_db($dbVars->strDb, $svrConn);
					
					if($dbConn)
						{
		
							if($query = mysql_query($strQuery))
								{
						
									$permissions = mysql_result($query, 0, 0);
						
									$publisherAccess = explode(",", $permissions);

								}
						}
				}
		}

?>