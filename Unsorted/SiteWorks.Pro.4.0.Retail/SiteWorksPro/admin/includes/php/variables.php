<?php
	
		/*
			Filename: variables.php
			Desc: Declares all variables which will be used globally
			      throughout the app
		*/
		
		if(!is_numeric(strpos($_SERVER["PHP_SELF"], "admin")))
			require_once("admin/config.php");
		else
			require_once("config.php");
		
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