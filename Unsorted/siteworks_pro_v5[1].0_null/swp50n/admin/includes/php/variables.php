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
		if(!is_numeric(strpos($_SERVER["PHP_SELF"], "admin")))
			include(realpath("conf.php"));
		else
			include(realpath("../conf.php"));

		

		/*
			Filename: variables.php
			Desc: Declares all variables which will be used globally
			      throughout the app
		*/

		if(!is_numeric(strpos($_SERVER["PHP_SELF"], $admindir)))
			require_once(realpath("$admindir/config.php"));
		else
			require_once(realpath("config.php"));
		
		class dbVars
			{
				var $strUser;
				var $strPass;
				var $strServer;
				var $strDb;
				
				// Constructor
				function dbVars()
				{
					global $dbUser, $dbPass, $dbServer, $dbDatabase;
					
					$this->strUser = $dbUser;
					$this->strPass = $dbPass;
					$this->strServer = $dbServer;
					$this->strDb = $dbDatabase;
				}
				
			}
	?>