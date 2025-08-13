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
	include_once(realpath("includes/php/variables.php"));

	/*
		Page: security.php
		Desc: Provides functions related to security including
		      making sure the user is logged in, appending to the
		      log file, viewing the log, etc.
	*/
	
	function AppendToLog($strAppend = "")
		{
			/*
				Function Name: AppendToLog()
				Paramaters: [strAppend: Text to append to log file]
				Desc: Adds a new record to the tbl_Logs table
			*/
				
				$dbVars = new dbVars();
				@$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);

				if($svrConn)
				{
					// Connected to the database server OK
					$dbConn = mysql_select_db($dbVars->strDb, $svrConn);
								
					if($dbConn)
					{
						// Connected to the database OK
						mysql_query("insert into tbl_Logs values(0, '$strAppend')");
					}
					else
					{
						// Failed
						die("Couldn't write log data to the MySQL database.");
					}
				}
				else
				{
					// Failed
					die("Couldn't write log data to the MySQL database.");
				}
		}
		
	function StoreSessionData($strUserId = 0, $strPass = "", $strFName = "", $strLName = "", $intSec = 0)
		{
			/*
				Function Name: StoreSessionData()
				Paramaters: [strUserId: The user id of the current user
				             strPass: The password of the current user
				             strFName: The first name of the current user
				             strLName: The last name of the current user
				            ]
				Desc: Stores the details of a new session with the parsed user credentials.
				      This way, if we need load-balancing, there will be no trouble with
				      cookies or session variables.
			*/
			
				session_start();
				
				$dbVars = new dbVars();
				@$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);
						
				if($svrConn)
					{
						$dbConn = mysql_select_db($dbVars->strDb, $svrConn);
								
						if($dbConn)
							{
									$strQuery  = "insert into tbl_AdminSessions ";
									$strQuery .= "values(0, '" . session_id() . "', '$strUserId', '$strPass', '$strFName', '$strLName', $intSec)";
									$result = mysql_query($strQuery);
									
									if($result)
										{ return true; }
									else
										{ return false; }							
							}
						else
							{
								// Couldnt connect to database
								return false;							
							}
					}
				else
					{
						// Couldnt connect to server
						return false;
					}
		}
			
	function IsLoggedIn()
		{
			/*
				Function Name: IsLoggedIn()
				Paramaters: N/A
				Desc: Checks if the current user has a session id. If the session id
					  is valid, then true is returned. If not false is returned.
			*/
			
			error_reporting(0);
			
			session_start();

			// Check that the session id is valid
			$dbVars = new dbVars();
					
			$svrConn = @mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);
					
			if($svrConn)
				{
					$dbConn = @mysql_select_db($dbVars->strDb, $svrConn);
					
					if($dbConn)
						{
							$strQuery  = "select * from tbl_AdminSessions ";
							$strQuery .= "where asSessId = '" . session_id() . "'";
							
							$results = @mysql_query($strQuery);							
														
							if($result = @mysql_fetch_array($results))
								{ return $result["asSec"]; }
							else
								{ return false; }
						}
					else
						{ return false; }
				}
			else
				{ return false; }
			
		}		
		
	class usrCredentials
		{
			/*
				This class will return the details of the current user an
				index array from zero to three, with the following:
				
					[0]: Current users userid
					[1]: Current users password
					[2]: Currentusers first name
					[3]: Current users last name
			*/
			
			var $strUserId;
			var $strPass;
			var $strFName;
			var $strLName;
			
			function GetData()
				{
			
					session_start();
		
					// Check that the session id is valid
					$dbVars = new dbVars();
							
					@$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);					
					if($svrConn)
						{
							$dbConn = mysql_select_db($dbVars->strDb, $svrConn);
							
							if($dbConn)
								{
									$strQuery  = "select * from tbl_AdminSessions ";
									$strQuery .= "where asSessId = '" . session_id() . "'";
									
									$results = mysql_query($strQuery);							
																
									if($result = mysql_fetch_array($results))
										{
											$arrRet[] = $result["asUName"];
											$arrRet[] = $result["asPass"];
											$arrRet[] = $result["asFName"];
											$arrRet[] = $result["asLName"];											
										}
									else
										{
											$arrRet[] = "";
											$arrRet[] = "";
											$arrRet[] = "";
											$arrRet[] = "";
										}
								}
							else
								{
									$arrRet[] = "";
									$arrRet[] = "";
									$arrRet[] = "";
									$arrRet[] = "";
								}
						}
					else
						{
							$arrRet[] = "";
							$arrRet[] = "";
							$arrRet[] = "";
							$arrRet[] = "";
						}
						
					return $arrRet;
				}

			
		}
		
		
	include(realpath("includes/php/permissions.php"));

?>